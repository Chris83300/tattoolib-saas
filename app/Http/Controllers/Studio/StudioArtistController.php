<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Http\Requests\Studio\InviteArtistRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudioArtistController extends Controller
{
    /**
     * Récupère le studio que l'utilisateur connecté POSSÈDE.
     */
    private function studio(): Studio
    {
        $studio = auth()->user()->studio;
        abort_unless($studio, 403, 'Profil studio non trouvé');
        return $studio;
    }

    public function artists()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->get();
        $pendingInvitations = $artists->whereNull('user_id');
        $activeArtists = $artists->whereNotNull('user_id');

        return view('studio.artists', [
            'studio'                        => $studio,
            'artists'                       => $artists,
            'activeArtists'                 => $activeArtists,
            'pendingInvitations'            => $pendingInvitations,
            'canAddArtist'                  => $studio->canAddArtist(),
            'needsSubscriptionForNewArtist' => $studio->needsSubscriptionForNewArtist(),
            'paidArtistCount'               => $studio->paidArtistCount(),
            'monthlyPrice'                  => $studio->monthlyPrice(),
        ]);
    }

    public function artistShow(StudioArtist $studioArtist)
    {
        $studio = $this->studio();
        abort_unless($studioArtist->studio_id === $studio->id, 403);

        $studioArtist->load('user');

        $tattooerIds = Tattooer::where('user_id', $studioArtist->user_id)->pluck('id');
        $piercerIds  = Piercer::where('user_id', $studioArtist->user_id)->pluck('id');

        $scopeQuery = function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        };

        // Toutes les demandes de l'artiste
        $allBookings = \App\Models\BookingRequest::where($scopeQuery)->get();

        // 10 dernières demandes avec client
        $requests = \App\Models\BookingRequest::where($scopeQuery)
            ->with('client')
            ->latest()
            ->limit(10)
            ->get();

        // Prochains RDV (confirmed_date ou appointment_datetime >= aujourd'hui)
        $upcomingAppointments = \App\Models\BookingRequest::where($scopeQuery)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->whereNotNull('confirmed_date')->where('confirmed_date', '>=', now()->toDateString());
                })->orWhere(function ($q2) {
                    $q2->whereNotNull('appointment_datetime')->where('appointment_datetime', '>=', now());
                });
            })
            ->whereNotIn('status', ['cancelled', 'rejected', 'expired', 'no_show', 'completed', 'fully_completed'])
            ->with('client')
            ->orderByRaw('COALESCE(confirmed_date, DATE(appointment_datetime)) ASC')
            ->limit(5)
            ->get();

        // Stats financières (total_deposit_amount en euros)
        $totalDeposits = $allBookings
            ->whereNotNull('deposit_paid_at')
            ->sum('total_deposit_amount');

        $completedStatuses = ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'];
        $totalRevenue = $allBookings
            ->whereIn('status', $completedStatuses)
            ->sum('total_deposit_amount');

        // Clients uniques
        $uniqueClientsCount = $allBookings->pluck('client_id')->unique()->filter()->count();

        $stats = [
            'total_requests'    => $allBookings->count(),
            'pending_requests'  => $allBookings->where('status', 'pending')->count(),
            'completed_requests' => $allBookings->whereIn('status', $completedStatuses)->count(),
            'unique_clients'    => $uniqueClientsCount,
            'total_deposits'    => round((float) $totalDeposits, 2),
            'total_revenue'     => round((float) $totalRevenue, 2),
        ];

        return view('studio.artist-show', [
            'studio'               => $studio,
            'studioArtist'         => $studioArtist,
            'requests'             => $requests,
            'upcomingAppointments' => $upcomingAppointments,
            'stats'                => $stats,
        ]);
    }

    public function createArtist()
    {
        $studio = $this->studio();

        if ($studio->needsSubscriptionForNewArtist()) {
            return redirect()->route('studio.subscribe')
                ->with('info', 'Votre essai inclut 1 artiste. Activez votre abonnement pour en ajouter davantage.');
        }

        abort_unless($studio->canAddArtist(), 403, 'Limite d\'artistes atteinte');

        return view('studio.artists-create', [
            'studio' => $studio,
        ]);
    }

    /**
     * Création directe d'un artiste par le studio
     */
    public function storeArtist(Request $request)
    {
        $studio = $this->studio();

        if ($studio->needsSubscriptionForNewArtist()) {
            return redirect()->route('studio.subscribe')
                ->with('info', 'Votre essai inclut 1 artiste. Activez votre abonnement pour en ajouter davantage.');
        }

        abort_unless($studio->canAddArtist(), 403);

        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:255',
            'email'        => [
                'required',
                'email',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $email = mb_strtolower(trim((string) $value));

                    if ($email === '') {
                        return;
                    }

                    $exists = User::query()
                        ->whereRaw('LOWER(email) = ?', [$email])
                        ->exists();

                    if ($exists) {
                        $fail("Le champ adresse email est déjà utilisé.");
                    }
                },
            ],
            'password'     => 'required|string|min:8',
            'artisan_type' => 'required|in:tattooer,piercer',
        ]);

        $validated = $validator->validate();

        $validated['email'] = mb_strtolower(trim($validated['email']));

        DB::transaction(function () use ($validated, $studio, &$user) {
            // Créer le User avec le mot de passe fourni
            $user = User::create([
                'name'              => $validated['name'],
                'email'             => $validated['email'],
                'password'          => bcrypt($validated['password']), // Utiliser le mot de passe fourni
                'email_verified_at' => now(),
                'role'              => $validated['artisan_type'] === 'piercer' ? 'pierceur' : 'tattooer',
            ]);

            // Assigner les rôles Spatie
            $role = $validated['artisan_type'] === 'piercer' ? 'pierceur' : 'tattooer';
            $user->assignRole($role);
            $user->assignRole('studio_artist');

            // `siret` est stocké sur 14 caractères (varchar(14)), donc on génère un identifiant 14 chiffres.
            // Unicité garantie par couple (studio_id, user_id).
            $artistSiret = sprintf('%07d%07d', (int) $studio->id, (int) $user->id);

            // Créer le profil artisan
            if ($validated['artisan_type'] === 'piercer') {
                Piercer::create([
                    'user_id'   => $user->id,
                    'studio_id' => $studio->id,
                    'siret'     => $artistSiret,
                    'name'      => $studio->name, // Nom du studio
                    'slug'      => Str::slug($user->name) . '-' . uniqid(),
                    'city'      => $studio->city,
                    'postal_code' => $studio->postal_code,
                    'current_plan'      => 'pro', // Plan Pro car inclus dans l'abonnement studio
                    'is_subscribed'     => true, // Abonnement actif via le studio
                    'upgraded_to_pro_at' => now(), // Date de l'upgrade
                ]);
            } else {
                Tattooer::create([
                    'user_id'   => $user->id,
                    'studio_id' => $studio->id,
                    'siret'     => $artistSiret,
                    'slug'      => Str::slug($user->name) . '-' . uniqid(),
                    'current_plan'      => 'pro', // Plan Pro car inclus dans l'abonnement studio
                    'is_subscribed'     => true, // Abonnement actif via le studio
                    'upgraded_to_pro_at' => now(), // Date de l'upgrade
                ]);
            }

            // Créer le lien StudioArtist
            StudioArtist::create([
                'studio_id'    => $studio->id,
                'user_id'      => $user->id,
                'artisan_type' => $validated['artisan_type'],
                'role'         => 'artist',
                'is_active'    => true,
                'joined_at'    => now()->toDateString(), // Utiliser seulement la date
                'artist_name'  => $user->name, // Utiliser le nom de l'utilisateur
                'slug'         => Str::slug($user->name), // Générer un slug à partir du nom
            ]);

            Mail::to($validated['email'])->send(new \App\Mail\StudioArtistCreatedMail(
                $studio,
                $validated['name'],
                $validated['email'],
                $validated['password'], // Utiliser le mot de passe fourni
                $validated['artisan_type']
            ));

            app(\App\Services\StudioBillingService::class)->updateArtistQuantity($studio);
        });

        return redirect()->route('studio.artists')
            ->with('success', "Artiste {$validated['name']} créé. Il peut maintenant se connecter avec le mot de passe que vous avez défini.");
    }

    /**
     * Invitation par email
     */
    public function inviteArtist(InviteArtistRequest $request)
    {
        $studio = $this->studio();

        if ($studio->needsSubscriptionForNewArtist()) {
            return redirect()->route('studio.subscribe')
                ->with('info', 'Votre essai inclut 1 artiste. Activez votre abonnement pour en ajouter davantage.');
        }

        abort_unless($studio->canAddArtist(), 403);

        $validated = $request->validated();

        $token = Str::uuid()->toString();

        StudioArtist::create([
            'studio_id'        => $studio->id,
            'user_id'          => null, // Pas encore de user
            'artisan_type'     => $validated['artisan_type'],
            'role'             => 'artist',
            'is_active'        => false,
            'invitation_token' => $token,
            'invitation_email' => $validated['email'],
            'invited_at'       => now(),
        ]);

        Mail::to($validated['email'])->send(new \App\Mail\StudioInvitationMail(
            $studio,
            $token,
            $validated['artisan_type'],
            $validated['email']
        ));

        return back()->with('success', "Invitation envoyée à {$validated['email']}");
    }

    public function removeArtist(StudioArtist $studioArtist)
    {
        abort_unless($studioArtist->studio_id === $this->studio()->id, 403);

        DB::transaction(function () use ($studioArtist) {
            $user = $studioArtist->user;
            $artisan = $user?->artisan(); // Tattooer ou Piercer

            // 1) Supprimer le lien StudioArtist
            $studioArtist->delete();

            // 2) Supprimer le profil artisan (tattooer/piercer) et ses médias
            if ($artisan) {
                // Médias liés (avatar, banner, etc.)
                $artisan->clearMediaCollection('avatar');
                $artisan->clearMediaCollection('banner');
                $artisan->clearMediaCollection('works');
                $artisan->clearMediaCollection('consents');
                $artisan->clearMediaCollection('portfolio');
                // Supprimer le modèle artisan
                $artisan->delete();
            }

            // 3) Supprimer l'utilisateur et ses rôles
            if ($user) {
                // Médias éventuels sur le User
                $user->clearMediaCollection('avatar');
                // Supprimer les rôles liés
                $user->roles()->detach();
                // Supprimer le User
                $user->delete();
            }

            // 4) Nettoyer les données liées (demandes, chats, consentements, traceability, etc.)
            // - Demandes de booking
            if ($artisan) {
                \App\Models\BookingRequest::where('bookable_type', get_class($artisan))
                    ->where('bookable_id', $artisan->id)
                    ->each(function ($request) {
                        // Médias liés à la demande
                        $request->clearMediaCollection('reference_images');
                        $request->clearMediaCollection('consent_documents');
                        // Consent forms
                        \App\Models\ClientConsentForm::where('booking_request_id', $request->id)->delete();
                        // Traceability records
                        \App\Models\TraceabilityRecord::where('booking_request_id', $request->id)->delete();
                        // Supprimer la demande
                        $request->delete();
                    });
            }

            // - Messages/Chats : si une table messages existe, on peut supprimer ceux envoyés par cet utilisateur
            // (à adapter selon ton implémentation)
            // \App\Models\Message::where('sender_id', $user->id ?? null)->delete();

            // - Autres tables liées éventuelles (reviews, etc.) à ajouter si nécessaire
        });

        app(\App\Services\StudioBillingService::class)->updateArtistQuantity($this->studio());

        return back()->with('success', 'Artiste et toutes ses données ont été supprimés du studio');
    }

    public function toggleArtist(StudioArtist $studioArtist)
    {
        abort_unless($studioArtist->studio_id === $this->studio()->id, 403);
        $studioArtist->update(['is_active' => !$studioArtist->is_active]);
        return back();
    }

    public function clients(\Illuminate\Http\Request $request)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        $clients = \App\Models\Client::whereHas('bookingRequests', function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds, $piercerIds) {
                $q2->where(function ($q3) use ($tattooerIds) {
                    $q3->where('bookable_type', 'App\\Models\\Tattooer')
                       ->whereIn('bookable_id', $tattooerIds);
                })->orWhere(function ($q3) use ($piercerIds) {
                    $q3->where('bookable_type', 'App\\Models\\Piercer')
                       ->whereIn('bookable_id', $piercerIds);
                });
            })
            // Seulement les clients ayant payé l'acompte
            ->whereNotNull('deposit_paid_at');
        })
        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($q2) use ($request) {
                $q2->where('first_name', 'LIKE', '%' . $request->search . '%')
                   ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                   ->orWhere('email', 'LIKE', '%' . $request->search . '%');
            });
        })
        ->with(['bookingRequests' => function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        }])
        ->latest()
        ->paginate(20);

        return view('studio.clients', [
            'studio'  => $studio,
            'clients' => $clients,
        ]);
    }

    public function clientShow(\App\Models\Client $client)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        // Vérifier que ce client a bien des demandes pour ce studio
        $hasAccess = $client->bookingRequests()->where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        })->exists();

        abort_unless($hasAccess, 403, 'Accès non autorisé');

        $requests = $client->bookingRequests()->where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        })->with('bookable.user')->latest()->get();

        // Calculer les statistiques du client
        $stats = (object) [
            'total_requests' => $requests->count(),
            'completed' => $requests->where('status', 'completed')->count(),
            'total_paid' => $requests->sum(function($request) {
                return (float) ($request->total_deposit_amount ?? 0);
            }),
            'total_appointments' => $requests->whereNotNull('confirmed_date')->count(),
        ];

        // Charger les données pour les onglets
        $consents = \App\Models\ClientConsentForm::whereIn('booking_request_id', $requests->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->keyBy('booking_request_id');

        $consentDocuments = $client->getMedia('consent_documents');
        $traceabilities = \App\Models\TraceabilityRecord::where('client_id', $client->id)
            ->where('studio_id', $studio->id)
            ->orderBy('procedure_date', 'desc')
            ->get();

        return view('studio.client-show', [
            'studio'           => $studio,
            'client'           => $client,
            'requests'         => $requests,
            'bookingRequests'  => $requests, // Pour compatibilité avec la vue
            'stats'            => $stats,
            'consents'         => $consents,
            'consentDocuments' => $consentDocuments,
            'traceabilities'   => $traceabilities,
        ]);
    }

    public function clientUpdate(\App\Models\Client $client, Request $request)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        // Vérifier que ce client a bien des demandes pour ce studio
        $hasAccess = $client->bookingRequests()->where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        })->exists();

        abort_unless($hasAccess, 403, 'Accès non autorisé');

        // Valider les données
        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'pseudo' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:2000',
        ]);

        // Mettre à jour le client
        $client->update($validated);

        // Si email fourni, mettre à jour l'utilisateur associé
        if (isset($validated['email']) && $client->user) {
            $client->user->update(['email' => $validated['email']]);
        }

        return redirect()->route('studio.clients.show', $client)
            ->with('success', 'Informations client mises à jour avec succès');
    }

    public function acceptInvitation(string $token)
    {
        $invitation = StudioArtist::where('invitation_token', $token)
            ->whereNull('user_id')
            ->firstOrFail();

        return view('studio.accept-invitation', [
            'invitation' => $invitation,
            'studio'     => $invitation->studio,
        ]);
    }

    public function processInvitation(Request $request, string $token)
    {
        $invitation = StudioArtist::where('invitation_token', $token)
            ->whereNull('user_id')
            ->firstOrFail();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $roleSlug = $invitation->artisan_type === 'piercer' ? 'pierceur' : 'tattooer';

        // Créer le user
        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'email_verified_at' => now(),
            'role'              => $roleSlug,
        ]);

        $user->assignRole($roleSlug);
        $user->assignRole('studio_artist');

        // Profil artisan
        $artisanModel = $invitation->artisan_type === 'piercer' ? Piercer::class : Tattooer::class;
        $artisanModel::create([
            'user_id'   => $user->id,
            'studio_id' => $invitation->studio_id,
        ]);

        // Lier l'invitation
        $invitation->update([
            'user_id'          => $user->id,
            'is_active'        => true,
            'joined_at'        => now(),
            'invitation_token' => null, // Token consommé
        ]);

        app(\App\Services\StudioBillingService::class)->updateArtistQuantity($invitation->studio);

        auth()->login($user);
        $redirect = $roleSlug === 'pierceur' ? 'pierceur.dashboard' : 'tattooer.dashboard';
        return redirect()->route($redirect)
            ->with('success', 'Bienvenue dans le studio ' . $invitation->studio->name . ' !');
    }
}

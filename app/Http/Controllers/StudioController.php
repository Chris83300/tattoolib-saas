<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Traits\HasAccountDeletion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudioController extends Controller
{
    use HasAccountDeletion;
    /**
     * Récupère le studio que l'utilisateur connecté POSSÈDE.
     */
    private function studio(): Studio
    {
        $studio = auth()->user()->studio;
        abort_unless($studio, 403, 'Profil studio non trouvé');
        return $studio;
    }

    // ═══ DASHBOARD ═══

    public function dashboard()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();

        // Récupérer les IDs des artisans du studio
        $artistUserIds = $artists->pluck('user_id')->filter();
        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        // Requête de base pour les demandes du studio
        $bookingBase = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        });

        $pendingCount   = (clone $bookingBase)->where('status', 'pending')->count();
        $confirmedCount = (clone $bookingBase)->whereIn('status', ['accepted', 'deposit_paid', 'date_confirmed'])->count();
        $completedCount = (clone $bookingBase)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->count();

        // Chiffre d'affaires du mois (acomptes encaissés — total_deposit_amount en euros)
        $monthlyRevenue = (clone $bookingBase)
            ->whereNotNull('deposit_paid_at')
            ->whereMonth('deposit_paid_at', now()->month)
            ->whereYear('deposit_paid_at', now()->year)
            ->sum('total_deposit_amount');

        // 5 dernières demandes en attente
        $latestRequests = (clone $bookingBase)
            ->with(['bookable.user', 'client'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('studio.dashboard', [
            'studio'         => $studio,
            'artists'        => $artists,
            'artistCount'    => $artists->count(),
            'monthlyPrice'   => $studio->monthlyPrice(),
            'totalArtists'   => $artists->count(),
            'activeArtists'  => $artists->count(),
            'totalRevenue'   => 0,
            // Nouveaux compteurs
            'pendingCount'   => $pendingCount,
            'confirmedCount' => $confirmedCount,
            'completedCount' => $completedCount,
            'monthlyRevenue' => $monthlyRevenue,
            'latestRequests' => $latestRequests,
        ]);
    }

    // ═══ SETTINGS ═══

    public function settings()
    {
        return view('studio.settings', [
            'studio' => $this->studio(),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $studio = $this->studio();

        $validated = $request->validate([
            'name'                   => 'required|string|max:255',
            'description'            => 'nullable|string|max:2000',
            'address'                => 'nullable|string|max:255',
            'city'                   => 'nullable|string|max:255',
            'postal_code'            => 'nullable|string|max:10',
            'phone'                  => 'nullable|string|max:20',
            'email'                  => 'nullable|email|max:255',
            'website'                => 'nullable|url|max:255',
            'siret'                  => 'nullable|string|size:14',
            'payment_mode'           => 'required|in:artist_direct,studio_managed',
            'artist_commission_rate' => 'nullable|numeric|min:0|max:99.99',
            'opening_hours'          => 'nullable|array',
            'social_media_links'     => 'nullable|array',
        ]);

        // En mode studio_managed, pas de commission artiste directe
        if ($validated['payment_mode'] === 'studio_managed') {
            $validated['artist_commission_rate'] = null;
        }

        $validated['slug'] = Str::slug($validated['name']);
        $studio->update($validated);

        if ($request->hasFile('logo')) {
            $studio->addMediaFromRequest('logo')->toMediaCollection('logo');
        }
        if ($request->hasFile('cover')) {
            $studio->addMediaFromRequest('cover')->toMediaCollection('cover');
        }
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $studio->addMedia($photo)->toMediaCollection('photos');
            }
        }

        return back()->with('success', 'Paramètres mis à jour');
    }

    /**
     * Initier le Stripe Connect du studio (mode studio_managed).
     * Crée ou reprend l'onboarding Express et redirige vers Stripe.
     */
    public function connectStripe(Request $request)
    {
        $studio = $this->studio();

        // Sécurité : uniquement si le mode le requiert
        if ($studio->payment_mode !== 'studio_managed') {
            return back()->with('info', 'Le Stripe Connect du studio n\'est utile qu\'en mode "Géré par le studio".');
        }

        try {
            $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

            // Créer le compte Connect s'il n'existe pas encore
            if (!$studio->stripe_account_id) {
                $account = $stripe->accounts->create([
                    'type'          => 'express',
                    'country'       => 'FR',
                    'email'         => $studio->stripeEmail(),
                    'capabilities'  => [
                        'card_payments' => ['requested' => true],
                        'transfers'     => ['requested' => true],
                    ],
                    'business_type' => 'company',
                    'metadata'      => [
                        'studio_id'    => $studio->id,
                        'user_id'      => auth()->id(),
                        'account_type' => 'studio_owner',
                    ],
                ]);

                $studio->update(['stripe_account_id' => $account->id]);

                Log::info('Compte Stripe Connect Studio créé', [
                    'studio_id'  => $studio->id,
                    'account_id' => $account->id,
                ]);
            }

            // Générer le lien d'onboarding
            $accountLink = $stripe->accountLinks->create([
                'account'     => $studio->stripe_account_id,
                'refresh_url' => route('studio.stripe.refresh'),
                'return_url'  => route('studio.stripe.return'),
                'type'        => 'account_onboarding',
            ]);

            return redirect($accountLink->url);
        } catch (\Exception $e) {
            Log::error('Erreur Stripe Connect Studio', [
                'studio_id' => $studio->id,
                'error'     => $e->getMessage(),
            ]);

            return back()->with('error', 'Impossible de connecter Stripe. Veuillez réessayer.');
        }
    }

    // ═══ ARTISTES ═══

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

    public function artistShow(\App\Models\StudioArtist $studioArtist)
    {
        $studio = $this->studio();
        abort_unless($studioArtist->studio_id === $studio->id, 403);

        $studioArtist->load('user');

        $tattooerIds = \App\Models\Tattooer::where('user_id', $studioArtist->user_id)->pluck('id');
        $piercerIds  = \App\Models\Piercer::where('user_id', $studioArtist->user_id)->pluck('id');

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
    public function inviteArtist(Request $request)
    {
        $studio = $this->studio();

        if ($studio->needsSubscriptionForNewArtist()) {
            return redirect()->route('studio.subscribe')
                ->with('info', 'Votre essai inclut 1 artiste. Activez votre abonnement pour en ajouter davantage.');
        }

        abort_unless($studio->canAddArtist(), 403);

        $validated = $request->validate([
            'email'        => 'required|email',
            'artisan_type' => 'required|in:tattooer,piercer',
        ]);

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

    // ═══ PLANNING ═══

    public function planning()
    {
        $studio = $this->studio();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();

        return view('studio.planning', [
            'studio'  => $studio,
            'artists' => $artists,
        ]);
    }

    /**
     * JSON : événements du planning pour FullCalendar
     */
    public function planningEvents(\Illuminate\Http\Request $request)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        $bookings = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        })
        ->where(function ($q) {
            $q->whereNotNull('confirmed_date')
              ->orWhereNotNull('appointment_datetime');
        })
        ->whereNotIn('status', ['cancelled', 'rejected', 'expired', 'no_show'])
        ->with(['bookable.user', 'client'])
        ->get();

        // Couleurs par artiste (hash simple)
        $colors = ['#8B7355', '#6B9E78', '#7B8FA1', '#A07850', '#9E6B6B', '#6B7F9E'];

        $events = $bookings->map(function ($booking) use ($colors) {
            $artistName = $booking->bookable?->user?->name ?? 'Artiste';
            $colorIndex = crc32($artistName) % count($colors);
            $color = $colors[abs($colorIndex)];

            // Utiliser confirmed_date en priorité, sinon appointment_datetime
            if ($booking->confirmed_date) {
                $date = $booking->confirmed_date instanceof \Carbon\Carbon
                    ? $booking->confirmed_date->toDateString()
                    : substr($booking->confirmed_date, 0, 10);

                $start = $booking->scheduled_start_time
                    ? $date . 'T' . $booking->scheduled_start_time
                    : $date;

                $end = $booking->scheduled_end_time
                    ? $date . 'T' . $booking->scheduled_end_time
                    : null;
            } else {
                // Fallback sur appointment_datetime
                $appt = $booking->appointment_datetime instanceof \Carbon\Carbon
                    ? $booking->appointment_datetime
                    : \Carbon\Carbon::parse($booking->appointment_datetime);

                $start = $appt->toIso8601String();
                $end = $booking->appointment_duration_minutes
                    ? $appt->copy()->addMinutes($booking->appointment_duration_minutes)->toIso8601String()
                    : $appt->copy()->addHours(2)->toIso8601String();
            }

            $clientName = trim(($booking->client?->first_name ?? '') . ' ' . ($booking->client?->last_name ?? ''));

            return [
                'id'              => $booking->id,
                'title'           => $clientName . ' → ' . $artistName,
                'start'           => $start,
                'end'             => $end,
                'backgroundColor' => $color,
                'borderColor'     => $color,
                'textColor'       => '#FFF8F0',
                'url'             => route('studio.demandes.show', $booking),
                'extendedProps'   => [
                    'artist'  => $artistName,
                    'client'  => $clientName,
                    'status'  => is_object($booking->status) ? $booking->status->value : $booking->status,
                    'type'    => $booking->bookable instanceof \App\Models\Piercer ? 'piercing' : 'tatouage',
                ],
            ];
        });

        return response()->json($events->values());
    }

    // ═══ DEMANDES ═══

    public function requests()
    {
        $studio = $this->studio();
        $artistIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        // Récupérer les IDs des profils artisan (tattooers + piercers)
        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistIds)->pluck('id');

        $requests = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')
                   ->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')
                   ->whereIn('bookable_id', $piercerIds);
            });
        })
        ->with(['bookable.user', 'client'])
        ->latest()
        ->paginate(20);

        return view('studio.requests', [
            'studio'   => $studio,
            'requests' => $requests,
        ]);
    }

    public function demandeShow(\App\Models\BookingRequest $bookingRequest)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        // Vérifier que cette demande appartient bien à un artiste du studio
        $allowed = (
            ($bookingRequest->bookable_type === 'App\\Models\\Tattooer' && $tattooerIds->contains($bookingRequest->bookable_id)) ||
            ($bookingRequest->bookable_type === 'App\\Models\\Piercer' && $piercerIds->contains($bookingRequest->bookable_id))
        );

        abort_unless($allowed, 403, 'Accès non autorisé');

        $bookingRequest->load(['bookable.user', 'client', 'messages.sender']);

        return view('studio.demande-show', [
            'studio'         => $studio,
            'bookingRequest' => $bookingRequest,
        ]);
    }

    // ═══ PROFIL PUBLIC ═══

    public function profile()
    {
        return view('studio.profile-edit', [
            'studio' => $this->studio(),
        ]);
    }

    public function publicProfile(string $slug)
    {
        $studio = Studio::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $artists = $studio->studioArtists()->with('user')->where('is_active', true)->get();

        return view('studio.public-profile', [
            'studio'  => $studio,
            'artists' => $artists,
        ]);
    }

    // ═══ BILLING ═══

    public function billing(Request $request)
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        // Retour de Stripe Checkout
        if ($request->get('checkout') === 'success') {
            $sessionId = $request->get('session_id');

            // Sync via session d'abord (plus précis), puis fallback syncFromStripe
            $synced = false;
            if ($sessionId) {
                $synced = $billingService->syncFromCheckoutSession($studio, $sessionId);
            }
            if (!$synced) {
                $synced = $billingService->syncFromStripe($studio);
            }

            // Terminer le trial si paiement effectué (syncFromCheckoutSession le fait aussi,
            // mais syncFromStripe n'a pas accès au payment_status — on refait ici par sécurité)
            if ($synced) {
                $billingService->endTrialImmediately($studio);
            }

            if ($synced) {
                return redirect()->route('studio.billing')
                    ->with('success', 'Abonnement activé avec succès ! Bienvenue sur le plan Studio.');
            }

            return redirect()->route('studio.billing')
                ->with('warning', "Le paiement semble avoir abouti mais l'abonnement n'est pas encore synchronisé. Cliquez sur « Synchroniser » dans quelques instants.");
        }

        if ($request->get('checkout') === 'cancel') {
            return redirect()->route('studio.billing')
                ->with('warning', 'Paiement annulé. Vous pouvez réessayer quand vous le souhaitez.');
        }

        $subscriptionInfo = $billingService->getSubscriptionInfo($studio);
        $isSubscribed     = $billingService->isSubscribed($studio);
        $portalUrl        = $billingService->billingPortalUrl($studio);

        $totalArtists = $studio->tattooers()->count() + $studio->piercers()->count();
        $includedArtists = (int) config('inkpik.pricing.studio.included_artists', 1);
        $extraArtists = max(0, $totalArtists - $includedArtists);
        $basePrice = \App\Enums\SubscriptionPlan::STUDIO->price();
        $extraPrice = \App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist();
        $totalPrice = $basePrice + ($extraArtists * $extraPrice);

        // Charger les subscription_items Cashier si abonnement actif
        $subscriptionItems = [];
        if ($isSubscribed && $studio->user && $studio->user->subscription('default')) {
            $sub = $studio->user->subscription('default');
            $studioPriceId = config('inkpik.pricing.studio.stripe_price_id');
            $extraPriceId = config('inkpik.pricing.studio.stripe_price_id_extra');
            $subscriptionItems = $sub->items()->with(['subscription'])->get()->map(function ($item) use ($studioPriceId, $extraPriceId) {
                $unitPrice = null;
                if ($item->stripe_price === $studioPriceId) {
                    $unitPrice = \App\Enums\SubscriptionPlan::STUDIO->price();
                } elseif ($item->stripe_price === $extraPriceId) {
                    $unitPrice = \App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist();
                }
                return [
                    'stripe_id'      => $item->stripe_id,
                    'stripe_product' => $item->stripe_product,
                    'stripe_price'   => $item->stripe_price,
                    'quantity'       => $item->quantity,
                    'unit_price'     => $unitPrice,
                ];
            })->toArray();
        }

        return view('studio.billing', compact(
            'studio',
            'subscriptionInfo',
            'isSubscribed',
            'portalUrl',
            'totalArtists',
            'includedArtists',
            'extraArtists',
            'basePrice',
            'extraPrice',
            'totalPrice',
            'subscriptionItems'
        ));
    }

    public function subscribe()
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        try {
            $checkoutUrl = $billingService->createCheckoutSession($studio);
            return redirect($checkoutUrl);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de la session de paiement : ' . $e->getMessage());
        }
    }

    public function cancelSubscription(Request $request)
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        $immediate = $request->boolean('immediate', false);
        $success = $immediate
            ? $billingService->cancelNow($studio)
            : $billingService->cancel($studio);

        if ($success) {
            $message = $immediate
                ? 'Abonnement annulé immédiatement. Vos prélèvements sont arrêtés.'
                : "Abonnement annulé. Vous conservez l'accès jusqu'à la fin de la période en cours.";
            return redirect()->route('studio.billing')->with('success', $message);
        }

        return back()->with('error', "Impossible d'annuler l'abonnement. Veuillez réessayer.");
    }

    public function resumeSubscription()
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        if ($billingService->resume($studio)) {
            return redirect()->route('studio.billing')->with('success', 'Abonnement réactivé avec succès !');
        }

        return back()->with('error', "Impossible de réactiver l'abonnement.");
    }

    public function syncSubscription()
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        if ($billingService->syncFromStripe($studio)) {
            return redirect()->route('studio.billing')->with('success', 'Abonnement synchronisé depuis Stripe.');
        }

        return back()->with('warning', 'Aucun abonnement actif trouvé dans Stripe.');
    }

    // ═══ SOUSCRIPTION (ancienne page dédiée — redirige vers billing) ═══

    public function showSubscribe()
    {
        return redirect()->route('studio.billing');
    }

    public function processSubscribe()
    {
        return $this->subscribe();
    }

    // ═══ FICHES CLIENTS ═══

    public function clients(\Illuminate\Http\Request $request)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

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

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

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

    public function clientUpdate(\App\Models\Client $client, \Illuminate\Http\Request $request)
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

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

    // ═══ STATS ═══

    public function stats()
    {
        $studio = $this->studio();
        $artistUserIds = $studio->studioArtists()
            ->where('is_active', true)
            ->pluck('user_id')
            ->filter();

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        // Requête de base
        $base = \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        });

        $totalRequests    = (clone $base)->count();
        $pendingRequests  = (clone $base)->where('status', 'pending')->count();
        $completedAll     = (clone $base)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])->count();
        $cancelledAll     = (clone $base)->whereIn('status', ['cancelled', 'rejected', 'no_show'])->count();

        // Revenus mensuels (6 derniers mois) — acomptes encaissés (total_deposit_amount en euros)
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenue = (clone $base)
                ->whereNotNull('deposit_paid_at')
                ->whereMonth('deposit_paid_at', $month->month)
                ->whereYear('deposit_paid_at', $month->year)
                ->sum('total_deposit_amount');
            $monthlyRevenue[] = [
                'label'   => $month->format('M Y'),
                'revenue' => round((float) $revenue, 2),
            ];
        }

        // Stats par artiste
        $artistsStats = $studio->studioArtists()
            ->where('is_active', true)
            ->with('user')
            ->get()
            ->map(function ($sa) use ($tattooerIds, $piercerIds) {
                if (!$sa->user_id) return null;

                $artistTattooerIds = \App\Models\Tattooer::where('user_id', $sa->user_id)->pluck('id');
                $artistPiercerIds  = \App\Models\Piercer::where('user_id', $sa->user_id)->pluck('id');

                $artistBase = \App\Models\BookingRequest::where(function ($q) use ($artistTattooerIds, $artistPiercerIds) {
                    $q->where(function ($q2) use ($artistTattooerIds) {
                        $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $artistTattooerIds);
                    })->orWhere(function ($q2) use ($artistPiercerIds) {
                        $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $artistPiercerIds);
                    });
                });

                return [
                    'name'       => $sa->artist_name ?: $sa->user?->name ?? 'Artiste',
                    'type'       => $sa->artisan_type,
                    'total'      => (clone $artistBase)->count(),
                    'pending'    => (clone $artistBase)->where('status', 'pending')->count(),
                    'completed'  => (clone $artistBase)->whereIn('status', ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline'])->count(),
                    'revenue'    => round((float) (clone $artistBase)->whereNotNull('deposit_paid_at')->sum('total_deposit_amount'), 2),
                ];
            })
            ->filter()
            ->values();

        return view('studio.stats', compact(
            'studio',
            'totalRequests',
            'pendingRequests',
            'completedAll',
            'cancelledAll',
            'monthlyRevenue',
            'artistsStats'
        ));
    }

    // ═══ INVITATION ═══

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

    protected function performDeletion(\App\Models\User $user): void
    {
        DB::transaction(function () use ($user) {
            $studio = $user->studio;
            if (!$studio) return;

            // 1. Annuler l'abonnement Stripe
            try {
                if ($user->subscribed('default')) {
                    $user->subscription('default')->cancelNow();
                }
            } catch (\Exception $e) {
                Log::warning('Annulation abo studio impossible: ' . $e->getMessage());
            }

            // 2. Détacher les artistes (ils deviennent indépendants)
            foreach ($studio->studioArtists as $sa) {
                if ($linkedUser = $sa->user) {
                    $linkedUser->tattooer?->update(['studio_id' => null]);
                    $linkedUser->piercer?->update(['studio_id' => null]);
                }
                $sa->forceDelete();
            }

            // 3. Supprimer les médias studio
            $studio->media()->each(fn($m) => $m->delete());
            $studio->forceDelete();

            // 4. Anonymiser l'user
            $user->notifications()->delete();
            $user->update([
                'name'      => 'Compte supprimé',
                'email'     => 'deleted_' . $user->id . '@inkpik.deleted',
                'phone'     => null,
                'password'  => bcrypt(\Str::random(40)),
                'stripe_id' => null,
                'fcm_token' => null,
            ]);
            $user->forceDelete();
        });
    }
}

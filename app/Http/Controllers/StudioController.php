<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\StudioArtist;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Piercer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class StudioController extends Controller
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
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:2000',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:255',
            'postal_code'   => 'nullable|string|max:10',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'website'       => 'nullable|url|max:255',
            'siret'         => 'nullable|string|size:14',
            'payment_mode'  => 'required|in:artist_direct,studio_managed',
            'opening_hours' => 'nullable|array',
            'social_media_links' => 'nullable|array',
        ]);

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

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:8',
            'artisan_type' => 'required|in:tattooer,piercer',
        ]);

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

        // Créer le profil artisan
        if ($validated['artisan_type'] === 'piercer') {
            Piercer::create([
                'user_id'   => $user->id,
                'studio_id' => $studio->id,
                'siret'     => $studio->siret ?: 'STUDIO_' . $studio->id, // SIRET du studio ou placeholder
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
                'siret'     => $studio->siret ?: 'STUDIO_' . $studio->id, // SIRET du studio ou placeholder
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

        // Désactiver plutôt que supprimer (garder l'historique)
        $studioArtist->update(['is_active' => false]);

        // Retirer le studio_id du profil artisan
        if ($studioArtist->user) {
            $artisan = $studioArtist->user->artisan();
            if ($artisan) {
                $artisan->update(['studio_id' => null]);
            }
        }

        app(\App\Services\StudioBillingService::class)->updateArtistQuantity($this->studio());

        return back()->with('success', 'Artiste retiré du studio');
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

            // Laisser Stripe finaliser (délai Stripe)
            sleep(2);

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

        return view('studio.billing', compact('studio', 'subscriptionInfo', 'isSubscribed', 'portalUrl'));
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
}

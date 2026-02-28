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

        return view('studio.dashboard', [
            'studio'       => $studio,
            'artists'      => $artists,
            'artistCount'  => $artists->count(),
            'monthlyPrice' => $studio->monthlyPrice(),
            // Compatibilité avec la vue existante
            'totalArtists'  => $artists->count(),
            'activeArtists' => $artists->count(),
            'totalRevenue'  => 0,
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
            'joined_at'    => now(),
            'artist_name'  => $user->name, // Utiliser le nom de l'utilisateur
            'slug'         => Str::slug($user->name), // Générer un slug à partir du nom
        ]);

        \Mail::to($validated['email'])->send(new \App\Mail\StudioArtistCreatedMail(
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

        \Mail::to($validated['email'])->send(new \App\Mail\StudioInvitationMail(
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

    public function billing()
    {
        $studio = $this->studio();
        $billingService = app(\App\Services\StudioBillingService::class);

        return view('studio.billing', [
            'studio'          => $studio,
            'monthlyPrice'    => $studio->monthlyPrice(),
            'artistCount'     => $studio->artistCount(),
            'paidArtistCount' => $studio->paidArtistCount(),
            'isSubscribed'    => $billingService->isSubscribed($studio),
            'portalUrl'       => $studio->hasStripeId() ? $billingService->billingPortalUrl($studio) : null,
        ]);
    }

    // ═══ SOUSCRIPTION ═══

    public function showSubscribe()
    {
        $studio = $this->studio();

        if ($studio->hasActiveSubscription()) {
            return redirect()->route('studio.billing')
                ->with('info', 'Vous avez déjà un abonnement actif.');
        }

        return view('studio.subscribe', [
            'studio'          => $studio,
            'monthlyPrice'    => $studio->monthlyPrice(),
            'paidArtistCount' => $studio->paidArtistCount(),
        ]);
    }

    public function processSubscribe()
    {
        $studio = $this->studio();

        if ($studio->hasActiveSubscription()) {
            return redirect()->route('studio.billing');
        }

        $studioPriceId = config('services.stripe.studio_price_id');
        $artistPriceId = config('services.stripe.studio_artist_price_id');

        if (!$studioPriceId) {
            return back()->with('error', 'Configuration Stripe incomplète. Contactez le support.');
        }

        // Créer le customer Stripe si nécessaire
        if (!$studio->hasStripeId()) {
            $studio->createAsStripeCustomer([
                'name'     => $studio->name,
                'email'    => $studio->stripeEmail(),
                'metadata' => ['studio_id' => $studio->id],
            ]);
        }

        $lineItems = [
            ['price' => $studioPriceId, 'quantity' => 1],
        ];

        $paidArtists = $studio->paidArtistCount();
        if ($paidArtists > 0 && $artistPriceId) {
            $lineItems[] = ['price' => $artistPriceId, 'quantity' => $paidArtists];
        }

        // Stripe Checkout Session native (supporte multi-price avec quantity)
        \Stripe\Stripe::setApiKey(config('cashier.secret'));
        $session = \Stripe\Checkout\Session::create([
            'customer'   => $studio->stripe_id,
            'mode'       => 'subscription',
            'line_items' => $lineItems,
            'success_url' => route('studio.billing') . '?activated=1',
            'cancel_url'  => route('studio.subscribe'),
        ]);

        return redirect($session->url);
    }

    // ═══ STATS ═══

    public function stats()
    {
        return view('studio.stats', [
            'studio' => $this->studio(),
        ]);
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

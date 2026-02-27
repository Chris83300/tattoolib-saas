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
            'payment_mode'  => 'required|in:centralized,distributed',
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
            'studio'             => $studio,
            'artists'            => $artists,
            'activeArtists'      => $activeArtists,
            'pendingInvitations' => $pendingInvitations,
            'canAddArtist'       => $studio->canAddArtist(),
            'paidArtistCount'    => $studio->paidArtistCount(),
            'monthlyPrice'       => $studio->monthlyPrice(),
        ]);
    }

    public function createArtist()
    {
        $studio = $this->studio();
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
        abort_unless($studio->canAddArtist(), 403);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'artisan_type' => 'required|in:tattooer,piercer',
        ]);

        // Créer le User avec mot de passe temporaire
        $tempPassword = Str::random(12);
        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($tempPassword),
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
            ]);
        } else {
            Tattooer::create([
                'user_id'   => $user->id,
                'studio_id' => $studio->id,
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
        ]);

        \Mail::to($validated['email'])->send(new \App\Mail\StudioArtistCreatedMail(
            $studio,
            $validated['name'],
            $validated['email'],
            $tempPassword,
            $validated['artisan_type']
        ));

        // TODO (Prompt 4) : Mettre à jour la subscription Stripe (quantity)

        return redirect()->route('studio.artists')
            ->with('success', "Artiste {$validated['name']} créé. Un email avec les identifiants a été envoyé.");
    }

    /**
     * Invitation par email
     */
    public function inviteArtist(Request $request)
    {
        $studio = $this->studio();
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

        // TODO (Prompt 4) : Mettre à jour la subscription Stripe (quantity -1)

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
        return view('studio.billing', [
            'studio'          => $studio,
            'monthlyPrice'    => $studio->monthlyPrice(),
            'artistCount'     => $studio->artistCount(),
            'paidArtistCount' => $studio->paidArtistCount(),
        ]);
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

        // TODO (Prompt 4) : Mettre à jour subscription Stripe

        auth()->login($user);
        $redirect = $roleSlug === 'pierceur' ? 'pierceur.dashboard' : 'tattooer.dashboard';
        return redirect()->route($redirect)
            ->with('success', 'Bienvenue dans le studio ' . $invitation->studio->name . ' !');
    }
}

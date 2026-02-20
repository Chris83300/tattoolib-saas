<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\StudioArtist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudioController extends Controller
{
    /**
     * Récupère le studio de l'utilisateur connecté ou abort 403.
     */
    private function getStudio(): Studio
    {
        $studio = $this->getStudio();
        abort_unless($studio, 403, 'Profil studio non trouvé');

        return $studio;
    }

    public function dashboard()
    {
        $studio = $this->getStudio();

        $totalArtists = $studio->artists()->count();
        $activeArtists = $studio->artists()->where('is_active', true)->count();
        $totalRevenue = 0;

        return view('studio.dashboard', compact('studio', 'totalArtists', 'activeArtists', 'totalRevenue'));
    }

    public function artists()
    {
        $studio = $this->getStudio();
        $artists = $studio->artists()->with('user')->get();

        return view('studio.artists', compact('studio', 'artists'));
    }

    public function inviteArtist(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:artist,manager,receptionist',
        ]);

        $studio = $this->getStudio();

        // Générer un code d'invitation unique
        $invitationCode = strtoupper(substr(md5(uniqid()), 0, 8));

        // Stocker l'invitation (à implémenter dans une table invitations)

        // Envoyer l'email d'invitation (à implémenter)

        return back()->with('success', "Invitation envoyée à {$request->email} avec le code {$invitationCode}");
    }

    public function planning()
    {
        $studio = $this->getStudio();
        return view('studio.planning', compact('studio'));
    }

    public function requests()
    {
        $studio = $this->getStudio();
        return view('studio.requests', compact('studio'));
    }

    public function transactions()
    {
        $studio = $this->getStudio();
        return view('studio.transactions', compact('studio'));
    }

    public function stats()
    {
        $studio = $this->getStudio();
        return view('studio.stats', compact('studio'));
    }

    public function exports()
    {
        $studio = $this->getStudio();
        return view('studio.exports', compact('studio'));
    }

    public function settings()
    {
        $studio = $this->getStudio();
        return view('studio.settings', compact('studio'));
    }

    public function publicProfile($slug)
    {
        $studio = Studio::where('slug', $slug)->firstOrFail();
        return view('marketplace.studio-show', compact('studio'));
    }
}

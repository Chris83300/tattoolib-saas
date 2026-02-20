<?php

namespace App\Http\Controllers;

use App\Models\Studio;
use App\Models\StudioArtist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudioController extends Controller
{
    public function dashboard()
    {
        $studio = Auth::user()->studio;

        // Stats globales
        $totalArtists = $studio->artists()->count();
        $activeArtists = $studio->activeArtists()->count();
        $totalRevenue = $studio->artists()
            ->whereHas('studioArtist', function ($query) {
                return $query->where('is_active', true);
            })
            ->withSum('total_revenue');

        return view('studio.dashboard', compact('studio', 'totalArtists', 'activeArtists', 'totalRevenue'));
    }

    public function artists()
    {
        $studio = Auth::user()->studio;
        $artists = $studio->artists()->with('user')->get();

        return view('studio.artists', compact('studio', 'artists'));
    }

    public function inviteArtist(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:artist,manager,receptionist',
        ]);

        $studio = Auth::user()->studio;

        // Générer un code d'invitation unique
        $invitationCode = strtoupper(substr(md5(uniqid()), 0, 8));

        // Stocker l'invitation (à implémenter dans une table invitations)

        // Envoyer l'email d'invitation (à implémenter)

        return back()->with('success', "Invitation envoyée à {$request->email} avec le code {$invitationCode}");
    }

    public function planning()
    {
        $studio = Auth::user()->studio;
        return view('studio.planning', compact('studio'));
    }

    public function requests()
    {
        $studio = Auth::user()->studio;
        return view('studio.requests', compact('studio'));
    }

    public function transactions()
    {
        $studio = Auth::user()->studio;
        return view('studio.transactions', compact('studio'));
    }

    public function stats()
    {
        $studio = Auth::user()->studio;
        return view('studio.stats', compact('studio'));
    }

    public function exports()
    {
        $studio = Auth::user()->studio;
        return view('studio.exports', compact('studio'));
    }

    public function settings()
    {
        $studio = Auth::user()->studio;
        return view('studio.settings', compact('studio'));
    }

    public function publicProfile($slug)
    {
        $studio = Studio::where('slug', $slug)->firstOrFail();
        return view('marketplace.studio-show', compact('studio'));
    }
}

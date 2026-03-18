<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Afficher le profil du client
     */
    public function index(Request $request)
    {
        // La vue Blade contient le composant Livewire
        return view('client.profile');
    }

    /**
     * Afficher les settings du client
     */
    public function settings(Request $request)
    {
        return view('client.settings');
    }

    /**
     * Mettre à jour l'avatar du client (sur le modèle User)
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif,webp|max:5120',
        ]);

        $user = auth()->user();
        $user->clearMediaCollection('avatar');
        $user->addMediaFromRequest('avatar')->toMediaCollection('avatar');

        return redirect()->route('client.settings')->with('success', 'Photo de profil mise à jour !');
    }

    /**
     * Export RGPD — droit à la portabilité (Art. 20 RGPD)
     */
    public function exportGdpr(Request $request)
    {
        $user = $request->user();
        $path = app(\App\Services\GdprExportService::class)->exportUserData($user);

        return Storage::disk('local')->download(
            $path,
            'mes-donnees-inkpik-' . now()->format('Y-m-d') . '.json'
        );
    }

    /**
     * Supprimer l'avatar du client
     */
    public function deleteAvatar(Request $request)
    {
        auth()->user()->clearMediaCollection('avatar');

        return response()->json(['success' => true]);
    }

    /**
     * Afficher les messages du client
     */
    public function messages(Request $request)
    {
        // La vue Blade contient le composant Livewire
        return view('client.messages');
    }

    /**
     * Afficher les réservations du client
     */
    public function bookings(Request $request)
    {
        // La vue Blade contient le composant Livewire
        return view('client.bookings');
    }
}

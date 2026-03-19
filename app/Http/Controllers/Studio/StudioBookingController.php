<?php

namespace App\Http\Controllers\Studio;

use App\Http\Controllers\Controller;
use App\Models\Studio;

class StudioBookingController extends Controller
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
}

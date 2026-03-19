<?php

namespace App\Http\Controllers\Tattooer;

use App\Models\BookingRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class TattooerMediaController extends ArtisanBaseController
{
    /**
     * Uploader des photos pour un client
     */
    public function uploadClientPhotos(Request $request, Client $client)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $request->validate([
            'photos' => 'required|array|min:1|max:10',
            'photos.*' => 'required|file|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $uploadedCount = 0;
        foreach ($request->file('photos') as $photo) {
            $client->addMedia($photo)
                ->withCustomProperties([
                    'uploaded_by' => 'tattooer',
                    'tattooer_id' => $tattooer->id,
                    'upload_date' => now()->format('Y-m-d H:i:s'),
                ])
                ->toMediaCollection('client_photos');
            $uploadedCount++;
        }

        return redirect()->back()->with('success', "✅ {$uploadedCount} photo(s) uploadée(s) avec succès !");
    }

    /**
     * Supprimer une photo client
     */
    public function deleteClientPhoto(Client $client, $media)
    {
        $tattooer = $this->artisan();

        // Vérifier que ce client appartient bien au tattooer
        $hasBookingRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->whereNotNull('deposit_paid_at')
            ->exists();

        $isManuallyCreated = $client->tattooer_id === $tattooer->id;

        if (!$hasBookingRelation && !$isManuallyCreated) {
            abort(403, 'Ce client ne fait pas partie de votre clientèle.');
        }

        $mediaItem = $client->getMedia('client_photos')->where('id', $media)->first();

        if (!$mediaItem) {
            abort(404, 'Photo non trouvée.');
        }

        $mediaItem->delete();

        return redirect()->back()->with('success', '✅ Photo supprimée avec succès !');
    }

    /**
     * Upload photos du tattoo réalisé
     */
    public function uploadClientTattooPhotos(Request $request, Client $client, BookingRequest $bookingRequest)
    {
        $tattooer = $this->artisan();

        if ($bookingRequest->bookable_id !== $tattooer->id) {
            abort(403);
        }

        $request->validate([
            'photos' => 'required|array|max:10',
            'photos.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        foreach ($request->file('photos') as $photo) {
            $bookingRequest->addMedia($photo)
                ->withCustomProperties([
                    'type' => 'tattoo_result',
                    'uploaded_by' => 'tattooer',
                    'uploaded_at' => now()->toISOString(),
                ])
                ->toMediaCollection('tattoo_results');
        }

        return redirect()->to(url()->previous() . '#media')->with('success', '📸 Photos enregistrées.');
    }

    /**
     * Supprimer un média client
     */
    public function deleteClientMedia(Client $client, $mediaId)
    {
        $tattooer = $this->artisan();

        // Vérifier la relation client-tattooer
        $hasRelation = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->exists();

        if (!$hasRelation) {
            abort(403);
        }

        // Trouver le media dans les booking requests de ce tattooer
        $bookingRequestIds = BookingRequest::where('client_id', $client->id)
            ->where('bookable_id', $tattooer->id)
            ->pluck('id');

        $media = \Spatie\MediaLibrary\MediaCollections\Models\Media::where('id', $mediaId)
            ->where('model_type', BookingRequest::class)
            ->whereIn('model_id', $bookingRequestIds)
            ->firstOrFail();

        $media->delete();

        return back()->with('success', 'Photo supprimée.');
    }

    /**
     * Supprimer l'avatar
     */
    public function deleteAvatar()
    {
        $tattooer = $this->artisan();

        if ($tattooer->user->hasMedia('avatar')) {
            $tattooer->user->clearMediaCollection('avatar');
        }

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer la bannière
     */
    public function deleteBanner()
    {
        $tattooer = $this->artisan();

        if ($tattooer->hasMedia('banner')) {
            $tattooer->clearMediaCollection('banner');
        }

        return response()->json(['success' => true]);
    }
}

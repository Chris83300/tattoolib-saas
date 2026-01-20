<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use App\Models\StudioArtist;
use Illuminate\Http\Request;

class ArtistController extends Controller
{
    /**
     * Affiche le profil public d'un artiste (Tattooer OU StudioArtist)
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug)
    {
        // 1. Chercher dans Tattooer (indépendants)
        $artist = Tattooer::where('slug', $slug)
            ->with(['user', 'studio']) // Studio peut être null
            ->first();

        $type = 'tattooer';

        // 2. Si pas trouvé, chercher dans StudioArtist
        if (!$artist) {
            $artist = StudioArtist::where('slug', $slug)
                ->where('status', 'active')
                ->with(['user', 'studio'])
                ->first();

            $type = 'studio_artist';
        }

        // 3. Si toujours pas trouvé → 404
        if (!$artist) {
            abort(404, 'Artiste non trouvé');
        }

        // 4. Préparer les données unifiées
        $profile = $this->prepareArtistProfile($artist, $type);

        // 5. Charger les données communes
        $bookingStats = $this->getBookingStats($artist, $type);
        $portfolio = $this->getPortfolio($artist, $type);
        $availability = $this->getNextAvailability($artist, $type);

        return view('artists.show', compact('profile', 'bookingStats', 'portfolio', 'availability'));
    }

    /**
     * Prépare un profil unifié (même structure pour Tattooer et StudioArtist)
     */
    private function prepareArtistProfile($artist, string $type): array
    {
        if ($type === 'tattooer') {
            return [
                'id' => $artist->id,
                'type' => 'tattooer',
                'model' => $artist,
                'name' => $artist->user->name,
                'slug' => $artist->slug,
                'bio' => $artist->bio,
                'specialties' => [], // TODO: Implémenter selon ta logique
                'email' => $artist->user->email,
                'phone' => $artist->phone,
                'social_links' => [
                    'instagram' => $artist->instagram ?? null,
                    'facebook' => $artist->facebook ?? null,
                    'tiktok' => $artist->tiktok ?? null,
                    'website' => $artist->website ?? null,
                ],
                'portfolio_images' => [], // TODO: Implémenter selon ta logique
                'verified' => $artist->siret_verified ?? false,
                'studio' => null, // Indépendant
            ];
        }

        // StudioArtist
        return [
            'id' => $artist->id,
            'type' => 'studio_artist',
            'model' => $artist,
            'name' => $artist->artist_name ?? $artist->user->name,
            'slug' => $artist->slug,
            'bio' => $artist->bio,
            'specialties' => $artist->specialties ?? [],
            'email' => $artist->user->email,
            'phone' => $artist->phone,
            'social_links' => [], // TODO: Implémenter selon ta logique
            'portfolio_images' => [], // TODO: Implémenter selon ta logique
            'verified' => $artist->studio->is_verified ?? false,
            'studio' => [
                'name' => $artist->studio->name,
                'slug' => $artist->studio->slug,
                'address' => $artist->studio->address,
                'city' => $artist->studio->city,
            ],
        ];
        }

    /**
     * Récupère les stats de bookings (polymorphic)
     */
    private function getBookingStats($artist, string $type): array
    {
        try {
            $bookingsCount = $artist->bookingRequests()->count();
            $appointmentsCount = $artist->appointments()->count();
        } catch (\Exception $e) {
            // En cas d'erreur de relation, utiliser des valeurs par défaut
            $bookingsCount = 0;
            $appointmentsCount = 0;
        }

        return [
            'total_bookings' => $bookingsCount,
            'total_appointments' => $appointmentsCount,
            'rating' => 4.8, // TODO: Implémenter système de notes
        ];
    }

    /**
     * Récupère le portfolio (à implémenter selon ta logique)
     */
    private function getPortfolio($artist, string $type): array
    {
        // TODO: Récupérer depuis Media Library ou portfolio_images
        return $type === 'tattooer'
            ? ($artist->portfolio_images ?? [])
            : ($artist->portfolio_images ?? []);
    }

    /**
     * Récupère la prochaine disponibilité
     */
    private function getNextAvailability($artist, string $type): ?string
    {
        try {
            $nextSlot = $artist->availabilities()
                ->where('type', 'available')
                ->where('start_time', '>', now())
                ->orderBy('start_time')
                ->first();

            return $nextSlot ? $nextSlot->start_time->format('d/m/Y H:i') : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}

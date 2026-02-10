<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use App\Models\Pierceur;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    /**
     * Page marketplace
     */
    public function index(Request $request): View
    {
        $cacheService = app(\App\Services\CacheService::class);

        $filters = $request->only(['city', 'styles', 'rating']);
        $artists = $cacheService->getMarketplaceListings($filters);

        // Obtenir aussi les données de filtrage depuis cache
        $availableStyles = $cacheService->getAvailableStyles();
        $availableCities = $cacheService->getAvailableCities();

        return view('marketplace.index', compact(
            'artists',
            'filters',
            'availableStyles',
            'availableCities'
        ));
    }

    /**
     * Profil public artiste
     */
    public function show(string $slug): View
    {
        // Chercher dans tattooers
        $artist = Tattooer::where('slug', $slug)
            ->whereHas('user', fn($q) => $q->whereIn('status', ['active', 'pending_verification']))
            ->with('user')
            ->first();

        $type = 'tattooer';

        // Si pas trouvé, chercher dans pierceurs
        if (!$artist) {
            $artist = Pierceur::where('slug', $slug)
                ->whereHas('user', fn($q) => $q->whereIn('status', ['active', 'pending_verification']))
                ->with('user')
                ->first();

            $type = 'pierceur';
        }

        abort_if(!$artist, 404, 'Artiste non trouvé');

        // Charger les relations
        $artist->load(['media', 'reviews']);

        // Stats
        $stats = [
            'rating' => $artist->reviews_avg_rating ?? 0,
            'reviews_count' => $artist->reviews_count ?? 0,
            'appointments_count' => $artist->appointments()->whereIn('status', ['completed', 'confirmed'])->count(),
            'years_experience' => max(1, now()->diffInYears($artist->created_at)),
        ];

        // Portfolio
        $portfolio = $artist->getMedia('portfolio');

        return view('marketplace.show', compact('artist', 'type', 'stats', 'portfolio'));
    }
}

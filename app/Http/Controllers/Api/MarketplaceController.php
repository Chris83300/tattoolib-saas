<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\ArtistCollection;
use App\Services\MarketplaceSearchService;
use App\Models\Tattooer;
use App\Models\Piercer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class MarketplaceController extends Controller
{
    protected MarketplaceSearchService $searchService;

    public function __construct(MarketplaceSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Suggestions de recherche dynamique
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $tattooers = Tattooer::where('status', 'active')
                ->where(function($q) use ($query) {
                    $q->where('pseudo', 'LIKE', "%{$query}%")
                      ->orWhere('name', 'LIKE', "%{$query}%")
                      ->orWhere('studio_name', 'LIKE', "%{$query}%");
                })
                ->select(['id', 'pseudo as label', 'name', 'studio_name', 'city', 'slug'])
                ->limit(5)
                ->get();

            $piercers = Piercer::where('status', 'active')
                ->where(function($q) use ($query) {
                    $q->where('pseudo', 'LIKE', "%{$query}%")
                      ->orWhere('name', 'LIKE', "%{$query}%")
                      ->orWhere('studio_name', 'LIKE', "%{$query}%");
                })
                ->select(['id', 'pseudo as label', 'name', 'studio_name', 'city', 'slug'])
                ->limit(5)
                ->get();

            $suggestions = [];

            foreach ($tattooers as $tattooer) {
                $suggestions[] = [
                    'value' => $tattooer->slug,
                    'label' => $tattooer->pseudo ?: $tattooer->name,
                    'type' => 'Tatoueur',
                    'city' => $tattooer->city
                ];
            }

            foreach ($piercers as $piercer) {
                $suggestions[] = [
                    'value' => $piercer->slug,
                    'label' => $piercer->pseudo ?: $piercer->name,
                    'type' => 'Pierceur',
                    'city' => $piercer->city
                ];
            }

            return response()->json(array_slice($suggestions, 0, 10));
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    /**
     * Recherche d'artistes avec filtres
     */
    public function search(Request $request): JsonResponse
    {
        // Debug
        \Log::info('API search received filters:', $request->all());

        $filters = $request->only([
            'specialization',
            'styles',
            'region',
            'city',
            'verified_only',
            'artist_type',
            'artisan_type',
            'sort'
        ]);

        $perPage = min($request->get('per_page', 12), 50); // Max 50 par page

        $artists = $this->searchService->search($filters, $perPage);

        return response()->json([
            'data' => ArtistResource::collection($artists->items()),
            'pagination' => [
                'current_page' => $artists->currentPage(),
                'last_page' => $artists->lastPage(),
                'per_page' => $artists->perPage(),
                'total' => $artists->total(),
                'from' => $artists->firstItem(),
                'to' => $artists->lastItem(),
            ],
            'filters' => [
                'specializations' => $this->searchService->getSpecializations(),
                'styles' => $this->searchService->getStyles(),
                'regions' => $this->searchService->getRegions(),
                'sort_options' => $this->searchService->getSortOptions(),
            ]
        ]);
    }

    /**
     * Artistes mis en avant
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = min($request->get('limit', 6), 12);

        $artists = $this->searchService->getFeaturedArtists($limit);

        return response()->json([
            'data' => ArtistResource::collection($artists),
            'meta' => [
                'count' => $artists->count(),
                'limit' => $limit
            ]
        ]);
    }

    /**
     * Options de filtrage pour le frontend
     */
    public function filters(): JsonResponse
    {
        return response()->json([
            'specializations' => $this->searchService->getSpecializations(),
            'styles' => $this->searchService->getStyles(),
            'regions' => $this->searchService->getRegions(),
            'sort_options' => $this->searchService->getSortOptions(),
        ]);
    }

    /**
     * Statistiques de la marketplace
     */
    public function stats(): JsonResponse
    {
        return response()->json([
            'total_artists' => $this->searchService->getTotalArtists(),
            'verified_artists' => $this->searchService->getVerifiedArtistsCount(),
            'pro_artists' => $this->searchService->getProArtistsCount(),
            'total_appointments' => $this->searchService->getTotalAppointments(),
        ]);
    }
}

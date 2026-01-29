<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArtistResource;
use App\Http\Resources\ArtistCollection;
use App\Services\MarketplaceSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MarketplaceController extends Controller
{
    protected MarketplaceSearchService $searchService;

    public function __construct(MarketplaceSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Recherche d'artistes avec filtres
     */
    public function search(Request $request): JsonResponse
    {
        $filters = $request->only([
            'specialization',
            'styles',
            'region',
            'city',
            'verified_only',
            'artist_type',
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

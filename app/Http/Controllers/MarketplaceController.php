<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Appointment;
use App\Models\Subscription;
use App\Services\CacheService;
use App\Services\MarketplaceSearchService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    /**
     * Page marketplace (rendu serveur)
     */
    public function index(Request $request): View
    {
        $cacheService = app(CacheService::class);

        $filters = $request->only(['city', 'styles', 'rating', 'artisan_type']);
        $artists = $cacheService->getMarketplaceListings($filters);

        $studios = collect();
        if (($filters['artisan_type'] ?? '') === 'studio') {
            $studios = app(MarketplaceSearchService::class)->getStudios($filters);
        }

        $availableStyles = $cacheService->getAvailableStyles();
        $availableCities = $cacheService->getAvailableCities();

        return view('marketplace.index', compact(
            'artists',
            'filters',
            'availableStyles',
            'availableCities',
            'studios'
        ));
    }

    /**
     * Profil public artiste
     */
    public function show(string $slug): View
    {
        // Chercher dans tattooers
        $artist = Tattooer::query()
            ->marketplaceVisible()
            ->where('slug', $slug)
            ->whereHas('user', fn($q) => $q->whereIn('status', ['active', 'pending_verification']))
            ->with('user')
            ->first();

        $type = 'tattooer';

        // Si pas trouvé, chercher dans Piercers
        if (!$artist) {
            $artist = Piercer::query()
                ->marketplaceVisible()
                ->where('slug', $slug)
                ->whereHas('user', fn($q) => $q->whereIn('status', ['active', 'pending_verification']))
                ->with('user')
                ->first();
            $type = 'piercer';
        }

        abort_if(!$artist, 404, 'Artiste non trouvé');

        $artist->load(['media', 'reviews']);

        // Charger aussi les reviews des BookingRequests liées à cet artiste
        $bookingRequestReviews = \App\Models\Review::where('reviewable_type', 'App\Models\BookingRequest')
            ->whereHas('reviewable', function($query) use ($artist) {
                $query->where('bookable_id', $artist->id)
                      ->where('bookable_type', get_class($artist));
            })
            ->leftJoin('clients', 'reviews.client_id', '=', 'clients.id')
            ->leftJoin('users', 'clients.user_id', '=', 'users.id')
            ->select('reviews.*', 'clients.pseudo as client_pseudo', 'clients.first_name', 'clients.last_name', 'users.pseudo as user_pseudo')
            ->where('is_visible', true)
            ->get();

        // Fusionner les reviews directes et les reviews de BookingRequests
        $allReviews = $artist->reviews->merge($bookingRequestReviews);

        $stats = [
            'rating' => $artist->reviews_avg_rating ?? 0,
            'reviews_count' => $artist->reviews_count ?? 0,
            'appointments_count' => $artist->appointments()->whereIn('status', ['completed', 'confirmed'])->count(),
            'years_experience' => max(1, now()->diffInYears($artist->created_at)),
        ];

        $portfolio = $artist->getMedia('portfolio');
        $drawings = $artist->getMedia('drawings');
        $beforeAfter = $artist->getMedia('before_after');

        return view('marketplace.show', compact('artist', 'type', 'stats', 'portfolio', 'drawings', 'beforeAfter', 'allReviews'));
    }

    /**
     * API : Artistes mis en avant (pour welcome page et marketplace)
     * GET /api/marketplace/featured?limit=6
     */
    public function featured(Request $request): JsonResponse
    {
        $limit = min((int) $request->query('limit', 6), 20);
        $cacheService = app(CacheService::class);

        $artists = $this->getFeaturedArtists()
            ->take($limit)
            ->map(fn($artist) => $this->enrichArtistData(
                $cacheService->getArtistProfile($artist)
            ))
            ->values();

        return response()->json(['data' => $artists]);
    }

    /**
     * API : Recherche d'artistes avec pagination
     * GET /api/marketplace/search?city=&artisan_type=&page=&per_page=
     */
    public function search(Request $request): JsonResponse
    {
        $cacheService = app(CacheService::class);

        $filters = $request->only(['city', 'styles', 'rating', 'artisan_type']);
        $page = max(1, (int) $request->query('page', 1));
        $perPage = min(max(1, (int) $request->query('per_page', 12)), 50);

        $allArtists = $cacheService->getMarketplaceListings($filters);
        $total = count($allArtists);
        $artists = array_slice($allArtists, ($page - 1) * $perPage, $perPage);

        return response()->json([
            'data' => array_values(array_map(
                fn($a) => $this->enrichArtistData($a),
                $artists
            )),
            'pagination' => [
                'total' => $total,
                'current_page' => $page,
                'last_page' => max(1, (int) ceil($total / $perPage)),
                'per_page' => $perPage,
            ],
        ]);
    }

    /**
     * API : Statistiques générales de la marketplace
     * GET /api/marketplace/stats
     */
    public function stats(): JsonResponse
    {
        $totalTattooers = Tattooer::count();
        $totalPiercers = Piercer::count();

        return response()->json([
            'total_artists' => $totalTattooers + $totalPiercers,
            'verified_artists' => Tattooer::where('siret_verified', true)->count()
                + Piercer::where('siret_verified', true)->count(),
            'pro_artists' => Tattooer::where('is_subscribed', true)->count()
                + Piercer::where('is_subscribed', true)->count(),
            'total_appointments' => Appointment::count(),
        ]);
    }

    /**
     * API : Options de filtrage disponibles
     * GET /api/marketplace/filters
     */
    public function filters(): JsonResponse
    {
        $cacheService = app(CacheService::class);
        $styles = $cacheService->getAvailableStyles();
        $cities = $cacheService->getAvailableCities();

        return response()->json([
            'specializations' => [
                'tattooer' => '🎨 Tatoueurs',
                'piercer'  => '💎 Pierceurs',
            ],
            'regions' => array_combine($cities, $cities) ?: [],
            'styles'  => $styles,
            'sort_options' => [
                'rating'    => 'Meilleures notes',
                'recent'    => 'Plus récents',
                'price_asc' => 'Prix croissant',
            ],
        ]);
    }

    /**
     * Artistes mis en avant (tatoueurs Pro en priorité + pierceurs Pro)
     */
    public function getFeaturedArtists()
    {
        // Seed hebdomadaire : change chaque lundi → nouvelle rotation
        $weeklySeed = (int) now()->startOfWeek()->timestamp;

        // Tatoueurs PRO actifs en priorité
        $proTattooers = Tattooer::query()
            ->marketplaceVisible()
            ->whereHas('user', fn($q) => $q->where('status', 'active'))
            ->whereHas('subscription', fn($q) => $q->where('plan', Subscription::PLAN_PRO)->where('status', 'active'))
            ->with(['user', 'media'])
            ->inRandomOrder($weeklySeed)
            ->limit(6)
            ->get();

        // Compléter avec tatoueurs FREE si moins de 6
        $freeTattooers = collect();
        if ($proTattooers->count() < 6) {
            $remaining = 6 - $proTattooers->count();
            $freeTattooers = Tattooer::query()
                ->marketplaceVisible()
                ->whereHas('user', fn($q) => $q->where('status', 'active'))
                ->where(function ($q) {
                    $q->whereDoesntHave('subscription')
                        ->orWhereHas('subscription', fn($sq) => $sq->where('plan', Subscription::PLAN_FREE));
                })
                ->whereNotIn('id', $proTattooers->pluck('id'))
                ->with(['user', 'media'])
                ->inRandomOrder($weeklySeed)
                ->limit($remaining)
                ->get();
        }

        $proTattooers = $proTattooers->concat($freeTattooers);

        // Ajouter quelques Piercers PRO
        $proPiercers = Piercer::query()
            ->marketplaceVisible()
            ->whereHas('user', fn($q) => $q->where('status', 'active'))
            ->whereHas('subscription', fn($q) => $q->where('plan', Subscription::PLAN_PRO)->where('status', 'active'))
            ->with(['user', 'media'])
            ->inRandomOrder($weeklySeed)
            ->limit(2)
            ->get();

        return $proTattooers->concat($proPiercers)->take(8);
    }

    /**
     * Enrichit les données d'artiste avec les champs attendus par le front JS
     */
    private function enrichArtistData(array $artist): array
    {
        $isPiercer = ($artist['type'] ?? 'tattooer') === 'piercer';

        return array_merge($artist, [
            'specialization_label' => $isPiercer ? '💎 Pierceur' : '🎨 Tatoueur',
            'rating'               => round($artist['average_rating'] ?? 0, 1),
            'reviews_count'        => $artist['total_reviews'] ?? 0,
            'region_label'         => $artist['city'] ?? null,
            'postal_code'          => null,
            'portfolio_images'     => [],
            'minimum_price'        => null,
            'wait_time_weeks_min'  => null,
            'wait_time_weeks_max'  => null,
            'working_hours'        => null,
            'stats'                => [
                'years_experience' => 0,
            ],
        ]);
    }
}

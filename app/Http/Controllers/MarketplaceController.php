<?php

namespace App\Http\Controllers;

use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Subscription;
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

        // Si pas trouvé, chercher dans Piercers
        if (!$artist) {
            $artist = Piercer::where('slug', $slug)
                ->whereHas('user', fn($q) => $q->whereIn('status', ['active', 'pending_verification']))
                ->with('user')
                ->first();

            $type = 'Piercer';
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
        $drawings = $artist->getMedia('drawings');
        $beforeAfter = $artist->getMedia('before_after');

        return view('marketplace.show', compact('artist', 'type', 'stats', 'portfolio', 'drawings', 'beforeAfter'));
    }

    /**
     * Artistes mis en avant pour la welcome page
     */
    public function getFeaturedArtists()
    {
        // Récupérer les tatoueurs PRO actifs en priorité
        $proTattooers = Tattooer::whereHas('user', fn($q) => $q->where('status', 'active'))
            ->whereHas('subscription', fn($q) => $q->where('plan', Subscription::PLAN_PRO)->where('status', 'active'))
            ->with(['user', 'media'])
            ->inRandomOrder()
            ->limit(6)
            ->get();

        // Compléter avec des tatoueurs FREE si moins de 6
        if ($proTattooers->count() < 6) {
            $remaining = 6 - $proTattooers->count();
            $freeTattooers = Tattooer::whereHas('user', fn($q) => $q->where('status', 'active'))
                ->whereDoesntHave('subscription')
                ->orWhereHas('subscription', fn($q) => $q->where('plan', Subscription::PLAN_FREE))
                ->whereNotIn('id', $proTattooers->pluck('id'))
                ->with(['user', 'media'])
                ->inRandomOrder()
                ->limit($remaining)
                ->get();

            $proTattooers = $proTattooers->concat($freeTattooers);
        }

        // Ajouter quelques Piercers PRO si disponible
        $proPiercers = Piercer::whereHas('user', fn($q) => $q->where('status', 'active'))
            ->whereHas('subscription', fn($q) => $q->where('plan', Subscription::PLAN_PRO)->where('status', 'active'))
            ->with(['user', 'media'])
            ->inRandomOrder()
            ->limit(2)
            ->get();

        $allArtists = $proTattooers->concat($proPiercers);

        return $allArtists->take(8); // Maximum 8 artistes
    }
}

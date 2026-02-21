<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Tattooer;
use App\Models\Piercer;

class CacheService
{
    // Durées de cache (en secondes)
    const PORTFOLIO_TTL = 86400; // 24h
    const MARKETPLACE_TTL = 1800; // 30min
    const WORKING_HOURS_TTL = 43200; // 12h
    const ARTIST_PROFILE_TTL = 3600; // 1h
    const STATS_TTL = 3600; // 1h

    /**
     * Cache portfolio images d'un artiste
     */
    public function getPortfolio(Tattooer|Piercer $artist): array
    {
        $cacheKey = $this->portfolioKey($artist);

        return Cache::remember($cacheKey, self::PORTFOLIO_TTL, function() use ($artist) {
            return $artist->getMedia('portfolio')->map(function($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'thumb' => $media->getUrl('thumb'),
                    'created_at' => $media->created_at,
                    'size' => $media->size,
                    'mime_type' => $media->mime_type,
                ];
            })->toArray();
        });
    }

    /**
     * Cache horaires de travail
     */
    public function getWorkingHours(Tattooer|Piercer $artist): array
    {
        $cacheKey = "artist.{$artist->id}.working_hours";

        return Cache::remember($cacheKey, self::WORKING_HOURS_TTL, function() use ($artist) {
            return $artist->workingHours()
                ->orderBy('day_of_week')
                ->get()
                ->groupBy('day_of_week')
                ->map(function($hours, $day) {
                    return [
                        'day' => (int) $day,
                        'day_name' => $this->getDayName($day),
                        'hours' => $hours->map(function($hour) {
                            return [
                                'open_time' => $hour->open_time,
                                'close_time' => $hour->close_time,
                                'is_closed' => $hour->is_closed,
                            ];
                        })->toArray(),
                    ];
                })
                ->values()
                ->toArray();
        });
    }

    /**
     * Cache profil complet artiste (pour marketplace)
     */
    public function getArtistProfile(Tattooer|Piercer $artist): array
    {
        $cacheKey = "artist.{$artist->id}.full_profile";

        return Cache::remember($cacheKey, self::ARTIST_PROFILE_TTL, function() use ($artist) {
            $artist->load(['user', 'workingHours']);

            $isPiercer = $artist instanceof Piercer;
            return [
                'id' => $artist->id,
                'type' => $isPiercer ? 'piercer' : 'tattooer',
                'name' => $artist->name,
                'slug' => $artist->slug,
                'bio' => $artist->bio,
                'styles' => $isPiercer ? ($artist->piercing_types ?? []) : ($artist->styles ?? []),
                'city' => $artist->city,
                'avatar_url' => $artist->getFirstMediaUrl('avatar'),
                'banner_url' => $artist->getFirstMediaUrl('banner'),
                'portfolio_count' => $artist->getMedia('portfolio')->count(),
                'average_rating' => $artist->reviews()->avg('rating') ?? 0,
                'total_reviews' => $artist->reviews()->count() ?? 0,
                'is_subscribed' => $artist->is_subscribed ?? false,
                'siret_verified' => $artist->siret_verified ?? false,
                'status' => $artist->user?->status ?? 'active',
                'created_at' => $artist->created_at,
            ];
        });
    }

    /**
     * Cache listings marketplace
     */
    public function getMarketplaceListings(array $filters = []): array
    {
        $cacheKey = 'marketplace.listings.' . md5(json_encode($filters));

        return Cache::remember($cacheKey, self::MARKETPLACE_TTL, function() use ($filters) {
            $artisanType = $filters['artisan_type'] ?? '';
            $results = collect();

            // Tattooers
            if ($artisanType !== 'piercer') {
                $query = Tattooer::query()
                    ->with(['user', 'workingHours'])
                    ->whereHas('user', fn($q) => $q->where('status', 'active'));

                if (isset($filters['city']) && !empty($filters['city'])) {
                    $query->where('city', 'LIKE', '%' . $filters['city'] . '%');
                }
                if (isset($filters['styles']) && !empty($filters['styles'])) {
                    $query->where('styles', 'LIKE', '%' . $filters['styles'] . '%');
                }
                if (isset($filters['rating']) && $filters['rating'] > 0) {
                    $query->whereHas('reviews', fn($q) => $q->havingRaw('AVG(rating) >= ?', [$filters['rating']]));
                }

                $results = $results->concat($query->get()->map(fn($t) => $this->getArtistProfile($t)));
            }

            // Piercers
            if ($artisanType !== 'tattooer') {
                $query = Piercer::query()
                    ->with(['user', 'workingHours'])
                    ->whereHas('user', fn($q) => $q->where('status', 'active'));

                if (isset($filters['city']) && !empty($filters['city'])) {
                    $query->where('city', 'LIKE', '%' . $filters['city'] . '%');
                }

                $results = $results->concat($query->get()->map(fn($p) => $this->getArtistProfile($p)));
            }

            return $results->sortByDesc('average_rating')->values()->toArray();
        });
    }

    /**
     * Cache stats dashboard (déjà dans TattooerStatsService mais wrapper ici)
     */
    public function getDashboardStats(Tattooer|Piercer $artist): array
    {
        $cacheKey = "artist.{$artist->id}.dashboard_stats";

        return Cache::remember($cacheKey, self::STATS_TTL, function() use ($artist) {
            return app(\App\Services\TattooerStatsService::class)->getDashboardStats($artist);
        });
    }

    /**
     * Cache pour les styles disponibles
     */
    public function getAvailableStyles(): array
    {
        return Cache::remember('available_styles', self::MARKETPLACE_TTL, function() {
            $tattooerStyles = Tattooer::whereNotNull('styles')
                ->pluck('styles')
                ->flatMap(function($styles) {
                    // Si styles est un array, le retourner directement
                    if (is_array($styles)) {
                        return $styles;
                    }
                    // Si styles est une string, l'exploder
                    return explode(',', $styles);
                })
                ->map('trim')
                ->unique()
                ->sort()
                ->values();

            return $tattooerStyles->toArray();
        });
    }

    /**
     * Cache pour les villes disponibles
     */
    public function getAvailableCities(): array
    {
        return Cache::remember('available_cities', self::MARKETPLACE_TTL, function() {
            return Tattooer::whereNotNull('city')
                ->distinct('city')
                ->orderBy('city')
                ->pluck('city')
                ->toArray();
        });
    }

    /**
     * Invalider cache artiste (appelé après update)
     */
    public function invalidateArtist(Tattooer|Piercer $artist): void
    {
        Cache::forget($this->portfolioKey($artist));
        Cache::forget("artist.{$artist->id}.working_hours");
        Cache::forget("artist.{$artist->id}.full_profile");
        Cache::forget("artist.{$artist->id}.dashboard_stats");

        // Invalider aussi marketplace si artiste y est présent
        $this->invalidateMarketplace();
    }

    /**
     * Invalider cache marketplace (après création/suppression artiste)
     */
    public function invalidateMarketplace(): void
    {
        // Invalider toutes les clés marketplace
        if (config('cache.default') === 'redis') {
            $keys = Cache::getRedis()->keys('marketplace.listings.*');
            if (!empty($keys)) {
                Cache::getRedis()->del($keys);
            }
        }

        // Invalider aussi les données de filtrage
        Cache::forget('available_styles');
        Cache::forget('available_cities');
    }

    /**
     * Invalider tous les caches pour un artiste
     */
    public function invalidateAllArtistCache(Tattooer|Piercer $artist): void
    {
        $this->invalidateArtist($artist);

        // Invalider aussi les caches liés aux médias
        $this->invalidateMediaCache($artist);
    }

    /**
     * Invalider cache médias
     */
    public function invalidateMediaCache(Tattooer|Piercer $artist): void
    {
        $type = $artist instanceof Tattooer ? 'tattooer' : 'Piercer';
        if (config('cache.default') === 'redis') {
            $keys = Cache::getRedis()->keys("{$type}.{$artist->id}.*");
            if (!empty($keys)) {
                Cache::getRedis()->del($keys);
            }
        }
    }

    /**
     * Obtenir les statistiques du cache
     */
    public function getCacheStats(): array
    {
        if (config('cache.default') === 'redis') {
            $redis = Cache::getRedis();
            return [
                'total_keys' => $redis->dbSize(),
                'info' => $redis->info(),
            ];
        } else {
            return [
                'total_keys' => 0,
                'info' => 'Array cache (not Redis)',
            ];
        }
    }

    /**
     * Vider tous les caches (développement)
     */
    public function flushAll(): void
    {
        Cache::flush();
    }

    private function portfolioKey($artist): string
    {
        $type = $artist instanceof Tattooer ? 'tattooer' : 'Piercer';
        return "{$type}.{$artist->id}.portfolio";
    }

    private function getDayName(int $day): string
    {
        $days = [
            0 => 'Dimanche',
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
        ];

        return $days[$day] ?? 'Inconnu';
    }
}

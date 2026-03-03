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
        $type = $artist instanceof Piercer ? 'piercer' : 'tattooer';
        $cacheKey = "{$type}.{$artist->id}.working_hours";

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
        $type = $artist instanceof Piercer ? 'piercer' : 'tattooer';
        $cacheKey = "{$type}.{$artist->id}.full_profile";

        return Cache::remember($cacheKey, self::ARTIST_PROFILE_TTL, function() use ($artist) {
            $artist->load(['user', 'workingHours', 'media']);

            $isPiercer = $artist instanceof Piercer;
            // Charger aussi les reviews des BookingRequests pour cet artiste
            $bookingRequestReviews = \App\Models\Review::where('reviewable_type', 'App\Models\BookingRequest')
                ->whereHas('reviewable', function($query) use ($artist) {
                    $query->where('bookable_id', $artist->id)
                          ->where('bookable_type', get_class($artist));
                })
                ->where('is_visible', true)
                ->get();

            // Fusionner les reviews directs et les reviews de BookingRequests
            $allReviews = $artist->reviews->merge($bookingRequestReviews);

            return [
                'id' => $artist->id,
                'type' => $isPiercer ? 'piercer' : 'tattooer',
                'name' => $artist->pseudo,
                'slug' => $artist->slug,
                'bio' => $artist->bio,
                'studio_name' => $artist->studio_name,
                'styles' => $isPiercer ? ($artist->piercing_types ?? []) : ($artist->styles ?? []),
                'city' => $artist->city,
                'avatar_url' => $this->getAvatarUrl($artist),
                'banner_url' => $artist->hasMedia('banner') ? $artist->getFirstMediaUrl('banner') : '',
                'portfolio_count' => $artist->getMedia('portfolio')->count(),
                'portfolio_images' => $artist->getMedia('portfolio')->map(function($media) {
                    return [
                        'url' => $media->getUrl(),
                        'thumb' => $media->getUrl('thumb'),
                    ];
                })->toArray(),
                'average_rating' => $allReviews->avg('rating') ?? 0,
                'total_reviews' => $allReviews->count() ?? 0,
                'is_subscribed' => $artist->is_subscribed ?? false,
                'siret_verified' => $artist->siret_verified ?? false,
                'status' => $artist->user?->status ?? 'active',
                'experience_years' => $artist->years_of_experience ?? 0,
                'min_price' => $artist->minimum_price ?? 0,
                'wait_time' => $artist->wait_time_weeks_min
                    ? ($artist->wait_time_weeks_min . ($artist->wait_time_weeks_max ? '–' . $artist->wait_time_weeks_max : '') . ' sem.')
                    : 'Non spécifié',
                'opening_hours' => $artist->opening_hours ?? 'Non spécifié',
                'open_days' => $artist->open_days ?? 'Non spécifié',
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

            // Studios handled separately in MarketplaceController
            if ($artisanType === 'studio') {
                return [];
            }

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

            // PRO en premier, puis par note décroissante
            return $results
                ->sort(function ($a, $b) {
                    $proA = (bool) ($a['is_subscribed'] ?? false);
                    $proB = (bool) ($b['is_subscribed'] ?? false);
                    if ($proA !== $proB) {
                        return $proB <=> $proA; // PRO (true=1) avant FREE (false=0)
                    }
                    return ($b['average_rating'] ?? 0) <=> ($a['average_rating'] ?? 0);
                })
                ->values()
                ->toArray();
        });
    }

    /**
     * Cache stats dashboard (déjà dans TattooerStatsService mais wrapper ici)
     */
    public function getDashboardStats(Tattooer|Piercer $artist): array
    {
        $type = $artist instanceof Piercer ? 'piercer' : 'tattooer';
        $cacheKey = "{$type}.{$artist->id}.dashboard_stats";

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
        $type = $artist instanceof Piercer ? 'piercer' : 'tattooer';
        Cache::forget($this->portfolioKey($artist));
        Cache::forget("{$type}.{$artist->id}.working_hours");
        Cache::forget("{$type}.{$artist->id}.full_profile");
        Cache::forget("{$type}.{$artist->id}.dashboard_stats");

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

    /**
     * Obtenir l'URL de l'avatar avec fallback UI Avatars
     */
    private function getAvatarUrl(Tattooer|Piercer $artist): ?string
    {
        // Essayer d'abord le media Spatie de l'artiste
        if ($artist->hasMedia('avatar')) {
            $avatar = $artist->getFirstMediaUrl('avatar');
            if ($avatar && $avatar !== '/images/default-tattooer-avatar.png') {
                return $avatar;
            }
        }

        // Essayer le media Spatie de l'utilisateur associé
        if ($artist->user && $artist->user->hasMedia('avatar')) {
            $userAvatar = $artist->user->getFirstMediaUrl('avatar');
            if ($userAvatar && $userAvatar !== '/images/default-tattooer-avatar.png') {
                return $userAvatar;
            }
        }

        // Fallback vers UI Avatars avec le pseudo ou nom
        $name = $artist->pseudo ?: $artist->user?->first_name . ' ' . $artist->user?->last_name ?: 'Artiste';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=ffffff&background=8B7355&size=200';
    }
}

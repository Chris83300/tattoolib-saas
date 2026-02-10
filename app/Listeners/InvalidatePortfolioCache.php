<?php

namespace App\Listeners;

use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenUpdatedEvent;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenDeletedEvent;
use App\Services\CacheService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class InvalidatePortfolioCache
{
    /**
     * Handle media added event.
     */
    public function handleMediaAdded(MediaHasBeenAddedEvent $event): void
    {
        $this->invalidateCacheForMedia($event->media);
    }

    /**
     * Handle media updated event.
     */
    public function handleMediaUpdated(MediaHasBeenUpdatedEvent $event): void
    {
        $this->invalidateCacheForMedia($event->media);
    }

    /**
     * Handle media deleted event.
     */
    public function handleMediaDeleted(MediaHasBeenDeletedEvent $event): void
    {
        $this->invalidateCacheForMedia($event->media);
    }

    /**
     * Invalidate cache when media is modified.
     */
    private function invalidateCacheForMedia($media): void
    {
        $model = $media->model;
        $collection = $media->collection_name;

        // Si upload/modification/suppression dans portfolio, banner ou avatar
        if (in_array($collection, ['portfolio', 'banner', 'avatar'])) {
            if ($model && method_exists($model, 'getCacheKey')) {
                app(CacheService::class)->invalidateArtist($model);
            } else {
                // Gérer selon le type de modèle
                if ($model instanceof \App\Models\User) {
                    // Pour les User (clients), invalider le cache client
                    $this->invalidateUserCache($model);
                } elseif ($model instanceof \App\Models\Tattooer || $model instanceof \App\Models\Pierceur) {
                    // Pour les artistes, utiliser la méthode existante
                    app(CacheService::class)->invalidateMediaCache($model);
                }
            }
        }
    }

    /**
     * Invalider le cache pour un User (client)
     */
    private function invalidateUserCache(\App\Models\User $user): void
    {
        // Invalider le cache du profil client
        $keys = [
            "client.profile.{$user->id}",
            "client.settings.{$user->id}",
            "client.avatar.{$user->id}",
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Invalider aussi les clés Redis si disponible (uniquement si le driver est Redis)
        try {
            if (config('cache.default') === 'redis' && method_exists(Cache::store(), 'getRedis')) {
                $redisKeys = Cache::getRedis()->keys("client.{$user->id}.*");
                if (!empty($redisKeys)) {
                    Cache::getRedis()->del($redisKeys);
                }
            }
        } catch (\Exception $e) {
            // Ignorer les erreurs Redis, continuer avec le cache normal
            Log::warning('Redis cache error, continuing with normal cache: ' . $e->getMessage());
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe($events): void
    {
        $events->listen(
            MediaHasBeenAddedEvent::class,
            [self::class, 'handleMediaAdded']
        );

        $events->listen(
            MediaHasBeenUpdatedEvent::class,
            [self::class, 'handleMediaUpdated']
        );

        $events->listen(
            MediaHasBeenDeletedEvent::class,
            [self::class, 'handleMediaDeleted']
        );
    }
}

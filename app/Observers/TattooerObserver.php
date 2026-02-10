<?php

namespace App\Observers;

use App\Models\Tattooer;
use App\Services\CacheService;

class TattooerObserver
{
    /**
     * Handle the Tattooer "updated" event.
     */
    public function updated(Tattooer $tattooer): void
    {
        // Invalider cache si champs importants changent
        $dirtyFields = [
            'name', 'bio', 'styles', 'city', 'status', 
            'siret_verified', 'is_subscribed', 'slug'
        ];
        
        if ($tattooer->isDirty($dirtyFields)) {
            app(CacheService::class)->invalidateArtist($tattooer);
            app(CacheService::class)->invalidateMarketplace();
        }
    }

    /**
     * Handle the Tattooer "created" event.
     */
    public function created(Tattooer $tattooer): void
    {
        // Invalider marketplace pour inclure le nouvel artiste
        app(CacheService::class)->invalidateMarketplace();
    }

    /**
     * Handle the Tattooer "deleted" event.
     */
    public function deleted(Tattooer $tattooer): void
    {
        // Invalider tous les caches de l'artiste
        app(CacheService::class)->invalidateAllArtistCache($tattooer);
        app(CacheService::class)->invalidateMarketplace();
    }

    /**
     * Handle the Tattooer "restored" event.
     */
    public function restored(Tattooer $tattooer): void
    {
        app(CacheService::class)->invalidateArtist($tattooer);
        app(CacheService::class)->invalidateMarketplace();
    }
}

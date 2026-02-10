<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tattooer;
use App\Services\CacheService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class CacheTest extends TestCase
{
    use RefreshDatabase;

    private User $tattooerUser;
    private Tattooer $tattooer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tattooerUser = User::factory()->tattooer()->create();
        $this->tattooer = Tattooer::factory()->create([
            'user_id' => $this->tattooerUser->id,
            'status' => 'active'
        ]);
    }

    /**
     * Test que le portfolio est mis en cache pour 24 heures
     */
    public function test_portfolio_is_cached_for_24_hours(): void
    {
        $cacheService = app(CacheService::class);
        
        // Premier appel (calcule)
        $portfolio1 = $cacheService->getPortfolio($this->tattooer);
        
        // Vérifier que cache existe
        $cacheKey = "tattooer.{$this->tattooer->id}.portfolio";
        expect(Cache::has($cacheKey))->toBeTrue();
        
        // Second appel (depuis cache)
        $portfolio2 = $cacheService->getPortfolio($this->tattooer);
        
        expect($portfolio1)->toEqual($portfolio2);
        expect($portfolio1)->toHaveCount(0); // Portfolio vide initialement
    }

    /**
     * Test que les horaires de travail sont mis en cache
     */
    public function test_working_hours_are_cached(): void
    {
        $cacheService = app(CacheService::class);
        
        // Créer des horaires de travail
        $this->tattooer->workingHours()->createMany([
            [
                'day_of_week' => 1,
                'open_time' => '09:00',
                'close_time' => '18:00',
                'is_closed' => false,
            ],
            [
                'day_of_week' => 2,
                'open_time' => '09:00',
                'close_time' => '18:00',
                'is_closed' => false,
            ],
        ]);
        
        // Premier appel
        $hours1 = $cacheService->getWorkingHours($this->tattooer);
        
        // Vérifier cache
        $cacheKey = "artist.{$this->tattooer->id}.working_hours";
        expect(Cache::has($cacheKey))->toBeTrue();
        
        // Second appel depuis cache
        $hours2 = $cacheService->getWorkingHours($this->tattooer);
        
        expect($hours1)->toEqual($hours2);
        expect($hours1)->toHaveCount(2);
    }

    /**
     * Test que le profil complet est mis en cache
     */
    public function test_artist_profile_is_cached(): void
    {
        $cacheService = app(CacheService::class);
        
        // Premier appel
        $profile1 = $cacheService->getArtistProfile($this->tattooer);
        
        // Vérifier cache
        $cacheKey = "artist.{$this->tattooer->id}.full_profile";
        expect(Cache::has($cacheKey))->toBeTrue();
        
        // Second appel depuis cache
        $profile2 = $cacheService->getArtistProfile($this->tattooer);
        
        expect($profile1)->toEqual($profile2);
        expect($profile1['id'])->toBe($this->tattooer->id);
        expect($profile1['name'])->toBe($this->tattooer->name);
    }

    /**
     * Test que le cache est invalidé lors de la mise à jour du profil
     */
    public function test_cache_is_invalidated_on_profile_update(): void
    {
        $cacheService = app(CacheService::class);
        
        // Créer cache
        $cacheService->getArtistProfile($this->tattooer);
        $cacheKey = "artist.{$this->tattooer->id}.full_profile";
        expect(Cache::has($cacheKey))->toBeTrue();
        
        // Update profil
        $this->tattooer->update(['bio' => 'New bio']);
        
        // Cache doit être invalidé
        expect(Cache::has($cacheKey))->toBeFalse();
    }

    /**
     * Test que les listings marketplace sont mis en cache
     */
    public function test_marketplace_listings_are_cached(): void
    {
        Tattooer::factory()->count(10)->create(['status' => 'active']);
        $cacheService = app(CacheService::class);
        
        $listings = $cacheService->getMarketplaceListings();
        
        expect($listings)->toHaveCount(10);
        expect(Cache::has('marketplace.listings.' . md5('[]')))->toBeTrue();
    }

    /**
     * Test que les filtres marketplace sont mis en cache séparément
     */
    public function test_marketplace_filters_are_cached_separately(): void
    {
        Tattooer::factory()->count(5)->create([
            'status' => 'active',
            'city' => 'Paris'
        ]);
        
        $cacheService = app(CacheService::class);
        
        // Appel sans filtre
        $allListings = $cacheService->getMarketplaceListings([]);
        
        // Appel avec filtre ville
        $parisListings = $cacheService->getMarketplaceListings(['city' => 'Paris']);
        
        expect($allListings)->toHaveCount(5);
        expect($parisListings)->toHaveCount(5);
        
        // Vérifier que les clés sont différentes
        expect(Cache::has('marketplace.listings.' . md5('[]')))->toBeTrue();
        expect(Cache::has('marketplace.listings.' . md5(json_encode(['city' => 'Paris']))))->toBeTrue();
    }

    /**
     * Test que les styles disponibles sont mis en cache
     */
    public function test_available_styles_are_cached(): void
    {
        Tattooer::factory()->create(['styles' => 'realism,traditional']);
        Tattooer::factory()->create(['styles' => 'watercolor,new school']);
        
        $cacheService = app(CacheService::class);
        $styles = $cacheService->getAvailableStyles();
        
        expect($styles)->toContain('realism', 'traditional', 'watercolor', 'new school');
        expect(Cache::has('available_styles'))->toBeTrue();
    }

    /**
     * Test que les villes disponibles sont mises en cache
     */
    public function test_available_cities_are_cached(): void
    {
        Tattooer::factory()->create(['city' => 'Paris']);
        Tattooer::factory()->create(['city' => 'Lyon']);
        Tattooer::factory()->create(['city' => 'Marseille']);
        
        $cacheService = app(CacheService::class);
        $cities = $cacheService->getAvailableCities();
        
        expect($cities)->toContain('Paris', 'Lyon', 'Marseille');
        expect(Cache::has('available_cities'))->toBeTrue();
    }

    /**
     * Test l'invalidation du cache marketplace
     */
    public function test_marketplace_cache_invalidation(): void
    {
        Tattooer::factory()->count(5)->create(['status' => 'active']);
        $cacheService = app(CacheService::class);
        
        // Créer cache
        $cacheService->getMarketplaceListings();
        $cacheService->getAvailableStyles();
        $cacheService->getAvailableCities();
        
        expect(Cache::has('marketplace.listings.' . md5('[]')))->toBeTrue();
        expect(Cache::has('available_styles'))->toBeTrue();
        expect(Cache::has('available_cities'))->toBeTrue();
        
        // Invalider marketplace
        $cacheService->invalidateMarketplace();
        
        expect(Cache::has('marketplace.listings.' . md5('[]')))->toBeFalse();
        expect(Cache::has('available_styles'))->toBeFalse();
        expect(Cache::has('available_cities'))->toBeFalse();
    }

    /**
     * Test l'invalidation complète du cache artiste
     */
    public function test_complete_artist_cache_invalidation(): void
    {
        $cacheService = app(CacheService::class);
        
        // Créer tous les caches
        $cacheService->getPortfolio($this->tattooer);
        $cacheService->getWorkingHours($this->tattooer);
        $cacheService->getArtistProfile($this->tattooer);
        $cacheService->getDashboardStats($this->tattooer);
        
        // Vérifier que tout est en cache
        expect(Cache::has("tattooer.{$this->tattooer->id}.portfolio"))->toBeTrue();
        expect(Cache::has("artist.{$this->tattooer->id}.working_hours"))->toBeTrue();
        expect(Cache::has("artist.{$this->tattooer->id}.full_profile"))->toBeTrue();
        expect(Cache::has("artist.{$this->tattooer->id}.dashboard_stats"))->toBeTrue();
        
        // Invalider tout
        $cacheService->invalidateAllArtistCache($this->tattooer);
        
        // Vérifier que tout est invalidé
        expect(Cache::has("tattooer.{$this->tattooer->id}.portfolio"))->toBeFalse();
        expect(Cache::has("artist.{$this->tattooer->id}.working_hours"))->toBeFalse();
        expect(Cache::has("artist.{$this->tattooer->id}.full_profile"))->toBeFalse();
        expect(Cache::has("artist.{$this->tattooer->id}.dashboard_stats"))->toBeFalse();
    }

    /**
     * Test les statistiques du cache
     */
    public function test_cache_statistics(): void
    {
        $cacheService = app(CacheService::class);
        
        // Créer quelques entrées de cache
        $cacheService->getPortfolio($this->tattooer);
        $cacheService->getWorkingHours($this->tattooer);
        
        $stats = $cacheService->getCacheStats();
        
        expect($stats)->toHaveKey('total_keys');
        expect($stats)->toHaveKey('memory_usage');
        expect($stats)->toHaveKey('hit_rate');
        expect($stats['total_keys'])->toBeGreaterThan(0);
    }
}

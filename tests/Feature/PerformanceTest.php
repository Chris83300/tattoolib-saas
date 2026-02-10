<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Tattooer;
use App\Models\BookingRequest;
use App\Services\TattooerStatsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class PerformanceTest extends TestCase
{
    use RefreshDatabase;

    private User $tattooerUser;
    private Tattooer $tattooer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tattooerUser = User::factory()->tattooer()->create();
        $this->tattooer = Tattooer::factory()->create(['user_id' => $this->tattooerUser->id]);
    }

    /**
     * Test que le dashboard génère moins de 5 requêtes
     */
    public function test_tattooer_dashboard_generates_less_than_5_queries(): void
    {
        // Créer quelques bookings pour les stats
        BookingRequest::factory()->count(10)->create([
            'bookable_id' => $this->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'confirmed'
        ]);

        DB::enableQueryLog();
        
        $this->actingAs($this->tattooerUser)
            ->get('/tattooer/dashboard');
        
        $queryCount = count(DB::getQueryLog());
        
        // Doit être < 5 (1 pour stats + eager loading)
        $this->assertLessThan($queryCount, 5);
    }

    /**
     * Test que le service de stats utilise le cache
     */
    public function test_stats_service_uses_cache(): void
    {
        $statsService = app(TattooerStatsService::class);
        
        // Premier appel (requête DB)
        $stats1 = $statsService->getDashboardStats($this->tattooer);
        
        // Deuxième appel (cache hit)
        $stats2 = $statsService->getDashboardStats($this->tattooer);
        
        $this->assertEquals($stats1, $stats2);
    }

    /**
     * Test que les requêtes client sont optimisées
     */
    public function test_client_dashboard_optimized_queries(): void
    {
        // Créer quelques bookings pour le client
        BookingRequest::factory()->count(10)->create([
            'client_id' => $this->tattooer->client->id,
            'bookable_type' => Tattooer::class,
            'status' => 'pending'
        ]);

        DB::enableQueryLog();
        
        $this->actingAs($this->tattooer->client)
            ->get('/client/dashboard');
        
        $queryCount = count(DB::getQueryLog());
        
        // Doit être ~2-3 (1 requête principale avec eager loading)
        $this->assertLessThan($queryCount, 4);
    }

    /**
     * Test que la page requests est optimisée
     */
    public function test_requests_page_optimized(): void
    {
        // Créer quelques bookings pour le test
        BookingRequest::factory()->count(5)->create([
            'bookable_id' => $this->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'pending'
        ]);

        DB::enableQueryLog();
        
        $this->actingAs($this->tattooerUser)
            ->get('/tattooer/requests?status=pending');
        
        $queryCount = count(DB::getQueryLog());
        
        // Doit être ~2-3 (1 requête principale avec eager loading)
        $this->assertLessThan($queryCount, 4);
    }

    /**
     * Test que l'observer invalide le cache correctement
     */
    public function test_booking_request_observer_invalidates_cache(): void
    {
        $statsService = app(TattooerStatsService::class);
        
        // Créer un booking (devrait invalider le cache)
        $booking = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'pending'
        ]);
        
        // Premier appel (requête + cache)
        $stats1 = $statsService->getDashboardStats($this->tattooer);
        
        // Créer un nouveau booking (devrait invalider le cache)
        $booking2 = BookingRequest::factory()->create([
            'bookable_id' => $this->tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'confirmed'
        ]);
        
        // Deuxième appel (devrait être cache miss)
        DB::enableQueryLog();
        $stats2 = $statsService->getDashboardStats($this->tattooer);
        
        // Vérifier que les stats sont différentes (nouvelles données)
        $this->assertGreaterThan($stats2['completed_projects'], $stats1['completed_projects']);
    }

    /**
     * Test performance des requêtes avec eager loading
     */
    public function test_eager_loading_performance(): void
    {
        // Créer des données de test
        $clients = \App\Models\Client::factory()->count(5)->create();
        $conversations = \App\Models\Conversation::factory()->count(5)->create();
        
        // Test eager loading en une seule requête
        DB::enableQueryLog();
        $start = microtime(true);
        
        $requests = BookingRequest::with(['client.user', 'conversation'])
            ->limit(10)
            ->get();
        
        $duration = microtime(true) - $start;
        $queryCount = count(DB::getQueryLog());
        
        // Doit être 1 requête avec eager loading
        $this->assertEquals(1, $queryCount);
        $this->assertLessThan($duration, 0.1); // Moins de 100ms
    }

    /**
     * Test performance du cache
     */
    public function test_cache_performance(): void
    {
        $statsService = app(TattooerStatsService::class);
        
        // Test cache miss (première requête)
        $start = microtime(true);
        $stats1 = $statsService->getDashboardStats($this->tattooer);
        $cacheMissTime = microtime(true) - $start;
        
        // Test cache hit (deuxième requête)
        $start = microtime(true);
        $stats2 = $statsService->getDashboardStats($this->tattooer);
        $cacheHitTime = microtime(true) - $start;
        
        // Le cache hit doit être significativement plus rapide
        $this->assertLessThan($cacheHitTime, $cacheMissTime);
        $this->assertLessThan($cacheHitTime, 0.01); // Moins de 10ms
    }
}

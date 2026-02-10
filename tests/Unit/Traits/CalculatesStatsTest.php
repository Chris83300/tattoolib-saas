<?php

namespace Tests\Unit\Traits;

use App\Models\Tattooer;
use App\Models\BookingRequest;
use App\Traits\CalculatesStats;
use Tests\TestCase;

class CalculatesStatsTest extends TestCase
{
    /**
     * Setup test environment
     */
    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test trait provides stats methods
     */
    public function test_trait_provides_stats_methods(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        expect($tattooer)->toHaveMethod('getBookingStats');
        expect($tattooer)->toHaveMethod('getMonthlyEarnings');
        expect($tattooer)->toHaveMethod('getAcceptanceRate');
        expect($tattooer)->toHaveMethod('getAverageResponseTime');
        expect($tattooer)->toHaveMethod('getMonthlyStats');
        expect($tattooer)->toHaveMethod('getTopClients');
        expect($tattooer)->toHaveMethod('getConversionRate');
        expect($tattooer)->toHaveMethod('getServiceStats');
    }

    /**
     * Test calculates booking stats correctly
     */
    public function test_calculates_booking_stats_correctly(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        // Créer bookings de test
        BookingRequest::factory()->count(3)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'confirmed',
            'deposit_amount' => 50,
        ]);
        
        BookingRequest::factory()->count(2)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'pending',
        ]);
        
        $stats = $tattooer->getBookingStats();
        
        expect($stats['completed_projects'])->toBe(3);
        expect($stats['active_projects'])->toBe(2);
        expect($stats['total_earnings'])->toBe(150.0);
        expect($stats['total_deposits'])->toBe(150.0);
    }

    /**
     * Test calculates acceptance rate correctly
     */
    public function test_calculates_acceptance_rate_correctly(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        // 7 acceptés sur 10 = 70%
        BookingRequest::factory()->count(7)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'accepted',
        ]);
        
        BookingRequest::factory()->count(3)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'rejected',
        ]);
        
        expect($tattooer->getAcceptanceRate())->toBe(70.0);
    }

    /**
     * Test calculates monthly earnings correctly
     */
    public function test_calculates_monthly_earnings_correctly(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        BookingRequest::factory()->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'confirmed',
            'deposit_amount' => 100,
            'confirmed_at' => now()->createFromDate('2024-01-15'),
        ]);
        
        $monthlyEarnings = $tattooer->getMonthlyEarnings(2024, 1);
        
        expect($monthlyEarnings)->toBe(100.0);
    }

    /**
     * Test calculates yearly earnings correctly
     */
    public function test_calculates_yearly_earnings_correctly(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        BookingRequest::factory()->count(12)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'confirmed',
            'deposit_amount' => 50,
            'confirmed_at' => now()->createFromDate('2024-01-01'),
        ]);
        
        $yearlyEarnings = $tattooer->getYearlyEarnings(2024);
        
        expect($yearlyEarnings)->toBe(600.0); // 12 * 50
    }

    /**
     * Test calculates average response time correctly
     */
    public function test_calculates_average_response_time_correctly(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        // Créer bookings avec temps de réponse différents
        BookingRequest::factory()->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'accepted',
            'created_at' => now()->subHours(2), // 2 heures avant
            'accepted_at' => now(),
        ]);
        
        BookingRequest::factory()->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'accepted',
            'created_at' => now()->subHours(4), // 4 heures avant
            'accepted_at' => now(),
        ]);
        
        $avgTime = $tattooer->getAverageResponseTime();
        
        expect($avgTime)->toBe(3.0); // Moyenne de 3 heures
    }

    /**
     * Test calculates conversion rate correctly
     */
    public function test_calculates_conversion_rate_correctly(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        // 5 confirmés sur 10 demandes = 50%
        BookingRequest::factory()->count(5)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'confirmed',
        ]);
        
        BookingRequest::factory()->count(5)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'cancelled',
        ]);
        
        expect($tattooer->getConversionRate())->toBe(50.0);
    }

    /**
     * Test monthly stats structure
     */
    public function test_monthly_stats_structure(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        BookingRequest::factory()->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'status' => 'confirmed',
            'deposit_amount' => 100,
            'created_at' => now()->createFromDate('2024-03-15'),
        ]);
        
        $monthlyStats = $tattooer->getMonthlyStats(2024);
        
        expect($monthlyStats)->toBeArray();
        expect($monthlyStats)->toHaveCount(12); // 12 mois
        
        $march = $monthlyStats[2]; // Mars
        expect($march['month'])->toBe(3);
        expect($march['month_name'])->toBe('March');
        expect($march['earnings'])->toBe(100.0);
        expect($march['bookings_count'])->toBe(1);
    }

    /**
     * Test top clients functionality
     */
    public function test_top_clients_functionality(): void
    {
        $tattooer = Tattooer::factory()->create();
        
        $client1 = $this->createClientWithBookings($tattooer, 200, 2);
        $client2 = $this->createClientWithBookings($tattooer, 150, 1);
        
        $topClients = $tattooer->getTopClients(2);
        
        expect($topClients)->toHaveCount(2);
        expect($topClients[0]['total_spent'])->toBe(200.0);
        expect($topClients[1]['total_spent'])->toBe(150.0);
        expect($topClients[0]['bookings_count'])->toBe(2);
        expect($topClients[1]['bookings_count'])->toBe(1);
    }

    /**
     * Helper to create client with bookings
     */
    private function createClientWithBookings($tattooer, int $totalSpent, int $bookingCount)
    {
        $client = \App\Models\Client::factory()->create();
        
        BookingRequest::factory()->count($bookingCount)->create([
            'bookable_id' => $tattooer->id,
            'bookable_type' => Tattooer::class,
            'client_id' => $client->id,
            'status' => 'confirmed',
            'deposit_amount' => $totalSpent / $bookingCount,
        ]);
        
        return $client;
    }
}

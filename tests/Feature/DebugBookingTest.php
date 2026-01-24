<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\User;
use App\Models\WorkingHour;
use App\Models\Availability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DebugBookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_booking_request_creation()
    {
        // 1. Setup
        $clientUser = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        $artistUser = User::factory()->create(['is_studio_artist' => true]);
        $artist = StudioArtist::factory()->create([
            'user_id' => $artistUser->id,
            'siret_verified' => true,
            'stripe_onboarding_complete' => true,
        ]);

        // 2. Créer les availabilities
        WorkingHour::create([
            'owner_type' => StudioArtist::class,
            'owner_id' => $artistUser->id,
            'day_of_week' => now()->addDays(5)->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00'
        ]);

        $targetDate = now()->addDays(5);
        Availability::generateFromWorkingHours(
            $artistUser->id,
            $targetDate,
            $targetDate
        );

        // 3. Vérifier que les availabilities existent
        $this->assertDatabaseHas('availabilities', [
            'owner_type' => StudioArtist::class,
            'owner_id' => $artistUser->id,
            'date' => $targetDate->format('Y-m-d'),
            'type' => 'available'
        ]);

        // 4. Créer le booking request
        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'preferred_date' => $targetDate->format('Y-m-d'),
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $this->assertEquals(BookingRequest::STATUS_PENDING, $bookingRequest->status);
        $this->assertEquals($client->id, $bookingRequest->client_id);
        $this->assertEquals(StudioArtist::class, $bookingRequest->bookable_type);
        $this->assertEquals($artist->id, $bookingRequest->bookable_id);
    }

    /** @test */
    public function test_booking_accept_endpoint()
    {
        // 1. Setup
        $clientUser = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        $artistUser = User::factory()->create(['is_studio_artist' => true]);
        $artist = StudioArtist::factory()->create([
            'user_id' => $artistUser->id,
            'siret_verified' => true,
            'stripe_onboarding_complete' => true,
        ]);

        // 2. Créer availabilities
        WorkingHour::create([
            'owner_type' => StudioArtist::class,
            'owner_id' => $artist->id,
            'day_of_week' => now()->addDays(5)->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00'
        ]);

        $targetDate = now()->addDays(5);
        Availability::generateFromWorkingHours(
            $artist->id,
            $targetDate,
            $targetDate
        );

        // 3. Créer booking request
        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'preferred_date' => $targetDate->format('Y-m-d'),
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        // 4. Tester l'endpoint d'acceptation
        $acceptData = [
            'scheduled_date' => $targetDate->format('Y-m-d'),
            'scheduled_start_time' => '14:00',
            'scheduled_duration_minutes' => 180,
            'total_price' => 300,
            'deposit_rate' => 30,
            'deposit_deadline_hours' => 72
        ];

        $response = $this->actingAs($artistUser)
            ->postJson("/api/booking-requests/{$bookingRequest->id}/accept", $acceptData);

        if ($response->status() !== 200) {
            dump([
                'status' => $response->status(),
                'json' => $response->json(),
                'exception' => $response->exception ? $response->exception->getMessage() : 'No exception',
                'headers' => $response->headers
            ]);
        }

        $response->assertStatus(200);
    }
}

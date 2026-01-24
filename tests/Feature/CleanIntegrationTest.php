<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CleanIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_clean_workflow_with_studio_artists()
    {
        // 1. Setup des utilisateurs
        $clientUser = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        $artistUser = User::factory()->create();
        $artist = StudioArtist::factory()->create([
            'user_id' => $artistUser->id,
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);

        // 2. Créer les availabilities via WorkingHours
        \App\Models\WorkingHour::factory()->create([
            'owner_type' => StudioArtist::class,
            'owner_id' => $artist->id,
            'day_of_week' => now()->addDays(5)->dayOfWeek,
            'is_open' => true,
            'start_time' => '09:00',
            'end_time' => '18:00',
            'break_start' => '12:00',
            'break_end' => '13:00'
        ]);

        // 3. Client crée une demande
        $targetDate = now()->addDays(5);
        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'preferred_date' => $targetDate->format('Y-m-d'),
            'preferred_time_slot' => 'afternoon',
            'preferred_time_notes' => 'Créneau flexible',
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        // 4. Vérifications
        $this->assertEquals(BookingRequest::STATUS_PENDING, $bookingRequest->status);
        $this->assertEquals($client->id, $bookingRequest->client_id);
        $this->assertEquals(StudioArtist::class, $bookingRequest->bookable_type);
        $this->assertEquals($artist->id, $bookingRequest->bookable_id);

        // 5. Test des relations
        $this->assertInstanceOf(Client::class, $bookingRequest->client);
        $this->assertInstanceOf(StudioArtist::class, $bookingRequest->bookable);
    }

    /** @test */
    public function test_studio_artist_booking_acceptance()
    {
        $client = Client::factory()->create();

        $artistUser = User::factory()->create(['is_studio_artist' => true]);
        $artist = StudioArtist::factory()->create(['user_id' => $artistUser->id]);

        // Créer des availabilities pour le StudioArtist
        Availability::factory()
            ->state([
                'owner_type' => StudioArtist::class,
                'owner_id' => $artist->id, // Utiliser l'ID du studio artist
                'date' => now()->addDays(7)->format('Y-m-d'),
                'start_time' => '09:00',
                'end_time' => '18:00',
                'type' => 'available',
                'source' => 'working_hours'
            ])
            ->create();

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        // Artist accepte le booking
        $acceptData = [
            'total_price' => 500.00,
            'scheduled_date' => now()->addDays(7)->format('Y-m-d'),
            'scheduled_start_time' => '14:00',
            'scheduled_duration_minutes' => 120,
            'deposit_rate' => 30,
            'deposit_deadline_hours' => 72
        ];

        $response = $this->actingAs($artist->user)
            ->postJson("/api/booking-requests/{$booking->id}/accept", $acceptData);

        if ($response->status() !== 200) {
            dump([
                'status' => $response->status(),
                'json' => $response->json(),
                'exception' => $response->exception ? $response->exception->getMessage() : 'No exception'
            ]);
        }

        // Vérifier que le booking a été mis à jour
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'total_price' => 500.00,
        ]);

        $this->assertTrue(true);
    }

    /** @test */
    public function test_payment_workflow_with_studio_artist()
    {
        $client = Client::factory()->create();
        $artist = StudioArtist::factory()->create([
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'estimated_price' => 300.00,
        ]);

        // Test du calcul de dépôt
        $this->assertEquals(90.00, $booking->calculateDepositAmount());

        // Vérifier que les données sont bien créées
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking->id,
            'client_id' => $client->id,
            'estimated_price' => 300.00,
        ]);

        $this->assertTrue(true);
    }
}

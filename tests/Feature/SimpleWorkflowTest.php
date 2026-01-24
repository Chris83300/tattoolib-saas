<?php

namespace Tests\Feature;

use App\Models\Availability;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\Tattooer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SimpleWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_basic_studio_artist_workflow()
    {
        // 1. Setup des utilisateurs
        $clientUser = User::factory()->create();
        $client = Client::factory()->create(['user_id' => $clientUser->id]);

        $artistUser = User::factory()->create();
        $artist = Tattooer::factory()->create([
            'user_id' => $artistUser->id,
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);

        // 2. Client crée une demande
        $targetDate = now()->addDays(5);
        $bookingRequest = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => Tattooer::class,
            'bookable_id' => $artist->id,
            'preferred_date' => $targetDate->format('Y-m-d'),
            'preferred_time_slot' => 'afternoon',
            'preferred_time_notes' => 'Créneau flexible',
            'status' => BookingRequest::STATUS_PENDING,
            'estimated_price' => 400.00,
        ]);

        // 3. Vérifications
        $this->assertEquals(BookingRequest::STATUS_PENDING, $bookingRequest->status);
        $this->assertEquals($client->id, $bookingRequest->client_id);
        $this->assertEquals(Tattooer::class, $bookingRequest->bookable_type);
        $this->assertEquals($artist->id, $bookingRequest->bookable_id);
        $this->assertEquals(400.00, $bookingRequest->estimated_price);

        // 4. Test des relations
        $this->assertInstanceOf(Client::class, $bookingRequest->client);
        $this->assertInstanceOf(Tattooer::class, $bookingRequest->bookable);

        // 5. Test du calcul de dépôt
        $this->assertEquals(120.00, $bookingRequest->calculateDepositAmount());

        $this->assertTrue(true);
    }

    /** @test */
    public function test_booking_acceptance_workflow()
    {
        $client = Client::factory()->create();
        $artist = Tattooer::factory()->create();

        // Créer des availabilities pour le tatoueur
        Availability::create([
            'owner_type' => Tattooer::class,
            'owner_id' => $artist->id, // Utiliser l'ID du tattooer
            'date' => now()->addDays(7)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '18:00',
            'type' => 'available',
            'source' => 'working_hours',
            'notes' => null,
            'is_recurring' => false,
            'recurring_pattern' => null,
            'recurring_end_date' => null,
            'appointment_id' => null,
        ]);

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => Tattooer::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_PENDING,
            'estimated_price' => 300.00,
        ]);

        // Artist accepte le booking
        $acceptData = [
            'scheduled_date' => now()->addDays(7)->format('Y-m-d'),
            'scheduled_start_time' => '14:00',
            'scheduled_duration_minutes' => 120,
            'total_price' => 500.00,
            'deposit_rate' => 30,
            'deposit_deadline_hours' => 24,
        ];

        $response = $this->actingAs($artist->user)
            ->postJson("/api/booking-requests/{$booking->id}/accept", $acceptData);

        // Vérifier que la réponse est successful
        $response->assertStatus(200);

        // Vérifier que le booking a été mis à jour
        $this->assertDatabaseHas('booking_requests', [
            'id' => $booking->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'total_price' => 500.00,
        ]);

        $this->assertTrue(true);
    }

    /** @test */
    public function test_payment_workflow()
    {
        $client = Client::factory()->create();
        $artist = Tattooer::factory()->create([
            'stripe_connect_account_id' => 'acct_test_' . uniqid(),
        ]);

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => Tattooer::class,
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

<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\BookingRequest;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingWorkflowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_follows_complete_booking_workflow()
    {
        // Créer artiste
        $artist = StudioArtist::factory()->create([
            'artist_name' => 'Workflow Test Artist',
            'status' => 'active',
        ]);

        // Créer client
        $client = Client::factory()->create();

        // Étape 1: Créer booking
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_PENDING,
            'tattoo_size' => 'large',
            'body_zone' => 'back',
            'description' => 'Test workflow complet',
            'estimated_price' => 500.00,
        ]);

        // Étape 2: Accepter booking
        $booking->update(['status' => BookingRequest::STATUS_ACCEPTED]);

        // Étape 3: Créer payment
        $payment = Payment::factory()->create([
            'booking_request_id' => $booking->id,
            'amount' => 150.00, // 30% de 500€
            'status' => 'succeeded',
            'payment_type' => 'deposit',
            'paid_at' => now(),
        ]);

        // Étape 4: Marquer acompte payé
        $booking->update(['deposit_paid_at' => now()]);

        // Assertions
        $this->assertEquals(BookingRequest::STATUS_ACCEPTED, $booking->status);
        $this->assertEquals(150.00, $payment->amount);
        $this->assertNotNull($booking->deposit_paid_at);
        $this->assertEquals(500.00, $booking->estimated_price);
    }

    /** @test */
    public function it_prevents_duplicate_payments()
    {
        $artist = StudioArtist::factory()->create();
        $client = Client::factory()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_id' => $artist->id,
            'bookable_type' => StudioArtist::class,
        ]);

        // Créer premier payment
        Payment::factory()->create([
            'booking_request_id' => $booking->id,
            'amount' => 60.00,
            'status' => 'succeeded',
            'payment_type' => 'deposit',
        ]);

        // Tenter de créer un deuxième payment
        $response = $this->actingAs($client->user)
            ->postJson("/api/bookings/{$booking->id}/payment/deposit");

        $response->assertStatus(400)
            ->assertJson(['message' => 'Deposit already paid']);
    }

    /** @test */
    public function it_validates_booking_status()
    {
        $artist = StudioArtist::factory()->create();
        $client = Client::factory()->create();

        // Test avec booking pending
        $bookingPending = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_id' => $artist->id,
            'bookable_type' => StudioArtist::class,
            'status' => BookingRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($client->user)
            ->postJson("/api/bookings/{$bookingPending->id}/payment/deposit");

        $response->assertStatus(400)
            ->assertJson(['message' => 'Booking must be accepted']);

        // Test avec booking rejected
        $bookingRejected = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_id' => $artist->id,
            'bookable_type' => StudioArtist::class,
            'status' => BookingRequest::STATUS_REJECTED,
        ]);

        $response = $this->actingAs($client->user)
            ->postJson("/api/bookings/{$bookingRejected->id}/payment/deposit");

        $response->assertStatus(400)
            ->assertJson(['message' => 'Booking must be accepted']);
    }
}

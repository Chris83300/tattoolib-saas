<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\BookingRequest;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestStripePayment extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_stripe_payment_creation()
    {
        $artist = StudioArtist::factory()->create([
            'stripe_connect_account_id' => 'acct_test_123456789',
            'artist_name' => 'Test Artist',
            'status' => 'active',
        ]);

        $client = Client::factory()->create();

        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'estimated_price' => 200.00,
        ]);

        $paymentData = [
            'booking_request_id' => $booking->id,
        ];

        $response = $this->actingAs($client->user)
            ->postJson("/api/bookings/{$booking->id}/payment/deposit", $paymentData);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'client_secret',
                'payment_id',
                'amount',
                'currency',
            ]);

        $this->assertDatabaseHas('payments', [
            'booking_request_id' => $booking->id,
            'amount' => 60.00,
            'status' => 'pending',
            'payment_type' => 'deposit',
        ]);
    }
}

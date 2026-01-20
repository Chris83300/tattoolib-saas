<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\StudioArtist;
use App\Models\BookingRequest;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class StripePaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_payment_intent_with_direct_charges()
    {
        // Créer artiste avec Stripe Account
        $artist = StudioArtist::factory()->create([
            'stripe_connect_account_id' => 'acct_test_123456789',
            'artist_name' => 'Test Artist',
            'status' => 'active',
        ]);

        // Créer client
        $client = Client::factory()->create();

        // Créer booking accepté avec prix
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_type' => StudioArtist::class,
            'bookable_id' => $artist->id,
            'status' => BookingRequest::STATUS_ACCEPTED,
            'estimated_price' => 200.00,
        ]);

        // Données de test
        $paymentData = [
            'booking_request_id' => $booking->id,
        ];

        // Simuler la requête API
        $response = $this->actingAs($client->user)
            ->postJson("/api/bookings/{$booking->id}/payment/deposit", $paymentData);

        // Assertions
        $response->assertStatus(200)
            ->assertJsonStructure([
                'client_secret',
                'payment_id',
                'amount',
                'currency',
            ]);

        // Vérifier que le payment a été créé
        $this->assertDatabaseHas('payments', [
            'booking_request_id' => $booking->id,
            'amount' => 60.00, // 30% de 200€
            'status' => 'pending',
            'payment_type' => 'deposit',
        ]);
    }

    /** @test */
    public function it_requires_authentication_to_create_payment()
    {
        $booking = BookingRequest::factory()->create();

        // Tenter sans authentification
        $response = $this->postJson("/api/bookings/{$booking->id}/payment/deposit", []);

        $response->assertStatus(401);
    }

    /** @test */
    public function it_validates_booking_ownership()
    {
        // Créer deux clients
        $client1 = Client::factory()->create();
        $client2 = Client::factory()->create();

        $artist = StudioArtist::factory()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client1->id,
            'bookable_id' => $artist->id,
            'bookable_type' => StudioArtist::class,
        ]);

        // Tenter avec un autre client
        $response = $this->actingAs($client2->user)
            ->postJson("/api/bookings/{$booking->id}/payment/deposit", []);

        $response->assertStatus(403)
            ->assertJson(['message' => 'Unauthorized']);
    }

    /** @test */
    public function it_calculates_deposit_amount_correctly()
    {
        Config::set('services.stripe.default_deposit_percentage', 25);

        $artist = StudioArtist::factory()->create();
        $client = Client::factory()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->id,
            'bookable_id' => $artist->id,
            'bookable_type' => StudioArtist::class,
            'estimated_price' => 300.00,
        ]);

        $response = $this->actingAs($client->user)
            ->postJson("/api/bookings/{$booking->id}/payment/deposit");

        // 25% de 300€ = 75€
        $response->assertJsonFragment(['amount' => 75.00]);
    }
}

<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\BookingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class StripePaymentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function validates_payment_intent_before_confirming_deposit()
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
            'total_deposit_amount' => 90,
        ]);

        // PaymentIntent ID invalide
        $response = $this->actingAs($client, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/confirm-deposit", [
                'payment_intent_id' => 'invalid_id',
            ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function verifies_payment_amount_matches_deposit()
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
            'total_deposit_amount' => 90,
        ]);

        // Mock Stripe API - montant différent
        Http::fake([
            'api.stripe.com/*' => Http::response([
                'id' => 'pi_test_123',
                'amount' => 5000, // 50€ au lieu de 90€
                'status' => 'succeeded',
            ]),
        ]);

        $response = $this->actingAs($client, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/confirm-deposit", [
                'payment_intent_id' => 'pi_test_123',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'error' => 'Le montant payé ne correspond pas à l\'acompte requis',
        ]);
    }

    /** @test */
    public function webhook_handles_successful_payment()
    {
        $booking = BookingRequest::factory()->create([
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
        ]);

        $payload = [
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test_123',
                    'metadata' => [
                        'booking_request_id' => $booking->id,
                    ],
                ],
            ],
        ];

        // Mock signature Stripe
        $signature = 'valid_signature';

        $response = $this->postJson('/api/stripe/webhook', $payload, [
            'Stripe-Signature' => $signature,
        ]);

        $response->assertOk();
        
        $booking->refresh();
        expect($booking->status)->toBe(BookingRequest::STATUS_DEPOSIT_PAID);
    }

    /** @test */
    public function webhook_rejects_invalid_signature()
    {
        $payload = [
            'type' => 'payment_intent.succeeded',
        ];

        $response = $this->postJson('/api/stripe/webhook', $payload, [
            'Stripe-Signature' => 'invalid_signature',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function prevents_duplicate_payment_confirmation()
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
            'status' => BookingRequest::STATUS_DEPOSIT_PAID, // Déjà payé
            'stripe_payment_intent_id' => 'pi_already_paid',
        ]);

        $response = $this->actingAs($client, 'sanctum')
            ->postJson("/api/booking-requests/{$booking->id}/confirm-deposit", [
                'payment_intent_id' => 'pi_test_new',
            ]);

        $response->assertForbidden();
    }

    /** @test */
    public function uses_idempotency_key_for_stripe_requests()
    {
        $client = User::factory()->client()->create();
        $booking = BookingRequest::factory()->create([
            'client_id' => $client->client->id,
            'status' => BookingRequest::STATUS_AWAITING_DEPOSIT,
        ]);

        Http::fake([
            'api.stripe.com/*' => Http::response(['id' => 'pi_test_123']),
        ]);

        // Première requête
        $response1 = $this->actingAs($client, 'sanctum')
            ->postJson("/api/payments/create-intent", [
                'booking_request_id' => $booking->id,
            ]);

        // Deuxième requête identique (retry)
        $response2 = $this->actingAs($client, 'sanctum')
            ->postJson("/api/payments/create-intent", [
                'booking_request_id' => $booking->id,
            ]);

        // Vérifier qu'Idempotency-Key est présent dans headers
        Http::assertSent(function ($request) {
            return $request->hasHeader('Idempotency-Key');
        });
    }
}

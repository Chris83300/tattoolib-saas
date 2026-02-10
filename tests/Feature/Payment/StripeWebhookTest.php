<?php

namespace Tests\Feature\Payment;

use App\Models\BookingRequest;
use App\Models\BookingTransaction;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Stripe\Webhook;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer un client et un booking request pour les tests
        $this->client = Client::factory()->create();
        $this->user = User::factory()->create(['client_id' => $this->client->id]);
        $this->bookingRequest = BookingRequest::factory()->create([
            'client_id' => $this->client->id,
            'status' => 'accepted',
            'total_deposit_amount' => 150.00,
            'appointment_datetime' => now()->addDays(7),
        ]);
    }

    /** @test */
    public function stripe_webhook_confirms_payment_idempotently()
    {
        // Créer une conversation
        $conversation = $this->bookingRequest->conversation()->create([
            'subject' => 'Test conversation',
            'status' => 'active',
        ]);

        // Simuler un webhook Stripe
        $payload = [
            'id' => 'evt_test_123',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_webhook',
                    'payment_intent' => 'pi_test_webhook',
                    'payment_status' => 'succeeded',
                    'amount_total' => 15000,
                    'metadata' => [
                        'booking_request_id' => $this->bookingRequest->id,
                    ],
                ],
            ],
        ];

        $signature = 'test_signature';
        $headers = ['Stripe-Signature' => $signature];

        // Envoyer le webhook
        $response = $this->post('/webhooks/stripe', $payload, $headers);

        // Vérifier la réponse
        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);

        // Vérifier que le booking request est marqué comme payé
        $this->bookingRequest->refresh();
        $this->assertEquals('deposit_paid', $this->bookingRequest->status);
        $this->assertNotNull($this->bookingRequest->deposit_paid_at);

        // Vérifier que la transaction a été créée
        $transaction = BookingTransaction::where('booking_request_id', $this->bookingRequest->id)
            ->where('type', 'deposit')
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals(150.00, $transaction->amount);
        $this->assertEquals('completed', $transaction->status);

        // Vérifier que l'appointment a été créé
        $appointment = $this->bookingRequest->appointment;
        $this->assertNotNull($appointment);

        // Vérifier que les deadlines de la conversation sont synchronisées
        $conversation->refresh();
        $this->assertNull($conversation->deposit_deadline_at);
        $this->assertNotNull($conversation->expires_at);
        $this->assertEquals('active', $conversation->status);

        // Tester l'idempotence: envoyer le même webhook à nouveau
        $response2 = $this->post('/webhooks/stripe', $payload, $headers);
        $response2->assertStatus(200);

        // Vérifier qu'aucune transaction supplémentaire n'a été créée
        $transactions = BookingTransaction::where('booking_request_id', $this->bookingRequest->id)->get();
        $this->assertCount(1, $transactions);
    }

    /** @test */
    public function stripe_webhook_handles_payment_failed()
    {
        // Créer d'abord une transaction
        $transaction = BookingTransaction::create([
            'booking_request_id' => $this->bookingRequest->id,
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'amount' => 150.00,
            'currency' => 'eur',
            'status' => 'pending',
            'payment_method' => 'stripe',
            'stripe_payment_intent_id' => 'pi_test_failed',
        ]);

        // Simuler un webhook d'échec
        $payload = [
            'id' => 'evt_test_failed',
            'type' => 'payment_intent.payment_failed',
            'data' => [
                'object' => [
                    'id' => 'pi_test_failed',
                    'last_payment_error' => [
                        'message' => 'Your card was declined.',
                    ],
                ],
            ],
        ];

        $signature = 'test_signature';
        $headers = ['Stripe-Signature' => $signature];

        // Envoyer le webhook
        $response = $this->post('/webhooks/stripe', $payload, $headers);

        // Vérifier la réponse
        $response->assertStatus(200);

        // Vérifier que la transaction est marquée comme échouée
        $transaction->refresh();
        $this->assertEquals('failed', $transaction->status);
    }

    /** @test */
    public function stripe_webhook_handles_refunds()
    {
        // Créer d'abord une transaction d'acompte
        $originalTransaction = BookingTransaction::create([
            'booking_request_id' => $this->bookingRequest->id,
            'user_id' => $this->user->id,
            'type' => 'deposit',
            'amount' => 150.00,
            'currency' => 'eur',
            'status' => 'completed',
            'payment_method' => 'stripe',
            'stripe_payment_intent_id' => 'pi_test_refund',
        ]);

        // Simuler un webhook de remboursement
        $payload = [
            'id' => 'evt_test_refund',
            'type' => 'charge.refunded',
            'data' => [
                'object' => [
                    'id' => 'ch_test_refund',
                    'payment_intent' => 'pi_test_refund',
                    'amount_refunded' => 7500, // 75.00€ en cents
                    'currency' => 'eur',
                    'refunds' => [
                        'data' => [
                            [
                                'reason' => 'requested_by_customer',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $signature = 'test_signature';
        $headers = ['Stripe-Signature' => $signature];

        // Envoyer le webhook
        $response = $this->post('/webhooks/stripe', $payload, $headers);

        // Vérifier la réponse
        $response->assertStatus(200);

        // Vérifier qu'une transaction de remboursement a été créée
        $refundTransaction = BookingTransaction::where('booking_request_id', $this->bookingRequest->id)
            ->where('type', 'refund')
            ->first();

        $this->assertNotNull($refundTransaction);
        $this->assertEquals(75.00, $refundTransaction->amount);
        $this->assertEquals('completed', $refundTransaction->status);
        $this->assertEquals('stripe', $refundTransaction->payment_method);

        // Vérifier les métadonnées
        $metadata = $refundTransaction->metadata;
        $this->assertEquals($originalTransaction->id, $metadata['original_transaction_id']);
        $this->assertEquals('ch_test_refund', $metadata['refund_id']);
        $this->assertEquals('requested_by_customer', $metadata['reason']);
    }

    /** @test */
    public function stripe_webhook_ignores_unknown_events()
    {
        // Simuler un webhook avec un événement non géré
        $payload = [
            'id' => 'evt_test_unknown',
            'type' => 'unknown.event',
            'data' => [
                'object' => [
                    'id' => 'test_id',
                ],
            ],
        ];

        $signature = 'test_signature';
        $headers = ['Stripe-Signature' => $signature];

        // Envoyer le webhook
        $response = $this->post('/webhooks/stripe', $payload, $headers);

        // Vérifier que la réponse est quand même 200
        $response->assertStatus(200);

        // Vérifier que rien n'a été modifié
        $this->bookingRequest->refresh();
        $this->assertEquals('accepted', $this->bookingRequest->status);
        $this->assertNull($this->bookingRequest->deposit_paid_at);
    }

    /** @test */
    public function stripe_webhook_rejects_invalid_signature()
    {
        // Simuler un webhook avec une signature invalide
        $payload = [
            'id' => 'evt_test_invalid',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_invalid',
                    'payment_status' => 'succeeded',
                ],
            ],
        ];

        $headers = ['Stripe-Signature' => 'invalid_signature'];

        // Envoyer le webhook
        $response = $this->post('/webhooks/stripe', $payload, $headers);

        // Vérifier que la réponse est une erreur 400
        $response->assertStatus(400);
        $response->assertJson(['error' => 'Invalid signature']);
    }
}

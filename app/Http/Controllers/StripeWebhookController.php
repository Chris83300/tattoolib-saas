<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller
{
    /**
     * Gérer les webhooks Stripe
     *
     * POST /api/stripe/webhook
     */
    public function handleWebhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        try {
            // Vérifier signature webhook
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                $webhookSecret
            );

        } catch (\UnexpectedValueException $e) {
            Log::error('Webhook Stripe : Payload invalide', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid payload'], 400);

        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook Stripe : Signature invalide', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Gérer événement
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;

            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            default:
                Log::info('Webhook Stripe non géré : ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Paiement réussi
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        $bookingRequestId = $paymentIntent->metadata->booking_request_id ?? null;

        if (!$bookingRequestId) {
            Log::error('Webhook : booking_request_id manquant', [
                'payment_intent_id' => $paymentIntent->id
            ]);
            return;
        }

        DB::transaction(function () use ($paymentIntent, $bookingRequestId) {

            // 1. Mettre à jour Payment
            $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'succeeded',
                    'paid_at' => now(),
                ]);
            }

            // 2. Mettre à jour BookingRequest
            $bookingRequest = BookingRequest::find($bookingRequestId);

            if ($bookingRequest) {
                $bookingRequest->update([
                    'deposit_paid_at' => now(),
                    'status' => BookingRequest::STATUS_DEPOSIT_PAID,
                ]);

                Log::info('Acompte payé avec succès', [
                    'booking_request_id' => $bookingRequestId,
                    'amount' => $paymentIntent->amount / 100,
                ]);
            }
        });
    }

    /**
     * Paiement échoué
     */
    private function handlePaymentFailed($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)
            ->first();

        if ($payment) {
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
            ]);

            Log::warning('Paiement échoué', [
                'payment_id' => $payment->id,
                'reason' => $paymentIntent->last_payment_error->message ?? 'Unknown',
            ]);
        }
    }
}

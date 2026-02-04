<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Gérer les webhooks Stripe
     *
     * POST /api/stripe/webhook
     */
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');
        
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
        } catch (SignatureVerificationException $e) {
            Log::error('Webhook Stripe: Signature invalide', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }
        
        // Gérer événements
        switch ($event->type) {
            case 'checkout.session.completed':
                $this->handleCheckoutCompleted($event->data->object);
                break;
                
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
                
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
                
            default:
                Log::info('Stripe event non géré: ' . $event->type);
        }
        
        return response()->json(['status' => 'success']);
    }
    
    /**
     * Gérer la complétion d'une session checkout
     */
    protected function handleCheckoutCompleted($session)
    {
        $bookingRequestId = $session->metadata->booking_request_id ?? null;
        
        if (!$bookingRequestId) {
            Log::error('Webhook Stripe: booking_request_id manquant');
            return;
        }
        
        $bookingRequest = BookingRequest::find($bookingRequestId);
        
        if (!$bookingRequest) {
            Log::error('Webhook Stripe: BookingRequest non trouvé', ['id' => $bookingRequestId]);
            return;
        }
        
        // Vérifier si déjà payé
        if ($bookingRequest->deposit_paid_at) {
            Log::info('Acompte déjà marqué comme payé', ['booking_request_id' => $bookingRequestId]);
            return;
        }
        
        // Marquer acompte payé
        $bookingRequest->update([
            'status' => BookingRequest::STATUS_DEPOSIT_PAID,
            'deposit_paid_at' => now(),
            'stripe_payment_intent_id' => $session->payment_intent,
        ]);
        
        // Prolonger chat jusqu'au RDV (ou +30 jours si pas encore fixé)
        $chatExpiresAt = $bookingRequest->appointment_datetime 
            ?? now()->addDays(30);
        
        $bookingRequest->update([
            'chat_closes_at' => $chatExpiresAt,
        ]);
        
        // Mettre à jour conversation
        if ($bookingRequest->conversation) {
            $bookingRequest->conversation->update([
                'expiry_type' => 'permanent',
                'expires_at' => $chatExpiresAt,
            ]);
        }
        
        // TODO: Notification tattooer
        // Mail::to($bookingRequest->bookable->user)->send(new DepositPaid($bookingRequest));
        
        Log::info('Acompte payé avec succès', [
            'booking_request_id' => $bookingRequestId,
            'amount' => $session->amount_total / 100,
            'payment_intent' => $session->payment_intent
        ]);
    }
    
    /**
     * Gérer le succès d'un paiement intent
     */
    protected function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Payment intent succeeded', ['id' => $paymentIntent->id]);
    }
    
    /**
     * Gérer l'échec d'un paiement intent
     */
    protected function handlePaymentFailed($paymentIntent)
    {
        Log::warning('Payment intent failed', [
            'id' => $paymentIntent->id,
            'last_payment_error' => $paymentIntent->last_payment_error ?? null
        ]);
    }
}

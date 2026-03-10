<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\AccountingTransaction;
use App\Models\Payment;
use App\Models\Conversation;
use App\Enums\BookingRequestStatus;
use App\Enums\ConversationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    /**
     * Gérer les webhooks Stripe
     *
     * POST /webhooks/stripe
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

        // Logger l'événement reçu
        Log::info('Webhook Stripe reçu', [
            'type' => $event->type,
            'id' => $event->id,
        ]);

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

            case 'charge.refunded':
                $this->handleChargeRefunded($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handleInvoicePaymentSucceeded($event->data->object);
                break;

            case 'customer.subscription.created':
                $this->handleSubscriptionCreated($event->data->object);
                break;

            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handleInvoicePaymentFailed($event->data->object);
                break;

            default:
                Log::info('Webhook Stripe: Événement non géré', ['type' => $event->type]);
                break;
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Gérer la complétion de session checkout
     */
    private function handleCheckoutCompleted($session)
    {
        DB::transaction(function () use ($session) {
            try {
                // Récupérer la booking request depuis la session
                $bookingRequestId = $session->metadata['booking_request_id'] ?? null;
                $paymentType = $session->metadata['payment_type'] ?? 'deposit';

                if (!$bookingRequestId) {
                    Log::warning('Webhook: booking_request_id manquant dans la session', ['session_id' => $session->id]);
                    return;
                }

                $bookingRequest = BookingRequest::find($bookingRequestId);

                if (!$bookingRequest) {
                    Log::warning('Webhook: BookingRequest non trouvée', ['booking_request_id' => $bookingRequestId]);
                    return;
                }

                // Traitement différent selon le type de paiement
                if ($paymentType === 'balance') {
                    $this->handleBalancePayment($session, $bookingRequest);
                } else {
                    $this->handleDepositPayment($session, $bookingRequest);
                }

            } catch (\Exception $e) {
                Log::error('Webhook: Erreur lors du traitement du paiement', [
                    'error' => $e->getMessage(),
                    'session_id' => $session->id,
                    'trace' => $e->getTraceAsString(),
                ]);
                throw $e;
            }
        });
    }

    /**
     * Gérer le paiement du solde
     */
    private function handleBalancePayment($session, BookingRequest $bookingRequest)
    {
        // Vérifier si le paiement est déjà traité (idempotence)
        if ($bookingRequest->balance_paid_at) {
            Log::info('Webhook: Paiement du solde déjà traité (idempotent)', ['booking_request_id' => $bookingRequest->id]);
            return;
        }

        // Mettre à jour la booking request
        $bookingRequest->update([
            'balance_amount' => $session->amount_total / 100,
            'balance_paid_at' => now(),
            'balance_payment_method' => 'stripe',
            'status' => BookingRequestStatus::FULLY_COMPLETED,
        ]);

        // Enregistrer le paiement
        Payment::create([
            'booking_request_id' => $bookingRequest->id,
            'stripe_payment_intent_id' => $session->payment_intent,
            'amount' => $session->amount_total / 100,
            'currency' => 'eur',
            'payment_type' => 'balance',
            'status' => 'succeeded',
            'paid_at' => now(),
        ]);

        // Message système
        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $amount = number_format($session->amount_total / 100, 2, ',', ' ');
            $conversation->messages()->create([
                'sender_id' => null,
                'body' => "💰 Solde de {$amount}€ payé en ligne. Prestation complète !",
                'is_system' => true,
                'metadata' => json_encode(['type' => 'balance_paid_online']),
            ]);
        }

        // Notifier le tattooer
        $bookingRequest->bookable?->user?->notify(
            new \App\Notifications\BalancePaidNotification($bookingRequest)
        );

        Log::info('Webhook: Paiement du solde traité avec succès', [
            'booking_request_id' => $bookingRequest->id,
            'amount' => $session->amount_total / 100,
        ]);
    }

    /**
     * Gérer le paiement de l'acompte
     */
    private function handleDepositPayment($session, BookingRequest $bookingRequest)
    {
        // Vérifier si le paiement est déjà traité (idempotence)
        if ($bookingRequest->deposit_paid_at) {
            Log::info('Webhook: Paiement déjà traité (idempotent)', ['booking_request_id' => $bookingRequest->id]);
            return;
        }

        // Mettre à jour la booking request
        $bookingRequest->update([
            'status' => BookingRequestStatus::DEPOSIT_PAID,
            'deposit_paid_at' => now(),
            'stripe_payment_intent_id' => $session->payment_intent,
        ]);

        // Créer la transaction comptable
        $transaction = AccountingTransaction::createFromStripeSession($session, $bookingRequest, 'deposit');

        // Récupérer l'URL du reçu
        $this->storeReceiptUrl($transaction, $session->payment_intent);

        // Mettre à jour la conversation
        $this->updateConversationAfterPayment($bookingRequest);

        // Créer l'appointment si la date est définie
        if ($bookingRequest->appointment_datetime) {
            $this->createAppointmentFromBookingRequest($bookingRequest);
        }

        // Notifier le tattooer
        $tattooerUser = $bookingRequest->bookable?->user;
        if ($tattooerUser) {
            $tattooerUser->notify(new \App\Notifications\DepositPaidNotification($bookingRequest));
        }

        // Logger le succès
        Log::info('Webhook: Paiement d\'acompte traité avec succès', [
            'booking_request_id' => $bookingRequest->id,
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amount,
        ]);
    }

    /**
     * Gérer le succès du paiement intent
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Webhook: Payment Intent succeeded', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
        ]);

        // Le traitement principal est fait dans handleCheckoutCompleted
        // Mais on peut ajouter de la logique supplémentaire ici si nécessaire
    }

    /**
     * Gérer l'échec du paiement
     */
    private function handlePaymentFailed($paymentIntent)
    {
        Log::warning('Webhook: Payment Intent failed', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
            'last_payment_error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
        ]);

        // Marquer la transaction comme échouée si elle existe
        $transaction = AccountingTransaction::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($transaction) {
            $transaction->markAsFailed();
        }
    }

    /**
     * Gérer le remboursement
     */
    private function handleChargeRefunded($charge)
    {
        Log::info('Webhook: Charge refunded', [
            'charge_id' => $charge->id,
            'amount' => $charge->amount,
            'refunded' => $charge->refunded,
        ]);

        DB::transaction(function () use ($charge) {
            // Récupérer la transaction originale
            $transaction = AccountingTransaction::where('stripe_charge_id', $charge->id)->first();

            if ($transaction) {
                // Créer une transaction de remboursement
                $refundTransaction = AccountingTransaction::createRefund(
                    $transaction->bookingRequest,
                    abs($charge->amount),
                    $charge->metadata['reason'] ?? 'Remboursement client'
                );

                // Mettre à jour la booking request si nécessaire
                $bookingRequest = $transaction->bookingRequest;
                if ($bookingRequest) {
                    $bookingRequest->update([
                        'refund_amount' => abs($charge->amount),
                    ]);
                }

                Log::info('Webhook: Remboursement traité', [
                    'original_transaction_id' => $transaction->id,
                    'refund_transaction_id' => $refundTransaction->id,
                ]);
            }
        });
    }

    /**
     * Gérer le succès de paiement d'invoice
     */
    private function handleInvoicePaymentSucceeded($invoice)
    {
        Log::info('Webhook: Invoice payment succeeded', [
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount_paid,
        ]);
    }

    /**
     * Gérer l'échec de paiement d'invoice
     */
    private function handleInvoicePaymentFailed($invoice)
    {
        Log::warning('Webhook: Invoice payment failed', [
            'invoice_id' => $invoice->id,
            'attempt_count' => $invoice->attempt_count,
        ]);
    }

    /**
     * Stocker l'URL du reçu Stripe
     */
    private function storeReceiptUrl(AccountingTransaction $transaction, string $paymentIntentId): void
    {
        try {
            $stripe = new Stripe(config('services.stripe.secret_key'));
            $paymentIntent = $stripe->paymentIntents->retrieve($paymentIntentId);

            if ($paymentIntent->latest_charge) {
                $charge = $stripe->charges->retrieve($paymentIntent->latest_charge);
                $receiptUrl = $charge->receipt_url;

                if ($receiptUrl) {
                    $transaction->addMetadata(['receipt_url' => $receiptUrl]);
                    $transaction->update(['receipt_url' => $receiptUrl]);

                    Log::info('Webhook: URL du reçu stockée', [
                        'transaction_id' => $transaction->id,
                        'receipt_url' => $receiptUrl,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Webhook: Impossible de récupérer l\'URL du reçu', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $paymentIntentId,
            ]);
        }
    }

    /**
     * Mettre à jour la conversation après paiement
     */
    private function updateConversationAfterPayment(BookingRequest $bookingRequest): void
    {
        $conversation = $bookingRequest->conversation;

        if (!$conversation) {
            return;
        }

        // Passer en FULL_ACCESS après paiement de l'acompte
        $conversation->update([
            'status' => ConversationStatus::FULL_ACCESS,
            'deposit_deadline_at' => null, // Plus besoin de deadline
            'expires_at' => $bookingRequest->appointment_datetime
                ? $bookingRequest->appointment_datetime->addDays(30) // J+30 post-RDV
                : now()->addMonths(6), // 6 mois si pas de RDV
        ]);

        // Envoyer un message système
        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => "✅ Acompte reçu !\n\nMerci pour votre paiement de {$bookingRequest->total_deposit_amount}€.\n\nVous pouvez maintenant échanger des images avec le tatoueur et discuter des détails de votre tatouage.",
        ]);
    }

    /**
     * Créer un appointment depuis la booking request
     */
    private function createAppointmentFromBookingRequest(BookingRequest $bookingRequest): void
    {
        // Vérifier si un appointment existe déjà
        if ($bookingRequest->appointment) {
            return;
        }

        // Créer l'appointment
        $appointment = $bookingRequest->appointment()->create([
            'client_id' => $bookingRequest->client_id,
            'bookable_type' => $bookingRequest->bookable_type,
            'bookable_id' => $bookingRequest->bookable_id,
            'start_datetime' => $bookingRequest->appointment_datetime,
            'end_datetime' => $bookingRequest->appointment_datetime->addMinutes($bookingRequest->appointment_duration_minutes ?? 120),
            'duration_minutes' => $bookingRequest->appointment_duration_minutes ?? 120,
            'total_price' => $bookingRequest->estimated_total_price,
            'deposit_amount' => $bookingRequest->total_deposit_amount,
            'status' => 'confirmed',
        ]);

        // Mettre à jour la booking request
        $bookingRequest->update([
            'appointment_id' => $appointment->id,
        ]);

        Log::info('Webhook: Appointment créé automatiquement', [
            'booking_request_id' => $bookingRequest->id,
            'appointment_id' => $appointment->id,
            'appointment_datetime' => $appointment->start_datetime,
        ]);
    }

    // ═══ GESTION DES ABONNEMENTS ═══

    /**
     * Gérer la création d'un abonnement
     */
    private function handleSubscriptionCreated($subscription)
    {
        Log::info('Webhook: Abonnement créé', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer,
        ]);

        // Laravel Cashier gère automatiquement la synchronisation
        // Les abonnements sont créés via la méthode checkout() du modèle User
        // et synchronisés par Cashier
    }

    /**
     * Gérer la mise à jour d'un abonnement
     */
    private function handleSubscriptionUpdated($subscription)
    {
        Log::info('Webhook: Abonnement mis à jour', [
            'subscription_id' => $subscription->id,
            'status' => $subscription->status,
        ]);

        // Laravel Cashier gère automatiquement la synchronisation
    }

    /**
     * Gérer la suppression d'un abonnement
     */
    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Webhook: Abonnement supprimé', [
            'subscription_id' => $subscription->id,
            'customer_id' => $subscription->customer,
        ]);

        // Laravel Cashier gère automatiquement la synchronisation
        // L'abonnement est marqué comme annulé mais conservé en BDD
    }
}

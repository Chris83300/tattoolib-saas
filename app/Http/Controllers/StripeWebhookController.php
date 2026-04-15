<?php

namespace App\Http\Controllers;

use App\Models\BookingRequest;
use App\Models\AccountingTransaction;
use App\Models\Payment;
use App\Models\Conversation;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Studio;
use App\Models\User;
use App\Enums\BookingRequestStatus;
use App\Enums\ConversationStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;

class StripeWebhookController extends Controller
{
    protected \Stripe\StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient(config('cashier.secret'));
    }

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

            case 'account.updated':
                $this->handleAccountUpdated($event->data->object);
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
                'sender_type' => 'system',
                'booking_request_id' => $bookingRequest->id,
                'content' => "Solde de {$amount} € payé en ligne via Stripe. Prestation complète !",
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
        Log::info('Webhook: handleDepositPayment START', ['booking_request_id' => $bookingRequest->id]);

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
     * Mettre à jour la conversation après paiement d'acompte
     */
    private function updateConversationAfterPayment(BookingRequest $bookingRequest): void
    {
        Log::info('Webhook: updateConversationAfterPayment START', ['booking_request_id' => $bookingRequest->id]);

        $conversation = $bookingRequest->conversation;
        if (!$conversation) {
            Log::warning('Webhook: No conversation found for booking request', ['booking_request_id' => $bookingRequest->id]);
            return;
        }

        $amount = number_format($bookingRequest->deposit_amount, 2, ',', ' ');

        Log::info('Webhook: Creating consent message', ['booking_request_id' => $bookingRequest->id, 'amount' => $amount]);

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'booking_request_id' => $bookingRequest->id,
            'content' => "Acompte de {$amount} reçu. Merci de signer votre fiche de consentement avant le rendez-vous. [CONSENT_FORM:{$bookingRequest->id}]",
        ]);

        Log::info('Webhook: updateConversationAfterPayment DONE', ['booking_request_id' => $bookingRequest->id]);
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

        // Invalider les cache widgets financiers
        Cache::forget('admin.platform.revenue');
        Cache::forget('admin.platform.revenue.detail');

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

        // Invalider les cache widgets financiers
        Cache::forget('admin.platform.revenue');
        Cache::forget('admin.platform.revenue.detail');

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
     * Gérer le succès de paiement d'invoice (renouvellement abonnement)
     */
    private function handleInvoicePaymentSucceeded($invoice): void
    {
        Log::info('Webhook: Invoice payment succeeded', [
            'invoice_id' => $invoice->id,
            'amount' => $invoice->amount_paid,
        ]);

        // Invalider les cache widgets financiers
        Cache::forget('admin.platform.revenue');
        Cache::forget('admin.platform.revenue.detail');

        // Si c'est un renouvellement d'abonnement, synchroniser les modèles métier
        $subscriptionId = $invoice->subscription ?? null;
        if ($subscriptionId) {
            try {
                $stripe = $this->stripe;
                $subscription = $stripe->subscriptions->retrieve($subscriptionId);
                $this->syncArtistSubscription($subscription);
            } catch (\Exception $e) {
                Log::error('Webhook: Impossible de récupérer l\'abonnement pour sync', [
                    'subscription_id' => $subscriptionId,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Gérer l'échec de paiement d'invoice
     */
    private function handleInvoicePaymentFailed($invoice): void
    {
        Log::warning('Webhook: Invoice payment failed', [
            'invoice_id' => $invoice->id,
            'attempt_count' => $invoice->attempt_count,
        ]);

        // Notifier l'artiste : son paiement a échoué, is_subscribed reste inchangé
        // (Cashier gère la mise en grace_period automatiquement via past_due)
        $stripeCustomerId = $invoice->customer ?? null;
        if ($stripeCustomerId) {
            $user = User::where('stripe_id', $stripeCustomerId)->first();
            if ($user) {
                Log::warning('Webhook: Paiement facture échoué', [
                    'user_id' => $user->id,
                    'invoice_id' => $invoice->id,
                ]);
                // TODO post-lancement : envoyer notification email à l'artiste
            }
        }
    }

    /**
     * Gérer la création d'un abonnement
     */
    private function handleSubscriptionCreated($subscription)
    {
        Log::info('Webhook: Abonnement créé', [
            'subscription_id' => $subscription->id,
            'customer' => $subscription->customer,
            'status' => $subscription->status,
        ]);

        // Invalider les cache widgets financiers
        Cache::forget('admin.platform.revenue');
        Cache::forget('admin.platform.revenue.detail');

        $this->syncArtistSubscription($subscription);
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

        // Invalider les cache widgets financiers
        Cache::forget('admin.platform.revenue');
        Cache::forget('admin.platform.revenue.detail');

        $this->syncArtistSubscription($subscription);
    }

    /**
     * Gérer la suppression d'un abonnement
     */
    private function handleSubscriptionDeleted($subscription)
    {
        Log::info('Webhook: Abonnement supprimé', [
            'subscription_id' => $subscription->id,
            'customer' => $subscription->customer,
        ]);

        // Invalider les cache widgets financiers
        Cache::forget('admin.platform.revenue');
        Cache::forget('admin.platform.revenue.detail');

        $stripeCustomerId = $subscription->customer ?? $subscription['customer'] ?? null;
        if (!$stripeCustomerId) return;

        $user = User::where('stripe_id', $stripeCustomerId)->first();
        if (!$user) {
            Log::warning('Webhook: user non trouvé pour suppression abonnement', [
                'customer_id' => $stripeCustomerId,
            ]);
            return;
        }

        if ($user->tattooer) {
            $user->tattooer->update(['is_subscribed' => false]);
        }
        if ($user->piercer) {
            $user->piercer->update(['is_subscribed' => false]);
        }
        if ($user->studio) {
            $user->studio->update(['is_subscribed' => false]);
        }

        Log::info('Webhook: Abonnement supprimé — modèles métier mis à jour', [
            'user_id' => $user->id,
        ]);
    }

    // ═══ STRIPE CONNECT — MISES À JOUR COMPTE ═══

    /**
     * Gérer les changements d'état d'un compte Connect (account.updated)
     * Synchronise stripe_connect_status sur Tattooer, Piercer, ou Studio.
     */
    private function handleAccountUpdated($account): void
    {
        $accountId = $account->id;
        $chargesEnabled = $account->charges_enabled ?? false;
        $payoutsEnabled = $account->payouts_enabled ?? false;
        $detailsSubmitted = $account->details_submitted ?? false;
        $requirements = $account->requirements?->currently_due ?? [];

        // Déterminer le statut local
        $newStatus = match(true) {
            $chargesEnabled && $payoutsEnabled           => 'active',
            $detailsSubmitted && count($requirements) > 0 => 'restricted',
            $detailsSubmitted                            => 'onboarding',
            default                                      => 'not_connected',
        };

        Log::info('Webhook: account.updated reçu', [
            'account_id'       => $accountId,
            'charges_enabled'  => $chargesEnabled,
            'payouts_enabled'  => $payoutsEnabled,
            'resolved_status'  => $newStatus,
            'requirements_due' => $requirements,
        ]);

        // Chercher parmi Tattooer
        $tattooer = Tattooer::where('stripe_connect_account_id', $accountId)->first();
        if ($tattooer) {
            $this->syncArtistConnectStatus($tattooer, $newStatus, $chargesEnabled);
            return;
        }

        // Chercher parmi Piercer
        $piercer = Piercer::where('stripe_connect_account_id', $accountId)->first();
        if ($piercer) {
            $this->syncArtistConnectStatus($piercer, $newStatus, $chargesEnabled);
            return;
        }

        // Chercher parmi Studio (mode studio_managed)
        $studio = Studio::where('stripe_account_id', $accountId)->first();
        if ($studio) {
            $studio->update([
                'stripe_onboarding_complete' => $chargesEnabled,
            ]);

            Log::info('Webhook: Studio Connect statut mis à jour', [
                'studio_id'  => $studio->id,
                'charges_enabled' => $chargesEnabled,
            ]);
            return;
        }

        Log::warning('Webhook: account.updated — aucun artiste/studio trouvé', [
            'account_id' => $accountId,
        ]);
    }

    /**
     * Synchronise le statut Stripe Connect d'un artiste (Tattooer ou Piercer).
     */
    private function syncArtistConnectStatus($artist, string $newStatus, bool $chargesEnabled): void
    {
        $oldStatus = $artist->stripe_connect_status;

        $artist->update([
            'stripe_connect_status'    => $newStatus,
            'stripe_onboarding_complete' => $chargesEnabled,
        ]);

        // Activer si nouveaux droits
        if ($newStatus === 'active' && $oldStatus !== 'active') {
            $artist->activateStripeConnect();
        }

        // Désactiver si charges désactivées et était actif
        if (!$chargesEnabled && $oldStatus === 'active') {
            $artist->deactivateStripeConnect('stripe_account_restricted');
        }

        Log::info('Webhook: Artiste Connect statut synchronisé', [
            'artist_id'   => $artist->id,
            'artist_type' => get_class($artist),
            'old_status'  => $oldStatus,
            'new_status'  => $newStatus,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS ABONNEMENT
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Synchronise is_subscribed + current_plan sur Tattooer/Piercer/Studio
     * à partir d'un objet Stripe Subscription.
     */
    private function syncArtistSubscription($subscription): void
    {
        $stripeCustomerId = $subscription->customer ?? $subscription['customer'] ?? null;
        if (!$stripeCustomerId) return;

        $user = User::where('stripe_id', $stripeCustomerId)->first();
        if (!$user) {
            Log::warning('Webhook: syncArtistSubscription — user non trouvé', [
                'customer_id' => $stripeCustomerId,
            ]);
            return;
        }

        $stripeStatus = $subscription->status ?? $subscription['status'] ?? 'canceled';
        $items        = $subscription->items ?? $subscription['items'] ?? null;
        $priceId      = $items->data[0]->price->id
                        ?? $items['data'][0]['price']['id']
                        ?? null;

        $plan     = $this->resolvePlanFromPriceId($priceId);
        $isActive = in_array($stripeStatus, ['active', 'trialing']);

        if ($user->tattooer) {
            $user->tattooer->update([
                'is_subscribed' => $isActive,
                'current_plan'  => $isActive ? $plan : $user->tattooer->current_plan,
            ]);
            Log::info('Webhook: Tattooer sync abonnement', [
                'tattooer_id'   => $user->tattooer->id,
                'plan'          => $plan,
                'is_subscribed' => $isActive,
            ]);
        }

        if ($user->piercer) {
            $user->piercer->update([
                'is_subscribed' => $isActive,
                'current_plan'  => $isActive ? $plan : $user->piercer->current_plan,
            ]);
            Log::info('Webhook: Piercer sync abonnement', [
                'piercer_id'    => $user->piercer->id,
                'plan'          => $plan,
                'is_subscribed' => $isActive,
            ]);
        }

        if ($user->studio) {
            $user->studio->update(['is_subscribed' => $isActive]);
            Log::info('Webhook: Studio sync abonnement', [
                'studio_id'     => $user->studio->id,
                'is_subscribed' => $isActive,
            ]);
        }
    }

    /**
     * Résout le plan interne depuis le price ID Stripe.
     * Utilise config('services.stripe.prices.*') — renseigné via .env.
     */
    private function resolvePlanFromPriceId(?string $priceId): string
    {
        if (!$priceId) return 'starter';

        $map = array_filter([
            config('services.stripe.prices.starter')      => 'starter',
            config('services.stripe.prices.pro')          => 'pro',
            config('services.stripe.prices.studio')       => 'studio',
            config('services.stripe.prices.studio_extra') => 'studio',
        ]);

        return $map[$priceId] ?? 'starter';
    }
}

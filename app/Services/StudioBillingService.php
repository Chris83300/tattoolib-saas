<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\User;
use App\Enums\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class StudioBillingService
{
    /**
     * Le studio est-il abonné ? (via Cashier sur le User propriétaire)
     */
    public function isSubscribed(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user) return false;

            if ($user->subscribed('default')) return true;

            // Fallback : trial local encore actif
            if ($studio->trial_ends_at && $studio->trial_ends_at->isFuture()) return true;

            return false;
        } catch (\Exception $e) {
            Log::warning('StudioBillingService::isSubscribed error', [
                'studio_id' => $studio->id,
                'error' => $e->getMessage(),
            ]);
            return $studio->trial_ends_at && $studio->trial_ends_at->isFuture();
        }
    }

    /**
     * Créer une session Stripe Checkout pour l'abonnement studio.
     * Utilise l'API Stripe directe pour éviter les doublons créés par Cashier ->newSubscription().
     */
    public function createCheckoutSession(Studio $studio): string
    {
        $user = $studio->user;
        if (!$user) throw new \Exception('Studio sans utilisateur propriétaire.');

        $priceId = config('inkpik.pricing.studio.stripe_price_id');
        if (!$priceId || str_starts_with($priceId, 'price_xxx')) {
            throw new \Exception('Stripe Price ID Studio non configuré. Vérifiez STRIPE_PRICE_ID_STUDIO dans .env');
        }

        $stripe = new \Stripe\StripeClient(config('cashier.secret'));

        // Créer le customer Stripe si nécessaire (via Cashier pour garder stripe_id sur User)
        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer([
                'name'  => $user->name,
                'email' => $user->email,
                'metadata' => [
                    'studio_id'   => $studio->id,
                    'studio_name' => $studio->name,
                    'type'        => 'studio_owner',
                ],
            ]);
        }

        $subscriptionData = [
            'metadata' => [
                'studio_id' => $studio->id,
                'plan'      => 'studio',
            ],
        ];

        // Trial 14j si jamais eu d'abonnement actif/trialing
        $hasActiveSub = $user->subscriptions()
            ->whereIn('stripe_status', ['active', 'trialing'])
            ->exists();
        if (!$hasActiveSub) {
            $subscriptionData['trial_period_days'] = SubscriptionPlan::STUDIO->trialDays();
        }

        $params = [
            'customer'             => $user->stripe_id,
            'payment_method_types' => ['card'],
            'line_items'           => [['price' => $priceId, 'quantity' => 1]],
            'mode'                 => 'subscription',
            'success_url'          => route('studio.billing') . '?checkout=success&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('studio.billing') . '?checkout=cancel',
            'subscription_data'    => $subscriptionData,
            'metadata'             => ['studio_id' => $studio->id],
            'allow_promotion_codes' => true,
        ];

        // Coupon bêta si applicable (ne pas combiner avec allow_promotion_codes)
        $betaService = app(\App\Services\BetaService::class);
        if ($betaService->isActiveBetaTester($user)) {
            unset($params['allow_promotion_codes']);
            $params['discounts'] = [['coupon' => $betaService->getStripeCouponId()]];
            $params['subscription_data']['trial_period_days'] = 44; // 14j trial + 30j beta
        }

        $session = $stripe->checkout->sessions->create($params);

        Log::info('Stripe Checkout Session created', [
            'session_id' => $session->id,
            'studio_id'  => $studio->id,
            'user_id'    => $user->id,
            'price_id'   => $priceId,
        ]);

        return $session->url;
    }

    /**
     * Synchroniser depuis une Checkout Session spécifique.
     * Fallback quand syncFromStripe ne trouve rien (délai Stripe).
     */
    public function syncFromCheckoutSession(Studio $studio, string $sessionId): bool
    {
        try {
            $user = $studio->user;
            if (!$user) return false;

            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $session = $stripe->checkout->sessions->retrieve($sessionId, [
                'expand' => ['subscription'],
            ]);

            if (!$session->subscription) {
                Log::warning('syncFromCheckoutSession: pas de subscription', ['session_id' => $sessionId]);
                return false;
            }

            $stripeSub = is_string($session->subscription)
                ? $stripe->subscriptions->retrieve($session->subscription)
                : $session->subscription;

            if (!in_array($stripeSub->status, ['active', 'trialing'])) {
                Log::warning('syncFromCheckoutSession: status=' . $stripeSub->status, ['session_id' => $sessionId]);
                return false;
            }

            // Mettre à jour stripe_id du User si pas encore fait
            if (!$user->stripe_id && $session->customer) {
                $user->update(['stripe_id' => $session->customer]);
            }

            $user->subscriptions()->updateOrCreate(
                ['type' => 'default'],
                [
                    'stripe_id'     => $stripeSub->id,
                    'stripe_status' => $stripeSub->status,
                    'stripe_price'  => $stripeSub->items->data[0]->price->id ?? null,
                    'quantity'      => $stripeSub->items->data[0]->quantity ?? 1,
                    'trial_ends_at' => $stripeSub->trial_end
                        ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end)
                        : null,
                    'ends_at' => null,
                ]
            );

            $studio->update(['is_subscribed' => true]);

            Log::info('syncFromCheckoutSession: OK', [
                'studio_id'    => $studio->id,
                'stripe_sub_id' => $stripeSub->id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('syncFromCheckoutSession error', [
                'session_id' => $sessionId,
                'error'      => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Annuler l'abonnement (fin de période).
     */
    public function cancel(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->subscribed('default')) return false;

            $user->subscription('default')->cancel();
            Log::info('Studio subscription cancelled', ['studio_id' => $studio->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Studio cancel error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Annuler immédiatement.
     */
    public function cancelNow(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->subscribed('default')) return false;

            $user->subscription('default')->cancelNow();
            Log::info('Studio subscription cancelled immediately', ['studio_id' => $studio->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Studio cancelNow error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Reprendre un abonnement annulé (avant fin de période).
     */
    public function resume(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user) return false;

            $sub = $user->subscription('default');
            if ($sub && $sub->onGracePeriod()) {
                $sub->resume();
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('Studio resume error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * URL du portail de facturation Stripe.
     */
    public function billingPortalUrl(Studio $studio): ?string
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->hasStripeId()) return null;

            return $user->billingPortalUrl(route('studio.billing'));
        } catch (\Exception $e) {
            Log::error('Billing portal URL error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Informations de l'abonnement actuel.
     */
    public function getSubscriptionInfo(Studio $studio): ?array
    {
        try {
            $user = $studio->user;
            if (!$user) return null;

            $sub = $user->subscription('default');
            if (!$sub) return null;

            return [
                'active'          => !$sub->canceled(),
                'on_trial'        => $sub->onTrial(),
                'on_grace_period' => $sub->onGracePeriod(),
                'canceled'        => $sub->canceled(),
                'ends_at'         => $sub->ends_at,
                'trial_ends_at'   => $sub->trial_ends_at,
                'stripe_status'   => $sub->stripe_status,
                'stripe_price'    => $sub->stripe_price,
                'created_at'      => $sub->created_at,
            ];
        } catch (\Exception $e) {
            Log::warning('getSubscriptionInfo error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Synchroniser le statut d'abonnement depuis Stripe.
     * Indispensable en local sans webhook.
     */
    public function syncFromStripe(Studio $studio): bool
    {
        try {
            $user = $studio->user;
            if (!$user || !$user->hasStripeId()) {
                Log::warning('syncFromStripe: user sans stripe_id', ['studio_id' => $studio->id]);
                return false;
            }

            $stripe = new \Stripe\StripeClient(config('cashier.secret'));
            $stripeSubscriptions = $stripe->subscriptions->all([
                'customer' => $user->stripe_id,
                'limit'    => 10,
            ]);

            if (empty($stripeSubscriptions->data)) {
                Log::info('syncFromStripe: aucun abonnement trouvé', [
                    'studio_id'       => $studio->id,
                    'stripe_customer' => $user->stripe_id,
                ]);
                $studio->update(['is_subscribed' => false]);
                return false;
            }

            foreach ($stripeSubscriptions->data as $stripeSub) {
                if (!in_array($stripeSub->status, ['active', 'trialing'])) {
                    continue;
                }

                $priceId = $stripeSub->items->data[0]->price->id ?? null;

                $user->subscriptions()->updateOrCreate(
                    ['type' => 'default'],
                    [
                        'stripe_id'     => $stripeSub->id,
                        'stripe_status' => $stripeSub->status,
                        'stripe_price'  => $priceId,
                        'quantity'      => $stripeSub->items->data[0]->quantity ?? 1,
                        'trial_ends_at' => $stripeSub->trial_end
                            ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end)
                            : null,
                        'ends_at' => $stripeSub->cancel_at
                            ? \Carbon\Carbon::createFromTimestamp($stripeSub->cancel_at)
                            : null,
                    ]
                );

                $studio->update(['is_subscribed' => true]);

                Log::info('syncFromStripe: OK', [
                    'studio_id'   => $studio->id,
                    'stripe_sub'  => $stripeSub->id,
                    'status'      => $stripeSub->status,
                    'price_id'    => $priceId,
                ]);
                return true;
            }

            $studio->update(['is_subscribed' => false]);
            return false;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('syncFromStripe Stripe API error', [
                'studio_id' => $studio->id,
                'error'     => $e->getMessage(),
            ]);
            return false;
        } catch (\Exception $e) {
            Log::error('syncFromStripe error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return false;
        }
    }
}

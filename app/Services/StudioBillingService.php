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
     */
    public function createCheckoutSession(Studio $studio): string
    {
        $user = $studio->user;
        if (!$user) throw new \Exception('Studio sans utilisateur propriétaire.');

        if (!$user->hasStripeId()) {
            $user->createAsStripeCustomer([
                'name'  => $user->name,
                'email' => $user->email,
                'metadata' => [
                    'studio_id'   => $studio->id,
                    'studio_name' => $studio->name,
                ],
            ]);
        }

        $priceId = config('inkpik.pricing.studio.stripe_price_id');
        if (!$priceId) throw new \Exception('Stripe Price ID manquant pour le plan studio.');

        $checkoutParams = [
            'success_url' => route('studio.billing') . '?checkout=success',
            'cancel_url'  => route('studio.billing') . '?checkout=cancel',
            'metadata'    => [
                'studio_id' => $studio->id,
                'plan'      => 'studio',
            ],
        ];

        $betaService = app(\App\Services\BetaService::class);
        if ($betaService->isActiveBetaTester($user)) {
            $checkoutParams['discounts'] = [
                ['coupon' => $betaService->getStripeCouponId()],
            ];
        }

        // Trial 14 jours si jamais abonné
        $hasHadSubscription = $user->subscriptions()->exists();
        if (!$hasHadSubscription) {
            $checkoutParams['subscription_data'] = [
                'trial_period_days' => SubscriptionPlan::STUDIO->trialDays(),
            ];
        }

        $checkout = $user->newSubscription('default', $priceId)
            ->allowPromotionCodes()
            ->checkout($checkoutParams);

        return $checkout->url;
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
            if (!$user || !$user->hasStripeId()) return false;

            \Stripe\Stripe::setApiKey(config('cashier.secret'));

            $stripeSubscriptions = \Stripe\Subscription::all([
                'customer' => $user->stripe_id,
                'status'   => 'all',
                'limit'    => 5,
            ]);

            foreach ($stripeSubscriptions->data as $stripeSub) {
                if (in_array($stripeSub->status, ['active', 'trialing'])) {
                    $user->subscriptions()->updateOrCreate(
                        ['stripe_id' => $stripeSub->id],
                        [
                            'type'          => 'default',
                            'stripe_status' => $stripeSub->status,
                            'stripe_price'  => $stripeSub->items->data[0]->price->id ?? null,
                            'quantity'      => $stripeSub->items->data[0]->quantity ?? 1,
                            'trial_ends_at' => $stripeSub->trial_end
                                ? \Carbon\Carbon::createFromTimestamp($stripeSub->trial_end)
                                : null,
                            'ends_at' => $stripeSub->cancel_at
                                ? \Carbon\Carbon::createFromTimestamp($stripeSub->cancel_at)
                                : null,
                        ]
                    );

                    Log::info('Studio subscription synced from Stripe', [
                        'studio_id'  => $studio->id,
                        'stripe_sub' => $stripeSub->id,
                        'status'     => $stripeSub->status,
                    ]);
                    return true;
                }
            }

            return false;
        } catch (\Exception $e) {
            Log::error('syncFromStripe error', ['studio_id' => $studio->id, 'error' => $e->getMessage()]);
            return false;
        }
    }
}

<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\StudioSubscription;

class StudioBillingService
{
    /**
     * Crée ou met à jour l'abonnement studio via Laravel Cashier.
     * Studio base = 79.99€/mois
     * Artistes supplémentaires = 39.99€ × quantity
     */
    public function subscribe(Studio $studio, string $paymentMethodId): void
    {
        $studioPriceId = config('services.stripe.studio_price_id');
        $artistPriceId = config('services.stripe.studio_artist_price_id');

        if (!$studio->hasStripeId()) {
            $studio->createAsStripeCustomer([
                'name'     => $studio->name,
                'email'    => $studio->stripeEmail(),
                'metadata' => [
                    'studio_id' => $studio->id,
                    'type'      => 'studio',
                ],
            ]);
        }

        $studio->updateDefaultPaymentMethod($paymentMethodId);

        $paidArtists = $studio->paidArtistCount();

        // Create StudioSubscription record instead of using Cashier
        $studioSubscription = StudioSubscription::create([
            'studio_id' => $studio->id,
            'stripe_subscription_id' => 'temp_' . uniqid(), // Will be updated by Stripe webhook
            'stripe_customer_id' => $studio->stripe_id,
            'stripe_price_id' => $studioPriceId,
            'status' => 'active',
            'base_price' => 79.99,
            'price_per_artist' => 39.99,
            'included_artists' => 1,
            'current_artists' => $paidArtists + 1,
            'currency' => 'EUR',
            'billing_interval' => 'month',
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);

        $studioSubscription->updatePricing();
    }

    /**
     * Met à jour la quantity d'artistes dans l'abonnement Cashier.
     * Appelé quand un artiste est ajouté ou retiré du studio.
     */
    public function updateArtistQuantity(Studio $studio): void
    {
        // Use studio_subscriptions table instead of Cashier's subscriptions
        $subscription = $studio->studioSubscriptions()
            ->where('status', 'active')
            ->first();

        if (!$subscription) {
            return;
        }

        $paidArtists = $studio->paidArtistCount();

        // Update the subscription with new artist count
        $subscription->current_artists = $paidArtists + $subscription->included_artists;
        $subscription->updatePricing();
    }

    /**
     * Retourne l'URL du Stripe Customer Portal.
     */
    public function billingPortalUrl(Studio $studio): string
    {
        // For now, return a placeholder since we're not using Cashier's billing portal
        return route('studio.billing');
    }

    /**
     * Le studio a-t-il un abonnement actif ?
     */
    public function isSubscribed(Studio $studio): bool
    {
        return $studio->studioSubscriptions()
            ->where('status', 'active')
            ->exists();
    }
}

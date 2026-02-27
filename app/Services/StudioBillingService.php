<?php

namespace App\Services;

use App\Models\Studio;

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

        $subscriptionItems = [
            ['price' => $studioPriceId, 'quantity' => 1],
        ];

        if ($paidArtists > 0 && $artistPriceId) {
            $subscriptionItems[] = ['price' => $artistPriceId, 'quantity' => $paidArtists];
        }

        $studio->newSubscription('studio', $subscriptionItems)->create($paymentMethodId);
    }

    /**
     * Met à jour la quantity d'artistes dans l'abonnement Cashier.
     * Appelé quand un artiste est ajouté ou retiré du studio.
     */
    public function updateArtistQuantity(Studio $studio): void
    {
        $subscription = $studio->subscription('studio');
        if (!$subscription || !$subscription->active()) {
            return;
        }

        $artistPriceId = config('services.stripe.studio_artist_price_id');
        if (!$artistPriceId) {
            return;
        }

        $paidArtists = $studio->paidArtistCount();

        $artistItem = $subscription->items->first(
            fn ($item) => $item->stripe_price === $artistPriceId
        );

        if ($paidArtists > 0) {
            if ($artistItem) {
                $subscription->updateQuantity($paidArtists, $artistItem->stripe_price);
            } else {
                $subscription->addPrice($artistPriceId, $paidArtists);
            }
        } elseif ($artistItem) {
            $subscription->updateQuantity(0, $artistItem->stripe_price);
        }
    }

    /**
     * Retourne l'URL du Stripe Customer Portal.
     */
    public function billingPortalUrl(Studio $studio): string
    {
        return $studio->billingPortalUrl(route('studio.billing'));
    }

    /**
     * Le studio a-t-il un abonnement Cashier actif ?
     */
    public function isSubscribed(Studio $studio): bool
    {
        return $studio->subscribed('studio');
    }
}

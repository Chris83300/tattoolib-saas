<?php

namespace App\Services;

use App\Models\Studio;
use App\Models\StudioSubscription;
use Illuminate\Support\Facades\Log;

/**
 * @deprecated Utiliser StudioBillingService à la place.
 * Ce service n'est plus utilisé depuis le fix F2 (Cashier via User).
 */
class StripeStudioSubscriptionService
{
    public function __construct(
        private \Stripe\StripeClient $stripe
    ) {}

    /**
     * Créer un abonnement pour un studio
     */
    public function createSubscription(Studio $studio, array $paymentMethod): StudioSubscription
    {
        try {
            // 1. Créer ou récupérer le customer Stripe
            $customer = $this->createOrUpdateCustomer($studio, $paymentMethod);

            // 2. Calculer le prix initial
            $activeArtists = $studio->activeArtists()->count();
            $additionalArtists = max(0, $activeArtists - 1);
            $totalPrice = 79.99 + ($additionalArtists * 39.99);

            // 3. Créer l'abonnement Stripe
            $stripeSubscription = $this->stripe->subscriptions->create([
                'customer' => $customer->id,
                'items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Studio TattooLib - Abonnement Base',
                                'description' => 'Abonnement mensuel pour studio de tatouage',
                            ],
                            'unit_amount' => 7999, // 79.99€ en cents
                            'recurring' => ['interval' => 'month'],
                        ],
                        'quantity' => 1,
                    ],
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => 'Artiste Supplémentaire',
                                'description' => 'Coût par artiste supplémentaire',
                            ],
                            'unit_amount' => 3999, // 39.99€ en cents
                            'recurring' => ['interval' => 'month'],
                        ],
                        'quantity' => $additionalArtists,
                    ],
                ],
                'payment_behavior' => 'default_incomplete',
                'payment_settings' => ['save_default_payment_method' => 'on_subscription'],
                'expand' => ['latest_invoice.payment_intent'],
            ]);

            // 4. Créer l'abonnement en base
            $subscription = StudioSubscription::create([
                'studio_id' => $studio->id,
                'stripe_subscription_id' => $stripeSubscription->id,
                'stripe_customer_id' => $customer->id,
                'status' => $stripeSubscription->status,
                'base_price' => 79.99,
                'price_per_artist' => 39.99,
                'total_price' => $totalPrice,
                'currency' => 'EUR',
                'billing_interval' => 'month',
                'included_artists' => 1,
                'current_artists' => $activeArtists,
                'additional_artists' => $additionalArtists,
                'current_period_start' => now(),
                'current_period_end' => now()->addMonth(),
            ]);

            // 5. Mettre à jour le studio
            $studio->update([
                'stripe_customer_id' => $customer->id,
            ]);

            return $subscription;

        } catch (\Exception $e) {
            Log::error('Erreur création abonnement studio', [
                'studio_id' => $studio->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Mettre à jour un abonnement (changement nombre d'artistes)
     */
    public function updateSubscription(StudioSubscription $subscription): void
    {
        try {
            $studio = $subscription->studio;
            $activeArtists = $studio->activeArtists()->count();
            $additionalArtists = max(0, $activeArtists - 1);

            // Mettre à jour l'abonnement Stripe
            $stripeSubscription = $this->stripe->subscriptions->retrieve($subscription->stripe_subscription_id);

            // Mettre à jour la quantité d'artistes supplémentaires
            if (isset($stripeSubscription->items->data[1])) {
                $this->stripe->subscriptionItems->update(
                    $stripeSubscription->items->data[1]->id,
                    ['quantity' => $additionalArtists]
                );
            }

            // Mettre à jour la base de données
            $subscription->update([
                'current_artists' => $activeArtists,
                'additional_artists' => $additionalArtists,
            ]);
            $subscription->updatePricing();

        } catch (\Exception $e) {
            Log::error('Erreur mise à jour abonnement studio', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Annuler un abonnement
     */
    public function cancelSubscription(StudioSubscription $subscription, bool $immediately = false): void
    {
        try {
            if ($immediately) {
                $this->stripe->subscriptions->cancel($subscription->stripe_subscription_id);
                $subscription->update([
                    'status' => 'canceled',
                    'ends_at' => now(),
                ]);
            } else {
                $this->stripe->subscriptions->update($subscription->stripe_subscription_id, [
                    'cancel_at_period_end' => true,
                ]);
                $subscription->update([
                    'canceled_at' => now(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Erreur annulation abonnement studio', [
                'subscription_id' => $subscription->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Créer ou mettre à jour un customer Stripe
     */
    private function createOrUpdateCustomer(Studio $studio, array $paymentMethod): \Stripe\Customer
    {
        if ($studio->stripe_customer_id) {
            $customer = $this->stripe->customers->retrieve($studio->stripe_customer_id);

            // Ajouter la méthode de paiement
            $this->stripe->paymentMethods->attach(
                $paymentMethod['id'],
                ['customer' => $customer->id]
            );

            return $customer;
        }

        // Créer un nouveau customer
        return $this->stripe->customers->create([
            'name' => $studio->name,
            'email' => $studio->email,
            'phone' => $studio->phone,
            'address' => [
                'line1' => $studio->address,
                'city' => $studio->city,
                'postal_code' => $studio->postal_code,
                'country' => $studio->country,
            ],
            'metadata' => [
                'studio_id' => $studio->id,
                'type' => 'studio',
            ],
            'payment_method' => $paymentMethod['id'],
            'invoice_settings' => [
                'default_payment_method' => $paymentMethod['id'],
            ],
        ]);
    }
}

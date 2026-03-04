<?php

namespace App\Services;

use App\Models\User;

class BetaService
{
    public const DISCOUNT_PERCENT = 30;
    public const FREE_MONTHS      = 1;
    public const COUPON_ID        = 'BETA-LAUNCH-30'; // À créer dans Stripe Dashboard

    /**
     * Marquer un utilisateur comme bêta-testeur.
     */
    public function registerBetaTester(User $user): void
    {
        $user->update([
            'is_beta_tester'     => true,
            'beta_registered_at' => now(),
        ]);
    }

    /**
     * L'utilisateur est-il un bêta-testeur actif ?
     */
    public function isActiveBetaTester(User $user): bool
    {
        return (bool) $user->is_beta_tester && $user->beta_registered_at !== null;
    }

    /**
     * Coupon Stripe pour les bêta-testeurs (-30% à vie).
     */
    public function getStripeCouponId(): string
    {
        return config('inkpik.pricing.beta_coupon_id', self::COUPON_ID);
    }

    /**
     * Durée totale du trial pour un bêta-testeur : 14j + 1 mois offert = 44 jours.
     */
    public function getTotalTrialDays(): int
    {
        return 14 + (self::FREE_MONTHS * 30);
    }

    /**
     * Paramètres Stripe Checkout à ajouter pour les bêta-testeurs.
     * - Coupon -30% à vie
     * - 44 jours de trial (14j standard + 1 mois offert)
     */
    public function getStripeCheckoutParams(User $user): array
    {
        if (!$this->isActiveBetaTester($user)) {
            return [];
        }

        return [
            'discounts' => [
                ['coupon' => $this->getStripeCouponId()],
            ],
            'subscription_data' => [
                'trial_period_days' => $this->getTotalTrialDays(),
            ],
        ];
    }
}

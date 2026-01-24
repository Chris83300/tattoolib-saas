<?php

namespace App\Traits;

use App\Models\Subscription;

trait HasSubscription
{
    // =============================================
    // RELATIONS
    // =============================================

    /**
     * Abonnement actif
     */
    public function subscription()
    {
        return $this->morphOne(Subscription::class, 'subscribable')
            ->where('status', Subscription::STATUS_ACTIVE)
            ->latest();
    }

    /**
     * Tous les abonnements (historique)
     */
    public function subscriptions()
    {
        return $this->morphMany(Subscription::class, 'subscribable')
            ->orderByDesc('created_at');
    }

    // =============================================
    // GETTERS
    // =============================================

    /**
     * Plan actuel (FREE par défaut)
     */
    public function getCurrentPlan(): string
    {
        return $this->subscription?->plan ?? Subscription::PLAN_FREE;
    }

    /**
     * Taux de commission actuel
     */
    public function getCommissionRate(): float
    {
        $subscription = $this->subscription;

        if (!$subscription) {
            return Subscription::COMMISSION_FREE; // 7% par défaut
        }

        return (float) $subscription->commission_rate;
    }

    // =============================================
    // CHECKS
    // =============================================

    public function isOnFreePlan(): bool
    {
        return $this->getCurrentPlan() === Subscription::PLAN_FREE;
    }

    public function isOnProPlan(): bool
    {
        return $this->getCurrentPlan() === Subscription::PLAN_PRO;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->subscription?->isActive() ?? false;
    }

    /**
     * Vérifie si une feature est accessible
     */
    public function hasFeature(string $feature): bool
    {
        $subscription = $this->subscription;

        if (!$subscription) {
            // Plan FREE par défaut
            return in_array($feature, (new Subscription)->getFreePlanFeatures());
        }

        return $subscription->hasFeature($feature);
    }

    // =============================================
    // COMMISSION
    // =============================================

    /**
     * Calcule le montant de commission en centimes
     */
    public function calculateCommission(int $amountInCents): int
    {
        $rate = $this->getCommissionRate();

        if ($rate <= 0) {
            return 0;
        }

        return (int) round($amountInCents * ($rate / 100));
    }

    /**
     * Montant net après commission
     */
    public function calculateNetAmount(int $amountInCents): int
    {
        return $amountInCents - $this->calculateCommission($amountInCents);
    }

    // =============================================
    // ACTIONS
    // =============================================

    /**
     * Créer abonnement FREE par défaut
     */
    public function createFreeSubscription(): Subscription
    {
        return $this->subscriptions()->create([
            'plan' => Subscription::PLAN_FREE,
            'status' => Subscription::STATUS_ACTIVE,
            'commission_rate' => Subscription::COMMISSION_FREE,
            'price_monthly' => null,
        ]);
    }

    /**
     * Upgrade vers PRO
     */
    public function upgradeToPro(string $stripeSubscriptionId, string $stripePriceId): Subscription
    {
        // Annuler ancien abonnement FREE si existe
        $oldSubscription = $this->subscription;
        if ($oldSubscription && $oldSubscription->isFree()) {
            $oldSubscription->update(['status' => Subscription::STATUS_CANCELED]);
        }

        // Créer nouvel abonnement PRO
        $subscription = $this->subscriptions()->create([
            'plan' => Subscription::PLAN_PRO,
            'status' => Subscription::STATUS_ACTIVE,
            'stripe_subscription_id' => $stripeSubscriptionId,
            'stripe_price_id' => $stripePriceId,
            'price_monthly' => Subscription::PRICE_PRO_MONTHLY,
            'commission_rate' => Subscription::COMMISSION_PRO,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);

        // Mettre à jour cache Tattooer
        if ($this instanceof \App\Models\Tattooer) {
            $this->update([
                'current_plan' => Subscription::PLAN_PRO,
                'is_subscribed' => true,
                'upgraded_to_pro_at' => $this->upgraded_to_pro_at ?? now(),
            ]);
        }

        return $subscription;
    }

    /**
     * Downgrade vers FREE
     */
    public function downgradeToFree(): Subscription
    {
        // Annuler abonnement PRO
        $oldSubscription = $this->subscription;
        if ($oldSubscription && $oldSubscription->isPro()) {
            $oldSubscription->cancel();
        }

        // Créer abonnement FREE
        $subscription = $this->createFreeSubscription();

        // Mettre à jour cache Tattooer
        if ($this instanceof \App\Models\Tattooer) {
            $this->update([
                'current_plan' => Subscription::PLAN_FREE,
                'is_subscribed' => false,
            ]);
        }

        return $subscription;
    }
}

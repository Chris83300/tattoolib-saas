<?php

namespace App\Traits;

use App\Models\Subscription;
use Carbon\Carbon;

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
     * Vérifier si abonnement PRO actif
     */
    public function isPro(): bool
    {
        return $this->is_subscribed === true || $this->isOnProPlan();
    }

    /**
     * Vérifier si plan FREE
     */
    public function isFree(): bool
    {
        return !$this->isPro();
    }

    /**
     * Vérifie si une feature est accessible
     */
    public function hasFeature(string $feature): bool
    {
        $subscription = $this->subscription;

        if (!$subscription) {
            // Plan FREE par défaut
            return in_array($feature, (new Subscription())->getFreePlanFeatures());
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

        // Mettre à jour modèle
        if (method_exists($this, 'update')) {
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

        // Mettre à jour modèle
        if (method_exists($this, 'update')) {
            $this->update([
                'current_plan' => Subscription::PLAN_FREE,
                'is_subscribed' => false,
            ]);
        }

        return $subscription;
    }

    // =============================================
    // FEATURES & LIMITS
    // =============================================

    /**
     * Obtenir limite design versions selon plan
     */
    public function getDesignVersionsLimit(): int
    {
        return $this->isPro() ? PHP_INT_MAX : 3;
    }

    /**
     * Vérifier si peut envoyer plus de designs
     */
    public function canSendMoreDesigns(int $currentCount): bool
    {
        if ($this->isPro()) {
            return true;
        }

        return $currentCount < 3;
    }

    /**
     * Obtenir durée conservation conversations
     */
    public function getConversationRetentionDays(): int
    {
        return $this->isPro() ? 365 : 30;
    }

    /**
     * Obtenir limite images portfolio
     */
    public function getPortfolioLimit(): int
    {
        return $this->isPro() ? 100 : 20;
    }

    /**
     * Vérifier si peut ajouter plus d'images portfolio
     */
    public function canAddMorePortfolioImages(): bool
    {
        if (!method_exists($this, 'getPortfolioCount')) {
            return true; // Si le modèle n'a pas la méthode HandlesMedia
        }

        return $this->getPortfolioCount() < $this->getPortfolioLimit();
    }

    /**
     * Obtenir fonctionnalités disponibles selon plan
     */
    public function getAvailableFeatures(): array
    {
        $features = [
            'basic_portfolio' => true,
            'messaging' => true,
            'booking_requests' => true,
            'working_hours' => true,
        ];

        if ($this->isPro()) {
            $features = array_merge($features, [
                'unlimited_portfolio' => true,
                'unlimited_designs' => true,
                'conversation_archiving' => true,
                'advanced_analytics' => true,
                'priority_support' => true,
                'custom_domain' => true,
                'api_access' => true,
                'bulk_messaging' => true,
                'client_exports' => true,
            ]);
        } else {
            $features = array_merge($features, [
                'limited_portfolio' => true,
                'limited_designs' => true,
                'basic_analytics' => true,
                'standard_support' => true,
            ]);
        }

        return $features;
    }

    /**
     * Obtenir date fin d'essai (si applicable)
     */
    public function getTrialEndsAt(): ?Carbon
    {
        return $this->trial_ends_at;
    }

    /**
     * Vérifier si en période d'essai
     */
    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Obtenir jours restants d'essai
     */
    public function getTrialDaysRemaining(): int
    {
        if (!$this->isOnTrial()) {
            return 0;
        }

        return now()->diffInDays($this->trial_ends_at);
    }

    /**
     * Obtenir statistiques d'utilisation
     */
    public function getUsageStats(): array
    {
        return [
            'portfolio_images_used' => method_exists($this, 'getPortfolioCount') ? $this->getPortfolioCount() : 0,
            'portfolio_images_limit' => $this->getPortfolioLimit(),
            'portfolio_usage_percentage' => $this->getPortfolioUsagePercentage(),
            'design_versions_limit' => $this->getDesignVersionsLimit(),
            'conversation_retention_days' => $this->getConversationRetentionDays(),
            'available_features' => $this->getAvailableFeatures(),
        ];
    }

    /**
     * Obtenir pourcentage d'utilisation portfolio
     */
    public function getPortfolioUsagePercentage(): float
    {
        if (!method_exists($this, 'getPortfolioCount')) {
            return 0.0;
        }

        $limit = $this->getPortfolioLimit();
        if ($limit === 0) {
            return 0.0;
        }

        return round(($this->getPortfolioCount() / $limit) * 100, 1);
    }
}

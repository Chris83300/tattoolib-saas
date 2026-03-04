<?php

namespace App\Services;

use App\Enums\SubscriptionPlan;

class TrialService
{
    /**
     * Démarrer un trial de 14 jours pour un artiste (tattooer ou piercer).
     */
    public function startTrial($artisan): void
    {
        $artisan->update([
            'trial_ends_at' => now()->addDays(SubscriptionPlan::STARTER->trialDays()),
            'is_blocked'    => false,
        ]);
    }

    /**
     * Vérifier si le trial est actif (pas encore souscrit, trial non expiré).
     */
    public function isOnTrial($artisan): bool
    {
        return $artisan->trial_ends_at
            && $artisan->trial_ends_at->isFuture()
            && !$artisan->is_subscribed;
    }

    /**
     * Vérifier si le trial est expiré sans abonnement.
     */
    public function isTrialExpired($artisan): bool
    {
        if (!$artisan->trial_ends_at) {
            return false;
        }
        return $artisan->trial_ends_at->isPast() && !$artisan->is_subscribed;
    }

    /**
     * L'artiste a-t-il un accès actif (trial OU abonnement OU studio) ?
     */
    public function hasActiveAccess($artisan): bool
    {
        if ($artisan->is_subscribed) {
            return true;
        }
        if (!empty($artisan->studio_id)) {
            return true; // Artiste studio = couvert par le studio
        }
        return $this->isOnTrial($artisan);
    }

    /**
     * Bloquer un artiste dont le trial est expiré.
     */
    public function blockExpiredTrial($artisan): void
    {
        if ($this->isTrialExpired($artisan)) {
            $artisan->update(['is_blocked' => true]);
        }
    }

    /**
     * Nombre de jours restants dans le trial (0 si expiré ou absent).
     */
    public function trialDaysRemaining($artisan): int
    {
        if (!$artisan->trial_ends_at || $artisan->trial_ends_at->isPast()) {
            return 0;
        }
        return (int) now()->diffInDays($artisan->trial_ends_at, false);
    }
}

<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait HasStripeConnect
{
    // =============================================
    // CONSTANTES
    // =============================================

    const CONNECT_NOT_CONNECTED = 'not_connected';
    const CONNECT_ONBOARDING = 'onboarding';
    const CONNECT_ACTIVE = 'active';
    const CONNECT_INACTIVE = 'inactive';
    const CONNECT_REACTIVATING = 'reactivating';

    const INACTIVITY_DAYS = 60; // Jours sans transaction avant désactivation

    // =============================================
    // CHECKS ÉTAT
    // =============================================

    public function isStripeConnected(): bool
    {
        return $this->stripe_connect_status === self::CONNECT_ACTIVE;
    }

    public function isStripeActive(): bool
    {
        return $this->stripe_connect_status === self::CONNECT_ACTIVE;
    }

    public function isStripeInactive(): bool
    {
        return $this->stripe_connect_status === self::CONNECT_INACTIVE;
    }

    public function isStripeOnboarding(): bool
    {
        return $this->stripe_connect_status === self::CONNECT_ONBOARDING;
    }

    public function hasNoStripeConnect(): bool
    {
        return $this->stripe_connect_status === self::CONNECT_NOT_CONNECTED;
    }

    /**
     * Vérifie si peut encaisser des paiements
     */
    public function canReceivePayments(): bool
    {
        return $this->isStripeActive()
            && !empty($this->stripe_connect_account_id);
    }

    /**
     * Vérifie si prêt pour création Connect
     */
    public function canCreateStripeConnect(): bool
    {
        return $this->hasNoStripeConnect()
            && !empty($this->siret)
            && $this->siret_verified
            && $this->has_accepted_payment_terms;
    }

    // =============================================
    // INACTIVITÉ
    // =============================================

    /**
     * Jours depuis dernière transaction
     */
    public function getDaysSinceLastTransaction(): ?int
    {
        if (!$this->stripe_connect_last_transaction_at) {
            return null;
        }

        return abs(Carbon::now()->diffInDays($this->stripe_connect_last_transaction_at));
    }

    /**
     * Vérifie si compte doit être désactivé
     */
    public function shouldBeDeactivated(): bool
    {
        if (!$this->isStripeActive()) {
            return false;
        }

        $daysSinceLastTransaction = $this->getDaysSinceLastTransaction();

        // Si aucune transaction jamais faite ET actif depuis > 60 jours
        if ($daysSinceLastTransaction === null
            && $this->stripe_connect_activated_at
            && Carbon::now()->diffInDays($this->stripe_connect_activated_at) > self::INACTIVITY_DAYS) {
            return true;
        }

        // Si dernière transaction > 60 jours
        return $daysSinceLastTransaction !== null
            && $daysSinceLastTransaction >= self::INACTIVITY_DAYS;
    }

    // =============================================
    // ACTIONS
    // =============================================

    /**
     * Accepter CGU paiements
     */
    public function acceptPaymentTerms(): void
    {
        $this->update([
            'has_accepted_payment_terms' => true,
            'payment_terms_accepted_at' => now(),
        ]);
    }

    /**
     * Générer lien Stripe Connect Express
     */
    public function generateStripeConnectLink(): string
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        // Créer compte Connect si n'existe pas
        if (!$this->stripe_connect_account_id) {
            $account = $stripe->accounts->create([
                'type' => 'express',
                'country' => 'FR',
                'email' => $this->user->email,
                'capabilities' => [
                    'card_payments' => ['requested' => true],
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'metadata' => [
                    'artist_id' => $this->id,
                    'artist_type' => get_class($this),
                    'siret' => $this->siret,
                ],
            ]);

            $this->update([
                'stripe_connect_account_id' => $account->id,
                'stripe_connect_status' => self::CONNECT_ONBOARDING,
            ]);
        }

        // Générer lien onboarding
        $accountLink = $stripe->accountLinks->create([
            'account' => $this->stripe_connect_account_id,
            'refresh_url' => route('stripe.connect.refresh', ['artist' => $this->id]),
            'return_url' => route('stripe.connect.return', ['artist' => $this->id]),
            'type' => 'account_onboarding',
        ]);

        return $accountLink->url;
    }

    /**
     * Activer le compte Connect (après onboarding)
     */
    public function activateStripeConnect(): void
    {
        $this->update([
            'stripe_connect_status' => self::CONNECT_ACTIVE,
            'stripe_connect_activated_at' => now(),
        ]);

        Log::info('Stripe Connect activé', [
            'artist_id' => $this->id,
            'artist_type' => get_class($this),
            'stripe_account_id' => $this->stripe_connect_account_id,
        ]);
    }

    /**
     * Enregistrer une transaction
     */
    public function recordStripeTransaction(): void
    {
        $this->update([
            'stripe_connect_last_transaction_at' => now(),
        ]);

        // Si était inactif, réactiver
        if ($this->isStripeInactive()) {
            $this->activateStripeConnect();
        }
    }

    /**
     * Désactiver le compte Connect
     */
    public function deactivateStripeConnect(string $reason = 'inactivity'): void
    {
        $this->update([
            'stripe_connect_status' => self::CONNECT_INACTIVE,
            'stripe_connect_deactivated_at' => now(),
        ]);

        Log::warning('Stripe Connect désactivé', [
            'artist_id' => $this->id,
            'artist_type' => get_class($this),
            'reason' => $reason,
            'days_since_last_transaction' => $this->getDaysSinceLastTransaction(),
        ]);
    }

    // =============================================
    // HELPERS UI
    // =============================================

    public function getStripeConnectStatusBadge(): string
    {
        return match($this->stripe_connect_status) {
            self::CONNECT_ACTIVE => '<span class="badge bg-success">✅ Actif</span>',
            self::CONNECT_INACTIVE => '<span class="badge bg-warning">⏸️ Inactif</span>',
            self::CONNECT_ONBOARDING => '<span class="badge bg-info">⏳ Configuration</span>',
            self::CONNECT_REACTIVATING => '<span class="badge bg-primary">🔄 Réactivation</span>',
            self::CONNECT_NOT_CONNECTED => '<span class="badge bg-secondary">❌ Non configuré</span>',
            default => '<span class="badge bg-light">❓</span>',
        };
    }

    public function getStripeConnectAlertMessage(): ?string
    {
        if ($this->hasNoStripeConnect()) {
            return "💡 Pour encaisser des paiements, vous devez activer votre compte Stripe Connect.";
        }

        if ($this->isStripeInactive()) {
            return "⏸️ Votre compte paiement est inactif. Réactivez-le pour recevoir des acomptes.";
        }

        if ($this->isStripeOnboarding()) {
            return "⏳ Finalisez votre configuration Stripe pour commencer à encaisser.";
        }

        // Alerte si proche de l'inactivité
        if ($this->isStripeActive()) {
            $daysSince = $this->getDaysSinceLastTransaction();

            if ($daysSince !== null && $daysSince >= 45 && $daysSince < 60) {
                $daysRemaining = 60 - $daysSince;
                return "⚠️ Aucune transaction depuis {$daysSince} jours.
                        Votre compte sera désactivé dans {$daysRemaining} jours.";
            }
        }

        return null;
    }
}

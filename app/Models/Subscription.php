<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Subscription extends Model
{

    protected $table = 'subscriptions';

    // =============================================
    // CONSTANTES
    // =============================================

    const PLAN_STARTER = 'starter'; // Ancien: 'free' → migré en 'starter'
    const PLAN_FREE    = 'starter'; // Alias rétrocompatibilité → STARTER
    const PLAN_PRO     = 'pro';
    const PLAN_STUDIO  = 'studio';

    const STATUS_ACTIVE   = 'active';
    const STATUS_PAST_DUE = 'past_due';
    const STATUS_CANCELED = 'canceled';
    const STATUS_UNPAID   = 'unpaid';

    const COMMISSION_FREE    = 7.00; // 7% (plan Starter)
    const COMMISSION_STARTER = 7.00; // 7%
    const COMMISSION_PRO     = 0.00; // 0%

    const PRICE_STARTER_MONTHLY    = 9.99;
    const PRICE_PRO_MONTHLY        = 29.99;
    const PRICE_STUDIO_BASE        = 59.99;
    const PRICE_STUDIO_PER_ARTIST  = 24.99;

    // =============================================
    // CONFIGURATION
    // =============================================

    protected $fillable = [
        'subscribable_type',
        'subscribable_id',
        'plan',
        'status',
        'stripe_subscription_id',
        'stripe_price_id',
        'current_period_start',
        'current_period_end',
        'canceled_at',
        'ends_at',
        'price_monthly',
        'commission_rate',
        'features',
    ];

    protected $casts = [
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'ends_at' => 'datetime',
        'price_monthly' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'features' => 'array',
    ];

    // =============================================
    // RELATIONS
    // =============================================

    /**
     * Relation avec l'utilisateur (Laravel Cashier)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphic (Tattooer ou Studio)
     */
    public function subscribable()
    {
        return $this->morphTo();
    }

    // =============================================
    // SCOPES
    // =============================================

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeFree($query)
    {
        return $query->where('plan', self::PLAN_FREE);
    }

    public function scopePro($query)
    {
        return $query->where('plan', self::PLAN_PRO);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', self::STATUS_CANCELED);
    }

    // =============================================
    // MÉTHODES STATUS
    // =============================================

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    public function isPastDue(): bool
    {
        return $this->status === self::STATUS_PAST_DUE;
    }

    public function isFree(): bool
    {
        return $this->plan === self::PLAN_FREE;
    }

    public function isPro(): bool
    {
        return $this->plan === self::PLAN_PRO;
    }

    public function isStudio(): bool
    {
        return $this->plan === self::PLAN_STUDIO;
    }

    // =============================================
    // MÉTHODES COMMISSION
    // =============================================

    /**
     * Calcule le montant de la commission en centimes
     */
    public function calculateCommissionAmount(int $amountInCents): int
    {
        if ($this->commission_rate <= 0) {
            return 0;
        }

        return (int) round($amountInCents * ($this->commission_rate / 100));
    }

    /**
     * Calcule le montant net reçu par l'artiste (après commission)
     */
    public function calculateNetAmount(int $amountInCents): int
    {
        $commission = $this->calculateCommissionAmount($amountInCents);
        return $amountInCents - $commission;
    }

    // =============================================
    // MÉTHODES FEATURES
    // =============================================

    /**
     * Vérifie si une feature est activée
     */
    public function hasFeature(string $feature): bool
    {
        if ($this->isFree()) {
            return in_array($feature, $this->getFreePlanFeatures());
        }

        if ($this->isPro()) {
            return true; // PRO a tout
        }

        return false;
    }

    /**
     * Liste des features du plan FREE
     */
    public function getFreePlanFeatures(): array
    {
        return [
            'marketplace_profile',
            'receive_requests',
            'basic_calendar',
            'chat_limited',
            'compliance_badge',
            'deposit_payment',
        ];
    }

    /**
     * Liste des features du plan PRO
     */
    public function getProPlanFeatures(): array
    {
        return array_merge($this->getFreePlanFeatures(), [
            'client_history',
            'conversation_archive',
            'traceability_records',
            'inventory_management',
            'advanced_stats',
            'priority_support',
            'profile_boost',
            'zero_commission',
        ]);
    }

    /**
     * Features manquantes (utile pour upsell)
     */
    public function getMissingFeatures(): array
    {
        if ($this->isPro()) {
            return [];
        }

        return array_diff(
            $this->getProPlanFeatures(),
            $this->getFreePlanFeatures()
        );
    }

    // =============================================
    // MÉTHODES DATES
    // =============================================

    /**
     * Vérifie si la période actuelle est valide
     */
    public function isWithinCurrentPeriod(): bool
    {
        if (!$this->current_period_start || !$this->current_period_end) {
            return false;
        }

        return Carbon::now()->between(
            $this->current_period_start,
            $this->current_period_end
        );
    }

    /**
     * Jours restants avant renouvellement
     */
    public function getDaysUntilRenewal(): ?int
    {
        if (!$this->current_period_end) {
            return null;
        }

        return Carbon::now()->diffInDays($this->current_period_end, false);
    }

    // =============================================
    // MÉTHODES ACTIONS
    // =============================================

    /**
     * Annuler l'abonnement (garde actif jusqu'à fin période)
     */
    public function cancel(): void
    {
        $this->update([
            'status' => self::STATUS_CANCELED,
            'canceled_at' => now(),
            'ends_at' => $this->current_period_end ?? now(),
        ]);

        // Annuler sur Stripe si nécessaire
        if ($this->stripe_subscription_id) {
            try {
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $stripe->subscriptions->cancel($this->stripe_subscription_id, [
                    'prorate' => false, // Pas de remboursement
                ]);
            } catch (\Exception $e) {
                \Log::error('Erreur annulation Stripe', [
                    'subscription_id' => $this->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Réactiver un abonnement annulé
     */
    public function resume(): void
    {
        $this->update([
            'status' => self::STATUS_ACTIVE,
            'canceled_at' => null,
            'ends_at' => null,
        ]);

        // Réactiver sur Stripe si nécessaire
        if ($this->stripe_subscription_id) {
            try {
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $stripe->subscriptions->update($this->stripe_subscription_id, [
                    'cancel_at_period_end' => false,
                ]);
            } catch (\Exception $e) {
                \Log::error('Erreur réactivation Stripe', [
                    'subscription_id' => $this->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    // =============================================
    // HELPERS UI
    // =============================================

    public function getBadgeHtml(): string
    {
        return match($this->plan) {
            self::PLAN_FREE => '<span class="badge bg-secondary">🆓 FREE</span>',
            self::PLAN_PRO => '<span class="badge bg-success">⭐ PRO</span>',
            self::PLAN_STUDIO => '<span class="badge bg-primary">🏢 STUDIO</span>',
            default => '<span class="badge bg-light">❓</span>',
        };
    }

    public function getPlanLabel(): string
    {
        return match($this->plan) {
            self::PLAN_FREE => 'Plan FREE (7% commission)',
            self::PLAN_PRO => 'Plan PRO (49.99€/mois)',
            self::PLAN_STUDIO => 'Plan STUDIO',
            default => 'Plan inconnu',
        };
    }
}

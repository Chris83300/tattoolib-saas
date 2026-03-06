<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @deprecated Utiliser App\Models\Subscription à la place.
 * Les deux modèles pointent vers la table 'tattooer_subscriptions'.
 * TattooerSubscription sera supprimé dans une prochaine consolidation.
 */
class TattooerSubscription extends Model
{
    use SoftDeletes;

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
        'features' => 'array',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'ends_at' => 'datetime',
        'price_monthly' => 'decimal:2',
        'commission_rate' => 'decimal:2',
    ];

    // ═══ Relations ═══

    public function subscribable()
    {
        return $this->morphTo();
    }

    // ═══ Scopes ═══

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where(function ($q) {
                         $q->whereNull('ends_at')
                           ->orWhere('ends_at', '>', now());
                     });
    }

    public function scopeForTattooer($query, $tattooerId)
    {
        return $query->where('subscribable_type', 'App\\Models\\Tattooer')
                     ->where('subscribable_id', $tattooerId);
    }

    // ═══ Helpers ═══

    public function isActive(): bool
    {
        return $this->status === 'active'
            && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    public function isPro(): bool
    {
        return $this->isActive() && $this->plan === 'pro';
    }

    public function isCanceled(): bool
    {
        return $this->canceled_at !== null;
    }

    public function isOnGracePeriod(): bool
    {
        return $this->isCanceled() && $this->ends_at?->isFuture();
    }
}

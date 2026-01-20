<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudioSubscription extends Model
{
    protected $fillable = [
        'studio_id', 'stripe_subscription_id', 'stripe_customer_id',
        'stripe_price_id', 'status', 'base_price', 'price_per_artist',
        'total_price', 'currency', 'billing_interval', 'included_artists',
        'current_artists', 'additional_artists', 'features',
        'trial_ends_at', 'current_period_start', 'current_period_end',
        'canceled_at', 'ends_at'
    ];

    protected $casts = [
        'features' => 'array',
        'base_price' => 'decimal:2',
        'price_per_artist' => 'decimal:2',
        'total_price' => 'decimal:2',
        'trial_ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'canceled_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    // Calculer prix automatiquement
    public function updatePricing(): void
    {
        $this->additional_artists = max(0, $this->current_artists - $this->included_artists);
        $this->total_price = $this->base_price + ($this->additional_artists * $this->price_per_artist);
        $this->save();
    }
}

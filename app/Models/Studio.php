<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Studio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'slug', 'description', 'address', 'city', 'postal_code',
        'country', 'phone', 'email', 'website', 'social_media_links',
        'logo_url', 'cover_images', 'latitude', 'longitude',
        'opening_hours', 'facilities', 'settings', 'siret', 'vat_number',
        'stripe_customer_id', 'total_artists', 'is_active', 'is_verified',
        'verified_at',
        // Payment mode
        'payment_mode',
        'uses_accounting_module',
        'payment_mode_changed_at',
    ];

    protected $casts = [
        'social_media_links' => 'array',
        'cover_images' => 'array',
        'opening_hours' => 'array',
        'facilities' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'uses_accounting_module' => 'boolean',
        'payment_mode_changed_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Relations
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function artists()
    {
        return $this->hasMany(StudioArtist::class);
    }

    public function activeArtists()
    {
        return $this->artists()->where('status', 'active');
    }

    public function subscription()
    {
        return $this->hasOne(StudioSubscription::class);
    }

    // Relations existantes à préserver
    public function tattooers()
    {
        return $this->hasMany(Tattooer::class);
    }

    public function workingHours()
    {
        return $this->hasMany(WorkingHour::class);
    }

    // Helpers
    public function calculateSubscriptionPrice(): float
    {
        $activeCount = $this->activeArtists()->count();
        $additionalArtists = max(0, $activeCount - 1); // -1 car 1er inclus

        return 79.99 + ($additionalArtists * 39.99);
    }

    // ===========================================
    // RELATIONS
    // ===========================================

    /**
     * Écritures comptables internes
     */
    public function accountingEntries()
    {
        return $this->hasMany(StudioAccountingEntry::class)
            ->orderByDesc('transaction_date');
    }

    // ===========================================
    // MÉTHODES PAYMENT MODE
    // ===========================================

    /**
     * Vérifie si le studio gère les paiements de manière centralisée
     */
    public function managesPaymentsCentrally(): bool
    {
        return $this->payment_mode === 'studio_managed';
    }

    /**
     * Vérifie si les artistes encaissent directement
     */
    public function allowsDirectArtistPayments(): bool
    {
        return $this->payment_mode === 'artist_direct';
    }

    /**
     * Changer le mode de paiement
     */
    public function changePaymentMode(string $newMode): void
    {
        $oldMode = $this->payment_mode;

        $this->update([
            'payment_mode' => $newMode,
            'payment_mode_changed_at' => now(),
        ]);

        \Log::info("Studio payment mode changed", [
            'studio_id' => $this->id,
            'old_mode' => $oldMode,
            'new_mode' => $newMode,
        ]);
    }

    /**
     * Vérifier si le module comptabilité est activé
     */
    public function hasAccountingModule(): bool
    {
        return $this->uses_accounting_module;
    }
}

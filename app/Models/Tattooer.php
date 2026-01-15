<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tattooer extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'studio_id', // ✅ Relation avec Studio
        'siret',
        'siret_verified',
        'name',
        'studio_name', // ✅ Garde pour les tatoueurs indépendants
        'bio',
        'phone',
        'address',
        'city',
        'postal_code',
        'email',

        // Stripe Connect
        'stripe_connect_account_id',
        'stripe_onboarding_complete',

        // Réseaux sociaux
        'instagram',
        'facebook',
        'tiktok',
        'website',

        // Paramètres par défaut
        'minimum_deposit',
        'default_deposit_rate',
        'default_client_payment_deadline_days',
        'default_tattooer_design_deadline_days',
        'default_design_versions_included',

        // Délais d'attente
        'weekday_wait_days',
        'weekend_wait_days',
    ];

    protected $casts = [
        'siret_verified' => 'boolean',
        'stripe_onboarding_complete' => 'boolean',
        'minimum_deposit' => 'decimal:2',
    ];

    // ===== CONFIGURATION MEDIALIBRARY =====

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('portfolio')
            ->useDisk('public');

        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useDisk('public');
    }

    // ===== RELATIONS =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function workingHours(): HasMany
    {
        return $this->hasMany(WorkingHour::class);
    }

    public function bookingRequests(): HasMany
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // ===== MÉTHODES MÉTIER =====

    public function hasCompletedStripeOnboarding(): bool
    {
        return $this->stripe_onboarding_complete
            && !empty($this->stripe_connect_account_id);
    }

    public function canAcceptBookings(): bool
    {
        return $this->siret_verified
            && $this->hasCompletedStripeOnboarding();
    }

    public function calculateDepositAmount(float $totalPrice): float
    {
        $calculated = ($totalPrice * $this->default_deposit_rate) / 100;
        return max($calculated, $this->minimum_deposit);
    }

    // ===== SCOPES =====

    public function scopeVerified($query)
    {
        return $query->where('siret_verified', true);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('user', function($q) {
            $q->where('is_active', true);
        });
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('studio_name', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%")
              ->orWhereHas('studio', function($sq) use ($search) {
                  $sq->where('name', 'like', "%{$search}%");
              });
        });
    }

    // ===== ACCESSEURS =====

    /**
     * Retourne le nom du studio (relation ou champ direct)
     */
    public function getStudioNameAttribute(): ?string
    {
        // Si lié à un studio, retourner son nom
        if ($this->studio_id && $this->studio) {
            return $this->studio->name;
        }

        // Sinon, utiliser le champ studio_name (indépendant)
        return $this->attributes['studio_name'] ?? null;
    }

    /**
     * Retourne l'adresse complète (tatoueur ou studio)
     */
    public function getFullAddressAttribute(): string
    {
        if ($this->studio_id && $this->studio) {
            return $this->studio->full_address;
        }

        return "{$this->address}, {$this->postal_code} {$this->city}";
    }
}

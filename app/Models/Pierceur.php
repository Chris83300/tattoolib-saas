<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\HasCompliance;
use App\Traits\BookableArtist;
use App\Traits\HasSubscription;
use App\Traits\HasStripeConnect;

class Pierceur extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSubscription, BookableArtist, HasCompliance, HasStripeConnect;

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($pierceur) {
            if (empty($pierceur->slug)) {
                $pierceur->slug = Str::slug($pierceur->user->name . '-' . $pierceur->id);
            }
        });

        static::updating(function ($pierceur) {
            if (empty($pierceur->slug)) {
                $pierceur->slug = Str::slug($pierceur->user->name . '-' . $pierceur->id);
            }
        });
    }

    protected $table = 'piercers';

    protected $fillable = [
        'user_id',
        'studio_id',
        'siret',
        'siret_verified',
        'name',
        'slug',
        'specialization',
        'studio_name',
        'bio',
        'phone',
        'address',
        'city',
        'postal_code',
        'email',

        'stripe_connect_account_id',
        'stripe_onboarding_complete',
        'stripe_connect_status',
        'stripe_connect_activated_at',
        'stripe_connect_last_transaction_at',
        'stripe_connect_deactivated_at',
        'has_accepted_payment_terms',
        'payment_terms_accepted_at',

        'current_plan',
        'is_subscribed',
        'upgraded_to_pro_at',

        'instagram',
        'facebook',
        'tiktok',
        'website',

        'minimum_deposit',
        'default_deposit_rate',
        'default_client_payment_deadline_days',
        'default_design_versions_included',

        'weekday_wait_days',
        'weekend_wait_days',

        'is_decision_maker',
        'compliance_status',
        'last_compliance_check_at',
    ];

    protected $casts = [
        'siret_verified' => 'boolean',
        'stripe_onboarding_complete' => 'boolean',
        'stripe_connect_activated_at' => 'datetime',
        'stripe_connect_last_transaction_at' => 'datetime',
        'stripe_connect_deactivated_at' => 'datetime',
        'has_accepted_payment_terms' => 'boolean',
        'payment_terms_accepted_at' => 'datetime',
        'minimum_deposit' => 'decimal:2',
        'is_subscribed' => 'boolean',
        'upgraded_to_pro_at' => 'datetime',
        'is_decision_maker' => 'boolean',
        'last_compliance_check_at' => 'datetime',
    ];

    public function registerMediaCollections(): void
    {
        // Avatar
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/images/default-pierceur-avatar.png')
            ->useFallbackPath(public_path('/images/default-pierceur-avatar.png'))
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->sharpen(10);
            });

        // Portfolio Réalisations
        $this->addMediaCollection('portfolio')
            ->useFallbackUrl('/images/default-portfolio.png')
            ->useFallbackPath(public_path('/images/default-portfolio.png'))
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(400)
                    ->sharpen(10);
            });

        // Dessins
        $this->addMediaCollection('drawings')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(400)
                    ->sharpen(10);
            });

        // Avant/Après (paires d'images)
        $this->addMediaCollection('before_after')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->sharpen(10);
            });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function workingHours(): MorphMany
    {
        return $this->morphMany(WorkingHour::class, 'owner');
    }

    public function availabilities(): MorphMany
    {
        return $this->morphMany(Availability::class, 'owner');
    }

    public function bookingRequests(): MorphMany
    {
        return $this->morphMany(BookingRequest::class, 'bookable');
    }

    public function appointments(): MorphMany
    {
        return $this->morphMany(Appointment::class, 'bookable');
    }

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

    public function getSpecializationLabelAttribute(): string
    {
        return match($this->specialization) {
            'pierceur' => 'Pierceur',
            'bodemodeur' => 'Bodemodeur',
            'pierceur_bodemodeur' => 'Pierceur / Bodemodeur',
            default => 'Non spécifié',
        };
    }

    public function isPierceur(): bool
    {
        return $this->specialization === 'pierceur' || $this->specialization === 'pierceur_bodemodeur';
    }

    public function isBodemodeur(): bool
    {
        return $this->specialization === 'bodemodeur' || $this->specialization === 'pierceur_bodemodeur';
    }

    public function isPro(): bool
    {
        return $this->subscription_plan === 'pro';
    }

    public function isFree(): bool
    {
        return $this->subscription_plan === 'free';
    }

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

    public function scopeBySpecialization($query, string $specialization)
    {
        return $query->where('specialization', $specialization);
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

    public function getStudioNameAttribute(): ?string
    {
        if ($this->studio_id && $this->studio) {
            return $this->studio->name;
        }

        return $this->attributes['studio_name'] ?? null;
    }

    public function getFullAddressAttribute(): string
    {
        if ($this->studio_id && $this->studio) {
            return $this->studio->full_address;
        }

        return "{$this->address}, {$this->postal_code} {$this->city}";
    }

    public function getTaxRate(): float
    {
        return match($this->country) {
            'FR' => 20.0,
            'BE' => 21.0,
            default => 0.0,
        };
    }
}

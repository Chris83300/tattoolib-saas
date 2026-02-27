<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
use App\Traits\HasWorkingHours;
use App\Traits\HandlesMedia;
use App\Traits\CalculatesStats;
use App\Models\Traits\IsArtisan;
use App\Contracts\ArtisanInterface;

class Piercer extends Model implements HasMedia, ArtisanInterface
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    use HasSubscription, BookableArtist, HasCompliance, HasStripeConnect;
    use HasWorkingHours, HandlesMedia, CalculatesStats;
    use IsArtisan;

    protected $table = 'piercers';

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($piercer) {
            if (empty($piercer->slug)) {
                $piercer->slug = Str::slug(($piercer->user->name ?? 'piercer') . '-' . uniqid());
            }
        });

        static::updating(function ($piercer) {
            if (empty($piercer->slug)) {
                $piercer->slug = Str::slug(($piercer->user->name ?? 'piercer') . '-' . $piercer->id);
            }
        });
    }

    protected $fillable = [
        'user_id',
        'studio_id',
        'first_name',
        'last_name',
        'pseudo',
        'name',
        'slug',
        'siret',
        'siret_verified',
        'is_decision_maker',
        'compliance_status',
        'last_compliance_check_at',
        'studio_name',
        'bio',
        'styles',
        'custom_styles',
        'years_of_experience',
        'minimum_price',
        'wait_time_weeks_min',
        'wait_time_weeks_max',
        'working_hours',
        'phone',
        'address',
        'city',
        'postal_code',
        'email',

        // Aftercare
        'aftercare_sheet',
        'aftercare_reminder_2h',
        'aftercare_reminder_7d',
        'aftercare_reminder_14d',

        // Stripe Connect
        'stripe_connect_account_id',
        'stripe_onboarding_complete',
        'stripe_connect_status',
        'stripe_connect_activated_at',
        'stripe_connect_last_transaction_at',
        'stripe_connect_deactivated_at',
        'has_accepted_payment_terms',
        'payment_terms_accepted_at',

        // Abonnement
        'current_plan',
        'is_subscribed',
        'has_compliance_badge',
        'upgraded_to_pro_at',

        // Paiements
        'minimum_deposit',
        'default_deposit_rate',
        'default_client_payment_deadline_days',
        'default_design_versions_included',
        'weekday_wait_days',
        'weekend_wait_days',

        // Réseaux sociaux
        'instagram',
        'facebook',
        'tiktok',
        'website',

        // Vérification admin
        'admin_verified_at',

        // Spécifique piercing
        'pricing_grid',
        'custom_pricing_note',
        'piercing_types',
        'default_appointment_duration',
    ];

    protected $casts = [
        'siret_verified' => 'boolean',
        'is_decision_maker' => 'boolean',
        'stripe_onboarding_complete' => 'boolean',
        'has_accepted_payment_terms' => 'boolean',
        'has_compliance_badge' => 'boolean',
        'is_subscribed' => 'boolean',
        'aftercare_reminder_2h' => 'boolean',
        'aftercare_reminder_7d' => 'boolean',
        'aftercare_reminder_14d' => 'boolean',
        'working_hours' => 'string',
        'styles' => 'json',
        'custom_styles' => 'json',
        'years_of_experience' => 'integer',
        'minimum_price' => 'decimal:2',
        'wait_time_weeks_min' => 'integer',
        'wait_time_weeks_max' => 'integer',
        'pricing_grid' => 'json',
        'piercing_types' => 'json',
        'default_appointment_duration' => 'integer',
        'minimum_deposit' => 'decimal:2',
        'default_deposit_rate' => 'decimal:2',
        'stripe_connect_activated_at' => 'datetime',
        'stripe_connect_last_transaction_at' => 'datetime',
        'stripe_connect_deactivated_at' => 'datetime',
        'payment_terms_accepted_at' => 'datetime',
        'upgraded_to_pro_at' => 'datetime',
        'last_compliance_check_at' => 'datetime',
        'admin_verified_at' => 'datetime',
    ];

    // ===== CONFIGURATION MEDIALIBRARY =====

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->useFallbackUrl('/images/default-tattooer-avatar.png')
            ->useFallbackPath(public_path('/images/default-tattooer-avatar.png'))
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(200)->height(200)->sharpen(10);
            });

        $this->addMediaCollection('banner')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)->height(133)->sharpen(10);
            });

        $this->addMediaCollection('portfolio')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'image/heic'])
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)->height(400)->sharpen(10);
            });
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

    public function isStudioArtist(): bool
    {
        return $this->studio_id !== null;
    }

    public function bookingRequests(): MorphMany
    {
        return $this->morphMany(BookingRequest::class, 'bookable');
    }

    public function appointments(): MorphMany
    {
        return $this->morphMany(Appointment::class, 'bookable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    public function availabilities(): MorphMany
    {
        return $this->morphMany(Availability::class, 'owner');
    }

    // ===== ACCESSORS =====

    public function getFullNameAttribute(): string
    {
        return $this->pseudo ??
               trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')) ?:
               $this->name ??
               'N/A';
    }

    public function getLocationAttribute(): string
    {
        return $this->city . ($this->postal_code ? ' (' . $this->postal_code . ')' : '');
    }

    public function getUserStatusAttribute(): string
    {
        return $this->user->status ?? 'pending_verification';
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
            return $this->studio->full_address ?? "{$this->address}, {$this->postal_code} {$this->city}";
        }
        return "{$this->address}, {$this->postal_code} {$this->city}";
    }

    // ===== MUTATORS =====

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = $value;
        $this->syncNameField();
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = $value;
        $this->syncNameField();
    }

    private function syncNameField()
    {
        if (isset($this->attributes['first_name']) || isset($this->attributes['last_name'])) {
            $firstName = $this->attributes['first_name'] ?? $this->first_name ?? '';
            $lastName = $this->attributes['last_name'] ?? $this->last_name ?? '';
            $this->attributes['name'] = trim($firstName . ' ' . $lastName);
        }
    }

    // ===== MÉTHODES MÉTIER =====

    public function hasCompletedStripeOnboarding(): bool
    {
        return $this->stripe_onboarding_complete
            && !empty($this->stripe_connect_account_id);
    }

    /**
     * Retourne le stripe_account_id à utiliser pour les paiements.
     * Si artiste studio en mode centralisé → stripe du studio.
     * Sinon → stripe de l'artiste.
     */
    public function getStripeAccountId(): ?string
    {
        if ($this->studio_id) {
            $studio = $this->studio;
            if ($studio && $studio->payment_mode === 'centralized') {
                return $studio->stripe_account_id;
            }
        }
        return $this->stripe_connect_account_id;
    }

    /**
     * Vérifie si l'artiste a un Stripe Connect opérationnel
     */
    public function hasStripeConnect(): bool
    {
        return !empty($this->getStripeAccountId());
    }

    /**
     * L'artiste a-t-il besoin de configurer son propre Stripe Connect ?
     * Non si le studio est en mode centralisé.
     */
    public function needsOwnStripeConnect(): bool
    {
        if ($this->studio_id) {
            $studio = $this->studio;
            if ($studio && $studio->payment_mode === 'centralized') {
                return false;
            }
        }
        return true;
    }

    public function canAcceptBookings(): bool
    {
        return $this->siret_verified && $this->hasCompletedStripeOnboarding();
    }

    public function calculateDepositAmount(float $totalPrice): float
    {
        $calculated = ($totalPrice * $this->default_deposit_rate) / 100;
        return max($calculated, $this->minimum_deposit);
    }

    public function isPro(): bool
    {
        return $this->activeSubscription !== null
            && $this->activeSubscription->plan === 'pro';
    }

    public function isFree(): bool
    {
        return !$this->isPro();
    }

    public function commissionRate(): float
    {
        return $this->isPro() ? 0.0 : 7.0;
    }

    // ===== MÉTHODES SPÉCIFIQUES PIERCING =====

    public function getPricingGrid(): array
    {
        $grid = $this->pricing_grid;
        if (is_string($grid)) {
            $decoded = json_decode($grid, true);
            return is_array($decoded) ? $decoded : [];
        }
        return is_array($grid) ? $grid : [];
    }

    public function getPricingForType(string $type): ?float
    {
        foreach ($this->getPricingGrid() as $item) {
            if (isset($item['type']) && strtolower($item['type']) === strtolower($type)) {
                return isset($item['price']) ? (float) $item['price'] : null;
            }
        }
        return null;
    }

    // ===== SUBSCRIPTIONS =====

    public function subscriptions()
    {
        return $this->morphMany(TattooerSubscription::class, 'subscribable');
    }

    public function activeSubscription()
    {
        return $this->morphOne(TattooerSubscription::class, 'subscribable')
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
                    })
                    ->latest();
    }

    // ===== SCOPES =====

    public function scopeVerified($query)
    {
        return $query->where('siret_verified', true);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('is_active', true);
        });
    }

    public function scopeByCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('studio_name', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%");
        });
    }
}

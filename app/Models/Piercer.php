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

class Piercer extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSubscription, BookableArtist, HasCompliance, HasStripeConnect;

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($Piercer) {
            if (empty($Piercer->slug)) {
                $Piercer->slug = Str::slug($Piercer->user->name . '-' . $Piercer->id);
            }
        });

        static::updating(function ($Piercer) {
            if (empty($Piercer->slug)) {
                $Piercer->slug = Str::slug($Piercer->user->name . '-' . $Piercer->id);
            }
        });
    }

    protected $table = 'piercers';

    protected $fillable = [
        'user_id',
        'studio_id',
        'first_name',
        'last_name',
        'pseudo',
        'siret',
        'siret_verified',
        'name',
        'slug',
        'specialization',
        'studio_name',
        'studio_id',
        'bio',
        'phone',
        'address',
        'city',
        'postal_code',
        'email',
        'created_at',
        'updated_at',
        'deleted_at',

        // Stripe Connect
        'stripe_connect_account_id',
        'stripe_onboarding_complete',
        'stripe_connect_status',
        'stripe_connect_activated_at',
        'stripe_connect_last_transaction_at',
        'stripe_connect_deactivated_at',
        'has_accepted_payment_terms',
        'payment_terms_accepted_at',

        // Configuration
        'minimum_deposit',
        'default_deposit_rate',
        'default_client_payment_deadline_days',
        'default_design_versions_included',
        'weekday_wait_days',
        'weekend_wait_days',

        // Abonnement
        'current_plan',
        'subscription_plan',
        'is_subscribed',
        'upgraded_to_pro_at',

        // Réseaux sociaux
        'instagram',
        'facebook',
        'tiktok',
        'website',

        // Conformité
        'has_compliance_badge',
        'admin_verified_at',
    ];

    protected $casts = [
        'admin_verified_at' => 'datetime',
        'stripe_connect_activated_at' => 'datetime',
        'stripe_connect_last_transaction_at' => 'datetime',
        'stripe_connect_deactivated_at' => 'datetime',
        'payment_terms_accepted_at' => 'datetime',
        'upgraded_to_pro_at' => 'datetime',
        'is_subscribed' => 'boolean',
        'has_compliance_badge' => 'boolean',
        'siret_verified' => 'boolean',
        'stripe_onboarding_complete' => 'boolean',
        'has_accepted_payment_terms' => 'boolean',
        'minimum_deposit' => 'decimal:2',
        'default_deposit_rate' => 'decimal:2',
    ];

    protected $appends = ['specialization_label'];

    protected $dates = [
        'deleted_at',
    ];

    // ===== RELATIONS =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    // Relations polymorphiques (comme Tattooer)
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

    // ===== MÉTHODES MÉTIER =====

    public function isVerified(): bool
    {
        return $this->siret_verified && $this->has_compliance_badge;
    }

    public function canAcceptBookings(): bool
    {
        return $this->isVerified() && $this->stripe_onboarding_complete;
    }

    public function isPro(): bool
    {
        return $this->current_plan === 'pro';
    }

    public function isPiercer(): bool
    {
        return $this->specialization === 'Piercer';
    }

    public function isBodemodeur(): bool
    {
        return $this->specialization === 'bodemodeur';
    }

    public function isBoth(): bool
    {
        return $this->specialization === 'Piercer_bodemodeur';
    }

    public function getSpecializationLabel(): string
    {
        return match($this->specialization) {
            'Piercer' => 'Piercer',
            'bodemodeur' => 'Bodemodeur',
            'Piercer_bodemodeur' => 'Piercer / Bodemodeur',
            default => 'Spécialiste',
        };
    }

    public function getSpecializationLabelAttribute(): string
    {
        return $this->getSpecializationLabel();
    }

    // ===== SCOPE =====

    public function scopeVerified($query)
    {
        return $query->where('siret_verified', true)->where('has_compliance_badge', true);
    }

    public function scopeActive($query)
    {
        return $query; // Pas de filtre pour la recherche publique
    }

    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('studio_name', 'like', "%{$search}%")
              ->orWhere('city', 'like', "%{$search}%");
        });
    }

    // ===== MÉDIAS =====

    public function registerMediaCollections(): void
    {
        // Avatar tatoueur (unique)
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp'
            ])
            ->useFallbackUrl('/images/default-piercer-avatar.png')
            ->useFallbackPath(public_path('/images/default-piercer-avatar.png'))
            ->useDisk('public');

        // Bannière profil (unique)
        $this->addMediaCollection('banner')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp'
            ])
            ->useDisk('public');

        $this->addMediaCollection('portfolio')
            ->useDisk('public')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Avatar conversions
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10)
            ->performOnCollections('avatar');

        // Bannière conversions
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(133)
            ->sharpen(10)
            ->performOnCollections('banner');

        // Portfolio conversions
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->sharpen(10)
            ->performOnCollections('portfolio');
    }
}

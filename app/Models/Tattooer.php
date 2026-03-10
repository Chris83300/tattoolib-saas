<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Client;
use App\Models\BookingRequest;
use App\Models\StudioArtist;
use App\Models\Piercer;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Availability;
use App\Traits\HasCompliance;
use App\Traits\BookableArtist;
use App\Traits\HasSubscription;
use App\Traits\HasStripeConnect;
use App\Traits\HasWorkingHours;
use App\Traits\HandlesMedia;
use App\Traits\CalculatesStats;
use App\Models\Traits\IsArtisan;
use App\Contracts\ArtisanInterface;

class Tattooer extends Model implements HasMedia, ArtisanInterface
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSubscription, BookableArtist, HasCompliance, HasStripeConnect, HasWorkingHours, HandlesMedia, CalculatesStats;
    use IsArtisan;

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($tattooer) {
            if (empty($tattooer->slug)) {
                $tattooer->slug = Str::slug($tattooer->user->name . '-' . $tattooer->id);
            }
        });

        static::updating(function ($tattooer) {
            if (empty($tattooer->slug)) {
                $tattooer->slug = Str::slug($tattooer->user->name . '-' . $tattooer->id);
            }
        });

        static::saved(function ($tattooer) {
            // Mettre à jour la date de validation si le statut change vers "active"
            if ($tattooer->isDirty('user.status') && $tattooer->user->status === 'active') {
                $tattooer->admin_verified_at = now();
            }
        });
    }

    protected $fillable = [
        'user_id',
        'studio_id', // ✅ Relation avec Studio
        'siret',
        'siret_verified',
        'first_name', // ✅ Ajout pour inscription
        'last_name', // ✅ Ajout pour inscription
        'pseudo', // ✅ Ajout pour inscription
        'name', // ✅ Nom complet (fallback)
        'studio_name', // ✅ Nom du studio (indépendant)
        'bio', // ✅ Description du tatoueur
        'working_hours', // ✅ Horaires JSON
        'styles', // ✅ Styles de tatouage (JSON)
        'custom_styles', // ✅ Styles personnalisés (JSON)
        'years_of_experience', // ✅ Expérience
        'minimum_price', // ✅ Prix minimum
        'wait_time_weeks_min', // ✅ Temps d'attente min
        'wait_time_weeks_max', // ✅ Temps d'attente max
        'slug', // ✅ URL slug
        'phone', // ✅ Téléphone
        'address', // ✅ Adresse
        'city', // ✅ Ville
        'postal_code', // ✅ Code postal
        'email', // ✅ Email professionnel
        'stripe_connect_account_id', // ✅ Stripe Connect
        'stripe_connect_status', // ✅ Statut Stripe Connect
        'stripe_connect_activated_at', // ✅ Date activation Stripe
        'stripe_connect_last_transaction_at', // ✅ Dernière transaction Stripe
        'stripe_connect_deactivated_at', // ✅ Date désactivation Stripe
        'has_accepted_payment_terms', // ✅ CGU paiements
        'payment_terms_accepted_at', // ✅ Date acceptation CGU
        'current_plan', // ✅ Plan actuel
        'is_subscribed', // ✅ Statut abonnement
        'is_blocked', // ✅ Compte bloqué post-trial
        'trial_ends_at', // ✅ Fin période d'essai
        'has_compliance_badge', // ✅ Badge conformité
        'upgraded_to_pro_at', // ✅ Date upgrade PRO
        'stripe_onboarding_complete', // ✅ Onboarding Stripe terminé
        'instagram', // ✅ Instagram
        'facebook', // ✅ Facebook
        'tiktok', // ✅ TikTok
        'website', // ✅ Site web
        'minimum_deposit', // ✅ Acompte minimum
        'default_deposit_rate', // ✅ Taux acompte défaut
        'default_client_payment_deadline_days', // ✅ Délai paiement client
        'default_tattooer_design_deadline_days', // ✅ Délai design tattooer
        'default_design_versions_included', // ✅ Versions design incluses
        'weekday_wait_days', // ✅ Jours attente semaine
        'weekend_wait_days', // ✅ Jours attente week-end
        'admin_verified_at', // ✅ Date validation admin

        // Aftercare (soins post-tatouage)
        'aftercare_sheet', // ✅ Contenu fiche de soins
        'aftercare_reminder_2h', // ✅ Rappel 2h après RDV
        'aftercare_reminder_7d', // ✅ Rappel 7 jours après RDV
        'aftercare_reminder_14d', // ✅ Rappel 14 jours après RDV

        // Conformité réglementaire
        'is_decision_maker',
        'compliance_status',
        'last_compliance_check_at',
    ];

    protected $casts = [
        'siret_verified' => 'boolean',
        'stripe_onboarding_complete' => 'boolean',
        'has_accepted_payment_terms' => 'boolean',
        'is_decision_maker' => 'boolean',
        'has_compliance_badge' => 'boolean',
        'is_subscribed'  => 'boolean',
        'is_blocked'     => 'boolean',
        'trial_ends_at'  => 'datetime',
        'working_hours' => 'string', // ✅ Garder comme string pour JSON decode
        'styles' => 'json', // ✅ Styles de tatouage (JSON)
        'custom_styles' => 'json', // ✅ Styles personnalisés (JSON)
        'years_of_experience' => 'integer',
        'minimum_price' => 'decimal:2',
        'wait_time_weeks_min' => 'integer',
        'wait_time_weeks_max' => 'integer',
        'stripe_connect_activated_at' => 'datetime',
        'stripe_connect_last_transaction_at' => 'datetime',
        'stripe_connect_deactivated_at' => 'datetime',
        'payment_terms_accepted_at' => 'datetime',
        'upgraded_to_pro_at' => 'datetime',
        'last_compliance_check_at' => 'datetime',
        'aftercare_reminder_2h' => 'boolean',
        'aftercare_reminder_7d' => 'boolean',
        'aftercare_reminder_14d' => 'boolean',
        'admin_verified_at' => 'datetime',
        'minimum_deposit' => 'decimal:2',
        'user_status' => 'string',
    ];

    // ===== CONFIGURATION MEDIALIBRARY =====

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
            ->useFallbackUrl('/images/default-tattooer-avatar.png')
            ->useFallbackPath(public_path('/images/default-tattooer-avatar.png'))
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->sharpen(10);
            });

        // Bannière profil (unique)
        $this->addMediaCollection('banner')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp'
            ])
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(133)
                    ->sharpen(10);
            });

        // Portfolio tattoos réalisés (multiples)
        $this->addMediaCollection('portfolio')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/heic'
            ])
            ->useFallbackUrl('/images/default-portfolio.png')
            ->useFallbackPath(public_path('/images/default-portfolio.png'))
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(400)
                    ->sharpen(10);
            });

        // Dessins / sketches (multiples)
        $this->addMediaCollection('drawings')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp'
            ])
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(400)
                    ->height(400)
                    ->sharpen(10);
            });

        // Avant/Après (multiples, par paires)
        $this->addMediaCollection('before_after')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/heic'
            ])
            ->useDisk('public')
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('thumb')
                    ->width(200)
                    ->height(200)
                    ->sharpen(10);
            });
    }

    // ===== RELATIONS =====

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studioArtist()
    {
        return $this->hasOne(StudioArtist::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function isStudioArtist(): bool
    {
        return $this->studio_id !== null;
    }

    public function workingHours(): MorphMany
    {
        return $this->morphMany(WorkingHour::class, 'owner');
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

    // ===== MUTATORS FOR SYNC =====

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

    // ===== FILAMENT SYNC METHODS =====

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
            if ($studio && $studio->payment_mode === 'studio_managed') {
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
            if ($studio && $studio->payment_mode === 'studio_managed') {
                return false;
            }
        }
        return true;
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

    public function scopeMarketplaceVisible($query)
    {
        $trialDays = (int) config('inkpik.trial_days', 14);

        return $query
            ->where('is_blocked', false)
            ->where(function ($q) {
                $trialDays = (int) config('inkpik.trial_days', 14);
                $trialCutoff = now()->subDays($trialDays);

                // Visible si l'artiste a un abonnement (hors studio) ou est en période d'essai
                $q->where(function ($paid) {
                        $paid->whereNull('studio_id')
                            ->where('is_subscribed', true);
                    })
                    ->orWhere('trial_ends_at', '>', now())
                    ->orWhere(function ($trial) use ($trialCutoff) {
                        $trial->whereNull('trial_ends_at')
                            ->where('created_at', '>', $trialCutoff);
                    })
                    // Ou si l'artiste dépend d'un studio qui est en essai / abonné
                    ->orWhereHas('studio', function ($sq) use ($trialCutoff) {
                        $sq->where('is_subscribed', true)
                            ->orWhere('trial_ends_at', '>', now())
                            ->orWhere(function ($trial) use ($trialCutoff) {
                                $trial->whereNull('trial_ends_at')
                                    ->where('created_at', '>', $trialCutoff);
                            });
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

    /**
     * Retourne le taux de TVA selon le pays
     */
    public function getTaxRate(): float
    {
        // Exemple: France = 20%, Belgique = 21%, etc.
        // À adapter selon vos besoins
        return match($this->country) {
            'FR' => 20.0,
            'BE' => 21.0,
            default => 0.0,
        };
    }

    // ═══ Subscription Relations ═══

    public function subscriptions()
    {
        return $this->hasManyThrough(
            \Laravel\Cashier\Subscription::class,
            User::class,
            'id', // Foreign key on users table
            'user_id', // Foreign key on subscriptions table
            'user_id', // Local key on tattooers table
            'id' // Local key on users table
        );
    }

    public function activeSubscription()
    {
        return $this->hasOneThrough(
            \Laravel\Cashier\Subscription::class,
            User::class,
            'id', // Foreign key on users table
            'user_id', // Foreign key on subscriptions table
            'user_id', // Local key on tattooers table
            'id' // Local key on users table
        )->where('stripe_status', 'active')
         ->where(function ($q) {
             $q->whereNull('ends_at')
               ->orWhere('ends_at', '>', now());
         })
         ->latest();
    }

    public function isPro(): bool
    {
        // Artiste studio = toujours PRO (le studio paie, is_subscribed=true)
        if ($this->studio_id) {
            return true;
        }

        // Artiste indépendant : vérifier trial, is_subscribed ou activeSubscription
        if ($this->isOnTrial()) {
            return true;
        }

        if ($this->is_subscribed) {
            return true;
        }

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

    /**
     * Override HasSubscription::getCurrentPlan() pour les artistes studio.
     */
    public function getCurrentPlan(): string
    {
        if ($this->studio_id) {
            return \App\Models\Subscription::PLAN_PRO;
        }

        return $this->subscription?->plan ?? \App\Models\Subscription::PLAN_FREE;
    }

    /**
     * Override HasSubscription::getCommissionRate() pour les artistes studio.
     */
    public function getCommissionRate(): float
    {
        if ($this->studio_id) {
            return 0.0;
        }

        $subscription = $this->subscription;
        if (!$subscription) {
            return \App\Models\Subscription::COMMISSION_FREE;
        }

        return (float) $subscription->commission_rate;
    }

    // ═══ TRIAL & SUBSCRIPTION ═══

    /**
     * En période d'essai : trial non expiré ET pas encore souscrit.
     */
    public function isOnTrial(): bool
    {
        return $this->trial_ends_at
            && $this->trial_ends_at->isFuture()
            && !$this->is_subscribed;
    }

    public function canOperate(): bool
    {
        return !$this->is_blocked;
    }

    public function isReadOnly(): bool
    {
        return !$this->canOperate();
    }
}

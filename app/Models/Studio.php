<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Studio extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'name',
        'slug',
        'description',
        'bio',
        'address',
        'city',
        'postal_code',
        'country',
        'phone',
        'email',
        'website',
        'siret',
        'hygiene_certificate',
        'ars_declaration_number',
        'logo_url',
        'banner_url',
        'payment_mode',              // 'artist_direct' ou 'studio_managed' (colonne DB existante)
        'artist_commission_rate',    // % prélevé par le studio sur ses artistes (null = aucune)
        'stripe_account_id',         // Stripe Connect du studio
        'stripe_onboarding_complete',
        'max_artists',         // Limite contractuelle (null = illimité)
        'is_active',
        'opening_hours',       // JSON : {"monday": {"open": "09:00", "close": "19:00"}, ...}
        'social_media_links',  // JSON : {"instagram": "...", "facebook": "...", ...}
        'social_links',        // Alias pour compatibilité prompt
        'trial_ends_at',   // Trial local (indépendant de Cashier)
        'is_subscribed',   // Flag mis à jour par sync/webhook
    ];

    protected $casts = [
        'opening_hours'              => 'array',
        'social_media_links'         => 'array',
        'social_links'               => 'array',
        'stripe_onboarding_complete' => 'boolean',
        'is_subscribed'              => 'boolean',
        'is_active'                  => 'boolean',
        'max_artists'                => 'integer',
        'joined_at'                  => 'datetime',
        'trial_ends_at'              => 'datetime',
    ];

    // ═══ RELATIONS ═══

    /**
     * Le propriétaire du studio (User)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Alias owner() pour compatibilité avec le prompt
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Tous les StudioArtist liés au studio
     */
    public function studioArtists()
    {
        return $this->hasMany(StudioArtist::class);
    }

    /**
     * Alias pour compatibilité avec le code existant
     */
    public function artists()
    {
        return $this->hasMany(StudioArtist::class);
    }

    /**
     * Studio subscriptions relationship
     */
    public function studioSubscriptions()
    {
        return $this->hasMany(StudioSubscription::class);
    }

    public function activeArtists()
    {
        return $this->artists()->where('is_active', true);
    }

    /**
     * Retourne tous les Users artistes rattachés au studio
     */
    public function artistUsers()
    {
        return $this->hasManyThrough(
            User::class,
            StudioArtist::class,
            'studio_id',  // FK sur studio_artists
            'id',         // FK sur users
            'id',         // PK de studios
            'user_id'     // FK sur studio_artists vers users
        );
    }

    /**
     * Retourne les profils Tattooer rattachés au studio
     */
    public function tattooers()
    {
        $userIds = $this->studioArtists()->pluck('user_id');
        return Tattooer::whereIn('user_id', $userIds);
    }

    /**
     * Retourne les profils Piercer rattachés au studio
     */
    public function piercers()
    {
        $userIds = $this->studioArtists()->pluck('user_id');
        return Piercer::whereIn('user_id', $userIds);
    }

    // ═══ RATINGS ═══

    /**
     * Note moyenne globale : agrégation des avis de tous les artistes du studio.
     */
    public function getAverageRatingAttribute(): ?float
    {
        $tattooerIds = $this->tattooers()->pluck('id');
        $piercerIds  = $this->piercers()->pluck('id');

        if ($tattooerIds->isEmpty() && $piercerIds->isEmpty()) {
            return null;
        }

        $avg = \App\Models\Review::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('reviewable_type', \App\Models\Tattooer::class)
                   ->whereIn('reviewable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('reviewable_type', \App\Models\Piercer::class)
                   ->whereIn('reviewable_id', $piercerIds);
            });
        })->where('is_visible', true)->avg('rating');

        return $avg ? round((float) $avg, 1) : null;
    }

    /**
     * Nombre total d'avis de tous les artistes du studio.
     */
    public function getTotalReviewsAttribute(): int
    {
        $tattooerIds = $this->tattooers()->pluck('id');
        $piercerIds  = $this->piercers()->pluck('id');

        if ($tattooerIds->isEmpty() && $piercerIds->isEmpty()) {
            return 0;
        }

        return \App\Models\Review::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('reviewable_type', \App\Models\Tattooer::class)
                   ->whereIn('reviewable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('reviewable_type', \App\Models\Piercer::class)
                   ->whereIn('reviewable_id', $piercerIds);
            });
        })->where('is_visible', true)->count();
    }

    // ═══ HELPERS ═══

    public function stripeEmail(): ?string
    {
        return $this->email ?? $this->user?->email;
    }

    public function isStudioManaged(): bool
    {
        return $this->payment_mode === 'studio_managed';
    }

    public function isArtistDirect(): bool
    {
        return $this->payment_mode === 'artist_direct';
    }

    /**
     * Nombre d'artistes actuellement actifs
     */
    public function artistCount(): int
    {
        return $this->studioArtists()->where('is_active', true)->count();
    }

    /**
     * Nombre d'artistes inclus dans l'offre (1)
     */
    public function includedArtists(): int
    {
        return 1;
    }

    /**
     * Nombre d'artistes supplémentaires payants
     */
    public function paidArtistCount(): int
    {
        return max(0, $this->artistCount() - $this->includedArtists());
    }

    /**
     * Coût mensuel total
     * 59.99€ base + 24.99€ × artistes supplémentaires
     */
    public function monthlyPrice(): float
    {
        return 59.99 + ($this->paidArtistCount() * 24.99);
    }

    /**
     * Peut ajouter un artiste ?
     * - En trial : max 1 artiste (l'inclus)
     * - Trial expiré sans abonnement : aucun ajout
     * - Avec abonnement actif : selon max_artists (null = illimité)
     */
    public function canAddArtist(): bool
    {
        $currentCount = $this->artistCount();

        // En période d'essai sans abonnement : max 1 artiste
        if ($this->onTrial() && !$this->hasActiveSubscription()) {
            return $currentCount < 1;
        }

        // Trial expiré sans abonnement : aucun ajout
        if ($this->trialExpired()) {
            return false;
        }

        // Avec abonnement actif : vérifier la limite contractuelle
        if ($this->max_artists !== null) {
            return $currentCount < $this->max_artists;
        }

        return true; // Abonnement actif, pas de limite
    }

    /**
     * Le studio doit-il souscrire pour ajouter un artiste ?
     * True si trial avec déjà 1 artiste, ou trial expiré.
     */
    public function needsSubscriptionForNewArtist(): bool
    {
        if ($this->hasActiveSubscription()) return false;

        // Trial avec déjà 1 artiste
        if ($this->onTrial() && $this->artistCount() >= 1) return true;

        // Trial expiré
        if ($this->trialExpired()) return true;

        return false;
    }

    public function getProfileUrl(): string
    {
        return route('studio.public.show', $this->slug);
    }

    // ═══ CASHIER DÉLÉGATION → USER ═══
    // Le trait Billable est sur User, pas sur Studio.
    // Ces méthodes délèguent vers $this->user pour compatibilité.

    public function subscribed(string $type = 'default'): bool
    {
        return $this->user?->subscribed($type) ?? false;
    }

    public function subscription(string $type = 'default')
    {
        return $this->user?->subscription($type);
    }

    public function hasStripeId(): bool
    {
        return $this->user?->hasStripeId() ?? false;
    }

    public function stripeId(): ?string
    {
        return $this->user?->stripe_id;
    }

    public function createOrGetStripeCustomer(array $options = [])
    {
        return $this->user?->createOrGetStripeCustomer($options);
    }

    // ═══ TRIAL ═══

    public function onTrial(): bool
    {
        return $this->trial_ends_at !== null && $this->trial_ends_at->isFuture();
    }

    public function trialExpired(): bool
    {
        if ($this->trial_ends_at === null) return false;
        return $this->trial_ends_at->isPast() && !$this->hasActiveSubscription();
    }

    public function trialDaysLeft(): int
    {
        if (!$this->trial_ends_at || $this->trial_ends_at->isPast()) return 0;
        return (int) now()->diffInDays($this->trial_ends_at, false);
    }

    public function hasActiveSubscription(): bool
    {
        // Source de vérité 1 : Cashier via le User propriétaire
        try {
            if ($this->user && $this->user->subscribed('default')) {
                return true;
            }
        } catch (\Exception) {
            // Fallback si Cashier échoue (ex: erreur SQL)
        }

        // Source de vérité 2 : flag mis à jour par webhook/sync
        if ($this->is_subscribed) {
            return true;
        }

        return false;
    }

    public function canOperate(): bool
    {
        return $this->onTrial() || $this->hasActiveSubscription();
    }

    public function isReadOnly(): bool
    {
        return !$this->canOperate();
    }

    // ═══ ONBOARDING ═══

    public function getOnboardingChecklist(): array
    {
        return [
            [
                'key'   => 'logo',
                'label' => 'Ajouter le logo du studio',
                'done'  => $this->getFirstMediaUrl('logo') !== '',
                'icon'  => '🖼️',
            ],
            [
                'key'   => 'artist',
                'label' => 'Ajouter au moins 1 artiste',
                'done'  => $this->studioArtists()->where('is_active', true)->exists(),
                'icon'  => '👤',
            ],
            [
                'key'   => 'payment',
                'label' => 'Configurer le mode de paiement',
                'done'  => $this->payment_mode !== null,
                'icon'  => '💳',
            ],
            [
                'key'   => 'profile',
                'label' => 'Personnaliser la fiche studio',
                'done'  => !empty($this->description) && !empty($this->address),
                'icon'  => '📝',
            ],
            [
                'key'   => 'booking',
                'label' => 'Recevoir une première demande',
                'done'  => $this->hasReceivedBookingRequest(),
                'icon'  => '📋',
            ],
        ];
    }

    public function onboardingProgress(): int
    {
        $checklist = $this->getOnboardingChecklist();
        $done = collect($checklist)->where('done', true)->count();
        return (int) round(($done / count($checklist)) * 100);
    }

    public function onboardingComplete(): bool
    {
        return $this->onboardingProgress() === 100;
    }

    public function hasReceivedBookingRequest(): bool
    {
        $artistUserIds = $this->studioArtists()->where('is_active', true)->pluck('user_id')->filter();
        if ($artistUserIds->isEmpty()) return false;

        $tattooerIds = \App\Models\Tattooer::whereIn('user_id', $artistUserIds)->pluck('id');
        $piercerIds  = \App\Models\Piercer::whereIn('user_id', $artistUserIds)->pluck('id');

        return \App\Models\BookingRequest::where(function ($q) use ($tattooerIds, $piercerIds) {
            $q->where(function ($q2) use ($tattooerIds) {
                $q2->where('bookable_type', 'App\\Models\\Tattooer')->whereIn('bookable_id', $tattooerIds);
            })->orWhere(function ($q2) use ($piercerIds) {
                $q2->where('bookable_type', 'App\\Models\\Piercer')->whereIn('bookable_id', $piercerIds);
            });
        })->exists();
    }

    // ═══ MEDIA ═══

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('photos'); // Photos du salon
    }
}

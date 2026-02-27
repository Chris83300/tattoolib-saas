<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Studio extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Billable;

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
        'payment_mode',        // 'artist_direct' ou 'studio_managed' (colonne DB existante)
        'stripe_account_id',   // Stripe Connect du studio
        'stripe_onboarding_complete',
        'max_artists',         // Limite contractuelle (null = illimité)
        'is_active',
        'opening_hours',       // JSON : {"monday": {"open": "09:00", "close": "19:00"}, ...}
        'social_media_links',  // JSON : {"instagram": "...", "facebook": "...", ...}
        'social_links',        // Alias pour compatibilité prompt
        // Cashier
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];

    protected $casts = [
        'opening_hours'              => 'array',
        'social_media_links'         => 'array',
        'social_links'               => 'array',
        'stripe_onboarding_complete' => 'boolean',
        'is_active'                  => 'boolean',
        'max_artists'                => 'integer',
        'joined_at'                  => 'datetime',
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
     * 79.99€ base + 39.99€ × artistes supplémentaires
     */
    public function monthlyPrice(): float
    {
        return 79.99 + ($this->paidArtistCount() * 39.99);
    }

    /**
     * Peut ajouter un artiste ?
     */
    public function canAddArtist(): bool
    {
        if ($this->max_artists === null) return true;
        return $this->artistCount() < $this->max_artists;
    }

    public function getProfileUrl(): string
    {
        return route('studio.public.show', $this->slug);
    }

    // ═══ MEDIA ═══

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('photos'); // Photos du salon
    }
}

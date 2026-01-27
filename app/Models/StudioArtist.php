<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Traits\BookableArtist;
use App\Traits\HasSubscription;
use App\Traits\HasCompliance;
use App\Traits\HasStripeConnect;

class StudioArtist extends Model
{
    use HasFactory, SoftDeletes, BookableArtist, HasSubscription, HasCompliance, HasStripeConnect;

    protected $fillable = [
        'studio_id', 'user_id', 'artist_name', 'slug', 'bio',
        'specialties', 'stripe_connect_account_id', 'status',
        'is_active', 'joined_at', 'left_at', 'working_schedule',
        'total_appointments', 'total_revenue',
        'credentials_managed_by_studio', 'notes',
        // Stripe Connect
        'stripe_connect_status',
        'stripe_connect_activated_at',
        'stripe_connect_last_transaction_at',
        'stripe_connect_deactivated_at',
        'has_accepted_payment_terms',
        'payment_terms_accepted_at',
        // Conformité réglementaire
        'is_decision_maker',
        'compliance_status',
        'last_compliance_check_at',
    ];

    protected $casts = [
        'specialties' => 'array',
        'working_schedule' => 'array',
        'is_active' => 'boolean',
        'credentials_managed_by_studio' => 'boolean',
        'joined_at' => 'date',
        'left_at' => 'date',
        'total_revenue' => 'decimal:2',
        'stripe_connect_activated_at' => 'datetime',
        'stripe_connect_last_transaction_at' => 'datetime',
        'stripe_connect_deactivated_at' => 'datetime',
        'has_accepted_payment_terms' => 'boolean',
        'payment_terms_accepted_at' => 'datetime',
        'is_decision_maker' => 'boolean',
        'last_compliance_check_at' => 'datetime',
    ];

    // Relations
    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relations polymorphic - IDENTIQUES à Tattooer
    public function bookingRequests(): MorphMany
    {
        return $this->morphMany(BookingRequest::class, 'bookable');
    }

    public function appointments(): MorphMany
    {
        return $this->morphMany(Appointment::class, 'bookable');
    }

    public function availabilities(): MorphMany
    {
        return $this->morphMany(Availability::class, 'owner');
    }

    public function workingHours(): MorphMany
    {
        return $this->morphMany(WorkingHour::class, 'owner');
    }

    // Helpers : Réutiliser logique Tattooer
    public function getPublicProfileAttribute()
    {
        return [
            'name' => $this->artist_name,
            'slug' => $this->slug,
            'bio' => $this->bio,
            'specialties' => $this->specialties,
            'studio' => $this->studio->name,
        ];
    }

    /**
     * SIRET hérité du Studio
     */
    public function getSiretAttribute(): ?string
    {
        return $this->studio?->siret;
    }

    public function getSiretVerifiedAttribute(): bool
    {
        return $this->studio?->siret_verified ?? false;
    }
}

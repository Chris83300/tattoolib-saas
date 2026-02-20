<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

class Piercer extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, HasSubscription, BookableArtist, HasCompliance, HasStripeConnect, HasWorkingHours, HandlesMedia, CalculatesStats;

    protected static function booted()
    {
        parent::booted();

        static::creating(function ($piercer) {
            if (empty($piercer->slug)) {
                $piercer->slug = Str::slug($piercer->user->name . '-' . $piercer->id);
            }
        });

        static::updating(function ($piercer) {
            if (empty($piercer->slug)) {
                $piercer->slug = Str::slug($piercer->user->name . '-' . $piercer->id);
            }
        });
    }

    // ===== FILAMENT SYNC METHODS =====

    public function bookingRequests(): MorphMany
    {
        return $this->morphMany(BookingRequest::class, 'bookable');
    }

    // ===== RELATIONS =====

    public function studioArtist()
    {
        return $this->hasOne(StudioArtist::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(Availability::class);
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}

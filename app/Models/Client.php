<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'birth_date',
        'email',
        'no_show_count',
        'is_blacklisted',
        'blacklist_reason',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_blacklisted' => 'boolean',
        'no_show_count' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function conversations()
    {
        return $this->morphMany(Conversation::class, 'participant');
    }

    // Spatie Media Library
    public function registerMediaCollections(): void
    {
        // Avatar
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl('/images/default-avatar.png')
            ->useFallbackPath(public_path('/images/default-avatar.png'));
    }

    // Scopes
    public function scopeNotBlacklisted($query)
    {
        return $query->where('is_blacklisted', false);
    }

    public function scopeWithNoShowCount($query, $count = 1)
    {
        return $query->where('no_show_count', '>=', $count);
    }
}

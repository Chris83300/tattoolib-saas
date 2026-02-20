<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'client_id',
        'rating',
        'comment',
        'is_visible',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_visible' => 'boolean',
    ];

    /**
     * Get the parent reviewable model.
     */
    public function reviewable()
    {
        return $this->morphTo();
    }

    /**
     * Get the tattooer from the reviewable (if it's a BookingRequest).
     */
    public function getTattooerAttribute()
    {
        if ($this->reviewable_type === 'App\Models\BookingRequest') {
            return $this->reviewable->bookable;
        }
        return null;
    }

    /**
     * Get the client who wrote the review.
     */
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    /**
     * Get the tattooer who is being reviewed.
     */
    public function tattooer()
    {
        return $this->belongsTo(User::class, 'tattooer_id');
    }

    /**
     * Get the booking request.
     */
    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class);
    }
}

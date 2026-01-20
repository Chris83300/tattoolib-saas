<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_request_id',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'amount',
        'currency',
        'status',
        'payment_type',
        'paid_at',
        'failure_reason',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    // Relations
    public function bookingRequest()
    {
        return $this->belongsTo(BookingRequest::class);
    }

    // Helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSucceeded(): bool
    {
        return $this->status === 'succeeded';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}

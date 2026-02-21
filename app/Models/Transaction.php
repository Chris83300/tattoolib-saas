<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id',
        'stripe_payment_intent_id',
        'stripe_charge_id',
        'client_id',
        'artist_id',
        'artist_type', // 'tattooer' ou 'piercer'
        'amount',
        'commission_amount', // Commission plateforme 7%
        'net_amount', // Montant pour l'artiste
        'currency',
        'status',
        'payment_type', // 'deposit', 'full_payment'
        'refund_status', // 'none', 'partial', 'full'
        'refund_amount',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function artist()
    {
        if ($this->artist_type === 'tattooer') {
            return $this->belongsTo(Tattooer::class, 'artist_id');
        } elseif ($this->artist_type === 'piercer') {
            return $this->belongsTo(Piercer::class, 'artist_id');
        }
        return null;
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class, 'payment_id', 'payment_id');
    }

    public function isSucceeded(): bool
    {
        return $this->status === 'succeeded';
    }

    public function isRefunded(): bool
    {
        return $this->refund_status !== 'none';
    }

    public function isPartiallyRefunded(): bool
    {
        return $this->refund_status === 'partial';
    }

    public function isFullyRefunded(): bool
    {
        return $this->refund_status === 'full';
    }

    public function scopeForPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('processed_at', [$startDate, $endDate]);
    }

    public function scopeByArtistType($query, $type)
    {
        return $query->where('artist_type', $type);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

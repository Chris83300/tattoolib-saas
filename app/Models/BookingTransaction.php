<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_request_id',
        'user_id', // le payeur (client)
        'type', // 'deposit', 'final_payment', 'refund', 'commission'
        'amount',
        'currency',
        'status', // 'pending', 'completed', 'failed', 'refunded'
        'payment_method', // 'stripe', 'cash', etc.
        'stripe_payment_intent_id',
        'stripe_session_id',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    // ===== CONSTANTES =====

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_FINAL_PAYMENT = 'final_payment';
    const TYPE_REFUND = 'refund';
    const TYPE_COMMISSION = 'commission';

    const TYPES = [
        self::TYPE_DEPOSIT => 'Acompte',
        self::TYPE_FINAL_PAYMENT => 'Paiement final',
        self::TYPE_REFUND => 'Remboursement',
        self::TYPE_COMMISSION => 'Commission',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_REFUNDED = 'refunded';

    const STATUSES = [
        self::STATUS_PENDING => 'En attente',
        self::STATUS_COMPLETED => 'Complété',
        self::STATUS_FAILED => 'Échoué',
        self::STATUS_REFUNDED => 'Remboursé',
    ];

    // ===== RELATIONS =====

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    public function scopeDeposits($query)
    {
        return $query->where('type', self::TYPE_DEPOSIT);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', self::STATUS_FAILED);
    }

    public function scopeRefunds($query)
    {
        return $query->where('type', self::TYPE_REFUND);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si la transaction est un acompte
     */
    public function isDeposit(): bool
    {
        return $this->type === self::TYPE_DEPOSIT;
    }

    /**
     * Vérifie si la transaction est complétée
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Vérifie si la transaction a échoué
     */
    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    /**
     * Marque comme complétée
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
        ]);
    }

    /**
     * Marque comme échouée
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
        ]);
    }

    /**
     * Crée une transaction d'acompte
     */
    public static function createDeposit(BookingRequest $bookingRequest, string $sessionId, string $paymentIntentId): self
    {
        return self::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $bookingRequest->client->user_id,
            'type' => self::TYPE_DEPOSIT,
            'amount' => $bookingRequest->total_deposit_amount,
            'currency' => 'eur',
            'status' => self::STATUS_COMPLETED,
            'payment_method' => 'stripe',
            'stripe_session_id' => $sessionId,
            'stripe_payment_intent_id' => $paymentIntentId,
            'metadata' => [
                'booking_request_id' => $bookingRequest->id,
                'client_id' => $bookingRequest->client_id,
                'tattooer_id' => $bookingRequest->bookable_id,
            ],
        ]);
    }

    /**
     * Crée une transaction de remboursement
     */
    public static function createRefund(BookingRequest $bookingRequest, float $amount, string $reason): self
    {
        return self::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $bookingRequest->client->user_id,
            'type' => self::TYPE_REFUND,
            'amount' => $amount,
            'currency' => 'eur',
            'status' => self::STATUS_COMPLETED,
            'payment_method' => 'stripe',
            'metadata' => [
                'reason' => $reason,
                'original_deposit_amount' => $bookingRequest->total_deposit_amount,
            ],
        ]);
    }
}

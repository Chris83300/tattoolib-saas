<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountingTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        // Relations
        'booking_request_id',
        'user_id',

        // Transaction details
        'type', // 'deposit', 'final_payment', 'refund', 'commission', 'surcharge'
        'amount',
        'currency',
        'status', // 'pending', 'completed', 'failed', 'refunded'
        'payment_method', // 'stripe', 'cash', 'bank_transfer', etc.

        // Stripe integration
        'stripe_payment_intent_id',
        'stripe_session_id',
        'stripe_charge_id',
        'receipt_url',

        // Additional data
        'metadata',
        'description',
        'processed_at',

        // Legacy fields (kept for compatibility)
        'studio_id',
        'reference',
        'category',
        'notes',
        'transaction_date',
        'due_date',
        'paid_date',
        'appointment_id',
        'client_id',
        'purchase_order_id',
        'tax_rate',
        'tax_amount',
        'attachments',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'amount_with_tax' => 'decimal:2',
        'transaction_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'attachments' => 'array',
        'metadata' => 'array',
        'processed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Auto-calcul TVA selon pays tatoueur
            if ($transaction->tax_rate === null) {
                $transaction->tax_rate = 0.20; // TVA France 20%
            }

            // Auto-calcul montant avec taxe
            if ($transaction->tax_amount === null) {
                $transaction->tax_amount = $transaction->amount * $transaction->tax_rate;
            }

            if ($transaction->amount_with_tax === null) {
                $transaction->amount_with_tax = $transaction->amount + $transaction->tax_amount;
            }
        });
    }

    // ===========================================
    // RELATIONS
    // ===========================================

    /**
     * Relation avec la booking request
     */
    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    /**
     * Relation avec l'utilisateur (payeur)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le client (legacy)
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec le studio (legacy)
     */
    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    /**
     * Relation avec l'appointment (legacy)
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    // ===========================================
    // SCOPES
    // ===========================================

    /**
     * Transactions de type acompte
     */
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    /**
     * Transactions de type paiement final
     */
    public function scopeFinalPayments($query)
    {
        return $query->where('type', 'final_payment');
    }

    /**
     * Transactions de type remboursement
     */
    public function scopeRefunds($query)
    {
        return $query->where('type', 'refund');
    }

    /**
     * Transactions complétées
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Transactions échouées
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Transactions en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Transactions Stripe
     */
    public function scopeStripe($query)
    {
        return $query->where('payment_method', 'stripe');
    }

    /**
     * Transactions pour un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ===========================================
    // MÉTHODES
    // ===========================================

    /**
     * Vérifier si la transaction est un acompte
     */
    public function isDeposit(): bool
    {
        return $this->type === 'deposit';
    }

    /**
     * Vérifier si la transaction est un remboursement
     */
    public function isRefund(): bool
    {
        return $this->type === 'refund';
    }

    /**
     * Vérifier si la transaction est complétée
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Vérifier si la transaction a échoué
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Obtenir le montant total (avec taxe)
     */
    public function getTotalAmount(): float
    {
        return $this->amount_with_tax ?? ($this->amount + ($this->tax_amount ?? 0));
    }

    /**
     * Obtenir le label du type de transaction
     */
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'deposit' => 'Acompte',
            'final_payment' => 'Paiement final',
            'refund' => 'Remboursement',
            'commission' => 'Commission',
            'surcharge' => 'Supplément',
            default => ucfirst($this->type),
        };
    }

    /**
     * Obtenir le label du statut
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'completed' => 'Complétée',
            'failed' => 'Échouée',
            'refunded' => 'Remboursée',
            default => ucfirst($this->status),
        };
    }

    /**
     * Obtenir la couleur du statut pour l'UI
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'completed' => 'green',
            'failed' => 'red',
            'refunded' => 'orange',
            default => 'gray',
        };
    }

    /**
     * Marquer comme complétée
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Marquer comme échouée
     */
    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Marquer comme remboursée
     */
    public function markAsRefunded(): void
    {
        $this->update([
            'status' => 'refunded',
            'processed_at' => now(),
        ]);
    }

    /**
     * Obtenir l'URL du reçu
     */
    public function getReceiptUrl(): ?string
    {
        return $this->receipt_url ?? $this->metadata['receipt_url'] ?? null;
    }

    /**
     * Vérifier si la transaction a un reçu
     */
    public function hasReceipt(): bool
    {
        return !empty($this->getReceiptUrl());
    }

    /**
     * Obtenir les métadonnées formatées
     */
    public function getFormattedMetadata(): array
    {
        return $this->metadata ?? [];
    }

    /**
     * Ajouter des métadonnées
     */
    public function addMetadata(array $data): void
    {
        $currentMetadata = $this->metadata ?? [];
        $newMetadata = array_merge($currentMetadata, $data);

        $this->update(['metadata' => $newMetadata]);
    }

    /**
     * Créer une transaction depuis une session Stripe
     */
    public static function createFromStripeSession($session, BookingRequest $bookingRequest, string $type = 'deposit'): self
    {
        return static::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $bookingRequest->client->user_id,
            'type' => $type,
            'amount' => $bookingRequest->total_deposit_amount,
            'currency' => 'eur',
            'status' => 'completed',
            'payment_method' => 'stripe',
            'stripe_session_id' => $session->id,
            'stripe_payment_intent_id' => $session->payment_intent,
            'description' => "Acompte pour demande #{$bookingRequest->id}",
            'processed_at' => now(),
        ]);
    }

    /**
     * Créer une transaction de remboursement
     */
    public static function createRefund(BookingRequest $bookingRequest, float $amount, string $reason = ''): self
    {
        return static::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $bookingRequest->client->user_id,
            'type' => 'refund',
            'amount' => -$amount, // Négatif pour les remboursements
            'currency' => 'eur',
            'status' => 'completed',
            'payment_method' => 'stripe',
            'description' => "Remboursement pour demande #{$bookingRequest->id}",
            'metadata' => [
                'refund_reason' => $reason,
                'original_amount' => $bookingRequest->total_deposit_amount,
            ],
            'processed_at' => now(),
        ]);
    }
}

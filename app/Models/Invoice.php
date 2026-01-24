<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'studio_id',
        'client_id',
        'appointment_id',
        'invoice_number',
        'type',
        'status',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'invoice_date',
        'due_date',
        'paid_date',
        'client_address',
        'client_email',
        'client_phone',
        'items',
        'payment_method',
        'transaction_id',
        'notes',
        'payment_terms',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'items' => 'array',
    ];

    // ===== CONSTANTES =====

    const TYPE_APPOINTMENT = 'appointment';
    const TYPE_PRODUCT = 'product';
    const TYPE_SERVICE = 'service';
    const TYPE_DEPOSIT = 'deposit';

    const TYPES = [
        self::TYPE_APPOINTMENT => 'Rendez-vous',
        self::TYPE_PRODUCT => 'Produit',
        self::TYPE_SERVICE => 'Service',
        self::TYPE_DEPOSIT => 'Acompte',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    const STATUSES = [
        self::STATUS_DRAFT => 'Brouillon',
        self::STATUS_SENT => 'Envoyée',
        self::STATUS_PAID => 'Payée',
        self::STATUS_OVERDUE => 'En retard',
        self::STATUS_CANCELLED => 'Annulée',
    ];

    // ===== RELATIONS =====

    public function tattooer(): BelongsTo
    {
        return $this->belongsTo(Tattooer::class);
    }

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    // ===== SCOPES =====

    public function scopeForTattooer($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [self::STATUS_DRAFT, self::STATUS_SENT]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
            ->orWhere(function ($q) {
                $q->whereIn('status', [self::STATUS_DRAFT, self::STATUS_SENT])
                  ->where('due_date', '<', now());
            });
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Génère un numéro de facture unique
     */
    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $year = now()->format('Y');
        $month = now()->format('m');
        $sequence = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$year}{$month}-{$sequence}";
    }

    /**
     * Calcule les montants
     */
    public function calculateAmounts(): void
    {
        $this->tax_amount = $this->subtotal * ($this->tax_rate / 100);
        $this->total_amount = $this->subtotal + $this->tax_amount;
        $this->remaining_amount = $this->total_amount - $this->paid_amount;
    }

    /**
     * Marque comme payée
     */
    public function markAsPaid(string $paymentMethod = null): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_date' => now(),
            'payment_method' => $paymentMethod,
            'paid_amount' => $this->total_amount,
            'remaining_amount' => 0,
        ]);
    }

    /**
     * Vérifie si la facture est en retard
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE ||
               ($this->due_date && $this->due_date->isPast() && !$this->isPaid());
    }

    /**
     * Vérifie si la facture est payée
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Crée une facture depuis un rendez-vous
     */
    public static function createFromAppointment(Appointment $appointment): self
    {
        $items = [
            [
                'description' => "Tatouage - {$appointment->bookingRequest->tattoo_size} sur {$appointment->bookingRequest->body_zone}",
                'quantity' => 1,
                'unit_price' => $appointment->total_price,
                'total' => $appointment->total_price,
            ]
        ];

        return self::create([
            'user_id' => $appointment->user_id,
            'client_id' => $appointment->client_id,
            'appointment_id' => $appointment->id,
            'invoice_number' => self::generateInvoiceNumber(),
            'type' => self::TYPE_APPOINTMENT,
            'status' => self::STATUS_SENT,
            'subtotal' => $appointment->total_price,
            'tax_rate' => 20,
            'invoice_date' => $appointment->start_time->toDateString(),
            'due_date' => $appointment->start_time->addDays(30)->toDateString(),
            'items' => $items,
        ]);
    }
}

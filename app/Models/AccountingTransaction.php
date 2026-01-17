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
        'tattooer_id',
        'studio_id',
        'reference',
        'type',
        'category',
        'amount',
        'currency',
        'description',
        'notes',
        'transaction_date',
        'due_date',
        'paid_date',
        'status',
        'payment_method',
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
    ];
    protected static function booted()
    {
        static::creating(function ($transaction) {
            // Auto-calcul TVA selon pays tatoueur
            if (!$transaction->tax_rate) {
                $transaction->tax_rate = $transaction->tattooer->getTaxRate();
            }

            if (!$transaction->tax_amount) {
                $transaction->tax_amount = $transaction->amount * ($transaction->tax_rate / 100);
            }
        });
    }

    // ===== CONSTANTES =====

    const TYPE_INCOME = 'income';
    const TYPE_EXPENSE = 'expense';
    const TYPE_TAX_PAYMENT = 'tax_payment';
    const TYPE_TRANSFER = 'transfer';

    const TYPES = [
        self::TYPE_INCOME => 'Revenu',
        self::TYPE_EXPENSE => 'Dépense',
        self::TYPE_TAX_PAYMENT => 'Paiement taxes',
        self::TYPE_TRANSFER => 'Transfert',
    ];

    const CATEGORY_APPOINTMENT = 'appointment';
    const CATEGORY_PRODUCT_SALE = 'product_sale';
    const CATEGORY_EQUIPMENT = 'equipment';
    const CATEGORY_RENT = 'rent';
    const CATEGORY_UTILITY = 'utility';
    const CATEGORY_MARKETING = 'marketing';
    const CATEGORY_TAX = 'tax';
    const CATEGORY_OTHER = 'other';

    const CATEGORIES = [
        self::CATEGORY_APPOINTMENT => 'Rendez-vous',
        self::CATEGORY_PRODUCT_SALE => 'Vente produits',
        self::CATEGORY_EQUIPMENT => 'Équipement',
        self::CATEGORY_RENT => 'Loyer',
        self::CATEGORY_UTILITY => 'Charges',
        self::CATEGORY_MARKETING => 'Marketing',
        self::CATEGORY_TAX => 'Taxes',
        self::CATEGORY_OTHER => 'Autre',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_CANCELLED = 'cancelled';

    const STATUSES = [
        self::STATUS_DRAFT => 'Brouillon',
        self::STATUS_PENDING => 'En attente',
        self::STATUS_PAID => 'Payé',
        self::STATUS_OVERDUE => 'En retard',
        self::STATUS_CANCELLED => 'Annulé',
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

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    // ===== SCOPES =====

    public function scopeIncome($query)
    {
        return $query->where('type', self::TYPE_INCOME);
    }

    public function scopeExpense($query)
    {
        return $query->where('type', self::TYPE_EXPENSE);
    }

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('tattooer_id', $tattooerId);
    }

    public function scopeForStudio($query, int $studioId)
    {
        return $query->where('studio_id', $studioId);
    }

    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('transaction_date', [$startDate, $endDate]);
    }

    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [self::STATUS_DRAFT, self::STATUS_PENDING]);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
            ->orWhere(function ($q) {
                $q->whereIn('status', [self::STATUS_DRAFT, self::STATUS_PENDING])
                  ->where('due_date', '<', now());
            });
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si la transaction est un revenu
     */
    public function isIncome(): bool
    {
        return $this->type === self::TYPE_INCOME;
    }

    /**
     * Vérifie si la transaction est une dépense
     */
    public function isExpense(): bool
    {
        return $this->type === self::TYPE_EXPENSE;
    }

    /**
     * Vérifie si la transaction est payée
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Vérifie si la transaction est en retard
     */
    public function isOverdue(): bool
    {
        return $this->status === self::STATUS_OVERDUE ||
               ($this->due_date && $this->due_date->isPast() && !$this->isPaid());
    }

    /**
     * Marque comme payé
     */
    public function markAsPaid(string $paymentMethod = null): void
    {
        $this->update([
            'status' => self::STATUS_PAID,
            'paid_date' => now(),
            'payment_method' => $paymentMethod,
        ]);
    }

    /**
     * Génère une référence unique
     */
    public static function generateReference(string $type, string $category): string
    {
        $prefix = '';

        if ($type === self::TYPE_INCOME) {
            $prefix = 'INC';
        } elseif ($type === self::TYPE_EXPENSE) {
            $prefix = 'EXP';
        } elseif ($type === self::TYPE_TAX_PAYMENT) {
            $prefix = 'TAX';
        }

        $categoryCode = strtoupper(substr($category, 0, 3));
        $date = now()->format('Ymd');
        $sequence = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$categoryCode}-{$date}-{$sequence}";
    }

    /**
     * Crée une transaction depuis un rendez-vous
     */
    public static function createFromAppointment(Appointment $appointment): self
    {
        return self::create([
            'tattooer_id' => $appointment->tattooer_id,
            'client_id' => $appointment->client_id,
            'appointment_id' => $appointment->id,
            'reference' => self::generateReference(self::TYPE_INCOME, self::CATEGORY_APPOINTMENT),
            'type' => self::TYPE_INCOME,
            'category' => self::CATEGORY_APPOINTMENT,
            'amount' => $appointment->total_price,
            'description' => "Tatouage - {$appointment->bookingRequest->tattoo_size} sur {$appointment->bookingRequest->body_zone}",
            'transaction_date' => $appointment->start_time->toDateString(),
            'status' => self::STATUS_PAID,
            'payment_method' => 'stripe',
            'tax_rate' => 20,
            'tax_amount' => $appointment->total_price * 0.20,
        ]);
    }

    /**
     * Calcule le solde (revenus - dépenses)
     */
    public static function getBalance(int $tattooerId = null, int $studioId = null, \Carbon\Carbon $startDate = null, \Carbon\Carbon $endDate = null): array
    {
        $query = self::query();

        if ($tattooerId) {
            $query->where('tattooer_id', $tattooerId);
        }

        if ($studioId) {
            $query->where('studio_id', $studioId);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('transaction_date', [$startDate, $endDate]);
        }

        $income = $query->income()->sum('amount');
        $expense = $query->expense()->sum('amount');

        return [
            'total_income' => $income,
            'total_expense' => $expense,
            'net_balance' => $income - $expense,
            'profit_margin' => $income > 0 ? (($income - $expense) / $income) * 100 : 0,
        ];
    }
}

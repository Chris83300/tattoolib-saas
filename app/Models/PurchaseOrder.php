<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'studio_id',
        'order_number',
        'supplier',
        'status',
        'order_date',
        'expected_delivery_date',
        'received_date',
        'total_amount',
        'tax_amount',
        'shipping_cost',
        'invoice_number',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_delivery_date' => 'date',
        'received_date' => 'date',
        'total_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
    ];

    // ===== CONSTANTES =====

    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_RECEIVED = 'received';
    const STATUS_CANCELLED = 'cancelled';

    const STATUSES = [
        self::STATUS_DRAFT => 'Brouillon',
        self::STATUS_SENT => 'Envoyée',
        self::STATUS_RECEIVED => 'Reçue',
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

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // ===== SCOPES =====

    public function scopeForTattooer($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForStudio($query, int $studioId)
    {
        return $query->where('studio_id', $studioId);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_DRAFT, self::STATUS_SENT]);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Génère un numéro de commande unique
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'PO';
        $date = now()->format('Ymd');
        $sequence = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

        return "{$prefix}-{$date}-{$sequence}";
    }

    /**
     * Calcule le montant total
     */
    public function calculateTotal(): void
    {
        $itemsTotal = $this->items()->sum('total_price');
        $this->update([
            'total_amount' => $itemsTotal + $this->shipping_cost,
        ]);
    }

    /**
     * Marque comme reçue et met à jour le stock
     */
    public function markAsReceived(): void
    {
        foreach ($this->items as $item) {
            if ($item->inventory_item) {
                $item->inventory_item->stockIn(
                    $item->quantity_received - $item->quantity_ordered,
                    'purchase',
                    "Réception commande {$this->order_number}"
                );
            }
        }

        $this->update([
            'status' => self::STATUS_RECEIVED,
            'received_date' => now(),
        ]);
    }
}

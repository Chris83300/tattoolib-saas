<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'inventory_item_id',
        'item_name',
        'sku',
        'quantity_ordered',
        'quantity_received',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity_ordered' => 'integer',
        'quantity_received' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // ===== RELATIONS =====

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Calcule le prix total
     */
    public function calculateTotal(): void
    {
        $this->update([
            'total_price' => $this->quantity_ordered * $this->unit_price,
        ]);
    }

    /**
     * Vérifie si la commande est complètement reçue
     */
    public function isFullyReceived(): bool
    {
        return $this->quantity_received >= $this->quantity_ordered;
    }

    /**
     * Calcule la quantité restante à recevoir
     */
    public function getRemainingQuantity(): int
    {
        return max(0, $this->quantity_ordered - $this->quantity_received);
    }
}

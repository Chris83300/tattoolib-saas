<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_item_id',
        'tattooer_id',
        'movement_type',
        'quantity',
        'stock_before',
        'stock_after',
        'reason',
        'notes',
        'appointment_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
    ];

    // ===== CONSTANTES =====

    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_ADJUSTMENT = 'adjustment';
    const TYPE_TRANSFER = 'transfer';

    const TYPES = [
        self::TYPE_IN => 'Entrée',
        self::TYPE_OUT => 'Sortie',
        self::TYPE_ADJUSTMENT => 'Ajustement',
        self::TYPE_TRANSFER => 'Transfert',
    ];

    const REASONS = [
        'purchase' => 'Achat',
        'sale' => 'Vente',
        'usage' => 'Utilisation',
        'damage' => 'Dégât',
        'return' => 'Retour',
        'adjustment' => 'Ajustement',
        'transfer' => 'Transfert',
        'expiry' => 'Péremption',
    ];

    // ===== RELATIONS =====

    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function tattooer(): BelongsTo
    {
        return $this->belongsTo(Tattooer::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    // ===== SCOPES =====

    public function scopeIn($query)
    {
        return $query->where('movement_type', self::TYPE_IN);
    }

    public function scopeOut($query)
    {
        return $query->where('movement_type', self::TYPE_OUT);
    }

    public function scopeAdjustment($query)
    {
        return $query->where('movement_type', self::TYPE_ADJUSTMENT);
    }

    public function scopeForItem($query, int $itemId)
    {
        return $query->where('inventory_item_id', $itemId);
    }

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('tattooer_id', $tattooerId);
    }

    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si c'est un mouvement d'entrée
     */
    public function isInMovement(): bool
    {
        return $this->movement_type === self::TYPE_IN;
    }

    /**
     * Vérifie si c'est un mouvement de sortie
     */
    public function isOutMovement(): bool
    {
        return $this->movement_type === self::TYPE_OUT;
    }

    /**
     * Calcule la valeur du mouvement
     */
    public function getValue(): float
    {
        if ($this->inventoryItem && $this->inventoryItem->unit_price) {
            return $this->quantity * $this->inventoryItem->unit_price;
        }

        return 0;
    }

    /**
     * Récupère le libellé du type de mouvement
     */
    public function getTypeLabel(): string
    {
        return self::TYPES[$this->movement_type] ?? $this->movement_type;
    }

    /**
     * Récupère le libellé de la raison
     */
    public function getReasonLabel(): string
    {
        return self::REASONS[$this->reason] ?? $this->reason;
    }
}

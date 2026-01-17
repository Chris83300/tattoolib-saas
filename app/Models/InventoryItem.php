<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tattooer_id',
        'studio_id',
        'name',
        'sku',
        'description',
        'category',
        'brand',
        'supplier',
        'current_stock',
        'min_stock_level',
        'max_stock_level',
        'unit_price',
        'unit_type',
        'color',
        'size',
        'is_vegan',
        'expiration_date',
        'needle_type',
        'needle_size',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'current_stock' => 'integer',
        'min_stock_level' => 'integer',
        'max_stock_level' => 'integer',
        'unit_price' => 'decimal:2',
        'is_vegan' => 'boolean',
        'expiration_date' => 'date',
        'is_active' => 'boolean',
    ];

    // ===== CONSTANTES =====

    const CATEGORY_INK = 'ink';
    const CATEGORY_NEEDLE = 'needle';
    const CATEGORY_EQUIPMENT = 'equipment';
    const CATEGORY_AFTERCARE = 'aftercare';
    const CATEGORY_DISPOSABLE = 'disposable';

    const CATEGORIES = [
        self::CATEGORY_INK => 'Encres',
        self::CATEGORY_NEEDLE => 'Aiguilles',
        self::CATEGORY_EQUIPMENT => 'Équipement',
        self::CATEGORY_AFTERCARE => 'Soins après-tatouage',
        self::CATEGORY_DISPOSABLE => 'Consommables jetables',
    ];

    const UNIT_TYPES = [
        'unit' => 'Unité',
        'ml' => 'Millilitre',
        'grams' => 'Gramme',
        'box' => 'Boîte',
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

    public function movements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // ===== SCOPES =====

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('tattooer_id', $tattooerId);
    }

    public function scopeForStudio($query, int $studioId)
    {
        return $query->where('studio_id', $studioId);
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('current_stock <= min_stock_level');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('current_stock', '<=', 0);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExpiringSoon($query, int $days = 30)
    {
        return $query->whereNotNull('expiration_date')
            ->where('expiration_date', '<=', now()->addDays($days));
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si le stock est bas
     */
    public function isLowStock(): bool
    {
        return $this->current_stock <= $this->min_stock_level;
    }

    /**
     * Vérifie si le produit est en rupture
     */
    public function isOutOfStock(): bool
    {
        return $this->current_stock <= 0;
    }

    /**
     * Vérifie si le produit expire bientôt
     */
    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->expiration_date &&
               $this->expiration_date->isBefore(now()->addDays($days));
    }

    /**
     * Calcule la valeur totale du stock
     */
    public function getTotalValue(): float
    {
        return $this->current_stock * $this->unit_price;
    }

    /**
     * Ajoute du mouvement de stock
     */
    public function addMovement(
        string $movementType,
        int $quantity,
        string $reason,
        string $notes = null,
        int $appointmentId = null
    ): InventoryMovement {
        $stockBefore = $this->current_stock;

        // Calculer le nouveau stock
        if ($movementType === InventoryMovement::TYPE_IN) {
            $newStock = $stockBefore + $quantity;
        } else {
            $newStock = $stockBefore - $quantity;
        }

        // Créer le mouvement
        $movement = $this->movements()->create([
            'movement_type' => $movementType,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $newStock,
            'reason' => $reason,
            'notes' => $notes,
            'appointment_id' => $appointmentId,
        ]);

        // Mettre à jour le stock
        $this->update(['current_stock' => $newStock]);

        return $movement;
    }

    /**
     * Entrée de stock (achat, retour, etc.)
     */
    public function stockIn(int $quantity, string $reason = 'purchase', string $notes = null): InventoryMovement
    {
        return $this->addMovement(InventoryMovement::TYPE_IN, $quantity, $reason, $notes);
    }

    /**
     * Sortie de stock (utilisation, casse, etc.)
     */
    public function stockOut(int $quantity, string $reason = 'usage', string $notes = null, int $appointmentId = null): InventoryMovement
    {
        if ($quantity > $this->current_stock) {
            throw new \Exception("Stock insuffisant pour {$this->name}");
        }

        return $this->addMovement(InventoryMovement::TYPE_OUT, $quantity, $reason, $notes, $appointmentId);
    }

    /**
     * Ajustement de stock
     */
    public function adjustStock(int $newQuantity, string $reason = 'adjustment', string $notes = null): InventoryMovement
    {
        $difference = $newQuantity - $this->current_stock;

        if ($difference > 0) {
            return $this->stockIn($difference, $reason, $notes);
        } elseif ($difference < 0) {
            return $this->stockOut(abs($difference), $reason, $notes);
        }

        // Pas de changement nécessaire
        return $this->movements()->create([
            'movement_type' => InventoryMovement::TYPE_ADJUSTMENT,
            'quantity' => 0,
            'stock_before' => $this->current_stock,
            'stock_after' => $newQuantity,
            'reason' => $reason,
            'notes' => $notes,
        ]);
    }

    /**
     * Génère un SKU unique
     */
    public static function generateSku(string $name, string $category): string
    {
        $prefix = strtoupper(substr($category, 0, 3));
        $namePart = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $name), 0, 6));
        $unique = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return $prefix . '-' . $namePart . '-' . $unique;
    }

    /**
     * Récupère les statistiques de stock
     */
    public function getStockStats(): array
    {
        return [
            'current_stock' => $this->current_stock,
            'min_level' => $this->min_stock_level,
            'max_level' => $this->max_stock_level,
            'is_low_stock' => $this->isLowStock(),
            'is_out_of_stock' => $this->isOutOfStock(),
            'stock_percentage' => $this->max_stock_level > 0
                ? ($this->current_stock / $this->max_stock_level) * 100
                : 0,
            'total_value' => $this->getTotalValue(),
            'is_expiring_soon' => $this->isExpiringSoon(),
            'days_until_expiry' => $this->expiration_date
                ? now()->diffInDays($this->expiration_date)
                : null,
        ];
    }
}

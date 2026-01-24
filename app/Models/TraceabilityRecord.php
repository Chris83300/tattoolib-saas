<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraceabilityRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'appointment_id',
        'client_consent_form_id',

        // Informations de procédure
        'procedure_date',
        'procedure_start_time',
        'procedure_end_time',

        // Aiguilles utilisées
        'needles_used',

        // Encres utilisées
        'inks_used',

        // Équipement stérile
        'sterile_equipment',

        // Produits de soin
        'aftercare_products',

        // Environnement
        'room_number',
        'autoclave_batch_number',
        'autoclave_test_date',

        // Photos de procédure
        'procedure_photos',
        'workstation_photos',

        // Notes et observations
        'procedure_notes',
        'client_condition_notes',
        'equipment_notes',

        // Validation
        'client_verified_photos',
        'tattooer_verified_traceability',
        'verified_at',
        'verification_notes',
    ];

    protected $casts = [
        'procedure_date' => 'date',
        'procedure_start_time' => 'datetime:H:i',
        'procedure_end_time' => 'datetime:H:i',
        'autoclave_test_date' => 'date',
        'needles_used' => 'array',
        'inks_used' => 'array',
        'sterile_equipment' => 'array',
        'aftercare_products' => 'array',
        'procedure_photos' => 'array',
        'workstation_photos' => 'array',
        'client_verified_photos' => 'boolean',
        'tattooer_verified_traceability' => 'boolean',
        'verified_at' => 'datetime',
    ];

    // ===== RELATIONS =====

    public function tattooer(): BelongsTo
    {
        return $this->belongsTo(Tattooer::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function clientConsentForm(): BelongsTo
    {
        return $this->belongsTo(ClientConsentForm::class);
    }

    // ===== SCOPES =====

    public function scopeForTattooer($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('procedure_date', [$startDate, $endDate]);
    }

    public function scopeVerified($query)
    {
        return $query->where('tattooer_verified_traceability', true);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Ajoute une aiguille utilisée
     */
    public function addNeedle(array $needleData): void
    {
        $needles = $this->needles_used ?? [];

        $needles[] = [
            'type' => $needleData['type'], // 'round_liner', 'magnum', etc.
            'size' => $needleData['size'], // '3RL', '5M1', etc.
            'quantity' => $needleData['quantity'] ?? 1,
            'lot_number' => $needleData['lot_number'],
            'expiration_date' => $needleData['expiration_date'],
            'photo_url' => $needleData['photo_url'] ?? null,
            'added_at' => now()->toISOString(),
        ];

        $this->update(['needles_used' => $needles]);
    }

    /**
     * Ajoute une encre utilisée
     */
    public function addInk(array $inkData): void
    {
        $inks = $this->inks_used ?? [];

        $inks[] = [
            'brand' => $inkData['brand'],
            'color' => $inkData['color'],
            'color_code' => $inkData['color_code'] ?? null,
            'lot_number' => $inkData['lot_number'],
            'expiration_date' => $inkData['expiration_date'],
            'quantity_ml' => $inkData['quantity_ml'],
            'photo_url' => $inkData['photo_url'] ?? null,
            'is_vegan' => $inkData['is_vegan'] ?? false,
            'added_at' => now()->toISOString(),
        ];

        $this->update(['inks_used' => $inks]);
    }

    /**
     * Ajoute un équipement stérile
     */
    public function addSterileEquipment(array $equipmentData): void
    {
        $equipment = $this->sterile_equipment ?? [];

        $equipment[] = [
            'type' => $equipmentData['type'], // 'gloves', 'tubes', 'tips', etc.
            'brand' => $equipmentData['brand'],
            'lot_number' => $equipmentData['lot_number'],
            'expiration_date' => $equipmentData['expiration_date'],
            'quantity' => $equipmentData['quantity'] ?? 1,
            'photo_url' => $equipmentData['photo_url'] ?? null,
            'added_at' => now()->toISOString(),
        ];

        $this->update(['sterile_equipment' => $equipment]);
    }

    /**
     * Ajoute un produit de soin
     */
    public function addAftercareProduct(array $productData): void
    {
        $products = $this->aftercare_products ?? [];

        $products[] = [
            'brand' => $productData['brand'],
            'product_name' => $productData['product_name'],
            'lot_number' => $productData['lot_number'],
            'expiration_date' => $productData['expiration_date'],
            'quantity' => $productData['quantity'] ?? 1,
            'photo_url' => $productData['photo_url'] ?? null,
            'added_at' => now()->toISOString(),
        ];

        $this->update(['aftercare_products' => $products]);
    }

    /**
     * Ajoute une photo de procédure
     */
    public function addProcedurePhoto(string $photoUrl, string $type = 'general'): void
    {
        $photos = $this->procedure_photos ?? [];

        $photos[] = [
            'url' => $photoUrl,
            'type' => $type, // 'before', 'during', 'after', 'work_area'
            'added_at' => now()->toISOString(),
        ];

        $this->update(['procedure_photos' => $photos]);
    }

    /**
     * Ajoute une photo de l'espace de travail
     */
    public function addWorkstationPhoto(string $photoUrl, string $type = 'general'): void
    {
        $photos = $this->workstation_photos ?? [];

        $photos[] = [
            'url' => $photoUrl,
            'type' => $type, // 'setup', 'during', 'cleanup'
            'added_at' => now()->toISOString(),
        ];

        $this->update(['workstation_photos' => $photos]);
    }

    /**
     * Calcule la durée de la procédure
     */
    public function getProcedureDuration(): int
    {
        if (!$this->procedure_start_time || !$this->procedure_end_time) {
            return 0;
        }

        $start = \Carbon\Carbon::createFromFormat('H:i', $this->procedure_start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->procedure_end_time);

        return $start->diffInMinutes($end);
    }

    /**
     * Vérifie si tous les lots sont valides
     */
    public function areLotNumbersValid(): bool
    {
        $allItems = array_merge(
            $this->needles_used ?? [],
            $this->inks_used ?? [],
            $this->sterile_equipment ?? [],
            $this->aftercare_products ?? []
        );

        foreach ($allItems as $item) {
            if (isset($item['expiration_date']) &&
                \Carbon\Carbon::parse($item['expiration_date'])->isPast()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Marque comme vérifié par le tatoueur
     */
    public function markAsVerified(string $notes = null): void
    {
        $this->update([
            'tattooer_verified_traceability' => true,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Génère un rapport de tracabilité
     */
    public function generateTraceabilityReport(): array
    {
        return [
            'procedure_info' => [
                'date' => $this->procedure_date->format('d/m/Y'),
                'start_time' => $this->procedure_start_time,
                'end_time' => $this->procedure_end_time,
                'duration_minutes' => $this->getProcedureDuration(),
                'room' => $this->room_number,
            ],
            'client_info' => [
                'name' => $this->clientConsentForm->full_name,
                'age' => $this->clientConsentForm->getAge(),
                'consent_status' => $this->clientConsentForm->status,
            ],
            'materials_used' => [
                'needles' => $this->needles_used ?? [],
                'inks' => $this->inks_used ?? [],
                'sterile_equipment' => $this->sterile_equipment ?? [],
                'aftercare_products' => $this->aftercare_products ?? [],
            ],
            'sterilization' => [
                'autoclave_batch' => $this->autoclave_batch_number,
                'autoclave_test_date' => $this->autoclave_test_date?->format('d/m/Y'),
            ],
            'verification' => [
                'tattooer_verified' => $this->tattooer_verified_traceability,
                'verified_at' => $this->verified_at?->format('d/m/Y H:i'),
                'verification_notes' => $this->verification_notes,
            ],
            'photos' => [
                'procedure' => $this->procedure_photos ?? [],
                'workstation' => $this->workstation_photos ?? [],
            ],
        ];
    }

    /**
     * Vérifie si l'enregistrement est complet
     */
    public function isComplete(): bool
    {
        return !empty($this->needles_used) &&
               !empty($this->inks_used) &&
               !empty($this->sterile_equipment) &&
               $this->tattooer_verified_traceability;
    }
}

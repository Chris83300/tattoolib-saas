<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParentalConsentForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_consent_form_id',
        'tattooer_id',

        // Informations du parent/tuteur
        'parent_full_name',
        'parent_relationship',
        'parent_id_document_type',
        'parent_id_document_number',
        'parent_id_document_expiry',
        'parent_phone',
        'parent_email',
        'parent_address',

        // Consentement parental
        'parent_consents_to_tattoo',
        'parent_understands_risks',
        'parent_will_supervise_aftercare',
        'parent_consents_to_emergency_treatment',

        // Documents
        'parent_id_document_photos',
        'parent_signature',
        'parent_ip_address',
        'parent_user_agent',

        // Statut
        'status',
        'signed_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'parent_id_document_expiry' => 'date',
        'parent_consents_to_tattoo' => 'boolean',
        'parent_understands_risks' => 'boolean',
        'parent_will_supervise_aftercare' => 'boolean',
        'parent_consents_to_emergency_treatment' => 'boolean',
        'parent_id_document_photos' => 'encrypted:array',
        'parent_signature' => 'encrypted:array',
        'signed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // ===== CONSTANTES =====

    const RELATIONSHIP_MOTHER = 'mother';
    const RELATIONSHIP_FATHER = 'father';
    const RELATIONSHIP_GUARDIAN = 'guardian';

    const RELATIONSHIPS = [
        self::RELATIONSHIP_MOTHER => 'Mère',
        self::RELATIONSHIP_FATHER => 'Père',
        self::RELATIONSHIP_GUARDIAN => 'Tuteur légal',
    ];

    const STATUS_DRAFT = 'draft';
    const STATUS_SIGNED = 'signed';
    const STATUS_VERIFIED = 'verified';
    const STATUS_EXPIRED = 'expired';

    const STATUSES = [
        self::STATUS_DRAFT => 'Brouillon',
        self::STATUS_SIGNED => 'Signé',
        self::STATUS_VERIFIED => 'Vérifié',
        self::STATUS_EXPIRED => 'Expiré',
    ];

    // ===== RELATIONS =====

    public function clientConsentForm(): BelongsTo
    {
        return $this->belongsTo(ClientConsentForm::class);
    }

    public function tattooer(): BelongsTo
    {
        return $this->belongsTo(Tattooer::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ===== SCOPES =====

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('tattooer_id', $tattooerId);
    }

    public function scopeSigned($query)
    {
        return $query->where('status', self::STATUS_SIGNED);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si le consentement est valide
     */
    public function isValid(): bool
    {
        // Vérifier que tous les consentements sont cochés
        if (!$this->parent_consents_to_tattoo ||
            !$this->parent_understands_risks ||
            !$this->parent_will_supervise_aftercare) {
            return false;
        }

        // Vérifier la validité du document d'identité
        if ($this->parent_id_document_expiry && $this->parent_id_document_expiry->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Marque comme signé
     */
    public function markAsSigned(): void
    {
        $this->update([
            'status' => self::STATUS_SIGNED,
            'signed_at' => now(),
            'parent_ip_address' => request()->ip(),
            'parent_user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Marque comme vérifié
     */
    public function markAsVerified(int $verifiedByUserId): void
    {
        $this->update([
            'status' => self::STATUS_VERIFIED,
            'verified_by' => $verifiedByUserId,
            'verified_at' => now(),
        ]);
    }

    /**
     * Ajoute une photo de pièce d'identité du parent
     */
    public function addParentIdDocumentPhoto(string $photoUrl, string $type = 'front'): void
    {
        $photos = $this->parent_id_document_photos ?? [];

        $photos[] = [
            'url' => $photoUrl,
            'type' => $type,
            'added_at' => now()->toISOString(),
        ];

        $this->update(['parent_id_document_photos' => $photos]);
    }

    /**
     * Ajoute la signature du parent
     */
    public function addParentSignature(array $signatureData): void
    {
        $this->update([
            'parent_signature' => [
                'signature_data' => $signatureData['signature_data'],
                'signature_date' => now()->toISOString(),
                'ip_address' => request()->ip(),
            ],
        ]);
    }

    /**
     * Génère un résumé pour affichage
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'parent_name' => $this->parent_full_name,
            'relationship' => $this->parent_relationship,
            'relationship_label' => self::RELATIONSHIPS[$this->parent_relationship] ?? $this->parent_relationship,
            'status' => $this->status,
            'signed_at' => $this->signed_at,
            'consents' => [
                'parent_consents_to_tattoo' => $this->parent_consents_to_tattoo,
                'parent_understands_risks' => $this->parent_understands_risks,
                'parent_will_supervise_aftercare' => $this->parent_will_supervise_aftercare,
                'parent_consents_to_emergency_treatment' => $this->parent_consents_to_emergency_treatment,
            ],
        ];
    }
}

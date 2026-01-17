<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClientConsentForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'tattooer_id',
        'appointment_id',

        // Informations personnelles
        'full_name',
        'birth_date',
        'id_document_type',
        'id_document_number',
        'id_document_expiry',
        'phone',
        'email',
        'address',

        // Vérification d'âge
        'is_adult',
        'consent_date',
        'consent_time',

        // Déclaration de santé
        'has_allergies',
        'allergies_details',
        'has_skin_conditions',
        'skin_conditions_details',
        'has_blood_disorders',
        'blood_disorders_details',
        'has_diabetes',
        'has_heart_conditions',
        'is_pregnant',
        'is_breastfeeding',
        'taking_medications',
        'medications_details',
        'has_recent_surgery',
        'recent_surgery_details',

        // Tatouages existants
        'has_existing_tattoos',
        'existing_tattoos_location',

        // Consentement
        'consents_to_tattoo',
        'understands_risks',
        'understands_aftercare',
        'consents_to_photos',
        'consents_to_data_processing',

        // Documents
        'id_document_photos',
        'consent_signature',
        'ip_address',
        'user_agent',

        // Statut
        'status',
        'signed_at',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'id_document_expiry' => 'date',
        'consent_date' => 'date',
        'consent_time' => 'datetime:H:i',
        'has_allergies' => 'boolean',
        'has_skin_conditions' => 'boolean',
        'has_blood_disorders' => 'boolean',
        'has_diabetes' => 'boolean',
        'has_heart_conditions' => 'boolean',
        'is_pregnant' => 'boolean',
        'is_breastfeeding' => 'boolean',
        'taking_medications' => 'boolean',
        'has_recent_surgery' => 'boolean',
        'has_existing_tattoos' => 'boolean',
        'consents_to_tattoo' => 'boolean',
        'understands_risks' => 'boolean',
        'understands_aftercare' => 'boolean',
        'consents_to_photos' => 'boolean',
        'consents_to_data_processing' => 'boolean',
        'signed_at' => 'datetime',
        'verified_at' => 'datetime',
        'allergies_details' => 'encrypted',
        'medications_details' => 'encrypted',
        'skin_conditions_details' => 'encrypted',
        'id_document_photos' => 'encrypted:array',
        'consent_signature' => 'encrypted:array',
    ];

    // ===== CONSTANTES =====

    const ID_DOCUMENT_TYPES = [
        'carte_id' => 'Carte d\'identité',
        'passeport' => 'Passeport',
        'permis' => 'Permis de conduire',
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

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tattooer(): BelongsTo
    {
        return $this->belongsTo(Tattooer::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function parentalConsent(): HasOne
    {
        return $this->hasOne(ParentalConsentForm::class);
    }

    public function traceabilityRecord(): HasOne
    {
        return $this->hasOne(TraceabilityRecord::class);
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

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeSigned($query)
    {
        return $query->where('status', self::STATUS_SIGNED);
    }

    public function scopeVerified($query)
    {
        return $query->where('status', self::STATUS_VERIFIED);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', self::STATUS_EXPIRED);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si le client est majeur
     */
    public function isAdult(): bool
    {
        return $this->birth_date->age >= 18;
    }

    /**
     * Calcule l'âge du client
     */
    public function getAge(): int
    {
        return $this->birth_date->age;
    }

    /**
     * Vérifie si un consentement parental est requis
     */
    public function requiresParentalConsent(): bool
    {
        return !$this->is_adult || $this->getAge() < 18;
    }

    /**
     * Vérifie si le formulaire est valide
     */
    public function isValid(): bool
    {
        // Vérifier que tous les consentements sont cochés
        if (!$this->consents_to_tattoo || !$this->understands_risks || !$this->understands_aftercare) {
            return false;
        }

        // Vérifier la validité du document d'identité
        if ($this->id_document_expiry && $this->id_document_expiry->isPast()) {
            return false;
        }

        // Vérifier si mineur avec consentement parental
        if ($this->requiresParentalConsent() && !$this->parentalConsent) {
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
            'consent_date' => now()->toDateString(),
            'consent_time' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Marque comme vérifié par le tatoueur
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
     * Vérifie si le formulaire est expiré (1 an)
     */
    public function isExpired(): bool
    {
        return $this->signed_at && $this->signed_at->lt(now()->subYear());
    }

    /**
     * Ajoute une photo de pièce d'identité
     */
    public function addIdDocumentPhoto(string $photoUrl, string $type = 'front'): void
    {
        $photos = $this->id_document_photos ?? [];

        $photos[] = [
            'url' => $photoUrl,
            'type' => $type, // 'front', 'back', 'selfie'
            'added_at' => now()->toISOString(),
        ];

        $this->update(['id_document_photos' => $photos]);
    }

    /**
     * Ajoute la signature numérique
     */
    public function addSignature(array $signatureData): void
    {
        $this->update([
            'consent_signature' => [
                'signature_data' => $signatureData['signature_data'], // Base64 ou URL
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
            'client_name' => $this->full_name,
            'age' => $this->getAge(),
            'is_adult' => $this->is_adult,
            'requires_parental_consent' => $this->requiresParentalConsent(),
            'has_parental_consent' => $this->parentalConsent ? true : false,
            'status' => $this->status,
            'signed_at' => $this->signed_at,
            'health_risks' => [
                'has_allergies' => $this->has_allergies,
                'has_skin_conditions' => $this->has_skin_conditions,
                'has_blood_disorders' => $this->has_blood_disorders,
                'has_diabetes' => $this->has_diabetes,
                'is_pregnant' => $this->is_pregnant,
            ],
            'consents' => [
                'consents_to_tattoo' => $this->consents_to_tattoo,
                'understands_risks' => $this->understands_risks,
                'consents_to_photos' => $this->consents_to_photos,
            ],
        ];
    }
}

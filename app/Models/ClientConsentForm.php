<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ClientConsentForm extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        // Relations
        'client_id', 'tattooer_id', 'appointment_id', 'booking_request_id',
        // Identité client
        'client_full_name', 'client_birth_date', 'client_address',
        'client_phone', 'client_email', 'client_id_type', 'client_id_number',
        // Pièce identité (gardé de v1)
        'id_document_expiry',
        // Mineur
        'is_minor', 'parent_name', 'parent_relation', 'parent_id_number',
        'parent_signature_data',
        // Acte
        'act_type', 'body_zone', 'act_description',
        // Médical SNAT
        'medical_allergies', 'medical_allergies_detail',
        'medical_anticoagulant', 'medical_diabetes', 'medical_cicatrisation',
        'medical_skin_disease', 'medical_skin_disease_detail',
        'medical_vih_hepatite', 'medical_pregnant', 'medical_roaccutane',
        'medical_cheloide', 'medical_other',
        // Confirmations SNAT
        'confirm_medical_sincere', 'confirm_risks_informed',
        'confirm_info_sheet_read', 'confirm_aftercare_received',
        'confirm_not_intoxicated', 'confirm_over_18_or_authorized',
        'confirm_rgpd',
        // Financier
        'total_price', 'deposit_amount', 'retouche_included',
        // Image
        'image_authorization',
        // Signature
        'signature_data', 'signed_at', 'signed_ip', 'signed_user_agent',
        'handwritten_mention',
        // Workflow
        'status', 'verified_by', 'verified_at',
        // Anciens champs (compatibilité)
        'full_name', 'birth_date', 'id_document_type', 'id_document_number',
        'phone', 'email', 'address', 'consent_date', 'consent_time',
        'has_allergies', 'allergies_details', 'has_skin_conditions',
        'skin_conditions_details', 'has_blood_disorders', 'blood_disorders_details',
        'has_diabetes', 'has_heart_conditions', 'is_pregnant',
        'is_breastfeeding', 'taking_medications', 'has_recent_surgery',
        'recent_surgery_details', 'has_existing_tattoos', 'existing_tattoos_location',
        'consents_to_tattoo', 'understands_risks', 'understands_aftercare',
        'consents_to_photos', 'consents_to_data_processing',
        'id_document_photos', 'consent_signature', 'ip_address', 'user_agent',
        'studio_id',
    ];

    protected $casts = [
        'client_birth_date' => 'date',
        'id_document_expiry' => 'date',
        'is_minor' => 'boolean',
        'medical_allergies' => 'boolean',
        'medical_anticoagulant' => 'boolean',
        'medical_diabetes' => 'boolean',
        'medical_cicatrisation' => 'boolean',
        'medical_skin_disease' => 'boolean',
        'medical_vih_hepatite' => 'boolean',
        'medical_pregnant' => 'boolean',
        'medical_roaccutane' => 'boolean',
        'medical_cheloide' => 'boolean',
        'confirm_medical_sincere' => 'boolean',
        'confirm_risks_informed' => 'boolean',
        'confirm_info_sheet_read' => 'boolean',
        'confirm_aftercare_received' => 'boolean',
        'confirm_not_intoxicated' => 'boolean',
        'confirm_over_18_or_authorized' => 'boolean',
        'confirm_rgpd' => 'boolean',
        'total_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'retouche_included' => 'boolean',
        'image_authorization' => 'boolean',
        'signed_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // ===== CONSTANTES =====

    // SNAT 2026 - Types de pièces d'identité
    const SNAT_ID_TYPES = [
        'cni' => 'Carte Nationale d\'Identité',
        'passeport' => 'Passeport',
        'titre_sejour' => 'Titre de Séjour',
    ];

    // SNAT 2026 - Relations parentales
    const PARENT_RELATIONS = [
        'pere' => 'Père',
        'mere' => 'Mère',
        'tuteur' => 'Tuteur légal',
    ];

    // SNAT 2026 - Types d'actes
    const ACT_TYPES = [
        'tatouage' => 'Tatouage',
        'piercing' => 'Piercing',
        'dermographie' => 'Dermographie',
        'scarification' => 'Scarification',
        'modification_corporelle' => 'Modification corporelle',
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

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
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

    public function studio(): BelongsTo
    {
        return $this->belongsTo(Studio::class);
    }

    // ===== MEDIA =====

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('parent_id')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);

        $this->addMediaCollection('client_id')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf']);
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

    /**
     * Scope : artiste indépendant voit ses formulaires, artiste studio voit ceux du studio.
     */
    public function scopeForArtisan($query, $artisan)
    {
        if ($artisan->studio_id) {
            return $query->where('studio_id', $artisan->studio_id);
        }

        return $query->where('tattooer_id', $artisan->id);
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
     * Vérifie si le formulaire est valide (SNAT 2026)
     */
    public function isValid(): bool
    {
        return $this->status === 'signed'
            || (!empty($this->signed_at) && !empty($this->signature_data));
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified' && !empty($this->verified_at);
    }

    /**
     * Le tattooer vérifie le consentement (après lecture)
     */
    public function verify(int $userId): void
    {
        $this->update([
            'status' => 'verified',
            'verified_by' => $userId,
            'verified_at' => now(),
        ]);
    }

    /**
     * Marque le formulaire comme signé (client signe)
     */
    public function markAsSigned(): void
    {
        $this->update([
            'status' => self::STATUS_SIGNED,
            'signed_at' => now(),
            'signed_ip' => request()->ip(),
            'signed_user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Vérifie si le consentement parental est requis
     */
    public function requiresParentalConsent(): bool
    {
        return $this->is_minor === true;
    }

    /**
     * Vérifie si le client est majeur
     */
    public function isAdult(): bool
    {
        return !$this->is_minor;
    }
}

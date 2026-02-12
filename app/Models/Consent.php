<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BookingRequest;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Consent extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'client_id', 'booking_request_id', 'bookable_id', 'bookable_type', 'signature_data', 'signed_at',
        'medical_conditions', 'allergies', 'medications',
        'is_pregnant', 'has_skin_conditions',
        'is_minor', 'parent_signature_data', 'parent_name', 'parent_relation',
        'accepts_terms', 'accepts_aftercare'
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'medical_conditions' => 'array',
        'allergies' => 'array',
        'medications' => 'array',
        'is_pregnant' => 'boolean',
        'has_skin_conditions' => 'boolean',
        'is_minor' => 'boolean',
        'accepts_terms' => 'boolean',
        'accepts_aftercare' => 'boolean',
    ];

    // ===== RELATIONS =====

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    // Helper rétrocompatibilité
    public function getTattooerAttribute()
    {
        return $this->bookable_type === 'App\\Models\\Tattooer' ? $this->bookable : null;
    }

    // ===== SPATIE MEDIA =====

    public function registerMediaCollections(): void
    {
        // Photo ID parent (si mineur) - unique
        $this->addMediaCollection('parent_id_photo')
            ->singleFile()
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp'
            ])
            ->useDisk('public');
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si le consentement est valide
     */
    public function isValid(): bool
    {
        if (!$this->signature_data || !$this->signed_at) {
            return false;
        }

        if (!$this->accepts_terms || !$this->accepts_aftercare) {
            return false;
        }

        // Si mineur, vérifier consentement parental
        if ($this->is_minor) {
            return !empty($this->parent_signature_data) &&
                   !empty($this->parent_name) &&
                   $this->getMedia('parent_id_photo')->isNotEmpty();
        }

        return true;
    }

    /**
     * Obtenir un résumé des conditions médicales
     */
    public function getMedicalSummary(): array
    {
        return [
            'has_conditions' => !empty($this->medical_conditions),
            'conditions' => $this->medical_conditions ?? [],
            'has_allergies' => !empty($this->allergies),
            'allergies' => $this->allergies ?? [],
            'has_medications' => !empty($this->medications),
            'medications' => $this->medications ?? [],
            'is_pregnant' => $this->is_pregnant,
            'has_skin_conditions' => $this->has_skin_conditions,
        ];
    }

    /**
     * Obtenir les informations du consentement parental
     */
    public function getParentalConsentInfo(): ?array
    {
        if (!$this->is_minor) {
            return null;
        }

        return [
            'parent_name' => $this->parent_name,
            'parent_relation' => $this->parent_relation,
            'has_signature' => !empty($this->parent_signature_data),
            'has_id_photo' => $this->getMedia('parent_id_photo')->isNotEmpty(),
        ];
    }

    /**
     * Vérifier si le consentement est récent (moins de 1 an)
     */
    public function isRecent(): bool
    {
        return $this->signed_at && $this->signed_at->gt(now()->subYear());
    }

    /**
     * Obtenir l'URL de la signature du client
     */
    public function getClientSignatureUrl(): ?string
    {
        return $this->signature_data;
    }

    /**
     * Obtenir l'URL de la signature du parent
     */
    public function getParentSignatureUrl(): ?string
    {
        return $this->parent_signature_data;
    }

    /**
     * Obtenir l'URL de la photo d'identité du parent
     */
    public function getParentIdPhotoUrl(): ?string
    {
        $photo = $this->getFirstMedia('parent_id_photo');
        return $photo ? $photo->getUrl() : null;
    }
}

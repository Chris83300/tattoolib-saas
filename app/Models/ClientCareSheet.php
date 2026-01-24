<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Notifications\CareSheetReminder;

class ClientCareSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'appointment_id',

        // Informations tattoo
        'tattoo_description',
        'tattoo_location',
        'tattoo_size',
        'technique_used',
        'ink_colors_used',

        // Informations médicales
        'allergies_details',
        'skin_conditions_details',
        'medications_details',
        'has_diabetes',
        'has_blood_disorders',
        'is_pregnant',

        // Soins immédiats
        'immediate_care_instructions',
        'products_used',
        'bandage_type',
        'bandage_removal_time',

        // Instructions
        'washing_instructions',
        'moisturizing_instructions',
        'activity_restrictions',
        'sun_exposure_warnings',

        // Suivi
        'healing_estimated_date',
        'first_touchup_date',
        'healing_notes',
        'healing_status',
        'healing_photos',
    ];

    protected $casts = [
        'bandage_removal_time' => 'datetime',
        'healing_estimated_date' => 'date',
        'first_touchup_date' => 'date',
        'has_diabetes' => 'boolean',
        'has_blood_disorders' => 'boolean',
        'is_pregnant' => 'boolean',
        'healing_photos' => 'array',
        'allergies_details' => 'encrypted',
        'medications_details' => 'encrypted',
        'skin_conditions_details' => 'encrypted',
    ];
    protected static function booted()
    {
        static::created(function ($careSheet) {
            // Notification J+1
            $careSheet->client->user->notify(
                (new CareSheetReminder($careSheet, 'bandage_removal'))
                    ->delay($careSheet->bandage_removal_time)
            );

            // Notification J+3
            $careSheet->client->user->notify(
                (new CareSheetReminder($careSheet, 'photo_day_3'))
                    ->delay(now()->addDays(3))
            );

            // Notification J+14
            $careSheet->client->user->notify(
                (new CareSheetReminder($careSheet, 'photo_day_14'))
                    ->delay(now()->addDays(14))
            );
        });
    }

    // ===== CONSTANTES =====

    const HEALING_STATUS_IN_PROGRESS = 'in_progress';
    const HEALING_STATUS_HEALED = 'healed';
    const HEALING_STATUS_COMPLICATED = 'complicated';
    const HEALING_STATUS_TOUCHUP_NEEDED = 'touchup_needed';

    const HEALING_STATUSES = [
        self::HEALING_STATUS_IN_PROGRESS => 'En cours de cicatrisation',
        self::HEALING_STATUS_HEALED => 'Cicatrisé',
        self::HEALING_STATUS_COMPLICATED => 'Complications',
        self::HEALING_STATUS_TOUCHUP_NEEDED => 'Retouche nécessaire',
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

    // ===== SCOPES =====

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeHealingInProgress($query)
    {
        return $query->where('healing_status', self::HEALING_STATUS_IN_PROGRESS);
    }

    public function scopeHealed($query)
    {
        return $query->where('healing_status', self::HEALING_STATUS_HEALED);
    }

    public function scopeNeedsTouchup($query)
    {
        return $query->where('healing_status', self::HEALING_STATUS_TOUCHUP_NEEDED);
    }

    public function scopeComplicated($query)
    {
        return $query->where('healing_status', self::HEALING_STATUS_COMPLICATED);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si la période de cicatrisation est terminée
     */
    public function isHealingPeriodOver(): bool
    {
        return $this->healing_estimated_date->isPast();
    }

    /**
     * Calcule le nombre de jours de cicatrisation restants
     */
    public function getHealingDaysRemaining(): int
    {
        if ($this->isHealingPeriodOver()) {
            return 0;
        }

        return now()->diffInDays($this->healing_estimated_date);
    }

    /**
     * Ajoute une photo de suivi
     */
    public function addHealingPhoto(string $photoUrl, string $stage = 'unknown'): void
    {
        $photos = $this->healing_photos ?? [];

        $photos[] = [
            'url' => $photoUrl,
            'stage' => $stage, // 'day_1', 'day_3', 'day_7', 'day_14', 'final'
            'added_at' => now()->toISOString(),
        ];

        $this->update(['healing_photos' => $photos]);
    }

    /**
     * Met à jour le statut de cicatrisation
     */
    public function updateHealingStatus(string $status, string $notes = null): void
    {
        $this->update([
            'healing_status' => $status,
            'healing_notes' => $notes,
        ]);

        // Si statut "healed", on peut proposer une retouche
        if ($status === self::HEALING_STATUS_HEALED) {
            $this->update([
                'first_touchup_date' => now()->addWeeks(2), // Retouche suggérée après 2 semaines
            ]);
        }
    }

    /**
     * Génère les instructions de soins par défaut
     */
    public static function generateDefaultInstructions(string $tattooSize, string $tattooLocation): array
    {
        $baseInstructions = [
            'immediate_care_instructions' => "Garder le pansement pendant 2-4 heures. Ne pas exposer à l'eau pendant cette période.",
            'washing_instructions' => "Laver délicatement 2-3 fois par jour avec de l'eau tiède et un savon doux. Tamponner avec une serviette propre.",
            'moisturizing_instructions' => "Appliquer une fine couche de crème hydratante spécifique tattoo 2-3 fois par jour après la première semaine.",
            'activity_restrictions' => "Éviter les piscines, jacuzzis, et sports intenses pendant 2 semaines. Porter des vêtements amples.",
            'sun_exposure_warnings' => "Éviter toute exposition au soleil pendant 4 semaines. Appliquer un écran total SPF 50+ après cicatrisation complète.",
        ];

        // Adapter selon la taille et la localisation
        if (in_array($tattooSize, ['grand', 'very_large'])) {
            $baseInstructions['healing_estimated_date'] = now()->addWeeks(4);
        } else {
            $baseInstructions['healing_estimated_date'] = now()->addWeeks(2);
        }

        if (in_array($tattooLocation, ['main', 'pied', 'cou'])) {
            $baseInstructions['activity_restrictions'] .= " Attention particulière pour cette zone sujette aux frottements.";
        }

        return $baseInstructions;
    }

    /**
     * Crée une fiche de soins à partir d'un rendez-vous
     */
    public static function createFromAppointment(Appointment $appointment, array $tattooDetails = []): self
    {
        $bookingRequest = $appointment->bookingRequest;

        $careSheet = self::create([
            'client_id' => $appointment->client_id,
            'user_id' => $appointment->user_id,
            'appointment_id' => $appointment->id,

            'tattoo_description' => $tattooDetails['description'] ?? $bookingRequest->description ?? '',
            'tattoo_location' => $tattooDetails['location'] ?? $bookingRequest->body_zone ?? '',
            'tattoo_size' => $tattooDetails['size'] ?? $bookingRequest->tattoo_size ?? '',
            'technique_used' => $tattooDetails['technique'] ?? '',
            'ink_colors_used' => $tattooDetails['colors'] ?? '',

            // Instructions par défaut
            ...self::generateDefaultInstructions(
                $tattooDetails['size'] ?? $bookingRequest->tattoo_size ?? 'medium',
                $tattooDetails['location'] ?? $bookingRequest->body_zone ?? 'arm'
            ),

            'bandage_type' => $tattooDetails['bandage_type'] ?? 'film plastique',
            'bandage_removal_time' => now()->addHours(3),
        ]);

        return $careSheet;
    }

    /**
     * Vérifie si une retouche est recommandée
     */
    public function isTouchupRecommended(): bool
    {
        return $this->first_touchup_date &&
               $this->first_touchup_date->isPast() &&
               $this->healing_status === self::HEALING_STATUS_HEALED;
    }
}

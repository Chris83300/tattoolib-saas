<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkingHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'owner_type',
        'day_of_week',
        'is_open',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'slot_duration_minutes', // ⭐ NOUVEAU : durée créneau par défaut
        'buffer_time_minutes',   // ⭐ NOUVEAU : temps de préparation entre RDV
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'day_of_week' => 'integer',
    ];

    // ===== CONSTANTES =====

    const DAYS = [
        0 => 'Dimanche',
        1 => 'Lundi',
        2 => 'Mardi',
        3 => 'Mercredi',
        4 => 'Jeudi',
        5 => 'Vendredi',
        6 => 'Samedi',
    ];

    const DEFAULT_SLOT_DURATION = 60; // 1h par défaut
    const DEFAULT_BUFFER_TIME = 15;   // 15min de battement

    // ===== RELATIONS =====

    public function owner()
    {
        return $this->morphTo();
    }

    // Helper rétrocompatibilité
    public function getTattooerAttribute()
    {
        return $this->owner;
    }

    // ===== SCOPES =====

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('owner_id', $tattooerId)
                   ->where('owner_type', 'App\\Models\\Tattooer');
    }

    public function scopeForStudioArtist($query, int $studioArtistId)
    {
        return $query->where('owner_id', $studioArtistId)
                   ->where('owner_type', 'App\\Models\\StudioArtist');
    }

    public function scopeForBookable($query, Model $bookable)
    {
        return $query->where('owner_id', $bookable->getKey())
                   ->where('owner_type', get_class($bookable));
    }

    public function scopeOpen($query)
    {
        return $query->where('is_open', true);
    }

    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si le jour est fermé
     */
    public function isClosed(): bool
    {
        return !$this->is_open;
    }

    /**
     * Vérifie si une pause est configurée
     */
    public function hasBreak(): bool
    {
        return !empty($this->break_start) && !empty($this->break_end);
    }

    /**
     * Calcule la durée de travail effective (hors pause)
     */
    public function getWorkingDurationMinutes(): int
    {
        if ($this->isClosed()) {
            return 0;
        }

        $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);
        $totalMinutes = $start->diffInMinutes($end);

        // Soustraire la pause si elle existe
        if ($this->hasBreak()) {
            $breakStart = \Carbon\Carbon::createFromFormat('H:i', $this->break_start);
            $breakEnd = \Carbon\Carbon::createFromFormat('H:i', $this->break_end);
            $breakMinutes = $breakStart->diffInMinutes($breakEnd);
            $totalMinutes -= $breakMinutes;
        }

        return $totalMinutes;
    }

    /**
     * Génère les créneaux disponibles pour ce jour
     */
    public function generateTimeSlots(): array
    {
        if ($this->isClosed()) {
            return [];
        }

        $slots = [];
        $slotDuration = $this->slot_duration_minutes ?? self::DEFAULT_SLOT_DURATION;
        $bufferTime = $this->buffer_time_minutes ?? self::DEFAULT_BUFFER_TIME;

        $current = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);

        $breakStart = $this->hasBreak()
            ? \Carbon\Carbon::createFromFormat('H:i', $this->break_start)
            : null;
        $breakEnd = $this->hasBreak()
            ? \Carbon\Carbon::createFromFormat('H:i', $this->break_end)
            : null;

        while ($current->copy()->addMinutes($slotDuration) <= $end) {
            $slotEnd = $current->copy()->addMinutes($slotDuration);

            // Vérifier si le créneau ne chevauche pas la pause
            $isInBreak = false;
            if ($breakStart && $breakEnd) {
                $isInBreak = ($current >= $breakStart && $current < $breakEnd) ||
                             ($slotEnd > $breakStart && $slotEnd <= $breakEnd) ||
                             ($current < $breakStart && $slotEnd > $breakEnd);
            }

            if (!$isInBreak) {
                $slots[] = [
                    'start' => $current->format('H:i'),
                    'end' => $slotEnd->format('H:i'),
                    'duration_minutes' => $slotDuration,
                ];
            }

            // Avancer avec buffer time
            $current->addMinutes($slotDuration + $bufferTime);
        }

        return $slots;
    }

    /**
     * Obtient le libellé du jour
     */
    public function getDayLabel(): string
    {
        return self::DAYS[$this->day_of_week] ?? 'Inconnu';
    }

    /**
     * Vérifie si une heure est dans la plage de travail
     */
    public function isTimeInWorkingHours(string $time): bool
    {
        if ($this->isClosed()) {
            return false;
        }

        $checkTime = \Carbon\Carbon::createFromFormat('H:i', $time);
        $start = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);

        // Vérifier si dans les horaires d'ouverture
        if ($checkTime < $start || $checkTime >= $end) {
            return false;
        }

        // Vérifier si pas pendant la pause
        if ($this->hasBreak()) {
            $breakStart = \Carbon\Carbon::createFromFormat('H:i', $this->break_start);
            $breakEnd = \Carbon\Carbon::createFromFormat('H:i', $this->break_end);

            if ($checkTime >= $breakStart && $checkTime < $breakEnd) {
                return false;
            }
        }

        return true;
    }

    /**
     * Crée les horaires par défaut pour un tatoueur (Lundi-Vendredi 9h-18h)
     */
    public static function createDefaultSchedule(int $userId, string $ownerType = Tattooer::class): void
    {
        $defaultSchedule = [
            // Lundi à Vendredi
            ['day' => 1, 'open' => true, 'start' => '09:00', 'end' => '18:00', 'break_start' => '12:00', 'break_end' => '13:00'],
            ['day' => 2, 'open' => true, 'start' => '09:00', 'end' => '18:00', 'break_start' => '12:00', 'break_end' => '13:00'],
            ['day' => 3, 'open' => true, 'start' => '09:00', 'end' => '18:00', 'break_start' => '12:00', 'break_end' => '13:00'],
            ['day' => 4, 'open' => true, 'start' => '09:00', 'end' => '18:00', 'break_start' => '12:00', 'break_end' => '13:00'],
            ['day' => 5, 'open' => true, 'start' => '09:00', 'end' => '18:00', 'break_start' => '12:00', 'break_end' => '13:00'],
            // Weekend fermé
            ['day' => 6, 'open' => false],
            ['day' => 0, 'open' => false],
        ];

        foreach ($defaultSchedule as $schedule) {
            self::create([
                'owner_type' => $ownerType,
                'owner_id' => $userId,
                'day_of_week' => $schedule['day'],
                'is_open' => $schedule['open'],
                'start_time' => $schedule['start'] ?? null,
                'end_time' => $schedule['end'] ?? null,
                'break_start' => $schedule['break_start'] ?? null,
                'break_end' => $schedule['break_end'] ?? null,
                'slot_duration_minutes' => self::DEFAULT_SLOT_DURATION,
                'buffer_time_minutes' => self::DEFAULT_BUFFER_TIME,
            ]);
        }
    }

    /**
     * Valide la cohérence des horaires
     */
    public function validate(): array
    {
        $errors = [];

        if ($this->is_open) {
            // Vérifier que start_time < end_time
            $opening = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
            $closing = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);

            if ($opening >= $closing) {
                $errors[] = "L'heure d'ouverture doit être avant l'heure de fermeture";
            }

            // Vérifier la cohérence de la pause
            if ($this->hasBreak()) {
                $breakStart = \Carbon\Carbon::createFromFormat('H:i', $this->break_start);
                $breakEnd = \Carbon\Carbon::createFromFormat('H:i', $this->break_end);

                if ($breakStart >= $breakEnd) {
                    $errors[] = "L'heure de début de pause doit être avant l'heure de fin";
                }

                if ($breakStart < $opening || $breakEnd > $closing) {
                    $errors[] = "La pause doit être comprise dans les horaires d'ouverture";
                }
            }
        }

        return $errors;
    }
}

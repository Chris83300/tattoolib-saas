<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'bookable_id', 'bookable_type', 'type', 'appointment_id',
        'start_datetime', 'end_datetime', 'is_recurring', 'recurrence_rule',
        'notes', 'color', 'project_id'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'is_recurring' => 'boolean',
        'recurrence_rule' => 'array',
    ];

    // ===== CONSTANTES =====

    const TYPE_APPOINTMENT = 'appointment';
    const TYPE_BREAK = 'break';
    const TYPE_VACATION = 'vacation';
    const TYPE_CLOSURE = 'closure';

    const TYPES = [
        self::TYPE_APPOINTMENT => 'Rendez-vous',
        self::TYPE_BREAK => 'Pause',
        self::TYPE_VACATION => 'Vacances',
        self::TYPE_CLOSURE => 'Fermé',
    ];

    const COLORS = [
        self::TYPE_APPOINTMENT => '#06D6A0', // Vert
        self::TYPE_BREAK => '#F77F00', // Ambre
        self::TYPE_VACATION => '#E63946', // Rouge
        self::TYPE_CLOSURE => '#2E3440', // Titane
    ];

    // ===== RELATIONS =====

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    public function appointment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Appointment::class);
    }

    // Helper rétrocompatibilité
    public function getTattooerAttribute()
    {
        return $this->bookable_type === 'App\\Models\\Tattooer' ? $this->bookable : null;
    }

    // ===== SCOPES =====

    public function scopeForBookable($query, $bookableId, $bookableType)
    {
        return $query->where('bookable_id', $bookableId)
                   ->where('bookable_type', $bookableType);
    }

    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->where('start_datetime', '>=', $startDate)
                    ->where('end_datetime', '<=', $endDate);
    }

    public function scopeForType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now());
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Obtenir la durée en minutes
     */
    public function getDurationMinutes(): int
    {
        return $this->start_datetime->diffInMinutes($this->end_datetime);
    }

    /**
     * Obtenir la durée formatée
     */
    public function getFormattedDuration(): string
    {
        $minutes = $this->getDurationMinutes();
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        if ($hours > 0) {
            return $hours . 'h' . ($remainingMinutes > 0 ? ' ' . $remainingMinutes . 'min' : '');
        }

        return $remainingMinutes . 'min';
    }

    /**
     * Obtenir le titre de l'événement
     */
    public function getTitle(): string
    {
        return match($this->type) {
            self::TYPE_APPOINTMENT => $this->project_id && $this->project
                ? "RDV - {$this->project->client->first_name} {$this->project->client->last_name}"
                : 'Rendez-vous',
            self::TYPE_BREAK => 'Pause',
            self::TYPE_VACATION => 'Vacances',
            self::TYPE_CLOSURE => 'Fermé',
            default => 'Événement',
        };
    }

    /**
     * Obtenir la couleur par défaut selon le type
     */
    public function getDefaultColor(): string
    {
        return self::COLORS[$this->type] ?? '#D4B59E';
    }

    /**
     * Vérifier si l'événement peut être supprimé
     */
    public function canBeDeleted(): bool
    {
        // Pour les événements custom (repos, vacances, etc.), toujours autoriser la suppression
        if ($this->type !== self::TYPE_APPOINTMENT) {
            return true;
        }

        // Pour les RDV, on autorise la suppression (le tattooer gère son planning)
        return true;
    }

    /**
     * Vérifier si l'événement est en cours
     */
    public function isOngoing(): bool
    {
        $now = now();
        return $now->between($this->start_datetime, $this->end_datetime);
    }

    /**
     * Vérifier si l'événement est passé
     */
    public function isPast(): bool
    {
        return $this->end_datetime->isPast();
    }

    /**
     * Vérifier si l'événement est à venir
     */
    public function isFuture(): bool
    {
        return $this->start_datetime->isFuture();
    }

    /**
     * Convertir en format FullCalendar.js
     */
    public function toFullCalendarEvent(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTitle(),
            'start' => $this->start_datetime->toIso8601String(),
            'end' => $this->end_datetime->toIso8601String(),
            'backgroundColor' => $this->color ?? $this->getDefaultColor(),
            'borderColor' => $this->color ?? $this->getDefaultColor(),
            'textColor' => '#FFFFFF',
            'extendedProps' => [
                'type' => $this->type,
                'notes' => $this->notes,
                'duration' => $this->getFormattedDuration(),
                'can_be_deleted' => $this->canBeDeleted(),
            ],
        ];
    }

    /**
     * Obtenir les occurrences pour un événement récurrent
     */
    public function getRecurringOccurrences(\DateTime $startDate, \DateTime $endDate): array
    {
        if (!$this->is_recurring || !$this->recurrence_rule) {
            return [$this];
        }

        $occurrences = [];
        $current = clone $this->start_datetime;

        // Implémentation simple pour la récurrence hebdomadaire
        if (($this->recurrence_rule['freq'] ?? null) === 'weekly') {
            $days = $this->recurrence_rule['days'] ?? [];

            while ($current <= $endDate) {
                if (in_array($current->dayOfWeek, $days)) {
                    $occurrence = $this->replicate();
                    $occurrence->start_datetime = clone $current;
                    $occurrence->end_datetime = clone $current;
                    $occurrence->end_datetime->addMinutes($this->getDurationMinutes());
                    $occurrences[] = $occurrence;
                }
                $current->addDay();
            }
        }

        return $occurrences;
    }

    /**
     * Obtenir un résumé pour affichage
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTitle(),
            'type' => $this->type,
            'type_label' => $this->type,
            'start_datetime' => $this->start_datetime->format('d/m/Y H:i'),
            'end_datetime' => $this->end_datetime->format('d/m/Y H:i'),
            'duration' => $this->getFormattedDuration(),
            'is_recurring' => $this->is_recurring,
            'color' => $this->color ?? $this->getDefaultColor(),
            'notes' => $this->notes,
            'is_ongoing' => $this->isOngoing(),
            'is_past' => $this->isPast(),
            'is_future' => $this->isFuture(),
        ];
    }
}

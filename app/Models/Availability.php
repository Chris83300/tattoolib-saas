<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'tattooer_id',
        'date',
        'start_time',
        'end_time',
        'type',
        'source',
        'appointment_id',
        'notes',
        'is_recurring',
        'recurring_pattern',
        'recurring_end_date',
    ];

    protected $casts = [
        'date' => 'date',
        'recurring_end_date' => 'date',
        'is_recurring' => 'boolean',
    ];

    protected $appends = ['duration_minutes'];

    // ===== CONSTANTES =====

    const TYPE_AVAILABLE = 'available';
    const TYPE_BUSY = 'busy';
    const TYPE_BREAK = 'break';
    const TYPE_HOLIDAY = 'holiday';
    const TYPE_EXTERNAL_BOOKING = 'external_booking'; // ⭐ RDV hors plateforme
    const TYPE_BLOCKED = 'blocked'; // ⭐ Bloqué manuellement par tatoueur

    const SOURCE_WORKING_HOURS = 'working_hours';
    const SOURCE_MANUAL = 'manual';
    const SOURCE_APPOINTMENT = 'appointment';
    const SOURCE_EXTERNAL = 'external';

    const TYPES = [
        self::TYPE_AVAILABLE => 'Disponible',
        self::TYPE_BUSY => 'Occupé',
        self::TYPE_BREAK => 'Pause',
        self::TYPE_HOLIDAY => 'Congés',
        self::TYPE_EXTERNAL_BOOKING => 'RDV externe',
        self::TYPE_BLOCKED => 'Bloqué',
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

    // ===== SCOPES =====

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('tattooer_id', $tattooerId);
    }

    public function scopeBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeOnDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeAvailable($query)
    {
        return $query->where('type', self::TYPE_AVAILABLE);
    }

    public function scopeBookable($query)
    {
        // Créneaux réellement disponibles pour booking client
        return $query->where('type', self::TYPE_AVAILABLE)
            ->whereDate('date', '>=', now()->toDateString());
    }

    public function scopeBusy($query)
    {
        return $query->where('type', self::TYPE_BUSY)
            ->orWhereNotNull('appointment_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('date', '>=', now()->toDateString());
    }

    public function scopePast($query)
    {
        return $query->where('date', '<', now()->toDateString());
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Génère les availabilities depuis WorkingHours (version optimisée)
     */
    public static function generateFromWorkingHours(
        int $tattooerId,
        \Carbon\Carbon $startDate,
        \Carbon\Carbon $endDate
    ): int {
        $workingHours = WorkingHour::where('tattooer_id', $tattooerId)
            ->where('is_open', true)
            ->get()
            ->keyBy('day_of_week');

        if ($workingHours->isEmpty()) {
            return 0;
        }

        $generated = 0;
        $current = $startDate->copy();
        $toInsert = [];

        while ($current <= $endDate) {
            $dayOfWeek = $current->dayOfWeek;
            $workingHour = $workingHours->get($dayOfWeek);

            if ($workingHour) {
                $dateStr = $current->toDateString();

                // Vérifier qu'aucune availability n'existe déjà
                $exists = self::where('tattooer_id', $tattooerId)
                    ->where('date', $dateStr)
                    ->exists();

                if (!$exists) {
                    // Créneau principal de travail
                    $toInsert[] = [
                        'tattooer_id' => $tattooerId,
                        'date' => $dateStr,
                        'start_time' => $workingHour->start_time,
                        'end_time' => $workingHour->end_time,
                        'type' => self::TYPE_AVAILABLE,
                        'source' => self::SOURCE_WORKING_HOURS,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // Pause si existe
                    if ($workingHour->hasBreak()) {
                        $toInsert[] = [
                            'tattooer_id' => $tattooerId,
                            'date' => $dateStr,
                            'start_time' => $workingHour->break_start,
                            'end_time' => $workingHour->break_end,
                            'type' => self::TYPE_BREAK,
                            'source' => self::SOURCE_WORKING_HOURS,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }

                    $generated++;
                }
            }

            $current->addDay();
        }

        // Insertion groupée (optimisation)
        if (!empty($toInsert)) {
            self::insert($toInsert);
        }

        return $generated;
    }

    /**
     * Bloque un créneau pour un rendez-vous
     */
    public function blockForAppointment(Appointment $appointment): void
    {
        $this->update([
            'type' => self::TYPE_BUSY,
            'source' => self::SOURCE_APPOINTMENT,
            'appointment_id' => $appointment->id,
            'notes' => "Rendez-vous avec {$appointment->client->user->name}",
        ]);
    }

    /**
     * Libérer le créneau
     */
    public function release(): void
    {
        $this->update([
            'type' => self::TYPE_AVAILABLE,
            'source' => self::SOURCE_WORKING_HOURS,
            'appointment_id' => null,
            'notes' => null,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Bloquer un créneau spécifique manuellement
     */
    public static function blockSlot(
        int $tattooerId,
        string $date,
        string $startTime,
        string $endTime,
        string $type = self::TYPE_BLOCKED,
        ?string $notes = null
    ): self {
        return self::create([
            'tattooer_id' => $tattooerId,
            'date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'type' => $type,
            'source' => self::SOURCE_MANUAL,
            'notes' => $notes,
        ]);
    }

    /**
     * ⭐ NOUVEAU : Marquer comme RDV externe (pris hors plateforme)
     */
    public function markAsExternalBooking(string $notes): void
    {
        $this->update([
            'type' => self::TYPE_EXTERNAL_BOOKING,
            'source' => self::SOURCE_EXTERNAL,
            'notes' => $notes,
        ]);
    }

    /**
     * Vérifie si un créneau horaire spécifique est disponible
     */
    public function isTimeSlotAvailable(string $startTime, int $durationMinutes): bool
    {
        if ($this->type !== self::TYPE_AVAILABLE) {
            return false;
        }

        $requestedStart = \Carbon\Carbon::createFromFormat('H:i', $startTime);
        $requestedEnd = $requestedStart->copy()->addMinutes($durationMinutes);

        $availabilityStart = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $availabilityEnd = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);

        return $requestedStart >= $availabilityStart && $requestedEnd <= $availabilityEnd;
    }

    /**
     * Découpe une availability en plusieurs créneaux
     * Utile pour afficher des slots horaires précis au client
     */
    public function splitIntoSlots(int $slotDurationMinutes = 60, int $bufferMinutes = 15): array
    {
        if ($this->type !== self::TYPE_AVAILABLE) {
            return [];
        }

        $slots = [];
        $current = \Carbon\Carbon::createFromFormat('H:i', $this->start_time);
        $end = \Carbon\Carbon::createFromFormat('H:i', $this->end_time);

        while ($current->copy()->addMinutes($slotDurationMinutes) <= $end) {
            $slotEnd = $current->copy()->addMinutes($slotDurationMinutes);

            $slots[] = [
                'start' => $current->format('H:i'),
                'end' => $slotEnd->format('H:i'),
                'date' => $this->date->format('Y-m-d'),
                'available' => $this->isSlotAvailable($current->format('H:i'), $slotDurationMinutes),
            ];

            $current->addMinutes($slotDurationMinutes + $bufferMinutes);
        }

        return $slots;
    }

    /**
     * Vérifie si un slot spécifique est libre (pas de RDV qui chevauche)
     */
    protected function isSlotAvailable(string $startTime, int $durationMinutes): bool
    {
        $slotStart = \Carbon\Carbon::parse("{$this->date->format('Y-m-d')} {$startTime}");
        $slotEnd = $slotStart->copy()->addMinutes($durationMinutes);

        // Vérifier qu'aucun RDV ne chevauche
        $hasConflict = Appointment::where('tattooer_id', $this->tattooer_id)
            ->whereDate('start_time', $this->date)
            ->where(function ($query) use ($slotStart, $slotEnd) {
                $query->whereBetween('start_time', [$slotStart, $slotEnd])
                    ->orWhereBetween('end_time', [$slotStart, $slotEnd])
                    ->orWhere(function ($q) use ($slotStart, $slotEnd) {
                        $q->where('start_time', '<=', $slotStart)
                          ->where('end_time', '>=', $slotEnd);
                    });
            })
            ->exists();

        return !$hasConflict;
    }

    /**
     * Génère des availabilities récurrentes
     */
    public static function generateRecurring(
        int $tattooerId,
        \Carbon\Carbon $startDate,
        \Carbon\Carbon $endDate,
        string $startTime,
        string $endTime,
        string $pattern,
        array $daysOfWeek = [],
        string $type = self::TYPE_AVAILABLE
    ): int {
        $generated = 0;
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $shouldCreate = false;

            switch ($pattern) {
                case 'daily':
                    $shouldCreate = true;
                    break;
                case 'weekly':
                    $shouldCreate = in_array($current->dayOfWeek, $daysOfWeek);
                    break;
                case 'monthly':
                    $shouldCreate = $current->day == $startDate->day;
                    break;
            }

            if ($shouldCreate) {
                self::create([
                    'tattooer_id' => $tattooerId,
                    'date' => $current->toDateString(),
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'type' => $type,
                    'is_recurring' => true,
                    'recurring_pattern' => $pattern,
                    'recurring_end_date' => $endDate,
                    'generated_from_working_hour' => false,
                ]);

                $generated++;
            }

            $current->addDay();
        }

        return $generated;
    }

    /**
     * Obtenir la durée en minutes (calculée ou stockée)
     */
    public function getDurationMinutesAttribute(): int
    {
        // Si la valeur est déjà calculée (MySQL virtuelle ou déjà stockée)
        if (isset($this->attributes['duration_minutes']) && $this->attributes['duration_minutes'] !== null) {
            return (int) $this->attributes['duration_minutes'];
        }

        // Calcul manuel pour SQLite ou si la colonne est null
        if ($this->start_time && $this->end_time) {
            $start = \Carbon\Carbon::parse($this->start_time);
            $end = \Carbon\Carbon::parse($this->end_time);
            return $start->diffInMinutes($end);
        }

        return 0;
    }

    /**
     * Vérifie si la disponibilité est dans le passé
     */
    public function isPast(): bool
    {
        return $this->date->isPast() ||
               ($this->date->isToday() && now()->format('H:i') > $this->end_time);
    }

    /**
     * ⭐ NOUVEAU : Découper la journée en créneaux libres
     * Retourne uniquement les créneaux réellement disponibles
     */
    public static function getAvailableSlotsForDay(
        int $tattooerId,
        string $date
    ): array {
        $allSlots = self::forTattooer($tattooerId)
            ->onDate($date)
            ->orderBy('start_time')
            ->get();

        $availableSlots = [];
        $busyPeriods = $allSlots->whereIn('type', [
            self::TYPE_BUSY,
            self::TYPE_BREAK,
            self::TYPE_EXTERNAL_BOOKING,
            self::TYPE_BLOCKED,
            self::TYPE_HOLIDAY
        ])->sortBy('start_time');

        $workingPeriods = $allSlots->where('type', self::TYPE_AVAILABLE);

        foreach ($workingPeriods as $workingPeriod) {
            $start = \Carbon\Carbon::parse($workingPeriod->start_time);
            $end = \Carbon\Carbon::parse($workingPeriod->end_time);

            // Découper selon les périodes occupées
            $currentStart = $start->copy();

            foreach ($busyPeriods as $busy) {
                $busyStart = \Carbon\Carbon::parse($busy->start_time);
                $busyEnd = \Carbon\Carbon::parse($busy->end_time);

                // Si période occupée chevauche
                if ($busyStart < $end && $busyEnd > $currentStart) {
                    // Ajouter le créneau libre avant la période occupée
                    if ($currentStart < $busyStart) {
                        $availableSlots[] = [
                            'date' => $date,
                            'start_time' => $currentStart->format('H:i'),
                            'end_time' => $busyStart->format('H:i'),
                            'duration_minutes' => $currentStart->diffInMinutes($busyStart),
                        ];
                    }

                    // Avancer après la période occupée
                    $currentStart = $busyEnd > $currentStart ? $busyEnd : $currentStart;
                }
            }

            // Ajouter le créneau final s'il reste du temps
            if ($currentStart < $end) {
                $availableSlots[] = [
                    'date' => $date,
                    'start_time' => $currentStart->format('H:i'),
                    'end_time' => $end->format('H:i'),
                    'duration_minutes' => $currentStart->diffInMinutes($end),
                ];
            }
        }

        return $availableSlots;
    }

    /**
     * ⭐ NOUVEAU : Vérifier si une date a de la disponibilité
     */
    public static function hasAvailabilityOnDate(int $tattooerId, string $date): bool
    {
        $slots = self::getAvailableSlotsForDay($tattooerId, $date);
        return !empty($slots);
    }

    /**
     * ⭐ NOUVEAU : Obtenir les dates disponibles sur une période
     */
    public static function getAvailableDates(
        int $tattooerId,
        \Carbon\Carbon $startDate,
        \Carbon\Carbon $endDate
    ): array {
        $dates = [];
        $current = $startDate->copy();

        while ($current <= $endDate) {
            $dateStr = $current->toDateString();

            // S'assurer que les availabilities existent
            $exists = self::forTattooer($tattooerId)
                ->onDate($dateStr)
                ->exists();

            if (!$exists) {
                self::generateFromWorkingHours($tattooerId, $current, $current);
            }

            // Vérifier disponibilité
            if (self::hasAvailabilityOnDate($tattooerId, $dateStr)) {
                $slots = self::getAvailableSlotsForDay($tattooerId, $dateStr);
                $totalMinutes = array_sum(array_column($slots, 'duration_minutes'));

                $dates[] = [
                    'date' => $dateStr,
                    'day_name' => $current->locale('fr')->dayName,
                    'is_today' => $current->isToday(),
                    'is_weekend' => $current->isWeekend(),
                    'available_slots_count' => count($slots),
                    'total_available_minutes' => $totalMinutes,
                ];
            }

            $current->addDay();
        }

        return $dates;
    }
}

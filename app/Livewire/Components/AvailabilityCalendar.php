<?php

namespace App\Livewire\Components;

use App\Models\Appointment;
use App\Models\Availability;
use App\Models\CalendarEvent;
use App\Models\Tattooer;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class AvailabilityCalendar extends Component
{
    /**
     * ═══════════════════════════════════════
     * MAPPING JOURS FRANÇAIS ↔ CARBON
     * ═══════════════════════════════════════
     *
     * CRITIQUE : Les clés du JSON tattooers.working_hours
     * sont en FRANÇAIS. Carbon::dayOfWeek retourne un INT (0=dim, 6=sam).
     */
    const CARBON_TO_FRENCH_DAY = [
        0 => 'dimanche',
        1 => 'lundi',
        2 => 'mardi',
        3 => 'mercredi',
        4 => 'jeudi',
        5 => 'vendredi',
        6 => 'samedi',
    ];

    // === Props (passées par le parent via mount) ===
    public int $tattooerId;
    public string $mode = 'single';       // 'single' | 'multi-max-3' | 'multi-select'
    public bool $showPeriodSelector = true;
    public bool $readOnly = false; // New property for read-only mode
    public array $selectableDates = [];    // Si fourni, seules ces dates sont cliquables

    // === State ===
    public int $currentMonth;
    public int $currentYear;
    public array $calendarDays = [];
    public array $selectedDates = [];      // [['date' => '2026-03-15', 'period' => 'morning'], ...]
    public array $dayAvailability = [];    // Cache des dispos par jour

    // === Computed ===
    public string $monthName = '';

    public function mount(
        int $tattooerId,
        string $mode = 'single',
        bool $showPeriodSelector = true,
        array $selectableDates = [],
        array $initialSelectedDates = []
    ): void {
        $this->tattooerId = $tattooerId;
        $this->mode = $mode;
        $this->showPeriodSelector = $showPeriodSelector;
        $this->selectableDates = $selectableDates;
        $this->selectedDates = $initialSelectedDates;

        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;

        $this->computeCalendarDays();
    }

    public function previousMonth(): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        if ($date->lt(now()->startOfMonth())) {
            return;
        }
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->computeCalendarDays();
    }

    public function nextMonth(): void
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->computeCalendarDays();
    }

    /**
     * ═══════════════════════════════════════════════
     * LOGIQUE CENTRALE — Calcul des jours du calendrier
     * ═══════════════════════════════════════════════
     */
    public function computeCalendarDays(): void
    {
        $tattooer = Tattooer::find($this->tattooerId);
        if (!$tattooer) {
            $this->calendarDays = [];
            return;
        }

        // ── 1. Lire les horaires depuis le JSON tattooers.working_hours ──
        //
        // ⭐ C'EST ICI LA DIFFÉRENCE CRUCIALE ⭐
        // On lit $tattooer->working_hours (colonne JSON de la table tattooers)
        // PAS la table working_hours qui est VIDE.
        //
        // Format : ["lundi" => ["open" => "09:00", "close" => "18:00",
        //           "break_start" => "12:00", "break_end" => "14:00"], ...]
        //
        $workingHoursJson = $tattooer->working_hours ?? [];
        // Si c'est un string JSON (non casté), décoder :
        if (is_string($workingHoursJson)) {
            $workingHoursJson = json_decode($workingHoursJson, true) ?? [];
        }

        // ── 2. Récupérer les CalendarEvents du mois ──
        $monthStart = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfDay();
        $monthEnd = $monthStart->copy()->endOfMonth()->endOfDay();

        $calendarEvents = CalendarEvent::where('bookable_type', Tattooer::class)
            ->where('bookable_id', $tattooer->id)
            ->where(function ($q) use ($monthStart, $monthEnd) {
                $q->whereBetween('start_datetime', [$monthStart, $monthEnd])
                  ->orWhereBetween('end_datetime', [$monthStart, $monthEnd])
                  ->orWhere(function ($q2) use ($monthStart, $monthEnd) {
                      $q2->where('start_datetime', '<=', $monthStart)
                         ->where('end_datetime', '>=', $monthEnd);
                  });
            })
            ->get();

        // ── 3. Récupérer les Appointments du mois (RDV bookés) ──
        $appointments = Appointment::where('bookable_type', Tattooer::class)
            ->where('bookable_id', $tattooer->id)
            ->whereBetween('start_datetime', [$monthStart, $monthEnd])
            ->whereNotIn('status', ['cancelled'])
            ->get();

        // ── 4. Récupérer les Availabilities bloquées du mois ──
        $blockedAvailabilities = Availability::where('owner_type', Tattooer::class)
            ->where('owner_id', $tattooer->id)
            ->whereIn('type', ['holiday', 'sick_leave', 'blocked', 'busy', 'external_booking'])
            ->whereBetween('date', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->get();

        // ── 5. Construire le tableau des jours ──
        $this->calendarDays = [];
        $this->dayAvailability = [];

        $gridStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
        $period = CarbonPeriod::create($gridStart, $gridEnd);

        foreach ($period as $date) {
            $dateString = $date->toDateString();
            $isOutsideMonth = ($date->month !== $this->currentMonth);
            $isPast = $date->lt(now()->startOfDay());

            // ── 5a. Jour travaillé ? (depuis le JSON) ──
            $frenchDay = self::CARBON_TO_FRENCH_DAY[$date->dayOfWeek];
            $daySchedule = $workingHoursJson[$frenchDay] ?? null;

            // Le jour est travaillé si open n'est pas null
            $isWorkDay = $daySchedule
                && !empty($daySchedule['open'])
                && !empty($daySchedule['close']);

            $openingHour = $isWorkDay ? (int) substr($daySchedule['open'], 0, 2) : 0;
            $closingHour = $isWorkDay ? (int) substr($daySchedule['close'], 0, 2) : 0;
            $breakStart = !empty($daySchedule['break_start']) ? (int) substr($daySchedule['break_start'], 0, 2) : null;
            $breakEnd = !empty($daySchedule['break_end']) ? (int) substr($daySchedule['break_end'], 0, 2) : null;

            // ── 5b. Blocked par une Availability ? ──
            $isDayBlocked = $blockedAvailabilities->contains(function ($av) use ($dateString) {
                return $av->date->toDateString() === $dateString;
            });

            // ── 5c. Événements calendrier ce jour ──
            $dayEvents = $calendarEvents->filter(function ($event) use ($date) {
                $eventStart = Carbon::parse($event->start_datetime)->startOfDay();
                $eventEnd = Carbon::parse($event->end_datetime)->startOfDay();
                return $date->between($eventStart, $eventEnd);
            });

            $fullDayBlocked = $dayEvents->contains(function ($event) {
                return in_array($event->type, ['vacation', 'closure']);
            });

            // Matin bloqué par un event ?
            $morningBlockedByEvent = $dayEvents->contains(function ($event) use ($date) {
                if (in_array($event->type, ['vacation', 'closure'])) return true;
                $eventStart = Carbon::parse($event->start_datetime);
                $eventEnd = Carbon::parse($event->end_datetime);
                $morningEnd = $date->copy()->setTime(13, 0);
                return $eventStart->lt($morningEnd) && $eventEnd->gt($date->copy()->startOfDay());
            });

            // Après-midi bloqué par un event ?
            $afternoonBlockedByEvent = $dayEvents->contains(function ($event) use ($date) {
                if (in_array($event->type, ['vacation', 'closure'])) return true;
                $eventStart = Carbon::parse($event->start_datetime);
                $eventEnd = Carbon::parse($event->end_datetime);
                $afternoonStart = $date->copy()->setTime(13, 0);
                return $eventStart->lt($date->copy()->endOfDay()) && $eventEnd->gt($afternoonStart);
            });

            // ── 5d. Appointments bookés ce jour ──
            $dayAppointments = $appointments->filter(function ($apt) use ($dateString) {
                return Carbon::parse($apt->start_datetime)->toDateString() === $dateString;
            });

            $hasMorningAppointment = $dayAppointments->contains(function ($apt) {
                return Carbon::parse($apt->start_datetime)->hour < 13;
            });

            $hasAfternoonAppointment = $dayAppointments->contains(function ($apt) {
                return Carbon::parse($apt->start_datetime)->hour >= 13;
            });

            // ── 5e. Calculer disponibilité finale ──
            $morningAvailable = false;
            $afternoonAvailable = false;

            if ($isWorkDay && !$isDayBlocked && !$fullDayBlocked && !$isPast && !$isOutsideMonth) {
                // Matin dispo si : le tattooer travaille le matin + pas bloqué + pas de RDV
                $worksMorning = ($openingHour < 13);
                $worksAfternoon = ($closingHour > 13);

                // Prise en compte de la pause déjeuner :
                // Si break_start=12 et break_end=14, le matin va de open→12h et l'aprem de 14h→close
                // Ça ne change pas la DISPONIBILITÉ matin/après-midi,
                // juste les heures exactes (gérées au moment du booking)

                $morningAvailable = $worksMorning && !$morningBlockedByEvent && !$hasMorningAppointment;
                $afternoonAvailable = $worksAfternoon && !$afternoonBlockedByEvent && !$hasAfternoonAppointment;
            }

            // ── 5f. Déterminer le statut ──
            $status = 'unavailable';
            if ($isPast) {
                $status = 'past';
            } elseif ($morningAvailable && $afternoonAvailable) {
                $status = 'fully_available';
            } elseif ($morningAvailable) {
                $status = 'morning_only';
            } elseif ($afternoonAvailable) {
                $status = 'afternoon_only';
            }

            // ── 5g. Restreindre si selectableDates fourni ──
            if (!empty($this->selectableDates)) {
                $isSelectable = collect($this->selectableDates)->contains(fn($sd) => $sd['date'] === $dateString);
                if (!$isSelectable && $status !== 'past') {
                    $status = 'unavailable';
                }
            }

            // ── 5h. Sélectionné ? ──
            $isSelected = collect($this->selectedDates)->contains(fn($sd) => $sd['date'] === $dateString);

            $this->calendarDays[] = [
                'date' => $dateString,
                'day_number' => $date->day,
                'outside_month' => $isOutsideMonth,
                'is_today' => $date->isToday(),
                'status' => $status,
                'morning_available' => $morningAvailable,
                'afternoon_available' => $afternoonAvailable,
                'selected' => $isSelected,
            ];

            $this->dayAvailability[$dateString] = [
                'morning' => $morningAvailable,
                'afternoon' => $afternoonAvailable,
            ];
        }

        $this->monthName = Carbon::create($this->currentYear, $this->currentMonth, 1)
            ->translatedFormat('F');
    }

    /**
     * ═══════════════════════════════════════
     * SÉLECTION DE DATES
     * ═══════════════════════════════════════
     */
    public function selectDate(string $date): void
    {
        // Prevent selection in read-only mode
        if ($this->readOnly) {
            return;
        }

        $day = collect($this->calendarDays)->firstWhere('date', $date);
        if (!$day || in_array($day['status'], ['unavailable', 'past']) || $day['outside_month']) {
            return;
        }

        $existingIndex = collect($this->selectedDates)->search(fn($sd) => $sd['date'] === $date);

        if ($existingIndex !== false) {
            array_splice($this->selectedDates, $existingIndex, 1);
            $this->selectedDates = array_values($this->selectedDates);
        } else {
            $newEntry = ['date' => $date, 'period' => ''];

            $avail = $this->dayAvailability[$date] ?? ['morning' => true, 'afternoon' => true];
            if ($avail['morning'] && !$avail['afternoon']) {
                $newEntry['period'] = 'morning';
            } elseif (!$avail['morning'] && $avail['afternoon']) {
                $newEntry['period'] = 'afternoon';
            }

            switch ($this->mode) {
                case 'single':
                    $this->selectedDates = [$newEntry];
                    break;
                case 'multi-max-3':
                    if (count($this->selectedDates) < 3) {
                        $this->selectedDates[] = $newEntry;
                    }
                    break;
                case 'multi-select':
                    $this->selectedDates[] = $newEntry;
                    break;
            }
        }

        foreach ($this->calendarDays as &$d) {
            $d['selected'] = collect($this->selectedDates)->contains(fn($sd) => $sd['date'] === $d['date']);
        }
        unset($d);

        $this->dispatch('dates-updated', selectedDates: $this->selectedDates);
    }

    public function removeDate(int $index): void
    {
        // Prevent removal in read-only mode
        if ($this->readOnly) {
            return;
        }

        if (isset($this->selectedDates[$index])) {
            array_splice($this->selectedDates, $index, 1);
            $this->selectedDates = array_values($this->selectedDates);

            foreach ($this->calendarDays as &$d) {
                $d['selected'] = collect($this->selectedDates)->contains(fn($sd) => $sd['date'] === $d['date']);
            }
            unset($d);

            $this->dispatch('dates-updated', selectedDates: $this->selectedDates);
        }
    }

    public function updatedSelectedDates($value, $key)
    {
        // Quand la période est mise à jour via wire:model
        if (is_string($key) && str_contains($key, '.period')) {
            $this->dispatch('dates-updated', selectedDates: $this->selectedDates);
        }
    }

    public function render()
    {
        return view('livewire.components.availability-calendar');
    }
}

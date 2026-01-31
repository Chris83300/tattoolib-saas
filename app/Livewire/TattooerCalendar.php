<?php

namespace App\Livewire;

use App\Models\CalendarEvent;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TattooerCalendar extends Component
{
    public $events = [];
    public $selectedEvent = null;
    public $showEventModal = false;
    public $showCreateModal = false;
    
    // Formulaire création événement
    public $eventType = 'break';
    public $eventTitle = '';
    public $eventStartDate = '';
    public $eventStartTime = '';
    public $eventEndDate = '';
    public $eventEndTime = '';
    public $eventNotes = '';
    public $eventColor = '#F77F00';

    protected $rules = [
        'eventType' => 'required|in:appointment,break,vacation,closure',
        'eventTitle' => 'required_if:eventType,break,vacation,closure|string|max:255',
        'eventStartDate' => 'required|date',
        'eventStartTime' => 'required',
        'eventEndDate' => 'required|date|after_or_equal:eventStartDate',
        'eventEndTime' => 'required',
        'eventNotes' => 'nullable|string|max:1000',
    ];

    protected $listeners = [
        'refreshCalendar' => 'loadEvents',
        'eventCreated' => 'loadEvents',
        'eventUpdated' => 'loadEvents',
        'eventDeleted' => 'loadEvents',
    ];

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $user = Auth::user();
        $bookable = null;

        // Déterminer le bookable selon le rôle
        if ($user->isTattooer()) {
            $bookable = $user->tattooer;
        } elseif ($user->isStudioArtist()) {
            $bookable = $user->studioArtist;
        } elseif ($user->isPiercer()) {
            $bookable = $user->piercer;
        }

        if (!$bookable) {
            $this->events = [];
            return;
        }

        $calendarEvents = CalendarEvent::where('bookable_id', $bookable->id)
            ->where('bookable_type', get_class($bookable))
            ->with('project.client')
            ->get();

        $this->events = $calendarEvents->map(fn($event) => $event->toFullCalendarEvent())->toArray();
    }

    public function createEvent($data)
    {
        $this->validate([
            'eventType' => 'required|in:break,vacation,closure',
            'eventTitle' => 'required|string|max:255',
            'eventStartDate' => 'required|date',
            'eventStartTime' => 'required',
            'eventEndDate' => 'required|date|after_or_equal:eventStartDate',
            'eventEndTime' => 'required',
            'eventNotes' => 'nullable|string|max:1000',
        ]);

        try {
            $user = Auth::user();
            $bookable = $this->getBookable();

            if (!$bookable) {
                session()->flash('error', 'Non autorisé');
                return;
            }

            // Combiner date et heure
            $startDateTime = new \DateTime($this->eventStartDate . ' ' . $this->eventStartTime);
            $endDateTime = new \DateTime($this->eventEndDate . ' ' . $this->eventEndTime);

            // Vérifier que la fin est après le début
            if ($endDateTime <= $startDateTime) {
                session()->flash('error', 'L\'heure de fin doit être après l\'heure de début');
                return;
            }

            CalendarEvent::create([
                'bookable_id' => $bookable->id,
                'bookable_type' => get_class($bookable),
                'type' => $this->eventType,
                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,
                'notes' => $this->eventNotes,
                'color' => $this->getEventColor($this->eventType),
            ]);

            $this->resetCreateForm();
            $this->showCreateModal = false;
            $this->loadEvents();

            session()->flash('success', 'Événement créé avec succès');
            $this->dispatch('eventCreated');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la création de l\'événement');
            \Log::error('Calendar event creation error: ' . $e->getMessage());
        }
    }

    public function updateEvent($eventId, $data)
    {
        try {
            $event = CalendarEvent::findOrFail($eventId);
            
            // Vérifier que l'utilisateur peut modifier cet événement
            if (!$this->canModifyEvent($event)) {
                session()->flash('error', 'Non autorisé à modifier cet événement');
                return;
            }

            // Mettre à jour les dates
            if (isset($data['start']) && isset($data['end'])) {
                $startDateTime = new \DateTime($data['start']);
                $endDateTime = new \DateTime($data['end']);

                $event->update([
                    'start_datetime' => $startDateTime,
                    'end_datetime' => $endDateTime,
                ]);
            }

            $this->loadEvents();
            session()->flash('success', 'Événement mis à jour');
            $this->dispatch('eventUpdated');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la mise à jour');
            \Log::error('Calendar event update error: ' . $e->getMessage());
        }
    }

    public function deleteEvent($eventId)
    {
        try {
            $event = CalendarEvent::findOrFail($eventId);
            
            // Vérifier que l'utilisateur peut supprimer cet événement
            if (!$this->canModifyEvent($event)) {
                session()->flash('error', 'Non autorisé à supprimer cet événement');
                return;
            }

            $event->delete();
            $this->loadEvents();
            session()->flash('success', 'Événement supprimé');
            $this->dispatch('eventDeleted');

        } catch (\Exception $e) {
            session()->flash('error', 'Erreur lors de la suppression');
            \Log::error('Calendar event deletion error: ' . $e->getMessage());
        }
    }

    public function showEventDetails($eventId)
    {
        $event = CalendarEvent::with('project.client')->findOrFail($eventId);
        $this->selectedEvent = $event;
        $this->showEventModal = true;
    }

    public function openCreateModal($data = [])
    {
        $this->eventType = $data['type'] ?? 'break';
        $this->eventStartDate = $data['start_date'] ?? '';
        $this->eventStartTime = $data['start_time'] ?? '';
        $this->eventEndDate = $data['end_date'] ?? '';
        $this->eventEndTime = $data['end_time'] ?? '';
        $this->eventTitle = $this->getDefaultTitle($this->eventType);
        $this->eventColor = $this->getEventColor($this->eventType);
        $this->showCreateModal = true;
    }

    private function getBookable()
    {
        $user = Auth::user();

        if ($user->isTattooer()) {
            return $user->tattooer;
        } elseif ($user->isStudioArtist()) {
            return $user->studioArtist;
        } elseif ($user->isPiercer()) {
            return $user->piercer;
        }

        return null;
    }

    private function canModifyEvent(CalendarEvent $event): bool
    {
        $bookable = $this->getBookable();
        
        if (!$bookable) {
            return false;
        }

        return $event->bookable_id === $bookable->id && 
               $event->bookable_type === get_class($bookable) &&
               $event->canBeDeleted();
    }

    private function getEventColor($type): string
    {
        return CalendarEvent::COLORS[$type] ?? '#D4B59E';
    }

    private function getDefaultTitle($type): string
    {
        return match($type) {
            'break' => 'Pause',
            'vacation' => 'Vacances',
            'closure' => 'Fermé',
            default => '',
        };
    }

    private function resetCreateForm()
    {
        $this->reset([
            'eventType', 'eventTitle', 'eventStartDate', 'eventStartTime',
            'eventEndDate', 'eventEndTime', 'eventNotes', 'eventColor'
        ]);
    }

    public function render()
    {
        return view('livewire.tattooer-calendar');
    }
}

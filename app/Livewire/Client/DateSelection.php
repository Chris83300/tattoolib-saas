<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\BookingRequest;
use Livewire\Attributes\On;

class DateSelection extends Component
{
    public BookingRequest $bookingRequest;
    public array $selectedDateIndexes = [];
    public array $selectedDates = [];

    public function mount(BookingRequest $bookingRequest): void
    {
        $this->bookingRequest = $bookingRequest;
    }

    public function toggleDateSelection(int $index): void
    {
        if (in_array($index, $this->selectedDateIndexes)) {
            $this->selectedDateIndexes = array_values(array_diff($this->selectedDateIndexes, [$index]));
        } else {
            $this->selectedDateIndexes[] = $index;
        }
    }

    public function confirmDateSelection(): void
    {
        if (empty($this->selectedDateIndexes)) {
            session()->flash('error', 'Veuillez sélectionner au moins une date');
            return;
        }

        $proposedDates = $this->bookingRequest->proposed_dates;
        $selectedDates = [];
        
        foreach ($this->selectedDateIndexes as $index) {
            if (isset($proposedDates[$index])) {
                $selectedDates[] = $proposedDates[$index];
            }
        }

        $this->bookingRequest->update([
            'client_selected_dates' => $selectedDates,
            'client_dates_selected_at' => now(),
        ]);

        // Envoyer message système dans le chat
        $conversation = $this->bookingRequest->conversation;
        if ($conversation) {
            $datesList = collect($selectedDates)->map(function ($d) {
                $date = \Carbon\Carbon::parse($d['date'])->translatedFormat('l d F Y');
                $period = match($d['period'] ?? '') {
                    'morning' => 'matin', 
                    'afternoon' => 'après-midi', 
                    'evening' => 'soirée', 
                    default => 'flexible'
                };
                return "{$date} ({$period})";
            })->join(', ');

            $conversation->messages()->create([
                'sender_type' => 'system',
                'content' => "📅 Le client a sélectionné ses dates préférées : {$datesList}. Vous pouvez maintenant fixer le rendez-vous.",
            ]);
        }

        // TODO: Notification tattooer (sera implémenté plus tard)

        session()->flash('success', 'Dates envoyées au tatoueur !');
        
        // Rafraîchir la page
        $this->dispatch('date-selection-confirmed');
    }

    public function requestAlternativeDate(): void
    {
        // Envoyer message dans le chat demandant d'autres dates
        $conversation = $this->bookingRequest->conversation;
        if ($conversation) {
            $conversation->messages()->create([
                'sender_type' => 'system',
                'content' => "⚠️ Le client ne peut pas aux dates proposées et demande d'autres alternatives.",
            ]);
        }

        // TODO: Notification tattooer (sera implémenté plus tard)
        
        session()->flash('success', 'Demande d\'autres dates envoyée au tatoueur');
    }

    public function render()
    {
        return view('livewire.client.date-selection');
    }
}

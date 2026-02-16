<?php

namespace App\Livewire\Tattooer;

use App\Models\BookingRequest;
use App\Models\Conversation;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class ReproposeDatesModal extends Component
{
    public ?int $bookingRequestId = null;
    public bool $showModal = false;
    public array $selectedDates = [];

    /**
     * Écouter l'événement du calendrier — MÊME mécanisme que AcceptBookingModal
     */

    public function onDatesUpdated($dates = [])
    {
        // Le calendrier envoie parfois un tableau imbriqué, parfois direct
        if (isset($dates['selectedDates'])) {
            $this->selectedDates = $dates['selectedDates'];
        } elseif (isset($dates[0]) && is_array($dates[0])) {
            $this->selectedDates = $dates;
        } else {
            $this->selectedDates = $dates;
        }
    }

    /**
     * Écouter l'événement d'ouverture de modal
     */
    #[On('openModal')]
    public function openModal()
    {
        $this->showModal = true;
        $this->selectedDates = [];
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedDates = [];
    }
    #[On('submitReproposedDates')]
    public function submitNewDates($dates = [])
    {
        $this->selectedDates = $dates;

        if (empty($this->selectedDates)) {
            $this->addError('dates', 'Sélectionnez au moins 1 date.');
            return;
        }

        if (count($this->selectedDates) > 3) {
            $this->addError('dates', 'Maximum 3 dates.');
            return;
        }

        $bookingRequest = BookingRequest::findOrFail($this->bookingRequestId);

        // Vérifier ownership
        $userId = auth()->user()->id;
        $user = \App\Models\User::find($userId);
        $tattooer = $user ? $user->tattooer : null;

        abort_unless(
            $tattooer && $bookingRequest->bookable_id === $tattooer->id
            && $bookingRequest->bookable_type === 'App\\Models\\Tattooer',
            403
        );

        // Nettoyer les dates (garder seulement date + period)
        $cleanDates = collect($this->selectedDates)->map(function ($d) {
            return [
                'date' => $d['date'] ?? $d,
                'period' => $d['period'] ?? null,
            ];
        })->toArray();

        // Mettre à jour la booking request
        $bookingRequest->update([
            'proposed_dates' => $cleanDates,
            'client_selected_dates' => null,
            'client_dates_selected_at' => null,
            'date_selection_deadline' => now()->addHours(48),
        ]);

        // Message système dans le chat
        $conversation = $bookingRequest->conversation;
        if ($conversation) {
            $datesFormatted = collect($cleanDates)->map(function ($d) {
                $date = Carbon::parse($d['date'])->translatedFormat('l d F Y');
                $period = match ($d['period'] ?? '') {
                    'morning' => 'matin',
                    'afternoon' => 'après-midi',
                    default => '',
                };
                return $date . ($period ? " ($period)" : '');
            })->join(', ');

            $conversation->messages()->create([
                'sender_id' => auth()->user()->id,
                'sender_type' => 'tattooer',
                'content' => "📅 Nouvelles dates proposées : {$datesFormatted}. Merci de sélectionner votre préférence.",
                'read_by_tattooer_at' => now(),
            ]);

            $conversation->update(['last_message_at' => now()]);
        }

        // Notifier le client
        $client = $bookingRequest->client?->user;
        if ($client) {
            $client->notifications()->create([
                'id' => Str::uuid(),
                'type' => 'App\\Notifications\\NewDatesProposedNotification',
                'data' => [
                    'title' => 'Nouvelles dates proposées',
                    'message' => "L'artiste vous propose de nouvelles dates pour votre projet.",
                    'booking_request_id' => $bookingRequest->id,
                ],
            ]);
        }

        $this->closeModal();

        // Rafraîchir la page pour voir le nouveau message
        $this->dispatch('message-sent');

        session()->flash('success', 'Nouvelles dates proposées au client !');

        // Forcer le rechargement si le chat ne se rafraîchit pas via Livewire
        return redirect()->back()->with('success', 'Nouvelles dates proposées au client !');
    }

    public function render()
    {
        return view('livewire.tattooer.repropose-dates-modal');
    }
}

<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use App\Models\BookingRequest;
use App\Models\Appointment;
use App\Models\CalendarEvent;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use App\Notifications\AppointmentConfirmedNotification;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class BookingQuickCreate extends Component
{
    public string $appointmentTitle = '';
    public string $appointmentDate = '';
    public string $appointmentStartTime = '';
    public string $appointmentEndTime = '';
    public ?int $currentBookingRequestId = null;
    public bool $showModal = false;

    #[On('open-booking-modal')]
    public function openBookingModal(string $date, string $period, int $bookingRequestId): void
    {
        $bookingRequest = BookingRequest::with('client.user')->findOrFail($bookingRequestId);
        $client = $bookingRequest->client;

        // Pseudo du client (ou nom si pas de pseudo)
        $pseudo = $client->user->pseudo ?? $client->user->name;

        $this->appointmentTitle = "Tattoo → {$pseudo}";
        $this->appointmentDate = $date;
        $this->currentBookingRequestId = $bookingRequestId;

        // Pré-remplir heure selon période
        $this->appointmentStartTime = match($period) {
            'morning' => '09:00',
            'afternoon' => '14:00',
            'evening' => '18:00',
            default => '10:00',
        };
        $this->appointmentEndTime = match($period) {
            'morning' => '12:00',
            'afternoon' => '17:00',
            'evening' => '20:00',
            default => '13:00',
        };

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->appointmentTitle = '';
        $this->appointmentDate = '';
        $this->appointmentStartTime = '';
        $this->appointmentEndTime = '';
        $this->currentBookingRequestId = null;
    }

    public function createAppointmentFromBooking(): void
    {
        $this->validate([
            'appointmentStartTime' => 'required|date_format:H:i',
            'appointmentEndTime' => 'required|date_format:H:i|after:appointmentStartTime',
        ]);

        $bookingRequest = BookingRequest::findOrFail($this->currentBookingRequestId);

        DB::transaction(function () use ($bookingRequest) {
            // 1. Créer l'Appointment
            $startDatetime = Carbon::parse($this->appointmentDate . ' ' . $this->appointmentStartTime);
            $endDatetime = Carbon::parse($this->appointmentDate . ' ' . $this->appointmentEndTime);

            $appointment = Appointment::create([
                'booking_request_id' => $bookingRequest->id,
                'bookable_type' => $bookingRequest->bookable_type,
                'bookable_id' => $bookingRequest->bookable_id,
                'client_id' => $bookingRequest->client_id,
                'title' => $this->appointmentTitle,
                'start_datetime' => $startDatetime,
                'end_datetime' => $endDatetime,
                'status' => AppointmentStatus::SCHEDULED,
            ]);

            // 2. Créer l'événement dans le calendrier FullCalendar
            CalendarEvent::create([
                'owner_type' => $bookingRequest->bookable_type,
                'owner_id' => $bookingRequest->bookable_id,
                'type' => 'appointment',
                'title' => $this->appointmentTitle,
                'start_datetime' => $startDatetime,
                'end_datetime' => $endDatetime,
                'appointment_id' => $appointment->id,
                'color' => '#D4B59E', // beige-peau
            ]);

            // 3. Transition BookingRequest → DATE_CONFIRMED
            $bookingRequest->update([
                'status' => BookingRequestStatus::DATE_CONFIRMED,
                'confirmed_date' => $this->appointmentDate,
                'confirmed_period' => $this->appointmentStartTime < '13:00' ? 'morning' : 'afternoon',
            ]);

            // 4. Message système dans le chat
            $conversation = $bookingRequest->conversation;
            if ($conversation) {
                $conversation->messages()->create([
                    'sender_type' => 'system',
                    'content' => "✅ Rendez-vous confirmé le {$startDatetime->translatedFormat('l d F Y')} de {$startDatetime->format('H:i')} à {$endDatetime->format('H:i')}.",
                ]);

                // Envoyer le formulaire de consentement dans le chat
                $conversation->messages()->create([
                    'sender_type' => 'system',
                    'sender_id' => null,
                    'content' => '[CONSENT_FORM:' . $bookingRequest->id . ']',
                ]);
            }

            // Notifier le client que son rendez-vous a été confirmé
            if ($bookingRequest->client?->user) {
                $bookingRequest->client->user->notify(new AppointmentConfirmedNotification($bookingRequest));
            }
        });

        // Reset form
        $this->closeModal();

        session()->flash('success', 'Rendez-vous créé avec succès !');

        // Refresh la page ou le composant
        $this->dispatch('appointment-created');
    }

    public function render()
    {
        return view('livewire.tattooer.booking-quick-create');
    }
}

<?php

namespace App\Livewire\Tattooer;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\CalendarEvent;
use App\Models\BookingRequest;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use App\Notifications\BookingModifiedNotification;
use App\Notifications\BookingCancelledNotification;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\DB;

class AppointmentDetailModal extends Component
{
    public ?Appointment $appointment = null;
    public bool $showModal = false;
    public bool $showCancelConfirm = false;
    public bool $editMode = false;
    
    // Champs édition
    public string $editDate = '';
    public string $editStartTime = '';
    public string $editEndTime = '';
    public string $cancelReason = '';

    #[On('open-appointment-detail')]
    public function openAppointmentDetail(int $appointmentId): void
    {
        $this->appointment = Appointment::with('bookingRequest.client.user')->findOrFail($appointmentId);
        $this->showModal = true;
        $this->resetEditFields();
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->showCancelConfirm = false;
        $this->editMode = false;
        $this->resetEditFields();
    }

    private function resetEditFields(): void
    {
        if ($this->appointment) {
            $this->editDate = $this->appointment->start_datetime->format('Y-m-d');
            $this->editStartTime = $this->appointment->start_datetime->format('H:i');
            $this->editEndTime = $this->appointment->end_datetime->format('H:i');
        }
        $this->cancelReason = '';
    }

    public function updateAppointment(): void
    {
        $this->validate([
            'editDate' => 'required|date|after:today',
            'editStartTime' => 'required|date_format:H:i',
            'editEndTime' => 'required|date_format:H:i|after:editStartTime',
        ]);

        $oldDatetime = $this->appointment->start_datetime->translatedFormat('l d F Y H:i');
        
        $newStart = Carbon::parse($this->editDate . ' ' . $this->editStartTime);
        $newEnd = Carbon::parse($this->editDate . ' ' . $this->editEndTime);

        DB::transaction(function () use ($newStart, $newEnd, $oldDatetime) {
            $this->appointment->update([
                'start_datetime' => $newStart,
                'end_datetime' => $newEnd,
            ]);

            // Mettre à jour CalendarEvent associé
            CalendarEvent::where('appointment_id', $this->appointment->id)->update([
                'start_datetime' => $newStart,
                'end_datetime' => $newEnd,
            ]);

            // Mettre à jour BookingRequest
            $this->appointment->bookingRequest->update([
                'confirmed_date' => $this->editDate,
            ]);

            // Message système dans le chat
            $conversation = $this->appointment->bookingRequest->conversation;
            if ($conversation) {
                $conversation->messages()->create([
                    'sender_type' => 'system',
                    'content' => "📅 Rendez-vous modifié : nouveau créneau le {$newStart->translatedFormat('l d F Y')} de {$newStart->format('H:i')} à {$newEnd->format('H:i')}.",
                ]);
            }

            // Notifier le client que le rendez-vous a été modifié
            $bookingRequest = $this->appointment->bookingRequest;
            if ($bookingRequest?->client?->user) {
                $bookingRequest->client->user->notify(new BookingModifiedNotification($bookingRequest));
            }
        });

        session()->flash('success', 'Rendez-vous modifié !');
        $this->dispatch('appointment-updated');
        $this->closeModal();
    }

    public function openCancelConfirm(): void
    {
        $this->showCancelConfirm = true;
    }

    public function cancelAppointment(): void
    {
        DB::transaction(function () {
            // 1. Annuler l'Appointment
            $this->appointment->update(['status' => AppointmentStatus::CANCELLED]);

            // 2. Supprimer le CalendarEvent
            CalendarEvent::where('appointment_id', $this->appointment->id)->delete();

            // 3. BookingRequest → CANCELLED
            $bookingRequest = $this->appointment->bookingRequest;
            $bookingRequest->update([
                'status' => BookingRequestStatus::CANCELLED,
                'cancellation_reason' => $this->cancelReason ?: 'Annulé par le tatoueur',
            ]);

            // TODO: Calcul remboursement selon politique (existant)

            // 4. Message système chat
            $conversation = $bookingRequest->conversation;
            if ($conversation) {
                $conversation->messages()->create([
                    'sender_type' => 'system',
                    'content' => "❌ Rendez-vous annulé" . ($this->cancelReason ? " : {$this->cancelReason}" : "."),
                ]);
            }

            // Notifier le client que le rendez-vous a été annulé
            if ($bookingRequest?->client?->user) {
                $bookingRequest->client->user->notify(new BookingCancelledNotification($bookingRequest));
            }
        });

        $this->showCancelConfirm = false;
        $this->showModal = false;
        session()->flash('success', 'Rendez-vous annulé.');
        $this->dispatch('appointment-cancelled');
    }

    public function render()
    {
        return view('livewire.tattooer.appointment-detail-modal');
    }
}

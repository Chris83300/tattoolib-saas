<?php

namespace App\Livewire\Tattooer;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\CalendarEvent;
use App\Notifications\AppointmentConfirmedNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickBookingModal extends Component
{
    public bool $showQuickBookingModal = false;
    public string $appointmentStartTime = '';
    public string $appointmentEndTime = '';
    public ?int $currentBookingRequestId = null;
    public ?BookingRequest $bookingRequest = null;
    public string $appointmentTitle = '';
    public string $appointmentDate = '';
    public string $appointmentDateDisplay = '';
    public string $startTime = '';
    public string $endTime = '';
    public bool $isSubmitting = false;

    protected $rules = [
        'startTime' => 'required|date_format:H:i',
        'endTime'   => 'required|date_format:H:i|after:startTime',
    ];

    protected $messages = [
        'startTime.required'    => 'L\'heure de début est obligatoire.',
        'endTime.required'      => 'L\'heure de fin est obligatoire.',
        'endTime.after'         => 'L\'heure de fin doit être après l\'heure de début.',
    ];

    #[On('open-booking-modal')]
    #[On('open-booking-from-chat')]
    public function openBookingModal(string $date, string $period, int $bookingRequestId): void
    {
        $this->currentBookingRequestId = $bookingRequestId;
        $this->showQuickBookingModal = true;

        // Debug logs
        Log::info('🎯 QuickBookingModal.openBookingModal called', [
            'bookingRequestId' => $bookingRequestId,
            'date' => $date,
            'period' => $period,
            'auth_id' => auth()->id(),
        ]);

        // Récupérer l'artisan connecté (tattooer ou piercer)
        $artisan = auth()->user()->tattooer ?? auth()->user()->piercer;

        if (!$artisan) {
            throw new \Exception('Aucun artisan (tattooer/piercer) trouvé');
        }

        // Récupérer la demande de réservation
        $this->bookingRequest = BookingRequest::with(['client.user'])->findOrFail($bookingRequestId);

        // Debug: Vérifier la demande
        Log::info('📋 BookingRequest found', [
            'id' => $this->bookingRequest->id,
            'client_id' => $this->bookingRequest->client_id,
            'status' => $this->bookingRequest->status->value,
        ]);

        $this->currentBookingRequestId = $bookingRequestId;

        // Titre pré-rempli avec le pseudo du client
        $clientPseudo = $this->bookingRequest->client?->user?->pseudo
            ?? $this->bookingRequest->client?->user?->name
            ?? 'Client';

        // Adapter le titre selon le type d'artisan
        if (auth()->user()->piercer) {
            $this->appointmentTitle = "Piercing → {$clientPseudo}";
        } else {
            $this->appointmentTitle = "Tattoo → {$clientPseudo}";
        }

        // Date verrouillée
        $this->appointmentDate = $date;
        $this->appointmentDateDisplay = Carbon::parse($date)->translatedFormat('l d F Y');

        // Heures pré-remplies selon la période
        $this->startTime = match($period) {
            'morning'   => '09:00',
            'afternoon' => '14:00',
            'evening'   => '18:00',
            default     => '10:00',
        };
        $this->endTime = match($period) {
            'morning'   => '12:00',
            'afternoon' => '18:00',
            'evening'   => '21:00',
            default     => '12:00',
        };

        // Debug: Vérifier les propriétés
        Log::info('🔧 Modal properties set', [
            'showModal' => true,
            'appointmentTitle' => $this->appointmentTitle,
            'appointmentDate' => $this->appointmentDate,
            'startTime' => $this->startTime,
            'endTime' => $this->endTime,
        ]);

        // Ouvrir la modal
        $this->showQuickBookingModal = true;

        Log::info('✅ QuickBookingModal should be open now');
    }

    public function createAppointment(): void
    {
        if ($this->isSubmitting) return;
        $this->isSubmitting = true;

        try {
        $this->validate();

        $artisan = auth()->user()->tattooer ?? auth()->user()->piercer;

        if (!$artisan) {
            throw new \Exception('Aucun artisan (tattooer/piercer) trouvé');
        }

        $startDatetime = Carbon::parse("{$this->appointmentDate} {$this->startTime}");
        $endDatetime = Carbon::parse("{$this->appointmentDate} {$this->endTime}");
        $durationMinutes = $startDatetime->diffInMinutes($endDatetime);

        DB::transaction(function () use ($artisan, $startDatetime, $endDatetime, $durationMinutes) {

            // 1. Créer l'Appointment
            $appointment = Appointment::create([
                'booking_request_id' => $this->bookingRequest->id,
                'bookable_type'      => $artisan->getMorphClass(),
                'bookable_id'        => $artisan->id,
                'client_id'          => $this->bookingRequest->client_id,
                'start_datetime'     => $startDatetime,
                'end_datetime'       => $endDatetime,
                'duration_minutes'   => $durationMinutes,
                'title'             => $this->appointmentTitle,
                'status'             => \App\Enums\AppointmentStatus::SCHEDULED,
                'deposit_amount'     => $this->bookingRequest->total_deposit_amount ?? 0,
                'total_price'        => $this->bookingRequest->estimated_total_price
                                     ?? $this->bookingRequest->price_estimate_max ?? 0,
            ]);

            // 2. Créer le CalendarEvent associé
            CalendarEvent::create([
                'bookable_type'  => $artisan->getMorphClass(),
                'bookable_id'    => $artisan->id,
                'type'           => 'appointment',
                'title'          => $this->appointmentTitle,
                'appointment_id' => $appointment->id,
                'start_datetime' => $startDatetime,
                'end_datetime'   => $endDatetime,
                'color'          => '#06D6A0', // vert-succes
            ]);

            // 3. Mettre à jour la BookingRequest
            $this->bookingRequest->update([
                'status'                       => 'date_confirmed',
                'appointment_datetime'         => $startDatetime,
                'scheduled_start_time'          => $this->startTime,
                'scheduled_end_time'            => $this->endTime,
                'scheduled_duration_minutes'    => $durationMinutes,
            ]);

            // 4. Message système dans le chat
            $conversation = $this->bookingRequest->conversation;
            if ($conversation) {
                $conversation->messages()->create([
                    'sender_type' => 'system',
                    'sender_id'   => null,
                    'content'     => "✅ Rendez-vous confirmé !\n📅 {$startDatetime->translatedFormat('l d F Y')}\n🕐 {$this->startTime} → {$this->endTime}\nVous recevrez un rappel avant le jour J.",
                ]);

                // Envoyer le formulaire de consentement dans le chat
                $conversation->messages()->create([
                    'sender_type' => 'system',
                    'sender_id'   => null,
                    'content'     => '[CONSENT_FORM:' . $this->bookingRequest->id . ']',
                ]);
            }

            // 5. Notifier le client que son rendez-vous a été confirmé
            if ($this->bookingRequest->client?->user) {
                $this->bookingRequest->client->user->notify(new AppointmentConfirmedNotification($this->bookingRequest));
            }
        });

        $this->showQuickBookingModal = false;
        $this->resetForm();
        session()->flash('success', '✅ Rendez-vous créé avec succès !');

        // Rafraîchir le calendrier FullCalendar
        $this->dispatch('refresh-quick-booking');
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function resetForm(): void
    {
        $this->reset([
            'appointmentTitle',
            'appointmentDate',
            'appointmentDateDisplay',
            'startTime',
            'endTime',
            'currentBookingRequestId',
            'bookingRequest',
        ]);
    }

    public function render()
    {
        return view('livewire.tattooer.quick-booking-modal');
    }
}

<?php

namespace App\Livewire\Tattooer;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class QuickBookingModal extends Component
{
    public bool $showModal = false;
    public ?int $bookingRequestId = null;
    public ?BookingRequest $bookingRequest = null;
    
    public string $appointmentTitle = '';
    public string $appointmentDate = '';
    public string $appointmentDateDisplay = '';
    public string $startTime = '';
    public string $endTime = '';

    protected $rules = [
        'startTime' => 'required|date_format:H:i',
        'endTime'   => 'required|date_format:H:i|after:startTime',
    ];

    protected $messages = [
        'startTime.required'    => 'L\'heure de début est obligatoire.',
        'endTime.required'      => 'L\'heure de fin est obligatoire.',
        'endTime.after'         => 'L\'heure de fin doit être après l\'heure de début.',
    ];

    #[On('open-booking-from-chat')]
    public function openFromChat(int $bookingRequestId, string $date, string $period): void
    {
        $tattooer = auth()->user()->tattooer;
        
        $this->bookingRequest = BookingRequest::with(['client.user'])
            ->where('id', $bookingRequestId)
            ->where('bookable_id', $tattooer->id)
            ->where('bookable_type', $tattooer->getMorphClass())
            ->firstOrFail();

        $this->bookingRequestId = $bookingRequestId;

        // Titre pré-rempli avec le pseudo du client
        $clientPseudo = $this->bookingRequest->client?->user?->pseudo 
            ?? $this->bookingRequest->client?->user?->name 
            ?? 'Client';
        $this->appointmentTitle = "Tattoo → {$clientPseudo}";
        
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
            default     => '13:00',
        };

        $this->showModal = true;
    }

    public function createAppointment(): void
    {
        $this->validate();

        $tattooer = auth()->user()->tattooer;
        $startDatetime = Carbon::parse("{$this->appointmentDate} {$this->startTime}");
        $endDatetime = Carbon::parse("{$this->appointmentDate} {$this->endTime}");
        $durationMinutes = $startDatetime->diffInMinutes($endDatetime);

        DB::transaction(function () use ($tattooer, $startDatetime, $endDatetime, $durationMinutes) {
            
            // 1. Créer l'Appointment
            $appointment = Appointment::create([
                'booking_request_id' => $this->bookingRequest->id,
                'bookable_type'      => $tattooer->getMorphClass(),
                'bookable_id'        => $tattooer->id,
                'client_id'          => $this->bookingRequest->client_id,
                'start_datetime'     => $startDatetime,
                'end_datetime'       => $endDatetime,
                'duration_minutes'   => $durationMinutes,
                'status'             => 'confirmed',
                'deposit_amount'     => $this->bookingRequest->total_deposit_amount ?? 0,
                'total_price'        => $this->bookingRequest->estimated_total_price 
                                     ?? $this->bookingRequest->price_estimate_max ?? 0,
            ]);

            // 2. Créer le CalendarEvent associé
            CalendarEvent::create([
                'bookable_type'  => $tattooer->getMorphClass(),
                'bookable_id'    => $tattooer->id,
                'type'           => 'appointment',
                'title'          => $this->appointmentTitle,
                'appointment_id' => $appointment->id,
                'start_datetime' => $startDatetime,
                'end_datetime'   => $endDatetime,
                'color'          => '#06D6A0', // vert-succes
            ]);

            // 3. Mettre à jour la BookingRequest
            $this->bookingRequest->update([
                'status'                       => 'confirmed',
                'appointment_datetime'         => $startDatetime,
                'appointment_duration_minutes'  => $durationMinutes,
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
            }

            // 5. Notification au client (utiliser les notifications de rappel existantes)
            // Chercher les notifications existantes :
            // find app/Notifications -name "*Appointment*" -o -name "*Reminder*" -o -name "*Rappel*" 2>/dev/null
            // et déclencher AppointmentConfirmedNotification si elle existe.
            // Sinon TODO: créer la notification.
        });

        $this->showModal = false;
        session()->flash('success', '✅ Rendez-vous créé avec succès !');
        
        // Rafraîchir le calendrier FullCalendar
        $this->dispatch('appointment-created');
    }

    public function render()
    {
        return view('livewire.tattooer.quick-booking-modal');
    }
}

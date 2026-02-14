<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentCompletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public ?BookingRequest $bookingRequest = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $artistName = $this->bookingRequest?->bookable?->user?->display_name ?? 'votre artiste';
        $date = $this->appointment->start_datetime?->translatedFormat('d/m/Y à H:i') ?? 'N/A';

        return (new MailMessage)
            ->subject("✅ Votre rendez-vous est terminé !")
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("Votre rendez-vous tattoo avec {$artistName} le {$date} est marqué comme terminé.")
            ->line("Comment ça s'est passé ? Votre avis compte pour la communauté !")
            ->action('Laisser un avis', url('/client/reviews/create?booking=' . $this->bookingRequest?->id))
            ->line("Merci d'utiliser Ink&Pik !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'appointment_completed',
            'appointment_id' => $this->appointment->id,
            'booking_request_id' => $this->bookingRequest?->id,
            'message' => "✅ Votre RDV est terminé ! Laissez un avis.",
            'action_url' => '/client/reviews/create?booking=' . $this->bookingRequest?->id,
        ];
    }
}

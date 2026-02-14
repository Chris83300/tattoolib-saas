<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private BookingRequest $bookingRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $artistName = $this->bookingRequest->bookable?->user?->display_name ?? 'votre artiste';
        $date = $this->bookingRequest->appointment_datetime?->translatedFormat('d/m/Y à H:i');

        return (new MailMessage)
            ->subject("📅 Rendez-vous confirmé !")
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("🎉 Super ! Votre rendez-vous est confirmé.")
            ->line("👤 Artiste : {$artistName}")
            ->line("📅 Date : {$date}")
            ->line("📍 Lieu : " . ($this->bookingRequest->bookable?->address ?? 'À confirmer'))
            ->action("Voir les détails", route('client.booking-request.show', $this->bookingRequest))
            ->line("N'hésitez pas à contacter l'artiste via le chat si vous avez des questions.")
            ->line("À très bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'appointment_confirmed',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "📅 Rendez-vous confirmé",
            'action_url' => '/client/booking-requests/' . $this->bookingRequest->id,
            'appointment_datetime' => $this->bookingRequest->appointment_datetime,
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingModifiedNotification extends Notification implements ShouldQueue
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

        return (new MailMessage)
            ->subject("📝 Votre réservation a été modifiée")
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("{$artistName} a apporté des modifications à votre réservation.")
            ->action("Voir les détails", route('client.booking-request.show', $this->bookingRequest))
            ->line("N'hésitez pas à contacter l'artiste via le chat si vous avez des questions.")
            ->line("À très bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_modified',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "📝 Réservation modifiée",
            'action_url' => '/client/booking-requests/' . $this->bookingRequest->id,
        ];
    }
}

<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification implements ShouldQueue
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
        $artistName = $this->bookingRequest->bookable?->user?->display_name ?? 'l\'artiste';

        return (new MailMessage)
            ->subject("❌ Réservation annulée")
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("Votre réservation avec {$artistName} a été annulée.")
            ->when($this->bookingRequest->cancellation_reason, function ($mail) {
                return $mail->line("Raison : {$this->bookingRequest->cancellation_reason}");
            })
            ->action("Voir les détails", route('client.booking-requests'))
            ->line("Nous espérons vous revoir bientôt sur Ink&Pik.")
            ->line("À très bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_cancelled',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "❌ Réservation annulée",
            'action_url' => '/client/booking-requests',
            'reason' => $this->bookingRequest->cancellation_reason,
        ];
    }
}

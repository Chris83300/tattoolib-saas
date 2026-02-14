<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private BookingRequest $bookingRequest,
        private ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $artistName = $this->bookingRequest->bookable?->user?->display_name ?? 'l\'artiste';

        return (new MailMessage)
            ->subject("❌ Votre demande n'a pas été retenue")
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("Nous sommes désolés, mais votre demande de réservation auprès de {$artistName} n'a pas pu être acceptée.")
            ->when($this->reason, function ($mail) {
                return $mail->line("Raison : {$this->reason}");
            })
            ->line("🎨 Mais ne vous découragez pas ! Ink&Pik regorge d'artistes talentueux.")
            ->action("Découvrir d'autres artistes", route('marketplace.index'))
            ->line("Nous vous souhaitons une excellente recherche.")
            ->line("À très bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'booking_rejected',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "❌ Demande refusée par l'artiste",
            'action_url' => '/marketplace',
            'artist_name' => $this->bookingRequest->bookable?->user?->display_name,
            'reason' => $this->reason,
        ];
    }
}

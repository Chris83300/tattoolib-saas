<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DesignSentNotification extends Notification implements ShouldQueue
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
            ->subject("🎨 Design envoyé par {$artistName}")
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("🎉 Super ! {$artistName} a préparé votre design.")
            ->line("📋 Vous pouvez le consulter directement dans la conversation.")
            ->action("Voir le design", route('client.chat', $this->bookingRequest->conversation))
            ->line("N'hésitez pas à donner votre avis dans le chat. Une fois validé, vous pourrez fixer la date du rendez-vous.")
            ->line("À très bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'design_sent',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "🎨 Design envoyé par l'artiste",
            'action_url' => '/client/chat/' . $this->bookingRequest->conversation_id,
            'artist_name' => $this->bookingRequest->bookable?->user?->display_name,
        ];
    }
}

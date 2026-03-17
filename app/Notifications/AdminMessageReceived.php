<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminMessageReceived extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string $message,
        public ?BookingRequest $booking = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $name = $notifiable->name ?? $notifiable->pseudo ?? '';
        $mail = (new MailMessage)
            ->subject("Message de l'équipe Ink&Pik")
            ->greeting("Bonjour {$name},")
            ->line("Vous avez reçu un message de l'équipe Ink&Pik" .
                ($this->booking ? " concernant la demande #{$this->booking->id}" : '') . '.')
            ->line('"' . $this->message . '"');

        return $mail->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'admin_message',
            'message'    => $this->message,
            'booking_id' => $this->booking?->id,
        ];
    }
}

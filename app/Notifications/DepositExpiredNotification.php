<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format($this->bookingRequest->total_deposit_amount, 2);

        return (new MailMessage)
            ->subject("❌ Délai de paiement acompte dépassé")
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("Le délai de paiement de l'acompte de {$amount}€ est dépassé.")
            ->line("La demande a été automatiquement annulée.")
            ->line("Vous pouvez soumettre une nouvelle demande à tout moment.")
            ->action('Nouvelle demande', url('/marketplace'))
            ->line("Merci de votre compréhension.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deposit_expired',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "❌ Délai acompte expiré",
            'action_url' => '/marketplace',
        ];
    }
}

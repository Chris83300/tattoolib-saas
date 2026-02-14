<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositDeadlineApproachingNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $deadline = $this->bookingRequest->deposit_deadline?->translatedFormat('d/m/Y à H:i');
        $amount = number_format($this->bookingRequest->total_deposit_amount, 2);

        return (new MailMessage)
            ->subject("⏰ Votre acompte expire bientôt")
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("Votre acompte de {$amount}€ expire le {$deadline}.")
            ->line("Régler rapidement pour confirmer votre réservation.")
            ->action('Payer maintenant', url('/client/deposit/payment/' . $this->bookingRequest->id))
            ->line("Après paiement, vous pourrez choisir votre date de RDV.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deposit_deadline_approaching',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "⏰ Deadline acompte approchant",
            'action_url' => '/client/deposit/payment/' . $this->bookingRequest->id,
        ];
    }
}

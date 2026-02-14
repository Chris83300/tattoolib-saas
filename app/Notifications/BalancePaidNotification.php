<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BalancePaidNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format($this->bookingRequest->balance_amount, 2);
        $clientName = $this->bookingRequest->client?->display_name ?? 'Un client';

        return (new MailMessage)
            ->subject("💰 Solde payé - Prestation complète !")
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("Le solde de {$amount}€ a été payé par {$clientName}.")
            ->line("La prestation est maintenant complètement terminée.")
            ->line("Vous pouvez consulter les détails dans votre espace.")
            ->action('Voir les détails', url('/tattooer/bookings/' . $this->bookingRequest->id))
            ->line("Merci d'utiliser Ink&Pik !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'balance_paid',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "💰 Solde payé par {$this->bookingRequest->client?->display_name}",
            'action_url' => '/tattooer/bookings/' . $this->bookingRequest->id,
        ];
    }
}

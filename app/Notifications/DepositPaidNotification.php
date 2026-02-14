<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositPaidNotification extends Notification implements ShouldQueue
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
        $clientName = $this->bookingRequest->client?->display_name ?? 'Un client';
        $amount = number_format($this->bookingRequest->total_deposit_amount, 2, ',', ' ');

        return (new MailMessage)
            ->subject("💰 Acompte reçu - {$clientName}")
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("✅ Super nouvelle ! L'acompte a été payé.")
            ->line("👤 Client : {$clientName}")
            ->line("💰 Montant reçu : {$amount}€")
            ->line("📋 Réservation : #{$this->bookingRequest->id}")
            ->action("Voir les détails", route('tattooer.request-show', $this->bookingRequest))
            ->line("Vous pouvez maintenant envoyer le design et convenir de la date du rendez-vous.")
            ->line("À très bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deposit_paid',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "💰 Acompte de {$this->bookingRequest->total_deposit_amount}€ reçu",
            'action_url' => '/tattooer/requests/' . $this->bookingRequest->id,
            'client_name' => $this->bookingRequest->client?->display_name,
        ];
    }
}

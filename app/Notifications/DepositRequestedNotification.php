<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositRequestedNotification extends Notification implements ShouldQueue
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
        $amount = number_format($this->bookingRequest->total_deposit_amount, 2, ',', ' ');
        $deadline = $this->bookingRequest->client_payment_deadline?->translatedFormat('d/m/Y');

        return (new MailMessage)
            ->subject("💰 Acompte demandé pour votre réservation")
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("🎉 Votre demande de réservation a été acceptée par {$artistName} !")
            ->line("Pour confirmer votre rendez-vous, un acompte de {$amount}€ est requis.")
            ->line("📅 Date limite : {$deadline}")
            ->action("Payer l'acompte", route('client.deposit.payment', $this->bookingRequest))
            ->line("Une fois l'acompte payé, vous pourrez convenir de la date exacte du rendez-vous.")
            ->line("À très bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deposit_requested',
            'booking_request_id' => $this->bookingRequest->id,
            'message' => "💰 Acompte de {$this->bookingRequest->total_deposit_amount}€ demandé",
            'action_url' => '/client/deposit-payment/' . $this->bookingRequest->id,
            'deadline' => $this->bookingRequest->client_payment_deadline,
        ];
    }
}

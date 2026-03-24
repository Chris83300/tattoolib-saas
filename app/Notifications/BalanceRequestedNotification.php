<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BalanceRequestedNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected BookingRequest $booking,
        protected float $finalPrice,
        protected float $remaining,
    ) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $paymentUrl = route('client.balance.show', ['bookingRequest' => $this->booking->id]);
        $artistName = $this->booking->bookable?->user?->name ?? 'Votre artiste';
        $depositPaid = round($this->finalPrice - $this->remaining, 2);

        return (new MailMessage)
            ->subject("Ink&Pik — Paiement du solde ({$this->remaining} €)")
            ->greeting("Bonjour {$notifiable->name} !")
            ->line("{$artistName} vous demande le paiement du solde de votre prestation.")
            ->line("**Prix définitif : " . number_format($this->finalPrice, 2, ',', ' ') . " €**")
            ->line("Acompte déjà versé : " . number_format($depositPaid, 2, ',', ' ') . " €")
            ->line("**Reste à payer : " . number_format($this->remaining, 2, ',', ' ') . " €**")
            ->action("Payer le solde ({$this->remaining} €)", $paymentUrl)
            ->line('Le paiement est sécurisé via Stripe (3D Secure).')
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'balance_requested',
            'booking_id' => $this->booking->id,
            'final_price' => $this->finalPrice,
            'remaining' => $this->remaining,
            'message' => "Paiement du solde demandé : " . number_format($this->remaining, 2, ',', ' ') . " € à régler.",
        ];
    }
}

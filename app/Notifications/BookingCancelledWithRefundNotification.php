<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledWithRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private BookingRequest $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $cancelledByLabel = match ($this->booking->cancelled_by) {
            'client' => 'le client',
            'artist' => "l'artiste",
            default  => 'un administrateur',
        };

        $refundLine = $this->booking->refund_amount > 0
            ? "Un remboursement de {$this->booking->refund_amount} € ({$this->booking->refund_percent}%) a été initié."
            : "Aucun remboursement n'est prévu selon les conditions d'annulation.";

        return (new MailMessage)
            ->subject("❌ Demande #{$this->booking->id} annulée")
            ->greeting('Bonjour,')
            ->line("La demande de réservation #{$this->booking->id} a été annulée par {$cancelledByLabel}.")
            ->when($this->booking->cancellation_reason, fn ($mail) =>
                $mail->line("Motif : {$this->booking->cancellation_reason}")
            )
            ->line($refundLine)
            ->line("Le remboursement apparaîtra sur le compte du client sous 5 à 10 jours ouvrés.")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'booking_cancelled_with_refund',
            'booking_id'     => $this->booking->id,
            'cancelled_by'   => $this->booking->cancelled_by,
            'refund_amount'  => $this->booking->refund_amount,
            'refund_percent' => $this->booking->refund_percent,
            'message'        => "Demande #{$this->booking->id} annulée — remboursement {$this->booking->refund_percent}%",
        ];
    }
}

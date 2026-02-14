<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepositExpiredBookingCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("❌ Demande annulée — acompte non réglé dans les délais")
            ->greeting("Bonjour {$notifiable->display_name}")
            ->line("La demande de tattoo n'a pas reçu l'acompte dans le délai imparti.")
            ->line("La demande et les échanges associés ont été supprimés automatiquement.")
            ->line("Vous pouvez soumettre une nouvelle demande à tout moment sur Ink&Pik.")
            ->action('Retour à la marketplace', url('/marketplace'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deposit_expired_booking_cancelled',
            'message' => "❌ Une demande a été annulée (acompte non payé dans les délais).",
        ];
    }
}

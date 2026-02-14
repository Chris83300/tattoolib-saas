<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $artistName = $this->appointment->bookingRequest?->bookable?->user?->display_name ?? 'votre artiste';
        $date = $this->appointment->completed_at->translatedFormat('d/m/Y');

        return (new MailMessage)
            ->subject("⭐ Partagez votre expérience !")
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("Votre tattoo du {$date} est terminé depuis 2 semaines.")
            ->line("Comment s'est passée votre séance avec {$artistName} ?")
            ->line("Votre avis compte énormément et aide d'autres clients à choisir.")
            ->action('Laisser un avis', url('/client/review?appointment=' . $this->appointment->id))
            ->line("Merci de votre confiance et à bientôt sur Ink&Pik !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'request_review',
            'appointment_id' => $this->appointment->id,
            'message' => "⭐ Demande d'avis J+14",
            'action_url' => '/client/review?appointment=' . $this->appointment->id,
        ];
    }
}

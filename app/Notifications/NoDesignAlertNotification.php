<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoDesignAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public int $daysBefore = 0
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $date = $this->appointment->start_datetime->translatedFormat('d/m/Y à H:i');
        $clientName = $this->appointment->bookingRequest?->client?->display_name ?? 'Un client';
        
        $subject = match($this->daysBefore) {
            7 => "🎨 Alerte design : aucun design reçu pour RDV dans 7 jours",
            3 => "🎨 Alerte design : aucun design reçu pour RDV dans 3 jours",
            default => "🎨 Alerte design : aucun design reçu"
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("Aucun design n'a été envoyé pour le RDV du {$date}.")
            ->line("Merci de l'envoyer dès que possible pour ne pas retarder le projet.")
            ->line("Le client {$clientName} attend impatiemment de voir vos créations.")
            ->action('Envoyer un design', url('/tattooer/designs'))
            ->line("Merci de votre réactivité !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'no_design_alert',
            'appointment_id' => $this->appointment->id,
            'message' => "🎨 Alerte design J-{$this->daysBefore}",
            'action_url' => '/tattooer/designs',
        ];
    }
}

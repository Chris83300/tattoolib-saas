<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public int $daysBefore = 0
    ) {}

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
        $date = $this->appointment->start_datetime->translatedFormat('d/m/Y à H:i');
        $artistName = $this->appointment->bookingRequest?->bookable?->user?->display_name ?? 'votre artiste';

        $subject = match($this->daysBefore) {
            7 => "📅 Rappel : votre rendez-vous tattoo dans 7 jours",
            2 => "📅 Rappel : votre rendez-vous tattoo dans 2 jours",
            0 => "📅 Rappel : votre rendez-vous tattoo est aujourd'hui !",
            default => "📅 Rappel : votre rendez-vous tattoo"
        };

        $message = match($this->daysBefore) {
            7 => "Votre rendez-vous avec {$artistName} est prévu le {$date}.",
            2 => "Votre rendez-vous avec {$artistName} est prévu le {$date}.",
            0 => "Votre rendez-vous avec {$artistName} est aujourd'hui à {$date} !",
            default => "Votre rendez-vous avec {$artistName} est prévu le {$date}."
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line($message)
            ->line("Pensez à confirmer votre présence et à préparer les documents nécessaires.")
            ->action('Voir les détails', url('/client/appointments'))
            ->line("À très bientôt sur Ink&Pik !");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $daysText = match($this->daysBefore) {
            7 => 'J-7',
            2 => 'J-2',
            0 => 'Jour J',
            default => $this->daysBefore . ' jours'
        };

        return [
            'type' => 'appointment_reminder',
            'appointment_id' => $this->appointment->id,
            'message' => "📅 Rappel RDV {$daysText}",
            'action_url' => '/client/appointments',
            'days_before' => $this->daysBefore,
        ];
    }
}

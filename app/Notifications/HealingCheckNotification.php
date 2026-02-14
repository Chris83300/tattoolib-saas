<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class HealingCheckNotification extends Notification implements ShouldQueue
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
        $date = $this->appointment->completed_at->addDays(7)->translatedFormat('d/m/Y');

        return (new MailMessage)
            ->subject("🩹 Comment cicatrise votre tattoo ?")
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("Votre tattoo du {$date} semble bien cicatrisé.")
            ->line("Voici quelques conseils pour la suite :")
            ->line("• Continuez d'appliquer la crème cicatrisante 2x/jour pendant 1 semaine")
            ->line("• Évitez l'exposition prolongée au soleil et aux rayons UV")
            ->line("• Pas de bains prolongés, pas de piscine pendant 2 semaines")
            ->line("• Portez des vêtements amples et doux sur la zone")
            ->action('Voir la fiche de soin', url('/client/aftercare'))
            ->line("N'hésitez pas à nous contacter en cas de doute !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'healing_check',
            'appointment_id' => $this->appointment->id,
            'message' => "🩹 Suivi cicatrisation J+7",
            'action_url' => '/client/aftercare',
        ];
    }
}

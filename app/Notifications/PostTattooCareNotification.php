<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostTattooCareNotification extends Notification implements ShouldQueue
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
            ->subject("💊 Conseils de soin pour votre nouveau tattoo")
            ->greeting("Félicitations pour votre nouveau tattoo !")
            ->line("Voici les conseils essentiels pour les premières heures :")
            ->line("🧴 Retirez le pansement après 2-4h")
            ->line("🧼 Lavez délicatement à l'eau tiède et savon doux")
            ->line("💧 Appliquez une fine couche de crème cicatrisante")
            ->line("🚫 Ne grattez pas, ne trempez pas dans l'eau")
            ->line("☀️ Protégez du soleil pendant 4 semaines minimum")
            ->action('Voir la fiche de soin complète', url('/client/aftercare'))
            ->line("En cas de rougeur anormale ou d'infection, consultez un médecin.");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'post_tattoo_care',
            'appointment_id' => $this->appointment->id,
            'message' => "💊 Conseils de soin post-tattoo",
            'action_url' => '/client/aftercare',
        ];
    }
}

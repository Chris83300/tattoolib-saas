<?php

namespace App\Notifications\Client;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountBannedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Client $client
    ) {
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('🚫 Votre compte a été banni')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous vous informons que votre compte Ink&Pik a été banni.')
            ->line('')
            ->line('📋 Raison du bannissement :')
            ->line('• 3 absences non signalées (no-shows)')
            ->line('• Nombre total d\'absences : ' . $notifiable->no_show_count)
            ->line('• Date du bannissement : ' . $notifiable->banned_at->format('d/m/Y à H:i'))
            ->line('')
            ->line('⚠️ Conséquences du bannissement :')
            ->line('• Vous ne pouvez plus créer de nouvelles demandes de tatouage')
            ->line('• Vous ne pouvez plus contacter les tatoueurs')
            ->line('• Vos données personnelles sont conservées pour la durée légale')
            ->line('• Votre historique et médias ont été supprimés')
            ->line('')
            ->line('📧 Pour contester cette décision :')
            ->line('• Contactez notre service client via le formulaire du site')
            ->line('• Expliquez les circonstances de vos absences')
            ->line('• Fournissez des preuves si nécessaire')
            ->line('')
            ->line('Nous espérons pouvoir vous aider à résoudre cette situation.')
            ->line('')
            ->salutation('Cordialement,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Compte banni',
            'message' => 'Votre compte a été banni suite à 3 absences',
            'type' => 'error',
            'icon' => 'ban',
            'no_show_count' => $notifiable->no_show_count,
            'banned_at' => $notifiable->banned_at->format('Y-m-d H:i:s'),
            'banned_reason' => $notifiable->banned_reason,
            'action_url' => route('contact.form'), // Lien vers formulaire de contact
        ];
    }
}

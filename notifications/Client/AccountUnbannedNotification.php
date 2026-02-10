<?php

namespace App\Notifications\Client;

use App\Models\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountUnbannedNotification extends Notification implements ShouldQueue
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
            ->subject('✅ Votre compte a été réactivé')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Bonne nouvelle ! Votre compte Ink&Pik a été réactivé.')
            ->line('')
            ->line('📋 Détails de la réactivation :')
            ->line('• Date de réactivation : ' . $notifiable->unbanned_at->format('d/m/Y à H:i'))
            ->line('• Raison : ' . ($notifiable->unbanned_reason ?? 'Réexamen de votre dossier'))
            ->line('• Compteur no-show réinitialisé : ' . $notifiable->no_show_count)
            ->line('')
            ->line('🎉 Ce qui a changé :')
            -> line('• Vous pouvez de nouveau créer des demandes de tatouage')
            ->line('• Vous pouvez contacter les tatoueurs')
            ->line('• Votre compte est de nouveau pleinement fonctionnel')
            ->line('')
            ->line('💡 Nous vous invitons à :')
            ->line('• Respecter les rendez-vous que vous prenez')
            ->line('• Prévenir en cas d\'impossibilité (au moins 24h à l\'avance)')
            ->line('• Communiquer avec les tatoueurs si besoin')
            ->line('')
            ->line('Nous sommes ravis de vous retrouver parmi nous !')
            ->line('N\'hésitez pas à nous contacter si vous avez des questions.')
            ->line('')
            ->action('Accéder à mon compte', route('login'))
            ->line('Connectez-vous à votre espace client pour commencer.')
            ->salutation('Bienvenue à nouveau !')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Compte réactivé',
            'message' => 'Votre compte a été réactivé avec succès',
            'type' => 'success',
            'icon' => 'check-circle',
            'unbanned_at' => $notifiable->unbanned_at->format('Y-m-d H:i:s'),
            'unbanned_reason' => $notifiable->unbanned_reason,
            'no_show_count' => $notifiable->no_show_count,
            'action_url' => route('login'),
        ];
    }
}

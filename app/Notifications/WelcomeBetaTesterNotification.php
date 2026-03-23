<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeBetaTesterNotification extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenue sur Ink&Pik — Votre mois gratuit est activé !')
            ->greeting('Bienvenue ' . $notifiable->name . ' !')
            ->line('Merci de rejoindre la bêta d\'Ink&Pik.')
            ->line('**Votre mois gratuit est activé** — profitez de toutes les fonctionnalités sans limitation.')
            ->line('À la fin du mois, vous pourrez souscrire avec **-30% à vie** en tant que bêta-testeur privilégié.')
            ->action('Accéder à mon espace', url('/'))
            ->line('Votre retour est précieux — n\'hésitez pas à nous signaler tout bug ou suggestion.')
            ->salutation('L\'équipe Ink&Pik');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'    => 'welcome_beta',
            'message' => 'Bienvenue ! Votre mois gratuit bêta-testeur est activé.',
        ];
    }
}

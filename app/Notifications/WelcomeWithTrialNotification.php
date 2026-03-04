<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeWithTrialNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Bienvenue sur Ink&Pik — Votre essai gratuit de 14 jours')
            ->greeting('Bienvenue sur Ink&Pik !')
            ->line('Votre compte artiste a été créé avec succès.')
            ->line('**Vous bénéficiez de 14 jours d\'essai gratuit** pour découvrir toutes les fonctionnalités de la plateforme.')
            ->line('Pendant cette période :')
            ->line('• Votre profil est visible dans la marketplace')
            ->line('• Vous pouvez recevoir et accepter des demandes')
            ->line('• Vous avez accès à toutes les fonctionnalités Starter')
            ->action('Voir les tarifs', route('pricing'))
            ->line('À la fin de l\'essai, choisissez un abonnement pour continuer à utiliser Ink&Pik. Sans abonnement, votre profil sera masqué de la marketplace.')
            ->salutation('L\'équipe Ink&Pik');
    }
}

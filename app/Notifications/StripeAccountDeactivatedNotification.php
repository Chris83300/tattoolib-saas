<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StripeAccountDeactivatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public $artisan) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Votre compte Stripe Connect a été désactivé')
            ->greeting("Bonjour {$notifiable->first_name} !")
            ->line("Votre compte Stripe Connect a été désactivé suite à une période d'inactivité prolongée.")
            ->line("Pour réactiver les paiements en ligne, vous devrez compléter à nouveau le processus d'onboarding Stripe.")
            ->line("Connectez-vous à votre espace Ink&Pik pour relancer le processus.")
            ->line("À bientôt,")
            ->salutation("L'équipe Ink&Pik");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'stripe_account_deactivated',
            'message' => 'Votre compte Stripe Connect a été désactivé pour inactivité',
            'artisan_id' => $this->artisan->id,
        ];
    }
}

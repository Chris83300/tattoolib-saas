<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BetaUpgradeReminderNotification extends Notification
{
    use Queueable;

    public function __construct(protected int $daysRemaining)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $upgradeUrl = url('/tattooer/subscription/plans?coupon=BETA-LAUNCH-30');

        if ($notifiable->hasRole('pierceur')) {
            $upgradeUrl = url('/pierceur/subscription/plans?coupon=BETA-LAUNCH-30');
        } elseif ($notifiable->hasRole('studio_owner')) {
            $upgradeUrl = url('/studio/billing?coupon=BETA-LAUNCH-30');
        }

        return (new MailMessage)
            ->subject('Ink&Pik — Votre mois offert se termine dans ' . $this->daysRemaining . ' jours')
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Merci d\'avoir participé à la bêta d\'Ink&Pik !')
            ->line('Votre mois gratuit se termine dans **' . $this->daysRemaining . ' jours**.')
            ->line('Pour continuer à utiliser la plateforme, souscrivez maintenant et bénéficiez de **-30% à vie** en tant que bêta-testeur.')
            ->action('Souscrire avec -30% à vie', $upgradeUrl)
            ->line('Ce tarif exclusif est réservé aux bêta-testeurs — il ne sera plus disponible après le lancement.')
            ->line('Des questions ? Répondez directement à cet email.')
            ->salutation('L\'équipe Ink&Pik');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'beta_upgrade_reminder',
            'days_remaining' => $this->daysRemaining,
            'message'        => "Votre mois offert se termine dans {$this->daysRemaining} jours. Souscrivez avec -30% à vie !",
        ];
    }
}

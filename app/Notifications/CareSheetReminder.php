<?php

namespace App\Notifications;

use App\Models\ClientCareSheet;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CareSheetReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private ClientCareSheet $careSheet,
        private string $reminderType
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return match($this->reminderType) {
            'bandage_removal' => $this->bandageRemovalReminder(),
            'photo_day_3' => $this->photoDay3Reminder(),
            'photo_day_14' => $this->photoDay14Reminder(),
            default => $this->defaultReminder(),
        };
    }

    private function bandageRemovalReminder(): MailMessage
    {
        return (new MailMessage)
            ->subject('Rappel : Retrait de votre pansement')
            ->greeting('Bonjour ' . $this->careSheet->client->first_name . ' !')
            ->line('Il est temps de retirer votre pansement.')
            ->line('Voici les instructions à suivre :')
            ->line('1. Lavez-vous soigneusement les mains')
            ->line('2. Retirez délicatement le pansement')
            ->line('3. Rincez la zone à l\'eau tiède et au savon doux')
            ->line('4. Tamponnez délicatement avec une serviette propre')
            ->line($this->careSheet->washing_instructions ?? '')
            ->action('Voir ma fiche de soins', route('client.care-sheets.show', $this->careSheet->id))
            ->line('Prenez soin de votre nouveau tattoo !');
    }

    private function photoDay3Reminder(): MailMessage
    {
        return (new MailMessage)
            ->subject('Suivi J+3 : Comment évolue votre tattoo ?')
            ->greeting('Bonjour ' . $this->careSheet->client->first_name . ' !')
            ->line('Cela fait 3 jours depuis votre séance de tattoo.')
            ->line('Comment se passe la cicatrisation ?')
            ->line('N\'hésitez pas à prendre une photo pour suivre l\'évolution.')
            ->line('Continuez à suivre les instructions de soins.')
            ->action('Ajouter une photo de suivi', route('client.care-sheets.photos', $this->careSheet->id))
            ->line('En cas de doute, contactez votre tatoueur.');
    }

    private function photoDay14Reminder(): MailMessage
    {
        return (new MailMessage)
            ->subject('Suivi J+14 : Votre tattoo est presque cicatrisé !')
            ->greeting('Bonjour ' . $this->careSheet->client->first_name . ' !')
            ->line('Cela fait 2 semaines depuis votre séance.')
            ->line('Votre tattoo devrait être presque complètement cicatrisé.')
            ->line('C\'est le moment idéal pour prendre une photo finale.')
            ->line('Une retouche pourrait être nécessaire dans quelques semaines.')
            ->action('Voir ma fiche de soins', route('client.care-sheets.show', $this->careSheet->id))
            ->line('Félicitations pour votre patience !');
    }

    private function defaultReminder(): MailMessage
    {
        return (new MailMessage)
            ->subject('Rappel de soins pour votre tattoo')
            ->greeting('Bonjour ' . $this->careSheet->client->first_name . ' !')
            ->line('Ceci est un rappel concernant les soins de votre tattoo.')
            ->action('Voir ma fiche de soins', route('client.care-sheets.show', $this->careSheet->id));
    }
}

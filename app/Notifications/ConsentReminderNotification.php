<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ConsentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Project $project) {}

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
        $appointmentDate = $this->project->appointment_date->format('d/m/Y à H:i');
        $artistName = $this->project->bookable->user->name;

        return (new MailMessage)
            ->subject('📋 Consentement requis pour votre rendez-vous du ' . $appointmentDate)
            ->greeting('Bonjour ' . $this->project->client->first_name . ',')
            ->line('Votre rendez-vous avec ' . $artistName . ' approche à grands pas !')
            ->line('📅 **Date du rendez-vous :** ' . $appointmentDate)
            ->line('📍 **Emplacement du tattoo :** ' . $this->project->tattoo_location)
            ->line('')
            ->line('Pour pouvoir réaliser votre tattoo, nous avons besoin de votre consentement éclairé.')
            ->action('Signer le consentement', route('client.projects.show', $this->project->id))
            ->line('')
            ->line('Le consentement est obligatoire et doit être signé avant le rendez-vous.')
            ->line('Si vous êtes mineur, un consentement parental sera également requis.')
            ->line('')
            ->line('Merci de votre confiance,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'type' => 'consent_reminder',
            'title' => 'Consentement requis',
            'message' => 'Veuillez signer le consentement pour votre rendez-vous du ' . $this->project->appointment_date->format('d/m/Y'),
            'appointment_date' => $this->project->appointment_date,
            'artist_name' => $this->project->bookable->user->name,
            'action_url' => route('client.projects.show', $this->project->id),
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'project_id' => $this->project->id,
            'type' => 'consent_reminder',
            'title' => 'Consentement requis',
            'message' => 'Veuillez signer le consentement pour votre rendez-vous du ' . $this->project->appointment_date->format('d/m/Y'),
            'data' => [
                'appointment_date' => $this->project->appointment_date,
                'artist_name' => $this->project->bookable->user->name,
                'action_url' => route('client.projects.show', $this->project->id),
            ],
        ]);
    }
}

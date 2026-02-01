<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public Project $project)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $clientName = $this->project->client?->full_name ?? 'Client';

        return (new MailMessage)
            ->subject('Nouvelle demande de projet - Ink&Pik')
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('Vous avez reçu une nouvelle demande de projet.')
            ->line('Client : ' . $clientName)
            ->line('Projet : ' . str($this->project->tattoo_description)->limit(120))
            ->action('Voir les demandes', route('tattooer.requests'));
    }

    public function toArray($notifiable): array
    {
        return [
            'project_id' => $this->project->id,
            'client_name' => $this->project->client?->full_name,
            'status' => $this->project->status,
        ];
    }
}

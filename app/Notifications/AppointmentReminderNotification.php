<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Project $project, 
        public string $type // 'tomorrow' or 'today'
    ) {}

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
        $isTomorrow = $this->type === 'tomorrow';
        
        $subject = $isTomorrow 
            ? '🗓️ Rappel : Votre rendez-vous demain avec ' . $artistName
            : '🎉 Votre rendez-vous est aujourd\'hui avec ' . $artistName;

        $mail = (new MailMessage)
            ->subject($subject)
            ->greeting('Bonjour ' . $this->project->client->first_name . ',')
            ->line($isTomorrow 
                ? 'N\'oubliez pas votre rendez-vous demain :'
                : 'Votre rendez-vous est aujourd\'hui !');

        $mail->line('📅 **Date :** ' . $appointmentDate)
            ->line('📍 **Artiste :** ' . $artistName)
            ->line('🎯 **Emplacement :** ' . $this->project->tattoo_location);

        if ($this->project->deposit_amount && !$this->project->isDepositPaid()) {
            $mail->line('')
                ->line('⚠️ **Attention :** Votre acompte de ' . number_format($this->project->deposit_amount, 2) . '€ n\'a pas encore été payé.')
                ->action('Payer l\'acompte', route('deposit.payment', $this->project->id));
        }

        $mail->line('')
            ->line($isTomorrow 
                ? 'À très bientôt pour votre séance de tattoo !'
                : 'On vous attend à l\'heure convenue !')
            ->line('')
            ->line('En cas d\'empêchement, merci de prévenir l\'artiste au plus vite.')
            ->line('Merci de votre confiance,')
            ->line('L\'équipe Ink&Pik');

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $isTomorrow = $this->type === 'tomorrow';
        
        return [
            'project_id' => $this->project->id,
            'type' => 'appointment_reminder',
            'reminder_type' => $this->type,
            'title' => $isTomorrow ? 'Rappel RDV demain' : 'RDV aujourd\'hui',
            'message' => 'Rendez-vous ' . ($isTomorrow ? 'demain' : 'aujourd\'hui') . ' à ' . $this->project->appointment_date->format('H:i'),
            'appointment_date' => $this->project->appointment_date,
            'artist_name' => $this->project->bookable->user->name,
            'deposit_pending' => $this->project->deposit_amount && !$this->project->isDepositPaid(),
            'action_url' => route('client.projects.show', $this->project->id),
        ];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        $isTomorrow = $this->type === 'tomorrow';
        
        return new DatabaseMessage([
            'project_id' => $this->project->id,
            'type' => 'appointment_reminder',
            'reminder_type' => $this->type,
            'title' => $isTomorrow ? 'Rappel RDV demain' : 'RDV aujourd\'hui',
            'message' => 'Rendez-vous ' . ($isTomorrow ? 'demain' : 'aujourd\'hui') . ' à ' . $this->project->appointment_date->format('H:i'),
            'data' => [
                'appointment_date' => $this->project->appointment_date,
                'artist_name' => $this->project->bookable->user->name,
                'deposit_pending' => $this->project->deposit_amount && !$this->project->isDepositPaid(),
                'action_url' => route('client.projects.show', $this->project->id),
            ],
        ]);
    }
}

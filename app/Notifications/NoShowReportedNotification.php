<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoShowReportedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Appointment $appointment,
        public string $reportedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $reporter = $this->reportedBy === 'tattooer' ? "L'artiste" : "Le client";

        return (new MailMessage)
            ->subject("⚠️ No-show signalé pour votre rendez-vous")
            ->greeting("Bonjour {$notifiable->display_name}")
            ->line("{$reporter} a signalé une absence pour votre rendez-vous.")
            ->line("Notre équipe va examiner la situation et vous tiendra informé(e).")
            ->line("Si un acompte a été versé, les conditions de remboursement seront appliquées selon nos CGV.")
            ->action('Voir le détail', url('/'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'no_show_reported',
            'appointment_id' => $this->appointment->id,
            'reported_by' => $this->reportedBy,
            'message' => "⚠️ Un no-show a été signalé pour votre RDV.",
        ];
    }
}

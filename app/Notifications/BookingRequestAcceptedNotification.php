<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BookingRequestAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public $bookingRequest
    ) {}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Votre demande de projet a été acceptée ! - Ink&Pik')
            ->greeting('Bonjour ' . $this->bookingRequest->first_name)
            ->line('Bonne nouvelle ! Votre demande de projet a été acceptée par le tatoueur.')
            ->line('Détails du projet :')
            ->line('Description : ' . $this->bookingRequest->description)
            ->line('Budget : ' . number_format($this->bookingRequest->estimated_budget, 2, ',', ' ') . ' €')
            ->action('Voir vos demandes', url('/client/projets'))
            ->line('Vous pouvez maintenant discuter directement avec le tatoueur pour finaliser les détails.')
            ->line('Merci d\'utiliser Ink&Pik !');
    }

    public function toArray($notifiable)
    {
        return [
            'booking_request_id' => $this->bookingRequest->id,
            'message' => 'Votre demande de projet a été acceptée !',
            'type' => 'booking_accepted',
        ];
    }
}

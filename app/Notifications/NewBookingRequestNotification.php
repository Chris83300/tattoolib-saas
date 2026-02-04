<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingRequestNotification extends Notification
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $clientName = $this->bookingRequest->client?->pseudo ?? 'Client';

        return (new MailMessage)
            ->subject('Nouvelle demande de projet - Ink&Pik')
            ->greeting('Bonjour ' . ($notifiable->name ?? ''))
            ->line('Vous avez reçu une nouvelle demande de projet.')
            ->line('Client : ' . $clientName)
            ->line('Projet : ' . str($this->bookingRequest->description)->limit(120))
            ->action('Voir les demandes', route('tattooer.requests'));
    }

    public function toArray($notifiable): array
    {
        return [
            'booking_request_id' => $this->bookingRequest->id,
            'client_name' => $this->bookingRequest->client?->pseudo,
            'status' => $this->bookingRequest->status,
        ];
    }
}

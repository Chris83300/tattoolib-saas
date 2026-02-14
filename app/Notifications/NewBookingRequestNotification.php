<?php

namespace App\Notifications;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewBookingRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public BookingRequest $bookingRequest) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $clientName = $this->bookingRequest->client?->display_name ?? 'Un client';
        $description = Str::limit($this->bookingRequest->description, 100);

        return (new MailMessage)
            ->subject("📩 Nouvelle demande de tattoo sur Ink&Pik")
            ->greeting("Bonjour {$notifiable->display_name} !")
            ->line("{$clientName} souhaite un tattoo :")
            ->line("\"{$description}\"")
            ->action('Voir la demande', route('tattooer.booking-requests.show', $this->bookingRequest))
            ->line("Répondez rapidement pour ne pas perdre ce client !");
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_booking_request',
            'booking_request_id' => $this->bookingRequest->id,
            'client_name' => $this->bookingRequest->client?->display_name,
            'message' => "📩 Nouvelle demande de {$this->bookingRequest->client?->display_name}",
            'action_url' => route('tattooer.booking-requests.show', $this->bookingRequest),
        ];
    }
}

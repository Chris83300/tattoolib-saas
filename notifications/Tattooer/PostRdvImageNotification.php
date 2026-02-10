<?php

namespace App\Notifications\Tattooer;

use App\Models\Appointment;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostRdvImageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Appointment $appointment,
        private Message $message
    ) {
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database']; // In-app uniquement
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $appointment = $this->appointment;
        $bookingRequest = $appointment->bookingRequest;
        $client = $bookingRequest->client;
        
        return [
            'title' => 'Photo post-tatouage reçue',
            'message' => $client->full_name . ' a envoyé une photo de suivi',
            'type' => 'info',
            'icon' => 'image',
            'appointment_id' => $appointment->id,
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'image_number' => $this->message->attachments['image_number'] ?? null,
            'image_path' => $this->message->attachments['path'] ?? null,
            'days_after_appointment' => $appointment->end_datetime->diffInDays(now()),
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }
}

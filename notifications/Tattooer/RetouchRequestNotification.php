<?php

namespace App\Notifications\Tattooer;

use App\Models\Appointment;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class RetouchRequestNotification extends Notification implements ShouldQueue
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
            'title' => 'Demande de retouche',
            'message' => $client->full_name . ' souhaite une retouche',
            'type' => 'warning',
            'icon' => 'refresh-cw',
            'appointment_id' => $appointment->id,
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'request_message' => $this->message->content,
            'has_images' => !empty($this->message->attachments),
            'days_after_appointment' => $appointment->end_datetime->diffInDays(now()),
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }
}

<?php

namespace App\Notifications\Tattooer;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class DateChosenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private BookingRequest $bookingRequest
    ) {
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database']; // In-app uniquement pour le choix de date
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $bookingRequest = $this->bookingRequest;
        $client = $bookingRequest->client;
        
        return [
            'title' => 'Date choisie par le client',
            'message' => $client->full_name . ' a choisi une date pour son rendez-vous',
            'type' => 'info',
            'icon' => 'calendar-check',
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'confirmed_date' => $bookingRequest->confirmed_date,
            'confirmed_period' => $bookingRequest->confirmed_period,
            'appointment_datetime' => $bookingRequest->appointment_datetime,
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }
}

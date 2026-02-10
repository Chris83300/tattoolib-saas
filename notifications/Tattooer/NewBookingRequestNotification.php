<?php

namespace App\Notifications\Tattooer;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewBookingRequestNotification extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $bookingRequest = $this->bookingRequest;
        $client = $bookingRequest->client;
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('🆕 Nouvelle demande de tatouage reçue !')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('🎉 Bonne nouvelle ! Vous avez reçu une nouvelle demande de tatouage.')
            ->line('')
            ->line('📋 Détails de la demande :')
            ->line('• Client : ' . $client->full_name . ' (@' . $client->pseudo . ')')
            ->line('• Email : ' . $client->email)
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('• Description : ' . $bookingRequest->description)
            ->line('')
            ->line('💰 Budget du client :')
            ->line('• Budget estimé : ' . ($bookingRequest->estimated_budget ? $bookingRequest->estimated_budget . '€' : 'Non spécifié'))
            ->line('')
            ->line('📅 Préférences du client :')
            ->line('• Délai souhaité : ' . ($bookingRequest->preferred_timeframe ?? 'Non spécifié'))
            ->line('• Date préférée : ' . ($bookingRequest->preferred_date ? $bookingRequest->preferred_date->format('d/m/Y') : 'Non spécifiée'))
            ->line('• Créneau préféré : ' . ($bookingRequest->preferred_time_slot ?? 'Non spécifié'))
            ->line('')
            ->line('🔔 Prochaines étapes :')
            ->line('1. Étudiez la demande et les préférences du client')
            ->line('2. Acceptez ou refusez la demande')
            ->line('3. Si vous acceptez, proposez vos conditions et dates')
            ->line('')
            ->action('Voir la demande', route('tattooer.requests.show', $bookingRequest->id))
            ->line('Connectez-vous à votre espace tatoueur pour répondre à cette demande.')
            ->salutation('À bientôt,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $bookingRequest = $this->bookingRequest;
        $client = $bookingRequest->client;
        
        return [
            'title' => 'Nouvelle demande reçue',
            'message' => 'Vous avez reçu une nouvelle demande de tatouage de ' . $client->full_name,
            'type' => 'info',
            'icon' => 'plus-circle',
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'client_email' => $client->email,
            'tattoo_size' => $bookingRequest->tattoo_size,
            'body_zone' => $bookingRequest->body_zone,
            'estimated_budget' => $bookingRequest->estimated_budget,
            'preferred_timeframe' => $bookingRequest->preferred_timeframe,
            'preferred_date' => $bookingRequest->preferred_date?->format('Y-m-d'),
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }
}

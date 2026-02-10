<?php

namespace App\Notifications\Client;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingRequestAcceptedNotification extends Notification implements ShouldQueue
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
        $tattooer = $bookingRequest->bookable;
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('✅ Votre demande de tatouage a été acceptée !')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('🎉 Bonne nouvelle ! Votre demande de tatouage a été acceptée par ' . $tattooer->name . '.')
            ->line('')
            ->line('📋 Détails de votre demande :')
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('• Description : ' . $bookingRequest->description)
            ->line('')
            ->line('💰 Estimation et acompte :')
            ->line('• Estimation : ' . $bookingRequest->price_estimate_min . '€ - ' . $bookingRequest->price_estimate_max . '€')
            ->line('• Acompte : ' . $bookingRequest->deposit_amount . '€')
            ->line('')
            ->line('📅 Prochaines étapes :')
            ->line('1. Payez l\'acompte pour confirmer votre rendez-vous')
            ->line('2. Choisissez une date parmi celles proposées')
            ->line('3. Discutez des détails avec le tatoueur')
            ->line('')
            ->action('Voir ma demande', route('client.requests.show', $bookingRequest->id))
            ->line('Vous pouvez accéder à votre espace client pour suivre l\'évolution de votre demande.')
            ->salutation('À bientôt,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Demande acceptée',
            'message' => 'Votre demande de tatouage a été acceptée par le tatoueur',
            'type' => 'success',
            'icon' => 'check-circle',
            'booking_request_id' => $this->bookingRequest->id,
            'tattooer_name' => $this->bookingRequest->bookable->name,
            'price_estimate_min' => $this->bookingRequest->price_estimate_min,
            'price_estimate_max' => $this->bookingRequest->price_estimate_max,
            'deposit_amount' => $this->bookingRequest->deposit_amount,
            'action_url' => route('client.requests.show', $this->bookingRequest->id),
        ];
    }
}

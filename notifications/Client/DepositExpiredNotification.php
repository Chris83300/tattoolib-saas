<?php

namespace App\Notifications\Client;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepositExpiredNotification extends Notification implements ShouldQueue
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
            ->subject('⏰ Délai d\'acompte expiré')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous vous informons que le délai pour payer l\'acompte de votre demande de tatouage a expiré.')
            ->line('')
            ->line('📋 Détails de votre demande :')
            ->line('• Tatoueur : ' . $tattooer->name)
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('• Acompte requis : ' . $bookingRequest->deposit_amount . '€')
            ->line('')
            ->line('😕 Que s\'est-il passé ?')
            ->line('Le délai de paiement de ' . $bookingRequest->deposit_deadline_hours . ' heures est dépassé.')
            ->line('Votre demande a donc été automatiquement annulée.')
            ->line('')
            ->line('🔄 Que faire maintenant ?')
            ->line('1. Vous pouvez soumettre une nouvelle demande')
            ->line('2. Contactez directement le tatoueur pour convenir d\'un nouveau délai')
            ->line('3. Nos équipes sont à votre disposition si besoin')
            ->line('')
            ->action('Soumettre une nouvelle demande', route('booking-request.create'))
            ->line('Nous espérons vous revoir prochainement pour réaliser votre projet !')
            ->salutation('Cordialement,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Acompte expiré',
            'message' => 'Le délai pour payer l\'acompte de votre demande a expiré',
            'type' => 'error',
            'icon' => 'clock',
            'booking_request_id' => $this->bookingRequest->id,
            'tattooer_name' => $this->bookingRequest->bookable->name,
            'deposit_amount' => $this->bookingRequest->deposit_amount,
            'deadline_hours' => $this->bookingRequest->deposit_deadline_hours,
            'action_url' => route('booking-request.create'),
        ];
    }
}

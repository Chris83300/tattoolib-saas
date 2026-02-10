<?php

namespace App\Notifications\Tattooer;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class DepositPaidNotification extends Notification implements ShouldQueue
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
            ->subject('💰 Acompte payé - Demande de tatouage confirmée !')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('🎉 Excellent ! Le client a payé l\'acompte pour sa demande de tatouage.')
            ->line('')
            ->line('💰 Détails du paiement :')
            ->line('• Client : ' . $client->full_name . ' (@' . $client->pseudo . ')')
            ->line('• Montant de l\'acompte : ' . $bookingRequest->deposit_amount . '€')
            ->line('• Date du paiement : ' . $bookingRequest->deposit_paid_at->format('d/m/Y à H:i'))
            ->line('')
            ->line('📋 Détails de la demande :')
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('• Description : ' . $bookingRequest->description)
            ->line('')
            ->line('📅 Prochaines étapes :')
            ->line('1. Le client va maintenant choisir une date parmi vos propositions')
            ->line('2. Une fois la date choisie, vous pourrez ajouter le RDV à votre calendrier')
            ->line('3. Communiquez avec le client pour finaliser les détails')
            ->line('')
            ->line('💡 Le chat est maintenant activé avec le client.')
            ->line('Vous pouvez échanger des images et discuter des détails du tatouage.')
            ->line('')
            ->action('Voir la demande', route('tattooer.requests.show', $bookingRequest->id))
            ->line('Accédez à votre espace tatoueur pour suivre l\'évolution.')
            ->salutation('Félicitations pour cette nouvelle réservation !')
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
            'title' => 'Acompte payé',
            'message' => 'Le client a payé l\'acompte pour sa demande de tatouage',
            'type' => 'success',
            'icon' => 'check-circle',
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'deposit_amount' => $bookingRequest->deposit_amount,
            'deposit_paid_at' => $bookingRequest->deposit_paid_at->format('Y-m-d H:i:s'),
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }
}

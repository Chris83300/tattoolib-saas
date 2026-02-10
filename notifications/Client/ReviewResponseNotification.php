<?php

namespace App\Notifications\Client;

use App\Models\ClientReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReviewResponseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private ClientReview $review
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
        $review = $this->review;
        $tattooer = $review->bookable;
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('💬 Réponse à votre avis')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Le tatoueur a répondu à votre avis !')
            ->line('')
            ->line('📋 Votre avis :')
            ->line('• Note : ' . str_repeat('⭐', $review->rating) . ' (' . $review->rating . '/5)')
            ->line('• Date : ' . $review->reviewed_at->format('d/m/Y à H:i'))
            ->line('• Rendez-vous : ' . $review->appointment->start_datetime->translatedFormat('l d F Y'))
            ->line('')
            ->line('💬 Réponse du tatoueur :')
            ->line($review->tattooer_response)
            ->line('')
            ->line('🤝 Merci pour votre retour !')
            ->line('Votre avis aide la communauté à faire les bons choix.')
            ->line('N\'hésitez pas à contacter le tatoueur si vous avez des questions.')
            ->line('')
            ->action('Voir la conversation', route('client.requests.show', $review->bookingRequest->id))
            ->line('Accédez à votre espace client pour voir la conversation complète.')
            ->salutation('À bientôt,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $review = $this->review;
        $tattooer = $review->bookable;
        
        return [
            'title' => 'Réponse à votre avis',
            'message' => $tattooer->name . ' a répondu à votre avis',
            'type' => 'info',
            'icon' => 'message-circle',
            'review_id' => $review->id,
            'appointment_id' => $review->appointment->id,
            'booking_request_id' => $review->bookingRequest->id,
            'tattooer_name' => $tattooer->name,
            'tattooer_response' => $review->tattooer_response,
            'responded_at' => $review->tattooer_responded_at->format('Y-m-d H:i:s'),
            'action_url' => route('client.requests.show', $review->bookingRequest->id),
        ];
    }
}

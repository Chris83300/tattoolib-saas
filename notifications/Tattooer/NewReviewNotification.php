<?php

namespace App\Notifications\Tattooer;

use App\Models\Appointment;
use App\Models\ClientReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewReviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Appointment $appointment,
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
        $appointment = $this->appointment;
        $review = $this->review;
        $client = $review->client;
        
        $stars = str_repeat('⭐', $review->rating);
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('⭐ Nouvel avis reçu !')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('🎉 Bonne nouvelle ! Vous avez reçu un nouvel avis de la part d\'un client.')
            ->line('')
            ->line('📋 Détails de l\'avis :')
            ->line('• Client : ' . $client->full_name . ' (@' . $client->pseudo . ')')
            ->line('• Note : ' . $stars . ' (' . $review->rating . '/5)')
            ->line('• Date : ' . $review->reviewed_at->format('d/m/Y à H:i'))
            ->line('• Rendez-vous : ' . $appointment->start_datetime->translatedFormat('l d F Y'))
            ->line('')
            ->line('💬 Commentaire :')
            ->line($review->comment ?: 'Aucun commentaire laissé');
            
        if ($review->hasPhotos()) {
            $line('');
            $line('📸 Photos : ' . $review->getPhotosCount() . ' photo(s) jointe(s)');
        }
        
        $line('')
            ->line('📈 Impact sur votre réputation :')
            ->line('• Note moyenne actuelle : ' . $notifiable->average_rating . '/5')
            ->line('• Total d\'avis : ' . $notifiable->total_reviews)
            ->line('')
            ->action('Voir l\'avis', route('tattooer.reviews.show', $review->id))
            ->line('Connectez-vous à votre espace tatoueur pour voir et répondre à cet avis.')
            ->salutation('Félicitations pour cet excellent retour !')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $appointment = $this->appointment;
        $review = $this->review;
        $client = $review->client;
        
        return [
            'title' => 'Nouvel avis reçu',
            'message' => $client->full_name . ' a laissé un avis ' . $review->rating . ' étoiles',
            'type' => 'success',
            'icon' => 'star',
            'review_id' => $review->id,
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'rating' => $review->rating,
            'has_comment' => $review->hasComment(),
            'has_photos' => $review->hasPhotos(),
            'reviewed_at' => $review->reviewed_at->format('Y-m-d H:i:s'),
            'action_url' => route('tattooer.reviews.show', $review->id),
        ];
    }
}

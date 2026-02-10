<?php

namespace App\Notifications\Admin;

use App\Models\Appointment;
use App\Models\ClientReview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class LowRatingNotification extends Notification implements ShouldQueue
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
        $tattooer = $appointment->bookingRequest->bookable;
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('⚠️ Avis négatif reçu - Modération requise')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un avis négatif a été laissé et nécessite votre attention.')
            ->line('')
            ->line('📋 Détails de l\'avis :')
            ->line('• Client : ' . $client->full_name . ' (@' . $client->pseudo . ')')
            ->line('• Tatoueur : ' . $tattooer->name)
            ->line('• Note : ' . $review->rating . '/5 ⭐')
            ->line('• Date : ' . $review->reviewed_at->format('d/m/Y à H:i'))
            ->line('• Rendez-vous : ' . $appointment->start_datetime->translatedFormat('l d F Y'))
            ->line('')
            ->line('💬 Commentaire du client :')
            ->line($review->comment ?: 'Aucun commentaire');
            
        if ($review->hasPhotos()) {
            $line('');
            $line('📸 Photos jointes : ' . $review->getPhotosCount() . ' photo(s)');
        }
        
        $line('')
            ->line('⚠️ Actions recommandées :')
            ->line('1. Vérifier si l\'avis est conforme aux règles de la plateforme')
            ->line('2. Contacter le client si nécessaire pour clarifier la situation')
            ->line('3. Modérer l\'avis si nécessaire (cacher ou supprimer)')
            ->line('4. Répondre à l\'avis pour montrer votre engagement')
            ->line('')
            ->line('🔗 Lien vers l\'avis :')
            ->action('Voir et modérer l\'avis', route('admin.reviews.show', $review->id))
            ->line('Connectez-vous à l\'administration pour gérer cet avis.')
            ->salutation('Cordialement,')
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
        $tattooer = $appointment->bookingRequest->bookable;
        
        return [
            'title' => 'Avis négatif reçu',
            'message' => $client->full_name . ' a laissé un avis ' . $review->rating . ' étoiles',
            'type' => 'warning',
            'icon' => 'exclamation-triangle',
            'review_id' => $review->id,
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'tattooer_name' => $tattooer->name,
            'rating' => $review->rating,
            'has_comment' => $review->hasComment(),
            'has_photos' => $review->hasPhotos(),
            'reviewed_at' => $review->reviewed_at->format('Y-m-d H:i:s'),
            'action_url' => route('admin.reviews.show', $review->id),
        ];
    }
}

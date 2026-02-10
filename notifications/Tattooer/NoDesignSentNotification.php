<?php

namespace App\Notifications\Tattooer;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoDesignSentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private BookingRequest $bookingRequest,
        private int $daysBeforeAppointment
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
        $appointment = $bookingRequest->appointment;
        
        $daysText = $this->daysBeforeAppointment === 7 ? '7 jours' : '3 jours';
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('⚠️ Alerte : Aucun design envoyé (RDV dans ' . $daysText . ')')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('⚠️ Attention : Vous n\'avez pas encore envoyé de design pour le rendez-vous de ' . $client->full_name . '.')
            ->line('')
            ->line('📋 Détails du rendez-vous :')
            ->line('• Client : ' . $client->full_name . ' (@' . $client->pseudo . ')')
            ->line('• Date : ' . $appointment->start_datetime->translatedFormat('l d F Y'))
            ->line('• Heure : ' . $appointment->start_datetime->format('H:i'))
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('')
            ->line('🎨 Design requis :')
            ->line('• Designs inclus : ' . $bookingRequest->included_designs)
            ->line('• Designs envoyés : ' . $bookingRequest->designs_sent_count)
            ->line('• Statut : ' . ($bookingRequest->designs_sent_count === 0 ? 'Aucun design envoyé' : $bookingRequest->designs_sent_count . ' design(s) envoyé(s)'))
            ->line('')
            ->line('📝 Actions recommandées :')
            ->line('1. Envoyez au moins un design avant le rendez-vous')
            ->line('2. Le client doit valider le design pour préparer la séance')
            ->line('3. Communiquez avec le client pour ajustements si nécessaire')
            ->line('')
            ->line('⏰ Le rendez-vous est dans ' . $daysText . ', il est temps d\'agir !')
            ->line('Un design envoyé à temps assure une meilleure préparation.')
            ->line('')
            ->action('Voir la demande', route('tattooer.requests.show', $bookingRequest->id))
            ->line('Accédez à votre espace tatoueur pour envoyer le design.')
            ->salutation('Courage,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $bookingRequest = $this->bookingRequest;
        $client = $bookingRequest->client;
        $appointment = $bookingRequest->appointment;
        
        return [
            'title' => 'Alerte : Aucun design envoyé',
            'message' => 'Vous n\'avez pas encore envoyé de design pour le rendez-vous dans ' . $this->daysBeforeAppointment . ' jours',
            'type' => 'warning',
            'icon' => 'exclamation-triangle',
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'appointment_date' => $appointment->start_datetime->format('Y-m-d'),
            'appointment_time' => $appointment->start_datetime->format('H:i'),
            'days_before' => $this->daysBeforeAppointment,
            'designs_sent' => $bookingRequest->designs_sent_count,
            'designs_included' => $bookingRequest->included_designs,
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }
}

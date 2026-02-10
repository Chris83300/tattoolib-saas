<?php

namespace App\Notifications\Tattooer;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoShowNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Appointment $appointment
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
        $bookingRequest = $appointment->bookingRequest;
        $client = $bookingRequest->client;
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('⚠️ Client absent au rendez-vous')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous vous informons que le client n\'a pas honoré son rendez-vous.')
            ->line('')
            ->line('📋 Détails du rendez-vous manqué :')
            ->line('• Client : ' . $client->full_name . ' (@' . $client->pseudo . ')')
            ->line('• Date prévue : ' . $appointment->start_datetime->translatedFormat('l d F Y'))
            ->line('• Heure prévue : ' . $appointment->start_datetime->format('H:i'))
            ->line('• Durée : ' . $appointment->duration_minutes . ' minutes')
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('')
            ->line('💰 Situation financière :')
            ->line('• Acompte payé : ' . $bookingRequest->deposit_amount . '€')
            ->line('• Prix total : ' . $bookingRequest->total_price . '€')
            ->line('')
            ->line('🔄 Options disponibles :')
            ->line('1. Reporter le rendez-vous (avec accord du client)')
            ->line('2. Conserver l\'acompte pour une future séance')
            ->line('3. Rembourser partiellement selon votre politique')
            ->line('4. Contacter le client (si c\'est récurrent)')
            ->line('')
            ->line('📝 Actions recommandées :')
            ->line('1. Contactez le client pour comprendre la raison de l\'absence')
            ->line('2. Proposez une solution adaptée à la situation')
            ->line('3. Documentez la communication pour votre protection')
            ->line('')
            ->action('Voir la demande', route('tattooer.requests.show', $bookingRequest->id))
            ->line('Accédez à votre espace tatoueur pour gérer cette situation.')
            ->salutation('Prenez soin de vous,')
            ->line('L\'équipe Ink&Pik');
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
            'title' => 'Client absent',
            'message' => 'Le client n\'a pas honoré son rendez-vous',
            'type' => 'error',
            'icon' => 'user-x',
            'appointment_id' => $appointment->id,
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'appointment_date' => $appointment->start_datetime->format('Y-m-d'),
            'appointment_time' => $appointment->start_datetime->format('H:i'),
            'deposit_amount' => $bookingRequest->deposit_amount,
            'total_price' => $bookingRequest->total_price,
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }
}

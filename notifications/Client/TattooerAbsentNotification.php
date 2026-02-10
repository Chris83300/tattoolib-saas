<?php

namespace App\Notifications\Client;

use App\Models\Appointment;
use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class TattooerAbsentNotification extends Notification implements ShouldQueue
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
        $tattooer = $bookingRequest->bookable;
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('⚠️ Absence du tatoueur signalée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous sommes désolés, mais le tatoueur n\'était pas disponible pour votre rendez-vous.')
            ->line('')
            ->line('📋 Détails du rendez-vous manqué :')
            ->line('• Tatoueur : ' . $tattooer->name)
            ->line('• Date prévue : ' . $appointment->start_datetime->translatedFormat('l d F Y'))
            ->line('• Heure prévue : ' . $appointment->start_datetime->format('H:i'))
            ->line('• Durée : ' . $appointment->duration_minutes . ' minutes')
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('')
            ->line('💰 Remboursement :')
            ->line('• Montant remboursé : ' . $bookingRequest->total_deposit_amount . '€')
            -> line('• Statut : Remboursement complet effectué')
            ->line('• Délai : 5-7 jours ouvrés')
            ->line('• Méthode : Virement sur votre compte bancaire')
            ->line('')
            ->line('🔄 Options disponibles :')
            ->line('1. Trouver un autre tatoueur sur la plateforme')
            ->line('2. Contacter directement le tatoueur pour convenir d\'un nouveau délai')
            ->line('3. Attendre que le remboursement soit crédité')
            ->line('')
            ->line('Nous sommes sincèrement désolés pour ce contretemps.')
            ->line('Votre satisfaction est notre priorité.')
            ->line('')
            ->action('Voir les autres tatoueurs', route('marketplace.index'))
            ->line('Explorez notre sélection de tatoueurs qualifiés.')
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
        $tattooer = $bookingRequest->bookable;
        
        return [
            'title' => 'Tattooer absent',
            'message' => 'Le tatoueur n\'était pas disponible pour votre rendez-vous',
            'type' => 'error',
            'icon' => 'user-x',
            'appointment_id' => $appointment->id,
            'booking_request_id' => $bookingRequest->id,
            'tattooer_name' => $tattooer->name,
            'appointment_date' => $appointment->start_datetime->format('Y-m-d'),
            'appointment_time' => $appointment->start_datetime->format('H:i'),
            'deposit_amount' => $bookingRequest->total_deposit_amount,
            'total_price' => $bookingRequest->total_price,
            'refund_amount' => $bookingRequest->total_deposit_amount,
            'action_url' => route('marketplace.index'),
        ];
    }
}

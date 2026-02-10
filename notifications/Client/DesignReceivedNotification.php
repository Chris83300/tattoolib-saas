<?php

namespace App\Notifications\Client;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class DesignReceivedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private BookingRequest $bookingRequest,
        private int $designNumber
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
            ->subject('🎨 Nouveau design disponible pour votre tatouage !')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('🎉 Bonne nouvelle ! Le tattooer vous a envoyé le design #' . $this->designNumber . ' pour votre tatouage.')
            ->line('')
            ->line('📋 Détails de votre projet :')
            ->line('• Tatoueur : ' . $tattooer->name)
            ->line('• Taille : ' . $bookingRequest->tattoo_size)
            ->line('• Zone : ' . $bookingRequest->body_zone)
            ->line('')
            ->line('🎨 À propos du design :')
            ->line('• Design n°' . $this->designNumber . ' sur ' . $bookingRequest->included_designs . ' inclus')
            ->line('• Vous pouvez demander jusqu\'à ' . $bookingRequest->modifications_per_design . ' modifications')
            ->line('')
            ->line('💬 Prochaines étapes :')
            ->line('1. Connectez-vous à votre espace client pour voir le design')
            ->line('2. Discutez avec le tatoueur si vous souhaitez des modifications')
            ->line('3. Validez le design ou demandez des changements')
            ->line('')
            ->action('Voir le design', route('client.requests.show', $bookingRequest->id))
            ->line('Le design est disponible dans votre conversation avec le tatoueur.')
            ->line('')
            ->line('N\'hésitez pas à donner votre feedback pour que le tatoueur puisse ajuster le design selon vos préférences.')
            ->salutation('À bientôt,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'title' => 'Nouveau design reçu',
            'message' => 'Le tattooer vous a envoyé le design #' . $this->designNumber . ' pour votre tatouage',
            'type' => 'success',
            'icon' => 'brush',
            'booking_request_id' => $this->bookingRequest->id,
            'tattooer_name' => $this->bookingRequest->bookable->name,
            'design_number' => $this->designNumber,
            'total_designs' => $this->bookingRequest->included_designs,
            'modifications_allowed' => $this->bookingRequest->modifications_per_design,
            'action_url' => route('client.requests.show', $this->bookingRequest->id),
        ];
    }
}

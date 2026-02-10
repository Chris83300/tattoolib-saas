<?php

namespace App\Notifications\Admin;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class HighValueRefundNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private BookingRequest $bookingRequest,
        private float $refundAmount,
        private string $cancelledBy
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
        $tattooer = $bookingRequest->bookable;
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('⚠️ Remboursement important traité')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un remboursement important a été traité et nécessite votre attention.')
            ->line('')
            ->line('📋 Détails de l\'annulation :')
            ->line('• Client : ' . $client->full_name . ' (@' . $client->pseudo . ')')
            ->line('• Email : ' . $client->email)
            ->line('• Tatoueur : ' . $tattooer->name)
            ->line('• Annulé par : ' . ($this->cancelledBy === 'client' ? 'Client' : 'Tattooer'))
            ->line('• Date d\'annulation : ' . $bookingRequest->cancelled_at->format('d/m/Y à H:i'))
            ->line('')
            ->line('💰 Détails du remboursement :')
            ->line('• Montant remboursé : ' . number_format($this->refundAmount, 2) . '€')
            ->line('• Acompte initial : ' . number_format($bookingRequest->total_deposit_amount, 2) . '€')
            ->line('• Pourcentage remboursé : ' . round(($this->refundAmount / $bookingRequest->total_deposit_amount) * 100, 1) . '%')
            ->line('• Designs envoyés : ' . $bookingRequest->designs_sent_count)
            ->line('')
            ->line('📊 Impact sur la plateforme :')
            ->line('• Ce remboursement affecte les revenus de la période')
            ->line('• Le taux d\'annulation peut nécessiter un suivi')
            ->line('• La satisfaction client doit être surveillée')
            ->line('')
            ->line('🔗 Actions recommandées :')
            ->line('1. Analyser la raison de cette annulation')
            ->line('2. Contacter le client si nécessaire pour comprendre la situation')
            ->line('3. Surveiller les statistiques d\'annulation du tatoueur')
            ->line('4. Vérifier si des mesures préventives sont nécessaires')
            ->line('')
            ->action('Voir les détails', route('admin.requests.show', $bookingRequest->id))
            ->line('Connectez-vous à l\'administration pour analyser cette annulation.')
            ->salutation('Cordialement,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $bookingRequest = $this->bookingRequest;
        $client = $bookingRequest->client;
        $tattooer = $bookingRequest->bookable;
        
        return [
            'title' => 'Remboursement important',
            'message' => 'Un remboursement de ' . number_format($this->refundAmount, 2) . '€ a été traité',
            'type' => 'warning',
            'icon' => 'euro',
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'tattooer_name' => $tattooer->name,
            'cancelled_by' => $this->cancelledBy,
            'refund_amount' => $this->refundAmount,
            'deposit_amount' => $bookingRequest->total_deposit_amount,
            'refund_percent' => round(($this->refundAmount / $bookingRequest->total_deposit_amount) * 100, 1),
            'designs_sent' => $bookingRequest->designs_sent_count,
            'cancelled_at' => $bookingRequest->cancelled_at->format('Y-m-d H:i:s'),
            'action_url' => route('admin.requests.show', $bookingRequest->id),
        ];
    }
}

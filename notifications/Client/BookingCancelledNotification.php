<?php

namespace App\Notifications\Client;

use App\Models\BookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private BookingRequest $bookingRequest,
        private string $cancelledBy,
        private float $refundAmount
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
        
        $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $content = $this->getEmailContent();
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($content['intro'])
            ->line('')
            ->line($content['details'])
            ->line('')
            ->line($content['refund_info'])
            ->line('')
            ->line($content['next_steps'])
            ->line('')
            ->action($content['action_text'], route('client.requests.show', $bookingRequest->id))
            ->line($content['closing'])
            ->salutation('Cordialement,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $bookingRequest = $this->bookingRequest;
        $tattooer = $bookingRequest->bookable;
        
        return [
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'type' => 'warning',
            'icon' => 'x-circle',
            'booking_request_id' => $bookingRequest->id,
            'tattooer_name' => $tattooer->name,
            'cancelled_by' => $this->cancelledBy,
            'refund_amount' => $this->refundAmount,
            'deposit_amount' => $bookingRequest->total_deposit_amount,
            'designs_sent' => $bookingRequest->designs_sent_count,
            'cancellation_reason' => $bookingRequest->cancellation_reason,
            'action_url' => route('client.requests.show', $bookingRequest->id),
        ];
    }

    /**
     * Get subject based on who cancelled
     */
    private function getSubject(): string
    {
        return match($this->cancelledBy) {
            'client' => '📝 Votre demande de tatouage a été annulée',
            'tattooer' => '📝 Le tatoueur a annulé votre demande',
            default => '📝 Demande de tatouage annulée',
        };
    }

    /**
     * Get greeting
     */
    private function getGreeting(): string
    {
        return 'Bonjour ' . $this->bookingRequest->client->first_name . ',';
    }

    /**
     * Get email content
     */
    private function getEmailContent(): array
    {
        $bookingRequest = $this->bookingRequest;
        $tattooer = $bookingRequest->bookable;
        
        $intro = match($this->cancelledBy) {
            'client' => 'Votre demande de tatouage a été annulée selon votre demande.',
            'tattooer' => 'Nous vous informons que le tatoueur a annulé votre demande de tatouage.',
            default => 'Votre demande de tatouage a été annulée.',
        };

        $details = "📋 Détails de la demande :\n";
        $details .= "• Tatoueur : " . $tattooer->name . "\n";
        $details .= "• Taille : " . $bookingRequest->tattoo_size . "\n";
        $details .= "• Zone : " . $bookingRequest->body_zone . "\n";
        $details .= "• Description : " . $bookingRequest->description . "\n";
        
        if ($bookingRequest->appointment_datetime) {
            $details .= "• Date prévue : " . $bookingRequest->appointment_datetime->translatedFormat('l d F Y') . "\n";
            $details .= "• Heure prévue : " . $bookingRequest->appointment_datetime->format('H:i') . "\n";
        }

        $refundInfo = $this->getRefundInfo();
        $nextSteps = $this->getNextSteps();
        $actionText = $this->getActionText();
        $closing = $this->getClosing();

        return [
            'intro' => $intro,
            'details' => $details,
            'refund_info' => $refundInfo,
            'next_steps' => $nextSteps,
            'action_text' => $actionText,
            'closing' => $closing,
        ];
    }

    /**
     * Get refund information
     */
    private function getRefundInfo(): string
    {
        if ($this->refundAmount > 0) {
            return "💰 Remboursement :\n• Montant : " . number_format($this->refundAmount, 2) . "€\n• Délai : 5-7 jours ouvrés\n• Méthode : Virement sur votre compte bancaire";
        } else {
            return "💰 Remboursement :\n• Montant : 0€ (aucun remboursement)\n• Raison : " . $this->getNoRefundReason();
        }
    }

    /**
     * Get no refund reason
     */
    private function getNoRefundReason(): string
    {
        $designsSent = $this->bookingRequest->designs_sent_count;
        
        if ($designsSent >= 3) {
            return "3+ designs ont été envoyés";
        }
        
        if ($designsSent === 2) {
            return "2 designs ont été envoyés";
        }
        
        if ($designsSent === 1) {
            return "1 design a été envoyé";
        }
        
        return "Annulation après J-3 avec design(s)";
    }

    /**
     * Get next steps
     */
    private function getNextSteps(): string
    {
        if ($this->cancelledBy === 'tattooer') {
            return "🔄 Prochaines étapes :\n• Trouver un autre tatoueur sur la plateforme\n• Consulter les profils et avis\n• Soumettre une nouvelle demande";
        } else {
            return "🔄 Prochaines étapes :\n• Vous pouvez soumettre une nouvelle demande à tout moment\n• Consulter d'autres tatoueurs disponibles\n• Nous sommes à votre disposition si besoin";
        }
    }

    /**
     * Get action text
     */
    private function getActionText(): string
    {
        if ($this->cancelledBy === 'tattooer') {
            return 'Voir les autres tatoueurs';
        } else {
            return 'Voir ma demande';
        }
    }

    /**
     * Get closing message
     */
    private function getClosing(): string
    {
        if ($this->cancelledBy === 'tattooer') {
            return "Nous sommes désolés pour ce contretemps et vous aidons à trouver un autre tatoueur.";
        } else {
            return "Merci de nous avoir informés. N'hésitez pas à nous contacter si vous avez des questions.";
        }
    }

    /**
     * Get title
     */
    private function getTitle(): string
    {
        return match($this->cancelledBy) {
            'client' => 'Demande annulée',
            'tattooer' => 'Tattooer a annulé',
            default => 'Demande annulée',
        };
    }

    /**
     * Get message
     */
    private function getMessage(): string
    {
        $tattooerName = $this->bookingRequest->bookable->name;
        
        return match($this->cancelledBy) {
            'client' => "Vous avez annulé votre demande avec {$tattooerName}",
            'tattooer' => "{$tattooerName} a annulé votre demande",
            default => "Votre demande a été annulée",
        };
    }
}

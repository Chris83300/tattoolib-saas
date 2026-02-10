<?php

namespace App\Notifications\Tattooer;

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
        $client = $bookingRequest->client;
        
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
            ->line($content['impact'])
            ->line('')
            ->action($content['action_text'], route('tattooer.requests.show', $bookingRequest->id))
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
        $client = $bookingRequest->client;
        
        return [
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'type' => 'warning',
            'icon' => 'x-circle',
            'booking_request_id' => $bookingRequest->id,
            'client_name' => $client->full_name,
            'client_pseudo' => $client->pseudo,
            'cancelled_by' => $this->cancelledBy,
            'refund_amount' => $this->refundAmount,
            'deposit_amount' => $bookingRequest->total_deposit_amount,
            'designs_sent' => $bookingRequest->designs_sent_count,
            'cancellation_reason' => $bookingRequest->cancellation_reason,
            'action_url' => route('tattooer.requests.show', $bookingRequest->id),
        ];
    }

    /**
     * Get subject based on who cancelled
     */
    private function getSubject(): string
    {
        return match($this->cancelledBy) {
            'client' => '📝 Le client a annulé sa demande',
            'tattooer' => '📝 Vous avez annulé une demande',
            default => '📝 Demande de tatouage annulée',
        };
    }

    /**
     * Get greeting
     */
    private function getGreeting(): string
    {
        return 'Bonjour ' . $this->bookingRequest->bookable->name . ',';
    }

    /**
     * Get email content
     */
    private function getEmailContent(): array
    {
        $bookingRequest = $this->bookingRequest;
        $client = $bookingRequest->client;
        
        $intro = match($this->cancelledBy) {
            'client' => 'Le client a annulé sa demande de tatouage.',
            'tattooer' => 'Vous avez annulé la demande de tatouage du client.',
            default => 'Une demande de tatouage a été annulée.',
        };

        $details = "📋 Détails de la demande :\n";
        $details .= "• Client : " . $client->full_name . " (@" . $client->pseudo . ")\n";
        $details .= "• Email : " . $client->email . "\n";
        $details .= "• Taille : " . $bookingRequest->tattoo_size . "\n";
        $details .= "• Zone : " . $bookingRequest->body_zone . "\n";
        $details .= "• Description : " . $bookingRequest->description . "\n";
        
        if ($bookingRequest->appointment_datetime) {
            $details .= "• Date prévue : " . $bookingRequest->appointment_datetime->translatedFormat('l d F Y') . "\n";
            $details .= "• Heure prévue : " . $bookingRequest->appointment_datetime->format('H:i') . "\n";
        }

        $details .= "• Designs envoyés : " . $bookingRequest->designs_sent_count . "\n";

        $refundInfo = $this->getRefundInfo();
        $impact = $this->getImpact();
        $actionText = $this->getActionText();
        $closing = $this->getClosing();

        return [
            'intro' => $intro,
            'details' => $details,
            'refund_info' => $refundInfo,
            'impact' => $impact,
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
            return "💰 Remboursement client :\n• Montant : " . number_format($this->refundAmount, 2) . "€\n• Délai : 5-7 jours ouvrés\n• Méthode : Virement sur le compte du client";
        } else {
            return "💰 Remboursement client :\n• Montant : 0€ (aucun remboursement)\n• Raison : " . $this->getNoRefundReason();
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
     * Get impact on tattooer
     */
    private function getImpact(): string
    {
        if ($this->cancelledBy === 'tattooer') {
            return "📊 Impact sur votre profil :\n• Cette annulation est comptabilisée dans vos statistiques\n• Votre taux d'acceptance peut être affecté\n• Vos disponibilités sont libérées";
        } else {
            return "📊 Impact sur votre profil :\n• Votre taux d'acceptance peut être affecté\n• Vos disponibilités sont libérées\n• Vous pouvez accepter d'autres demandes";
        }
    }

    /**
     * Get action text
     */
    private function getActionText(): string
    {
        return 'Voir la demande annulée';
    }

    /**
     * Get closing message
     */
    private function getClosing(): string
    {
        if ($this->cancelledBy === 'tattooer') {
            return "Nous espérons que vous retrouverez rapidement d'autres clients intéressés.";
        } else {
            return "Nous vous invitons à consulter d'autres demandes dans votre espace tatoueur.";
        }
    }

    /**
     * Get title
     */
    private function getTitle(): string
    {
        return match($this->cancelledBy) {
            'client' => 'Client a annulé',
            'tattooer' => 'Demande annulée',
            default => 'Demande annulée',
        };
    }

    /**
     * Get message
     */
    private function getMessage(): string
    {
        $clientName = $this->bookingRequest->client->full_name;
        
        return match($this->cancelledBy) {
            'client' => "{$clientName} a annulé sa demande",
            'tattooer' => "Vous avez annulé la demande de {$clientName}",
            default => "Demande annulée",
        };
    }
}

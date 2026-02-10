<?php

namespace App\Notifications\Client;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostTattooCareNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Appointment $appointment,
        private string $careType // '2_hours', '7_days', '14_days'
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
        
        $content = $this->getCareContent();
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($content['subject'])
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line($content['intro'])
            ->line('')
            ->line($content['details'])
            ->line('')
            ->line($content['instructions'])
            ->line('')
            ->line($content['contact_info'])
            ->line('')
            ->line($content['closing'])
            ->salutation('Prenez soin de votre nouveau tatouage !')
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
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'type' => 'info',
            'icon' => 'heart',
            'appointment_id' => $appointment->id,
            'booking_request_id' => $bookingRequest->id,
            'tattooer_name' => $tattooer->name,
            'tattoo_size' => $bookingRequest->tattoo_size,
            'body_zone' => $bookingRequest->body_zone,
            'care_type' => $this->careType,
            'action_url' => route('client.appointments.show', $appointment->id),
        ];
    }

    /**
     * Get content based on care type
     */
    private function getCareContent(): array
    {
        $appointment = $this->appointment;
        $bookingRequest = $appointment->bookingRequest;
        $tattooer = $bookingRequest->bookable;
        
        return match($this->careType) {
            '2_hours' => [
                'subject' => '🩹 Instructions de soins post-tatouage (2h après)',
                'intro' => 'Votre séance de tatouage vient de se terminer ! Voici les instructions importantes pour prendre soin de votre nouveau tatouage.',
                'details' => "📋 Rappel de votre séance :\n• Tatoueur : {$tattooer->name}\n• Taille : {$bookingRequest->tattoo_size}\n• Zone : {$bookingRequest->body_zone}\n• Durée : {$appointment->duration_minutes} minutes\n• Date : {$appointment->start_datetime->translatedFormat('l d F Y')}",
                'instructions' => "🩹 Soins immédiats (2 premières heures) :\n• Gardez le pansement en place pendant 2-4 heures\n• Évitez de toucher ou gratter la zone\n• Pas de bains, piscine ou sauna\n• Évitez les vêtements serrés sur la zone\n• Gardez la zone propre et sèche",
                'contact_info' => "📞 En cas de problème :\n• Contactez directement le tatoueur : {$tattooer->email}\n• Ne pas hésiter à poser des questions",
                'closing' => "Ces instructions sont cruciales pour une bonne cicatrisation. Suivez-les attentivement pour un résultat optimal !",
            ],
            '7_days' => [
                'subject' => '🌿 Suivi de cicatrisation (J+7)',
                'intro' => 'Il y a une semaine que vous avez fait votre tatouage ! Voici les conseils pour la suite.',
                'details' => "📋 Votre tatouage :\n• Tatoueur : {$tattooer->name}\n• Taille : {$bookingRequest->tattoo_size}\n• Zone : {$bookingRequest->body_zone}\n• Date : {$appointment->start_datetime->translatedFormat('l d F Y')}",
                'instructions' => "🌿 Soins de la semaine 1 :\n• Continuez à appliquer la crème hydratante si prescrite\n• Évitez les bains prolongés (max 10 min)\n• Pas d'exposition prolongée au soleil\n• Portez des vêtements amples et doux\n• Évitez les sports intenses",
                'contact_info' => "📞 Si vous avez des questions :\n• Contactez le tatoueur pour des conseils personnalisés\n• Prenez des photos pour suivre l\'évolution",
                'closing' => "La cicatrisation est bien engagée ! Continuez à prendre soin de votre tatouage pour un résultat magnifique.",
            ],
            '14_days' => [
                'subject' => '⭐ Invitation à laisser un avis (J+14)',
                'intro' => 'Votre tatouage a maintenant 2 semaines et devrait être bien cicatrisé !',
                'details' => "📋 Votre expérience :\n• Tatoueur : {$tattooer->name}\n• Taille : {$bookingRequest->tattoo_size}\n• Zone : {$bookingRequest->body_zone}\n• Date : {$appointment->start_datetime->translatedFormat('l d F Y')}",
                'instructions' => "⭐ Partagez votre expérience :\n• Laissez un avis sur le travail du tatoueur\n• Partagez une photo (avec accord du tatoueur)\n• Recommandez le tatoueur à vos contacts\n• Votre avis aide la communauté",
                'contact_info' => "📞 Pour laisser votre avis :\n• Connectez-vous à votre espace client\n• Allez sur la page de votre rendez-vous\n• Votre retour est très apprécié !",
                'closing' => "Merci d'avoir fait confiance à {$tattooer->name} ! Votre avis aide d'autres clients à faire le bon choix.",
            ],
            default => [
                'subject' => '🩹 Suivi post-tatouage',
                'intro' => 'Conseils pour prendre soin de votre tatouage.',
                'details' => "📋 Votre tatouage :\n• Tatoueur : {$tattooer->name}\n• Taille : {$bookingRequest->tattoo_size}\n• Zone : {$bookingRequest->body_zone}",
                'instructions' => "🩹 Suivez les conseils de votre tatoueur pour une bonne cicatrisation.",
                'contact_info' => "📞 Contactez le tatoueur si vous avez des questions.",
                'closing' => "Prenez soin de votre nouveau tatouage !",
            ],
        };
    }

    /**
     * Get title based on care type
     */
    private function getTitle(): string
    {
        return match($this->careType) {
            '2_hours' => 'Soins post-tatouage (2h)',
            '7_days' => 'Suivi de cicatrisation (J+7)',
            '14_days' => 'Invitation à laisser un avis',
            default => 'Suivi post-tatouage',
        };
    }

    /**
     * Get message based on care type
     */
    private function getMessage(): string
    {
        return match($this->careType) {
            '2_hours' => 'Instructions de soins immédiats après votre séance de tatouage',
            '7_days' => 'Conseils de cicatrisation une semaine après votre tatouage',
            '14_days' => 'Invitation à partager votre expérience et laisser un avis',
            default => 'Conseils pour prendre soin de votre tatouage',
        };
    }
}

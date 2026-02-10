<?php

namespace App\Notifications\Client;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class AppointmentReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        private Appointment $appointment,
        private string $reminderType // '7_days', '2_days', '1_day', 'same_day'
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
        
        $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $content = $this->getContent();
        
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($content['intro'])
            ->line('')
            ->line($content['details'])
            ->line('')
            ->line($content['instructions'])
            ->line('')
            ->action('Voir mon rendez-vous', route('client.appointments.show', $appointment->id))
            ->line($content['closing'])
            ->salutation('À bientôt,')
            ->line('L\'équipe Ink&Pik');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $appointment = $this->appointment;
        $bookingRequest = $appointment->bookingRequest;
        
        return [
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'type' => 'info',
            'icon' => 'calendar',
            'appointment_id' => $appointment->id,
            'booking_request_id' => $bookingRequest->id,
            'tattooer_name' => $bookingRequest->bookable->name,
            'appointment_date' => $appointment->start_datetime->format('Y-m-d'),
            'appointment_time' => $appointment->start_datetime->format('H:i'),
            'reminder_type' => $this->reminderType,
            'action_url' => route('client.appointments.show', $appointment->id),
        ];
    }

    /**
     * Get subject based on reminder type
     */
    private function getSubject(): string
    {
        return match($this->reminderType) {
            '7_days' => '📅 Rappel : Votre rendez-vous dans 7 jours',
            '2_days' => '📅 Rappel : Votre rendez-vous dans 2 jours',
            '1_day' => '📅 Rappel : Votre rendez-vous est demain !',
            'same_day' => '📅 Rappel : Votre rendez-vous est aujourd\'hui !',
            default => '📅 Rappel de rendez-vous',
        };
    }

    /**
     * Get greeting based on reminder type
     */
    private function getGreeting(): string
    {
        $notifiable = $this->getNotifiable();
        return match($this->reminderType) {
            '7_days', '2_days' => 'Bonjour ' . $notifiable->first_name . ',',
            '1_day' => 'Bonjour ' . $notifiable->first_name . ',',
            'same_day' => 'Bonjour ' . $notifiable->first_name . ',',
            default => 'Bonjour ' . $notifiable->first_name . ',',
        };
    }

    /**
     * Get content based on reminder type
     */
    private function getContent(): array
    {
        $appointment = $this->appointment;
        $bookingRequest = $appointment->bookingRequest;
        $tattooer = $bookingRequest->bookable;
        
        $dateFormatted = $appointment->start_datetime->translatedFormat('l d F Y');
        $timeFormatted = $appointment->start_datetime->format('H:i');
        
        return match($this->reminderType) {
            '7_days' => [
                'intro' => 'Ceci est un rappel pour votre rendez-vous de tatouage.',
                'details' => "📋 Rendez-vous avec {$tattooer->name}\n• Date : {$dateFormatted}\n• Heure : {$timeFormatted}\n• Durée : {$appointment->duration_minutes} minutes\n• Lieu : {$tattooer->address ?? 'À confirmer'}",
                'instructions' => '📝 Pensez à confirmer votre présence et à préparer votre venue.',
                'closing' => 'Nous vous contacterons à nouveau 2 jours avant le rendez-vous.',
            ],
            '2_days' => [
                'intro' => 'Votre rendez-vous de tatouage est dans 2 jours !',
                'details' => "📋 Rendez-vous avec {$tattooer->name}\n• Date : {$dateFormatted}\n• Heure : {$timeFormatted}\n• Durée : {$appointment->duration_minutes} minutes",
                'instructions' => '📝 Assurez-vous d\'être prêt et de bien dormir la veille.',
                'closing' => 'Profitez bien de votre séance !',
            ],
            '1_day' => [
                'intro' => 'Votre rendez-vous de tatouage est demain !',
                'details' => "📋 Rendez-vous avec {$tattooer->name}\n• Date : {$dateFormatted}\n• Heure : {$timeFormatted}\n• Durée : {$appointment->duration_minutes} minutes",
                'instructions' => '📝 Préparez-vous et évitez l\'alcool avant la séance.',
                'closing' => 'Nous vous souhaitons une excellente séance !',
            ],
            'same_day' => [
                'intro' => 'Votre rendez-vous de tatouage est aujourd\'hui !',
                'details' => "📋 Rendez-vous avec {$tattooer->name}\n• Date : {$dateFormatted}\n• Heure : {$timeFormatted}\n• Durée : {$appointment->duration_minutes} minutes",
                'instructions' => '📝 N\'oubliez pas de manger avant la séance et d\'arriver à l\'heure.',
                'closing' => 'Le tatoueur vous attend !',
            ],
            default => [
                'intro' => 'Rappel de votre rendez-vous de tatouage.',
                'details' => "📋 Rendez-vous avec {$tattooer->name}\n• Date : {$dateFormatted}\n• Heure : {$timeFormatted}",
                'instructions' => '📝 Préparez-vous pour votre séance.',
                'closing' => 'À bientôt !',
            ],
        };
    }

    /**
     * Get title based on reminder type
     */
    private function getTitle(): string
    {
        return match($this->reminderType) {
            '7_days' => 'Rendez-vous dans 7 jours',
            '2_days' => 'Rendez-vous dans 2 jours',
            '1_day' => 'Rendez-vous demain',
            'same_day' => 'Rendez-vous aujourd\'hui',
            default => 'Rappel de rendez-vous',
        };
    }

    /**
     * Get message based on reminder type
     */
    private function getMessage(): string
    {
        $appointment = $this->appointment;
        $dateFormatted = $appointment->start_datetime->translatedFormat('l d F Y');
        $timeFormatted = $appointment->start_datetime->format('H:i');
        
        return match($this->reminderType) {
            '7_days' => "Votre rendez-vous du {$dateFormatted} à {$timeFormatted} est dans 7 jours.",
            '2_days' => "Votre rendez-vous du {$dateFormatted} à {$timeFormatted} est dans 2 jours.",
            '1_day' => "Votre rendez-vous du {$dateFormatted} à {$timeFormatted} est demain.",
            'same_day' => "Votre rendez-vous d'aujourd'hui à {$timeFormatted} approche.",
            default => "Rappel de votre rendez-vous.",
        };
    }

    /**
     * Get the notifiable model
     */
    private function getNotifiable()
    {
        return $this->appointment->client->user;
    }
}

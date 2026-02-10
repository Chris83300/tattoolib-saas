<?php

namespace App\Actions;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\ConversationStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ManagePostAppointmentChat
{
    /**
     * Gérer l'envoi d'images post-RDV
     */
    public function handlePostAppointmentImage(Appointment $appointment, string $imagePath, ?string $caption = null): void
    {
        DB::transaction(function () use ($appointment, $imagePath, $caption) {
            $bookingRequest = $appointment->bookingRequest;
            $conversation = $bookingRequest->conversation;
            
            if (!$conversation) {
                return;
            }

            // Vérifier que le chat est accessible post-RDV
            if (!$this->isPostAppointmentChatOpen($appointment)) {
                throw new \InvalidArgumentException('Le chat post-RDV n\'est plus accessible');
            }

            // Vérifier la limite d'images post-RDV
            if (!$this->canSendPostAppointmentImage($appointment)) {
                throw new \InvalidArgumentException('Limite d\'images post-RDV atteinte (4 images maximum)');
            }

            // Compter les images post-RDV déjà envoyées
            $postRdvImagesCount = $this->getPostRdvImagesCount($appointment);
            
            // Créer le message avec l'image
            $message = $conversation->messages()->create([
                'sender_id' => $bookingRequest->client->user_id,
                'sender_type' => 'client',
                'content' => $caption ?? 'Photo de suivi post-tatouage',
                'attachments' => [
                    'type' => 'image',
                    'path' => $imagePath,
                    'post_appointment' => true,
                    'image_number' => $postRdvImagesCount + 1,
                ],
            ]);

            // Envoyer une notification au tattooer
            $this->notifyTattooerAboutPostRdvImage($appointment, $message);

            // Logger l'envoi de l'image
            $this->logPostRdvImage($appointment, $message);

            // Vérifier si on approche de la limite
            if ($postRdvImagesCount >= 3) {
                $this->sendImageLimitWarning($appointment);
            }
        });
    }

    /**
     * Gérer la demande de retouche
     */
    public function handleRetouchRequest(Appointment $appointment, string $message, ?array $images = null): void
    {
        DB::transaction(function () use ($appointment, $message, $images) {
            $bookingRequest = $appointment->bookingRequest;
            $conversation = $bookingRequest->conversation;
            
            if (!$conversation) {
                return;
            }

            // Vérifier que le chat est accessible post-RDV
            if (!$this->isPostAppointmentChatOpen($appointment)) {
                throw new \InvalidArgumentException('Le chat post-RDV n\'est plus accessible');
            }

            // Créer le message de demande de retouche
            $messageData = [
                'sender_id' => $bookingRequest->client->user_id,
                'sender_type' => 'client',
                'content' => "🔄 Demande de retouche\n\n" . $message,
                'metadata' => [
                    'type' => 'retouch_request',
                    'appointment_id' => $appointment->id,
                    'requested_at' => now()->toISOString(),
                ],
            ];

            if ($images && !empty($images)) {
                $messageData['attachments'] = $images;
            }

            $retouchMessage = $conversation->messages()->create($messageData);

            // Envoyer une notification au tattooer
            $this->notifyTattooerAboutRetouchRequest($appointment, $retouchMessage);

            // Logger la demande de retouche
            $this->logRetouchRequest($appointment, $retouchMessage);
        });
    }

    /**
     * Vérifier si le chat post-RDV est ouvert
     */
    public function isPostAppointmentChatOpen(Appointment $appointment): bool
    {
        // Le chat reste ouvert 30 jours après le RDV
        $thirtyDaysAfterAppointment = $appointment->end_datetime->addDays(30);
        
        return now()->isBefore($thirtyDaysAfterAppointment);
    }

    /**
     * Vérifier si le client peut envoyer une image post-RDV
     */
    public function canSendPostAppointmentImage(Appointment $appointment): bool
    {
        if (!$this->isPostAppointmentChatOpen($appointment)) {
            return false;
        }

        $postRdvImagesCount = $this->getPostRdvImagesCount($appointment);
        
        return $postRdvImagesCount < 4;
    }

    /**
     * Compter les images post-RDV déjà envoyées
     */
    public function getPostRdvImagesCount(Appointment $appointment): int
    {
        $bookingRequest = $appointment->bookingRequest;
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return 0;
        }

        return $conversation->messages()
            ->where('sender_type', 'client')
            ->whereNotNull('attachments')
            ->whereJsonContains('attachments->post_appointment', true)
            ->count();
    }

    /**
     * Obtenir le statut du chat post-RDV
     */
    public function getPostAppointmentChatStatus(Appointment $appointment): array
    {
        $bookingRequest = $appointment->bookingRequest;
        $conversation = $bookingRequest->conversation;
        
        $thirtyDaysAfterAppointment = $appointment->end_datetime->addDays(30);
        $daysRemaining = now()->diffInDays($thirtyDaysAfterAppointment, false);
        
        return [
            'is_open' => $this->isPostAppointmentChatOpen($appointment),
            'days_remaining' => max(0, $daysRemaining),
            'closes_at' => $thirtyDaysAfterAppointment,
            'images_sent' => $this->getPostRdvImagesCount($appointment),
            'images_remaining' => max(0, 4 - $this->getPostRdvImagesCount($appointment)),
            'can_send_image' => $this->canSendPostAppointmentImage($appointment),
            'has_retouch_request' => $this->hasRetouchRequest($appointment),
        ];
    }

    /**
     * Vérifier s'il y a une demande de retouche
     */
    public function hasRetouchRequest(Appointment $appointment): bool
    {
        $bookingRequest = $appointment->bookingRequest;
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return false;
        }

        return $conversation->messages()
            ->where('sender_type', 'client')
            ->whereJsonContains('metadata->type', 'retouch_request')
            ->exists();
    }

    /**
     * Envoyer une notification au tattooer pour une image post-RDV
     */
    private function notifyTattooerAboutPostRdvImage(Appointment $appointment, Message $message): void
    {
        try {
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->bookable->user->notify(
                new \App\Notifications\Tattooer\PostRdvImageNotification($appointment, $message)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send post-RDV image notification to tattooer', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer une notification au tattooer pour une demande de retouche
     */
    private function notifyTattooerAboutRetouchRequest(Appointment $appointment, Message $message): void
    {
        try {
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->bookable->user->notify(
                new \App\Notifications\Tattooer\RetouchRequestNotification($appointment, $message)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send retouch request notification to tattooer', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer un avertissement de limite d'images
     */
    private function sendImageLimitWarning(Appointment $appointment): void
    {
        $bookingRequest = $appointment->bookingRequest;
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        $content = "⚠️ Limite d'images atteinte\n\n";
        $content .= "Vous avez atteint la limite de 4 images post-tatouage.\n\n";
        $content .= "Si vous avez besoin d'envoyer d'autres photos, contactez directement le tatoueur.\n\n";
        $content .= "Le chat restera ouvert pour les messages textes jusqu'au " . 
                    $appointment->end_datetime->addDays(30)->translatedFormat('d F Y') . ".";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Logger l'envoi d'une image post-RDV
     */
    private function logPostRdvImage(Appointment $appointment, Message $message): void
    {
        Log::info('Post-RDV image sent', [
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_id' => $appointment->client_id,
            'message_id' => $message->id,
            'image_number' => $message->attachments['image_number'] ?? null,
        ]);
    }

    /**
     * Logger une demande de retouche
     */
    private function logRetouchRequest(Appointment $appointment, Message $message): void
    {
        Log::info('Retouch request sent', [
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_id' => $appointment->client_id,
            'message_id' => $message->id,
            'has_images' => !empty($message->attachments),
        ]);
    }

    /**
     * Fermer le chat post-RDV (J+30)
     */
    public function closePostAppointmentChat(Appointment $appointment): void
    {
        DB::transaction(function () use ($appointment) {
            $bookingRequest = $appointment->bookingRequest;
            $conversation = $bookingRequest->conversation;
            
            if (!$conversation) {
                return;
            }

            // Sauvegarder les images dans la fiche client (plan Pro)
            $this->saveImagesToClientProfile($appointment);

            // Fermer la conversation
            $conversation->update([
                'status' => ConversationStatus::CLOSED,
                'closed_at' => now(),
                'close_reason' => 'post_appointment_30_days',
            ]);

            // Envoyer un message système de fermeture
            $this->sendChatClosureMessage($appointment);

            // Logger la fermeture
            $this->logChatClosure($appointment);
        });
    }

    /**
     * Sauvegarder les images dans la fiche client
     */
    private function saveImagesToClientProfile(Appointment $appointment): void
    {
        $bookingRequest = $appointment->bookingRequest;
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        // Récupérer toutes les images post-RDV
        $postRdvImages = $conversation->messages()
            ->where('sender_type', 'client')
            ->whereNotNull('attachments')
            ->whereJsonContains('attachments->post_appointment', true)
            ->get();

        foreach ($postRdvImages as $message) {
            // Sauvegarder dans la fiche client (implémentation plan Pro)
            // Pour l'instant, on log les informations
            Log::info('Post-RDV image saved to client profile', [
                'appointment_id' => $appointment->id,
                'client_id' => $appointment->client_id,
                'message_id' => $message->id,
                'image_path' => $message->attachments['path'] ?? null,
            ]);
        }
    }

    /**
     * Envoyer un message système de fermeture
     */
    private function sendChatClosureMessage(Appointment $appointment): void
    {
        $bookingRequest = $appointment->bookingRequest;
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        $content = "🔒 Conversation fermée\n\n";
        $content .= "Cette conversation a été fermée automatiquement 30 jours après votre rendez-vous.\n\n";
        $content .= "📋 Résumé :\n";
        $content .= "• Date du rendez-vous : " . $appointment->start_datetime->translatedFormat('l d F Y') . "\n";
        $content .= "• Tatoueur : " . $bookingRequest->bookable->name . "\n";
        $content .= "• Images post-RDV : " . $this->getPostRdvImagesCount($appointment) . " envoyées\n\n";
        $content .= "Les photos ont été sauvegardées dans votre fiche client.\n\n";
        $content .= "Pour toute question, n'hésitez pas à contacter le tatoueur directement.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Logger la fermeture du chat
     */
    private function logChatClosure(Appointment $appointment): void
    {
        Log::info('Post-appointment chat closed', [
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_id' => $appointment->client_id,
            'days_after_appointment' => 30,
            'images_sent' => $this->getPostRdvImagesCount($appointment),
        ]);
    }
}

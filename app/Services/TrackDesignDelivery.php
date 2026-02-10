<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\BookingRequest;
use App\Enums\ConversationStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TrackDesignDelivery
{
    /**
     * Gérer l'envoi d'une image par le tattooer (dessin)
     */
    public function handleTattooerImage(Conversation $conversation, Message $message): void
    {
        DB::transaction(function () use ($conversation, $message) {
            $booking = $conversation->bookingRequest;

            if (!$booking) {
                Log::warning('No booking request found for conversation', [
                    'conversation_id' => $conversation->id
                ]);
                return;
            }

            // Incrémenter le compteur de dessins envoyés
            $booking->increment('designs_sent_count');

            // Reset le compteur de modifications pour ce nouveau dessin
            $booking->update(['current_design_modifications_count' => 0]);

            // Envoyer un message système de suivi
            $this->sendDesignTrackingMessage($conversation, $booking);

            // Logger l'événement
            $this->logDesignDelivery($booking, $message);

            // Vérifier si tous les designs inclus ont été envoyés
            $this->checkDesignLimits($booking, $conversation);
        });
    }

    /**
     * Gérer une demande de modification par le client
     */
    public function handleModificationRequest(Conversation $conversation, Message $message): void
    {
        DB::transaction(function () use ($conversation, $message) {
            $booking = $conversation->bookingRequest;

            if (!$booking) {
                Log::warning('No booking request found for modification request', [
                    'conversation_id' => $conversation->id
                ]);
                return;
            }

            // Incrémenter le compteur de modifications
            $booking->increment('current_design_modifications_count');

            // Envoyer un message système de suivi
            $this->sendModificationTrackingMessage($conversation, $booking);

            // Logger l'événement
            $this->logModificationRequest($booking, $message);

            // Vérifier si les limites de modifications sont atteintes
            $this->checkModificationLimits($booking, $conversation);
        });
    }

    /**
     * Envoyer un message système après envoi d'un dessin
     */
    private function sendDesignTrackingMessage(Conversation $conversation, BookingRequest $booking): void
    {
        $remaining = $booking->included_designs - $booking->designs_sent_count;

        if ($remaining > 0) {
            $content = "🎨 Dessin #{$booking->designs_sent_count} envoyé !\n\n";
            $content .= "✅ {$remaining} dessin(s) restant(s) inclus dans votre acompte.";
        } else {
            $content = "🎨 Dessin #{$booking->designs_sent_count} envoyé !\n\n";
            $content .= "📋 Tous les dessins inclus ont été livrés.\n";
            $content .= "💡 Pour tout dessin supplémentaire, le tatoueur pourra vous proposer une option payante.";
        }

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Envoyer un message système après demande de modification
     */
    private function sendModificationTrackingMessage(Conversation $conversation, BookingRequest $booking): void
    {
        $remaining = $booking->modifications_per_design - $booking->current_design_modifications_count;

        if ($remaining > 0) {
            $content = "✏️ Demande de modification enregistrée !\n\n";
            $content .= "📊 Modifications restantes pour ce dessin : {$remaining}/{$booking->modifications_per_design}";
        } else {
            $content = "⚠️ Limite de modifications atteinte !\n\n";
            $content .= "Vous avez utilisé les {$booking->modifications_per_design} modifications incluses pour ce dessin.\n";
            $content .= "💬 Le tatoueur vous proposera des options pour continuer.";
        }

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Vérifier si les limites de designs sont atteintes
     */
    private function checkDesignLimits(BookingRequest $booking, Conversation $conversation): void
    {
        if ($booking->designs_sent_count >= $booking->included_designs) {
            // Notifier le tattooer que tous les designs inclus ont été envoyés
            $this->notifyTattooerDesignLimitReached($booking, $conversation);
        }
    }

    /**
     * Vérifier si les limites de modifications sont atteintes
     */
    private function checkModificationLimits(BookingRequest $booking, Conversation $conversation): void
    {
        if ($booking->current_design_modifications_count >= $booking->modifications_per_design) {
            // Notifier le tattooer que les modifications sont épuisées
            $this->notifyTattooerModificationLimitReached($booking, $conversation);
        }
    }

    /**
     * Notifier le tattooer que la limite de designs est atteinte
     */
    private function notifyTattooerDesignLimitReached(BookingRequest $booking, Conversation $conversation): void
    {
        $content = "🔔 Notification pour le tatoueur :\n\n";
        $content .= "Tous les {$booking->included_designs} designs inclus ont été livrés.\n\n";
        $content .= "Options disponibles :\n";
        $content .= "1️⃣ Continuer gratuitement (optionnel)\n";
        $content .= "2️⃣ Proposer un supplément payant\n\n";
        $content .= "Utilisez le bouton 'Options de surplus' pour choisir.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
            'is_tattooer_only' => true, // Champ à ajouter si nécessaire
        ]);
    }

    /**
     * Notifier le tattooer que les modifications sont épuisées
     */
    private function notifyTattooerModificationLimitReached(BookingRequest $booking, Conversation $conversation): void
    {
        $content = "🔔 Notification pour le tatoueur :\n\n";
        $content .= "Le client a utilisé les {$booking->modifications_per_design} modifications incluses.\n\n";
        $content .= "Options disponibles :\n";
        $content .= "1️⃣ Accepter une modification gratuite\n";
        $content .= "2️⃣ Proposer un supplément payant\n\n";
        $content .= "Utilisez le bouton 'Options de surplus' pour choisir.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
            'is_tattooer_only' => true, // Champ à ajouter si nécessaire
        ]);
    }

    /**
     * Logger la livraison d'un dessin
     */
    private function logDesignDelivery(BookingRequest $booking, Message $message): void
    {
        Log::info('Design delivered', [
            'booking_request_id' => $booking->id,
            'message_id' => $message->id,
            'design_count' => $booking->designs_sent_count,
            'included_designs' => $booking->included_designs,
            'remaining_designs' => $booking->included_designs - $booking->designs_sent_count,
        ]);
    }

    /**
     * Logger une demande de modification
     */
    private function logModificationRequest(BookingRequest $booking, Message $message): void
    {
        Log::info('Modification requested', [
            'booking_request_id' => $booking->id,
            'message_id' => $message->id,
            'modification_count' => $booking->current_design_modifications_count,
            'max_modifications' => $booking->modifications_per_design,
            'remaining_modifications' => $booking->modifications_per_design - $booking->current_design_modifications_count,
        ]);
    }

    /**
     * Obtenir le statut actuel des compteurs pour l'affichage UI
     */
    public function getDesignStatus(BookingRequest $booking): array
    {
        return [
            'designs_sent' => $booking->designs_sent_count,
            'included_designs' => $booking->included_designs,
            'remaining_designs' => max(0, $booking->included_designs - $booking->designs_sent_count),
            'designs_percentage' => $booking->included_designs > 0 
                ? ($booking->designs_sent_count / $booking->included_designs) * 100 
                : 0,
            'current_modifications' => $booking->current_design_modifications_count,
            'max_modifications' => $booking->modifications_per_design,
            'remaining_modifications' => max(0, $booking->modifications_per_design - $booking->current_design_modifications_count),
            'modifications_percentage' => $booking->modifications_per_design > 0 
                ? ($booking->current_design_modifications_count / $booking->modifications_per_design) * 100 
                : 0,
            'needs_overage_decision' => $booking->designs_sent_count >= $booking->included_designs 
                && !$booking->overage_decision,
        ];
    }
}

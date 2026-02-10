<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Tattooer;
use App\Models\Pierceur;
use App\Enums\ConversationStatus;
use App\Enums\BookingRequestStatus;

class ConversationPolicy
{
    /**
     * Voir une conversation
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $this->isParticipant($user, $conversation);
    }

    /**
     * Envoyer un message texte
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        if (!$this->isParticipant($user, $conversation)) {
            return false;
        }

        // Utiliser l'enum pour vérifier si le statut permet les messages
        return $conversation->status->allowsMessaging();
    }

    /**
     * Envoyer une image/pièce jointe
     */
    public function sendImage(User $user, Conversation $conversation): bool
    {
        if (!$this->isParticipant($user, $conversation)) {
            return false;
        }

        // Vérifier si le statut permet les images
        if (!$conversation->status->allowsImages()) {
            return false;
        }

        // Règles spécifiques selon le rôle
        if ($user->isClient()) {
            return $this->canClientSendImage($user, $conversation);
        }

        // Tattooer peut envoyer des images (dessins) en FULL_ACCESS
        return true;
    }

    /**
     * Client peut envoyer des images uniquement après RDV (suivi/retouches)
     */
    private function canClientSendImage(User $user, Conversation $conversation): bool
    {
        $booking = $conversation->bookingRequest;

        if (!$booking) {
            return false;
        }

        $appointment = $booking->appointment;

        // Client peut envoyer des images UNIQUEMENT après le RDV
        if (!$appointment || $appointment->start_datetime->isFuture()) {
            return false;
        }

        // Maximum 4 images post-RDV
        $postRdvImages = $conversation->messages()
            ->where('sender_type', 'client')
            ->where('created_at', '>', $appointment->end_datetime)
            ->whereNotNull('attachments')
            ->count();

        return $postRdvImages < 4;
    }

    /**
     * Demander une modification de dessin
     */
    public function requestModification(User $user, Conversation $conversation): bool
    {
        if (!$this->isParticipant($user, $conversation) || !$user->isClient()) {
            return false;
        }

        if (!$conversation->status->allowsMessaging()) {
            return false;
        }

        $booking = $conversation->bookingRequest;

        if (!$booking) {
            return false;
        }

        // Vérifier qu'il reste des modifications disponibles
        $remaining = $booking->modifications_per_design - $booking->current_design_modifications_count;

        return $remaining > 0;
    }

    /**
     * Envoyer un dessin (tattooer uniquement)
     */
    public function sendDesign(User $user, Conversation $conversation): bool
    {
        if (!$this->isParticipant($user, $conversation) || !$user->isTattooer()) {
            return false;
        }

        if (!$conversation->status->allowsImages()) {
            return false;
        }

        $booking = $conversation->bookingRequest;

        if (!$booking) {
            return false;
        }

        // Vérifier qu'il reste des designs inclus
        $remaining = $booking->included_designs - $booking->designs_sent_count;

        return $remaining > 0 || $this->hasOverageDecision($booking);
    }

    /**
     * Vérifier si une décision de surplus a été prise
     */
    private function hasOverageDecision($booking): bool
    {
        return in_array($booking->overage_decision, ['free', 'surcharge']);
    }

    /**
     * Archiver une conversation (PRO uniquement)
     */
    public function archive(User $user, Conversation $conversation): bool
    {
        if (!$this->isParticipant($user, $conversation)) {
            return false;
        }

        // Utiliser l'enum pour vérifier si la conversation peut être archivée
        if (!$conversation->status->canBeArchived()) {
            return false;
        }

        // Seuls les artistes PRO peuvent archiver
        if ($user->isTattooer()) {
            return $user->tattooer->is_subscribed;
        }

        if ($user->isPierceur()) {
            return $user->pierceur->is_subscribed;
        }

        if ($user->isStudioArtist()) {
            return $user->studioArtist->is_subscribed;
        }

        return false;
    }

    /**
     * Supprimer une conversation (admin uniquement)
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        if (!$user->isAdmin()) {
            return false;
        }

        // Utiliser l'enum pour vérifier si la conversation peut être supprimée
        return $conversation->status->canBeDeleted();
    }

    /**
     * Télécharger une pièce jointe
     */
    public function downloadAttachment(User $user, Conversation $conversation): bool
    {
        if (!$this->isParticipant($user, $conversation)) {
            return false;
        }

        // Les pièces jointes ne sont accessibles que si la conversation n'est pas en lecture seule
        return !$conversation->status->isReadOnly();
    }

    /**
     * Mettre à jour la conversation
     */
    public function update(User $user, Conversation $conversation): bool
    {
        return $this->isParticipant($user, $conversation);
    }

    /**
     * Vérifier si utilisateur est participant
     */
    private function isParticipant(User $user, Conversation $conversation): bool
    {
        // Via BookingRequest
        if ($conversation->bookingRequest) {
            $booking = $conversation->bookingRequest;

            // Client propriétaire
            if ($user->isClient() && $booking->client_id === $user->client?->id) {
                return true;
            }

            // Artiste destinataire
            if ($user->isTattooer() || $user->isPierceur()) {
                $profile = $user->isTattooer() ? $user->tattooer : $user->pierceur;

                return $booking->bookable_id === $profile->id
                    && $booking->bookable_type === get_class($profile);
            }

            // Studio artist
            if ($user->isStudioArtist()) {
                return $booking->bookable->user_id === $user->id;
            }
        }

        // Fallback avec méthode existante
        return $conversation->hasParticipant($user->id);
    }

    /**
     * Obtenir les restrictions actuelles pour l'affichage UI
     */
    public function getRestrictions(User $user, Conversation $conversation): array
    {
        if (!$this->isParticipant($user, $conversation)) {
            return [
                'can_message' => false,
                'can_send_image' => false,
                'can_request_modification' => false,
                'can_send_design' => false,
                'reason' => 'not_participant'
            ];
        }

        $status = $conversation->status;
        $booking = $conversation->bookingRequest;

        return [
            'can_message' => $status->allowsMessaging(),
            'can_send_image' => $this->sendImage($user, $conversation),
            'can_request_modification' => $this->requestModification($user, $conversation),
            'can_send_design' => $this->sendDesign($user, $conversation),
            'status' => $status->value,
            'status_label' => $status->label(),
            'status_color' => $status->color(),
            'is_read_only' => $status->isReadOnly(),
            'remaining_designs' => $booking ? $booking->included_designs - $booking->designs_sent_count : 0,
            'remaining_modifications' => $booking ? $booking->modifications_per_design - $booking->current_design_modifications_count : 0,
        ];
    }
}

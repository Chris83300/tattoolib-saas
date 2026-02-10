<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Voir un message
     */
    public function view(User $user, Message $message): bool
    {
        return $user->can('view', $message->conversation);
    }

    /**
     * Supprimer un message (uniquement son propre message dans les 5min)
     */
    public function delete(User $user, Message $message): bool
    {
        // Vérifier que c'est son propre message
        if ($message->sender_id !== $user->id) {
            return false;
        }

        // Vérifier que message a moins de 5 minutes
        if ($message->created_at->diffInMinutes(now()) > 5) {
            return false;
        }

        return true;
    }

    /**
     * Télécharger une pièce jointe
     */
    public function downloadAttachment(User $user, Message $message): bool
    {
        return $user->can('view', $message->conversation);
    }

    /**
     * Marquer comme lu
     */
    public function markAsRead(User $user, Message $message): bool
    {
        // Seul le destinataire peut marquer comme lu
        if ($message->sender_id === $user->id) {
            return false;
        }

        return $user->can('view', $message->conversation);
    }

    /**
     * Mettre à jour un message
     */
    public function update(User $user, Message $message): bool
    {
        // Seul l'expéditeur peut modifier son message
        return $message->sender_id === $user->id;
    }
}

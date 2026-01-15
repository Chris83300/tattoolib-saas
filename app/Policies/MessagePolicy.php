<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;

class MessagePolicy
{
    /**
     * Determine if the user can view the message.
     */
    public function view(User $user, Message $message): bool
    {
        return $message->conversation->hasParticipant($user->id);
    }

    /**
     * Determine if the user can delete the message.
     */
    public function delete(User $user, Message $message): bool
    {
        // Seul l'expéditeur peut supprimer son propre message
        return $message->sender_id === $user->id;
    }

    /**
     * Determine if the user can update the message.
     */
    public function update(User $user, Message $message): bool
    {
        // Seul l'expéditeur peut modifier son message (si besoin)
        return $message->sender_id === $user->id;
    }
}

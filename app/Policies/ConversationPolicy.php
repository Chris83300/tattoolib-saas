<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Determine if the user can view the conversation.
     */
    public function view(User $user, Conversation $conversation): bool
    {
        return $conversation->hasParticipant($user->id);
    }

    /**
     * Determine if the user can send messages in the conversation.
     */
    public function sendMessage(User $user, Conversation $conversation): bool
    {
        return $conversation->hasParticipant($user->id)
            && $conversation->status === 'active';
    }

    /**
     * Determine if the user can update the conversation.
     */
    public function update(User $user, Conversation $conversation): bool
    {
        return $conversation->hasParticipant($user->id);
    }

    /**
     * Determine if the user can delete the conversation.
     */
    public function delete(User $user, Conversation $conversation): bool
    {
        // Seul un admin peut supprimer une conversation
        return $user->hasRole('admin');
    }
}

<?php

namespace App\Observers;

use App\Models\Conversation;
use App\Models\Message;

class ConversationObserver
{
    /**
     * Événement déclenché après la création d'un message
     */
    public function created(Message $message): void
    {
        $message->loadMissing('conversation');

        // Si le message n'a pas de conversation (messages de projet), ignorer
        if (!$message->conversation) {
            return;
        }

        $message->conversation->update([
            'last_message_id' => $message->id,
            'last_message_at' => $message->created_at,
        ]);
    }

    /**
     * Événement déclenché après la suppression d'un message
     */
    public function deleted(Message $message): void
    {
        $message->loadMissing('conversation');
        $conversation = $message->conversation;

        // Si le message supprimé était le dernier message de la conversation
        if ($conversation->last_message_id === $message->id) {
            $lastMessage = $conversation->messages()
                ->latest('id')
                ->first();

            $conversation->update([
                'last_message_id' => $lastMessage?->id,
                'last_message_at' => $lastMessage?->created_at,
            ]);
        }
    }
}

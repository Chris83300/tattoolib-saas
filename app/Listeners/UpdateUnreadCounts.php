<?php

namespace App\Listeners;

use App\Events\MessageCreated;

class UpdateUnreadCounts
{
    public function handle(MessageCreated $event): void
    {
        $message = $event->message;
        $conversation = $message->conversation;

        // Si le message n'a pas de conversation (messages de projet), ignorer
        if (!$conversation) {
            return;
        }

        // Marquer comme non lu pour tous les participants SAUF l'expéditeur
        $conversation->participants()
            ->where('user_id', '!=', $message->sender_id) // ✅ CORRIGÉ
            ->each(function ($user) {
                // Réinitialiser last_read_at pour marquer comme non lu
                $user->pivot->update([
                    'last_read_at' => null
                ]);
            });
    }
}

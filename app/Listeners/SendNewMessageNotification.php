<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Notifications\NewMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

// ===== SEND NEW MESSAGE NOTIFICATION (CORRIGÉ) =====

class SendNewMessageNotification implements ShouldQueue
{
    public function handle(MessageCreated $event): void
    {
        $message = $event->message;

        if (!$message instanceof \App\Models\Message) {
            Log::error('Invalid message instance', [
                'type' => get_class($message),
            ]);
            return;
        }

        // Récupérer tous les participants SAUF l'expéditeur
        $recipients = $message->conversation->participants()
            ->where('user_id', '!=', $message->sender_id) // ✅ CORRIGÉ
            ->get();

        // Envoyer la notification à chaque participant
        foreach ($recipients as $user) {
            // Vérifier si l'utilisateur a mis en sourdine la conversation
            if ($user->pivot->is_muted) {
                continue;
            }

            $user->notify(new NewMessageNotification($message));
        }

        Log::info('Message notifications sent', [
            'message_id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'recipients_count' => $recipients->count(),
        ]);
    }
}

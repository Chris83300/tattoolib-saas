<?php

namespace App\Listeners;

use App\Events\MessageDeleted;

class CleanupMessageMedia
{
    public function handle(MessageDeleted $event)
    {
        $message = $event->message;

        // ✅ Utiliser Spatie MediaLibrary au lieu de vérifier media_path
        if ($message->hasMedia('attachments')) {
            $message->clearMediaCollection('attachments');
        }
    }
}

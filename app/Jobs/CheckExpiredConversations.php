<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Conversation;
use App\Events\ConversationExpiring;
use App\Events\ConversationExpired;
use Illuminate\Support\Facades\Log;

class CheckExpiredConversations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('🔍 Vérification conversations expirées...');

        // ===========================================
        // 1. ENVOYER ALERTES J-2
        // ===========================================
        
        $expiringSoon = Conversation::where('is_expired', false)
            ->whereNotNull('expires_at')
            ->whereNull('expiry_warning_sent_at')
            ->where('expires_at', '<=', now()->addDays(Conversation::EXPIRY_WARNING_DAYS))
            ->get();

        foreach ($expiringSoon as $conversation) {
            if ($conversation->shouldSendExpiryWarning()) {
                event(new ConversationExpiring($conversation));
                $conversation->update(['expiry_warning_sent_at' => now()]);
                
                Log::info("⚠️ Alerte expiration envoyée", [
                    'conversation_id' => $conversation->id,
                    'expires_at' => $conversation->expires_at,
                ]);
            }
        }

        // ===========================================
        // 2. MARQUER CONVERSATIONS EXPIRÉES
        // ===========================================
        
        $toExpire = Conversation::where('is_expired', false)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($toExpire as $conversation) {
            $conversation->markAsExpired();
            event(new ConversationExpired($conversation));
            
            Log::info("❌ Conversation marquée expirée", [
                'conversation_id' => $conversation->id,
                'expiry_type' => $conversation->expiry_type,
            ]);
        }

        // ===========================================
        // 3. SUPPRIMER CONVERSATIONS EXPIRÉES (Phase 1)
        // ===========================================
        
        $phase1ToDelete = Conversation::expired()
            ->depositPending()
            ->get();

        foreach ($phase1ToDelete as $conversation) {
            Log::info("🗑️ Suppression conversation Phase 1", [
                'conversation_id' => $conversation->id,
            ]);
            $conversation->deleteCompletely();
        }

        Log::info("✅ Vérification terminée", [
            'alerts_sent' => $expiringSoon->count(),
            'marked_expired' => $toExpire->count(),
            'deleted_phase1' => $phase1ToDelete->count(),
        ]);
    }
}

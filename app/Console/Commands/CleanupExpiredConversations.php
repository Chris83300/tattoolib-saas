<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conversation;
use App\Events\ConversationExpiring;
use App\Events\ConversationExpired;

class CleanupExpiredConversations extends Command
{
    protected $signature = 'conversations:cleanup';
    protected $description = 'Nettoie conversations expirées selon leur type';

    public function handle()
    {
        $this->info('🧹 Nettoyage conversations expirées...');

        // ===========================================
        // 1. ENVOYER ALERTES J-2
        // ===========================================

        $this->info('📧 Envoi alertes expiration...');

        $expiring = Conversation::where('is_expired', false)
            ->whereNotNull('expires_at')
            ->whereNull('expiry_warning_sent_at')
            ->where('expires_at', '<=', now()->addDays(Conversation::EXPIRY_WARNING_DAYS))
            ->get();

        foreach ($expiring as $conversation) {
            if ($conversation->shouldSendExpiryWarning()) {
                event(new ConversationExpiring($conversation));

                $conversation->update(['expiry_warning_sent_at' => now()]);

                $this->warn("  ⚠️ Alerte envoyée : Conversation #{$conversation->id}");
            }
        }

        $this->info("✅ {$expiring->count()} alertes envoyées");

        // ===========================================
        // 2. MARQUER CONVERSATIONS EXPIRÉES
        // ===========================================

        $this->info('🔍 Détection conversations expirées...');

        $toExpire = Conversation::where('is_expired', false)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($toExpire as $conversation) {
            $conversation->markAsExpired();

            event(new ConversationExpired($conversation));

            $this->warn("  ❌ Expirée : Conversation #{$conversation->id} ({$conversation->expiry_type})");
        }

        $this->info("✅ {$toExpire->count()} conversations marquées expirées");

        // ===========================================
        // 3. SUPPRIMER CONVERSATIONS PHASE 1 (deposit_pending)
        // ===========================================

        $this->info('🗑️ Suppression conversations Phase 1 (acompte non payé)...');

        $phase1Expired = Conversation::expired()
            ->depositPending()
            ->get();

        foreach ($phase1Expired as $conversation) {
            $this->warn("  🗑️ Suppression totale : Conversation #{$conversation->id}");
            $conversation->deleteCompletely();
        }

        $this->info("✅ {$phase1Expired->count()} conversations Phase 1 supprimées");

        // ===========================================
        // 4. GÉRER CONVERSATIONS PHASE 3 (post_appointment)
        // ===========================================

        $this->info('📋 Gestion conversations Phase 3 (post-RDV)...');

        $phase3Expired = Conversation::expired()
            ->postAppointment()
            ->get();

        $deleted = 0;
        $archived = 0;

        foreach ($phase3Expired as $conversation) {
            $booking = $conversation->bookingRequest;
            $artist = $booking?->bookable;

            // Plan FREE → Suppression totale
            if (!$artist || $artist->isOnFreePlan()) {
                $this->warn("  🗑️ FREE - Suppression : Conversation #{$conversation->id}");
                $conversation->deleteCompletely();
                $deleted++;
            }
            // Plan PRO → Archivage
            else {
                $this->info("  📦 PRO - Archivage : Conversation #{$conversation->id}");
                $conversation->preserveImagesOnly();
                $conversation->archive();
                $archived++;
            }
        }

        $this->info("✅ Phase 3 : {$deleted} supprimées (FREE), {$archived} archivées (PRO)");

        // ===========================================
        // 5. STATISTIQUES FINALES
        // ===========================================

        $this->newLine();
        $this->info('📊 STATISTIQUES :');

        $stats = [
            'deposit_pending' => Conversation::depositPending()->count(),
            'permanent' => Conversation::permanent()->count(),
            'post_appointment' => Conversation::postAppointment()->count(),
            'archived' => Conversation::archived()->count(),
            'expired' => Conversation::expired()->count(),
        ];

        $this->table(
            ['Type', 'Nombre'],
            [
                ['⏱️ Acompte en attente', $stats['deposit_pending']],
                ['✅ Actives', $stats['permanent']],
                ['📋 Post-RDV', $stats['post_appointment']],
                ['📦 Archivées', $stats['archived']],
                ['❌ Expirées', $stats['expired']],
            ]
        );

        $this->newLine();
        $this->info('✅ Nettoyage terminé avec succès');

        return 0;
    }
}

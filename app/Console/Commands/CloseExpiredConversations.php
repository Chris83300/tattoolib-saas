<?php

namespace App\Console\Commands;

use App\Models\Conversation;
use App\Models\BookingRequest;
use App\Enums\ConversationStatus;
use App\Enums\BookingRequestStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CloseExpiredConversations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'conversations:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fermer automatiquement les conversations expirées (deadline acompte ou J+30 post-RDV)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Début de la fermeture des conversations expirées...');

        $closedCount = 0;
        $expiredCount = 0;

        // 1. Fermer les chats dont le deposit_deadline_at est dépassé sans paiement
        $expiredDeposits = $this->closeExpiredDepositConversations();
        $expiredCount = $expiredDeposits->count();

        // 2. Fermer les chats J+30 après le RDV
        $postRdvClosed = $this->closePostAppointmentConversations();
        $closedCount = $postRdvClosed->count();

        // 3. Nettoyer les conversations fermées depuis plus de 90 jours
        $this->cleanupOldConversations();

        $this->info('✅ Terminé !');
        $this->info("📊 {$expiredCount} conversations expirées (acompte non payé)");
        $this->info("📊 {$closedCount} conversations fermées (J+30 post-RDV)");

        return Command::SUCCESS;
    }

    /**
     * Fermer les conversations avec deadline d'acompte expiré
     */
    private function closeExpiredDepositConversations()
    {
        $this->line('🔍 Recherche des conversations avec deadline d\'acompte expiré...');

        $conversations = Conversation::where('status', ConversationStatus::ACTIVE)
            ->where('deposit_deadline_at', '<', now())
            ->whereHas('bookingRequest', function ($query) {
                $query->whereNull('deposit_paid_at')
                      ->where('status', '!=', BookingRequestStatus::CANCELLED);
            })
            ->with(['bookingRequest', 'messages'])
            ->get();

        foreach ($conversations as $conversation) {
            DB::transaction(function () use ($conversation) {
                // Fermer la conversation
                $conversation->update([
                    'status' => ConversationStatus::CLOSED,
                    'archived_at' => now(),
                ]);

                // Mettre à jour la booking request
                $booking = $conversation->bookingRequest;
                $booking->transitionTo(BookingRequestStatus::EXPIRED);
                $booking->update([
                    'expired_at' => now(),
                    'cancellation_reason' => 'Acompte non payé dans les délais',
                ]);

                // Envoyer un message système
                $conversation->messages()->create([
                    'sender_id' => null,
                    'sender_type' => 'system',
                    'content' => "⏰ Délai d'acompte expiré\n\nCette conversation a été fermée automatiquement car l'acompte n'a pas été payé dans les délais impartis.\n\nVous pouvez soumettre une nouvelle demande de réservation à tout moment.",
                ]);

                // Logger l'événement
                $this->logExpiredConversation($conversation, $booking, 'deposit_expired');
            });
        }

        return $conversations;
    }

    /**
     * Fermer les conversations J+30 après le RDV
     */
    private function closePostAppointmentConversations()
    {
        $this->line('🔍 Recherche des conversations J+30 post-RDV...');

        $conversations = Conversation::where('status', ConversationStatus::FULL_ACCESS)
            ->whereHas('bookingRequest.appointment', function ($query) {
                $query->where('end_datetime', '<', now()->subDays(30));
            })
            ->with(['bookingRequest.appointment', 'messages'])
            ->get();

        foreach ($conversations as $conversation) {
            DB::transaction(function () use ($conversation) {
                // Mettre en statut CLOSING d'abord (préavis)
                $conversation->update([
                    'status' => ConversationStatus::CLOSING,
                    'expires_at' => now()->addDays(7), // 7 jours de préavis avant fermeture
                ]);

                // Envoyer un message de préavis
                $conversation->messages()->create([
                    'sender_id' => null,
                    'sender_type' => 'system',
                    'content' => "⏰ Préavis de fermeture\n\nCette conversation sera fermée définitivement dans 7 jours (30 jours après votre rendez-vous).\n\nVous pouvez télécharger les images et documents importants avant la fermeture.",
                ]);

                // Si la conversation est déjà en CLOSING depuis plus de 7 jours, la fermer
                if ($conversation->expires_at && $conversation->expires_at->isPast()) {
                    $conversation->update([
                        'status' => ConversationStatus::CLOSED,
                        'archived_at' => now(),
                    ]);

                    $conversation->messages()->create([
                        'sender_id' => null,
                        'sender_type' => 'system',
                        'content' => "🔒 Conversation fermée\n\nCette conversation a été fermée conformément à notre politique de rétention des données (30 jours post-RDV).\n\nMerci d'avoir utilisé notre service !",
                    ]);

                    $this->logExpiredConversation($conversation, $conversation->bookingRequest, 'post_appointment_closed');
                }
            });
        }

        return $conversations;
    }

    /**
     * Nettoyer les anciennes conversations fermées
     */
    private function cleanupOldConversations(): void
    {
        $this->line('🧹 Nettoyage des conversations fermées depuis plus de 90 jours...');

        $oldConversations = Conversation::where('status', ConversationStatus::CLOSED)
            ->where('archived_at', '<', now()->subDays(90))
            ->with(['messages' => function ($query) {
                $query->with(['media']); // Inclure les médias pour suppression
            }])
            ->get();

        foreach ($oldConversations as $conversation) {
            DB::transaction(function () use ($conversation) {
                // Supprimer les médias associés
                foreach ($conversation->messages as $message) {
                    if ($message->media) {
                        foreach ($message->media as $media) {
                            $media->delete(); // Supprime le fichier physique
                        }
                    }
                }

                // Supprimer les messages
                $conversation->messages()->delete();

                // Supprimer la conversation
                $conversation->delete();

                $this->line("🗑️ Conversation #{$conversation->id} supprimée");
            });
        }

        if ($oldConversations->count() > 0) {
            $this->info("🗑️ {$oldConversations->count()} anciennes conversations supprimées");
        }
    }

    /**
     * Logger la fermeture de conversation
     */
    private function logExpiredConversation(Conversation $conversation, ?BookingRequest $booking, string $reason): void
    {
        Log::info('Conversation closed automatically', [
            'conversation_id' => $conversation->id,
            'booking_request_id' => $booking?->id,
            'reason' => $reason,
            'previous_status' => $conversation->getOriginal('status'),
            'new_status' => $conversation->status->value,
            'archived_at' => $conversation->archived_at,
        ]);
    }

    /**
     * Obtenir des statistiques sur les conversations
     */
    private function getConversationStats(): array
    {
        return [
            'total_conversations' => Conversation::count(),
            'active_conversations' => Conversation::where('status', ConversationStatus::ACTIVE)->count(),
            'full_access_conversations' => Conversation::where('status', ConversationStatus::FULL_ACCESS)->count(),
            'closing_conversations' => Conversation::where('status', ConversationStatus::CLOSING)->count(),
            'closed_conversations' => Conversation::where('status', ConversationStatus::CLOSED)->count(),
            'expiring_soon' => Conversation::where('status', ConversationStatus::ACTIVE)
                ->where('deposit_deadline_at', '>', now())
                ->where('deposit_deadline_at', '<', now()->addHours(24))
                ->count(),
        ];
    }
}

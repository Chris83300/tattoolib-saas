<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Enums\ConversationStatus;
use App\Enums\BookingRequestStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ManageChatStatus extends Command
{
    protected $signature = 'chat:manage-status';
    protected $description = 'Gérer le statut des chats (fermeture automatique après délai acompte) et expiration des demandes';

    public function handle()
    {
        $this->info('Gestion du statut des chats et expiration des demandes...');

        $expiredCount = 0;
        $closedCount = 0;
        $reopenedCount = 0;

        // 1. Traiter les demandes expirées (délai acompte dépassé)
        $expiredRequests = BookingRequest::whereIn('status', [BookingRequestStatus::ACCEPTED, BookingRequestStatus::DEPOSIT_REQUESTED])
            ->where(function($query) {
                $query->whereNotNull('client_payment_deadline')
                      ->orWhereNotNull('deposit_deadline');
            })
            ->where(function($query) {
                $query->where('client_payment_deadline', '<', now())
                      ->orWhere('deposit_deadline', '<', now());
            })
            ->whereNull('deposit_paid_at') // Non payé
            ->get();

        foreach ($expiredRequests as $request) {
            // Marquer la demande comme expirée
            $request->transitionTo(BookingRequestStatus::EXPIRED);
            $request->update([
                'expired_at' => now(),
                'cancellation_reason' => 'Acompte non payé dans les délais',
            ]);

            // Fermer la conversation associée
            if ($request->conversation) {
                // Utiliser une requête directe avec 'archived' qui existe dans la table
                DB::table('conversations')
                    ->where('id', $request->conversation->id)
                    ->update([
                        'status' => 'archived',
                        'is_expired' => true,
                        'archived_at' => now(),
                        'updated_at' => now(),
                    ]);

                // Envoyer un message système dans la conversation
                $request->conversation->messages()->create([
                    'sender_id' => null,
                    'sender_type' => 'system',
                    'content' => "⏰ Délai d'acompte expiré\n\nCette conversation a été fermée automatiquement car l'acompte n'a pas été payé dans les délais impartis.\n\nLa demande de réservation est maintenant marquée comme expirée. Vous pouvez soumettre une nouvelle demande à tout moment.",
                ]);
            }

            $expiredCount++;
            $this->info("Demande #{$request->id} marquée comme expirée et chat fermé (délai acompte dépassé)");
        }

        // 2. Fermer les conversations dont le délai est expiré
        $expiredConversations = Conversation::where('status', 'active')
            ->where('expiry_type', 'deposit_pending')
            ->where(function($query) {
                $query->whereNotNull('deposit_deadline_at')
                      ->orWhereHas('bookingRequest', function($subQuery) {
                          $subQuery->whereNotNull('deposit_deadline');
                      });
            })
            ->where(function($query) {
                $query->where('deposit_deadline_at', '<', now())
                      ->orWhereHas('bookingRequest', function($subQuery) {
                          $subQuery->where('deposit_deadline', '<', now());
                      });
            })
            ->whereHas('bookingRequest', function ($query) {
                $query->whereNull('deposit_paid_at');
            })
            ->get();

        foreach ($expiredConversations as $conversation) {
            // Utiliser une requête directe avec 'archived' qui existe dans la table
            DB::table('conversations')
                ->where('id', $conversation->id)
                ->update([
                    'status' => 'archived',
                    'is_expired' => true,
                    'archived_at' => now(),
                    'updated_at' => now(),
                ]);

            // Marquer la demande comme expirée si ce n'est pas déjà fait
            $booking = $conversation->bookingRequest;
            if ($booking && !in_array($booking->status->value, ['expired', 'cancelled', 'rejected'])) {
                $booking->transitionTo(BookingRequestStatus::EXPIRED);
                $booking->update([
                    'expired_at' => now(),
                    'cancellation_reason' => 'Acompte non payé dans les délais',
                ]);
            }

            // Envoyer un message système
            $conversation->messages()->create([
                'sender_id' => null,
                'sender_type' => 'system',
                'content' => "⏰ Délai d'acompte expiré\n\nCette conversation a été fermée automatiquement car l'acompte n'a pas été payé dans les délais impartis.",
            ]);

            $closedCount++;
            $this->info("Conversation #{$conversation->id} fermée (délai acompte dépassé)");
        }

        // 3. S'assurer que les conversations avec deadline non expirée sont actives
        $activeConversations = Conversation::where('status', '!=', 'active')
            ->where('expiry_type', 'deposit_pending')
            ->where(function($query) {
                $query->whereNotNull('deposit_deadline_at')
                      ->orWhereHas('bookingRequest', function($subQuery) {
                          $subQuery->whereNotNull('deposit_deadline');
                      });
            })
            ->where(function($query) {
                $query->where('deposit_deadline_at', '>', now())
                      ->orWhereHas('bookingRequest', function($subQuery) {
                          $subQuery->where('deposit_deadline', '>', now());
                      });
            })
            ->whereHas('bookingRequest', function ($query) {
                $query->whereNull('deposit_paid_at')
                      ->whereIn('status', ['accepted', 'deposit_requested']);
            })
            ->get();

        foreach ($activeConversations as $conversation) {
            $conversation->update([
                'status' => ConversationStatus::ACTIVE,
                'is_expired' => false,
            ]);

            $reopenedCount++;
            $this->info("Conversation #{$conversation->id} rouverte (délai encore valide)");
        }

        // 4. Prolonger les conversations pour les demandes avec acompte payé jusqu'à J+30 après RDV
        $paidConversations = Conversation::where('status', ConversationStatus::ACTIVE)
            ->where('expiry_type', 'deposit_pending')
            ->whereHas('bookingRequest', function ($query) {
                $query->whereNotNull('deposit_paid_at')
                      ->whereNotNull('appointment_datetime');
            })
            ->get();

        foreach ($paidConversations as $conversation) {
            $booking = $conversation->bookingRequest;
            $expiresAt = $booking->appointment_datetime->addDays(30);

            $conversation->update([
                'expiry_type' => 'post_appointment',
                'expires_at' => $expiresAt,
                'appointment_completed_at' => $booking->appointment_datetime,
            ]);

            $this->info("Conversation #{$conversation->id} prolongée jusqu'au J+30 post-RDV ({$expiresAt->format('d/m/Y')})");
        }

        $this->info('Gestion des chats terminée.');
        $this->info("📊 {$expiredCount} demandes marquées comme expirées");
        $this->info("📊 {$closedCount} conversations fermées (délai dépassé)");
        $this->info("📊 {$reopenedCount} conversations rouvertes (délai valide)");

        return 0;
    }
}

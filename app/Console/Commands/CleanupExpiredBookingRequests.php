<?php

namespace App\Console\Commands;

use App\Models\BookingRequest;
use App\Enums\BookingRequestStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupExpiredBookingRequests extends Command
{
    protected $signature = 'app:cleanup-expired-booking-requests';
    protected $description = 'Supprime les BookingRequests : acompte non payé après deadline, ou annulés/rejetés depuis 2 jours';

    public function handle(): int
    {
        $this->cleanupExpiredDeposits();
        $this->cleanupCancelledAndRejected();

        return Command::SUCCESS;
    }

    /**
     * 1. Acompte non payé dans le délai → annulation + suppression totale
     */
    private function cleanupExpiredDeposits(): void
    {
        $expired = BookingRequest::whereIn('status', [
                BookingRequestStatus::DEPOSIT_REQUESTED,
                BookingRequestStatus::EXPIRED
            ])
            ->whereNotNull('deposit_deadline')
            ->where('deposit_deadline', '<', now())
            ->with(['conversation.messages', 'bookable.user', 'client', 'media'])
            ->get();

        $count = 0;
        foreach ($expired as $br) {
            try {
                DB::transaction(function () use ($br) {
                    // 1. Notifier les deux parties AVANT suppression
                    $this->notifyExpiration($br);

                    // 2. Supprimer tous les médias Spatie liés au BookingRequest
                    $br->clearMediaCollection(); // Supprime tous les media collections

                    // 3. Supprimer les médias des messages de la conversation
                    if ($br->conversation) {
                        foreach ($br->conversation->messages as $message) {
                            $message->clearMediaCollection();
                        }
                        // 4. Supprimer les messages
                        $br->conversation->messages()->delete();
                        // 5. Supprimer la conversation
                        $br->conversation->delete();
                    }

                    // 6. Supprimer l'appointment si créé
                    if ($br->appointment) {
                        $br->appointment->delete();
                    }

                    // 7. Supprimer le BookingRequest
                    $br->forceDelete(); // forceDelete pour bypasser soft delete si applicable

                    Log::info("🗑️ BookingRequest #{$br->id} supprimé (acompte non payé, deadline dépassée)");
                });

                $count++;
                $this->info("🗑️ BR #{$br->id} supprimé — acompte expiré");
            } catch (\Exception $e) {
                $this->error("❌ BR #{$br->id} erreur : {$e->getMessage()}");
                Log::error("Cleanup BR #{$br->id} failed: {$e->getMessage()}");
            }
        }

        $this->info("Acomptes expirés : {$count} BookingRequests supprimés.");
    }

    /**
     * 2. Annulés ou rejetés depuis plus de 2 jours → suppression totale
     */
    private function cleanupCancelledAndRejected(): void
    {
        $twoDaysAgo = now()->subDays(2);

        $toDelete = BookingRequest::whereIn('status', [
                BookingRequestStatus::CANCELLED,
                BookingRequestStatus::REJECTED,
            ])
            ->where('updated_at', '<', $twoDaysAgo)
            ->with(['conversation.messages', 'media'])
            ->get();

        $count = 0;
        foreach ($toDelete as $br) {
            try {
                DB::transaction(function () use ($br) {
                    // 1. Supprimer tous les médias Spatie du BookingRequest
                    $br->clearMediaCollection();

                    // 2. Supprimer les médias des messages de la conversation
                    if ($br->conversation) {
                        foreach ($br->conversation->messages as $message) {
                            $message->clearMediaCollection();
                        }
                        $br->conversation->messages()->delete();
                        $br->conversation->delete();
                    }

                    // 3. Supprimer l'appointment si existant
                    if ($br->appointment) {
                        $br->appointment->delete();
                    }

                    // 4. Supprimer le BookingRequest
                    $br->forceDelete();

                    Log::info("🗑️ BookingRequest #{$br->id} supprimé (statut: {$br->status->value}, annulé/rejeté > 2j)");
                });

                $count++;
                $this->info("🗑️ BR #{$br->id} supprimé — {$br->status->value} > 2 jours");
            } catch (\Exception $e) {
                $this->error("❌ BR #{$br->id} erreur : {$e->getMessage()}");
                Log::error("Cleanup BR #{$br->id} failed: {$e->getMessage()}");
            }
        }

        $this->info("Annulés/Rejetés : {$count} BookingRequests supprimés.");
    }

    /**
     * Notifier client + tattooer que le booking est annulé pour non-paiement
     */
    private function notifyExpiration(BookingRequest $br): void
    {
        // Notifier le client
        if ($br->client) {
            $br->client->notify(new \App\Notifications\DepositExpiredBookingCancelledNotification($br));
        }

        // Notifier le tattooer
        $artistUser = $br->bookable?->user;
        if ($artistUser) {
            $artistUser->notify(new \App\Notifications\DepositExpiredBookingCancelledNotification($br));
        }
    }
}

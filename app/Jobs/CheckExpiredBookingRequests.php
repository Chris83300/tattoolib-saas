<?php

namespace App\Jobs;

use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Notifications\DepositExpiredNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckExpiredBookingRequests implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Traiter les demandes avec délai d'acompte expiré
        $this->handleExpiredDepositDeadlines();

        // 2. Nettoyer les demandes terminées (Plan Free)
        $this->handleCompletedRequests();
    }

    /**
     * Gérer les demandes avec délai d'acompte expiré
     */
    private function handleExpiredDepositDeadlines(): void
    {
        $expiredRequests = BookingRequest::where('status', BookingRequest::STATUS_ACCEPTED)
            ->whereNotNull('deposit_deadline')
            ->where('deposit_deadline', '<', now())
            ->whereNull('deposit_paid_at')
            ->get();

        foreach ($expiredRequests as $request) {
            try {
                // Vérifier le type de compte du tattooer
                $isPro = $request->bookable->user->subscription?->plan?->type === 'pro';

                // Fermer la conversation
                if ($request->conversation) {
                    $request->conversation->update(['status' => 'closed']);

                    // Envoyer message de fermeture
                    Message::create([
                        'conversation_id' => $request->conversation->id,
                        'sender_type' => 'system',
                        'sender_id' => null,
                        'content' => "⏰ Délai d'acompte expiré\n\n" .
                                   "Le délai de paiement de l'acompte est dépassé. " .
                                   "La conversation est maintenant fermée.\n\n" .
                                   "Vous pouvez refaire une demande si vous êtes toujours intéressé(e).",
                        'read_by_client_at' => null,
                        'read_by_tattooer_at' => now(),
                    ]);
                }

                // Mettre à jour le statut de la demande
                $request->update([
                    'status' => BookingRequest::STATUS_CANCELLED,
                    'cancellation_reason' => 'deposit_deadline_expired',
                    'cancelled_at' => now(),
                    'cancelled_by' => 'system',
                ]);

                // Notifications
                $this->notifyDepositExpired($request, $isPro);

                Log::info("Demande #{$request->id} - Délai d'acompte expiré", [
                    'request_id' => $request->id,
                    'client_id' => $request->client_id,
                    'tattooer_id' => $request->bookable_id,
                    'is_pro' => $isPro,
                ]);

            } catch (\Exception $e) {
                Log::error("Erreur lors du traitement de la demande #{$request->id}", [
                    'error' => $e->getMessage(),
                    'request_id' => $request->id,
                ]);
            }
        }

        if ($expiredRequests->count() > 0) {
            Log::info("Processed {$expiredRequests->count()} expired booking requests");
        }
    }

    /**
     * Gérer le nettoyage des demandes terminées (Plan Free)
     */
    private function handleCompletedRequests(): void
    {
        // Récupérer les demandes terminées depuis plus de 2 jours
        $completedRequests = BookingRequest::where('status', 'completed')
            ->where('updated_at', '<', now()->subDays(2))
            ->get();

        foreach ($completedRequests as $request) {
            try {
                $isPro = $request->bookable->user->subscription?->plan?->type === 'pro';

                if ($isPro) {
                    // Plan Pro : Conserver les données, juste fermer la conversation
                    if ($request->conversation) {
                        $request->conversation->update(['status' => 'closed']);

                        Message::create([
                            'conversation_id' => $request->conversation->id,
                            'sender_type' => 'system',
                            'sender_id' => null,
                            'content' => "✅ Conversation archivée\n\n" .
                                       "Le tatouage est terminé. Cette conversation est maintenant archivée. " .
                                       "Les médias sont conservés dans votre fiche client.",
                            'read_by_client_at' => null,
                            'read_by_tattooer_at' => now(),
                        ]);
                    }

                    Log::info("Demande #{$request->id} - Archivée (Plan Pro)", [
                        'request_id' => $request->id,
                        'client_id' => $request->client_id,
                    ]);

                } else {
                    // Plan Free : Suppression complète
                    $this->completelyDeleteRequest($request);

                    Log::info("Demande #{$request->id} - Supprimée (Plan Free)", [
                        'request_id' => $request->id,
                        'client_id' => $request->client_id,
                    ]);
                }

            } catch (\Exception $e) {
                Log::error("Erreur lors du nettoyage de la demande #{$request->id}", [
                    'error' => $e->getMessage(),
                    'request_id' => $request->id,
                ]);
            }
        }

        if ($completedRequests->count() > 0) {
            Log::info("Processed {$completedRequests->count()} completed requests for cleanup");
        }
    }

    /**
     * Suppression complète d'une demande (Plan Free)
     */
    private function completelyDeleteRequest(BookingRequest $request): void
    {
        // Supprimer la conversation et les messages
        if ($request->conversation) {
            $request->conversation->messages()->delete();
            $request->conversation->delete();
        }

        // Conserver les médias dans la fiche client avant suppression
        if ($request->hasMedia('attachments')) {
            $client = $request->client;
            foreach ($request->getMedia('attachments') as $media) {
                if ($client) {
                    $media->copy($client, 'reference_images');
                }
                $media->delete();
            }
        }

        // Supprimer la demande
        $request->delete();
    }

    /**
     * Notifier l'expiration du délai d'acompte
     */
    private function notifyDepositExpired(BookingRequest $request, bool $isPro): void
    {
        // Notification client
        if ($request->client?->user) {
            $request->client->user->notify(new DepositExpiredNotification($request));
        }

        // Notification artiste
        if ($request->bookable?->user) {
            $request->bookable->user->notify(new DepositExpiredNotification($request));
        }

        Log::info("Notifications envoyées pour expiration délai acompte", [
            'request_id' => $request->id,
            'is_pro' => $isPro,
        ]);
    }
}

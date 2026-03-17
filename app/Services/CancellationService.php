<?php
namespace App\Services;

use App\Models\BookingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancellationService
{
    /**
     * Calculer le montant remboursable selon les conditions d'annulation
     */
    public function calculateRefund(BookingRequest $booking, string $cancelledBy): array
    {
        // Pas d'acompte payé → pas de remboursement à traiter
        if (!$booking->deposit_paid_at || !$booking->total_deposit_amount) {
            return [
                'refund_amount'  => 0,
                'refund_percent' => 0,
                'reason'         => 'Aucun acompte versé.',
                'can_refund'     => false,
            ];
        }

        $depositAmount    = (float) $booking->total_deposit_amount;
        $depositPaidAt    = $booking->deposit_paid_at;
        $confirmedDate    = $booking->confirmed_date;
        $hoursUntilAppt   = $confirmedDate
            ? now()->diffInHours($confirmedDate, false)
            : null;
        $daysSinceDeposit = now()->diffInDays($depositPaidAt);

        // ─── Annulation par l'ARTISTE ──────────────────────────────────
        if ($cancelledBy === 'artist') {
            return [
                'refund_amount'  => $depositAmount,
                'refund_percent' => 100,
                'reason'         => 'Annulation par l\'artiste : remboursement intégral de l\'acompte.',
                'can_refund'     => true,
                'refund_type'    => 'full',
            ];
        }

        // ─── Annulation par le CLIENT ──────────────────────────────────
        if ($cancelledBy === 'client') {

            // Cas 1 : Annulation dans les 24h après paiement de l'acompte
            if ($daysSinceDeposit <= 1) {
                return [
                    'refund_amount'  => $depositAmount,
                    'refund_percent' => 100,
                    'reason'         => 'Annulation dans les 24h suivant le paiement : remboursement intégral.',
                    'can_refund'     => true,
                    'refund_type'    => 'full',
                ];
            }

            // Cas 2 : RDV confirmé et annulation > 7 jours avant
            if ($hoursUntilAppt !== null && $hoursUntilAppt >= 168) {
                $refundAmount = round($depositAmount * 0.50, 2);
                return [
                    'refund_amount'  => $refundAmount,
                    'refund_percent' => 50,
                    'reason'         => 'Annulation plus de 7 jours avant le RDV : remboursement de 50% de l\'acompte.',
                    'can_refund'     => true,
                    'refund_type'    => 'partial',
                ];
            }

            // Cas 3 : Annulation entre 48h et 7j avant le RDV
            if ($hoursUntilAppt !== null && $hoursUntilAppt >= 48) {
                $refundAmount = round($depositAmount * 0.25, 2);
                return [
                    'refund_amount'  => $refundAmount,
                    'refund_percent' => 25,
                    'reason'         => 'Annulation entre 48h et 7 jours avant le RDV : remboursement de 25% de l\'acompte.',
                    'can_refund'     => true,
                    'refund_type'    => 'partial',
                ];
            }

            // Cas 4 : Annulation moins de 48h avant le RDV
            if ($hoursUntilAppt !== null && $hoursUntilAppt < 48) {
                return [
                    'refund_amount'  => 0,
                    'refund_percent' => 0,
                    'reason'         => 'Annulation moins de 48h avant le RDV : aucun remboursement selon les CGV.',
                    'can_refund'     => false,
                    'refund_type'    => 'none',
                ];
            }

            // Cas 5 : Pas de RDV confirmé (demande en cours)
            return [
                'refund_amount'  => $depositAmount,
                'refund_percent' => 100,
                'reason'         => 'Annulation avant confirmation du RDV : remboursement intégral.',
                'can_refund'     => true,
                'refund_type'    => 'full',
            ];
        }

        // Cas admin : laisser à la discrétion
        return [
            'refund_amount'  => $depositAmount,
            'refund_percent' => 100,
            'reason'         => 'Décision administrative.',
            'can_refund'     => true,
            'refund_type'    => 'admin',
        ];
    }

    /**
     * Exécuter l'annulation complète avec remboursement conditionnel
     */
    public function processCancellation(
        BookingRequest $booking,
        string $cancelledBy,
        string $cancellationMessage = ''
    ): array {
        $refundInfo = $this->calculateRefund($booking, $cancelledBy);

        DB::transaction(function () use ($booking, $cancelledBy, $cancellationMessage, $refundInfo) {
            $booking->update([
                'status'              => 'cancelled',
                'cancelled_by'        => $cancelledBy,
                'cancellation_reason' => $cancellationMessage,
                'cancelled_at'        => now(),
                'refund_amount'       => $refundInfo['refund_amount'],
                'refund_percent'      => $refundInfo['refund_percent'],
            ]);

            // Archiver la conversation
            if ($booking->conversation) {
                $booking->conversation->update([
                    'status'      => 'archived',
                    'archived_at' => now(),
                ]);
            }

            // Effectuer le remboursement Stripe si applicable
            if ($refundInfo['can_refund'] && $refundInfo['refund_amount'] > 0
                && $booking->stripe_payment_intent_id) {
                try {
                    app(BookingRequestService::class)
                        ->processStripeRefund($booking, $refundInfo['refund_amount']);
                    $booking->update(['refund_processed_at' => now()]);
                } catch (\Exception $e) {
                    Log::error('Remboursement auto échoué: ' . $e->getMessage(), [
                        'booking_id' => $booking->id,
                    ]);
                    // Ne pas bloquer — l'admin pourra le faire manuellement
                }
            }

            // Message système dans la conversation
            $this->addSystemMessage($booking, $refundInfo);

            // Notifier l'artiste
            $artist = $booking->bookable;
            if ($artist?->user) {
                $artist->user->notify(
                    new \App\Notifications\BookingCancelledWithRefundNotification($booking)
                );
            }
        });

        return $refundInfo;
    }

    /**
     * Ajouter un message système dans la conversation après annulation/remboursement
     */
    private function addSystemMessage(BookingRequest $booking, array $refundInfo): void
    {
        if (!$booking->conversation) {
            return;
        }

        $cancelledByLabel = match ($booking->cancelled_by) {
            'client' => 'le client',
            'artist' => "l'artiste",
            default  => 'un administrateur',
        };

        $refundStatus = $booking->refund_processed_at
            ? '✅ Remboursé le ' . $booking->refund_processed_at->format('d/m/Y')
            : ($refundInfo['can_refund'] && $refundInfo['refund_amount'] > 0
                ? '⏳ En cours de traitement (5-10 jours ouvrés)'
                : 'Aucun remboursement applicable');

        $content = sprintf(
            "📋 Demande #%d annulée\n\n" .
            "Annulée par : %s\n" .
            "Motif : %s\n\n" .
            "💰 Remboursement\n" .
            "Acompte versé : %s €\n" .
            "Montant remboursé : %s € (%d%%)\n" .
            "Statut : %s",
            $booking->id,
            $cancelledByLabel,
            $booking->cancellation_reason ?: 'Non précisé',
            number_format((float) $booking->total_deposit_amount, 2, ',', '.'),
            number_format((float) $refundInfo['refund_amount'], 2, ',', '.'),
            $refundInfo['refund_percent'] ?? 0,
            $refundStatus
        );

        \App\Models\Message::create([
            'conversation_id' => $booking->conversation->id,
            'sender_id'       => null,
            'sender_type'     => 'admin',
            'content'         => $content,
        ]);
    }
}

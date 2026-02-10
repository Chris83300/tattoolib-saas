<?php

namespace App\Actions;

use App\Models\BookingRequest;
use App\Models\AccountingTransaction;
use App\Models\Conversation;
use App\Enums\BookingRequestStatus;
use App\Enums\ConversationStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CancelBookingWithRefund
{
    /**
     * Annuler une réservation avec remboursement automatique
     */
    public function execute(BookingRequest $booking, string $cancelledBy, ?string $reason = null): void
    {
        DB::transaction(function () use ($booking, $cancelledBy, $reason) {
            // 1. Calculer le pourcentage de remboursement
            $refundPercent = $this->calculateRefundPercentage($booking, $cancelledBy);
            
            // 2. Calculer le montant du remboursement
            $refundAmount = $this->calculateRefundAmount($booking, $refundPercent);
            
            // 3. Traiter le remboursement Stripe si nécessaire
            $refundTransaction = null;
            if ($refundAmount > 0 && $booking->deposit_paid_at) {
                $refundTransaction = $this->processStripeRefund($booking, $refundAmount, $cancelledBy);
            }
            
            // 4. Mettre à jour la booking request
            $this->updateBookingRequest($booking, $cancelledBy, $reason, $refundAmount, $refundPercent);
            
            // 5. Fermer la conversation
            $this->closeConversation($booking);
            
            // 6. Envoyer les notifications
            $this->sendCancellationNotifications($booking, $cancelledBy, $refundAmount, $refundTransaction);
            
            // 7. Logger l'annulation
            $this->logCancellation($booking, $cancelledBy, $refundAmount, $refundPercent);
        });
    }

    /**
     * Calculer le pourcentage de remboursement
     */
    private function calculateRefundPercentage(BookingRequest $booking, string $cancelledBy): int
    {
        // Si annulation par le tattooer, toujours 100%
        if ($cancelledBy === 'tattooer') {
            return 100;
        }
        
        // Si annulation par le client, appliquer les règles
        return $booking->getRefundPercentage();
    }

    /**
     * Calculer le montant du remboursement
     */
    private function calculateRefundAmount(BookingRequest $booking, int $refundPercent): float
    {
        if (!$booking->deposit_paid_at || $refundPercent === 0) {
            return 0;
        }
        
        $depositAmount = $booking->total_deposit_amount ?? 0;
        return ($depositAmount * $refundPercent) / 100;
    }

    /**
     * Traiter le remboursement Stripe
     */
    private function processStripeRefund(BookingRequest $booking, float $refundAmount, string $cancelledBy): ?AccountingTransaction
    {
        // Récupérer la transaction de dépôt originale
        $depositTransaction = $booking->accountingTransactions()
            ->where('type', 'deposit')
            ->where('status', 'completed')
            ->first();

        if (!$depositTransaction || !$depositTransaction->stripe_payment_intent_id) {
            Log::warning('No deposit transaction found for refund', [
                'booking_request_id' => $booking->id,
                'cancelled_by' => $cancelledBy,
            ]);
            return null;
        }

        try {
            // Créer le remboursement Stripe
            $stripeRefund = \Stripe\Refund::create([
                'payment_intent' => $depositTransaction->stripe_payment_intent_id,
                'amount' => (int) ($refundAmount * 100), // Convertir en centimes
                'reason' => 'requested_by_customer',
                'metadata' => [
                    'booking_request_id' => $booking->id,
                    'cancelled_by' => $cancelledBy,
                    'refund_percent' => $this->calculateRefundPercentage($booking, $cancelledBy),
                    'designs_sent' => $booking->designs_sent_count,
                ],
            ]);

            // Créer la transaction de remboursement
            $refundTransaction = AccountingTransaction::create([
                'booking_request_id' => $booking->id,
                'user_id' => $booking->client->user_id,
                'type' => 'refund',
                'amount' => -$refundAmount, // Négatif pour les remboursements
                'currency' => 'eur',
                'status' => 'completed',
                'payment_method' => 'stripe',
                'stripe_payment_intent_id' => $depositTransaction->stripe_payment_intent_id,
                'stripe_refund_id' => $stripeRefund->id,
                'description' => "Remboursement suite à annulation par {$cancelledBy}",
                'metadata' => [
                    'refund_percent' => $this->calculateRefundPercentage($booking, $cancelledBy),
                    'cancelled_by' => $cancelledBy,
                    'designs_sent' => $booking->designs_sent_count,
                    'original_deposit_amount' => $booking->total_deposit_amount,
                    'refund_reason' => $stripeRefund->reason,
                ],
                'processed_at' => now(),
            ]);

            Log::info('Stripe refund processed successfully', [
                'booking_request_id' => $booking->id,
                'refund_amount' => $refundAmount,
                'stripe_refund_id' => $stripeRefund->id,
                'cancelled_by' => $cancelledBy,
            ]);

            return $refundTransaction;

        } catch (\Exception $e) {
            Log::error('Failed to process Stripe refund', [
                'booking_request_id' => $booking->id,
                'refund_amount' => $refundAmount,
                'error' => $e->getMessage(),
                'cancelled_by' => $cancelledBy,
            ]);

            throw new \RuntimeException('Impossible de traiter le remboursement Stripe : ' . $e->getMessage());
        }
    }

    /**
     * Mettre à jour la booking request
     */
    private function updateBookingRequest(BookingRequest $booking, string $cancelledBy, ?string $reason, float $refundAmount, int $refundPercent): void
    {
        $booking->transitionTo(BookingRequestStatus::CANCELLED);
        
        $booking->update([
            'cancellation_reason' => $reason ?? "Annulé par {$cancelledBy}",
            'cancelled_at' => now(),
            'cancelled_by' => $cancelledBy,
            'refund_amount' => $refundAmount,
            'refund_percent' => $refundPercent,
            'refund_processed_at' => $refundAmount > 0 ? now() : null,
        ]);
    }

    /**
     * Fermer la conversation
     */
    private function closeConversation(BookingRequest $booking): void
    {
        $conversation = $booking->conversation;
        
        if ($conversation) {
            $conversation->update([
                'status' => ConversationStatus::CLOSED,
                'closed_at' => now(),
                'close_reason' => 'booking_cancelled',
            ]);
        }
    }

    /**
     * Envoyer les notifications d'annulation
     */
    private function sendCancellationNotifications(BookingRequest $booking, string $cancelledBy, float $refundAmount, ?AccountingTransaction $refundTransaction): void
    {
        // Notification au client
        try {
            $booking->client->user->notify(
                new \App\Notifications\Client\BookingCancelledNotification($booking, $cancelledBy, $refundAmount)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send cancellation notification to client', [
                'booking_request_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Notification au tattooer
        try {
            $booking->bookable->user->notify(
                new \App\Notifications\Tattooer\BookingCancelledNotification($booking, $cancelledBy, $refundAmount)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send cancellation notification to tattooer', [
                'booking_request_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Notification à l'admin si remboursement important
        if ($refundAmount > 100) {
            try {
                $adminUsers = \App\Models\User::where('role', 'admin')->get();
                foreach ($adminUsers as $admin) {
                    $admin->notify(
                        new \App\Notifications\Admin\HighValueRefundNotification($booking, $refundAmount, $cancelledBy)
                    );
                }
            } catch (\Exception $e) {
                Log::error('Failed to send high value refund notification to admin', [
                    'booking_request_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Logger l'annulation
     */
    private function logCancellation(BookingRequest $booking, string $cancelledBy, float $refundAmount, int $refundPercent): void
    {
        Log::info('Booking cancelled with refund', [
            'booking_request_id' => $booking->id,
            'client_id' => $booking->client_id,
            'bookable_id' => $booking->bookable_id,
            'bookable_type' => $booking->bookable_type,
            'cancelled_by' => $cancelledBy,
            'cancellation_reason' => $booking->cancellation_reason,
            'deposit_amount' => $booking->total_deposit_amount,
            'refund_amount' => $refundAmount,
            'refund_percent' => $refundPercent,
            'designs_sent' => $booking->designs_sent_count,
            'cancelled_at' => $booking->cancelled_at,
        ]);
    }

    /**
     * Vérifier si une réservation peut être annulée
     */
    public function canCancel(BookingRequest $booking, string $cancelledBy): bool
    {
        // Vérifier que la réservation n'est pas déjà annulée
        if ($booking->status->value === 'cancelled') {
            return false;
        }

        // Vérifier que la réservation n'est pas déjà terminée
        if ($booking->status->value === 'completed') {
            return false;
        }

        // Si annulation par le client, vérifier les règles spéciales
        if ($cancelledBy === 'client') {
            return $this->canClientCancel($booking);
        }

        // Le tattooer peut toujours annuler
        return true;
    }

    /**
     * Vérifier si le client peut annuler
     */
    private function canClientCancel(BookingRequest $booking): bool
    {
        // Règle spéciale : 0 design à J-3 avant RDV = 100% (annulation possible)
        // 1+ design à J-3 avant RDV = 0% (pas d'annulation)
        
        if ($booking->appointment_datetime) {
            $threeDaysBeforeAppointment = $booking->appointment_datetime->subDays(3);
            
            if (now()->isBefore($threeDaysBeforeAppointment)) {
                // Avant J-3, le client peut annuler
                return true;
            } else {
                // Après J-3, vérifier si des designs ont été envoyés
                return $booking->designs_sent_count === 0;
            }
        }

        // Si pas de date de RDV, le client peut annuler
        return true;
    }

    /**
     * Obtenir les détails d'annulation
     */
    public function getCancellationDetails(BookingRequest $booking, string $cancelledBy): array
    {
        $refundPercent = $this->calculateRefundPercentage($booking, $cancelledBy);
        $refundAmount = $this->calculateRefundAmount($booking, $refundPercent);
        
        return [
            'can_cancel' => $this->canCancel($booking, $cancelledBy),
            'refund_percent' => $refundPercent,
            'refund_amount' => $refundAmount,
            'deposit_amount' => $booking->total_deposit_amount ?? 0,
            'designs_sent' => $booking->designs_sent_count,
            'cancellation_reason' => $this->getCancellationReason($booking, $cancelledBy),
            'is_before_j3' => $this->isBeforeJ3($booking),
            'will_refund' => $refundAmount > 0,
            'refund_timeline' => $this->getRefundTimeline($refundAmount),
        ];
    }

    /**
     * Obtenir la raison de l'annulation
     */
    private function getCancellationReason(BookingRequest $booking, string $cancelledBy): string
    {
        if ($cancelledBy === 'tattooer') {
            return 'Annulation par le tattooer (remboursement 100%)';
        }

        $designsSent = $booking->designs_sent_count;
        $isBeforeJ3 = $this->isBeforeJ3($booking);

        if ($isBeforeJ3 && $designsSent === 0) {
            return 'Annulation avant J-3 sans design (remboursement 100%)';
        }

        if ($isBeforeJ3 && $designsSent > 0) {
            return 'Annulation avant J-3 avec design(s) (remboursement 0%)';
        }

        return match($designsSent) {
            0 => 'Annulation avec 0 design envoyé (remboursement 100%)',
            1 => 'Annulation avec 1 design envoyé (remboursement 80%)',
            2 => 'Annulation avec 2 designs envoyés (remboursement 50%)',
            default => 'Annulation avec 3+ designs envoyés (remboursement 0%)',
        };
    }

    /**
     * Vérifier si on est avant J-3
     */
    private function isBeforeJ3(BookingRequest $booking): bool
    {
        if (!$booking->appointment_datetime) {
            return false;
        }

        $threeDaysBeforeAppointment = $booking->appointment_datetime->subDays(3);
        return now()->isBefore($threeDaysBeforeAppointment);
    }

    /**
     * Obtenir le délai de remboursement
     */
    private function getRefundTimeline(float $refundAmount): string
    {
        if ($refundAmount === 0) {
            return 'Aucun remboursement';
        }

        return '5-7 jours ouvrés';
    }

    /**
     * Obtenir les statistiques d'annulation
     */
    public function getCancellationStats(?string $period = null): array
    {
        $query = BookingRequest::where('status', BookingRequestStatus::CANCELLED);
        
        if ($period) {
            switch ($period) {
                case 'month':
                    $query->where('cancelled_at', '>=', now()->subMonth());
                    break;
                case 'quarter':
                    $query->where('cancelled_at', '>=', now()->subQuarter());
                    break;
                case 'year':
                    $query->where('cancelled_at', '>=', now()->subYear());
                    break;
            }
        }

        $cancellations = $query->get();

        return [
            'total_cancellations' => $cancellations->count(),
            'cancelled_by_client' => $cancellations->where('cancelled_by', 'client')->count(),
            'cancelled_by_tattooer' => $cancellations->where('cancelled_by', 'tattooer')->count(),
            'total_refunded' => $cancellations->sum('refund_amount'),
            'average_refund' => $cancellations->avg('refund_amount'),
            'cancellation_rate' => $this->getCancellationRate($period),
            'recent_cancellations' => $cancellations->orderBy('cancelled_at', 'desc')->limit(10)->get(),
        ];
    }

    /**
     * Calculer le taux d'annulation
     */
    private function getCancellationRate(?string $period): float
    {
        $totalBookings = BookingRequest::count();
        $cancelledBookings = BookingRequest::where('status', BookingRequestStatus::CANCELLED)->count();
        
        if ($totalBookings === 0) {
            return 0;
        }

        return round(($cancelledBookings / $totalBookings) * 100, 2);
    }
}

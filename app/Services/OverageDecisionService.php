<?php

namespace App\Services;

use App\Models\BookingRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\ConversationStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OverageDecisionService
{
    /**
     * Traiter la décision du tatoueur pour les surplus
     */
    public function handleOverageDecision(BookingRequest $booking, string $decision, ?float $surchargeAmount = null, ?string $reason = null): void
    {
        DB::transaction(function () use ($booking, $decision, $surchargeAmount, $reason) {
            // Valider la décision
            $this->validateDecision($decision, $surchargeAmount);

            // Mettre à jour la booking request
            $booking->update([
                'overage_decision' => $decision,
                'surcharge_amount' => $decision === 'surcharge' ? $surchargeAmount : null,
                'overage_reason' => $reason,
            ]);

            // Envoyer un message système
            $this->sendOverageDecisionMessage($booking, $decision, $surchargeAmount);

            // Logger la décision
            $this->logOverageDecision($booking, $decision, $surchargeAmount, $reason);
        });
    }

    /**
     * Valider la décision de surplus
     */
    private function validateDecision(string $decision, ?float $surchargeAmount): void
    {
        if (!in_array($decision, ['free', 'surcharge', 'pending'])) {
            throw new \InvalidArgumentException("Décision invalide: {$decision}");
        }

        if ($decision === 'surcharge' && (!$surchargeAmount || $surchargeAmount <= 0)) {
            throw new \InvalidArgumentException("Le montant du surplus doit être positif");
        }
    }

    /**
     * Envoyer un message système pour la décision de surplus
     */
    private function sendOverageDecisionMessage(BookingRequest $booking, string $decision, ?float $surchargeAmount): void
    {
        $conversation = $booking->conversation;
        
        if (!$conversation) {
            return;
        }

        $content = match($decision) {
            'free' => $this->getFreeDecisionMessage($booking),
            'surcharge' => $this->getSurchargeDecisionMessage($booking, $surchargeAmount),
            'pending' => $this->getPendingDecisionMessage($booking),
            default => ''
        };

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Message pour décision gratuite
     */
    private function getFreeDecisionMessage(BookingRequest $booking): string
    {
        return "🎉 Bonne nouvelle !\n\nLe tatoueur a décidé de continuer gratuitement.\n\n" .
               "Vous pouvez continuer à demander des designs et modifications sans frais supplémentaires.";
    }

    /**
     * Message pour décision avec surplus
     */
    private function getSurchargeDecisionMessage(BookingRequest $booking, float $surchargeAmount): string
    {
        return "💰 Option payante proposée\n\n" .
               "Pour continuer avec des designs/modifications supplémentaires, un montant de {$surchargeAmount}€ est proposé.\n\n" .
               "Vous pouvez accepter cette option pour continuer.";
    }

    /**
     * Message pour décision en attente
     */
    private function getPendingDecisionMessage(BookingRequest $booking): string
    {
        return "⏳ En attente de décision\n\n" .
               "Le tatoueur étudie les options pour continuer.\n\n" .
               "Vous serez notifié dès qu'une décision sera prise.";
    }

    /**
     * Logger la décision de surplus
     */
    private function logOverageDecision(BookingRequest $booking, string $decision, ?float $surchargeAmount, ?string $reason): void
    {
        Log::info('Overage decision made', [
            'booking_request_id' => $booking->id,
            'decision' => $decision,
            'surcharge_amount' => $surchargeAmount,
            'reason' => $reason,
            'designs_sent' => $booking->designs_sent_count,
            'included_designs' => $booking->included_designs,
        ]);
    }

    /**
     * Vérifier si une décision de surplus est nécessaire
     */
    public function needsOverageDecision(BookingRequest $booking): bool
    {
        return $booking->designs_sent_count >= $booking->included_designs 
               && !$booking->overage_decision;
    }

    /**
     * Obtenir les options de surplus disponibles
     */
    public function getOverageOptions(BookingRequest $booking): array
    {
        $basePrice = $booking->price_estimate_max ?? 500.0; // Prix de base par défaut
        
        return [
            'free' => [
                'label' => 'Continuer gratuitement',
                'description' => 'Le tatoueur continue sans frais supplémentaires',
                'amount' => 0,
            ],
            'surcharge' => [
                'label' => 'Option payante',
                'description' => 'Paiement pour designs/modifications supplémentaires',
                'suggested_amounts' => [
                    $basePrice * 0.3, // 30% du prix
                    $basePrice * 0.5, // 50% du prix
                    $basePrice * 0.7, // 70% du prix
                ],
            ],
            'pending' => [
                'label' => 'Plus tard',
                'description' => 'Prendre une décision plus tard',
                'amount' => 0,
            ],
        ];
    }

    /**
     * Traiter le paiement d'un surplus
     */
    public function processOveragePayment(BookingRequest $booking, string $paymentIntentId): void
    {
        DB::transaction(function () use ($booking, $paymentIntentId) {
            if ($booking->overage_decision !== 'surcharge' || !$booking->surcharge_amount) {
                throw new \InvalidArgumentException('Aucun surplus à payer');
            }

            // Marquer comme payé
            $booking->update([
                'surcharge_paid_at' => now(),
                'stripe_payment_intent_id' => $paymentIntentId,
            ]);

            // Envoyer un message de confirmation
            $conversation = $booking->conversation;
            if ($conversation) {
                $conversation->messages()->create([
                    'sender_id' => null,
                    'sender_type' => 'system',
                    'content' => "✅ Paiement du surplus accepté\n\n" .
                               "Montant : {$booking->surcharge_amount}€\n\n" .
                               "Vous pouvez maintenant continuer avec les designs/modifications supplémentaires.",
                ]);
            }

            // Logger le paiement
            Log::info('Overage payment processed', [
                'booking_request_id' => $booking->id,
                'amount' => $booking->surcharge_amount,
                'payment_intent_id' => $paymentIntentId,
            ]);
        });
    }

    /**
     * Obtenir le statut actuel des surplus
     */
    public function getOverageStatus(BookingRequest $booking): array
    {
        return [
            'needs_decision' => $this->needsOverageDecision($booking),
            'decision' => $booking->overage_decision,
            'surcharge_amount' => $booking->surcharge_amount,
            'surcharge_paid' => $booking->surcharge_paid_at ? true : false,
            'designs_sent' => $booking->designs_sent_count,
            'included_designs' => $booking->included_designs,
            'overage_designs' => max(0, $booking->designs_sent_count - $booking->included_designs),
            'options' => $this->getOverageOptions($booking),
        ];
    }
}

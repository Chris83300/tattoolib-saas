<?php

namespace App\Actions;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\AccountingTransaction;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CompleteAppointment
{
    /**
     * Marquer un rendez-vous comme terminé
     */
    public function execute(Appointment $appointment, ?string $paymentMethod = null, ?float $finalAmount = null): void
    {
        DB::transaction(function () use ($appointment, $paymentMethod, $finalAmount) {
            // 1. Marquer l'appointment comme terminé
            $appointment->update([
                'status' => AppointmentStatus::COMPLETED,
                'actual_end_time' => now(),
            ]);

            // 2. Transitionner la booking request
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->transitionTo(BookingRequestStatus::COMPLETED);

            // 3. Créer la transaction de paiement final si spécifié
            if ($paymentMethod && $finalAmount) {
                $this->createFinalPaymentTransaction($bookingRequest, $paymentMethod, $finalAmount);
            }

            // 4. Envoyer un message système dans la conversation
            $this->sendCompletionMessage($appointment);

            // 5. Logger la completion
            $this->logAppointmentCompletion($appointment);

            // 6. Déclencher les notifications post-tatouage
            $this->schedulePostTattooNotifications($appointment);
        });
    }

    /**
     * Créer la transaction de paiement final
     */
    private function createFinalPaymentTransaction(BookingRequest $bookingRequest, string $paymentMethod, float $finalAmount): void
    {
        AccountingTransaction::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $bookingRequest->client->user_id,
            'type' => 'final_payment',
            'amount' => $finalAmount,
            'currency' => 'eur',
            'status' => 'completed',
            'payment_method' => $paymentMethod,
            'description' => "Paiement du solde pour demande #{$bookingRequest->id}",
            'processed_at' => now(),
        ]);

        Log::info('Final payment transaction created', [
            'booking_request_id' => $bookingRequest->id,
            'amount' => $finalAmount,
            'payment_method' => $paymentMethod,
        ]);
    }

    /**
     * Envoyer un message système de completion
     */
    private function sendCompletionMessage(Appointment $appointment): void
    {
        $conversation = $appointment->bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        $content = "✅ Rendez-vous terminé !\n\n";
        $content .= "Votre séance de tatouage est maintenant terminée.\n\n";
        $content .= "📋 Résumé :\n";
        $content .= "• Date : " . $appointment->start_datetime->translatedFormat('l d F Y') . "\n";
        $content .= "• Durée : " . $appointment->duration_minutes . " minutes\n";
        $content .= "• Tatoueur : " . $appointment->bookingRequest->bookable->name . "\n\n";
        $content .= "🩹 Prochaines étapes :\n";
        $content .= "1. Suivez les instructions de soins que vous recevrez\n";
        $content .= "2. Prenez des photos pour suivre la cicatrisation\n";
        $content .= "3. Contactez le tatoueur si vous avez des questions\n\n";
        $content .= "Félicitations pour votre nouveau tatouage ! 🎉";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Logger la completion du rendez-vous
     */
    private function logAppointmentCompletion(Appointment $appointment): void
    {
        Log::info('Appointment completed', [
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_id' => $appointment->client_id,
            'bookable_id' => $appointment->bookable_id,
            'start_datetime' => $appointment->start_datetime,
            'end_datetime' => $appointment->end_datetime,
            'actual_end_time' => $appointment->actual_end_time,
            'duration_minutes' => $appointment->duration_minutes,
        ]);
    }

    /**
     * Planifier les notifications post-tatouage
     */
    private function schedulePostTattooNotifications(Appointment $appointment): void
    {
        // Les notifications seront envoyées automatiquement par le scheduler
        // On peut ici logger ou faire d'autres actions si nécessaire
        
        Log::info('Post-tattoo notifications scheduled', [
            'appointment_id' => $appointment->id,
            '2h_notification' => $appointment->end_datetime->addHours(2),
            '7d_notification' => $appointment->end_datetime->addDays(7),
            '14d_notification' => $appointment->end_datetime->addDays(14),
        ]);
    }

    /**
     * Vérifier si un rendez-vous peut être complété
     */
    public function canComplete(Appointment $appointment): bool
    {
        return $appointment->status === AppointmentStatus::SCHEDULED 
               && $appointment->end_datetime->isPast();
    }

    /**
     * Obtenir le statut de completion d'un rendez-vous
     */
    public function getCompletionStatus(Appointment $appointment): array
    {
        return [
            'can_complete' => $this->canComplete($appointment),
            'is_completed' => $appointment->status === AppointmentStatus::COMPLETED,
            'is_past' => $appointment->end_datetime->isPast(),
            'time_since_end' => $appointment->end_datetime->diffForHumans(),
            'needs_final_payment' => $appointment->bookingRequest->needsFinalPayment(),
            'final_payment_amount' => $this->calculateFinalAmount($appointment->bookingRequest),
        ];
    }

    /**
     * Calculer le montant du paiement final
     */
    private function calculateFinalAmount(BookingRequest $bookingRequest): float
    {
        $totalPrice = $bookingRequest->total_price ?? 0;
        $depositAmount = $bookingRequest->total_deposit_amount ?? 0;
        
        return max(0, $totalPrice - $depositAmount);
    }
}

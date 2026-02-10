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

class ReportTattooerAbsence
{
    /**
     * Signaler l'absence du tattooer
     */
    public function execute(Appointment $appointment, ?string $reason = null): void
    {
        DB::transaction(function () use ($appointment, $reason) {
            // 1. Marquer l'appointment comme tattooer_absent
            $appointment->update([
                'status' => AppointmentStatus::TATTOOER_ABSENT,
                'tattooer_absence_reported_at' => now(),
                'tattooer_absence_reason' => $reason,
            ]);

            // 2. Transitionner la booking request
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->transitionTo(BookingRequestStatus::TATTOOER_ABSENT);

            // 3. Rembourser 100% automatiquement
            $this->processFullRefund($bookingRequest);

            // 4. Envoyer un message système
            $this->sendTattooerAbsentMessage($appointment, $reason);

            // 5. Logger l'absence
            $this->logTattooerAbsence($appointment);

            // 6. Envoyer les notifications
            $this->sendTattooerAbsentNotifications($appointment);
        });
    }

    /**
     * Traiter le remboursement complet
     */
    private function processFullRefund(BookingRequest $bookingRequest): void
    {
        // Créer une transaction de remboursement
        $refundAmount = $bookingRequest->total_deposit_amount;
        
        AccountingTransaction::create([
            'booking_request_id' => $bookingRequest->id,
            'user_id' => $bookingRequest->client->user_id,
            'type' => 'refund',
            'amount' => -$refundAmount, // Négatif pour les remboursements
            'currency' => 'eur',
            'status' => 'completed',
            'payment_method' => 'stripe', // Remboursement via Stripe
            'description' => "Remboursement complet suite à absence du tatoueur",
            'metadata' => [
                'refund_reason' => 'tattooer_absent',
                'original_amount' => $bookingRequest->total_deposit_amount,
                'refund_type' => 'full_refund',
            ],
            'processed_at' => now(),
        ]);

        // Mettre à jour la booking request
        $bookingRequest->update([
            'refund_amount' => $refundAmount,
            'refund_processed_at' => now(),
        ]);

        Log::info('Full refund processed due to tattooer absence', [
            'booking_request_id' => $bookingRequest->id,
            'refund_amount' => $refundAmount,
            'deposit_amount' => $bookingRequest->total_deposit_amount,
        ]);
    }

    /**
     * Envoyer un message système d'absence du tattooer
     */
    private function sendTattooerAbsentMessage(Appointment $appointment, ?string $reason): void
    {
        $conversation = $appointment->bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        $content = "⚠️ Absence du tatoueur signalée\n\n";
        $content .= "Nous sommes désolés, mais le tatoueur n'était pas disponible pour votre rendez-vous.\n\n";
        $content .= "📋 Détails :\n";
        $content .= "• Date prévue : " . $appointment->start_datetime->translatedFormat('l d F Y') . "\n";
        $content .= "• Heure prévue : " . $appointment->start_datetime->format('H:i') . "\n";
        $content .= "• Tatoueur : " . $appointment->bookingRequest->bookable->name . "\n";
        
        if ($reason) {
            $content .= "• Raison : " . $reason . "\n";
        }
        
        $content .= "\n💰 Remboursement :\n";
        $content .= "• Montant remboursé : " . $appointment->bookingRequest->total_deposit_amount . "€\n";
        $content .= "• Statut : Remboursement complet effectué\n";
        $content .= "• Délai : 5-7 jours ouvrés\n\n";
        $content .= "Nous vous invitons à trouver un autre tatoueur sur la plateforme.\n";
        $content .= "Vos excuses pour ce contretemps.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Logger l'absence du tattooer
     */
    private function logTattooerAbsence(Appointment $appointment): void
    {
        Log::warning('Tattooer absence reported', [
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'bookable_id' => $appointment->bookable_id,
            'bookable_type' => $appointment->bookable_type,
            'client_id' => $appointment->client_id,
            'appointment_datetime' => $appointment->start_datetime,
            'reason' => $appointment->tattooer_absence_reason,
        ]);
    }

    /**
     * Envoyer les notifications d'absence du tattooer
     */
    private function sendTattooerAbsentNotifications(Appointment $appointment): void
    {
        $bookingRequest = $appointment->bookingRequest;
        
        // Notification au client
        try {
            $bookingRequest->client->user->notify(
                new \App\Notifications\Client\TattooerAbsentNotification($appointment)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send tattooer absent notification to client', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Notification à l'admin (pour suivi)
        try {
            // Envoyer à l'admin ou au support
            $adminUsers = \App\Models\User::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                $admin->notify(
                    new \App\Notifications\Admin\TattooerAbsentNotification($appointment)
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to send tattooer absent notification to admin', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Vérifier si un client peut signaler l'absence du tattooer
     */
    public function canReportTattooerAbsence(Appointment $appointment): bool
    {
        return $appointment->status === AppointmentStatus::SCHEDULED 
               && $appointment->start_datetime->addMinutes(15)->isPast();
    }

    /**
     * Obtenir le statut d'absence du tattooer
     */
    public function getTattooerAbsenceStatus(Appointment $appointment): array
    {
        return [
            'can_report' => $this->canReportTattooerAbsence($appointment),
            'is_absent' => $appointment->status === AppointmentStatus::TATTOOER_ABSENT,
            'is_past' => $appointment->start_datetime->isPast(),
            'time_since_start' => $appointment->start_datetime->diffForHumans(),
            'can_report_in' => $this->getTimeUntilCanReport($appointment),
        ];
    }

    /**
     * Obtenir le temps restant avant de pouvoir signaler l'absence
     */
    private function getTimeUntilCanReport(Appointment $appointment): ?string
    {
        $canReportTime = $appointment->start_datetime->addMinutes(15);
        
        if ($canReportTime->isFuture()) {
            return $canReportTime->diffForHumans();
        }
        
        return null;
    }

    /**
     * Obtenir les statistiques d'absence d'un tattooer
     */
    public function getTattooerAbsenceStats($bookable): array
    {
        $totalAppointments = $bookable->appointments()->count();
        $absentAppointments = $bookable->appointments()
            ->where('status', AppointmentStatus::TATTOOER_ABSENT)
            ->count();
        
        return [
            'total_appointments' => $totalAppointments,
            'absent_count' => $absentAppointments,
            'absence_rate' => $totalAppointments > 0 ? round(($absentAppointments / $totalAppointments) * 100, 2) : 0,
            'recent_absences' => $bookable->appointments()
                ->where('status', AppointmentStatus::TATTOOER_ABSENT)
                ->orderBy('tattooer_absence_reported_at', 'desc')
                ->limit(5)
                ->get(),
        ];
    }

    /**
     * Vérifier si un tattooer doit être signalé pour absence répétée
     */
    public function shouldFlagTattooer($bookable): bool
    {
        $stats = $this->getTattooerAbsenceStats($bookable);
        
        // Signaler si plus de 10% d'absence et au moins 5 absences
        return $stats['absent_count'] >= 5 && $stats['absence_rate'] > 10;
    }

    /**
     * Signaler un tattooer pour absence répétée (admin)
     */
    public function flagTattooerForRepeatedAbsence($bookable, string $adminReason): void
    {
        DB::transaction(function () use ($bookable, $adminReason) {
            $bookable->update([
                'flagged_for_absence' => true,
                'flagged_at' => now(),
                'flagged_reason' => $adminReason,
                'flagged_by' => auth()->id(),
            ]);

            // Suspendre temporairement le tattooer
            $bookable->user->update([
                'status' => 'suspended',
                'suspended_at' => now(),
                'suspended_reason' => 'Repeated absences',
            ]);

            Log::warning('Tattooer flagged for repeated absence', [
                'bookable_id' => $bookable->id,
                'bookable_type' => get_class($bookable),
                'admin_reason' => $adminReason,
            ]);
        });
    }
}

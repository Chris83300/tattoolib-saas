<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Actions\CompleteAppointment;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AutoCompleteAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-compléter les rendez-vous après J+1 sans action du tattooer';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Auto-complétion des rendez-vous...');

        $completedCount = 0;
        $bookingRequestsCompleted = 0;

        // 1. Traiter les appointments existants
        $appointmentsToComplete = Appointment::where('status', AppointmentStatus::SCHEDULED)
            ->where('end_datetime', '<', now()->subDay())
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();

        foreach ($appointmentsToComplete as $appointment) {
            try {
                // Utiliser l'action de completion
                $completeAction = new CompleteAppointment();
                $completeAction->execute($appointment);

                $completedCount++;

                $this->line("✅ Rendez-vous #{$appointment->id} auto-complété");

                Log::info('Appointment auto-completed', [
                    'appointment_id' => $appointment->id,
                    'booking_request_id' => $appointment->bookingRequest->id,
                    'client_id' => $appointment->client_id,
                    'end_datetime' => $appointment->end_datetime,
                    'days_since_end' => $appointment->end_datetime->diffInDays(now()),
                ]);

            } catch (\Exception $e) {
                $this->error("❌ Erreur lors de l'auto-complétion du rendez-vous #{$appointment->id}: {$e->getMessage()}");

                Log::error('Auto-completion failed', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        // 2. Traiter les booking_requests sans appointment (système actuel)
        $pastBookingRequests = BookingRequest::where('status', BookingRequestStatus::DATE_CONFIRMED)
            ->whereNotNull('appointment_datetime')
            ->where('appointment_datetime', '<', now()->subHours(24)) // 24h après le RDV
            ->get();

        foreach ($pastBookingRequests as $bookingRequest) {
            try {
                // Marquer comme terminé automatiquement (statut neutre)
                $bookingRequest->update(['completed_at' => now()]);
                $bookingRequest->transitionTo(BookingRequestStatus::COMPLETED);

                $bookingRequestsCompleted++;

                $this->line("✅ Booking request #{$bookingRequest->id} auto-complétée (J+1 sans action)");

                Log::info("Booking request auto-completed", [
                    'booking_request_id' => $bookingRequest->id,
                    'appointment_datetime' => $bookingRequest->appointment_datetime,
                    'completed_at' => now(),
                    'auto_completion_reason' => 'no_action_j_plus_1'
                ]);

            } catch (\Exception $e) {
                $this->error("❌ Erreur lors de l'auto-complétion de la booking request #{$bookingRequest->id}: {$e->getMessage()}");

                Log::error("Booking request auto-completion failed", [
                    'booking_request_id' => $bookingRequest->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        if ($completedCount > 0 || $bookingRequestsCompleted > 0) {
            $this->info("✅ {$completedCount} rendez-vous et {$bookingRequestsCompleted} booking requests auto-complétés avec succès");
        } else {
            $this->info("ℹ️ Aucun rendez-vous ou booking request à auto-compléter");
        }

        return Command::SUCCESS;
    }

    /**
     * Obtenir les statistiques d'auto-complétion
     */
    public function getAutoCompletionStats(): array
    {
        $totalScheduled = Appointment::where('status', AppointmentStatus::SCHEDULED)->count();
        $overdueCount = Appointment::where('status', AppointmentStatus::SCHEDULED)
            ->where('end_datetime', '<', now()->subDay())
            ->count();

        return [
            'total_scheduled' => $totalScheduled,
            'overdue_count' => $overdueCount,
            'auto_completion_rate' => $totalScheduled > 0 ? round(($overdueCount / $totalScheduled) * 100, 2) : 0,
        ];
    }

    /**
     * Vérifier les rendez-vous qui seront auto-complétés prochainement
     */
    public function getUpcomingAutoCompletions(): array
    {
        return Appointment::where('status', AppointmentStatus::SCHEDULED)
            ->where('end_datetime', '>=', now()->subHours(24))
            ->where('end_datetime', '<=', now()->addHours(24))
            ->with(['bookingRequest.client', 'bookingRequest.bookable'])
            ->orderBy('end_datetime', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'client_name' => $appointment->bookingRequest->client->full_name,
                    'tattooer_name' => $appointment->bookingRequest->bookable->name,
                    'end_datetime' => $appointment->end_datetime->format('Y-m-d H:i'),
                    'hours_until_auto_complete' => $appointment->end_datetime->addDay()->diffInHours(now(), false),
                    'will_auto_complete' => $appointment->end_datetime->addDay()->isPast(),
                ];
            })
            ->toArray();
    }

    /**
     * Forcer l'auto-complétion d'un rendez-vous spécifique
     */
    public function forceAutoComplete(int $appointmentId): bool
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            $this->error("Rendez-vous #{$appointmentId} non trouvé");
            return false;
        }

        if ($appointment->status !== AppointmentStatus::SCHEDULED) {
            $this->error("Le rendez-vous #{$appointmentId} n'est pas en statut SCHEDULED");
            return false;
        }

        try {
            $completeAction = new CompleteAppointment();
            $completeAction->execute($appointment);

            $this->info("✅ Rendez-vous #{$appointmentId} forcé à être complété");

            Log::info('Appointment force-completed', [
                'appointment_id' => $appointmentId,
                'forced_by' => auth()->id() ?? 'system',
            ]);

            return true;

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de la force-complétion du rendez-vous #{$appointmentId}: {$e->getMessage()}");

            Log::error('Force-completion failed', [
                'appointment_id' => $appointmentId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Annuler l'auto-complétion d'un rendez-vous
     */
    public function cancelAutoCompletion(int $appointmentId): bool
    {
        $appointment = Appointment::find($appointmentId);

        if (!$appointment) {
            $this->error("Rendez-vous #{$appointmentId} non trouvé");
            return false;
        }

        if ($appointment->status !== AppointmentStatus::COMPLETED) {
            $this->error("Le rendez-vous #{$appointmentId} n'est pas en statut COMPLETED");
            return false;
        }

        try {
            // Remettre le rendez-vous en statut SCHEDULED
            $appointment->update([
                'status' => AppointmentStatus::SCHEDULED,
                'actual_end_time' => null,
            ]);

            // Remettre la booking request en statut DATE_CONFIRMED
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->update(['status' => 'date_confirmed']);

            $this->info("✅ Auto-complétion annulée pour le rendez-vous #{$appointmentId}");

            Log::info('Auto-completion cancelled', [
                'appointment_id' => $appointmentId,
                'cancelled_by' => auth()->id() ?? 'system',
            ]);

            return true;

        } catch (\Exception $e) {
            $this->error("❌ Erreur lors de l'annulation de l'auto-complétion du rendez-vous #{$appointmentId}: {$e->getMessage()}");

            Log::error('Auto-completion cancellation failed', [
                'appointment_id' => $appointmentId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}

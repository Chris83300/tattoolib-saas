<?php

namespace App\Actions;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use App\Notifications\AppointmentCompletedNotification;
use Illuminate\Support\Facades\DB;

class CompleteAppointmentAction
{
    public function execute(Appointment $appointment, string $completedBy = 'tattooer', ?string $notes = null): Appointment
    {
        return DB::transaction(function () use ($appointment, $completedBy, $notes) {
            // 1. Mettre à jour l'appointment
            $appointment->update([
                'status' => AppointmentStatus::COMPLETED,
                'completed_at' => now(),
                'completed_by' => $completedBy,
                'completion_notes' => $notes,
            ]);

            // 2. Mettre à jour le BookingRequest
            $bookingRequest = $appointment->bookingRequest;
            if ($bookingRequest) {
                $bookingRequest->update([
                    'status' => BookingRequestStatus::COMPLETED,
                ]);
            }

            // 3. Message système dans le chat
            $conversation = $bookingRequest?->conversation;
            if ($conversation) {
                $conversation->messages()->create([
                    'sender_id' => null, // message système
                    'content' => $completedBy === 'system'
                        ? "✅ Ce rendez-vous a été automatiquement marqué comme terminé."
                        : "✅ Le rendez-vous a été marqué comme terminé par l'artiste. Comment ça s'est passé ? Laissez un avis !",
                    'is_system' => true,
                    'metadata' => json_encode([
                        'type' => 'appointment_completed',
                        'appointment_id' => $appointment->id,
                        'show_review_cta' => true,
                    ]),
                ]);
            }

            // 4. Notifier le client
            $client = $bookingRequest?->client;
            if ($client) {
                $client->notify(new AppointmentCompletedNotification($appointment, $bookingRequest));
            }

            return $appointment->fresh();
        });
    }
}

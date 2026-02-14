<?php

namespace App\Actions;

use App\Models\Appointment;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use App\Notifications\NoShowReportedNotification;
use Illuminate\Support\Facades\DB;

class ReportNoShowAction
{
    public function execute(Appointment $appointment, string $reportedBy, ?string $reason = null): Appointment
    {
        return DB::transaction(function () use ($appointment, $reportedBy, $reason) {
            $status = $reportedBy === 'tattooer'
                ? AppointmentStatus::NO_SHOW_CLIENT
                : AppointmentStatus::NO_SHOW_ARTIST;

            // 1. Mettre à jour l'appointment
            $appointment->update([
                'status' => $status,
                'no_show_reported_by' => $reportedBy,
                'no_show_reported_at' => now(),
                'no_show_reason' => $reason,
            ]);

            // 2. Mettre à jour le BookingRequest
            $bookingRequest = $appointment->bookingRequest;
            if ($bookingRequest) {
                $bookingRequest->update([
                    'status' => BookingRequestStatus::NO_SHOW,
                ]);
            }

            // 3. Message système dans le chat
            $conversation = $bookingRequest?->conversation;
            if ($conversation) {
                $reporterLabel = $reportedBy === 'tattooer' ? "l'artiste" : "le client";
                $conversation->messages()->create([
                    'sender_id' => null,
                    'content' => "⚠️ Un no-show a été signalé par {$reporterLabel}. Notre équipe va examiner la situation.",
                    'is_system' => true,
                    'metadata' => json_encode([
                        'type' => 'no_show_reported',
                        'appointment_id' => $appointment->id,
                        'reported_by' => $reportedBy,
                    ]),
                ]);
            }

            // 4. Notifier l'autre partie + admin
            $this->sendNotifications($appointment, $bookingRequest, $reportedBy);

            return $appointment->fresh();
        });
    }

    private function sendNotifications($appointment, $bookingRequest, $reportedBy): void
    {
        if (!$bookingRequest) return;

        // Notifier l'autre partie
        $target = $reportedBy === 'tattooer'
            ? $bookingRequest->client
            : $bookingRequest->bookable?->user;

        if ($target) {
            $target->notify(new NoShowReportedNotification($appointment, $reportedBy));
        }

        // TODO: Notifier admin via Filament notification
    }
}

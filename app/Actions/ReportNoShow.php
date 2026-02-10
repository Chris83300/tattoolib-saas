<?php

namespace App\Actions;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Models\Client;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\AppointmentStatus;
use App\Enums\BookingRequestStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ReportNoShow
{
    /**
     * Signaler un no-show client
     */
    public function execute(Appointment $appointment, ?string $reason = null): void
    {
        DB::transaction(function () use ($appointment, $reason) {
            // 1. Marquer l'appointment comme no-show
            $appointment->update([
                'status' => AppointmentStatus::NO_SHOW,
                'no_show_reported_at' => now(),
                'no_show_reason' => $reason,
            ]);

            // 2. Transitionner la booking request
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->transitionTo(BookingRequestStatus::NO_SHOW);

            // 3. Incrémenter le compteur de no-show du client
            $client = $bookingRequest->client;
            $client->increment('no_show_count');

            // 4. Vérifier si le client doit être banni (3 no-shows)
            $this->checkAndBanClient($client);

            // 5. Envoyer un message système
            $this->sendNoShowMessage($appointment, $reason);

            // 6. Logger le no-show
            $this->logNoShow($appointment, $client);

            // 7. Envoyer la notification au tattooer
            $this->sendNoShowNotification($appointment);
        });
    }

    /**
     * Vérifier et bannir le client si nécessaire
     */
    private function checkAndBanClient(Client $client): void
    {
        if ($client->no_show_count >= 3) {
            // Bannir le client
            $client->user->update([
                'status' => 'banned',
                'banned_at' => now(),
                'banned_reason' => '3 no-shows detected',
            ]);

            // Envoyer une notification de bannissement
            $this->sendBanNotification($client);

            // Logger le bannissement
            Log::warning('Client banned due to 3 no-shows', [
                'client_id' => $client->id,
                'user_id' => $client->user_id,
                'no_show_count' => $client->no_show_count,
            ]);
        }
    }

    /**
     * Envoyer un message système de no-show
     */
    private function sendNoShowMessage(Appointment $appointment, ?string $reason): void
    {
        $conversation = $appointment->bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        $content = "⚠️ Absence signalée\n\n";
        $content .= "Le client n'a pas honoré son rendez-vous.\n\n";
        $content .= "📋 Détails :\n";
        $content .= "• Date prévue : " . $appointment->start_datetime->translatedFormat('l d F Y') . "\n";
        $content .= "• Heure prévue : " . $appointment->start_datetime->format('H:i') . "\n";
        $content .= "• Tatoueur : " . $appointment->bookingRequest->bookable->name . "\n";
        
        if ($reason) {
            $content .= "• Raison : " . $reason . "\n";
        }
        
        $content .= "\n💰 Situation :\n";
        $content .= "• Acompte payé : " . $appointment->bookingRequest->total_deposit_amount . "€\n";
        $content .= "• Statut : Absence confirmée\n\n";
        $content .= "Le tatoueur vous contactera pour discuter des options.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Envoyer une notification de bannissement au client
     */
    private function sendBanNotification(Client $client): void
    {
        try {
            $client->user->notify(
                new \App\Notifications\Client\AccountBannedNotification($client)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send ban notification', [
                'client_id' => $client->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Logger le no-show
     */
    private function logNoShow(Appointment $appointment, Client $client): void
    {
        Log::info('No-show reported', [
            'appointment_id' => $appointment->id,
            'booking_request_id' => $appointment->bookingRequest->id,
            'client_id' => $client->id,
            'user_id' => $client->user_id,
            'no_show_count' => $client->no_show_count,
            'is_banned' => $client->user->status === 'banned',
            'appointment_datetime' => $appointment->start_datetime,
        ]);
    }

    /**
     * Envoyer la notification de no-show au tattooer
     */
    private function sendNoShowNotification(Appointment $appointment): void
    {
        try {
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->bookable->user->notify(
                new \App\Notifications\Tattooer\NoShowNotification($appointment)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send no-show notification to tattooer', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Vérifier si un client peut signaler un no-show
     */
    public function canReportNoShow(Appointment $appointment): bool
    {
        return $appointment->status === AppointmentStatus::SCHEDULED 
               && $appointment->end_datetime->addHour()->isPast();
    }

    /**
     * Obtenir le statut de no-show d'un rendez-vous
     */
    public function getNoShowStatus(Appointment $appointment): array
    {
        $client = $appointment->bookingRequest->client;
        
        return [
            'can_report' => $this->canReportNoShow($appointment),
            'is_no_show' => $appointment->status === AppointmentStatus::NO_SHOW,
            'is_past' => $appointment->end_datetime->isPast(),
            'time_since_end' => $appointment->end_datetime->diffForHumans(),
            'can_report_in' => $this->getTimeUntilCanReport($appointment),
            'client_no_show_count' => $client->no_show_count,
            'client_is_banned' => $client->user->status === 'banned',
            'remaining_no_shows' => max(0, 3 - $client->no_show_count),
        ];
    }

    /**
     * Obtenir le temps restant avant de pouvoir signaler un no-show
     */
    private function getTimeUntilCanReport(Appointment $appointment): ?string
    {
        $canReportTime = $appointment->end_datetime->addHour();
        
        if ($canReportTime->isFuture()) {
            return $canReportTime->diffForHumans();
        }
        
        return null;
    }

    /**
     * Débannir un client (admin seulement)
     */
    public function unbanClient(Client $client, string $reason): void
    {
        DB::transaction(function () use ($client, $reason) {
            $client->user->update([
                'status' => 'active',
                'banned_at' => null,
                'banned_reason' => null,
                'unbanned_at' => now(),
                'unbanned_reason' => $reason,
            ]);

            // Réinitialiser le compteur de no-show (optionnel)
            $client->update(['no_show_count' => 0]);

            // Envoyer une notification de débannissement
            $this->sendUnbanNotification($client);

            Log::info('Client unbanned', [
                'client_id' => $client->id,
                'user_id' => $client->user_id,
                'reason' => $reason,
            ]);
        });
    }

    /**
     * Envoyer une notification de débannissement au client
     */
    private function sendUnbanNotification(Client $client): void
    {
        try {
            $client->user->notify(
                new \App\Notifications\Client\AccountUnbannedNotification($client)
            );
        } catch (\Exception $e) {
            Log::error('Failed to send unban notification', [
                'client_id' => $client->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Obtenir les statistiques de no-show d'un client
     */
    public function getClientNoShowStats(Client $client): array
    {
        $totalAppointments = $client->appointointments()->count();
        $noShowAppointments = $client->appointointments()
            ->where('status', AppointmentStatus::NO_SHOW)
            ->count();
        
        return [
            'total_appointments' => $totalAppointments,
            'no_show_count' => $client->no_show_count,
            'no_show_appointments' => $noShowAppointments,
            'no_show_rate' => $totalAppointments > 0 ? round(($noShowAppointments / $totalAppointments) * 100, 2) : 0,
            'is_banned' => $client->user->status === 'banned',
            'remaining_no_shows' => max(0, 3 - $client->no_show_count),
            'banned_at' => $client->user->banned_at,
            'banned_reason' => $client->user->banned_reason,
        ];
    }
}

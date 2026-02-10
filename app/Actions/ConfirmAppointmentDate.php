<?php

namespace App\Actions;

use App\Models\BookingRequest;
use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\Message;
use App\Enums\BookingRequestStatus;
use App\Enums\AppointmentStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConfirmAppointmentDate
{
    /**
     * Confirmer une date de rendez-vous et créer l'appointment
     */
    public function execute(BookingRequest $bookingRequest, string $date, string $period, ?string $time = null, ?int $durationMinutes = null): void
    {
        DB::transaction(function () use ($bookingRequest, $date, $period, $time, $durationMinutes) {
            // 1. Valider les données
            $this->validateDateSelection($date, $period, $time, $durationMinutes);

            // 2. Sauvegarder la date confirmée
            $appointmentDateTime = $this->buildAppointmentDateTime($date, $period, $time);
            
            $bookingRequest->update([
                'confirmed_date' => $date,
                'confirmed_period' => $period,
                'appointment_datetime' => $appointmentDateTime,
                'appointment_duration_minutes' => $durationMinutes ?? 120,
            ]);

            // 3. Créer l'Appointment
            $appointment = $this->createAppointment($bookingRequest, $appointmentDateTime);

            // 4. Transition statut
            $bookingRequest->transitionTo(BookingRequestStatus::DATE_CONFIRMED);

            // 5. Message système dans le chat
            $this->sendConfirmationMessage($bookingRequest, $date, $period, $appointmentDateTime);

            // 6. Logger l'événement
            $this->logAppointmentConfirmation($bookingRequest, $appointment);

            // 7. TODO: Envoyer notification au tattooer
            // $this->notifyTattooer($bookingRequest, $appointment);
        });
    }

    /**
     * Valider les données de sélection de date
     */
    private function validateDateSelection(string $date, string $period, ?string $time, ?int $durationMinutes): void
    {
        // Valider le format de la date
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            throw new \InvalidArgumentException('Format de date invalide. Attendu: YYYY-MM-DD');
        }

        // Valider que la date est dans le futur
        $carbonDate = Carbon::parse($date);
        if ($carbonDate->isPast()) {
            throw new \InvalidArgumentException('La date doit être dans le futur');
        }

        // Valider la période
        if (!in_array($period, ['morning', 'afternoon', 'evening', 'anytime'])) {
            throw new \InvalidArgumentException('Période invalide. Valeurs acceptées: morning, afternoon, evening, anytime');
        }

        // Valider l'heure si fournie
        if ($time && !preg_match('/^\d{2}:\d{2}$/', $time)) {
            throw new \InvalidArgumentException('Format d\'heure invalide. Attendu: HH:MM');
        }

        // Valider la durée si fournie
        if ($durationMinutes && ($durationMinutes < 30 || $durationMinutes > 480)) {
            throw new \InvalidArgumentException('Durée invalide. Doit être entre 30 et 480 minutes');
        }
    }

    /**
     * Construire la datetime complète du rendez-vous
     */
    private function buildAppointmentDateTime(string $date, string $period, ?string $time): string
    {
        $carbonDate = Carbon::parse($date);

        // Si une heure spécifique est fournie, l'utiliser
        if ($time) {
            return $carbonDate->setTimeFromTimeString($time)->format('Y-m-d H:i:s');
        }

        // Sinon, utiliser une heure par défaut selon la période
        $defaultTime = match($period) {
            'morning' => '10:00',
            'afternoon' => '14:00',
            'evening' => '18:00',
            'anytime' => '10:00', // Par défaut le matin
            default => '10:00',
        };

        return $carbonDate->setTimeFromTimeString($defaultTime)->format('Y-m-d H:i:s');
    }

    /**
     * Créer l'appointment
     */
    private function createAppointment(BookingRequest $bookingRequest, string $appointmentDateTime): Appointment
    {
        $startAt = Carbon::parse($appointmentDateTime);
        $endAt = $startAt->copy()->addMinutes($bookingRequest->appointment_duration_minutes ?? 120);

        return $bookingRequest->appointment()->create([
            'client_id' => $bookingRequest->client_id,
            'bookable_type' => $bookingRequest->bookable_type,
            'bookable_id' => $bookingRequest->bookable_id,
            'start_datetime' => $startAt,
            'end_datetime' => $endAt,
            'duration_minutes' => $bookingRequest->appointment_duration_minutes ?? 120,
            'total_price' => $bookingRequest->estimated_total_price,
            'deposit_amount' => $bookingRequest->total_deposit_amount,
            'status' => AppointmentStatus::SCHEDULED,
        ]);
    }

    /**
     * Envoyer un message système de confirmation
     */
    private function sendConfirmationMessage(BookingRequest $bookingRequest, string $date, string $period, string $appointmentDateTime): void
    {
        $conversation = $bookingRequest->conversation;
        
        if (!$conversation) {
            return;
        }

        $periodLabel = match($period) {
            'morning' => 'Matin',
            'afternoon' => 'Après-midi',
            'evening' => 'Soirée',
            'anytime' => 'Toute la journée',
            default => ucfirst($period),
        };

        $formattedDate = Carbon::parse($appointmentDateTime)->translatedFormat('l d F Y \à H:i');

        $content = "📅 Rendez-vous confirmé !\n\n";
        $content .= "Date : " . Carbon::parse($date)->translatedFormat('l d F Y') . "\n";
        $content .= "Période : {$periodLabel}\n";
        $content .= "Heure : " . Carbon::parse($appointmentDateTime)->format('H:i') . "\n\n";
        $content .= "Le tattooer va maintenant ajouter ce rendez-vous à son calendrier.";

        $conversation->messages()->create([
            'sender_id' => null,
            'sender_type' => 'system',
            'content' => $content,
        ]);
    }

    /**
     * Logger la confirmation du rendez-vous
     */
    private function logAppointmentConfirmation(BookingRequest $bookingRequest, Appointment $appointment): void
    {
        Log::info('Appointment confirmed', [
            'booking_request_id' => $bookingRequest->id,
            'appointment_id' => $appointment->id,
            'client_id' => $bookingRequest->client_id,
            'bookable_id' => $bookingRequest->bookable_id,
            'bookable_type' => $bookingRequest->bookable_type,
            'start_datetime' => $appointment->start_datetime,
            'end_datetime' => $appointment->end_datetime,
            'duration_minutes' => $appointment->duration_minutes,
            'status' => $appointment->status->value,
        ]);
    }

    /**
     * Vérifier si la date proposée est valide
     */
    public function isProposedDateValid(BookingRequest $bookingRequest, string $date, string $period): bool
    {
        if (!$bookingRequest->proposed_dates) {
            return false;
        }

        $proposedDates = $bookingRequest->proposed_dates;
        
        foreach ($proposedDates as $proposedDate) {
            if ($proposedDate['date'] === $date && $proposedDate['period'] === $period) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtenir les dates proposées formatées pour l'affichage
     */
    public function getFormattedProposedDates(BookingRequest $bookingRequest): array
    {
        if (!$bookingRequest->proposed_dates) {
            return [];
        }

        $formattedDates = [];
        
        foreach ($bookingRequest->proposed_dates as $proposedDate) {
            $carbonDate = Carbon::parse($proposedDate['date']);
            
            $formattedDates[] = [
                'date' => $proposedDate['date'],
                'period' => $proposedDate['period'],
                'period_label' => match($proposedDate['period']) {
                    'morning' => 'Matin',
                    'afternoon' => 'Après-midi',
                    'evening' => 'Soirée',
                    'anytime' => 'Toute la journée',
                    default => ucfirst($proposedDate['period']),
                },
                'formatted_date' => $carbonDate->translatedFormat('l d F Y'),
                'is_past' => $carbonDate->isPast(),
                'is_today' => $carbonDate->isToday(),
                'is_tomorrow' => $carbonDate->isTomorrow(),
            ];
        }

        return $formattedDates;
    }

    /**
     * Proposer de nouvelles dates (remplacer les dates existantes)
     */
    public function proposeNewDates(BookingRequest $bookingRequest, array $newDates): void
    {
        DB::transaction(function () use ($bookingRequest, $newDates) {
            // Valider les nouvelles dates
            $this->validateProposedDates($newDates);

            // Mettre à jour les dates proposées
            $bookingRequest->update([
                'proposed_dates' => $newDates,
            ]);

            // Envoyer un message système
            $conversation = $bookingRequest->conversation;
            if ($conversation) {
                $content = "📅 Nouvelles dates proposées\n\n";
                
                foreach ($newDates as $date) {
                    $periodLabel = match($date['period']) {
                        'morning' => 'Matin',
                        'afternoon' => 'Après-midi',
                        'evening' => 'Soirée',
                        'anytime' => 'Toute la journée',
                        default => ucfirst($date['period']),
                    };
                    
                    $formattedDate = Carbon::parse($date['date'])->translatedFormat('l d F Y');
                    $content .= "• {$formattedDate} - {$periodLabel}\n";
                }
                
                $content .= "\nMerci de choisir une date qui vous convient.";

                $conversation->messages()->create([
                    'sender_id' => null,
                    'sender_type' => 'system',
                    'content' => $content,
                ]);
            }

            Log::info('New dates proposed', [
                'booking_request_id' => $bookingRequest->id,
                'new_dates_count' => count($newDates),
            ]);
        });
    }

    /**
     * Valider les dates proposées
     */
    private function validateProposedDates(array $dates): void
    {
        if (empty($dates) || count($dates) > 3) {
            throw new \InvalidArgumentException('Vous devez proposer entre 1 et 3 dates');
        }

        foreach ($dates as $date) {
            if (!isset($date['date']) || !isset($date['period'])) {
                throw new \InvalidArgumentException('Chaque date doit avoir une date et une période');
            }

            // Valider le format de la date
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date['date'])) {
                throw new \InvalidArgumentException('Format de date invalide. Attendu: YYYY-MM-DD');
            }

            // Valider que la date est dans le futur
            $carbonDate = Carbon::parse($date['date']);
            if ($carbonDate->isPast()) {
                throw new \InvalidArgumentException('Les dates proposées doivent être dans le futur');
            }

            // Valider la période
            if (!in_array($date['period'], ['morning', 'afternoon', 'evening', 'anytime'])) {
                throw new \InvalidArgumentException('Période invalide. Valeurs acceptées: morning, afternoon, evening, anytime');
            }
        }
    }
}

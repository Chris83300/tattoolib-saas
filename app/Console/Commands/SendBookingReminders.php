<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Notifications\Client\AppointmentReminderNotification;
use App\Notifications\Tattooer\NoDesignSentNotification;
use App\Notifications\Client\DesignReceivedNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-booking-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les rappels de rendez-vous (J-7, J-3, J-2, Jour J)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Envoi des rappels de rendez-vous...');
        
        $remindersSent = 0;
        
        // Rappels J-7
        $remindersSent += $this->sendSevenDayReminders();
        
        // Rappels J-3
        $remindersSent += $this->sendThreeDayReminders();
        
        // Rappels J-2
        $remindersSent += $this->sendTwoDayReminders();
        
        // Rappels Jour J
        $remindersSent += $this->sendSameDayReminders();
        
        // Alertes tattooer si pas de design
        $remindersSent += $this->sendNoDesignAlerts();
        
        $this->info("✅ {$remindersSent} rappels envoyés avec succès");
        
        return Command::SUCCESS;
    }

    /**
     * Envoyer les rappels J-7
     */
    private function sendSevenDayReminders(): int
    {
        $count = 0;
        
        // Rappels clients J-7
        $appointmentsIn7Days = Appointment::where('status', 'scheduled')
            ->whereDate('start_datetime', '=', now()->addDays(7)->format('Y-m-d'))
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();
            
        foreach ($appointmentsIn7Days as $appointment) {
            try {
                $appointment->client->user->notify(
                    new AppointmentReminderNotification($appointment, '7_days')
                );
                $count++;
                
                Log::info('Rappel J-7 envoyé', [
                    'appointment_id' => $appointment->id,
                    'client_id' => $appointment->client_id,
                    'client_email' => $appointment->client->user->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du rappel J-7', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        // Alertes tattooer si pas de design envoyé
        $noDesignAlerts = $this->checkAndSendNoDesignAlerts(7);
        $count += $noDesignAlerts;
        
        return $count;
    }

    /**
     * Envoyer les rappels J-3
     */
    private function sendThreeDayReminders(): int
    {
        $count = 0;
        
        // Rappels clients J-3
        $appointmentsIn3Days = Appointment::where('status', 'scheduled')
            ->whereDate('start_datetime', '=', now()->addDays(3)->format('Y-m-d'))
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();
            
        foreach ($appointmentsIn3Days as $appointment) {
            try {
                $appointment->client->user->notify(
                    new AppointmentReminderNotification($appointment, '2_days')
                );
                $count++;
                
                Log::info('Rappel J-3 envoyé', [
                    'appointment_id' => $appointment->id,
                    'client_id' => $appointment->client_id,
                    'client_email' => $appointment->client->user->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du rappel J-3', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        // Alertes tattooer si pas de design envoyé
        $noDesignAlerts = $this->checkAndSendNoDesignAlerts(3);
        $count += $noDesignAlerts;
        
        return $count;
    }

    /**
     * Envoyer les rappels J-2
     */
    private function sendTwoDayReminders(): int
    {
        $count = 0;
        
        // Rappels clients J-2
        $appointmentsIn2Days = Appointment::where('status', 'scheduled')
            ->whereDate('start_datetime', '=', now()->addDays(2)->format('Y-m-d'))
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();
            
        foreach ($appointmentsIn2Days as $appointment) {
            try {
                $appointment->client->user->notify(
                    new AppointmentReminderNotification($appointment, '1_day')
                );
                $count++;
                
                Log::info('Rappel J-2 envoyé', [
                    'appointment_id' => $appointment->id,
                    'client_id' => $appointment->client_id,
                    'client_email' => $appointment->client->user->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du rappel J-2', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $count;
    }

    /**
     * Envoyer les rappels Jour J
     */
    private function sendSameDayReminders(): int
    {
        $count = 0;
        
        // Rappels clients Jour J
        $appointmentsToday = Appointment::where('status', 'scheduled')
            ->whereDate('start_datetime', '=', now()->format('Y-m-d'))
            ->whereTime('start_datetime', '>=', now()->format('H:i'))
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();
            
        foreach ($appointmentsToday as $appointment) {
            try {
                $appointment->client->user->notify(
                    new AppointmentReminderNotification($appointment, 'same_day')
                );
                $count++;
                
                Log::info('Rappel Jour J envoyé', [
                    'appointment_id' => $appointment->id,
                    'client_id' => $appointment->client_id,
                    'client_email' => $appointment->client->user->email,
                    'appointment_time' => $appointment->start_datetime->format('H:i'),
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du rappel Jour J', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $count;
    }

    /**
     * Vérifier et envoyer les alertes de design manquant
     */
    private function checkAndSendNoDesignAlerts(int $daysBefore): int
    {
        $count = 0;
        
        $appointmentsSoon = Appointment::where('status', 'scheduled')
            ->whereDate('start_datetime', '=', now()->addDays($daysBefore)->format('Y-m-d'))
            ->with(['bookingRequest.bookable'])
            ->get();
        
        foreach ($appointmentsSoon as $appointment) {
            $bookingRequest = $appointment->bookingRequest;
            
            // Vérifier si aucun design n'a été envoyé
            if ($bookingRequest->designs_sent_count === 0) {
                try {
                    $bookingRequest->bookable->user->notify(
                        new NoDesignSentNotification($appointment, $daysBefore)
                    );
                    $count++;
                    
                    Log::info('Alerte design manquant envoyée', [
                        'appointment_id' => $appointment->id,
                        'booking_request_id' => $bookingRequest->id,
                        'tattooer_id' => $bookingRequest->bookable_id,
                        'days_before' => $daysBefore,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Erreur lors de l\'envoi de l\'alerte design', [
                        'appointment_id' => $appointment->id,
                        'booking_request_id' => $bookingRequest->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }
        
        return $count;
    }
}

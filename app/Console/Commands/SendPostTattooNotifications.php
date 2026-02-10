<?php

namespace App\Console\Commands;

use App\Models\Appointment;
use App\Models\BookingRequest;
use App\Notifications\Client\PostTattooCareNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendPostTattooNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send-post-tattoo-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoyer les notifications post-tattoo (2h, J+7, J+14)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Envoi des notifications post-tattoo...');
        
        $notificationsSent = 0;
        
        // Notifications 2h après la fin du RDV
        $notificationsSent += $this->sendTwoHoursAfterNotifications();
        
        // Notifications J+7
        $notificationsSent += $this->sendSevenDaysAfterNotifications();
        
        // Notifications J+14
        $notificationsSent += $this->sendFourteenDaysAfterNotifications();
        
        $this->info("✅ {$notificationsSent} notifications post-tattoo envoyées avec succès");
        
        return Command::SUCCESS;
    }

    /**
     * Envoyer les notifications 2h après la fin du RDV
     */
    private function sendTwoHoursAfterNotifications(): int
    {
        $count = 0;
        
        // Rechercher les RDV terminés il y a 2 heures
        $appointments2HoursAgo = Appointment::where('status', 'completed')
            ->where('end_datetime', '=', now()->subHours(2)->format('Y-m-d H:i:s'))
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();
        
        foreach ($appointments2HoursAgo as $appointment) {
            try {
                $appointment->client->user->notify(
                    new PostTattooCareNotification($appointment, '2_hours')
                );
                $count++;
                
                Log::info('Notification 2h envoyée', [
                    'appointment_id' => $appointment->id,
                    'client_id' => $appointment->client_id,
                    'client_email' => $appointment->client->user->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification 2h', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $count;
    }

    /**
     * Envoyer les notifications J+7
     */
    private function sendSevenDaysAfterNotifications(): int
    {
        $count = 0;
        
        // Rechercher les RDV terminés il y a 7 jours
        $appointments7DaysAgo = Appointment::where('status', 'completed')
            ->where('end_datetime', '=', now()->subDays(7)->format('Y-m-d'))
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();
        
        foreach ($appointments7DaysAgo as $appointment) {
            try {
                $appointment->client->user->notify(
                    new PostTattooCareNotification($appointment, '7_days')
                );
                $count++;
                
                Log::info('Notification J+7 envoyée', [
                    'appointment_id' => $appointment->id,
                    'client_id' => $appointment->client_id,
                    'client_email' => $appointment->client->user->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification J+7', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $count;
    }

    /**
     * Envoyer les notifications J+14
     */
    private function sendFourteenDaysAfterNotifications(): int
    {
        $count = 0;
        
        // Rechercher les RDV terminés il y a 14 jours
        $appointments14DaysAgo = Appointment::where('status', 'completed')
            ->where('end_datetime', '=', now()->subDays(14)->format('Y-m-d'))
            ->with(['bookingRequest.client.user', 'bookingRequest.bookable'])
            ->get();
        
        foreach ($appointments14DaysAgo as $appointment) {
            try {
                $appointment->client->user->notify(
                    new PostTattooCareNotification($appointment, '14_days')
                );
                $count++;
                
                Log::info('Notification J+14 envoyée', [
                    'appointment_id' => $appointment->id,
                    'client_id' => $appointment->client_id,
                    'client_email' => $appointment->client->user->email,
                ]);
            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi de la notification J+14', [
                    'appointment_id' => $appointment->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        return $count;
    }

    /**
     * Envoyer la notification de design reçu immédiatement
     */
    public function sendDesignReceivedNotification(BookingRequest $bookingRequest, int $designNumber): void
    {
        try {
            $bookingRequest->client->user->notify(
                new \App\Notifications\Client\DesignReceivedNotification($bookingRequest, $designNumber)
            );
            
            Log::info('Notification design reçu envoyée', [
                'booking_request_id' => $bookingRequest->id,
                'design_number' => $designNumber,
                'client_id' => $bookingRequest->client_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification design reçu', [
                'booking_request_id' => $bookingRequest->id,
                'design_number' => $designNumber,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Envoyer la notification de no-show immédiatement
     */
    public function sendNoShowNotification(Appointment $appointment): void
    {
        try {
            $bookingRequest = $appointment->bookingRequest;
            $bookingRequest->bookable->user->notify(
                new \App\Notifications\Tattooer\NoShowNotification($appointment)
            );
            
            Log::info('Notification no-show envoyée', [
                'appointment_id' => $appointment->id,
                'booking_request_id' => $bookingRequest->id,
                'tattooer_id' => $bookingRequest->bookable_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification no-show', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

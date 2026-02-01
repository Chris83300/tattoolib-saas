<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // ⭐ Génère les availabilities automatiquement chaque nuit à 2h
        $schedule->command('availability:generate --days=' . config('tattoolib.availability.rolling_window_days'))
            ->dailyAt('02:00')
            ->withoutOverlapping()
            ->runInBackground();

        // ⭐ Vérifie les demandes de réservation expirées toutes les heures
        $schedule->command('booking-requests:check-expired')
            ->hourly()
            ->withoutOverlapping()
            ->runInBackground();

        // 🆕 Envoyer les rappels de consentement (J-4) à 10h
        $schedule->command('consents:send-reminders')
            ->dailyAt('10:00')
            ->withoutOverlapping()
            ->runInBackground();

        // 🆕 Envoyer les rappels de rendez-vous (J-1 et Jour J) à 9h
        $schedule->command('appointments:send-reminders')
            ->dailyAt('09:00')
            ->withoutOverlapping()
            ->runInBackground();

        // Exemple d'autres tâches utiles :

        // Vérifier stock faible
        $schedule->command('inventory:check-low-stock')
            ->dailyAt('08:00');

        // Marquer factures en retard
        $schedule->command('invoices:mark-overdue')
            ->dailyAt('00:30');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

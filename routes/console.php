<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Auto-complétion des RDV passés (toutes les heures)
Schedule::command('app:check-completed-appointments')->hourly();

// Nettoyage des bookings expirés/annulés/rejetés (toutes les 6h)
Schedule::command('app:cleanup-expired-booking-requests')->everySixHours();

// Rappels quotidiens (RDV, design, acompte) — tous les jours à 9h
Schedule::command('app:send-booking-reminders')->dailyAt('09:00');

// Notifications post-tattoo (soins, suivi, avis) — toutes les heures
Schedule::command('app:send-post-tattoo-notifications')->hourly();

// Rappels trial studio — J-4 avant expiration (tous les jours à 9h)
Schedule::command('studios:send-trial-reminders')->dailyAt('09:00');

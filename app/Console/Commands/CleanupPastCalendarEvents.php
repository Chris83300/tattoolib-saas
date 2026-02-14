<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupPastCalendarEvents extends Command
{
    protected $signature = 'app:cleanup-past-calendar-events';
    protected $description = 'Supprime les événements calendrier de la semaine passée (breaks, vacations, et appointments terminés/annulés)';

    public function handle(): int
    {
        $endOfLastWeek = Carbon::now()->startOfWeek()->subSecond(); // dimanche 23:59:59
        $startOfLastWeek = Carbon::now()->startOfWeek()->subWeek(); // lundi précédent 00:00

        $this->info("🗑️ Nettoyage des événements du {$startOfLastWeek->format('d/m/Y')} au {$endOfLastWeek->format('d/m/Y 23:59')}");

        // 1. Supprimer breaks, vacations, closures passés (pas de dépendance)
        $nonAppointments = CalendarEvent::whereIn('type', ['break', 'vacation', 'closure'])
            ->where('end_datetime', '<=', $endOfLastWeek)
            ->delete();

        $this->info("🗑️ {$nonAppointments} événements non-RDV supprimés");

        // 2. Supprimer les events appointment dont l'appointment est terminé/annulé
        $appointments = CalendarEvent::where('type', 'appointment')
            ->where('end_datetime', '<=', $endOfLastWeek)
            ->whereHas('appointment', function ($q) {
                $q->whereIn('status', ['completed', 'cancelled', 'no_show_client', 'no_show_artist']);
            })
            ->delete();

        $this->info("🗑️ {$appointments} événements RDV terminés/annulés supprimés");

        // 3. Nettoyer les events orphelins (appointment_id=NULL pour type=appointment)
        $orphanEvents = CalendarEvent::where('type', 'appointment')
            ->whereNull('appointment_id')
            ->where('end_datetime', '<=', now())
            ->delete();

        $this->info("🗑️ {$orphanEvents} événements orphelins supprimés");

        // 4. Nettoyer les events avec bookable inexistant
        $invalidBookable = CalendarEvent::where('end_datetime', '<=', now())
            ->whereDoesntHave('bookable')
            ->delete();

        $this->info("🗑️ {$invalidBookable} événements avec artiste inexistant supprimés");

        // 5. Log les events appointment orphelins (pas de status final)
        $orphans = CalendarEvent::where('type', 'appointment')
            ->where('end_datetime', '<=', $endOfLastWeek)
            ->whereHas('appointment', function ($q) {
                $q->where('status', 'scheduled'); // encore scheduled mais passé = à auto-compléter
            })
            ->count();

        if ($orphans > 0) {
            $this->warn("⚠️ {$orphans} RDV passés encore en 'scheduled' — lancez app:check-completed-appointments d'abord");
        }

        $totalDeleted = $nonAppointments + $appointments + $orphanEvents + $invalidBookable;
        $this->info("✅ Total : {$totalDeleted} événements supprimés");

        return self::SUCCESS;
    }
}

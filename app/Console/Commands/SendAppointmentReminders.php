<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Notifications\AppointmentReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendAppointmentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie les rappels de rendez-vous (J-1 et Jour J)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Envoi des rappels de rendez-vous...');

        $totalSent = 0;
        $totalErrors = 0;

        // Rappel J-1 (envoyé à 9h)
        $tomorrowProjects = $this->getTomorrowProjects();
        $this->processReminders($tomorrowProjects, 'tomorrow', $totalSent, $totalErrors);

        // Rappel Jour J (envoyé à 9h)
        $todayProjects = $this->getTodayProjects();
        $this->processReminders($todayProjects, 'today', $totalSent, $totalErrors);

        $this->info("Rappels de rendez-vous terminés: {$totalSent} envoyés, {$totalErrors} erreurs");
        
        return $totalErrors === 0 ? 0 : 1;
    }

    /**
     * Récupérer les projets de demain
     */
    private function getTomorrowProjects()
    {
        return Project::whereIn('status', [Project::STATUS_IN_PROGRESS])
            ->whereDate('appointment_date', now()->addDay()->toDateString())
            ->with(['client.user', 'bookable.user'])
            ->get();
    }

    /**
     * Récupérer les projets d'aujourd'hui
     */
    private function getTodayProjects()
    {
        return Project::whereIn('status', [Project::STATUS_IN_PROGRESS])
            ->whereDate('appointment_date', now()->toDateString())
            ->where('appointment_date', '>=', now()->startOfDay())
            ->where('appointment_date', '<=', now()->endOfDay())
            ->with(['client.user', 'bookable.user'])
            ->get();
    }

    /**
     * Traiter les rappels pour une liste de projets
     */
    private function processReminders($projects, $type, &$totalSent, &$totalErrors)
    {
        $typeLabel = $type === 'tomorrow' ? 'J-1' : 'Jour J';
        $this->line("Traitement des rappels {$typeLabel}...");

        foreach ($projects as $project) {
            try {
                // Envoyer au client
                $project->client->user->notify(new AppointmentReminderNotification($project, $type));
                
                // Envoyer au tatoueur (uniquement pour J-1)
                if ($type === 'tomorrow') {
                    // $project->bookable->user->notify(new AppointmentReminderForTattooerNotification($project, $type));
                }
                
                $this->line("✅ Rappel {$typeLabel} envoyé - Projet #{$project->id} - {$project->client->full_name}");
                $totalSent++;
                
            } catch (\Exception $e) {
                $this->error("❌ Erreur rappel {$typeLabel} pour projet #{$project->id}: " . $e->getMessage());
                Log::error("Appointment reminder {$type} error for project {$project->id}: " . $e->getMessage());
                $totalErrors++;
            }
        }

        if ($projects->isEmpty()) {
            $this->line("Aucun projet pour les rappels {$typeLabel}.");
        }
    }
}

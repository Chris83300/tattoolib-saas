<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Notifications\ConsentReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendConsentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'consents:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie les rappels de consentement 4 jours avant le RDV';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des projets nécessitant un rappel de consentement...');

        // Trouver les projets J-4 sans consentement
        $projects = Project::whereIn('status', [Project::STATUS_IN_PROGRESS])
            ->whereDate('appointment_date', now()->addDays(4)->toDateString())
            ->whereDoesntHave('consent')
            ->with(['client.user', 'bookable.user'])
            ->get();

        if ($projects->isEmpty()) {
            $this->info('Aucun projet nécessitant un rappel de consentement.');
            return 0;
        }

        $sentCount = 0;
        $errorCount = 0;

        foreach ($projects as $project) {
            try {
                // Envoyer la notification au client
                $project->client->user->notify(new ConsentReminderNotification($project));
                
                // Envoyer une notification au tatoueur pour information
                // $project->bookable->user->notify(new ConsentReminderForTattooerNotification($project));
                
                $this->line("✅ Rappel envoyé pour le projet #{$project->id} - Client: {$project->client->full_name}");
                $sentCount++;
                
            } catch (\Exception $e) {
                $this->error("❌ Erreur pour le projet #{$project->id}: " . $e->getMessage());
                Log::error("Consent reminder error for project {$project->id}: " . $e->getMessage());
                $errorCount++;
            }
        }

        $this->info("Rappels de consentement terminés: {$sentCount} envoyés, {$errorCount} erreurs");
        
        return $errorCount === 0 ? 0 : 1;
    }
}

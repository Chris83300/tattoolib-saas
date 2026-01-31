<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\TattooHistory;
use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ArchiveCompletedProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:archive-completed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive les projets terminés (J+1 après RDV)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des projets à archiver...');

        // Trouver les projets à archiver (J+1 après RDV, pas encore validés)
        $projects = Project::where('status', Project::STATUS_IN_PROGRESS)
            ->whereDate('appointment_date', '<', now()->subDay()->toDateString())
            ->whereNull('archived_at')
            ->with(['client', 'bookable'])
            ->get();

        if ($projects->isEmpty()) {
            $this->info('Aucun projet à archiver.');
            return 0;
        }

        $archivedCount = 0;
        $errorCount = 0;

        DB::beginTransaction();
        
        try {
            foreach ($projects as $project) {
                try {
                    $this->archiveProject($project);
                    $this->line("✅ Projet #{$project->id} archivé - Client: {$project->client->full_name}");
                    $archivedCount++;
                    
                } catch (\Exception $e) {
                    $this->error("❌ Erreur archivage projet #{$project->id}: " . $e->getMessage());
                    Log::error("Project archive error for project {$project->id}: " . $e->getMessage());
                    $errorCount++;
                }
            }

            DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Erreur lors de la transaction: " . $e->getMessage());
            Log::error("Archive transaction error: " . $e->getMessage());
            return 1;
        }

        $this->info("Archivage terminé: {$archivedCount} projets archivés, {$errorCount} erreurs");
        
        return $errorCount === 0 ? 0 : 1;
    }

    /**
     * Archiver un projet individuel
     */
    private function archiveProject(Project $project)
    {
        // Marquer le projet comme complété et archivé
        $project->update([
            'status' => Project::STATUS_COMPLETED,
            'completed_at' => $project->appointment_date,
            'archived_at' => now(),
        ]);

        // Créer l'historique du tattoo
        $this->createTattooHistory($project);

        // Gérer l'archivage des messages selon le plan de l'artiste
        $this->archiveMessages($project);
    }

    /**
     * Créer l'historique du tattoo
     */
    private function createTattooHistory(Project $project)
    {
        TattooHistory::create([
            'client_id' => $project->client_id,
            'bookable_id' => $project->bookable_id,
            'bookable_type' => $project->bookable_type,
            'project_id' => $project->id,
            'tattoo_date' => $project->appointment_date,
            'body_location' => $project->tattoo_location,
            'description' => $project->tattoo_description,
            'duration' => $project->estimated_duration ?? 0,
            'total_paid' => $project->final_price ?? $project->estimated_price ?? 0,
            'payment_method' => $project->payment_method ?? 'cash',
        ]);
    }

    /**
     * Archiver les messages selon le plan de l'artiste
     */
    private function archiveMessages(Project $project)
    {
        // TODO: Implémenter la logique selon le plan (FREE/PRO)
        // Pour l'instant, on archive tous les messages (soft delete)
        
        $messages = $project->messages()->get();
        
        foreach ($messages as $message) {
            // Copier les images importantes vers le projet avant suppression
            $this->archiveMessageAttachments($message, $project);
            
            // Soft delete du message
            $message->delete();
        }
    }

    /**
     * Archiver les pièces jointes importantes d'un message
     */
    private function archiveMessageAttachments($message, Project $project)
    {
        $attachments = $message->getMedia('attachments');
        
        foreach ($attachments as $media) {
            // Archiver uniquement les images importantes
            if (str_starts_with($media->mime_type, 'image/')) {
                try {
                    // Copier le média vers la collection chat_archive du projet
                    $project->addMediaFromUrl($media->getUrl())
                        ->usingFileName($media->file_name)
                        ->toMediaCollection('chat_archive');
                } catch (\Exception $e) {
                    Log::warning("Failed to archive attachment {$media->id}: " . $e->getMessage());
                }
            }
        }
    }
}

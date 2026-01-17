<?php

namespace App\Console\Commands;

use App\Models\Availability;
use App\Models\Tattooer;
use Illuminate\Console\Command;

class GenerateAvailabilities extends Command
{
    protected $signature = 'availability:generate
                            {--tattooer= : ID du tatoueur (tous si non spécifié)}
                            {--days=365 : Nombre de jours à générer}'; // ⭐ 1 an par défaut

    protected $description = 'Génère les availabilities depuis les WorkingHours';

    public function handle()
    {
        $tattooerId = $this->option('tattooer');
        $days = (int) $this->option('days');

        // ⭐ Utiliser la configuration si --days non spécifié
        if ($days === 365) {
            $days = config('tattoolib.availability.initial_generation_days', 365);
        }

        // ⭐ Générer 1 an (ou plus) d'availabilities
        $startDate = now()->startOfDay();
        $endDate = now()->addDays($days)->endOfDay();

        if ($tattooerId) {
            $tattooers = Tattooer::where('id', $tattooerId)->get();
        } else {
            $tattooers = Tattooer::all();
        }

        $totalGenerated = 0;

        foreach ($tattooers as $tattooer) {
            $this->info("Génération pour {$tattooer->user->name}...");

            $generated = Availability::generateFromWorkingHours(
                $tattooer->id,
                $startDate,
                $endDate
            );

            $totalGenerated += $generated;
            $this->line("  → {$generated} availabilities créées");
        }

        $this->info("Total : {$totalGenerated} availabilities générées");

        // ⭐ NOUVEAU : Nettoyer le passé (garder historique 30 jours)
        $this->cleanOldAvailabilities();

        return Command::SUCCESS;
    }

    /**
     * ⭐ NOUVEAU : Nettoie les availabilities obsolètes
     */
    protected function cleanOldAvailabilities(): void
    {
        $keepDays = config('tattoolib.availability.keep_past_days', 30);
        $cutoffDate = now()->subDays($keepDays)->startOfDay();

        // Supprimer availabilities passées (sauf celles liées à un RDV)
        $deleted = Availability::where('date', '<', $cutoffDate)
            ->whereNull('appointment_id')
            ->delete();

        $this->info("Nettoyage : {$deleted} availabilities obsolètes supprimées (conservées : {$keepDays} jours)");
    }
}

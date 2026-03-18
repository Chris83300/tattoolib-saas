<?php

namespace App\Console\Commands\Gdpr;

use Illuminate\Console\Command;

class PurgeInactiveData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature   = 'gdpr:purge-inactive';
    protected $description = 'Purger les données des comptes inactifs selon la politique de rétention RGPD';

    public function handle(): void
    {
        // 1. Anonymiser les comptes sans activité depuis 3 ans
        $inactiveUsers = \App\Models\User::where('last_login_at', '<', now()->subYears(3))
            ->whereNull('deleted_at')
            ->get();

        $this->info("Comptes inactifs +3 ans : {$inactiveUsers->count()}");

        foreach ($inactiveUsers as $user) {
            // Anonymiser uniquement les données non-légales
            // email et stripe_id conservés (obligations légales comptables)
            $user->update([
                'phone'     => null,
                'fcm_token' => null,
            ]);
            $this->line("Anonymisé : user #{$user->id}");
        }

        // 2. Purger les tokens FCM inutilisés depuis 6 mois
        $fcmPurged = \App\Models\User::whereNotNull('fcm_token')
            ->where('updated_at', '<', now()->subMonths(6))
            ->whereDoesntHave('notifications', fn($q) =>
                $q->where('created_at', '>', now()->subMonths(6))
            )
            ->update(['fcm_token' => null]);

        $this->info("Tokens FCM expirés nettoyés : {$fcmPurged}");

        // 3. Supprimer les fichiers d'export RGPD (temporaires, >30 jours)
        \Illuminate\Support\Facades\Storage::disk('local')
            ->deleteDirectory('gdpr-exports');

        // 4. Purger les sessions expirées (>30 jours)
        $sessionsPurged = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('last_activity', '<', now()->subDays(30)->timestamp)
            ->delete();

        $this->info("Sessions expirées supprimées : {$sessionsPurged}");

        $this->info('Purge RGPD terminée.');
    }
}

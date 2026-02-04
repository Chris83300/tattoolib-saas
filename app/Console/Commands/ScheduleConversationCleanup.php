<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckExpiredConversations;

class ScheduleConversationCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:schedule-conversation-cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie et nettoie les conversations expirées';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Lancement du nettoyage des conversations...');

        // Dispatcher le job de vérification
        CheckExpiredConversations::dispatch();

        $this->info('✅ Job de nettoyage dispatché avec succès');

        return 0;
    }
}

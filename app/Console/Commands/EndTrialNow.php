<?php

namespace App\Console\Commands;

use App\Models\Studio;
use App\Services\StudioBillingService;
use Illuminate\Console\Command;

class EndTrialNow extends Command
{
    protected $signature = 'inkpik:end-trial {--studio-id= : ID du studio}';
    protected $description = 'Terminer le trial immédiatement pour un studio (trialing → active)';

    public function handle(): int
    {
        $studioId = $this->option('studio-id');
        $studio   = $studioId ? Studio::find($studioId) : Studio::first();

        if (!$studio) {
            $this->error('Studio non trouvé.');
            return 1;
        }

        $billingService = app(StudioBillingService::class);

        $this->info("Studio #{$studio->id} — {$studio->name}");

        $sub = $studio->user?->subscription('default');
        if (!$sub) {
            $this->error('Aucun abonnement Cashier trouvé.');
            return 1;
        }

        $this->line('Statut actuel : ' . $sub->stripe_status);
        $this->line('Trial ends at : ' . ($sub->trial_ends_at ?? 'NULL'));

        if ($sub->stripe_status === 'active') {
            $this->info('Abonnement déjà actif — rien à faire.');
            return 0;
        }

        if (!$sub->onTrial()) {
            $this->warn('Pas en période de trial — impossible de terminer.');
            return 1;
        }

        if (!$this->confirm('Terminer le trial maintenant et passer en active ?', true)) {
            return 0;
        }

        if ($billingService->endTrialImmediately($studio)) {
            $sub->refresh();
            $this->info('Trial terminé — abonnement actif !');
            $this->line('Nouveau statut : ' . $sub->stripe_status);
        } else {
            $this->error('Échec. Vérifiez les logs Laravel.');
            return 1;
        }

        return 0;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Piercer;
use App\Models\Tattooer;
use App\Services\TrialService;
use Illuminate\Console\Command;

class BlockExpiredTrials extends Command
{
    protected $signature   = 'inkpik:block-expired-trials';
    protected $description = 'Bloquer les artistes dont le trial 14j est expiré sans abonnement';

    public function handle(TrialService $trialService): void
    {
        $blocked = 0;

        // Tattooers indépendants avec trial expiré
        Tattooer::where('is_subscribed', false)
            ->whereNull('studio_id')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->where('is_blocked', false)
            ->chunk(100, function ($tattooers) use ($trialService, &$blocked) {
                foreach ($tattooers as $tattooer) {
                    $trialService->blockExpiredTrial($tattooer);
                    $blocked++;
                }
            });

        // Piercers indépendants avec trial expiré
        Piercer::where('is_subscribed', false)
            ->whereNull('studio_id')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->where('is_blocked', false)
            ->chunk(100, function ($piercers) use ($trialService, &$blocked) {
                foreach ($piercers as $piercer) {
                    $trialService->blockExpiredTrial($piercer);
                    $blocked++;
                }
            });

        $this->info("{$blocked} artiste(s) bloqué(s) pour trial expiré.");
    }
}

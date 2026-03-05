<?php

namespace App\Console\Commands;

use App\Models\Studio;
use App\Services\StudioBillingService;
use Illuminate\Console\Command;

class SyncStripeSubscriptions extends Command
{
    protected $signature = 'inkpik:sync-stripe';
    protected $description = 'Synchroniser les abonnements studios depuis Stripe (utile en local sans webhook)';

    public function handle(): void
    {
        $billingService = app(StudioBillingService::class);
        $synced = 0;

        Studio::whereHas('user', fn ($q) => $q->whereNotNull('stripe_id'))
            ->each(function (Studio $studio) use ($billingService, &$synced) {
                if ($billingService->syncFromStripe($studio)) {
                    $this->info("Studio #{$studio->id} ({$studio->name}) : synchronisé");
                    $synced++;
                }
            });

        $this->info("{$synced} abonnement(s) synchronisé(s).");
    }
}

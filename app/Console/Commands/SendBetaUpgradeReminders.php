<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\BetaUpgradeReminderNotification;
use App\Services\BetaService;
use Illuminate\Console\Command;

class SendBetaUpgradeReminders extends Command
{
    protected $signature = 'inkpik:beta-reminders';
    protected $description = 'Envoyer les relances aux bêta-testeurs dont le mois gratuit expire bientôt + bloquer les expirés';

    public function handle(BetaService $betaService): int
    {
        $this->info('=== Relances bêta-testeurs ===');

        // 1. Envoyer les relances J+20 (8-10 jours restants)
        $reminded = 0;
        User::where('is_beta_tester', true)
            ->whereNotNull('beta_expires_at')
            ->where('beta_expires_at', '>', now())
            ->chunk(50, function ($users) use ($betaService, &$reminded) {
                foreach ($users as $user) {
                    if ($betaService->shouldSendUpgradeReminder($user)) {
                        $alreadySent = $user->notifications()
                            ->where('type', BetaUpgradeReminderNotification::class)
                            ->where('created_at', '>', now()->subDays(5))
                            ->exists();

                        if (!$alreadySent) {
                            $user->notify(new BetaUpgradeReminderNotification(
                                $user->betaDaysRemaining()
                            ));
                            $reminded++;
                            $this->line("  Relance envoyée à {$user->email} ({$user->betaDaysRemaining()}j restants)");
                        }
                    }
                }
            });

        $this->info("Relances envoyées : {$reminded}");

        // 2. Bloquer les bêta-testeurs expirés
        $blocked = $betaService->blockExpiredBetaTesters();
        $this->info("Bêta-testeurs bloqués (mois expiré) : {$blocked}");

        return self::SUCCESS;
    }
}

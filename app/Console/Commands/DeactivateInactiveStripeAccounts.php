<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tattooer;
use App\Models\StudioArtist;

class DeactivateInactiveStripeAccounts extends Command
{
    protected $signature = 'stripe:deactivate-inactive';
    protected $description = 'Désactive les comptes Stripe Connect inactifs (60j sans transaction)';

    public function handle()
    {
        $this->info('🔍 Recherche comptes Stripe Connect inactifs...');

        $deactivatedCount = 0;

        // ========================================
        // TATTOOERS
        // ========================================

        Tattooer::where('stripe_connect_status', 'active')
            ->where('current_plan', 'free') // Seulement plan FREE
            ->chunk(100, function ($tattooers) use (&$deactivatedCount) {
                foreach ($tattooers as $tattooer) {
                    if ($tattooer->shouldBeDeactivated()) {
                        $tattooer->deactivateStripeConnect('inactivity');
                        $deactivatedCount++;

                        $this->warn("  ⏸️ Désactivé: {$tattooer->name} (Tattooer #{$tattooer->id})");

                        // TODO: Envoyer notification email
                        // Mail::to($tattooer->user)->send(new StripeConnectDeactivatedMail($tattooer));
                    }
                }
            });

        // ========================================
        // STUDIO ARTISTS
        // ========================================

        StudioArtist::where('stripe_connect_status', 'active')
            ->chunk(100, function ($artists) use (&$deactivatedCount) {
                foreach ($artists as $artist) {
                    // Skip si studio gère paiements
                    if ($artist->studio && $artist->studio->managesPaymentsCentrally()) {
                        continue;
                    }

                    if ($artist->shouldBeDeactivated()) {
                        $artist->deactivateStripeConnect('inactivity');
                        $deactivatedCount++;

                        $this->warn("  ⏸️ Désactivé: {$artist->name} (StudioArtist #{$artist->id})");
                    }
                }
            });

        // ========================================
        // STATISTIQUES
        // ========================================

        $this->newLine();
        $this->info('📊 STATISTIQUES :');

        $stats = [
            'tattooers_active' => Tattooer::where('stripe_connect_status', 'active')->count(),
            'tattooers_inactive' => Tattooer::where('stripe_connect_status', 'inactive')->count(),
            'artists_active' => StudioArtist::where('stripe_connect_status', 'active')->count(),
            'artists_inactive' => StudioArtist::where('stripe_connect_status', 'inactive')->count(),
        ];

        $totalActive = $stats['tattooers_active'] + $stats['artists_active'];
        $stripeCostPerMonth = $totalActive * 2; // 2€ par compte actif

        $this->table(
            ['Catégorie', 'Actifs', 'Inactifs'],
            [
                ['Tattooers', $stats['tattooers_active'], $stats['tattooers_inactive']],
                ['Studio Artists', $stats['artists_active'], $stats['artists_inactive']],
                ['TOTAL', $totalActive, $stats['tattooers_inactive'] + $stats['artists_inactive']],
            ]
        );

        $this->newLine();
        $this->info("💰 Coût Stripe mensuel estimé : {$stripeCostPerMonth}€ ({$totalActive} comptes actifs)");
        $this->info("✅ {$deactivatedCount} comptes désactivés ce jour");

        return 0;
    }
}

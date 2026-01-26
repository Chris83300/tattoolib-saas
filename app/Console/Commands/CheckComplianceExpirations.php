<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ComplianceRecord;
use App\Models\Tattooer;
use App\Models\StudioArtist;

class CheckComplianceExpirations extends Command
{
    protected $signature = 'compliance:check';
    protected $description = 'Vérifie les expirations de certifications et envoie alertes';

    public function handle()
    {
        $this->info('🔍 Vérification conformité réglementaire...');

        // ===========================================
        // 1. METTRE À JOUR STATUTS CERTIFICATIONS
        // ===========================================

        $this->info('📋 Mise à jour statuts certifications...');

        $recordsUpdated = 0;
        ComplianceRecord::whereNotNull('expires_at')
            ->chunk(100, function ($records) use (&$recordsUpdated) {
                foreach ($records as $record) {
                    $record->updateStatus();
                    $record->checkAndSendAlerts();
                    $recordsUpdated++;
                }
            });

        $this->info("✅ {$recordsUpdated} certifications vérifiées");

        // ===========================================
        // 2. METTRE À JOUR STATUTS TATTOOERS
        // ===========================================

        $this->info('👨‍🎨 Mise à jour statuts Tattooers...');

        $tattooersUpdated = 0;
        Tattooer::chunk(100, function ($tattooers) use (&$tattooersUpdated) {
            foreach ($tattooers as $tattooer) {
                $tattooer->updateComplianceStatus();
                $tattooersUpdated++;
            }
        });

        $this->info("✅ {$tattooersUpdated} tattooers vérifiés");

        // ===========================================
        // 3. METTRE À JOUR STATUTS STUDIO ARTISTS
        // ===========================================

        $this->info('🏢 Mise à jour statuts Studio Artists...');

        $artistsUpdated = 0;
        StudioArtist::chunk(100, function ($artists) use (&$artistsUpdated) {
            foreach ($artists as $artist) {
                $artist->updateComplianceStatus();
                $artistsUpdated++;
            }
        });

        $this->info("✅ {$artistsUpdated} studio artists vérifiés");

        // ===========================================
        // 4. STATISTIQUES GLOBALES
        // ===========================================

        $this->newLine();
        $this->info('📊 STATISTIQUES CONFORMITÉ :');

        $tattooerStats = [
            'compliant' => Tattooer::where('compliance_status', 'compliant')->count(),
            'expiring_soon' => Tattooer::where('compliance_status', 'expiring_soon')->count(),
            'non_compliant' => Tattooer::where('compliance_status', 'non_compliant')->count(),
        ];

        $certificationStats = [
            'expired' => ComplianceRecord::where('status', 'expired')->count(),
            'expiring_soon' => ComplianceRecord::where('status', 'expiring_soon')->count(),
            'pending' => ComplianceRecord::where('status', 'pending')->count(),
            'valid' => ComplianceRecord::where('status', 'valid')->count(),
        ];

        $this->table(
            ['Statut Artistes', 'Nombre'],
            [
                ['✅ Conformes (badge)', $tattooerStats['compliant']],
                ['⚠️ Expire bientôt', $tattooerStats['expiring_soon']],
                ['📋 Non conformes', $tattooerStats['non_compliant']],
            ]
        );

        $this->newLine();

        $this->table(
            ['Statut Certifications', 'Nombre'],
            [
                ['✅ Valides', $certificationStats['valid']],
                ['⚠️ Expirent bientôt', $certificationStats['expiring_soon']],
                ['⏳ En attente', $certificationStats['pending']],
                ['❌ Expirées', $certificationStats['expired']],
            ]
        );

        // ===========================================
        // 5. ALERTES CRITIQUES
        // ===========================================

        if ($certificationStats['expired'] > 0) {
            $this->error("⚠️ ATTENTION : {$certificationStats['expired']} certifications expirées !");
        }

        if ($certificationStats['expiring_soon'] > 0) {
            $this->warn("⚠️ {$certificationStats['expiring_soon']} certifications expirent bientôt");
        }

        if ($certificationStats['pending'] > 0) {
            $this->info("ℹ️ {$certificationStats['pending']} certifications en attente validation admin");
        }

        $this->newLine();
        $this->info('✅ Vérification terminée avec succès');

        return 0;
    }
}

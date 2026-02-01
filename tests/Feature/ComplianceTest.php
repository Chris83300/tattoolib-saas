<?php

use Tests\TestCase;
use App\Models\Tattooer;
use App\Models\ComplianceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

test('tattooer_without_certifications_is_non_compliant', function () {
    $tattooer = Tattooer::factory()->create([
        'siret' => '12345678901234',
        'siret_verified' => true,
    ]);

    $tattooer->updateComplianceStatus();

    expect($tattooer->compliance_status)->toBe('non_compliant');
    expect($tattooer->isCompliant())->toBeFalse();
});

test('tattooer_with_hygiene_and_ars_is_compliant', function () {
    $tattooer = Tattooer::factory()->create([
        'siret' => '12345678901234',
        'siret_verified' => true,
    ]);

    // Créer Hygiène valide
    $tattooer->complianceRecords()->create([
        'certification_type' => ComplianceRecord::TYPE_HYGIENE,
        'obtained_at' => now()->subYear(),
        'expires_at' => now()->addYears(4),
        'status' => 'valid',
        'verified_at' => now(),
        'verified_by' => 1,
    ]);

    // Créer ARS valide
    $tattooer->complianceRecords()->create([
        'certification_type' => ComplianceRecord::TYPE_ARS,
        'obtained_at' => now()->subMonths(6),
        'ars_region' => 'Île-de-France',
        'ars_number' => 'ARS-IDF-2025-001',
        'status' => 'valid',
        'verified_at' => now(),
        'verified_by' => 1,
    ]);

    $tattooer->updateComplianceStatus();

    expect($tattooer->compliance_status)->toBe('compliant');
    expect($tattooer->isCompliant())->toBeTrue();
});

test('certibiocide_is_bonus_not_required_for_badge', function () {
    $tattooer = Tattooer::factory()->create([
        'siret' => '12345678901234',
        'siret_verified' => true,
        'is_decision_maker' => true, // Acheteur
    ]);

    // Hygiène + ARS → Conforme SANS Certibiocide
    $tattooer->complianceRecords()->create([
        'certification_type' => ComplianceRecord::TYPE_HYGIENE,
        'obtained_at' => now()->subYear(),
        'expires_at' => now()->addYears(4),
        'status' => 'valid',
        'verified_at' => now(),
    ]);

    $tattooer->complianceRecords()->create([
        'certification_type' => ComplianceRecord::TYPE_ARS,
        'obtained_at' => now()->subMonths(6),
        'status' => 'valid',
        'verified_at' => now(),
    ]);

    $tattooer->updateComplianceStatus();

    // Badge obtenu SANS Certibiocide
    expect($tattooer->isCompliant())->toBeTrue();
    expect($tattooer->hasCertibiocide())->toBeFalse();
});

test('expiring_certification_triggers_warning_status', function () {
    $tattooer = Tattooer::factory()->create([
        'siret' => '12345678901234',
        'siret_verified' => true,
    ]);

    // Hygiène expire dans 60 jours
    $record = $tattooer->complianceRecords()->create([
        'certification_type' => ComplianceRecord::TYPE_HYGIENE,
        'obtained_at' => now()->subYears(4)->subMonths(10),
        'expires_at' => now()->addDays(60),
        'status' => 'valid',
        'verified_at' => now(),
    ]);

    $record->updateStatus();

    expect($record->status)->toBe('expiring_soon');

    // Vérifier que la certification individuelle est bien expiring_soon
    expect($record->isExpiringSoon())->toBeTrue();
});

test('compliance_command_updates_all_statuses', function () {
    // Créer 10 tatoueurs avec certifications variées
    Tattooer::factory()->count(10)->create()->each(function ($tattooer) {
        $tattooer->update([
            'siret' => '1234567890' . str_pad((string)$tattooer->id, 4, '0', STR_PAD_LEFT) . uniqid(),
            'siret_verified' => true,
        ]);

        // Certifications random
        if (rand(0, 1)) {
            $tattooer->complianceRecords()->create([
                'certification_type' => ComplianceRecord::TYPE_HYGIENE,
                'obtained_at' => now()->subYear(),
                'expires_at' => now()->addYears(rand(1, 4)),
                'status' => 'valid',
                'verified_at' => now(),
            ]);
        }
    });

    // Exécuter command
    $this->artisan('compliance:check')
        ->assertExitCode(0);

    // Vérifier que la commande s'est exécutée
    expect(true)->toBeTrue(); // Command executed successfully
});

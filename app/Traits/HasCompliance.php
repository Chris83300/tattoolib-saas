<?php

namespace App\Traits;

use App\Models\ComplianceRecord;

trait HasCompliance
{
    // =====================================
    // RELATIONS
    // =====================================

    public function complianceRecords()
    {
        return $this->morphMany(ComplianceRecord::class, 'compliant');
    }

    public function hygieneRecord()
    {
        return $this->morphOne(ComplianceRecord::class, 'compliant')
            ->where('certification_type', ComplianceRecord::TYPE_HYGIENE)
            ->latest();
    }

    public function certibiocideRecord()
    {
        return $this->morphOne(ComplianceRecord::class, 'compliant')
            ->where('certification_type', ComplianceRecord::TYPE_CERTIBIOCIDE)
            ->latest();
    }

    public function arsRecord()
    {
        return $this->morphOne(ComplianceRecord::class, 'compliant')
            ->where('certification_type', ComplianceRecord::TYPE_ARS)
            ->latest();
    }

    // =====================================
    // STATUT GLOBAL DE CONFORMITÉ
    // =====================================

    /**
     * Calcule et met à jour le statut global de conformité
     *
     * NOUVELLE LOGIQUE :
     * - SIRET obligatoire (vérifié avant)
     * - Badge si : Hygiène + ARS valides
     * - Certibiocide = BONUS (pas bloquant)
     */
    public function updateComplianceStatus(): void
    {
        // SIRET vérifié au préalable (inscription)

        $hygiene = $this->hygieneRecord;
        $ars = $this->arsRecord;

        // 1. Vérifier Hygiène (OBLIGATOIRE pour badge)
        if (!$hygiene || !$hygiene->isValid()) {
            $this->compliance_status = 'non_compliant';
            $this->last_compliance_check_at = now();
            $this->save();
            return;
        }

        // 2. Vérifier ARS (OBLIGATOIRE pour badge)
        if (!$ars || !$ars->isValid()) {
            $this->compliance_status = 'non_compliant';
            $this->last_compliance_check_at = now();
            $this->save();
            return;
        }

        // 3. Vérifier si certifications expirent bientôt
        $expiringSoon = $this->complianceRecords()
            ->whereIn('certification_type', [
                ComplianceRecord::TYPE_HYGIENE,
                ComplianceRecord::TYPE_ARS
            ])
            ->where('status', 'expiring_soon')
            ->exists();

        if ($expiringSoon) {
            $this->compliance_status = 'expiring_soon';
        } else {
            $this->compliance_status = 'compliant';
        }

        $this->last_compliance_check_at = now();
        $this->save();
    }

    // =====================================
    // HELPERS CONFORMITÉ
    // =====================================

    /**
     * Vérifie si l'artiste est conforme (a le badge)
     */
    public function isCompliant(): bool
    {
        return $this->compliance_status === 'compliant';
    }

    /**
     * Vérifie si certifications expirent bientôt
     */
    public function hasExpiringSoonCertifications(): bool
    {
        return $this->compliance_status === 'expiring_soon';
    }

    /**
     * Badge HTML du statut global
     */
    public function getComplianceBadgeAttribute(): string
    {
        return match($this->compliance_status) {
            'compliant' => '✅ Conforme - réglementation française',
            'expiring_soon' => '⚠️ Certification à renouveler prochainement',
            'non_compliant' => '📋 En cours de mise en conformité',
            default => '📋 Statut inconnu'
        };
    }

    /**
     * Badge HTML avec info bulle (pour affichage frontend)
     */
    public function getComplianceBadgeHtmlAttribute(): string
    {
        $lastCheck = $this->last_compliance_check_at?->format('d/m/Y') ?? 'N/A';

        return match($this->compliance_status) {
            'compliant' => sprintf(
                '<div class="badge badge-success" data-tooltip="Vérifié le %s - Critères: SIRET + Hygiène + ARS">
                    ✅ Conforme - réglementation française
                    <a href="/conformite" class="badge-info">ℹ️</a>
                </div>',
                $lastCheck
            ),
            'expiring_soon' => '<span class="badge badge-warning">⚠️ Certification à renouveler</span>',
            'non_compliant' => '<span class="badge badge-secondary">📋 En cours de mise en conformité</span>',
            default => '<span class="badge badge-light">📋 Statut inconnu</span>'
        };
    }

    /**
     * Couleur du badge
     */
    public function getComplianceBadgeColorAttribute(): string
    {
        return match($this->compliance_status) {
            'compliant' => 'success',
            'expiring_soon' => 'warning',
            'non_compliant' => 'secondary',
            default => 'light'
        };
    }

    /**
     * Liste des certifications manquantes pour badge
     */
    public function getMissingCertificationsForBadgeAttribute(): array
    {
        $missing = [];

        if (!$this->hygieneRecord || !$this->hygieneRecord->isValid()) {
            $missing[] = 'Hygiène & Salubrité';
        }

        if (!$this->arsRecord || !$this->arsRecord->isValid()) {
            $missing[] = 'Déclaration ARS';
        }

        return $missing;
    }

    /**
     * Vérifie si Certibiocide présent (bonus)
     */
    public function hasCertibiocide(): bool
    {
        $certibiocide = $this->certibiocideRecord;
        return $certibiocide && $certibiocide->isValid();
    }

    /**
     * Message d'alerte global
     */
    public function getComplianceAlertMessageAttribute(): ?string
    {
        if ($this->compliance_status === 'non_compliant') {
            $missing = $this->missing_certifications_for_badge;
            if (!empty($missing)) {
                return "📋 Complétez votre profil pour obtenir le badge : " . implode(', ', $missing);
            }
        }

        if ($this->compliance_status === 'expiring_soon') {
            return "⚠️ ATTENTION : Au moins une de vos certifications expire bientôt";
        }

        return null;
    }
}

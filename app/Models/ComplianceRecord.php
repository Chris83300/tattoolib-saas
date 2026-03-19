<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ComplianceRecord extends Model
{
    use SoftDeletes;

    // =====================================
    // CONSTANTES
    // =====================================

    // Types de certification
    const TYPE_HYGIENE = 'hygiene_salubrite';
    const TYPE_CERTIBIOCIDE = 'certibiocide';
    const TYPE_ARS = 'declaration_ars';

    // Statuts
    const STATUS_VALID = 'valid';
    const STATUS_EXPIRING_SOON = 'expiring_soon';
    const STATUS_EXPIRED = 'expired';
    const STATUS_MISSING = 'missing';
    const STATUS_PENDING = 'pending';

    // Délais alertes (jours)
    const ALERT_90_DAYS = 90;
    const ALERT_30_DAYS = 30;

    // =====================================
    // CONFIGURATION
    // =====================================

    protected $fillable = [
        'compliant_type',
        'compliant_id',
        'certification_type',
        'certificate_number',
        'training_organization',
        'obtained_at',
        'expires_at',
        'certificate_file_path',
        'ars_proof_file_path',
        'status',
        'biocide_type',
        'is_decision_maker',
        'ars_region',
        'ars_number',
        'notification_90d_sent_at',
        'notification_30d_sent_at',
        'notification_expired_sent_at',
        'verified_by',
        'verified_at',
        'admin_notes',
    ];

    protected $appends = ['verified_by_admin'];

    protected $casts = [
        'obtained_at' => 'date',
        'expires_at' => 'date',
        'is_decision_maker' => 'boolean',
        'verified_at' => 'datetime',
        'notification_90d_sent_at' => 'datetime',
        'notification_30d_sent_at' => 'datetime',
        'notification_expired_sent_at' => 'datetime',
    ];

    // =====================================
    // ACCESSOR / MUTATOR — verified_by_admin
    // =====================================

    /**
     * Virtual attribute : le toggle Filament lit/écrit ici.
     * true  = verified_at est renseigné.
     */
    public function getVerifiedByAdminAttribute(): bool
    {
        return !is_null($this->verified_at);
    }

    public function setVerifiedByAdminAttribute(bool $value): void
    {
        if ($value && is_null($this->verified_at)) {
            $this->verified_at = now();
            $this->verified_by = auth()->id();
            $this->status = self::STATUS_VALID;
        } elseif (!$value && !is_null($this->verified_at)) {
            $this->verified_at = null;
            $this->verified_by = null;
            $this->status = self::STATUS_PENDING;
        }
    }

    // =====================================
    // BADGE CONFORMITÉ SUR L'ARTISAN
    // =====================================

    /**
     * Recalcule has_compliance_badge sur l'artisan propriétaire.
     * Badge = hygiene_salubrite ET declaration_ars tous deux vérifiés.
     * Invalide aussi le cache marketplace.
     */
    public function syncComplianceBadge(): void
    {
        $artisan = $this->compliant;
        if (!$artisan) return;

        $hasHygiene = $artisan->complianceRecords()
            ->where('certification_type', self::TYPE_HYGIENE)
            ->whereNotNull('verified_at')
            ->exists();

        $hasArs = $artisan->complianceRecords()
            ->where('certification_type', self::TYPE_ARS)
            ->whereNotNull('verified_at')
            ->exists();

        $artisan->update(['has_compliance_badge' => $hasHygiene && $hasArs]);

        app(\App\Services\CacheService::class)->invalidateAllArtistCache($artisan);
    }

    // =====================================
    // RELATIONS
    // =====================================

    public function compliant()
    {
        return $this->morphTo();
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // =====================================
    // MÉTHODES AUTO-CALCUL STATUT
    // =====================================

    public function updateStatus(): void
    {
        // ARS n'expire jamais
        if ($this->certification_type === self::TYPE_ARS) {
            $this->status = $this->verified_at ? self::STATUS_VALID : self::STATUS_PENDING;
            $this->save();
            return;
        }

        // Si pas encore vérifié par admin
        if (!$this->verified_at) {
            $this->status = self::STATUS_PENDING;
            $this->save();
            return;
        }

        // Si pas de date d'expiration (erreur)
        if (!$this->expires_at) {
            $this->status = self::STATUS_MISSING;
            $this->save();
            return;
        }

        // Calculer jours restants
        $now = Carbon::now();
        $daysUntilExpiry = $now->diffInDays($this->expires_at, false);

        if ($daysUntilExpiry < 0) {
            $this->status = self::STATUS_EXPIRED;
        } elseif ($daysUntilExpiry <= self::ALERT_90_DAYS) {
            $this->status = self::STATUS_EXPIRING_SOON;
        } else {
            $this->status = self::STATUS_VALID;
        }

        $this->save();
    }

    public function checkAndSendAlerts(): void
    {
        if ($this->certification_type === self::TYPE_ARS || !$this->verified_at) {
            return;
        }

        if (!$this->expires_at) {
            return;
        }

        $now = Carbon::now();
        $daysUntilExpiry = $now->diffInDays($this->expires_at, false);

        // Alerte J-90
        if ($daysUntilExpiry <= self::ALERT_90_DAYS &&
            $daysUntilExpiry > self::ALERT_30_DAYS &&
            !$this->notification_90d_sent_at) {

            event(new \App\Events\ComplianceExpiringEvent($this, 90));
            $this->update(['notification_90d_sent_at' => now()]);
        }

        // Alerte J-30
        if ($daysUntilExpiry <= self::ALERT_30_DAYS &&
            $daysUntilExpiry > 0 &&
            !$this->notification_30d_sent_at) {

            event(new \App\Events\ComplianceExpiringEvent($this, 30));
            $this->update(['notification_30d_sent_at' => now()]);
        }

        // Notification expiration
        if ($daysUntilExpiry <= 0 && !$this->notification_expired_sent_at) {
            event(new \App\Events\ComplianceExpiredEvent($this));
            $this->update(['notification_expired_sent_at' => now()]);
        }
    }

    // =====================================
    // HELPERS
    // =====================================

    public function isValid(): bool
    {
        return $this->status === self::STATUS_VALID;
    }

    public function isExpiringSoon(): bool
    {
        return $this->status === self::STATUS_EXPIRING_SOON;
    }

    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isMissing(): bool
    {
        return $this->status === self::STATUS_MISSING;
    }

    public function getDaysUntilExpiryAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return Carbon::now()->diffInDays($this->expires_at, false);
    }

    public function getBadgeHtmlAttribute(): string
    {
        return match($this->status) {
            self::STATUS_VALID => '<span class="badge bg-success">✅ Valide</span>',
            self::STATUS_EXPIRING_SOON => '<span class="badge bg-warning">⚠️ Expire bientôt</span>',
            self::STATUS_EXPIRED => '<span class="badge bg-danger">❌ Expiré</span>',
            self::STATUS_PENDING => '<span class="badge bg-info">⏳ En attente</span>',
            self::STATUS_MISSING => '<span class="badge bg-secondary">📋 Manquant</span>',
            default => '<span class="badge bg-secondary">?</span>',
        };
    }

    public function getAlertMessageAttribute(): ?string
    {
        if ($this->isExpired()) {
            return "⚠️ ATTENTION : Votre {$this->getCertificationLabel()} a expiré.";
        }

        if ($this->isExpiringSoon() && $this->days_until_expiry !== null) {
            return "⚠️ Votre {$this->getCertificationLabel()} expire dans {$this->days_until_expiry} jours.";
        }

        if ($this->isPending()) {
            return "⏳ Votre {$this->getCertificationLabel()} est en attente de vérification.";
        }

        return null;
    }

    public function getCertificationLabel(): string
    {
        return match($this->certification_type) {
            self::TYPE_HYGIENE => 'Formation Hygiène & Salubrité',
            self::TYPE_CERTIBIOCIDE => 'Certibiocide Désinfectants',
            self::TYPE_ARS => 'Déclaration ARS',
            default => 'Certification',
        };
    }
}

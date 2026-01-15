<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_request_id',
        'tattooer_id',
        'client_id',

        // Date/heure
        'opening_time',
        'closing_time',
        'duration_minutes',

        // Montants
        'deposit_amount',
        'total_price',
        'remaining_amount',

        // Statuts
        'status',

        // Annulation
        'cancelled_by',
        'cancelled_at',
        'cancellation_reason',
        'days_before_appointment',

        // Remboursement
        'refunded',
        'refund_amount',
        'refunded_at',
        'stripe_refund_id',

        // Confirmation post-RDV
        'tattooer_confirmation_status',
        'tattooer_confirmation_note',
        'tattooer_confirmed_at',

        // Signalement client
        'client_reported_issue',
        'client_issue_description',
        'client_reported_at',

        // Résolution
        'requires_manual_review',

        // Contestation
        'client_dispute_refund',
        'client_dispute_reason',
        'client_dispute_at',
        'dispute_resolution',
        'dispute_refund_amount',
        'dispute_resolution_note',
        'dispute_resolved_at',
        'dispute_resolved_by',
    ];

    protected $casts = [
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
        'deposit_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'dispute_refund_amount' => 'decimal:2',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
        'tattooer_confirmed_at' => 'datetime',
        'client_reported_at' => 'datetime',
        'client_dispute_at' => 'datetime',
        'dispute_resolved_at' => 'datetime',
        'refunded' => 'boolean',
        'client_reported_issue' => 'boolean',
        'client_dispute_refund' => 'boolean',
        'requires_manual_review' => 'boolean',
    ];

    // ===== CONSTANTES =====

    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CLIENT_NO_SHOW = 'client_no_show';
    const STATUS_TATTOOER_NO_SHOW = 'tattooer_no_show';
    const STATUS_DISPUTED = 'disputed';

    const TATTOOER_CONFIRMATION_PENDING = 'pending';
    const TATTOOER_CONFIRMATION_COMPLETED = 'completed';
    const TATTOOER_CONFIRMATION_CLIENT_NO_SHOW = 'client_no_show';
    const TATTOOER_CONFIRMATION_CLIENT_LATE = 'client_late';
    const TATTOOER_CONFIRMATION_OTHER_ISSUE = 'other_issue';

    // ===== RELATIONS =====

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function tattooer(): BelongsTo
    {
        return $this->belongsTo(Tattooer::class);
    }

    // ===== SCOPES =====

    public function scopeUpcoming($query)
    {
        return $query->where('opening_time', '>', now())
            ->where('status', self::STATUS_CONFIRMED)
            ->orderBy('opening_time');
    }

    public function scopePast($query)
    {
        return $query->where('opening_time', '<', now())
            ->orderBy('opening_time', 'desc');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('tattooer_id', $tattooerId);
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeRequiringConfirmation($query)
    {
        return $query->where('opening_time', '<', now())
            ->where('status', self::STATUS_CONFIRMED)
            ->whereNull('tattooer_confirmation_status');
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Confirmer la réalisation du RDV (par le tatoueur)
     */
    public function confirmCompletion(string $note = null): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'tattooer_confirmation_status' => self::TATTOOER_CONFIRMATION_COMPLETED,
            'tattooer_confirmation_note' => $note,
            'tattooer_confirmed_at' => now(),
        ]);

        // TODO: Event AppointmentCompleted
    }

    /**
     * Signaler un no-show client
     */
    public function reportClientNoShow(string $note = null): void
    {
        $this->update([
            'status' => self::STATUS_CLIENT_NO_SHOW,
            'tattooer_confirmation_status' => self::TATTOOER_CONFIRMATION_CLIENT_NO_SHOW,
            'tattooer_confirmation_note' => $note,
            'tattooer_confirmed_at' => now(),
        ]);

        // Incrémenter le compteur de no-show du client
        $this->client->incrementNoShow();

        // TODO: Event ClientNoShow
    }

    /**
     * Client signale un problème
     */
    public function reportIssue(string $description): void
    {
        $this->update([
            'client_reported_issue' => true,
            'client_issue_description' => $description,
            'client_reported_at' => now(),
            'requires_manual_review' => true,
            'status' => self::STATUS_DISPUTED,
        ]);

        // TODO: Event AppointmentDisputed
    }

    /**
     * Annuler le RDV
     */
    public function cancel(string $cancelledBy, string $reason): void
    {
        $daysBeforeAppointment = now()->diffInDays($this->opening_time, false);

        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_by' => $cancelledBy,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
            'days_before_appointment' => max(0, $daysBeforeAppointment),
        ]);

        // Logique de remboursement selon les conditions
        $this->processRefund($cancelledBy, $daysBeforeAppointment);

        // TODO: Event AppointmentCancelled
    }

    /**
     * Traiter le remboursement selon la politique
     * Prend en compte le travail déjà fourni (dessins)
     */
    private function processRefund(string $cancelledBy, int $daysBeforeAppointment): void
    {
        $refundAmount = 0;
        $designsUsed = $this->bookingRequest->design_versions_used ?? 0;

        // Politique de remboursement
        if ($cancelledBy === 'tattooer') {
            // Tatoueur annule
            if ($designsUsed === 0) {
                // Aucun travail fourni : remboursement intégral
                $refundAmount = $this->deposit_amount;
            } elseif ($designsUsed < 3) {
                // 1-2 dessins : remboursement partiel
                $refundAmount = $this->deposit_amount * 0.5; // 50%
            } else {
                // 3 dessins (travail complet) : 0 remboursement
                $refundAmount = 0;
            }

        } elseif ($cancelledBy === 'client') {
            // Client annule
            if ($designsUsed === 0) {
                // Aucun dessin reçu : politique standard
                if ($daysBeforeAppointment >= 7) {
                    $refundAmount = $this->deposit_amount; // 100%
                } elseif ($daysBeforeAppointment >= 3) {
                    $refundAmount = $this->deposit_amount * 0.5; // 50%
                }
                // < 3 jours : 0%

            } elseif ($designsUsed === 1) {
                // 1 dessin reçu : 70% remboursé
                $refundAmount = $this->deposit_amount * 0.7;

            } elseif ($designsUsed === 2) {
                // 2 dessins reçus : 40% remboursé
                $refundAmount = $this->deposit_amount * 0.4;

            } else {
                // 3 dessins reçus (travail complet)
                // PAS de remboursement automatique
                // Le client peut contester si dessin non conforme
                $refundAmount = 0;
            }
        }

        if ($refundAmount > 0) {
            $this->update([
                'refunded' => true,
                'refund_amount' => $refundAmount,
                'refunded_at' => now(),
            ]);

            // TODO: Lancer le job de remboursement Stripe
            // \App\Jobs\ProcessStripeRefund::dispatch($this);
        } else {
            // Pas de remboursement mais on enregistre la décision
            $this->update([
                'refunded' => false,
                'refund_amount' => 0,
            ]);
        }
    }

    /**
     * Vérifier si le RDV est passé
     */
    public function isPast(): bool
    {
        return $this->opening_time->isPast();
    }

    /**
     * Vérifier si le RDV nécessite confirmation
     */
    public function needsConfirmation(): bool
    {
        return $this->isPast()
            && $this->status === self::STATUS_CONFIRMED
            && !$this->tattooer_confirmation_status;
    }

    /**
     * Vérifier si annulable
     */
    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_CONFIRMED])
            && $this->opening_time->isFuture();
    }

    /**
     * Client conteste le remboursement (dessins non conformes)
     */
    public function disputeRefund(string $reason): void
    {
        $this->update([
            'client_dispute_refund' => true,
            'client_dispute_reason' => $reason,
            'client_dispute_at' => now(),
            'dispute_resolution' => 'pending',
            'requires_manual_review' => true,
        ]);

        // TODO: Notifier l'équipe admin
        // AdminNotification::dispatch($this);
    }

    /**
     * Résoudre une contestation (admin uniquement)
     */
    public function resolveDispute(
        string $resolution,
        float $refundAmount = null,
        string $note = null,
        int $adminId
    ): void {
        $this->update([
            'dispute_resolution' => $resolution,
            'dispute_refund_amount' => $refundAmount,
            'dispute_resolution_note' => $note,
            'dispute_resolved_at' => now(),
            'dispute_resolved_by' => $adminId,
        ]);

        // Si remboursement approuvé, lancer le job Stripe
        if ($resolution === 'approved' && $refundAmount > 0) {
            // TODO: Implémenter le Job
            // \App\Jobs\ProcessStripeRefund::dispatch($this, $refundAmount);
        }

        // TODO: Notifier le client et le tatoueur
    }
}

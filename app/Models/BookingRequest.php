<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class BookingRequest extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'client_id',
        'bookable_id',
        'bookable_type',

        // Infos projet
        'tattoo_size',
        'body_zone',
        'description',
        'estimated_total_price',

        // Préférences date
        'preferred_timeframe',
        'preferred_days',
        'date_notes',
        'preferred_date',
        'preferred_time_slot',

        // Montants
        'total_deposit_amount',
        'total_price',
        'deposit_paid_at',

        // Délais
        'client_payment_deadline_days',
        'tattooer_design_deadline_days',
        'client_payment_deadline',
        'tattooer_design_deadline',
        'deposit_deadline',
        'design_sent_at',

        // Gestion long terme
        'is_long_term_booking',
        'design_preparation_starts_at',
        'design_preparation_notified',

        // Versions design
        'included_design_versions',
        'design_versions_used',

        // Stripe
        'stripe_payment_intent_id',

        // Statuts
        'status',
        'tattooer_missed_deadline',
        'client_missed_deadline',

        // RDV
        'appointment_datetime',
        'appointment_duration_minutes',

        // Champs ajoutés pour l'acceptation
        'scheduled_date',
        'scheduled_start_time',
        'scheduled_end_time',
        'scheduled_duration_minutes',
        'deposit_rate',
        'deposit_deadline_hours',
        'accepted_at' => 'datetime',
        'expired_at' => 'datetime',

        // === 🎯 MODAL VALIDATION TATTOOER ===
        'price_range_min',
        'price_range_max',
        'proposed_dates',
        'deposit_covers_description',

        // === 🎨 RÈGLES MODIFICATION DESSIN ===
        'design_modification_rules',
        'modifications_per_version',
        'modifications_used',

        // === 💬 CHAT TEMPORAIRE ===
        'chat_closes_at',
        'chat_status',

        // === 💰 PRIX FINAL ===
        'confirmed_final_price',
        'final_price_confirmed',
        'final_price_confirmed_at',

        // === ❌ ANNULATION ===
        'cancelled_by',
        'cancellation_reason',
        'cancelled_at',
        'refund_amount',

        // === ⚖️ CONTESTATION ===
        'dispute_status',
        'dispute_opened_at',
        'dispute_reason',
        'dispute_resolved_at',
        'dispute_resolution',
        'dispute_refund_amount',
    ];

    protected $casts = [
        'total_deposit_amount' => 'decimal:2',
        'estimated_total_price' => 'decimal:2',
        'preferred_days' => 'array',
        'preferred_date' => 'date',
        'client_payment_deadline' => 'datetime',
        'tattooer_design_deadline' => 'datetime',
        'design_sent_at' => 'datetime',
        'design_preparation_starts_at' => 'datetime',
        'appointment_datetime' => 'datetime',
        'is_long_term_booking' => 'boolean',
        'design_preparation_notified' => 'boolean',
        'tattooer_missed_deadline' => 'boolean',
        'client_missed_deadline' => 'boolean',

        // === 🎯 MODAL VALIDATION TATTOOER ===
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'proposed_dates' => 'array',

        // === 💬 CHAT TEMPORAIRE ===
        'chat_closes_at' => 'datetime',

        // === 💰 PRIX FINAL ===
        'confirmed_final_price' => 'decimal:2',
        'final_price_confirmed' => 'boolean',
        'final_price_confirmed_at' => 'datetime',

        // === ❌ ANNULATION ===
        'cancelled_at' => 'datetime',
        'refund_amount' => 'decimal:2',

        // === ⚖️ CONTESTATION ===
        'dispute_opened_at' => 'datetime',
        'dispute_resolved_at' => 'datetime',
        'dispute_refund_amount' => 'decimal:2',
    ];

    // ===== CONSTANTES DE STATUT =====

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_AWAITING_DEPOSIT = 'awaiting_deposit';
    const STATUS_DEPOSIT_PAID = 'deposit_paid';
    const STATUS_DESIGN_SENT = 'design_sent';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    // ===== STATUTS CHAT =====
    const CHAT_STATUS_CLOSED = 'closed';
    const CHAT_STATUS_OPEN = 'open';
    const CHAT_STATUS_EXPIRED = 'expired';

    // ===== STATUTS CONTESTATION =====
    const DISPUTE_STATUS_NONE = 'none';
    const DISPUTE_STATUS_OPEN = 'open';
    const DISPUTE_STATUS_UNDER_REVIEW = 'under_review';
    const DISPUTE_STATUS_RESOLVED = 'resolved';
    const DISPUTE_STATUS_CLOSED = 'closed';

    // ===== TYPES ANNULATION =====
    const CANCELLED_BY_CLIENT = 'client';
    const CANCELLED_BY_TATTOOER = 'tattooer';
    const CANCELLED_BY_SYSTEM = 'system';
    const CANCELLED_BY_ADMIN = 'admin';

    const TIMEFRAMES = [
        'asap' => 'Dès que possible',
        '3-4months' => '3-4 mois',
        '5-6months' => '5-6 mois',
        '6plus' => 'Plus de 6 mois',
    ];

    // ===== RELATIONS =====

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function bookable()
    {
        return $this->morphTo();
    }

    // Helper rétrocompatibilité
    public function getTattooerAttribute()
    {
        return $this->bookable;
    }

    public function conversation(): HasOne
    {
        return $this->hasOne(Conversation::class);
    }

    public function appointment(): HasOne
    {
        return $this->hasOne(Appointment::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Vérifier si le chat permet l'envoi d'images
     */
    public function canSendImages(): bool
    {
        // Si le chat est fermé, impossible d'envoyer des images
        if ($this->chat_status !== 'open') {
            return false;
        }

        // Si l'acompte n'est pas payé et le délai est dépassé, impossible d'envoyer des images
        if (!$this->deposit_paid_at && $this->chat_closes_at && $this->chat_closes_at->isPast()) {
            return false;
        }

        // Si l'acompte est payé, on peut envoyer des images
        if ($this->deposit_paid_at) {
            return true;
        }

        // Si l'acompte n'est pas payé mais le délai n'est pas dépassé, on peut envoyer des images
        return true;
    }

    /**
     * Obtenir le nombre de dessins utilisés par rapport à la limite
     */
    public function getDesignVersionsUsedAttribute(): int
    {
        return $this->design_versions_used ?? 0;
    }

    /**
     * Vérifier si on peut encore ajouter des dessins
     */
    public function canAddMoreDesigns(): bool
    {
        return $this->design_versions_used < $this->included_design_versions;
    }

    /**
     * Obtenir le nombre de modifications utilisées pour la version actuelle
     */
    public function getModificationsUsedAttribute(): int
    {
        return $this->modifications_used ?? 0;
    }

    /**
     * Vérifier si on peut encore faire des modifications
     */
    public function canMakeMoreModifications(): bool
    {
        return $this->modifications_used < $this->modifications_per_version;
    }

    // ===== SCOPES =====

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', self::STATUS_ACCEPTED);
    }

    public function scopeAwaitingDeposit($query)
    {
        return $query->where('status', self::STATUS_AWAITING_DEPOSIT);
    }

    public function scopeDepositPaid($query)
    {
        return $query->where('status', self::STATUS_DEPOSIT_PAID);
    }

    public function scopeForTattooer($query, int $tattooerId)
    {
        return $query->where('bookable_id', $tattooerId)
                   ->where('bookable_type', 'App\\Models\\Tattooer');
    }

    public function scopeForClient($query, int $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeOverduePayment($query)
    {
        return $query->where('status', self::STATUS_AWAITING_DEPOSIT)
            ->where('client_payment_deadline', '<', now());
    }

    public function scopeOverdueDesign($query)
    {
        return $query->where('status', self::STATUS_DEPOSIT_PAID)
            ->where('tattooer_design_deadline', '<', now())
            ->whereNull('design_sent_at');
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Accepter la demande
     */
    public function accept(): void
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
        ]);

        // Créer la conversation
        $this->createConversation();

        // TODO: Event BookingRequestAccepted
    }

    /**
     * Rejeter la demande
     */
    public function reject(): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
        ]);

        // TODO: Event BookingRequestRejected
    }

    /**
     * Marquer comme en attente d'acompte
     */
    public function requestDeposit(float $amount, int $deadlineDays): void
    {
        $this->update([
            'status' => self::STATUS_AWAITING_DEPOSIT,
            'total_deposit_amount' => $amount,
            'client_payment_deadline_days' => $deadlineDays,
            'client_payment_deadline' => now()->addDays($deadlineDays),
        ]);

        // TODO: Event DepositRequested
    }

    /**
     * Marquer l'acompte comme payé
     */
    public function markDepositPaid(string $paymentIntentId): void
    {
        $designDeadline = $this->bookable->default_tattooer_design_deadline_days ?? 7;

        $this->update([
            'status' => self::STATUS_DEPOSIT_PAID,
            'stripe_payment_intent_id' => $paymentIntentId,
            'tattooer_design_deadline_days' => $designDeadline,
            'tattooer_design_deadline' => now()->addDays($designDeadline),
        ]);

        // TODO: Event DepositPaid
    }

    /**
     * Envoyer le design
     */
    public function sendDesign(): void
    {
        $this->update([
            'status' => self::STATUS_DESIGN_SENT,
            'design_sent_at' => now(),
            'design_versions_used' => $this->design_versions_used + 1,
        ]);

        // TODO: Event DesignSent
    }

    /**
     * Confirmer le RDV
     */
    public function confirm(\DateTime $appointmentDate, int $durationMinutes): void
    {
        $this->update([
            'status' => self::STATUS_CONFIRMED,
            'appointment_datetime' => $appointmentDate,
            'appointment_duration_minutes' => $durationMinutes,
        ]);

        // Créer l'appointment
        $this->createAppointment();

        // TODO: Event BookingConfirmed
    }

    /**
     * Vérifier si un nouveau design peut être envoyé
     */
    public function canSendNewDesignVersion(): bool
    {
        return $this->design_versions_used < $this->included_design_versions;
    }

    /**
     * Créer la conversation
     */
    private function createConversation(): void
    {
        if ($this->conversation()->exists()) {
            return;
        }

        $conversation = Conversation::create([
            'booking_request_id' => $this->id,
            'subject' => "Projet : {$this->tattoo_size} sur {$this->body_zone}",
            'status' => 'active',
        ]);

        // Ajouter les participants
        $conversation->participants()->attach([
            $this->client->user_id => ['role' => 'client'],
            $this->bookable->user_id => ['role' => 'tattooer'],
        ]);
    }

    // ===== MÉTHODES WORKFLOW COMPLET =====

    /**
     * Ouvrir le chat temporaire
     */
    public function openChat(): void
    {
        $this->update([
            'chat_status' => self::CHAT_STATUS_OPEN,
            'chat_closes_at' => $this->client_payment_deadline,
        ]);
    }

    /**
     * Fermer le chat temporaire
     */
    public function closeChat(): void
    {
        $this->update([
            'chat_status' => self::CHAT_STATUS_CLOSED,
        ]);
    }

    /**
     * Vérifier si le chat est ouvert
     */
    public function isChatOpen(): bool
    {
        // Si le statut est closed, c'est non
        if ($this->chat_status !== self::CHAT_STATUS_OPEN) {
            return false;
        }

        // Si acompte payé, le chat reste ouvert jusqu'au RDV
        if ($this->deposit_paid_at) {
            return true; // Le chat reste ouvert après paiement acompte
        }

        // Vérifier la conversation associée
        $conversation = $this->conversation;
        if (!$conversation) {
            return false;
        }

        // Si la conversation est expirée, le chat est fermé
        if ($conversation->isExpired()) {
            return false;
        }

        // Si la conversation est en phase deposit_pending et expirée
        if ($conversation->isDepositPending() && $conversation->shouldExpire()) {
            return false;
        }

        // Si pas d'acompte payé, vérifier la deadline de paiement
        if ($this->client_payment_deadline) {
            // Utiliser la deadline de la conversation si disponible
            if ($conversation->deposit_deadline_at) {
                return $conversation->deposit_deadline_at->greaterThan(now());
            }
            // Sinon utiliser la deadline du booking avec 24h de marge
            return $this->client_payment_deadline->greaterThan(now()->subHours(24));
        }

        // Si pas de deadline, considérer comme ouvert
        return true;
    }

    /**
     * Confirmer le prix final
     */
    public function confirmFinalPrice(float $finalPrice): void
    {
        $this->update([
            'confirmed_final_price' => $finalPrice,
            'final_price_confirmed' => true,
            'final_price_confirmed_at' => now(),
        ]);
    }

    /**
     * Annuler la demande
     */
    public function cancel(string $cancelledBy, string $reason, float $refundAmount = 0): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_by' => $cancelledBy,
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
            'refund_amount' => $refundAmount,
        ]);

        // Fermer le chat si ouvert
        if ($this->isChatOpen()) {
            $this->closeChat();
        }
    }

    /**
     * Ouvrir une contestation
     */
    public function openDispute(string $reason): void
    {
        $this->update([
            'dispute_status' => self::DISPUTE_STATUS_OPEN,
            'dispute_reason' => $reason,
            'dispute_opened_at' => now(),
        ]);
    }

    /**
     * Résoudre une contestation
     */
    public function resolveDispute(string $resolution, float $refundAmount = null): void
    {
        $this->update([
            'dispute_status' => self::DISPUTE_STATUS_RESOLVED,
            'dispute_resolution' => $resolution,
            'dispute_resolved_at' => now(),
            'dispute_refund_amount' => $refundAmount,
        ]);
    }

    /**
     * Utiliser une modification de dessin
     */
    public function useModification(): bool
    {
        if ($this->modifications_used >= $this->modifications_per_version) {
            return false;
        }

        $this->increment('modifications_used');
        return true;
    }

    /**
     * Obtenir le nombre de modifications restantes
     */
    public function getRemainingModifications(): int
    {
        return max(0, $this->modifications_per_version - $this->modifications_used);
    }

    /**
     * Vérifier si la demande est en contestation
     */
    public function isInDispute(): bool
    {
        return in_array($this->dispute_status, [
            self::DISPUTE_STATUS_OPEN,
            self::DISPUTE_STATUS_UNDER_REVIEW,
        ]);
    }

    /**
     * Obtenir la fourchette de prix formatée
     */
    public function getPriceRange(): string
    {
        if (!$this->price_range_min && !$this->price_range_max) {
            return 'Non définie';
        }

        if ($this->price_range_min && $this->price_range_max) {
            return number_format($this->price_range_min, 2, ',', ' ') . ' € - ' .
                   number_format($this->price_range_max, 2, ',', ' ') . ' €';
        }

        $price = $this->price_range_min ?? $this->price_range_max;
        return 'À partir de ' . number_format($price, 2, ',', ' ') . ' €';
    }

    /**
     * Créer l'appointment
     */
    private function createAppointment(): void
    {
        if ($this->appointment()->exists()) {
            return;
        }

        Appointment::create([
            'booking_request_id' => $this->id,
            'bookable_id' => $this->bookable_id,
            'bookable_type' => $this->bookable_type,
            'client_id' => $this->client_id,
            'start_time' => $this->appointment_datetime,
            'end_time' => $this->appointment_datetime->copy()->addMinutes($this->appointment_duration_minutes),
            'duration_minutes' => $this->appointment_duration_minutes,
            'deposit_amount' => $this->total_deposit_amount,
            'total_price' => $this->estimated_total_price,
            'remaining_amount' => $this->estimated_total_price - $this->total_deposit_amount,
            'status' => Appointment::STATUS_CONFIRMED,
        ]);
    }

    /**
     * Calculer le montant du dépôt
     */
    public function calculateDepositAmount(): float
    {
        return $this->estimated_price * 0.30; // 30% du prix total
    }

    /**
     * Vérifier si la deadline de paiement est dépassée
     */
    public function isPaymentOverdue(): bool
    {
        return $this->status === self::STATUS_AWAITING_DEPOSIT
            && $this->client_payment_deadline
            && $this->client_payment_deadline->isPast();
    }

    /**
     * Vérifier si la deadline de design est dépassée
     */
    public function isDesignOverdue(): bool
    {
        return $this->status === self::STATUS_DEPOSIT_PAID
            && $this->tattooer_design_deadline
            && $this->tattooer_design_deadline->isPast()
            && !$this->design_sent_at;
    }

    /**
     * Configuration des médias pour Spatie MediaLibrary
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reference_images')
            ->useDisk('media')
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/heic', 'image/heif', 'image/gif', 'image/svg+xml']);
    }
}

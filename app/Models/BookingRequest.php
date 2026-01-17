<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BookingRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'client_id',
        'tattooer_id',

        // Infos projet
        'tattoo_size',
        'body_zone',
        'description',
        'estimated_budget',

        // Préférences date
        'preferred_timeframe',
        'preferred_days',
        'date_notes',
        'preferred_date',
        'preferred_time_slot',

        // Montants
        'total_deposit_amount',
        'estimated_total_price',

        // Délais
        'client_payment_deadline_days',
        'tattooer_design_deadline_days',
        'client_payment_deadline',
        'tattooer_design_deadline',
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
        'total_price',
        'deposit_rate',
        'deposit_deadline_hours',
        'accepted_at',
    ];

    protected $casts = [
        'estimated_budget' => 'decimal:2',
        'total_deposit_amount' => 'decimal:2',
        'estimated_total_price' => 'decimal:2',
        'preferred_days' => 'array',
        'client_payment_deadline' => 'datetime',
        'tattooer_design_deadline' => 'datetime',
        'design_sent_at' => 'datetime',
        'design_preparation_starts_at' => 'datetime',
        'appointment_datetime' => 'datetime',
        'is_long_term_booking' => 'boolean',
        'design_preparation_notified' => 'boolean',
        'tattooer_missed_deadline' => 'boolean',
        'client_missed_deadline' => 'boolean',
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

    public function tattooer(): BelongsTo
    {
        return $this->belongsTo(Tattooer::class);
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
        return $query->where('tattooer_id', $tattooerId);
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
        $designDeadline = $this->tattooer->default_tattooer_design_deadline_days ?? 7;

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
            $this->tattooer->user_id => ['role' => 'tattooer'],
        ]);
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
            'tattooer_id' => $this->tattooer_id,
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
}

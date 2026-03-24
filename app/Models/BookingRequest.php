<?php

namespace App\Models;

use App\Enums\BookingRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

use App\Models\Consent;

class BookingRequest extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    /**
     * Vérifie si la demande est terminée
     */
    public function isCompleted(): bool
    {
        $status = $this->status instanceof \BackedEnum ? $this->status->value : $this->status;
        return $status === 'completed';
    }

    /**
     * Vérifie si la demande est en no-show
     */
    public function isNoShow(): bool
    {
        $status = $this->status instanceof \BackedEnum ? $this->status->value : $this->status;
        return $status === 'no_show';
    }

    // ... existing code ...

    public function consent(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Consent::class, 'bookable_id', 'id')
            ->where('bookable_type', $this->getMorphClass());
    }

    protected $fillable = [
        'client_id',
        'bookable_id',
        'bookable_type',

        // Infos projet
        'tattoo_size',
        'body_zone',
        'tattoo_style',
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

        // === 🎯 CHAMPS ACCEPTANCE TATTOOER ===
        'price_estimate_min',
        'price_estimate_max',
        'deposit_amount',
        'deposit_deadline_hours',
        'included_designs',
        'modifications_per_design',
        'proposed_dates',
        'confirmed_date',
        'confirmed_period',
        'tattooer_acceptance_message',

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

        // === 🎯 COMPTEURS SUIVI ===
        'designs_sent_count',
        'current_design_modifications_count',
        'design_modifications_tracker',

        // === 💰 GESTION SURPLUS ===
        'overage_decision',
        'surcharge_amount',
        'surcharge_paid_at',
        'overage_reason',

        // === 📢 NOTIFICATIONS ===
        'viewed_by_artist_at',

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
        'refund_percent',
        'refund_processed_at',

        // === ⚖️ CONTESTATION ===
        'dispute_status',
        'dispute_opened_at',
        'dispute_reason',
        'dispute_resolved_at',
        'dispute_resolution',
        'dispute_refund_amount',

        // === 📅 SÉLECTION DATES CLIENT ===
        'client_selected_dates',
        'date_selection_deadline',
        'client_dates_selected_at',

        // === 💰 PAIEMENT DU SOLDE ===
        'balance_amount',
        'balance_paid_at',
        'balance_payment_method',
        'balance_stripe_session_id',
        'balance_requested_at',
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

        // === 🎯 CHAMPS ACCEPTANCE TATTOOER ===
        'price_estimate_min' => 'decimal:2',
        'price_estimate_max' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'deposit_deadline_hours' => 'integer',
        'included_designs' => 'integer',
        'modifications_per_design' => 'integer',
        'proposed_dates' => 'array',
        'confirmed_date' => 'date',
        'confirmed_final_price' => 'decimal:2',
        'final_price_confirmed' => 'boolean',
        'final_price_confirmed_at' => 'datetime',

        // === 🎯 COMPTEURS SUIVI ===
        'designs_sent_count' => 'integer',
        'current_design_modifications_count' => 'integer',
        'design_modifications_tracker' => 'array',

        // === 💰 GESTION SURPLUS ===
        'overage_decision' => 'string',
        'surcharge_amount' => 'decimal:2',
        'surcharge_paid_at' => 'datetime',
        'overage_reason' => 'string',

        // === 📅 SÉLECTION DATES CLIENT ===
        'client_selected_dates' => 'array',
        'date_selection_deadline' => 'datetime',
        'client_dates_selected_at' => 'datetime',

        // === 💰 PAIEMENT DU SOLDE ===
        'balance_amount' => 'decimal:2',
        'balance_paid_at' => 'datetime',
        'balance_payment_method' => 'string',
        'balance_requested_at' => 'datetime',

        // === 🎯 AUTRES CHAMPS ===
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'deposit_covers_description' => 'boolean',
        'chat_closes_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refund_amount' => 'decimal:2',
        'refund_percent' => 'integer',
        'refund_processed_at' => 'datetime',
        'dispute_opened_at' => 'datetime',
        'dispute_resolved_at' => 'datetime',
        'dispute_refund_amount' => 'decimal:2',
        'deposit_paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'accepted_at' => 'datetime',

        // === 🎯 CAST VERS ENUM ===
        'status' => BookingRequestStatus::class,
    ];

    /**
     * Get the reviews for this booking request.
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    // ===== MÉTHODES STATUT (UTILISE L'ENUM) =====

    /**
     * Transition vers un nouveau statut avec validation
     */
    public function transitionTo(BookingRequestStatus $status): bool
    {
        if (!$this->status->canTransitionTo($status)) {
            return false;
        }

        $this->status = $status;
        return $this->save();
    }

    /**
     * Obtenir le statut actuel en tant qu'enum
     */
    public function getStatusEnum(): BookingRequestStatus
    {
        return $this->status;
    }

    /**
     * Vérifier si le statut actuel peut transitionner vers un autre
     */
    public function canTransitionTo(BookingRequestStatus $status): bool
    {
        return $this->status->canTransitionTo($status);
    }

    /**
     * Obtenir les transitions possibles depuis le statut actuel
     */
    public function getPossibleTransitions(): array
    {
        return $this->status->getPossibleTransitions();
    }

    /**
     * Vérifier si le statut est terminal
     */
    public function isStatusTerminal(): bool
    {
        return $this->status->isTerminal();
    }

    /**
     * Vérifier si le statut est actif
     */
    public function isStatusActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * Vérifier si le paiement d'acompte est permis
     */
    public function allowsDepositPayment(): bool
    {
        return $this->status->allowsDepositPayment();
    }

    /**
     * Vérifier si l'envoi de designs est permis
     */
    public function allowsDesignSending(): bool
    {
        return $this->status->allowsDesignSending();
    }

    /**
     * Vérifier si la confirmation de date est permise
     */
    public function allowsDateConfirmation(): bool
    {
        return $this->status->allowsDateConfirmation();
    }

    // ===== MÉTHODES RÉTROCOMPATIBILITÉ (CONSTANTES) =====

    // Garder pour rétrocompatibilité mais déprécié
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_AWAITING_DEPOSIT = 'awaiting_deposit';
    const STATUS_DEPOSIT_PAID = 'deposit_paid';
    const STATUS_DESIGN_SENT = 'design_sent';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';
    const STATUS_CANCELLED = 'cancelled';

    // ===========================================
    // MÉTHODES GESTION DATES
    // ===========================================

    /**
     * Confirmer une date de rendez-vous
     */
    public function confirmDate(string $date, string $period, ?string $time = null, ?int $durationMinutes = null): void
    {
        $action = new \App\Actions\ConfirmAppointmentDate();
        $action->execute($this, $date, $period, $time, $durationMinutes);
    }

    /**
     * Demander une date alternative
     */
    public function requestAlternativeDate(string $message): void
    {
        $action = new \App\Actions\RequestAlternativeDate();
        $action->execute($this, $message);
    }

    /**
     * Proposer de nouvelles dates
     */
    public function proposeNewDates(array $newDates): void
    {
        $action = new \App\Actions\ConfirmAppointmentDate();
        $action->proposeNewDates($this, $newDates);
    }

    /**
     * Vérifier si la date est dans les dates proposées
     */
    public function isProposedDate(string $date, string $period): bool
    {
        $action = new \App\Actions\ConfirmAppointmentDate();
        return $action->isProposedDateValid($this, $date, $period);
    }

    /**
     * Obtenir les dates proposées formatées
     */
    public function getFormattedProposedDates(): array
    {
        $action = new \App\Actions\ConfirmAppointmentDate();
        return $action->getFormattedProposedDates($this);
    }

    /**
     * Obtenir le statut de gestion des dates
     */
    public function getDateManagementStatus(): array
    {
        $action = new \App\Actions\RequestAlternativeDate();
        return $action->getDateManagementStatus($this);
    }

    /**
     * Vérifier si le client peut demander plus de dates
     */
    public function canRequestMoreDates(): bool
    {
        $action = new \App\Actions\RequestAlternativeDate();
        return $action->canRequestMoreDates($this);
    }

    /**
     * Obtenir le titre formaté pour le calendrier
     */
    public function getCalendarTitle(): string
    {
        $clientName = $this->client ? $this->client->full_name : 'Client';
        $size = $this->tattoo_size ?? 'Tattoo';

        return "Tattoo - {$clientName} - {$size}";
    }

    /**
     * Obtenir les données pour le calendrier FullCalendar
     */
    public function getCalendarEvent(): array
    {
        if (!$this->appointment_datetime) {
            return [];
        }

        return [
            'id' => $this->id,
            'title' => $this->getCalendarTitle(),
            'start' => $this->appointment_datetime,
            'end' => $this->appointment_datetime->addMinutes($this->appointment_duration_minutes ?? 120),
            'extendedProps' => [
                'booking_request_id' => $this->id,
                'client_id' => $this->client_id,
                'client_name' => $this->client ? $this->client->full_name : null,
                'tattoo_size' => $this->tattoo_size,
                'body_zone' => $this->body_zone,
                'status' => $this->status->value,
                'duration' => $this->appointment_duration_minutes ?? 120,
                'deposit_paid' => $this->deposit_paid_at ? true : false,
            ],
            'backgroundColor' => $this->status->color(),
            'borderColor' => $this->status->color(),
        ];
    }

    /**
     * Vérifier si le rendez-vous est dans le futur
     */
    public function isAppointmentInFuture(): bool
    {
        return $this->appointment_datetime && Carbon::parse($this->appointment_datetime)->isFuture();
    }

    /**
     * Vérifier si le rendez-vous est aujourd'hui
     */
    public function isAppointmentToday(): bool
    {
        return $this->appointment_datetime && Carbon::parse($this->appointment_datetime)->isToday();
    }

    /**
     * Obtenir le temps restant avant le rendez-vous
     */
    public function getTimeUntilAppointment(): ?string
    {
        if (!$this->appointment_datetime) {
            return null;
        }

        $appointment = Carbon::parse($this->appointment_datetime);
        $now = Carbon::now();

        if ($appointment->isPast()) {
            return 'Terminé';
        }

        if ($appointment->isToday()) {
            $hours = $appointment->diffInHours($now);
            if ($hours < 1) {
                $minutes = $appointment->diffInMinutes($now);
                return "Dans {$minutes} minute(s)";
            }
            return "Dans {$hours} heure(s)";
        }

        $days = $appointment->diffInDays($now);
        if ($days === 1) {
            return 'Demain';
        }

        return "Dans {$days} jours";
    }

    // ===========================================
    // RELATIONS
    // ===========================================

    /**
     * Relation avec les transactions comptables
     */
    public function accountingTransactions()
    {
        return $this->hasMany(AccountingTransaction::class);
    }

    // ===========================================
    // MÉTHODES GESTION SURPLUS
    // ===========================================

    /**
     * Vérifier si une décision de surplus est nécessaire
     */
    public function needsOverageDecision(): bool
    {
        return $this->designs_sent_count >= $this->included_designs
               && !$this->overage_decision;
    }

    /**
     * Obtenir le nombre de designs en surplus
     */
    public function getOverageDesignsCount(): int
    {
        return max(0, $this->designs_sent_count - $this->included_designs);
    }

    /**
     * Vérifier si le surplus a été payé
     */
    public function isOveragePaid(): bool
    {
        return $this->overage_decision === 'surcharge' && $this->surcharge_paid_at !== null;
    }

    /**
     * Peut continuer avec des designs supplémentaires
     */
    public function canContinueWithOverage(): bool
    {
        return in_array($this->overage_decision, ['free']) ||
               ($this->overage_decision === 'surcharge' && $this->isOveragePaid());
    }

    /**
     * Obtenir le statut des designs
     */
    public function getDesignStatus(): array
    {
        return [
            'sent' => $this->designs_sent_count,
            'included' => $this->included_designs,
            'remaining' => max(0, $this->included_designs - $this->designs_sent_count),
            'overage' => $this->getOverageDesignsCount(),
            'needs_decision' => $this->needsOverageDecision(),
            'can_continue' => $this->canContinueWithOverage(),
        ];
    }

    /**
     * Obtenir le statut des modifications
     */
    public function getModificationStatus(): array
    {
        return [
            'used' => $this->current_design_modifications_count,
            'max' => $this->modifications_per_design,
            'remaining' => max(0, $this->modifications_per_design - $this->current_design_modifications_count),
            'is_limit_reached' => $this->current_design_modifications_count >= $this->modifications_per_design,
        ];
    }

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

    public function bookingTransactions(): HasMany
    {
        return $this->hasMany(BookingTransaction::class);
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
        return $query->where('status', BookingRequestStatus::PENDING);
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', BookingRequestStatus::ACCEPTED);
    }

    public function scopeAwaitingDeposit($query)
    {
        return $query->where('status', BookingRequestStatus::DEPOSIT_REQUESTED);
    }

    public function scopeDepositPaid($query)
    {
        return $query->where('status', BookingRequestStatus::DEPOSIT_PAID);
    }

    public function scopeDateConfirmed($query)
    {
        return $query->where('status', BookingRequestStatus::DATE_CONFIRMED);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', BookingRequestStatus::COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', BookingRequestStatus::CANCELLED);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', BookingRequestStatus::EXPIRED);
    }

    public function scopeNoShow($query)
    {
        return $query->where('status', BookingRequestStatus::NO_SHOW);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            BookingRequestStatus::ACCEPTED,
            BookingRequestStatus::DEPOSIT_REQUESTED,
            BookingRequestStatus::DEPOSIT_PAID,
            BookingRequestStatus::DATE_CONFIRMED
        ]);
    }

    public function scopeTerminal($query)
    {
        return $query->whereIn('status', [
            BookingRequestStatus::COMPLETED,
            BookingRequestStatus::CANCELLED,
            BookingRequestStatus::EXPIRED,
            BookingRequestStatus::NO_SHOW
        ]);
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
        $this->transitionTo(BookingRequestStatus::ACCEPTED);

        // Créer la conversation
        $this->createConversation();

        event(new \App\Events\BookingRequestAccepted($this));
    }

    /**
     * Rejeter la demande
     */
    public function reject(): void
    {
        $this->transitionTo(BookingRequestStatus::CANCELLED);

        event(new \App\Events\BookingRequestRejected($this));
    }

    /**
     * Marquer comme en attente d'acompte
     */
    public function requestDeposit(float $amount, int $deadlineDays): void
    {
        $this->update([
            'total_deposit_amount' => $amount,
            'client_payment_deadline_days' => $deadlineDays,
            'client_payment_deadline' => now()->addDays($deadlineDays),
        ]);

        $this->transitionTo(BookingRequestStatus::DEPOSIT_REQUESTED);

        event(new \App\Events\DepositRequested($this));
    }

    /**
     * Marquer l'acompte comme payé
     */
    public function markDepositPaid(string $paymentIntentId): void
    {
        $designDeadline = $this->bookable->default_tattooer_design_deadline_days ?? 7;

        $this->update([
            'stripe_payment_intent_id' => $paymentIntentId,
            'tattooer_design_deadline_days' => $designDeadline,
            'tattooer_design_deadline' => now()->addDays($designDeadline),
        ]);

        $this->transitionTo(BookingRequestStatus::DEPOSIT_PAID);

        event(new \App\Events\DepositPaid($this));
    }

    /**
     * Envoyer le design
     */
    public function sendDesign(): void
    {
        $this->update([
            'design_sent_at' => now(),
            'design_versions_used' => $this->design_versions_used + 1,
        ]);

        $this->transitionTo(BookingRequestStatus::DATE_CONFIRMED);

        event(new \App\Events\DesignSent($this));
    }

    /**
     * Confirmer le RDV
     */
    public function confirm(\DateTime $appointmentDate, int $durationMinutes): void
    {
        $this->update([
            'appointment_datetime' => $appointmentDate,
            'appointment_duration_minutes' => $durationMinutes,
        ]);

        $this->transitionTo(BookingRequestStatus::DATE_CONFIRMED);

        // Créer l'appointment
        $this->createAppointment();

        event(new \App\Events\BookingConfirmed($this));
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
    }

    /**
     * Confirmer le prix final
     */
    public function confirmFinalPrice(float $finalPrice): void
    {
        $this->update([
            'final_price' => $finalPrice,
            'price_confirmed_at' => now(),
        ]);
    }

    /**
     * Marquer la demande comme vue par l'artiste
     */
    public function markAsViewedByArtist(): void
    {
        $this->update([
            'viewed_by_artist_at' => now(),
        ]);
    }

    /**
     * Annuler la demande
     */
    public function cancel(string $cancelledBy, string $reason, float $refundAmount = 0): void
    {
        $this->update([
            'cancelled_by' => $cancelledBy,
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
            'refund_amount' => $refundAmount,
        ]);

        $this->transitionTo(BookingRequestStatus::CANCELLED);

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
     * Obtenir le pourcentage de remboursement selon les règles
     */
    public function getRefundPercentage(): int
    {
        return match(true) {
            $this->designs_sent_count === 0 => 100,
            $this->designs_sent_count === 1 => 80,
            $this->designs_sent_count === 2 => 50,
            default => 0, // 3+ dessins = pas de remboursement
        };
    }

    // ═══════════════════════════════════════
    // DESIGN TRACKING HELPERS
    // ═══════════════════════════════════════

    /**
     * Dessins complets restants dans le forfait.
     */
    public function remainingDesigns(): int
    {
        return max(0, (int) $this->included_design_versions - (int) $this->designs_sent_count);
    }

    /**
     * Modifications restantes pour le dessin en cours (le dernier envoyé).
     */
    public function remainingModificationsForCurrentDesign(): int
    {
        if ($this->designs_sent_count === 0) {
            return 0;
        }

        $tracker = $this->design_modifications_tracker ?? [];
        $currentDesignKey = (string) $this->designs_sent_count;
        $modifsUsed = $tracker[$currentDesignKey] ?? 0;

        return max(0, (int) $this->modifications_per_design - (int) $modifsUsed);
    }

    /**
     * Le tattooer peut-il envoyer un nouveau dessin dans le forfait ?
     */
    public function canSendNewDesign(): bool
    {
        return $this->remainingDesigns() > 0;
    }

    /**
     * Le tattooer peut-il envoyer une modification du dessin actuel dans le forfait ?
     */
    public function canSendModification(): bool
    {
        return $this->designs_sent_count > 0
            && $this->remainingModificationsForCurrentDesign() > 0;
    }

    /**
     * Enregistrer l'envoi d'un nouveau dessin complet.
     */
    public function recordNewDesign(): void
    {
        $this->increment('designs_sent_count');
        $this->refresh(); // Pour avoir la valeur mise à jour

        $tracker = $this->design_modifications_tracker ?? [];
        $tracker[(string) $this->designs_sent_count] = 0;
        $this->update(['design_modifications_tracker' => $tracker]);
    }

    /**
     * Enregistrer l'envoi d'une modification du dessin actuel.
     */
    public function recordModification(): void
    {
        $tracker = $this->design_modifications_tracker ?? [];
        $currentDesignKey = (string) $this->designs_sent_count;
        $tracker[$currentDesignKey] = ($tracker[$currentDesignKey] ?? 0) + 1;
        $this->update(['design_modifications_tracker' => $tracker]);
    }

    /**
     * Résumé complet du suivi dessins pour affichage.
     */
    public function designTrackingSummary(): array
    {
        $tracker = $this->design_modifications_tracker ?? [];
        $currentKey = (string) $this->designs_sent_count;

        return [
            'designs_included'              => (int) $this->included_design_versions,
            'designs_sent'                  => (int) $this->designs_sent_count,
            'designs_remaining'             => $this->remainingDesigns(),
            'current_design_number'         => (int) $this->designs_sent_count,
            'modifications_per_design'      => (int) $this->modifications_per_design,
            'modifications_used_current'    => (int) ($tracker[$currentKey] ?? 0),
            'modifications_remaining_current'=> $this->remainingModificationsForCurrentDesign(),
            'can_send_new_design'           => $this->canSendNewDesign(),
            'can_send_modification'         => $this->canSendModification(),
        ];
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
        return $this->status === BookingRequestStatus::DEPOSIT_REQUESTED
            && $this->client_payment_deadline
            && $this->client_payment_deadline->isPast();
    }

    /**
     * Vérifier si la deadline d'acompte est dépassée
     */
    public function isDepositExpired(): bool
    {
        return $this->status === BookingRequestStatus::ACCEPTED
            && $this->deposit_deadline
            && $this->deposit_deadline->isPast()
            && !$this->deposit_paid_at;
    }

    /**
     * Vérifier si la deadline de design est dépassée
     */
    public function isDesignOverdue(): bool
    {
        return $this->status === BookingRequestStatus::DEPOSIT_PAID
            && $this->tattooer_design_deadline
            && $this->tattooer_design_deadline->isPast()
            && !$this->design_sent_at;
    }

    /**
     * Calculer le solde restant à payer (utilise confirmed_final_price si disponible).
     */
    public function getBalanceRemainingAttribute(): float
    {
        $basePrice = $this->confirmed_final_price ?? $this->total_price ?? 0;
        if (!$basePrice || !$this->total_deposit_amount) {
            return 0;
        }
        $paid = $this->balance_amount ?? 0;
        return max(0, (float) $basePrice - (float) $this->total_deposit_amount - (float) $paid);
    }

    /**
     * Le solde a-t-il été demandé (et pas encore payé) ?
     */
    public function isBalanceRequested(): bool
    {
        return $this->balance_requested_at !== null && $this->balance_paid_at === null;
    }

    /**
     * Le solde est-il payé ?
     */
    public function isBalancePaid(): bool
    {
        return $this->balance_paid_at !== null;
    }

    /**
     * Configuration des médias pour Spatie MediaLibrary
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reference_images')
            ->useDisk('media')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('tattoo_results')
            ->useDisk('media')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);
    }
}

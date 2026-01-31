<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'client_id', 'bookable_id', 'bookable_type', 'status', 'tattoo_description',
        'tattoo_location', 'tattoo_style', 'estimated_duration', 'estimated_price',
        'deposit_amount', 'deposit_paid_at', 'deposit_stripe_payment_id',
        'final_price', 'final_paid_at', 'final_stripe_payment_id', 'payment_method',
        'proposed_date', 'appointment_date', 'appointment_end',
        'accepted_at', 'deposit_requested_at', 'appointment_confirmed_at',
        'completed_at', 'cancelled_at', 'cancellation_reason', 'refund_issued',
        'archived_at'
    ];

    protected $casts = [
        'proposed_date' => 'datetime',
        'appointment_date' => 'datetime',
        'appointment_end' => 'datetime',
        'deposit_paid_at' => 'datetime',
        'final_paid_at' => 'datetime',
        'accepted_at' => 'datetime',
        'deposit_requested_at' => 'datetime',
        'appointment_confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'archived_at' => 'datetime',
        'refund_issued' => 'boolean',
        'estimated_price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    // ===== CONSTANTES DE STATUT =====

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_NO_SHOW = 'no_show';

    const STATUSES = [
        self::STATUS_PENDING => 'En attente',
        self::STATUS_ACCEPTED => 'Accepté',
        self::STATUS_IN_PROGRESS => 'En cours',
        self::STATUS_COMPLETED => 'Terminé',
        self::STATUS_CANCELLED => 'Annulé',
        self::STATUS_NO_SHOW => 'Non présenté',
    ];

    // ===== RELATIONS =====

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    // Helper rétrocompatibilité
    public function getTattooerAttribute()
    {
        return $this->bookable_type === 'App\\Models\\Tattooer' ? $this->bookable : null;
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function bookingRequest(): HasOne
    {
        return $this->hasOne(BookingRequest::class);
    }

    public function consent(): HasOne
    {
        return $this->hasOne(Consent::class);
    }

    public function calendarEvent(): HasOne
    {
        return $this->hasOne(CalendarEvent::class);
    }

    public function tattooHistory(): HasOne
    {
        return $this->hasOne(TattooHistory::class);
    }

    // ===== SPATIE MEDIA COLLECTIONS =====

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('reference_images')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxFilesize(10 * 1024 * 1024); // 10MB

        $this->addMediaCollection('approved_design')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp', 'application/pdf'])
            ->maxFilesize(10 * 1024 * 1024);

        $this->addMediaCollection('final_photos')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxFilesize(10 * 1024 * 1024);

        $this->addMediaCollection('chat_archive')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->maxFilesize(10 * 1024 * 1024);
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

    public function scopeInProgress($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeForBookable($query, $bookableId, $bookableType)
    {
        return $query->where('bookable_id', $bookableId)
                   ->where('bookable_type', $bookableType);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereIn('status', [self::STATUS_ACCEPTED, self::STATUS_IN_PROGRESS])
                    ->whereNotNull('appointment_date')
                    ->where('appointment_date', '>=', now());
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si l'acompte a été payé
     */
    public function isDepositPaid(): bool
    {
        return !is_null($this->deposit_paid_at);
    }

    /**
     * Vérifie si le solde a été payé
     */
    public function isFinalPaid(): bool
    {
        return !is_null($this->final_paid_at);
    }

    /**
     * Vérifie si le projet peut être annulé
     */
    public function canBeCancelled(): bool
    {
        if (!$this->appointment_date) return true;
        return $this->appointment_date->diffInDays(now()) >= 7;
    }

    /**
     * Vérifie si le projet doit être archivé
     */
    public function shouldBeArchived(): bool
    {
        if (!$this->appointment_date) return false;
        return now()->isAfter($this->appointment_date->addDay()) &&
               $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Accepter le projet
     */
    public function accept(): void
    {
        $this->update([
            'status' => self::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);
    }

    /**
     * Demander un acompte
     */
    public function requestDeposit(float $amount): void
    {
        $this->update([
            'deposit_amount' => $amount,
            'deposit_requested_at' => now(),
        ]);
    }

    /**
     * Marquer l'acompte comme payé
     */
    public function markDepositPaid(string $paymentIntentId): void
    {
        $this->update([
            'deposit_paid_at' => now(),
            'deposit_stripe_payment_id' => $paymentIntentId,
            'status' => self::STATUS_IN_PROGRESS,
        ]);
    }

    /**
     * Confirmer le rendez-vous
     */
    public function confirmAppointment(\DateTime $appointmentDate, int $durationMinutes): void
    {
        $this->update([
            'appointment_date' => $appointmentDate,
            'appointment_end' => (new \DateTime($appointmentDate->format('Y-m-d H:i:s')))->modify("+{$durationMinutes} minutes"),
            'appointment_confirmed_at' => now(),
        ]);

        // Créer l'événement calendrier
        CalendarEvent::create([
            'bookable_id' => $this->bookable_id,
            'bookable_type' => $this->bookable_type,
            'type' => 'appointment',
            'project_id' => $this->id,
            'start_datetime' => $appointmentDate,
            'end_datetime' => (new \DateTime($appointmentDate->format('Y-m-d H:i:s')))->modify("+{$durationMinutes} minutes"),
            'color' => '#06D6A0', // Vert succès
        ]);
    }

    /**
     * Compléter le projet
     */
    public function complete(float $finalPrice, string $paymentMethod): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'completed_at' => now(),
            'final_price' => $finalPrice,
            'payment_method' => $paymentMethod,
        ]);

        // Créer l'historique
        TattooHistory::create([
            'client_id' => $this->client_id,
            'bookable_id' => $this->bookable_id,
            'bookable_type' => $this->bookable_type,
            'project_id' => $this->id,
            'tattoo_date' => $this->appointment_date,
            'body_location' => $this->tattoo_location,
            'description' => $this->tattoo_description,
            'duration' => $this->estimated_duration ?? 0,
            'total_paid' => $finalPrice,
            'payment_method' => $paymentMethod,
        ]);
    }

    /**
     * Annuler le projet
     */
    public function cancel(string $reason, bool $refundIssued = false): void
    {
        $this->update([
            'status' => self::STATUS_CANCELLED,
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
            'refund_issued' => $refundIssued,
        ]);

        // Supprimer l'événement calendrier
        if ($this->calendarEvent) {
            $this->calendarEvent->delete();
        }
    }

    /**
     * Archiver le projet
     */
    public function archive(): void
    {
        $this->update([
            'archived_at' => now(),
        ]);
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatusFormattedAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    /**
     * Calculer le montant restant dû
     */
    public function getRemainingAmountAttribute(): float
    {
        $total = $this->final_price ?? $this->estimated_price ?? 0;
        $deposit = $this->deposit_amount ?? 0;

        return max(0, $total - $deposit);
    }

    /**
     * Obtenir la progression du projet en pourcentage
     */
    public function getProgressPercentageAttribute(): int
    {
        return match($this->status) {
            self::STATUS_PENDING => 10,
            self::STATUS_ACCEPTED => 25,
            self::STATUS_IN_PROGRESS => $this->isDepositPaid() ? 60 : 40,
            self::STATUS_COMPLETED => 100,
            default => 0,
        };
    }
}

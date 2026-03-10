<?php

namespace App\Models;

use App\Enums\ConversationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Conversation extends Model
{
    use HasFactory;

    // ===========================================
    // CONSTANTES EXPIRATION
    // ===========================================

    // Garder pour rétrocompatibilité mais déprécié
    const EXPIRY_DEPOSIT_PENDING = 'deposit_pending';
    const EXPIRY_PERMANENT = 'permanent';
    const EXPIRY_POST_APPOINTMENT = 'post_appointment';
    const EXPIRY_ARCHIVED = 'archived';

    const EXPIRY_WARNING_DAYS = 2; // Alerte J-2
    const POST_APPOINTMENT_DAYS_FREE = 0; // Suppression immédiate pour FREE
    const POST_APPOINTMENT_DAYS_PRO = null; // Jamais supprimé pour PRO

    protected $fillable = [
        'booking_request_id',
        'subject',
        'status',
        'last_message_at',
        'last_message_id',
        // Champs expiration
        'expiry_type',
        'deposit_deadline_at',
        'appointment_completed_at',
        'expires_at',
        'archived_at',
        'is_expired',
        'images_preserved',
        'expiry_warning_sent_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'deposit_deadline_at' => 'datetime',
        'appointment_completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'archived_at' => 'datetime',
        'is_expired' => 'boolean',
        'images_preserved' => 'boolean',
        'expiry_warning_sent_at' => 'datetime',

        // === 🎯 CAST VERS ENUM ===
        'status' => ConversationStatus::class,
    ];

    // Relation avec les messages
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // Dernier message
    public function lastMessage(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    // Participants à la conversation
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_user')
            ->withPivot(['role', 'last_read_at', 'is_muted'])
            ->withTimestamps();
    }

    // Relation avec la demande de réservation
    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    // ===========================================
    // MÉTHODES STATUT (UTILISE L'ENUM)
    // ===========================================

    /**
     * Transition vers un nouveau statut avec validation
     */
    public function transitionTo(ConversationStatus $status): bool
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
    public function getStatusEnum(): ConversationStatus
    {
        return $this->status;
    }

    /**
     * Vérifier si le statut actuel peut transitionner vers un autre
     */
    public function canTransitionTo(ConversationStatus $status): bool
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
     * Vérifier si le statut est actif (discussion possible)
     */
    public function isStatusActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * Vérifier si l'envoi de messages est permis
     */
    public function allowsStatusMessaging(): bool
    {
        return $this->status->allowsMessaging();
    }

    /**
     * Vérifier si l'envoi d'images est permis
     */
    public function allowsStatusImages(): bool
    {
        return $this->status->allowsImages();
    }

    /**
     * Vérifier si la conversation est en lecture seule
     */
    public function isStatusReadOnly(): bool
    {
        return $this->status->isReadOnly();
    }

    /**
     * Vérifier si la conversation est fermée
     */
    public function isStatusClosed(): bool
    {
        return $this->status->isClosed();
    }

    /**
     * Vérifier si la conversation peut être archivée
     */
    public function canBeStatusArchived(): bool
    {
        return $this->status->canBeArchived();
    }

    /**
     * Vérifier si la conversation peut être supprimée
     */
    public function canBeStatusDeleted(): bool
    {
        return $this->status->canBeDeleted();
    }

    // ===========================================
    // SCOPES
    // ===========================================

    public function scopeExpired($query)
    {
        return $query->where('is_expired', true);
    }

    public function scopeExpiring($query)
    {
        return $query->where('is_expired', false)
            ->whereNotNull('expires_at')
            ->where('expires_at', '<=', now());
    }

    public function scopeDepositPending($query)
    {
        return $query->where('expiry_type', self::EXPIRY_DEPOSIT_PENDING);
    }

    public function scopePermanent($query)
    {
        return $query->where('expiry_type', self::EXPIRY_PERMANENT);
    }

    public function scopePostAppointment($query)
    {
        return $query->where('expiry_type', self::EXPIRY_POST_APPOINTMENT);
    }

    public function scopeArchived($query)
    {
        return $query->where('expiry_type', self::EXPIRY_ARCHIVED);
    }

    // ===========================================
    // MÉTHODES CYCLE DE VIE
    // ===========================================

    /**
     * Phase 1 : Initialiser chat après validation projet
     */
    public function initializeDepositPendingPhase(int $depositDelayDays = 7): void
    {
        $this->update([
            'expiry_type' => self::EXPIRY_DEPOSIT_PENDING,
            'deposit_deadline_at' => now()->addDays($depositDelayDays),
            'expires_at' => now()->addDays($depositDelayDays), // ⭐ CRITICAL : Initialiser expires_at
            'is_expired' => false,
        ]);
    }

    /**
     * Phase 2 : Transition après paiement acompte
     */
    public function transitionToPermanentPhase(): void
    {
        $this->update([
            'expiry_type' => self::EXPIRY_PERMANENT,
            'deposit_deadline_at' => null,
            'expires_at' => null,
            'is_expired' => false,
        ]);
    }

    /**
     * Phase 3 : Après RDV terminé
     */
    public function transitionToPostAppointmentPhase(): void
    {
        $booking = $this->bookingRequest;
        $artist = $booking?->bookable;

        // Plan FREE → Suppression immédiate
        if ($artist && $artist->isOnFreePlan()) {
            $this->update([
                'expiry_type' => self::EXPIRY_POST_APPOINTMENT,
                'appointment_completed_at' => now(),
                'expires_at' => now(), // Suppression immédiate
                'images_preserved' => false,
            ]);
        }
        // Plan PRO → Archivage permanent
        else {
            $this->archive();
        }
    }

    /**
     * Archiver conversation (plan PRO uniquement)
     */
    public function archive(): void
    {
        $this->update([
            'expiry_type' => self::EXPIRY_ARCHIVED,
            'appointment_completed_at' => now(),
            'archived_at' => now(),
            'expires_at' => null, // Jamais supprimé
            'is_expired' => false,
            'images_preserved' => true,
        ]);
    }

    // ===========================================
    // CHECKS STATUT
    // ===========================================

    public function isExpired(): bool
    {
        return (bool) $this->is_expired;
    }

    public function isClosed(): bool
    {
        return $this->status->isClosed();
    }

    public function isDepositPending(): bool
    {
        return $this->expiry_type === self::EXPIRY_DEPOSIT_PENDING;
    }

    public function isPermanent(): bool
    {
        return $this->expiry_type === self::EXPIRY_PERMANENT;
    }

    public function isPostAppointment(): bool
    {
        return $this->expiry_type === self::EXPIRY_POST_APPOINTMENT;
    }

    public function isArchived(): bool
    {
        return $this->expiry_type === self::EXPIRY_ARCHIVED;
    }

    public function shouldExpire(): bool
    {
        if ($this->is_expired || !$this->expires_at) {
            return false;
        }

        return now()->greaterThanOrEqualTo($this->expires_at);
    }

    public function shouldSendExpiryWarning(): bool
    {
        if ($this->expiry_warning_sent_at || !$this->expires_at) {
            return false;
        }

        $warningDate = $this->expires_at->subDays(self::EXPIRY_WARNING_DAYS);

        return now()->greaterThanOrEqualTo($warningDate);
    }

    /**
     * Calculer le nombre de messages non lus pour le client
     */
    public function getUnreadCountAttribute(): int
    {
        return $this->messages()
            ->where('sender_type', '!=', 'client')
            ->whereNull('read_by_client_at')
            ->count();
    }

    /**
     * Calculer le nombre de messages non lus pour le tattooer
     */
    public function getUnreadCountForTattooerAttribute(): int
    {
        return $this->messages()
            ->where('sender_type', 'client')
            ->whereNull('read_by_tattooer_at')
            ->count();
    }

    // ===========================================
    // ACTIONS EXPIRATION
    // ===========================================

    /**
     * Marquer comme expirée
     */
    public function markAsExpired(): void
    {
        $this->update([
            'is_expired' => true,
        ]);
    }
    /**
     * Supprimer complètement conversation
     */
    public function deleteCompletely(): void
    {
        // Supprimer fichiers attachés
        $this->messages()->each(function ($message) {
            if ($message->hasMedia('attachments')) {
                $message->clearMediaCollection('attachments');
            }
        });

        // Supprimer messages
        $this->messages()->delete();

        // Supprimer conversation
        $this->delete();

        Log::info('Conversation supprimée complètement', [
            'conversation_id' => $this->id,
            'expiry_type' => $this->expiry_type,
        ]);
    }

    /**
     * Préserver uniquement images (plan PRO)
     */
    public function preserveImagesOnly(): void
    {
        $booking = $this->bookingRequest;

        if (!$booking) {
            return;
        }

        // Extraire images et les attacher au ClientCareSheet
        $this->messages()->where('message_type', 'image')->each(function ($message) use ($booking) {
            if ($message->hasMedia('attachments')) {
                $media = $message->getFirstMedia('attachments');

                // Créer/récupérer fiche suivi client
                $careSheet = $booking->clientCareSheet()->firstOrCreate([
                    'client_id' => $booking->client_id,
                ]);

                // Copier image vers fiche suivi
                $media->copy($careSheet, 'design_images');
            }
        });

        $this->update(['images_preserved' => true]);
    }

    // ===========================================
    // HELPERS UI
    // ===========================================

    public function getExpiryStatusBadge(): string
    {
        return match($this->expiry_type) {
            self::EXPIRY_DEPOSIT_PENDING => '<span class="badge bg-warning">⏱️ En attente acompte</span>',
            self::EXPIRY_PERMANENT => '<span class="badge bg-success">✅ Actif</span>',
            self::EXPIRY_POST_APPOINTMENT => '<span class="badge bg-secondary">📋 Post-RDV</span>',
            self::EXPIRY_ARCHIVED => '<span class="badge bg-primary">📦 Archivé</span>',
            default => '<span class="badge bg-light">❓</span>',
        };
    }

    public function getTimeUntilExpiry(): string
    {
        if (!$this->expires_at) {
            return '';
        }

        $now = now();
        $diff = $now->diff($this->expires_at);

        // Si plus de 24h, afficher les jours
        if ($diff->days > 0) {
            return "{$diff->days} jour(s)";
        }

        // Sinon afficher les heures
        return "{$diff->h} heure(s)";
    }

    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        $days = now()->diffInDays($this->expires_at, false);
        return (int) max(0, $days); // Forcer entier et éviter les nombres négatifs
    }

    public function getExpiryWarningMessage(): ?string
    {
        if ($this->isExpired()) {
            return "❌ Cette conversation a expiré et sera supprimée prochainement.";
        }

        if ($this->isDepositPending() && $this->expires_at) {
            $timeLeft = $this->getTimeUntilExpiry();
            $days = $this->getDaysUntilExpiry();

            if ($days <= 2) {
                return "⚠️ URGENT : Cette conversation sera supprimée dans {$timeLeft} si l'acompte n'est pas payé.";
            }

            return "⏱️ Cette conversation expire dans {$timeLeft} sans paiement acompte.";
        }

        return null;
    }

    // Vérifie si un utilisateur est participant
    public function hasParticipant(int $userId): bool
    {
        return $this->participants()->where('user_id', $userId)->exists();
    }

    // Marquer comme lu pour un utilisateur
    public function markAsRead(int $userId): void
    {
        $this->participants()->updateExistingPivot($userId, [
            'last_read_at' => now()
        ]);
    }
}

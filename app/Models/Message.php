<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'conversation_id',
        'booking_request_id', // ✅ Ajouté depuis migration
        'sender_id',
        'sender_type', // ✅ Ajouté depuis migration
        'content',
        'attachment_type', // ✅ Ajouté depuis migration
        'is_design_version',
        'design_version_number',
    ];

    protected $dispatchesEvents = [
        'created' => \App\Events\MessageCreated::class,
        'deleted' => \App\Events\MessageDeleted::class,
    ];

    protected $casts = [
        'is_design_version' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Configuration de MediaLibrary
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachments')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/gif',
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            ])
            ->useDisk('public'); // ✅ Changé en 'public' pour les images de design

        // Conversions d'images
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->performOnCollections('attachments');

        $this->addMediaConversion('preview')
            ->width(800)
            ->height(800)
            ->performOnCollections('attachments');
    }

    // ===== RELATIONS =====

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Vérifie si le message a été lu par un utilisateur
     */
    public function isReadBy(int $userId): bool
    {
        $participant = $this->conversation->participants()
            ->where('user_id', $userId)
            ->first();

        if (!$participant) {
            return false;
        }

        return $participant->pivot->last_read_at
            && $this->created_at->lte($participant->pivot->last_read_at);
    }

    /**
     * Vérifie si c'est une version de design
     */
    public function isDesignVersion(): bool
    {
        return $this->is_design_version === true;
    }

    /**
     * Récupère le type d'expéditeur (tattooer/client)
     */
    public function getSenderTypeAttribute(): string
    {
        // Si sender_type est déjà rempli dans la BDD
        if ($this->attributes['sender_type']) {
            return $this->attributes['sender_type'];
        }

        // Sinon on le détermine dynamiquement
        $user = $this->sender;
        return $user->isTattooer() ? 'tattooer' : 'client';
    }

    // ===== ACCESSEURS POUR LES PIÈCES JOINTES =====

    public function addAttachment($file, string $type = null)
    {
        // Mise à jour du type d'attachement
        if ($type) {
            $this->update(['attachment_type' => $type]);
        }

        return $this->addMedia($file)
            ->toMediaCollection('attachments');
    }

    public function getAttachmentUrl(string $conversion = '')
    {
        return $this->getFirstMediaUrl('attachments', $conversion);
    }

    public function hasAttachment(): bool
    {
        return $this->hasMedia('attachments');
    }

    // ===== SCOPES =====

    public function scopeDesignVersions($query)
    {
        return $query->where('is_design_version', true);
    }

    public function scopeForConversation($query, int $conversationId)
    {
        return $query->where('conversation_id', $conversationId)
            ->orderBy('created_at', 'asc');
    }
}

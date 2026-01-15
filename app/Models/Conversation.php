<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_request_id',
        'subject',
        'status',
        'last_message_at',
        'last_message_id'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
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

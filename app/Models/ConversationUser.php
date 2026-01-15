<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ConversationUser extends Pivot
{
    use HasFactory;

    protected $table = 'conversation_user';

    protected $fillable = [
        'conversation_id',
        'user_id',
        'role',
        'last_read_at',
        'is_muted',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'is_muted' => 'boolean',
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec la conversation
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // Marquer comme lu
    public function markAsRead()
    {
        $this->update(['last_read_at' => now()]);
    }

    // Marquer comme non lu
    public function markAsUnread()
    {
        $this->update(['last_read_at' => null]);
    }

    // Activer/désactiver les notifications
    public function toggleMute()
    {
        $this->update(['is_muted' => !$this->is_muted]);
        return $this->is_muted;
    }
}

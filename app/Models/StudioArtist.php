<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudioArtist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'studio_id',
        'user_id',
        'artisan_type',       // 'tattooer' ou 'piercer'
        'role',               // 'artist' ou 'manager'
        'is_active',
        'joined_at',
        'invited_at',
        'invitation_token',
        'invitation_email',
        'commission_rate',    // Override du taux de commission pour cet artiste (nullable)
    ];

    protected $casts = [
        'is_active'       => 'boolean',
        'joined_at'       => 'date',
        'invited_at'      => 'datetime',
        'commission_rate' => 'decimal:2',
    ];

    // ═══ RELATIONS ═══

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Le profil artisan (Tattooer ou Piercer) de cet artiste
     */
    public function artisanProfile()
    {
        $user = $this->user;
        if (!$user) return null;
        return $user->artisan();
    }

    // ═══ HELPERS ═══

    public function isActive(): bool
    {
        return $this->is_active && $this->user_id !== null;
    }

    public function isPending(): bool
    {
        return $this->user_id === null && $this->invitation_token !== null;
    }
}

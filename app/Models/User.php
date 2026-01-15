<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\PersonalAccessToken;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        TwoFactorAuthenticatable,
        Billable, // ✅ Stripe Cashier
        HasRoles; // ✅ Spatie Permissions

    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
        'last_login_at',
        'is_active',
        'fcm_token',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    // ===== TOKENS =====

    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    // ===== RELATIONS =====

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function tattooer()
    {
        return $this->hasOne(Tattooer::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')
            ->withPivot(['role', 'last_read_at', 'is_muted'])
            ->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // ===== MÉTHODES MÉTIER =====

    public function isClient(): bool
    {
        return $this->client()->exists();
    }

    public function isTattooer(): bool
    {
        return $this->tattooer()->exists();
    }

    public function getUserType(): ?string
    {
        if ($this->isTattooer()) return 'tattooer';
        if ($this->isClient()) return 'client';
        return null;
    }

    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    // ===== NOTIFICATIONS =====

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'users.' . $this->id;
    }

    // ===== SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTattooers($query)
    {
        return $query->whereHas('tattooer');
    }

    public function scopeClients($query)
    {
        return $query->whereHas('client');
    }
}

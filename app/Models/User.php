<?php

namespace App\Models;

use App\Models\Client;
use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Studio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    use HasApiTokens,
        HasFactory,
        Notifiable,
        TwoFactorAuthenticatable,
        Billable, // ✅ Stripe Cashier
        HasRoles, // ✅ Spatie Permissions
        InteractsWithMedia; // ✅ Spatie MediaLibrary

    protected static function booted()
    {
        parent::booted();

        // Observer pour la mise à jour du statut du tattooer
        static::updated(function ($user) {
            // Si le statut change vers 'active' et que l'utilisateur est un tattooer
            if ($user->isDirty('status') && $user->status === 'active' && $user->role === 'tattooer') {
                $tattooer = $user->tattooer;
                if ($tattooer && !$tattooer->admin_verified_at) {
                    $tattooer->admin_verified_at = now();
                    $tattooer->save();
                }
            }
        });
    }

    protected $fillable = [
        'id',
        'name',
        'pseudo', // 🆕 Pseudo public
        'email',
        'password',
        'role', // Ajout du rôle
        'status', // Ajout du status
        'timezone',
        'last_login_at',
        'is_active',
        'is_admin', // Ajout du flag admin
        'fcm_token',
        'studio_id',
        'is_studio_owner',
        'is_studio_artist',
        'first_name', // 🆕 Prénom
        'last_name', // 🆕 Nom de famille
        'phone', // 🆕 Téléphone
        'birth_date', // 🆕 Date de naissance
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
            'is_studio_owner' => 'boolean',
            'is_studio_artist' => 'boolean',
        ];
    }

    // ===== TOKENS =====

    public function tokens()
    {
        return $this->morphMany(PersonalAccessToken::class, 'tokenable');
    }

    // ===== RELATIONS =====

    /**
     * Relation polymorphique vers le profil selon le rôle
     */
    public function profile()
    {
        return match($this->role) {
            'client' => $this->hasOne(Client::class),
            'tattooer' => $this->hasOne(Tattooer::class),
            'pierceur' => $this->hasOne(Piercer::class),
            'studio' => $this->hasOne(Studio::class),
            'studio_artist' => $this->hasOne(StudioArtist::class),
            'admin' => $this->hasOne(Client::class)->where('id', 0), // Relation vide pour admin
            default => null,
        };
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function tattooer()
    {
        return $this->hasOne(Tattooer::class);
    }

    public function pierceur()
    {
        return $this->hasOne(Piercer::class);
    }

    public function studio()
    {
        return $this->belongsTo(Studio::class);
    }

    public function studioArtist()
    {
        return $this->hasOne(StudioArtist::class);
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

    /**
     * Nom affiché publiquement (pseudo ou fallback name)
     */
    public function displayName(): string
    {
        return $this->pseudo ?? $this->name;
    }

    /**
     * Nom réel (pour admin/légal uniquement)
     */
    public function realName(): string
    {
        return $this->name;
    }

    /**
     * Avatar via Spatie (polymorphic via profile)
     */
    public function getAvatarUrlAttribute(): string
    {
        $profile = $this->profile;

        if ($profile && method_exists($profile, 'hasMedia') && $profile->hasMedia('avatar')) {
            return $profile->getFirstMediaUrl('avatar');
        }

        return '/images/default-avatar.png';
    }

    /**
     * Helpers de rôle basés sur le champ role (plus performants)
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isTattooer(): bool
    {
        return $this->role === 'tattooer';
    }

    public function isPiercer(): bool
    {
        return in_array($this->role, ['pierceur', 'Piercer']);
    }

    public function isArtisan(): bool
    {
        return $this->isTattooer() || $this->isPiercer();
    }

    /**
     * Retourne le profil artisan de l'utilisateur (Tattooer ou Piercer)
     * Utiliser cette méthode dans les controllers pour le polymorphisme.
     */
    public function artisan(): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->tattooer ?? $this->pierceur;
    }

    /**
     * Retourne le type d'artisan : 'tattooer', 'piercer', ou null
     */
    public function artisanType(): ?string
    {
        if ($this->isTattooer()) return 'tattooer';
        if ($this->isPiercer()) return 'piercer';
        return null;
    }

    public function isStudio(): bool
    {
        return $this->role === 'studio';
    }

    public function isStudioArtist(): bool
    {
        return $this->role === 'studio_artist';
    }

    public function isStudioOwner(): bool
    {
        return $this->is_studio_owner && $this->studio_id;
    }

    public function getUserType(): ?string
    {
        return $this->role;
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

    /**
     * Vérifier si utilisateur peut effectuer action sur modèle
     */
    public function canAccess(string $ability, $model): bool
    {
        return $this->can($ability, $model);
    }

    // ===== ADMIN METHODS =====

    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->is_admin === true;
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

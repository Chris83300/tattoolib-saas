<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Client extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'pseudo', // Changé de 'name' à 'pseudo'
        'phone',
        'birth_date',
        'email',
        'address',
        'notes',
        'no_show_count',
        'is_blacklisted',
        'blacklist_reason',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_blacklisted' => 'boolean',
        'no_show_count' => 'integer',
    ];

    protected $dates = [
        'deleted_at',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookingRequests()
    {
        return $this->hasMany(BookingRequest::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user', 'user_id', 'conversation_id')
            ->withPivot(['role', 'last_read_at', 'is_muted'])
            ->withTimestamps();
    }

    // Nouvelles relations pour le workflow client
    // public function projects() - Supprimé, utiliser bookingRequests() à la place
    // public function activeProject() - Supprimé, utiliser activeBookingRequest() à la place

    public function tattooHistory()
    {
        return $this->hasMany(TattooHistory::class)->latest();
    }

    public function consent()
    {
        return $this->hasOne(Consent::class)->latest();
    }

    // Spatie Media Library
    public function registerMediaCollections(): void
    {
        // Avatar
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useFallbackUrl(asset('images/default-avatar.png'))
            ->useFallbackPath(public_path('/images/default-avatar.png'));
    }

    // Scopes
    public function scopeNotBlacklisted($query)
    {
        return $query->where('is_blacklisted', false);
    }

    public function scopeWithNoShowCount($query, $count = 1)
    {
        return $query->where('no_show_count', '>=', $count);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Obtenir le pseudo
     */
    public function getPseudoAttribute(): string
    {
        // Priorité: pseudo > first_name + last_name
        return $this->attributes['pseudo'] ?? trim(($this->attributes['first_name'] ?? '') . ' ' . ($this->attributes['last_name'] ?? ''));
    }

    /**
     * Vérifier si le client est mineur
     */
    public function isMinor(): bool
    {
        return $this->birth_date && $this->birth_date->age < 18;
    }

    /**
     * Obtenir l'âge du client
     */
    public function getAge(): int
    {
        return $this->birth_date ? $this->birth_date->age : 0;
    }

    /**
     * Vérifier si le client a un projet actif
     */
    public function hasActiveProject(): bool
    {
        return $this->activeProject()->exists();
    }

    /**
     * Obtenir le nombre total de tattoos réalisés
     */
    public function getTattoosCountAttribute(): int
    {
        return $this->tattooHistory()->count();
    }

    /**
     * Obtenir le montant total dépensé
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->tattooHistory()->sum('total_paid');
    }

    /**
     * Vérifier si le client a un consentement valide
     */
    public function hasValidConsent(): bool
    {
        $consent = $this->consent;
        return $consent && $consent->isValid() && $consent->isRecent();
    }

    /**
     * Obtenir le statut du client
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_blacklisted) {
            return 'Liste noire';
        }

        if ($this->hasActiveProject()) {
            return 'Projet en cours';
        }

        if ($this->tattoos_count > 0) {
            return 'Client fidèle';
        }

        return 'Nouveau client';
    }

    /**
     * Incrémenter le compteur de non-présentation
     */
    public function incrementNoShowCount(): void
    {
        $this->increment('no_show_count');

        // Blacklister automatiquement après 3 no-shows
        if ($this->no_show_count >= 3) {
            $this->update([
                'is_blacklisted' => true,
                'blacklist_reason' => 'Automatique après 3 non-présentations'
            ]);
        }
    }

    /**
     * Obtenir un résumé pour affichage
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->pseudo, // Utiliser le pseudo pour le full_name
            'email' => $this->email,
            'phone' => $this->phone,
            'age' => $this->getAge(),
            'is_minor' => $this->isMinor(),
            'status' => $this->status,
            'tattoos_count' => $this->tattoos_count,
            'total_spent' => $this->total_spent,
            'has_active_project' => $this->hasActiveProject(),
            'has_valid_consent' => $this->hasValidConsent(),
            'no_show_count' => $this->no_show_count,
            'is_blacklisted' => $this->is_blacklisted,
            'avatar_url' => $this->getFirstMediaUrl('avatar'),
        ];
    }
}

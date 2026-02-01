<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class TattooHistory extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'client_id', 'bookable_id', 'bookable_type', 'project_id',
        'tattoo_date', 'body_location', 'description',
        'duration', 'total_paid', 'payment_method', 'notes'
    ];

    protected $casts = [
        'tattoo_date' => 'date',
        'total_paid' => 'decimal:2',
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // ===== SPATIE MEDIA =====

    public function registerMediaCollections(): void
    {
        // Photos du tattoo final
        $this->addMediaCollection('photos')
            ->acceptsMimeTypes([
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/heic'
            ])
            ->useDisk('public');
    }

    // ===== SCOPES =====

    public function scopeForBookable($query, $bookableId, $bookableType)
    {
        return $query->where('bookable_id', $bookableId)
                   ->where('bookable_type', $bookableType);
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('tattoo_date', [$startDate, $endDate]);
    }

    // ===== MÉTHODES MÉTIER =====

    /**
     * Obtenir la durée formatée
     */
    public function getFormattedDuration(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0) {
            return $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'min' : '');
        }

        return $minutes . 'min';
    }

    /**
     * Obtenir le prix formaté
     */
    public function getFormattedPrice(): string
    {
        return number_format($this->total_paid, 2, ',', ' ') . '€';
    }

    /**
     * Obtenir la méthode de paiement formatée
     */
    public function getFormattedPaymentMethod(): string
    {
        return match($this->payment_method) {
            'stripe' => 'Carte bancaire',
            'cash' => 'Espèces',
            'other' => 'Autre',
            default => $this->payment_method,
        };
    }

    /**
     * Vérifier si des photos sont disponibles
     */
    public function hasPhotos(): bool
    {
        return $this->getMedia('photos')->isNotEmpty();
    }

    /**
     * Obtenir l'URL de la photo principale
     */
    public function getMainPhotoUrl(): ?string
    {
        $photo = $this->getFirstMedia('photos');
        return $photo ? $photo->getUrl() : null;
    }

    /**
     * Obtenir toutes les photos avec URLs
     */
    public function getPhotosWithUrls(): array
    {
        return $this->getMedia('photos')->map(function ($media) {
            return [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumbnail_url' => $media->getUrl('thumbnail'),
                'name' => $media->file_name,
                'size' => $media->size,
            ];
        })->toArray();
    }

    /**
     * Obtenir un résumé pour affichage
     */
    public function getSummary(): array
    {
        return [
            'id' => $this->id,
            'date' => $this->tattoo_date->format('d/m/Y'),
            'location' => $this->body_location,
            'description' => $this->description,
            'duration' => $this->getFormattedDuration(),
            'price' => $this->getFormattedPrice(),
            'payment_method' => $this->getFormattedPaymentMethod(),
            'has_photos' => $this->hasPhotos(),
            'photo_count' => $this->getMedia('photos')->count(),
        ];
    }
}

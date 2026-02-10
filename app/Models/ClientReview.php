<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'booking_request_id',
        'client_id',
        'bookable_id',
        'bookable_type',
        'rating',
        'comment',
        'photos',
        'status',
        'tattooer_response',
        'tattooer_responded_at',
        'moderated_at',
        'moderation_reason',
        'reviewed_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'photos' => 'array',
        'reviewed_at' => 'datetime',
        'tattooer_responded_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    // ===========================================
    // RELATIONS
    // ===========================================

    /**
     * Relation avec l'appointment
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Relation avec la booking request
     */
    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(BookingRequest::class);
    }

    /**
     * Relation avec le client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relation avec le bookable (tattooer ou studio)
     */
    public function bookable(): BelongsTo
    {
        return $this->morphTo('bookable');
    }

    // ===========================================
    // SCOPES
    // ===========================================

    /**
     * Avis publiés
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Avis cachés
     */
    public function scopeHidden($query)
    {
        return $query->where('status', 'hidden');
    }

    /**
     * Avis avec photos
     */
    public function scopeWithPhotos($query)
    {
        return $query->whereNotNull('photos')->where('photos', '!=', '[]');
    }

    /**
     * Avis avec commentaires
     */
    public function scopeWithComments($query)
    {
        return $query->whereNotNull('comment');
    }

    /**
     * Avis avec réponse du tattooer
     */
    public function scopeWithResponse($query)
    {
        return $query->whereNotNull('tattooer_response');
    }

    /**
     * Avis récents
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('reviewed_at', 'desc');
    }

    /**
     * Avis pour un bookable spécifique
     */
    public function scopeForBookable($query, $bookableId, $bookableType)
    {
        return $query->where('bookable_id', $bookableId)
                   ->where('bookable_type', $bookableType);
    }

    /**
     * Avis avec une note spécifique
     */
    public function scopeWithRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Avis avec une note supérieure ou égale
     */
    public function scopeWithRatingMin($query, $minRating)
    {
        return $query->where('rating', '>=', $minRating);
    }

    /**
     * Avis avec une note inférieure ou égale
     */
    public function scopeWithRatingMax($query, $maxRating)
    {
        return $query->where('rating', '<=', $maxRating);
    }

    // ===========================================
    // MÉTHODES
    // ===========================================

    /**
     * Vérifier si l'avis est publié
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Vérifier si l'avis est caché
     */
    public function isHidden(): bool
    {
        return $this->status === 'hidden';
    }

    /**
     * Vérifier si l'avis a des photos
     */
    public function hasPhotos(): bool
    {
        return !empty($this->photos);
    }

    /**
     * Vérifier si l'avis a un commentaire
     */
    public function hasComment(): bool
    {
        return !is_null($this->comment);
    }

    /**
     * Vérifier si le tattooer a répondu
     */
    public function hasResponse(): bool
    {
        return !is_null($this->tattooer_response);
    }

    /**
     * Obtenir le nombre de photos
     */
    public function getPhotosCount(): int
    {
        return count($this->photos ?? []);
    }

    /**
     * Obtenir les étoiles formatées
     */
    public function getStars(): string
    {
        return str_repeat('⭐', $this->rating);
    }

    /**
     * Obtenir le label de statut
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'published' => 'Publié',
            'hidden' => 'Caché',
            'pending' => 'En attente',
            default => ucfirst($this->status),
        };
    }

    /**
     * Obtenir la couleur de statut pour l'UI
     */
    public function getStatusColor(): string
    {
        return match($this->status) {
            'published' => 'green',
            'hidden' => 'yellow',
            'pending' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Obtenir le label de la note
     */
    public function getRatingLabel(): string
    {
        return match($this->rating) {
            1 => 'Très mécontent',
            2 => 'Mécontent',
            3 => 'Neutre',
            4 => 'Content',
            5 => 'Très content',
            default => 'Non noté',
        };
    }

    /**
     * Obtenir la couleur de la note pour l'UI
     */
    public function getRatingColor(): string
    {
        return match($this->rating) {
            1 => 'red',
            2 => 'orange',
            3 => 'yellow',
            4 => 'green',
            5 => 'emerald',
            default => 'gray',
        };
    }

    /**
     * Obtenir le temps écoulé depuis l'avis
     */
    public function getTimeAgo(): string
    {
        return $this->reviewed_at->diffForHumans();
    }

    /**
     * Obtenir la date formatée
     */
    public function getFormattedDate(): string
    {
        return $this->reviewed_at->format('d/m/Y');
    }

    /**
     * Obtenir la date et heure formatées
     */
    public function getFormattedDateTime(): string
    {
        return $this->reviewed_at->format('d/m/Y H:i');
    }

    /**
     * Obtenir l'URL de l'avis
     */
    public function getUrl(): string
    {
        return route('reviews.show', $this->id);
    }

    /**
     * Obtenir l'URL du bookable
     */
    public function getBookableUrl(): string
    {
        if ($this->bookable_type === 'App\Models\Tattooer') {
            return route('tattooer.show', $this->bookable->slug);
        }
        
        return '#'; // Fallback pour d'autres types
    }

    /**
     * Obtenir le texte abrégé du commentaire
     */
    public function getExcerpt(int $length = 100): string
    {
        if (!$this->comment) {
            return '';
        }

        $excerpt = substr($this->comment, 0, $length);
        
        if (strlen($this->comment) > $length) {
            $excerpt .= '...';
        }
        
        return $excerpt;
    }

    /**
     * Obtenir les photos formatées pour l'affichage
     */
    public function getFormattedPhotos(): array
    {
        if (!$this->hasPhotos()) {
            return [];
        }

        return array_map(function ($photo, $index) {
            return [
                'url' => $photo['url'] ?? $photo,
                'caption' => $photo['caption'] ?? "Photo " . ($index + 1),
                'thumbnail' => $photo['thumbnail'] ?? $photo,
            ];
        }, $this->photos);
    }

    /**
     * Vérifier si l'avis peut être modéré
     */
    public function canBeModerated(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Vérifier si le tattooer peut répondre
     */
    public function canRespond(): bool
    {
        return $this->status === 'published' && is_null($this->tattooer_response);
    }

    /**
     * Obtenir le résumé pour l'admin
     */
    public function getAdminSummary(): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'client_name' => $this->client->full_name,
            'tattooer_name' => $this->bookable->name,
            'reviewed_at' => $this->reviewed_at->format('d/m/Y H:i'),
            'has_comment' => $this->hasComment(),
            'has_photos' => $this->hasPhotos(),
            'has_response' => $this->hasResponse(),
            'status' => $this->status,
            'is_moderated' => !is_null($this->moderated_at),
        ];
    }
}

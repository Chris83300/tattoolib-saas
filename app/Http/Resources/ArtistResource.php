<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class ArtistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->pseudo ?: $this->name, // Utiliser le pseudo pour l'affichage public, fallback vers le nom
            'slug' => $this->slug,
            'bio' => $this->bio ? Str::limit($this->bio, 150) : '',

            // Localisation
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'region_label' => $this->getRegionLabel(),

            // Spécialisation
            'artist_type' => $this->artist_type,
            'specialization_label' => $this->artist_type === 'tattooer' ? 'Tatoueur' : 'Perceur',

            // Stats
            'rating' => round($this->rating ?? 0, 1),
            'reviews_count' => (int) ($this->reviews_count ?? 0),
            'appointments_count' => (int) ($this->appointments_count ?? 0),

            // Vérification
            'is_verified' => (bool) ($this->siret_verified ?? false),
            'has_compliance_badge' => (bool) ($this->has_compliance_badge ?? false),

            // Images
            'avatar_url' => $this->getAvatarUrl(),
            'portfolio_images' => $this->getPortfolioImages(),

            // Styles (pour tattooers)
            'styles' => [],

            // URLs
            'profile_url' => route('marketplace.tattooer.show', $this->slug),
            'contact_url' => $this->getContactUrl(),

            // Badges
            'badges' => $this->getBadges(),

            // Vérifier si le client a déjà une demande en cours
            'has_active_request' => $this->hasActiveRequestForCurrentUser(),

            // Stats détaillées
            'stats' => [
                'years_experience' => max(1, now()->diffInYears($this->created_at)),
                'completed_appointments' => (int) ($this->appointments_count ?? 0),
                'portfolio_count' => 0, // TODO: implementer
            ],
        ];
    }

    protected function getSpecializationLabel(): string
    {
        return $this->artist_type === 'tattooer' ? 'Tatoueur' : 'Perceur';
    }

    protected function getRegionLabel(): string
    {
        return $this->city ?? 'France';
    }

    protected function getAvatarUrl(): ?string
    {
        // Essayer d'abord le media Spatie
        $artistModel = $this->resource;

        if (method_exists($artistModel, 'getFirstMediaUrl')) {
            $avatar = $artistModel->getFirstMediaUrl('avatar');
            if ($avatar) {
                return $avatar;
            }
        }

        // Fallback vers UI Avatars avec le pseudo ou nom
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->pseudo ?: $this->name) . '&color=ffffff&background=8B7355';
    }

    protected function getPortfolioImages(): array
    {
        return [];
    }

    protected function getBadges(): array
    {
        $badges = [];

        if ($this->siret_verified) {
            $badges[] = [
                'type' => 'verified',
                'label' => '✓ Vérifié',
                'color' => 'beige-peau',
            ];
        }

        if ($this->rating >= 4.5 && $this->reviews_count >= 5) {
            $badges[] = [
                'type' => 'top_rated',
                'label' => '⭐ Top noté',
                'color' => 'beige-peau',
            ];
        }

        if ($this->has_compliance_badge) {
            $badges[] = [
                'type' => 'compliance',
                'label' => '✓ Conforme',
                'color' => 'vert-succes',
            ];
        }

        return $badges;
    }

    protected function getContactUrl(): ?string
    {
        if (!auth()->check() || !auth()->user()->client) {
            return route('login');
        }

        // Vérifier si le client a déjà une demande active
        if ($this->hasActiveRequestForCurrentUser()) {
            return null; // Pas de bouton contacter
        }

        return route('client.booking-request.form', [$this->id, $this->getMorphClass()]);
    }

    protected function hasActiveRequestForCurrentUser(): bool
    {
        if (!auth()->check() || !auth()->user()->client) {
            return false;
        }

        $client = auth()->user()->client;

        return \App\Models\BookingRequest::where('client_id', $client->id)
            ->where('bookable_type', $this->getMorphClass())
            ->where('bookable_id', $this->id)
            ->whereIn('status', ['pending', 'accepted', 'awaiting_deposit', 'deposit_paid', 'design_sent', 'confirmed'])
            ->exists();
    }

    protected function getMorphClass(): string
    {
        return match($this->artist_type) {
            'tattooer' => 'App\\Models\\Tattooer',
            'pierceur' => 'App\\Models\\Pierceur',
            default => 'App\\Models\\Tattooer',
        };
    }
}

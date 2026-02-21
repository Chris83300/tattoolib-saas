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
            'specialization_label' => $this->artist_type === 'tattooer' ? 'Tatoueur' : 'Pierceur',

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
            'styles' => $this->getStyles(),

            // Horaires et disponibilités
            'working_hours' => $this->getWorkingHours(),
            'is_open_today' => $this->isOpenToday(),
            'opening_days' => $this->getOpeningDays(),

            // Prix et délais
            'minimum_price' => $this->minimum_price,
            'wait_time_weeks_min' => $this->wait_time_weeks_min,
            'wait_time_weeks_max' => $this->wait_time_weeks_max,
            'wait_time_display' => $this->getWaitTimeDisplay(),

            // URLs
            'profile_url' => in_array($this->artist_type, ['piercer', 'Piercer'])
                ? route('marketplace.piercer.show', $this->slug)
                : route('marketplace.tattooer.show', $this->slug),
            'contact_url' => $this->getContactUrl(),

            // Badges
            'badges' => $this->getBadges(),

            // Vérifier si le client a déjà une demande en cours
            'has_active_request' => $this->hasActiveRequestForCurrentUser(),

            // Stats détaillées
            'stats' => [
                'years_experience' => (int) ($this->years_of_experience ?? 1),
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

    protected function getStyles(): array
    {
        // Les styles sont maintenant directement disponibles depuis la query
        if (isset($this->styles)) {
            if (is_array($this->styles)) {
                return array_filter($this->styles);
            }
            if (is_string($this->styles)) {
                return array_filter(array_map('trim', explode(',', $this->styles)));
            }
        }

        return [];
    }

    protected function getWorkingHours(): array
    {
        // Les working_hours sont maintenant directement disponibles depuis la query
        if (isset($this->working_hours)) {
            if (is_array($this->working_hours)) {
                return $this->working_hours;
            }
            if (is_string($this->working_hours)) {
                return json_decode($this->working_hours, true) ?? [];
            }
        }

        return [];
    }

    protected function isOpenToday(): bool
    {
        $workingHours = $this->getWorkingHours();
        $today = strtolower(now()->format('l'));

        if (!isset($workingHours[$today])) {
            return false;
        }

        $daySchedule = $workingHours[$today];
        return !empty($daySchedule['open']) && !empty($daySchedule['close']);
    }

    protected function getOpeningDays(): array
    {
        $workingHours = $this->getWorkingHours();
        $openingDays = [];

        $days = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

        foreach ($days as $day) {
            if (isset($workingHours[$day]) && !empty($workingHours[$day]['open'])) {
                $openingDays[] = ucfirst($day);
            }
        }

        return $openingDays;
    }

    protected function getWaitTimeDisplay(): string
    {
        $min = $this->wait_time_weeks_min;
        $max = $this->wait_time_weeks_max;

        if ($min && $max && $min != $max) {
            return "$min-$max semaines";
        }

        if ($min) {
            return "$min semaine" . ($min > 1 ? 's' : '');
        }

        return 'Disponible rapidement';
    }

    protected function getAvatarUrl(): ?string
    {
        $artistModel = $this->resource;

        // Essayer d'abord le media Spatie du tattooer
        if (method_exists($artistModel, 'getFirstMediaUrl')) {
            $avatar = $artistModel->getFirstMediaUrl('avatar');
            if ($avatar && $avatar !== '/images/default-tattooer-avatar.png') {
                return $avatar;
            }
        }

        // Essayer le media Spatie de l'utilisateur associé
        if (method_exists($artistModel, 'user') && $artistModel->user) {
            $userAvatar = $artistModel->user->getFirstMediaUrl('avatar');
            if ($userAvatar && $userAvatar !== '/images/default-tattooer-avatar.png') {
                return $userAvatar;
            }
        }

        // Fallback vers UI Avatars avec le pseudo ou nom
        $name = $this->pseudo ?: $this->name ?: 'Artiste';
        return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=ffffff&background=8B7355&size=200';
    }

    protected function getPortfolioImages(): array
    {
        $artistModel = $this->resource;
        $portfolioImages = [];

        // Chercher dans les médias du tattooer
        if (method_exists($artistModel, 'getMedia')) {
            $portfolioMedia = $artistModel->getMedia('portfolio');

            if ($portfolioMedia->isNotEmpty()) {
                $portfolioImages = $portfolioMedia->map(function ($media) {
                    return [
                        'url' => $media->getUrl(),
                        'thumb_url' => $media->getUrl('thumb'),
                        'name' => $media->name,
                    ];
                })->toArray();
            }
        }

        // Si pas d'images dans le tattooer, chercher dans l'utilisateur
        if (empty($portfolioImages) && method_exists($artistModel, 'user') && $artistModel->user) {
            $userPortfolioMedia = $artistModel->user->getMedia('portfolio');

            if ($userPortfolioMedia->isNotEmpty()) {
                $portfolioImages = $userPortfolioMedia->map(function ($media) {
                    return [
                        'url' => $media->getUrl(),
                        'thumb_url' => $media->getUrl('thumb'),
                        'name' => $media->name,
                    ];
                })->toArray();
            }
        }

        return $portfolioImages;
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
        if (!auth()->guard()->check() || !auth()->guard()->user()->client) {
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
        if (!auth()->guard()->check() || !auth()->guard()->user()->client) {
            return false;
        }

        $client = auth()->guard()->user()->client;

        return \App\Models\BookingRequest::where('client_id', $client->id)
            ->where('bookable_type', $this->getMorphClass())
            ->where('bookable_id', $this->id)
            ->whereIn('status', ['pending', 'accepted', 'awaiting_deposit', 'deposit_paid', 'design_sent', 'confirmed'])
            ->exists();
    }

    protected function getMorphClass(): string
    {
        return in_array($this->artist_type, ['piercer', 'Piercer'])
            ? 'App\\Models\\Piercer'
            : 'App\\Models\\Tattooer';
    }
}

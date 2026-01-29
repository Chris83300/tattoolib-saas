<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'name' => $this->name,
            'slug' => $this->slug,
            'bio' => $this->bio,
            'artist_type' => $this->artist_type,
            'specialization' => $this->specialization,
            'specialization_label' => $this->getSpecializationLabel(),
            'styles' => $this->styles ?? [],
            'city' => $this->city,
            'region' => $this->region,
            'region_label' => $this->getRegionLabel(),
            'is_verified' => (bool) $this->is_verified,
            'is_pro' => $this->isPro(),
            'subscription_plan' => $this->subscription_plan,
            'rating' => round($this->rating ?? 0, 1),
            'appointments_count' => (int) ($this->appointments_count ?? 0),
            'reviews_count' => $this->getReviewsCount(),
            'avatar_url' => $this->getAvatarUrl(),
            'portfolio_images' => $this->getPortfolioImages(),
            'studio' => $this->when($this->studio, function () {
                return [
                    'id' => $this->studio->id,
                    'name' => $this->studio->name,
                    'slug' => $this->studio->slug,
                    'city' => $this->studio->city,
                ];
            }),
            'badges' => $this->getBadges(),
            'stats' => [
                'years_experience' => $this->getYearsExperience(),
                'portfolio_count' => $this->getPortfolioCount(),
                'average_response_time' => $this->getAverageResponseTime(),
            ],
            'created_at' => $this->created_at?->format('Y-m-d'),
            'profile_url' => $this->getProfileUrl(),
        ];
    }

    protected function getSpecializationLabel(): string
    {
        $labels = [
            'tattooer' => 'Tatoueur',
            'pierceur' => 'Pierceur',
            'bodemodeur' => 'Bodemodeur',
            'studio_artist' => 'Artiste de studio'
        ];

        return $labels[$this->specialization] ?? $this->specialization;
    }

    protected function getRegionLabel(): string
    {
        $regions = [
            'ile-de-france' => 'Île-de-France',
            'provence-alpes-cote-dazur' => 'Provence-Alpes-Côte d\'Azur',
            'auvergne-rhone-alpes' => 'Auvergne-Rhône-Alpes',
            'occitanie' => 'Occitanie',
            'hauts-de-france' => 'Hauts-de-France',
            'grand-est' => 'Grand Est',
            'bretagne' => 'Bretagne',
            'normandie' => 'Normandie',
            'pays-de-la-loire' => 'Pays de la Loire',
            'centre-val-de-loire' => 'Centre-Val de Loire',
            'bourgogne-franche-comte' => 'Bourgogne-Franche-Comté',
            'nouvelle-aquitaine' => 'Nouvelle-Aquitaine',
            'poitou-charentes' => 'Poitou-Charentes',
            'corse' => 'Corse',
        ];

        return $regions[$this->region] ?? $this->region;
    }

    protected function isPro(): bool
    {
        return in_array($this->subscription_plan, ['pro', 'studio']);
    }

    protected function getReviewsCount(): int
    {
        // À implémenter avec les relations appropriées
        return 0;
    }

    protected function getAvatarUrl(): ?string
    {
        if ($this->avatar_url) {
            return $this->avatar_url;
        }

        // Avatar par défaut selon le type d'artiste
        $avatars = [
            'tattooer' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ffffff&background=8B7355',
            'pierceur' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ffffff&background=8B7355',
            'bodemodeur' => 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=ffffff&background=8B7355',
        ];

        return $avatars[$this->artist_type] ?? null;
    }

    protected function getPortfolioImages(): array
    {
        if (!method_exists($this->resource, 'getMedia')) {
            return [];
        }

        return $this->getMedia('portfolio')
            ->take(6)
            ->map(function ($media) {
                return [
                    'id' => $media->id,
                    'url' => $media->getUrl(),
                    'thumbnail' => $media->getUrl('thumbnail'),
                    'preview' => $media->getUrl('preview'),
                ];
            })
            ->toArray();
    }

    protected function getBadges(): array
    {
        $badges = [];

        if ($this->is_verified) {
            $badges[] = [
                'type' => 'verified',
                'label' => 'Vérifié',
                'color' => 'beige-peau',
            ];
        }

        if ($this->rating >= 4.8) {
            $badges[] = [
                'type' => 'top_rated',
                'label' => 'Top notes',
                'color' => 'beige-peau',
            ];
        }

        return $badges;
    }

    protected function getYearsExperience(): int
    {
        // Calcul basé sur la date de création
        return $this->created_at ? now()->year - $this->created_at->year : 0;
    }

    protected function getPortfolioCount(): int
    {
        if (!method_exists($this->resource, 'getMedia')) {
            return 0;
        }

        return $this->getMedia('portfolio')->count();
    }

    protected function getAverageResponseTime(): ?string
    {
        // À implémenter avec la logique de messagerie
        return '2 heures';
    }

    protected function getProfileUrl(): string
    {
        $routes = [
            'tattooer' => 'tattooers.show',
            'pierceur' => 'pierceurs.show',
            'bodemodeur' => 'bodemodeurs.show',
            'studio_artist' => 'studio-artists.show',
        ];

        $route = $routes[$this->artist_type] ?? 'tattooers.show';

        return route($route, $this->slug);
    }
}

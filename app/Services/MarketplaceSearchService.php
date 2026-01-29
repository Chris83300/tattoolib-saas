<?php

namespace App\Services;

use App\Models\Tattooer;
use App\Models\Pierceur;
use App\Models\StudioArtist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class MarketplaceSearchService
{
    protected array $specializations = ['tattooer', 'pierceur', 'bodemodeur'];
    protected array $styles = ['japonais', 'realisme', 'traditionnel', 'geometrique', 'aquarelle', 'dotwork', 'blackwork', 'lettering', 'new_school', 'old_school'];
    protected array $regions = ['ile-de-france', 'provence-alpes-cote-dazur', 'auvergne-rhone-alpes', 'occitanie', 'hauts-de-france', 'grand-est', 'bretagne', 'normandie', 'pays-de-la-loire', 'centre-val-de-loire', 'bourgogne-franche-comte', 'nouvelle-aquitaine', 'poitou-charentes', 'corse'];

    public function search(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $query = $this->getBaseQuery();

        $this->applyFilters($query, $filters);

        // Tri : Pro en premier, puis Free
        $this->applySorting($query, $filters['sort'] ?? 'pro_first');

        return $query->paginate($perPage);
    }

    public function getFeaturedArtists(int $limit = 6): Collection
    {
        return $this->getBaseQuery()
            ->where('is_verified', true)
            ->orderBy('is_pro', 'desc') // Pro en premier
            ->orderBy('rating', 'desc')
            ->orderBy('appointments_count', 'desc')
            ->limit($limit)
            ->get();
    }

    protected function getBaseQuery()
    {
        // Union de tous les types d'artistes
        $tattooers = Tattooer::query()
            ->select([
                'id', 'name', 'slug', 'bio', 'city', 'region', 'specialization',
                'styles', 'is_verified', 'subscription_plan', 'rating',
                'appointments_count', 'studio_id', 'avatar_url', 'created_at'
            ])
            ->selectRaw("'tattooer' as artist_type")
            ->with(['studio', 'media' => function($query) {
                $query->where('collection_name', 'portfolio')->limit(6);
            }]);

        $pierceurs = Pierceur::query()
            ->select([
                'id', 'name', 'slug', 'bio', 'city', 'region', 'specialization',
                'styles', 'is_verified', 'subscription_plan', 'rating',
                'appointments_count', 'studio_id', 'avatar_url', 'created_at'
            ])
            ->selectRaw("'pierceur' as artist_type")
            ->with(['studio', 'media' => function($query) {
                $query->where('collection_name', 'portfolio')->limit(6);
            }]);

        $studioArtists = StudioArtist::query()
            ->select([
                'id', 'artist_name as name', 'slug', 'bio', 'city', 'region', 'specialization',
                'styles', 'is_verified', 'subscription_plan', 'rating',
                'appointments_count', 'studio_id', 'avatar_url', 'created_at'
            ])
            ->selectRaw("'studio_artist' as artist_type")
            ->with(['studio', 'media' => function($query) {
                $query->where('collection_name', 'portfolio')->limit(6);
            }]);

        return $tattooers->union($pierceurs)->union($studioArtists);
    }

    protected function applyFilters($query, array $filters)
    {
        // Filtre par spécialisation
        if (!empty($filters['specialization'])) {
            $query->where('specialization', $filters['specialization']);
        }

        // Filtre par styles
        if (!empty($filters['styles'])) {
            $query->where(function($q) use ($filters) {
                foreach ($filters['styles'] as $style) {
                    $q->orWhereJsonContains('styles', $style);
                }
            });
        }

        // Filtre par région
        if (!empty($filters['region'])) {
            $query->where('region', $filters['region']);
        }

        // Filtre par ville
        if (!empty($filters['city'])) {
            $query->where('city', 'LIKE', '%' . $filters['city'] . '%');
        }

        // Filtre par vérification
        if (!empty($filters['verified_only'])) {
            $query->where('is_verified', true);
        }

        // Filtre par type d'artiste
        if (!empty($filters['artist_type'])) {
            $query->where('artist_type', $filters['artist_type']);
        }
    }

    protected function applySorting($query, string $sort)
    {
        switch ($sort) {
            case 'rating':
                $query->orderBy('rating', 'desc')
                      ->orderBy('appointments_count', 'desc');
                break;
            case 'appointments':
                $query->orderBy('appointments_count', 'desc')
                      ->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'pro_first':
            default:
                // Tri par pertinence : vérifié → notes → rendez-vous → expérience
                $query->orderBy('is_verified', 'desc')
                      ->orderBy('rating', 'desc')
                      ->orderBy('appointments_count', 'desc')
                      ->orderBy('created_at', 'desc');
        }
    }

    public function getSpecializations(): array
    {
        return [
            'tattooer' => 'Tatoueur',
            'pierceur' => 'Pierceur',
            'bodemodeur' => 'Bodemodeur',
            'studio_artist' => 'Artiste de studio'
        ];
    }

    public function getStyles(): array
    {
        return $this->styles;
    }

    public function getRegions(): array
    {
        return $this->regions;
    }

    public function getSortOptions(): array
    {
        return [
            'pro_first' => 'Pertinence',
            'rating' => 'Meilleures notes',
            'appointments' => 'Plus de rendez-vous',
            'newest' => 'Plus récents'
        ];
    }
}

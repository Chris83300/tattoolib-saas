<?php

namespace App\Services;

use App\Models\Tattooer;
use App\Models\Piercer;
use App\Models\Studio;
use App\Models\StudioArtist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MarketplaceSearchService
{
    protected array $specializations = ['tattooer', 'Piercer', 'bodemodeur'];
    protected array $styles = [
        'aquarelle',
        'blackwork',
        'dotwork',
        'geometrique',
        'japonais',
        'lettering',
        'new_school',
        'old_school',
        'realisme',
        'traditionnel'
    ];
    protected array $regions = [
        'auvergne-rhone-alpes',
        'bourgogne-franche-comte',
        'bretagne',
        'centre-val-de-loire',
        'corse',
        'grand-est',
        'hauts-de-france',
        'ile-de-france',
        'normandie',
        'nouvelle-aquitaine',
        'occitanie',
        'pays-de-la-loire',
        'poitou-charentes',
        'provence-alpes-cote-dazur'
    ];

    public function search(array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        $artistType = $filters['artisan_type'] ?? $filters['artist_type'] ?? '';

        try {
            if ($artistType === 'piercer') {
                Log::info('Using piercer branch');
                $query = $this->getPiercerBaseQuery();
                $this->applyFilters($query, $filters);
                $this->applySorting($query, $filters['sort'] ?? 'pro_first');
                return $query->paginate($perPage);
            } elseif ($artistType === 'tattooer') {
                Log::info('Using tattooer branch');
                $query = $this->getBaseQuery();
                $this->applyFilters($query, $filters);
                $this->applySorting($query, $filters['sort'] ?? 'pro_first');
                return $query->paginate($perPage);
            }

            Log::info('Using mixed branch (no type filter)');
            // Sans filtre de type : merger tattooers + piercers en mémoire
            $tattooerQuery = $this->getBaseQuery();
            $piercerQuery  = $this->getPiercerBaseQuery();
            $this->applyFilters($tattooerQuery, $filters);
            $this->applyFilters($piercerQuery, $filters);

            $tattooers = $tattooerQuery->get();
            $piercers  = $piercerQuery->get();

            $all = $tattooers->concat($piercers)
                ->sortByDesc('siret_verified')
                ->sortByDesc('rating')
                ->values();

            $page  = max(1, (int) request()->get('page', 1));
            $total = $all->count();

            return new LengthAwarePaginator(
                $all->forPage($page, $perPage)->values(),
                $total,
                $perPage,
                $page,
                ['path' => request()->url()]
            );
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            Log::error('Search trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function getStudios(array $filters = []): Collection
    {
        return Studio::with(['studioArtists' => fn($q) => $q->where('is_active', true), 'media'])
            ->where('is_active', true)
            ->when($filters['city'] ?? null, fn($q, $city) => $q->where('city', 'like', "%{$city}%"))
            ->get();
    }

    public function getFeaturedArtists(int $limit = 6): Collection
    {
        $tattooers = $this->getBaseQuery()
            ->orderByDesc('siret_verified')
            ->orderByDesc('rating')
            ->orderByDesc('appointments_count')
            ->limit($limit)
            ->get();

        $piercerLimit = max(1, (int) ceil($limit / 3));
        $piercers = $this->getPiercerBaseQuery()
            ->orderByDesc('siret_verified')
            ->orderByDesc('rating')
            ->limit($piercerLimit)
            ->get();

        return $tattooers->concat($piercers)
            ->sortByDesc('rating')
            ->take($limit)
            ->values();
    }

    protected function getPiercerBaseQuery()
    {
        return Piercer::query()
            ->select([
                'piercers.id',
                DB::raw("'piercer' as artist_type"),
                'piercers.user_id',
                'piercers.name',
                'piercers.slug',
                'piercers.city',
                'piercers.postal_code',
                'piercers.bio',
                'piercers.siret_verified',
                'piercers.has_compliance_badge',
                'piercers.created_at',
                'piercers.years_of_experience',
                'piercers.wait_time_weeks_min',
                'piercers.wait_time_weeks_max',
                'piercers.minimum_price',
                'piercers.piercing_types as styles',
                'piercers.working_hours',
                'users.status',
                'users.pseudo',
                DB::raw('COALESCE(AVG(reviews.rating), 0) as rating'),
                DB::raw('COUNT(DISTINCT reviews.id) as reviews_count'),
                DB::raw('COUNT(DISTINCT appointments.id) as appointments_count'),
            ])
            ->join('users', 'piercers.user_id', '=', 'users.id')
            ->leftJoin('reviews', function($join) {
                $join->on('reviews.reviewable_id', '=', 'piercers.id')
                     ->where('reviews.reviewable_type', '=', 'App\\Models\\Piercer');
            })
            ->leftJoin('appointments', function($join) {
                $join->on('appointments.bookable_id', '=', 'piercers.id')
                     ->where('appointments.bookable_type', '=', 'App\\Models\\Piercer')
                     ->whereIn('appointments.status', ['completed', 'confirmed']);
            })
            ->where('users.status', 'active')
            ->groupBy([
                'piercers.id', 'piercers.user_id', 'piercers.name', 'piercers.slug',
                'piercers.city', 'piercers.postal_code', 'piercers.bio',
                'piercers.siret_verified', 'piercers.has_compliance_badge',
                'piercers.created_at', 'piercers.years_of_experience',
                'piercers.wait_time_weeks_min', 'piercers.wait_time_weeks_max',
                'piercers.minimum_price', 'piercers.piercing_types', 'piercers.working_hours',
                'users.status', 'users.pseudo'
            ]);
    }

    protected function getBaseQuery()
    {
        return Tattooer::query()
            ->select([
                'tattooers.id',
                DB::raw("'tattooer' as artist_type"),
                'tattooers.user_id',
                'tattooers.name',
                'tattooers.slug',
                'tattooers.city',
                'tattooers.postal_code',
                'tattooers.bio',
                'tattooers.siret_verified',
                'tattooers.has_compliance_badge',
                'tattooers.created_at',
                'tattooers.years_of_experience',
                'tattooers.wait_time_weeks_min',
                'tattooers.wait_time_weeks_max',
                'tattooers.minimum_price',
                'tattooers.styles', // AJOUT: colonne styles
                'tattooers.working_hours', // AJOUT: colonne working_hours
                'users.status',
                'users.pseudo', // Ajout du pseudo
                DB::raw('COALESCE(AVG(reviews.rating), 0) as rating'),
                DB::raw('COUNT(DISTINCT reviews.id) as reviews_count'),
                DB::raw('COUNT(DISTINCT appointments.id) as appointments_count'),
            ])
            ->join('users', 'tattooers.user_id', '=', 'users.id')
            ->leftJoin('reviews', function($join) {
                $join->on('reviews.reviewable_id', '=', 'tattooers.id')
                     ->where('reviews.reviewable_type', '=', 'App\\Models\\Tattooer');
            })
            ->leftJoin('appointments', function($join) {
                $join->on('appointments.bookable_id', '=', 'tattooers.id')
                     ->where('appointments.bookable_type', '=', 'App\\Models\\Tattooer')
                     ->whereIn('appointments.status', ['completed', 'confirmed']);
            })
            ->where('users.status', 'active')
            ->groupBy([
                'tattooers.id', 'tattooers.user_id', 'tattooers.name', 'tattooers.slug',
                'tattooers.city', 'tattooers.postal_code', 'tattooers.bio',
                'tattooers.siret_verified', 'tattooers.has_compliance_badge',
                'tattooers.created_at', 'tattooers.years_of_experience',
                'tattooers.wait_time_weeks_min', 'tattooers.wait_time_weeks_max',
                'tattooers.minimum_price', 'tattooers.styles', 'tattooers.working_hours',
                'users.status', 'users.pseudo'
            ]);
    }

    protected function applyFilters($query, array $filters)
    {
        // Filtre par type d'artiste (artisan_type ou specialization)
        // NE PAS filtrer artist_type ici car c'est déjà dans la requête de base
        $artistType = $filters['artisan_type'] ?? $filters['specialization'] ?? null;
        if (!empty($artistType) && !in_array($artistType, ['tattooer', 'piercer'])) {
            // Pour le filtrage par type, utiliser les requêtes de base spécifiques
            // Le filtrage par type est géré dans la méthode search()
            return;
        }

        // Filtre par recherche textuelle (pseudo, nom, studio, ville)
        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('pseudo', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('studio_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('city', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Filtre par styles (pas implémenté pour l'instant)
        if (!empty($filters['styles'])) {
            // TODO: Implémenter le filtrage par styles quand la colonne existera
        }

        // Filtre par région (basé sur code postal)
        if (!empty($filters['region'])) {
            $postalPrefixes = $this->getPostalPrefixesForRegion($filters['region']);
            $query->where(function($q) use ($postalPrefixes) {
                foreach ($postalPrefixes as $prefix) {
                    $q->orWhere('postal_code', 'LIKE', $prefix . '%');
                }
            });
        }

        // Filtre par ville
        if (!empty($filters['city'])) {
            $query->where('city', 'LIKE', '%' . $filters['city'] . '%');
        }

        // Filtre par vérification
        if (!empty($filters['verified_only'])) {
            $query->where('siret_verified', true);
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
                $query->orderBy('siret_verified', 'desc')
                      ->orderBy('rating', 'desc')
                      ->orderBy('appointments_count', 'desc')
                      ->orderBy('created_at', 'desc');
        }
    }

    public function getSpecializations(): array
    {
        return [
            'tattooer' => 'Tatoueur',
            'piercer' => 'Pierceur',
            'bodemodeur' => 'Body Modeur',
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

    /**
     * Statistiques marketplace
     */
    public function getTotalArtists(): int
    {
        return Tattooer::whereHas('user', fn($q) => $q->where('status', 'active'))->count()
             + Piercer::whereHas('user', fn($q) => $q->where('status', 'active'))->count();
    }

    public function getVerifiedArtistsCount(): int
    {
        return Tattooer::whereHas('user', fn($q) => $q->where('status', 'active'))->where('siret_verified', true)->count()
             + Piercer::whereHas('user', fn($q) => $q->where('status', 'active'))->where('siret_verified', true)->count();
    }

    public function getProArtistsCount(): int
    {
        return Tattooer::whereHas('user', fn($q) => $q->where('status', 'active'))->where('current_plan', 'pro')->count()
             + Piercer::whereHas('user', fn($q) => $q->where('status', 'active'))->where('current_plan', 'pro')->count();
    }

    public function getTotalAppointments(): int
    {
        return DB::table('appointments')
            ->whereIn('status', ['completed', 'confirmed'])
            ->count();
    }

    /**
     * Mapping régions → préfixes codes postaux
     */
    protected function getPostalPrefixesForRegion(string $region): array
    {
        return match($region) {
            'ile-de-france' => ['75', '77', '78', '91', '92', '93', '94', '95'],
            'provence-alpes-cote-azur' => ['04', '05', '06', '13', '83', '84'],
            'auvergne-rhone-alpes' => ['01', '03', '07', '15', '26', '38', '42', '43', '63', '69', '73', '74'],
            'occitanie' => ['09', '11', '12', '30', '31', '32', '34', '46', '48', '65', '66', '81', '82'],
            'nouvelle-aquitaine' => ['16', '17', '19', '23', '24', '33', '40', '47', '64', '79', '86', '87'],
            'hauts-de-france' => ['02', '59', '60', '62', '80'],
            'grand-est' => ['08', '10', '51', '52', '54', '55', '57', '67', '68', '88'],
            'bretagne' => ['22', '29', '35', '56'],
            'normandie' => ['14', '27', '50', '61', '76'],
            'pays-de-la-loire' => ['44', '49', '53', '72', '85'],
            'centre-val-de-loire' => ['18', '28', '36', '37', '41', '45'],
            'bourgogne-franche-comte' => ['21', '25', '39', '58', '70', '71', '89', '90'],
            'corse' => ['20'],
            default => [],
        };
    }
}

@php
    $artist = [
        'id'                  => $tattooer->id,
        'name'                => $tattooer->pseudo ?: ($tattooer->user?->name ?: ($tattooer->name ?? 'Artiste')),
        'type'                => 'tattooer',
        'slug'                => $tattooer->slug,
        'avatar_url'          => $tattooer->user?->getFirstMediaUrl('avatar') ?: null,
        'banner_url'          => $tattooer->getFirstMediaUrl('banner') ?: null,
        'city'                => $tattooer->city,
        'studio_name'         => $tattooer->studio_name ?? null,
        'bio'                 => $tattooer->bio,
        'styles'              => is_array($tattooer->styles) ? $tattooer->styles : [],
        'has_compliance_badge'=> (bool) ($tattooer->has_compliance_badge ?? false),
        'siret_verified'      => (bool) ($tattooer->siret_verified ?? false),
        'experience_years'    => $tattooer->years_of_experience ?? 0,
        'min_price'           => $tattooer->minimum_price,
        'wait_time'           => $tattooer->wait_time_weeks_min
            ? ($tattooer->wait_time_weeks_min . ($tattooer->wait_time_weeks_max ? '–' . $tattooer->wait_time_weeks_max : '') . ' sem.')
            : 'Non spécifié',
        'average_rating'      => $tattooer->rating ?? 0,
        'total_reviews'       => $tattooer->reviews_count ?? 0,
        'sort_rank'           => \App\Helpers\ArtistSortHelper::calculateRank($tattooer),
    ];
@endphp

<x-ui.artistCard :artist="$artist" />

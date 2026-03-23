@php
    $artist = [
        'id'                  => $piercer->id,
        'name'                => $piercer->pseudo ?: ($piercer->user?->name ?: ($piercer->name ?? 'Artiste')),
        'type'                => 'piercer',
        'slug'                => $piercer->slug,
        'avatar_url'          => $piercer->user?->getFirstMediaUrl('avatar') ?: null,
        'banner_url'          => $piercer->getFirstMediaUrl('banner') ?: null,
        'city'                => $piercer->city,
        'studio_name'         => $piercer->studio_name ?? null,
        'bio'                 => $piercer->bio,
        'styles'              => is_array($piercer->piercing_types) ? $piercer->piercing_types : [],
        'has_compliance_badge'=> (bool) ($piercer->has_compliance_badge ?? false),
        'siret_verified'      => (bool) ($piercer->siret_verified ?? false),
        'experience_years'    => $piercer->years_of_experience ?? 0,
        'min_price'           => $piercer->minimum_price,
        'wait_time'           => $piercer->wait_time_weeks_min
            ? ($piercer->wait_time_weeks_min . ($piercer->wait_time_weeks_max ? '–' . $piercer->wait_time_weeks_max : '') . ' sem.')
            : 'Non spécifié',
        'average_rating'      => $piercer->rating ?? 0,
        'total_reviews'       => $piercer->reviews_count ?? 0,
        'sort_rank'           => \App\Helpers\ArtistSortHelper::calculateRank($piercer),
    ];
@endphp

<x-ui.artistCard :artist="$artist" />

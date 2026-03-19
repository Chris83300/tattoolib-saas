@props(['artist', 'studioArtist'])

@php
    // Déterminer si on utilise les données du marketplace ou du studio
    $isMarketplace = isset($artist);
    $data = $isMarketplace ? $artist : $studioArtist;

    // Normaliser les données pour compatibilité
    $name = $isMarketplace ? $artist['name'] : ($studioArtist->artist_name ?: $studioArtist->user?->name ?? 'Artiste');
    $type = $isMarketplace ? $artist['type'] ?? 'tattooer' : $studioArtist->artisan_type;
    $avatar = $isMarketplace ? $artist['avatar_url'] ?? null : $studioArtist->user?->getFirstMediaUrl('avatar');

    // Banner : priorité au modèle Tattooer/Piercer (qui porte la collection 'banner')
    if ($isMarketplace) {
        $banner = $artist['banner_url'] ?? null;
    } else {
        $artisanModel = null;
        if ($studioArtist->user) {
            $artisanModel =
                $studioArtist->artisan_type === 'piercer'
                    ? \App\Models\Piercer::where('user_id', $studioArtist->user_id)->first()
                    : \App\Models\Tattooer::where('user_id', $studioArtist->user_id)->first();
        }
        $banner = $artisanModel?->getFirstMediaUrl('banner') ?: null;
    }

    $city = $isMarketplace ? $artist['city'] ?? null : $studioArtist->studio?->city;
    $studioName = $isMarketplace ? $artist['studio_name'] ?? null : $studioArtist->studio?->name;
    $bio = $isMarketplace ? $artist['bio'] ?? null : $artisanModel?->bio ?? ($studioArtist->user?->bio ?? null);
@endphp

<div
    class="bg-noir-profond rounded-[2rem] border border-titane/40
           shadow-lg shadow-electric-blue/30 overflow-hidden
           hover:ring-2 hover:ring-beige-peau hover:shadow-cuivre/50
           transition-all relative m-2 mb-4">

    {{-- Badges --}}
    <div class="absolute top-2 left-2 space-y-1 z-10">
        @if ($isMarketplace)
            @php $sortRank = $artist['sort_rank'] ?? 0; @endphp
            @if ($sortRank >= 100)
                <span class="px-2 py-0.5 text-[10px] font-bold bg-beige-peau text-noir-profond rounded-full block">PRO</span>
            @elseif ($sortRank >= 90)
                <span class="px-2 py-0.5 text-[10px] font-bold bg-beige-peau/70 text-noir-profond rounded-full block">Studio</span>
            @endif
        @endif
        @if ($isMarketplace && isset($artist['siret_verified']) && $artist['siret_verified'])
            <span class="badge-verified">Vérifié</span>
        @elseif (!$isMarketplace && $studioArtist->is_active)
            <span class="bg-vert-succes/20 text-vert-succes text-xs px-2 py-1 rounded-full font-semibold">Actif</span>
        @elseif (!$isMarketplace && !$studioArtist->is_active)
            <span
                class="bg-rouge-alerte/20 text-rouge-alerte text-xs px-2 py-1 rounded-full font-semibold">Inactif</span>
        @endif
    </div>

    {{-- Image de bannière --}}
    <div class="h-64 md:h-80 bg-gradient-to-br from-titane/40 to-noir-profond relative overflow-hidden">
        @if (!empty($banner))
            <img src="{{ $banner }}" alt="Bannière de {{ $name }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent"></div>
        @elseif ($isMarketplace && !empty($artist['banner_url']))
            <img src="{{ $artist['banner_url'] }}" alt="Bannière de {{ $name }}"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent">
            </div>
        @else
            <!-- Bannière par défaut améliorée -->
            <div class="absolute inset-0 bg-gradient-to-br from-beige-peau/30 via-titane/40 to-noir-profond"></div>
            <div class="absolute inset-0 bg-black/30"></div>

            <!-- Icône ou motif décoratif -->
            <div class="absolute inset-0 flex items-center justify-center">
                @if ($type === 'piercer')
                    <svg class="w-24 h-24 text-beige-peau/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                    </svg>
                @else
                    <svg class="w-24 h-24 text-beige-peau/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                    </svg>
                @endif
            </div>

            <!-- Texte du type d'artiste -->
            <div class="absolute bottom-4 left-4">
                <span class="text-beige-peau/40 text-sm font-semibold">
                    {{ $type === 'piercer' ? 'Pierceur' : 'Tatoueur' }}
                </span>
            </div>
        @endif
    </div>

    {{-- Avatar et Stats --}}
    <div class="px-4 -mt-12 relative z-20">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            {{-- Avatar --}}
            <div
                class="w-32 h-32 md:w-36 md:h-36 rounded-full border-2 border-titane/30 shadow-lg shadow-titane/20 overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
                @if (!empty($avatar))
                    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 md:w-18 md:h-18 text-ivoire-text/30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>

            {{-- Stats compactes --}}
            <div class="flex-1 mt-4 flex flex-wrap justify-center lg:mt-20 md:mt-20 gap-2 text-xs text-ivoire-text">
                @if ($isMarketplace)
                    <!-- Stats marketplace -->
                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">

                        <span>{{ $artist['experience_years'] ?? 'N/A' }} ans</span> <span class="text-titane">
                            d'expérience</span>
                    </div>

                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <span>{{ $artist['min_price'] ? 'À partir de ' . $artist['min_price'] . '€' : 'N/A' }}</span>
                    </div>

                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <span>{{ $artist['wait_time'] ?? 'N/A' }}</span><span class="text-titane"> d'attente</span>
                    </div>
                @else
                    <!-- Stats studio -->
                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>{{ $type === 'piercer' ? ' Pierceur' : ' Tatoueur' }}</span>
                    </div>

                    <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Rejoint {{ $studioArtist->joined_at?->format('d/m/Y') ?? 'N/A' }}</span>
                    </div>

                    @if (!$studioArtist->user)
                        <div class="flex items-center border border-titane/30 bg-titane/10 p-1 rounded-full gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-ambre-warning"> En attente</span>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <div class="p-4 pt-2">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
            <div class="text-center sm:text-left">
                <h3 class="text-beige-peau font-semibold text-lg mb-1">
                    {{ $name }}
                </h3>
                <p class="text-ivoire-text text-sm mb-1">
                    {{ $type === 'piercer' ? 'Pierceur' : 'Tatoueur' }}
                </p>
                @if (!empty($studioName))
                    <p class="text-beige-peau text-sm mb-1">
                        {{ $studioName }}
                    </p>
                @endif
                @if (!empty($city))
                    <p class="text-titane text-sm">
                        {{ $city }}
                    </p>
                @endif
            </div>

            <div class="text-center sm:text-right">
                @if ($isMarketplace)
                    <!-- Rating marketplace -->
                    <div class="flex items-center justify-center sm:justify-end gap-1 text-beige-peau text-sm mb-1">
                        ⭐ {{ number_format($artist['average_rating'], 1) }} ({{ $artist['total_reviews'] }} avis)
                    </div>
                    <div class="text-ivoire-text/60 text-xs">
                        {{ $artist['total_reviews'] }} avis
                    </div>
                @else
                    <!-- Statut studio -->
                    @if ($studioArtist->user)
                        <div
                            class="flex items-center justify-center sm:justify-end gap-1 text-vert-succes text-sm mb-1">
                            ✅ Actif
                        </div>
                        <div class="text-ivoire-text/60 text-xs">
                            Profil complet
                        </div>
                    @else
                        <div
                            class="flex items-center justify-center sm:justify-end gap-1 text-ambre-warning text-sm mb-1">
                            ⏳ En attente
                        </div>
                        <div class="text-ivoire-text/60 text-xs">
                            Invitation envoyée
                        </div>
                    @endif
                @endif
            </div>
        </div>

        {{-- Styles (marketplace uniquement) --}}
        @if ($isMarketplace && isset($artist['styles']))
            <div class="flex flex-wrap justify-center gap-3 mb-3">
                @foreach ($artist['styles'] as $style)
                    <span class="badge-style text-xs bg-beige-peau/10 text-beige-peau px-2 py-1 rounded-full">
                        {{ $style }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Bio --}}
        @if (!empty($bio))
            <div class="text-ivoire-text text-sm mb-3 line-clamp-2">
                {{ Str::limit($bio, 100) }}
            </div>
        @elseif (!$isMarketplace && !$studioArtist->user)
            <div class="text-titane text-sm mb-3 italic">
                En attente d'acceptation de l'invitation...
            </div>
        @endif

        {{-- CTA --}}
        <div class="flex flex-col sm:flex-row gap-2">
            @if ($isMarketplace)
                <!-- Actions marketplace -->
                <x-ui.button variant="secondary" size="sm"
                    href="{{ route('marketplace.show.artist', $artist['slug']) }}" class="flex-1">
                    Voir le profil
                </x-ui.button>

                <x-ui.button variant="primary" size="sm"
                    href="{{ route('booking-request.form', ['bookableId' => $artist['id'], 'bookableType' => $artist['type']]) }}"
                    class="flex-1">
                    Contacter
                </x-ui.button>
            @else
                <!-- Actions studio -->
                @if ($studioArtist->user)
                    <x-ui.button variant="secondary" size="sm"
                        href="{{ route('studio.artists.show', $studioArtist) }}" class="flex-1">
                        Voir la gestion
                    </x-ui.button>
                @else
                    <x-ui.button variant="secondary" size="sm" href="{{ route('studio.artists') }}"
                        class="flex-1">
                        Gérer l'invitation
                    </x-ui.button>
                @endif

                <form method="POST" action="{{ route('studio.artists.toggle', $studioArtist) }}" class="flex-1">
                    @csrf
                    @method('PUT')
                    <x-ui.button variant="primary" size="sm" type="submit" class="w-full">
                        {{ $studioArtist->is_active ? 'Désactiver' : 'Activer' }}
                    </x-ui.button>
                </form>
            @endif
        </div>

    </div>
</div>

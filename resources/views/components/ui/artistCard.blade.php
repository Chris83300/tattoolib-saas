@props(['artist'])

<div
    class="bg-noir-profond rounded-[2rem] border border-titane/40
           shadow-lg shadow-electric-blue/30 overflow-hidden
           hover:ring-2 hover:ring-beige-peau hover:shadow-cuivre/50
           transition-all relative m-2 mb-4">

    {{-- Badges --}}
    <div class="absolute top-2 left-2 space-y-1 z-10">
        @if ($artist['is_subscribed'])
            <span class="badge-pro">PRO</span>
        @endif

        @if ($artist['siret_verified'])
            <span class="badge-verified">Vérifié</span>
        @endif
    </div>

    {{-- Image de bannière --}}
    <div class="h-64 md:h-80 bg-gradient-to-br from-titane/40 to-noir-profond relative overflow-hidden">
        @if (!empty($artist['banner_url']))
            <img src="{{ $artist['banner_url'] }}" alt="Bannière de {{ $artist['name'] }}"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent">
            </div>
        @else
            <!-- Bannière par défaut avec gradient -->
            <div class="absolute inset-0 bg-gradient-to-br from-beige-peau/20 via-titane/30 to-noir-profond"></div>
            <div class="absolute inset-0 bg-black/20"></div>
        @endif
    </div>

    {{-- Avatar et Stats --}}
    <div class="px-4 -mt-12 relative z-20">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            {{-- Avatar --}}
            <div
                class="w-32 h-32 md:w-36 md:h-36 rounded-full border-2 border-titane/30 shadow-lg shadow-titane/20 overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
                @if (!empty($artist['avatar_url']))
                    <img src="{{ $artist['avatar_url'] }}" alt="{{ $artist['name'] }}"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 md:w-18 md:h-18 text-ivoire-text/30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                @endif
            </div>

            {{-- Stats compactes --}}
            <div class="flex-1 flex flex-wrap gap-2 text-xs text-ivoire-text/70">
                <div class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $artist['experience_years'] ?? 'N/A' }} ans</span>
                </div>

                <div class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>{{ $artist['min_price'] ? 'À partir de ' . $artist['min_price'] . '€' : 'N/A' }}</span>
                </div>

                <div class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $artist['wait_time'] ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 pt-2">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
            <div class="text-center sm:text-left">
                <h3 class="text-beige-peau font-semibold text-lg mb-1">
                    {{ $artist['name'] }}
                </h3>
                <p class="text-ivoire-text text-sm mb-1">
                    {{ $artist['type'] === 'tattooer' ? 'Tatoueur' : 'Pierceur' }}
                </p>
                @if (!empty($artist['studio_name']))
                    <p class="text-titane text-sm mb-1">
                        {{ $artist['studio_name'] }}
                    </p>
                @endif
                <p class="text-titane text-sm">
                    {{ $artist['city'] }}
                </p>
            </div>

            <div class="text-center sm:text-right">
                <div class="flex items-center justify-center sm:justify-end gap-1 text-beige-peau text-sm mb-1">
                    ⭐ {{ number_format($artist['average_rating'], 1) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">
                    {{ $artist['total_reviews'] }} avis
                </div>
            </div>
        </div>

        {{-- Styles --}}
        <div class="flex flex-wrap justify-center gap-3 mb-3">
            @foreach ($artist['styles'] as $style)
                <span class="badge-style text-xs bg-beige-peau/10 text-beige-peau px-2 py-1 rounded-full">
                    {{ $style }}
                </span>
            @endforeach
        </div>

        {{-- Bio --}}
        @if (!empty($artist['bio']))
            <div class="text-ivoire-text text-sm mb-3 line-clamp-2">
                {{ Str::limit($artist['bio'], 100) }}
            </div>
        @endif

        {{-- CTA --}}
        <div class="flex flex-col sm:flex-row gap-2">
            <x-ui.button variant="secondary" size="sm"
                href="{{ route('marketplace.show.artist', $artist['slug']) }}" class="flex-1">
                Voir le profil
            </x-ui.button>

            <x-ui.button variant="primary" size="sm"
                href="{{ route('booking-request.form', ['bookableId' => $artist['id'], 'bookableType' => $artist['type']]) }}"
                class="flex-1">
                Contacter
            </x-ui.button>
        </div>

    </div>
</div>

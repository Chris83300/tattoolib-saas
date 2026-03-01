@props(['salon'])

<div
    class="bg-noir-profond rounded-[2rem] border border-titane/40
           shadow-lg shadow-electric-blue/30 overflow-hidden
           hover:ring-2 hover:ring-beige-peau hover:shadow-cuivre/50
           transition-all relative m-2 mb-4">

    {{-- Badges --}}
    <div class="absolute top-2 left-2 space-y-1 z-10">
        @if (isset($salon->siret_verified) && $salon->siret_verified)
            <span class="bg-vert-succes/20 text-vert-succes text-xs px-2 py-1 rounded-full font-semibold">Vérifié</span>
        @endif
        @if (isset($salon->is_featured) && $salon->is_featured)
            <span class="bg-beige-peau/20 text-beige-peau text-xs px-2 py-1 rounded-full font-semibold">⭐
                Populaire</span>
        @endif
    </div>

    {{-- Image de bannière --}}
    <div class="h-64 md:h-80 bg-gradient-to-br from-titane/40 to-noir-profond relative overflow-hidden">
        @if ($salon->getFirstMediaUrl('cover'))
            <img src="{{ $salon->getFirstMediaUrl('cover') }}" alt="Bannière de {{ $salon->name }}"
                class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent"></div>
        @else
            <!-- Bannière par défaut -->
            <div class="absolute inset-0 bg-gradient-to-br from-beige-peau/30 via-titane/40 to-noir-profond"></div>
            <div class="absolute inset-0 bg-black/30"></div>

            <!-- Icône de salon -->
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-24 h-24 text-beige-peau/20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>

            <!-- Texte du type -->
            <div class="absolute bottom-4 left-4">
                <span class="text-beige-peau/40 text-sm font-semibold">Salon</span>
            </div>
        @endif
    </div>

    {{-- Avatar et Stats --}}
    <div class="px-4 -mt-12 relative z-20">
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
            {{-- Avatar --}}
            <div
                class="w-32 h-32 md:w-36 md:h-36 rounded-full border-2 border-titane/30 shadow-lg shadow-titane/20 overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
                @if ($salon->getFirstMediaUrl('logo'))
                    <img src="{{ $salon->getFirstMediaUrl('logo') }}" alt="{{ $salon->name }}"
                        class="w-full h-full object-cover">
                @else
                    <svg class="w-16 h-16 md:w-18 md:h-18 text-ivoire-text/30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                @endif
            </div>

            {{-- Stats compactes --}}
            <div class="flex-1 flex flex-wrap gap-2 text-xs text-ivoire-text/70">
                <div class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>{{ $salon->studioArtists?->count() ?? 0 }} artistes</span>
                </div>

                <div class="flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>{{ $salon->city ?? 'Non spécifiée' }}</span>
                </div>

                @if (isset($salon->average_rating))
                    <div class="flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.784.57-1.838-.197-1.539-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <span>{{ number_format($salon->average_rating, 1) }} ⭐</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="p-4 pt-2">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
            <div class="text-center sm:text-left">
                <h3 class="text-beige-peau font-semibold text-lg mb-1">
                    {{ $salon->name }}
                </h3>
                @if (!empty($salon->city))
                    <p class="text-beige-peau text-sm mb-1">
                        {{ $salon->city }}
                    </p>
                @endif
                @if (!empty($salon->postal_code))
                    <p class="text-titane text-sm">
                        {{ $salon->postal_code }}
                    </p>
                @endif
            </div>

            <div class="text-center sm:text-right">
                @if (isset($salon->average_rating))
                    <div class="flex items-center justify-center sm:justify-end gap-1 text-beige-peau text-sm mb-1">
                        ⭐ {{ number_format($salon->average_rating, 1) }}
                    </div>
                    <div class="text-ivoire-text/60 text-xs">
                        {{ $salon->total_reviews ?? 0 }} avis
                    </div>
                @else
                    <div class="flex items-center justify-center sm:justify-end gap-1 text-vert-succes text-sm mb-1">
                        ✅ Actif
                    </div>
                    <div class="text-ivoire-text/60 text-xs">
                        Salon vérifié
                    </div>
                @endif
            </div>
        </div>

        {{-- Description --}}
        @if (!empty($salon->description))
            <div class="text-ivoire-text text-sm mb-3 line-clamp-2">
                {{ Str::limit($salon->description, 120) }}
            </div>
        @endif

        {{-- Styles spécialisés --}}
        @if (isset($salon->specialties) && is_array($salon->specialties) && count($salon->specialties) > 0)
            <div class="flex flex-wrap justify-center gap-2 mb-3">
                @foreach ($salon->specialties as $specialty)
                    <span class="text-xs bg-beige-peau/10 text-beige-peau px-2 py-1 rounded-full">
                        {{ $specialty }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- CTA --}}
        <div class="flex flex-col sm:flex-row gap-2">
            <a href="{{ route('studio.public', $salon->slug) }}"
                class="flex-1 block w-full text-center px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors text-sm">
                Voir le salon
            </a>
        </div>

    </div>
</div>

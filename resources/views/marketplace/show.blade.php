@extends('layouts.app')

@section('title', $artist->user->name . ' - ' . ($type === 'tattooer' ? 'Tatoueur' : 'Perceur') . ' - Ink&Pik')

@section('content')
    <div class="min-h-screen bg-noir-profond">

        <!-- HERO — Image bannière + infos principales -->
        <div class="relative">
            <!-- Image de bannière -->
            <div class="h-64 md:h-80 bg-gradient-to-br from-titane/40 to-noir-profond relative overflow-hidden">
                @if ($artist->getFirstMediaUrl('banner'))
                    <img src="{{ $artist->getFirstMediaUrl('banner') }}" alt="Bannière de {{ $artist->user->name }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent">
                    </div>
                @else
                    <!-- Bannière par défaut avec gradient -->
                    <div class="absolute inset-0 bg-gradient-to-br from-beige-peau/20 via-titane/30 to-noir-profond"></div>
                    <div class="absolute inset-0 bg-black/20"></div>
                @endif
            </div>

            <div class="max-w-6xl mx-auto px-4 -mt-16 relative z-10">
                <div class="flex flex-col items-center gap-4 md:flex-row md:items-end md:gap-6">

                    <!-- Avatar grand -->
                    <div
                        class="w-32 h-32 md:w-36 md:h-36 rounded-4xl border-4 border-cuivre shadow-2xl overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
                        @if ($artist->getFirstMediaUrl('avatar'))
                            <img src="{{ $artist->getFirstMediaUrl('avatar') }}" alt="{{ $artist->user->name }}"
                                class="w-full h-full object-cover">
                        @elseif ($artist->user->getFirstMediaUrl('avatar'))
                            <img src="{{ $artist->user->getFirstMediaUrl('avatar') }}" alt="{{ $artist->user->name }}"
                                class="w-full h-full object-cover">
                        @else
                            <svg class="w-16 h-16 md:w-18 md:h-18 text-ivoire-text/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        @endif
                    </div>

                    <!-- Infos -->
                    <div class="flex-1 text-center pb-4">
                        <!-- PSEUDO -->
                        <h1 class="text-2xl md:text-4xl font-bold text-ivoire-text drop-shadow-lg">{{ $artist->user->name }}
                        </h1>

                        <!-- Type -->
                        {{-- <p class="text-lg md:text-xl text-beige-peau mb-2 drop-shadow">
                            {{ $type === 'tattooer' ? 'Tatoueur professionnel' : 'Perceur professionnel' }}
                        </p> --}}

                        <!-- Nom du studio -->
                        @if ($artist->studio_name)
                            <p class="text-lg md:text-xl text-beige-peau mb-2 drop-shadow">
                                {{ $artist->studio_name }}
                            </p>
                        @endif

                        <!-- Localisation -->
                        @if ($artist->city || $artist->postal_code)
                            <div class="flex items-center justify-center gap-2 text-ivoire-text/80 mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $artist->city }}{{ $artist->postal_code ? ', ' . $artist->postal_code : '' }}</span>
                            </div>
                        @endif

                        <!-- Badges rapides -->
                        <div class="flex flex-wrap justify-center gap-2 mt-3">
                            @if ($artist->admin_verified_at)
                                <span
                                    class="px-3 py-1 bg-vert-succes/20 text-vert-succes rounded-full text-sm font-semibold">
                                    ✓ Vérifié
                                </span>
                            @endif

                            @if ($type === 'tattooer' && isset($artist->years_of_experience) && $artist->years_of_experience)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    ✏️ {{ $artist->years_of_experience }} ans d'expérience
                                </span>
                            @endif

                            @if (
                                $type === 'tattooer' &&
                                    isset($artist->wait_time_weeks_min) &&
                                    isset($artist->wait_time_weeks_max) &&
                                    $artist->wait_time_weeks_min &&
                                    $artist->wait_time_weeks_max)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    ⏱️ {{ $artist->wait_time_weeks_min }} à {{ $artist->wait_time_weeks_max }} semaines
                                </span>
                            @elseif ($type === 'tattooer' && isset($artist->wait_time_weeks_min) && $artist->wait_time_weeks_min)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    ⏱️ {{ $artist->wait_time_weeks_min }} semaines
                                </span>
                            @endif

                            @if ($type === 'tattooer' && isset($artist->minimum_price) && $artist->minimum_price)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    💰 À partir de {{ number_format($artist->minimum_price, 2, ',', ' ') }} €
                                </span>
                            @endif

                            @if ($type === 'tattooer' && isset($artist->is_certified) && $artist->is_certified)
                                <span
                                    class="px-3 py-1 bg-vert-succes/20 text-vert-succes rounded-full text-sm font-semibold">
                                    ✓ Badge conformité
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Avis -->
                    @if ($type === 'tattooer')
                        <div class="flex flex-col items-center gap-2 md:items-end">
                            <!-- Étoiles -->
                            <div class="flex items-center gap-1">
                                @php
                                    $rating = $artist->reviews_avg_rating ?? 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = $rating - $fullStars >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp

                                <!-- Étoiles pleines -->
                                @for ($i = 0; $i < $fullStars; $i++)
                                    <span class="text-yellow-400 text-xl">★</span>
                                @endfor

                                <!-- Demi-étoile -->
                                @if ($hasHalfStar)
                                    <span class="text-yellow-400 text-xl">☆</span>
                                @endif

                                <!-- Étoiles vides -->
                                @for ($i = 0; $i < $emptyStars; $i++)
                                    <span class="text-gray-600 text-xl">☆</span>
                                @endfor

                                <span class="text-ivoire-text font-semibold ml-2">{{ number_format($rating, 1) }}/5</span>
                            </div>

                            <!-- Nombre d'avis et lien -->
                            @if (isset($artist->reviews_count) && $artist->reviews_count > 0)
                                <a href="#reviews"
                                    onclick="document.getElementById('reviews').scrollIntoView({behavior: 'smooth', block: 'start'}); return false;"
                                    class="text-beige-peau hover:text-beige-peau/80 text-sm underline transition-colors cursor-pointer">
                                    Voir les {{ $artist->reviews_count }} avis
                                </a>
                            @else
                                <span class="text-ivoire-text/60 text-sm">Aucun avis pour le moment</span>
                            @endif
                        </div>
                    @endif

                    <!-- Stats et CTA -->
                    <div class="flex flex-col items-center gap-4 md:items-end">

                        <!-- CTA -->
                        @auth
                            @if (auth()->user()->client)
                                @php
                                    $hasActive = auth()
                                        ->user()
                                        ->client->bookingRequests()
                                        ->where('bookable_type', get_class($artist))
                                        ->where('bookable_id', $artist->id)
                                        ->whereIn('status', [
                                            'pending',
                                            'accepted',
                                            'awaiting_deposit',
                                            'deposit_paid',
                                            'design_sent',
                                            'confirmed',
                                        ])
                                        ->exists();
                                @endphp

                                @if ($hasActive)
                                    <span class="px-6 py-3 bg-beige-peau/20 text-beige-peau rounded-xl font-semibold">
                                        ✓ Demande déjà en cours
                                    </span>
                                @else
                                    <a href="{{ route('booking-request.form', [$artist->id, $type]) }}"
                                        class="inline-block px-8 py-4 bg-beige-peau text-noir-profond font-bold rounded-lg text-lg hover:bg-beige-peau/90 transition-colors">
                                        📅 Prendre RDV
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('booking-request.form', [$artist->id, $type]) }}"
                                    class="inline-block px-8 py-4 bg-beige-peau text-noir-profond font-bold rounded-lg text-lg hover:bg-beige-peau/90 transition-colors">
                                    📅 Prendre RDV
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-block px-8 py-4 bg-beige-peau text-noir-profond font-bold rounded-lg text-lg hover:bg-beige-peau/90 transition-colors">
                                📅 Prendre RDV
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENU PRINCIPAL -->
        <div class="max-w-4xl mx-auto px-4 py-8 space-y-8">

            <!-- Styles pratiqués -->
            @if ($type === 'tattooer' && isset($artist->styles) && !empty($artist->styles))
                <div class="bg-titane/10 rounded-xl p-6 border border-titane/20">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">🎨 Styles pratiqués</h3>
                    <div class="flex flex-wrap gap-2">
                        @php
                            $currentStyles = is_array($artist->styles)
                                ? $artist->styles
                                : json_decode($artist->styles ?? '[]', true) ?? [];
                        @endphp
                        @foreach ($currentStyles as $style)
                            <span class="px-3 py-1 bg-beige-peau/20 text-beige-peau rounded-full text-sm font-medium">
                                {{ $style }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Bio -->
            @if ($artist->bio)
                <div class="bg-titane/10 rounded-xl p-6 border border-titane/20">
                    <h2 class="text-2xl font-display font-bold text-ivoire-text mb-4">À propos</h2>
                    <p class="text-ivoire-text/80 leading-relaxed whitespace-pre-line">{{ $artist->bio }}</p>
                </div>
            @endif

            <!-- Adresse — CLIQUABLE avec modal Google Maps -->
            @if ($artist->address)
                <div class="bg-titane/10 rounded-xl p-6 border border-titane/20">
                    <h3 class="text-lg font-bold text-ivoire-text mb-3">📍 Adresse</h3>

                    <!-- Bouton adresse cliquable -->
                    <button type="button" @click="$dispatch('open-map-modal')"
                        class="flex items-center gap-2 text-beige-peau hover:text-beige-peau/80 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 12.414a1.5 1.5 0 010-2.122l4.243-4.243a8 8 0 11-11.314 11.314l4.243-4.243z" />
                        </svg>
                        <span class="underline">{{ $artist->address }}</span>
                    </button>

                    <!-- Modal Google Maps -->
                    <div x-data="{ open: false }" @open-map-modal.window="open = true">
                        <div x-show="open" x-cloak
                            class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
                            @click.self="open = false">
                            <div
                                class="bg-noir-profond rounded-2xl border border-titane/30 w-full max-w-lg overflow-hidden">
                                <!-- Header modal -->
                                <div class="flex items-center justify-between p-4 border-b border-titane/30">
                                    <h4 class="text-ivoire-text font-bold">📍 Localisation</h4>
                                    <button @click="open = false" class="text-ivoire-text/60 hover:text-ivoire-text">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- iframe Google Maps -->
                                <div class="h-64">
                                    <iframe
                                        src="https://maps.google.com/maps?q={{ urlencode($artist->address) }}&output=embed"
                                        class="w-full h-full border-0" allowfullscreen loading="lazy"></iframe>
                                </div>

                                <!-- Lien externe -->
                                <div class="p-4 border-t border-titane/30">
                                    <a href="https://maps.google.com/maps?q={{ urlencode($artist->address) }}"
                                        target="_blank" rel="noopener noreferrer"
                                        class="text-beige-peau text-sm hover:underline">
                                        Ouvrir dans Google Maps →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- PORTFOLIO -->
            <div class="space-y-12">
                <h2 class="text-2xl font-display font-bold text-ivoire-text mb-8">Portfolio</h2>

                <!-- Section 1 : Tattoos réalisés -->
                @if (
                    $type === 'tattooer' &&
                        isset($artist) &&
                        method_exists($artist, 'getMedia') &&
                        $artist->getMedia('portfolio')->isNotEmpty())
                    <div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-6 flex items-center gap-2">
                            <span class="text-2xl">🖋️</span>
                            Tattoos réalisés
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($artist->getMedia('portfolio') as $media)
                                <div class="aspect-square rounded-xl overflow-hidden bg-titane/20 cursor-pointer hover:opacity-90 transition-opacity group"
                                    onclick="openLightbox('{{ $media->getUrl() }}')">
                                    <img src="{{ $media->getUrl() }}" alt="Tattoo réalisé"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 2 : Dessins / Sketches -->
                @if (
                    $type === 'tattooer' &&
                        isset($artist) &&
                        method_exists($artist, 'getMedia') &&
                        $artist->getMedia('drawings')->isNotEmpty())
                    <div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-6 flex items-center gap-2">
                            <span class="text-2xl">🎨</span>
                            Dessins & Sketches
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            @foreach ($artist->getMedia('drawings') as $media)
                                <div class="aspect-square rounded-xl overflow-hidden bg-titane/20 cursor-pointer hover:opacity-90 transition-opacity group"
                                    onclick="openLightbox('{{ $media->getUrl() }}')">
                                    <img src="{{ $media->getUrl() }}" alt="Dessin / Sketch"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Section 3 : Avant / Après -->
                @if (
                    $type === 'tattooer' &&
                        isset($artist) &&
                        method_exists($artist, 'getMedia') &&
                        $artist->getMedia('before_after')->isNotEmpty())
                    <div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-6 flex items-center gap-2">
                            <span class="text-2xl">📸</span>
                            Avant / Après
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @php
                                $pairs = collect();

                                // Paires robustes (nouveau format): groupé par pair_id + role
                                $withPairId = $artist
                                    ->getMedia('before_after')
                                    ->filter(function ($m) {
                                        return (string) $m->getCustomProperty('pair_id');
                                    })
                                    ->groupBy(function ($m) {
                                        return (string) $m->getCustomProperty('pair_id');
                                    });

                                foreach ($withPairId as $pairId => $items) {
                                    $before = $items->first(function ($m) {
                                        return $m->getCustomProperty('role') === 'before';
                                    });
                                    $after = $items->first(function ($m) {
                                        return $m->getCustomProperty('role') === 'after';
                                    });
                                    if ($before && $after) {
                                        $pairs->push([$before, $after]);
                                    }
                                }

                                // Fallback legacy: paires par ordre d'upload (2 par 2)
$legacy = $artist
    ->getMedia('before_after')
    ->filter(function ($m) {
        return !(string) $m->getCustomProperty('pair_id');
    })
    ->sortBy('id')
                                    ->values()
                                    ->chunk(2)
                                    ->map(function ($chunk) {
                                        return $chunk->values();
                                    });

                                foreach ($legacy as $pair) {
                                    if ($pair->count() === 2) {
                                        $pairs->push([$pair[0], $pair[1]]);
                                    }
                                }
                            @endphp

                            @foreach ($pairs as $pair)
                                @if (is_array($pair) && count($pair) === 2)
                                    <div class="bg-titane/10 rounded-xl p-4 border border-titane/30">
                                        <!-- Before/After Slider -->
                                        <div
                                            class="relative aspect-video rounded-lg overflow-hidden mb-4 before-after-slider">
                                            <img src="{{ $pair[0]->getUrl() }}"
                                                class="absolute inset-0 w-full h-full object-cover before-image"
                                                alt="Avant">
                                            <img src="{{ $pair[1]->getUrl() }}"
                                                class="absolute inset-0 w-full h-full object-cover after-image"
                                                alt="Après" style="clip-path: inset(0 50% 0 0);">

                                            <!-- Slider -->
                                            <div class="absolute inset-y-0 left-1/2 w-1 bg-white z-10 slider-line"></div>
                                            <div
                                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-12 h-12 bg-white rounded-full flex items-center justify-center cursor-ew-resize slider-handle z-20">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                                </svg>
                                            </div>

                                            <!-- Labels -->
                                            <div
                                                class="absolute top-2 left-2 px-2 py-1 bg-black/60 text-white text-xs rounded backdrop-blur-sm">
                                                AVANT</div>
                                            <div
                                                class="absolute top-2 right-2 px-2 py-1 bg-black/60 text-white text-xs rounded backdrop-blur-sm">
                                                APRÈS</div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Message si portfolio vide -->
                @if (
                    $type === 'tattooer' &&
                        (!isset($artist) ||
                            (method_exists($artist, 'getMedia') &&
                                $artist->getMedia('portfolio')->isEmpty() &&
                                $artist->getMedia('drawings')->isEmpty() &&
                                $artist->getMedia('before_after')->isEmpty())))
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">🎨</div>
                        <h3 class="text-xl font-semibold text-ivoire-text mb-2">Portfolio en construction</h3>
                        <p class="text-ivoire-text/60">Ce tatoueur ajoutera bientôt ses réalisations</p>
                    </div>
                @endif
            </div>

            <!-- AVIS CLIENTS -->
            @if ($type === 'tattooer')
                <div id="reviews" class="bg-titane/10 rounded-xl p-6 border border-titane/20 scroll-mt-20"
                    style="scroll-margin-top: 100px;">
                    <h2 class="text-2xl font-display font-bold text-ivoire-text mb-6">Avis clients</h2>

                    @if (isset($artist->reviews) && $artist->reviews->isNotEmpty())
                        <!-- Statistiques des avis -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <!-- Note moyenne -->
                            <div class="text-center">
                                <div class="text-5xl font-bold text-beige-peau mb-2">
                                    {{ number_format($artist->reviews_avg_rating ?? 0, 1) }}
                                </div>
                                <div class="flex justify-center gap-1 mb-2">
                                    @php
                                        $rating = $artist->reviews_avg_rating ?? 0;
                                        $fullStars = floor($rating);
                                        $hasHalfStar = $rating - $fullStars >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                    @endphp

                                    @for ($i = 0; $i < $fullStars; $i++)
                                        <span class="text-yellow-400 text-2xl">★</span>
                                    @endfor

                                    @if ($hasHalfStar)
                                        <span class="text-yellow-400 text-2xl">☆</span>
                                    @endif

                                    @for ($i = 0; $i < $emptyStars; $i++)
                                        <span class="text-gray-600 text-2xl">☆</span>
                                    @endfor
                                </div>
                                <p class="text-ivoire-text/80">{{ $artist->reviews_count ?? 0 }} avis</p>
                            </div>

                            <!-- Répartition des notes -->
                            <div class="space-y-2">
                                @for ($i = 5; $i >= 1; $i--)
                                    @php
                                        $count = $artist->reviews_count_by_rating[$i] ?? 0;
                                        $percentage =
                                            $artist->reviews_count > 0 ? ($count / $artist->reviews_count) * 100 : 0;
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        <span class="text-ivoire-text w-12">{{ $i }} ★</span>
                                        <div class="flex-1 bg-titane/30 rounded-full h-2 overflow-hidden">
                                            <div class="bg-beige-peau h-full transition-all duration-300"
                                                style="width: {{ $percentage }}%"></div>
                                        </div>
                                        <span class="text-ivoire-text/60 w-12 text-right">{{ $count }}</span>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <!-- Liste des avis -->
                        <div class="space-y-6">
                            @foreach ($artist->reviews ?? [] as $review)
                                <div class="border-b border-titane/20 pb-6 last:border-b-0">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <div class="font-semibold text-ivoire-text">
                                                {{ $review->client->user->name ?? 'Anonyme' }}</div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <div class="flex gap-1">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <span
                                                            class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-600' }} text-sm">★</span>
                                                    @endfor
                                                </div>
                                                <span
                                                    class="text-ivoire-text/60 text-sm">{{ $review->created_at->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        @if ($review->appointment)
                                            <div class="text-xs text-ivoire-text/60 bg-titane/20 px-2 py-1 rounded">
                                                {{ $review->appointment->start_time->format('m/Y') }}
                                            </div>
                                        @endif
                                    </div>

                                    @if ($review->comment)
                                        <p class="text-ivoire-text/80 leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination si nécessaire -->
                        @if (isset($reviews) && method_exists($reviews ?? null, 'links'))
                            {{ $reviews->links() }}
                        @endif
                    @else
                        <div class="text-center py-12">
                            <div class="text-6xl mb-4">💬</div>
                            <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucun avis pour le moment</h3>
                            <p class="text-ivoire-text/60">Soyez le premier à laisser votre avis après votre rendez-vous !
                            </p>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Lightbox simple -->
    <div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center"
        onclick="closeLightbox()">
        <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full">
    </div>

    @push('scripts')
        <script>
            function openLightbox(url) {
                document.getElementById('lightbox-image').src = url;
                document.getElementById('lightbox').classList.remove('hidden');
                document.getElementById('lightbox').classList.add('flex');
            }

            function closeLightbox() {
                document.getElementById('lightbox').classList.add('hidden');
                document.getElementById('lightbox').classList.remove('flex');
            }

            // Before/After Slider functionality
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.before-after-slider').forEach(slider => {
                    const handle = slider.querySelector('.slider-handle');
                    const afterImage = slider.querySelector('.after-image');
                    const sliderLine = slider.querySelector('.slider-line');

                    let isDragging = false;

                    // Mobile-friendly: prevent page scroll while dragging
                    slider.style.touchAction = 'none';
                    handle.style.touchAction = 'none';

                    const updateFromClientX = (clientX) => {
                        const rect = slider.getBoundingClientRect();
                        const x = Math.max(0, Math.min(clientX - rect.left, rect.width));
                        const percent = (x / rect.width) * 100;

                        afterImage.style.clipPath = `inset(0 ${100 - percent}% 0 0)`;
                        handle.style.left = `${percent}%`;
                        sliderLine.style.left = `${percent}%`;
                    };

                    handle.addEventListener('pointerdown', (e) => {
                        isDragging = true;
                        try {
                            handle.setPointerCapture(e.pointerId);
                        } catch (err) {}
                        updateFromClientX(e.clientX);
                        e.preventDefault();
                    });

                    handle.addEventListener('pointermove', (e) => {
                        if (!isDragging) return;
                        updateFromClientX(e.clientX);
                        e.preventDefault();
                    });

                    handle.addEventListener('pointerup', (e) => {
                        isDragging = false;
                        try {
                            handle.releasePointerCapture(e.pointerId);
                        } catch (err) {}
                        e.preventDefault();
                    });

                    handle.addEventListener('pointercancel', () => {
                        isDragging = false;
                    });

                    // Touch support for mobile
                    handle.addEventListener('touchstart', (e) => {
                        isDragging = true;
                        const touch = e.touches[0];
                        updateFromClientX(touch.clientX);
                        e.preventDefault();
                    });

                    handle.addEventListener('touchmove', (e) => {
                        if (!isDragging) return;
                        const touch = e.touches[0];
                        updateFromClientX(touch.clientX);
                        e.preventDefault();
                    });

                    handle.addEventListener('touchend', () => {
                        isDragging = false;
                    });
                });
            });
        </script>
    @endpush
@endsection

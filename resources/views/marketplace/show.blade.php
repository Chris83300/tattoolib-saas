@extends('layouts.app')

@section('title', ($artist->user->pseudo ?? $artist->user->first_name . ' ' . $artist->user->last_name) . ' - ' . ($type
    === 'tattooer' ? 'Tatoueur' : 'Perceur') . ' - Ink&Pik')

@section('content')
    <div class="min-h-screen bg-noir-profond">

        <!-- HERO — Image bannière + infos principales -->
        <div class="relative">
            <!-- Image de bannière -->
            <div class="h-64 md:h-80 bg-gradient-to-br from-titane/40 to-noir-profond relative overflow-hidden">
                @if ($artist->getFirstMediaUrl('banner'))
                    <img src="{{ $artist->getFirstMediaUrl('banner') }}"
                        alt="Bannière de {{ $artist->user->pseudo ?? $artist->user->first_name . ' ' . $artist->user->last_name }}"
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
                        class="w-32 h-32 md:w-36 md:h-36 rounded-full border-4 border-cuivre/60 shadow-xl shadow-cuivre/20 overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
                        @if ($artist->user->getFirstMediaUrl('avatar'))
                            <img src="{{ $artist->user->getFirstMediaUrl('avatar') }}"
                                alt="{{ $artist->user->pseudo ?? $artist->user->first_name . ' ' . $artist->user->last_name }}"
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
                        <h1 class="text-2xl md:text-4xl font-bold text-ivoire-text drop-shadow-lg">
                            {{ $artist->user->pseudo ?? $artist->user->first_name . ' ' . $artist->user->last_name }}
                        </h1>

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
                                    ⏱️ {{ $artist->wait_time_weeks_min }}
                                    semaine{{ $artist->wait_time_weeks_min > 1 ? 's' : '' }}
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

            <!-- Horaires d'ouverture -->
            @if ($artist->working_hours)
                @php
                    $workingHoursData = [];
                    if ($artist->working_hours) {
                        if (is_string($artist->working_hours)) {
                            $workingHoursData = json_decode($artist->working_hours, true, 512) ?: [];
                        } elseif (is_array($artist->working_hours)) {
                            $workingHoursData = $artist->working_hours;
                        }
                    }
                    $daysOrder = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
                @endphp

                <div class="bg-titane/10 rounded-xl p-6 border border-titane/20">
                    <h2 class="text-2xl font-display font-bold text-ivoire-text mb-4 flex items-center gap-2">
                        🕐 Horaires d'ouverture
                    </h2>

                    <!-- Mobile: Carousel -->
                    <div class="md:hidden">
                        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                            @foreach ($daysOrder as $day)
                                @php
                                    $dayData = $workingHoursData[$day] ?? null;
                                    $isOpen = $dayData && !empty($dayData['open']) && !empty($dayData['close']);
                                @endphp

                                <div class="flex-shrink-0 w-32 p-3 bg-noir-profond rounded-lg border border-titane/20">
                                    <div class="text-center">
                                        <div class="font-semibold text-ivoire-text text-sm mb-1">
                                            {{ ucfirst($day) }}
                                        </div>
                                        <div class="text-xs {{ $isOpen ? 'text-vert-succes' : 'text-rouge-alerte' }} mb-1">
                                            {{ $isOpen ? 'Ouvert' : 'Fermé' }}
                                        </div>
                                        @if ($isOpen && $dayData)
                                            <div class="text-ivoire-text/70 text-xs">
                                                <div>{{ $dayData['open'] ?? '' }}</div>
                                                @if ($dayData['break_start'] && $dayData['break_end'])
                                                    <div class="text-amber-warning mt-1">
                                                        🍴 {{ $dayData['break_start'] }}-{{ $dayData['break_end'] }}
                                                    </div>
                                                @endif
                                                <div>{{ $dayData['close'] ?? '' }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Desktop: Grid -->
                    <div class="hidden md:block">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                            @foreach ($daysOrder as $day)
                                @php
                                    $dayData = $workingHoursData[$day] ?? null;
                                    $isOpen = $dayData && !empty($dayData['open']) && !empty($dayData['close']);
                                @endphp

                                <div
                                    class="bg-noir-profond rounded-lg p-4 border border-titane/20 hover:border-beige-peau/30 transition-colors">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="font-semibold text-ivoire-text">
                                            {{ ucfirst($day) }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            @if ($isOpen)
                                                <div class="w-2 h-2 bg-vert-succes rounded-full"></div>
                                                <span class="text-xs text-vert-succes font-medium">Ouvert</span>
                                            @else
                                                <div class="w-2 h-2 bg-rouge-alerte rounded-full"></div>
                                                <span class="text-xs text-rouge-alerte font-medium">Fermé</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($isOpen && $dayData)
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $dayData['open'] }} - {{ $dayData['close'] }}</span>
                                            </div>

                                            @if ($dayData['break_start'] && $dayData['break_end'])
                                                <div class="flex items-center gap-2 text-amber-warning/80 text-xs">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253">
                                                        </path>
                                                    </svg>
                                                    <span>Pause: {{ $dayData['break_start'] }} -
                                                        {{ $dayData['break_end'] }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-ivoire-text/40 text-sm italic">
                                            Fermé aujourd'hui
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Note pour mobile -->
                    <div class="mt-4 text-center md:hidden">
                        <p class="text-ivoire-text/60 text-xs">
                            💡 Faites glisser pour voir tous les jours
                        </p>
                    </div>
                </div>
            @endif

            <!-- Adresse — CLIQUABLE avec modal Google Maps -->
            @if ($artist->address)
                <div class="bg-titane/10 rounded-xl p-6 border border-titane/20">
                    <h3 class="text-lg font-bold text-ivoire-text mb-3">📍 Adresse</h3>

                    <div class="space-y-2">
                        <!-- Adresse principale -->
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <span
                                    class="text-beige-peau hover:text-beige-peau/80 transition-colors cursor-pointer underline"
                                    onclick="window.open('https://maps.google.com/maps?q={{ urlencode($artist->address) }}', '_blank')">
                                    {{ $artist->address }}
                                </span>
                            </div>
                        </div>

                        <!-- Ville et code postal -->
                        @if ($artist->city || $artist->postal_code)
                            <div class="flex items-center gap-2 text-ivoire-text/80">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>{{ $artist->city }}{{ $artist->postal_code ? ', ' . $artist->postal_code : '' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- PORTFOLIO -->
            @if ($portfolio->isNotEmpty())
                <div class="space-y-12">
                    <h2 class="text-2xl font-display font-bold text-ivoire-text mb-8">Portfolio</h2>

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
                                        ->chunk(2);

                                    foreach ($legacy as $pair) {
                                        if ($pair->count() === 2) {
                                            $pairs->push([$pair[0], $pair[1]]);
                                        }
                                    }
                                @endphp

                                @if ($pairs->count() > 0)
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
                                                        alt="Après" style="clip-path: inset(0 50% 100% 100%);">

                                                    <!-- Slider -->
                                                    <div
                                                        class="absolute inset-y-0 left-1/2 w-1 h-full bg-white/20 backdrop-blur-sm z-10 slider-handle cursor-ew-resize">
                                                        <svg
                                                            class="w-6 h-6 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-noir-profond">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M8 9l4-4 4 4 0 11-8 0 4 4 0 0118 0z">
                                                            </path>
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M8 15l6-6 6 6 0 11-8 0 6 6 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                </div>

                                                <!-- Labels -->
                                                <div class="flex justify-between items-center">
                                                    <div
                                                        class="absolute top-2 left-2 px-2 py-1 bg-black/60 text-white text-xs rounded backdrop-blur-sm">
                                                        AVANT
                                                    </div>
                                                    <div
                                                        class="absolute top-2 right-2 px-2 py-1 bg-black/60 text-white text-xs rounded backdrop-blur-sm">
                                                        APRÈS
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="text-center text-ivoire-text/60">
                                        <p class="text-sm">Aucune image avant/après disponible</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
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

        <!-- Labels -->
        <div class="absolute top-2 left-2 px-2 py-1 bg-black/60 text-white text-xs rounded backdrop-blur-sm">
            AVANT</div>
        <div class="absolute top-2 right-2 px-2 py-1 bg-black/60 text-white text-xs rounded backdrop-blur-sm">
            APRÈS</div>
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
        </script>
    @endpush
@endsection

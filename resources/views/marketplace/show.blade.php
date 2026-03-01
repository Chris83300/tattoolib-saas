@extends('layouts.app')

@section('title', ($artist->user->pseudo ?? $artist->user->first_name . ' ' . $artist->user->last_name) . ' - ' . ($type
    === 'tattooer' ? 'Tatoueur' : 'Pierceur') . ' - Ink&Pik')

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
                        class="w-32 h-32 md:w-36 md:h-36 rounded-full border-2 border-titane/30 shadow-lg shadow-titane/20 overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
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
                        <h1 class="text-2xl md:text-4xl mb-2 font-bold font-display text-ivoire-text drop-shadow-lg">
                            {{ $artist->user->pseudo ?? $artist->user->first_name . ' ' . $artist->user->last_name }}
                        </h1>

                        <!-- Nom du studio -->
                        @if ($artist->studio_name)
                            <p class="text-lg md:text-lg text-beige-peau mb-2 drop-shadow">
                                {{ $artist->studio_name }}
                            </p>
                        @endif

                        <!-- Téléphone -->
                        @if ($artist->studio_id && $artist->studio)
                            <!-- Artiste de studio : afficher le téléphone du studio -->
                            <p class="text-lg md:text-lg text-titane mb-2 drop-shadow">
                                <a href="tel:{{ $artist->studio->phone }}"
                                    class="md:no-underline hover:text-cuivre transition-colors">
                                    {{ $artist->studio->phone }}
                                </a>
                            </p>
                        @elseif ($artist->phone)
                            <!-- Artiste indépendant : afficher son téléphone personnel -->
                            <p class="text-lg md:text-lg text-titane mb-2 drop-shadow">
                                <a href="tel:{{ $artist->phone }}"
                                    class="md:no-underline hover:text-cuivre transition-colors">
                                    {{ $artist->phone }}
                                </a>
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


                            @if (isset($artist->years_of_experience) && $artist->years_of_experience)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    {{ $artist->years_of_experience }} ans d'expérience
                                </span>
                            @endif

                            @if (isset($artist->wait_time_weeks_min) &&
                                    isset($artist->wait_time_weeks_max) &&
                                    $artist->wait_time_weeks_min &&
                                    $artist->wait_time_weeks_max)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    {{ $artist->wait_time_weeks_min }} à {{ $artist->wait_time_weeks_max }} semaines
                                    d'attente
                                </span>
                            @elseif (isset($artist->wait_time_weeks_min) && $artist->wait_time_weeks_min)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    {{ $artist->wait_time_weeks_min }}
                                    semaine d'attente{{ $artist->wait_time_weeks_min > 1 ? 's' : '' }}
                                </span>
                            @endif

                            @if (isset($artist->minimum_price) && $artist->minimum_price)
                                <span class="px-3 py-1 bg-titane/30 text-ivoire-text/80 rounded-full text-sm">
                                    À partir de {{ number_format($artist->minimum_price, 2, ',', ' ') }} €
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
                    @if (in_array($type, ['tattooer', 'piercer']))
                        <div class="flex flex-col items-center gap-2 border-b border-titane/20 pb-2  md:items-end">
                            <!-- Étoiles -->
                            <div class="flex items-center gap-1">
                                @php
                                    $rating = $allReviews->where('is_visible', true)->avg('rating') ?? 0;
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
                            @if ($allReviews->where('is_visible', true)->count() > 0)
                                <a href="#reviews"
                                    onclick="document.getElementById('reviews').scrollIntoView({behavior: 'smooth', block: 'start'}); return false;"
                                    class="text-beige-peau hover:text-beige-peau/80 text-sm underline transition-colors cursor-pointer">
                                    Voir les {{ $allReviews->where('is_visible', true)->count() }} avis
                                </a>
                            @else
                                <span class="text-ivoire-text/60 text-sm">Aucun avis pour le moment</span>
                            @endif
                        </div>
                    @endif

                    <!-- Stats et CTA -->
                    <div class="flex flex-col items-center gap-4 mb-4 md:items-end">

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
                                    <span class="px-6 py-3 bg-beige-peau/20 text-beige-peau rounded-full font-semibold">
                                        ✓ Demande déjà en cours
                                    </span>
                                @else
                                    <a href="{{ route('booking-request.form', [$artist->id, $type]) }}"
                                        class="inline-block px-8 py-4 bg-beige-peau btn-shadow text-noir-profond font-bold rounded-full text-lg hover:bg-beige-peau/90 transition-colors">
                                        📅 Prendre RDV
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('booking-request.form', [$artist->id, $type]) }}"
                                    class="inline-block px-8 py-4 bg-beige-peau btn-shadow text-noir-profond font-bold rounded-full text-lg hover:bg-beige-peau/90 transition-colors">
                                    📅 Prendre RDV
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-block px-8 py-4 bg-beige-peau btn-shadow text-noir-profond font-bold rounded-full text-lg hover:bg-beige-peau/90 transition-colors">
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
            @php
                $currentStyles = is_array($artist->styles)
                    ? $artist->styles
                    : json_decode($artist->styles ?? '[]', true) ?? [];
                $customStyles = is_array($artist->custom_styles ?? null)
                    ? $artist->custom_styles
                    : json_decode($artist->custom_styles ?? '[]', true) ?? [];
                // Fusionner prédéfinis + personnalisés, retirer "Autres" (c'est juste le flag checkbox)
$displayStyles = array_filter(
    array_unique(array_merge($currentStyles, $customStyles)),
    fn($s) => $s !== 'Autres' && trim($s) !== '',
                );
            @endphp

            @if ($type === 'tattooer' && !empty($displayStyles))
                <div class="bg-titane/10 rounded-xl p-6 border border-titane/20">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4"> Styles pratiqués</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($displayStyles as $style)
                            <span class="px-3 py-1 bg-beige-peau/10 text-beige-peau rounded-full text-sm font-medium">
                                {{ $style }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($type === 'piercer')
                {{-- Grille tarifaire --}}
                @php
                    $pricingGrid = method_exists($artist, 'getPricingGrid') ? $artist->getPricingGrid() : [];
                @endphp
                @if (!empty($pricingGrid))
                    <div class="bg-titane/10 rounded-xl p-6 border border-titane/20">
                        <h3 class="text-xl font-bold text-ivoire-text mb-4"> Grille tarifaire des Piercings</h3>
                        <div class="space-y-2">
                            @foreach ($pricingGrid as $entry)
                                <div class="flex items-center justify-between py-2 border-b border-titane/10 last:border-0">
                                    <span
                                        class="text-ivoire-text border bg-beige-peau/5 border-beige-peau/40 shadow-sm shadow-beige-peau/20 rounded-full px-3 py-1 text-sm">{{ $entry['type'] ?? '' }}</span>
                                    <span
                                        class="text-beige-peau font-semibold">{{ number_format($entry['price'] ?? 0, 0, ',', ' ') }}
                                        €</span>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 border-t border-titane/20 pt-4">
                            <p
                                class="text-md text-ivoire-text text-center align-middle bg-beige-peau/5 border border-beige-peau/20 shadow-sm shadow-beige-peau/20 rounded-full px-3 py-1">
                                {{ $artist->custom_pricing_note }}</p>
                        </div>
                    </div>

                @endif
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
                                    class="bg-noir-profond rounded-xl p-4 border border-titane/20 hover:border-beige-peau/30 shadow-md shadow-titane/10 transition-colors">
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
            @if (
                $portfolio->isNotEmpty() ||
                    (isset($drawings) && $drawings->isNotEmpty()) ||
                    (isset($beforeAfter) && $beforeAfter->isNotEmpty()))
                <div class="space-y-12">
                    <h2
                        class="text-4xl font-display font-bold text-beige-peau flex justify-center underline underline-offset-8 mt-16 mb-8">
                        Portfolio</h2>

                    <!-- Section 1 : Réalisations -->
                    @if ($portfolio->isNotEmpty())
                        <div>
                            <h3 class="text-2xl font-bold text-cuivre justify-center mb-6 flex items-center gap-2">
                                Réalisations
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($portfolio as $media)
                                    <div class="aspect-square rounded-xl overflow-hidden bg-titane/20 cursor-pointer hover:opacity-90 transition-opacity group"
                                        onclick="openLightbox('{{ $media->getUrl() }}')">
                                        <img src="{{ $media->getUrl() }}" data-full="{{ $media->getUrl() }}"
                                            alt="Tatouage"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300 portfolio-photo">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Section 2 : Dessins / Sketches -->
                    @if ($type === 'tattooer' && isset($drawings) && $drawings->isNotEmpty())
                        <div>
                            <h3 class="text-2xl font-bold text-cuivre justify-center mb-6 flex items-center gap-2">
                                Flash Dispo
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($drawings as $media)
                                    <div class="aspect-square rounded-xl overflow-hidden bg-titane/20 cursor-pointer hover:opacity-90 transition-opacity group"
                                        onclick="openLightbox('{{ $media->getUrl() }}')">
                                        <img src="{{ $media->getUrl() }}" data-full="{{ $media->getUrl() }}"
                                            alt="Dessin / Sketch"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300 portfolio-photo">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Section 3 : Avant / Après -->
                    @if ($type === 'tattooer' && isset($beforeAfter) && $beforeAfter->isNotEmpty())
                        <div>
                            <h3 class="text-2xl font-bold text-cuivre justify-center mb-6 flex items-center gap-2">
                                Avant / Après
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @php
                                    $beforeAfterSorted = $beforeAfter->sortBy('id')->values();
                                    $pairs = [];

                                    // Créer des paires (2 par 2) - garder les objets Media
                                    for ($i = 0; $i < $beforeAfterSorted->count(); $i += 2) {
                                        if ($beforeAfterSorted->has($i + 1)) {
                                            $pairs[] = [$beforeAfterSorted[$i], $beforeAfterSorted[$i + 1]];
                                        }
                                    }
                                @endphp

                                @if (!empty($pairs))
                                    @foreach ($pairs as $pair)
                                        @if (is_array($pair) && count($pair) === 2)
                                            <div class="bg-titane/10 rounded-xl p-4 border border-titane/30">
                                                <!-- Before/After Slider -->
                                                <div
                                                    class="relative aspect-video rounded-lg overflow-hidden mb-4 before-after-slider">
                                                    <img src="{{ $pair[0]->getUrl() }}"
                                                        class="absolute inset-0 w-full h-full object-contain before-image"
                                                        alt="Avant">
                                                    <img src="{{ $pair[1]->getUrl() }}"
                                                        class="absolute inset-0 w-full h-full object-contain after-image"
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
                                    @if ($beforeAfter->isNotEmpty())
                                        <!-- Afficher les images individuellement si nombre impair -->
                                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                            @foreach ($beforeAfter as $media)
                                                <div class="aspect-square rounded-xl overflow-hidden bg-titane/20 cursor-pointer hover:opacity-90 transition-opacity group"
                                                    onclick="openLightbox('{{ $media->getUrl() }}')">
                                                    <img src="{{ $media->getUrl() }}" alt="Avant/Après"
                                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-ivoire-text/50 text-center py-8">Aucune image avant/après disponible
                                        </p>
                                    @endif
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

    <!-- Section Avis -->
    <section id="reviews" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-bold text-cuivre justify-center mb-6 flex items-center gap-2">
            Avis clients
            @if ($allReviews->where('is_visible', true)->count() > 0)
                <span class="text-sm font-normal text-titane">
                    ({{ number_format($allReviews->where('is_visible', true)->avg('rating'), 1) }}/5 —
                    {{ $allReviews->where('is_visible', true)->count() }} avis)
                </span>
            @endif
        </h2>

        @forelse ($allReviews->where('is_visible', true)->sortByDesc('created_at') as $review)
            <div class="bg-gris-fonde rounded-xl p-4 mb-3">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-2">
                        <div class="flex">
                            @for ($i = 1; $i <= 5; $i++)
                                <span
                                    class="{{ $i <= $review->rating ? 'text-ambre-warning' : 'text-titane/30' }}">&#9733;</span>
                            @endfor
                        </div>
                        <span class="text-sm font-semibold text-ivoire-text">
                            {{ $review->client_pseudo ?: ($review->user_pseudo ?: $review->first_name . ' ' . $review->last_name ?? 'Client') }}
                        </span>
                    </div>
                    <span class="text-xs text-titane">{{ $review->created_at->diffForHumans() }}</span>
                </div>
                @if ($review->comment)
                    <p class="text-sm text-ivoire-text/80">{{ $review->comment }}</p>
                @endif
            </div>
        @empty
            <p class="text-titane text-sm text-center py-6">Aucun avis pour le moment</p>
        @endforelse
    </section>

    <!-- Lightbox avancée -->
    <div id="lightbox"
        class="fixed inset-0 bg-black/90 z-50 opacity-0 invisible transition-all duration-300 flex items-center justify-center p-4">
        <div class="relative max-w-4xl max-h-full">
            <!-- Bouton fermer -->
            <button id="close-lightbox"
                class="absolute -top-12 right-0 text-white hover:text-beige-peau transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>

            <!-- Bouton précédent -->
            <button id="prev-photo"
                class="hidden md:flex absolute left-4 top-1/2 -translate-y-1/2 text-white hover:text-beige-peau transition-colors bg-black/50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Bouton suivant -->
            <button id="next-photo"
                class="hidden md:flex absolute right-4 top-1/2 -translate-y-1/2 text-white hover:text-beige-peau transition-colors bg-black/50 rounded-full p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <!-- Image -->
            <img id="lightbox-image" src="" alt="" class="max-w-full max-h-full rounded-lg">

            <!-- Compteur de photos -->
            <div id="photo-counter"
                class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-sm bg-black/50 px-3 py-1 rounded-full">
                1 / 1
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lightbox = document.getElementById('lightbox');
                const lightboxImage = document.getElementById('lightbox-image');
                const closeLightbox = document.getElementById('close-lightbox');
                const prevPhoto = document.getElementById('prev-photo');
                const nextPhoto = document.getElementById('next-photo');
                const photoCounter = document.getElementById('photo-counter');
                const portfolioPhotos = document.querySelectorAll('.portfolio-photo');

                let currentPhotoIndex = 0;
                const photosArray = Array.from(portfolioPhotos);

                // Fonction pour afficher une photo spécifique
                function showPhoto(index) {
                    if (index < 0) index = photosArray.length - 1;
                    if (index >= photosArray.length) index = 0;

                    currentPhotoIndex = index;
                    const photo = photosArray[index];
                    const fullUrl = photo.getAttribute('data-full');

                    lightboxImage.src = fullUrl;
                    photoCounter.textContent = `${index + 1} / ${photosArray.length}`;

                    // Afficher/masquer les boutons de navigation si une seule photo
                    if (photosArray.length <= 1) {
                        prevPhoto.style.display = 'none';
                        nextPhoto.style.display = 'none';
                        photoCounter.style.display = 'none';
                    } else {
                        // Sur mobile, les flèches sont cachées par CSS (hidden md:flex)
                        // Sur desktop, on les affiche
                        if (window.innerWidth >= 768) {
                            prevPhoto.style.display = 'flex';
                            nextPhoto.style.display = 'flex';
                        }
                        photoCounter.style.display = 'block';
                    }
                }

                // Fonction openLightbox pour compatibilité avec les onclick existants
                window.openLightbox = function(url) {
                    const index = photosArray.findIndex(photo => photo.getAttribute('data-full') === url);
                    if (index !== -1) {
                        showPhoto(index);
                    } else {
                        // Si la photo n'est pas dans le tableau (cas rare), on l'affiche directement
                        lightboxImage.src = url;
                        photoCounter.style.display = 'none';
                        prevPhoto.style.display = 'none';
                        nextPhoto.style.display = 'none';
                    }
                    lightbox.classList.remove('opacity-0', 'invisible');
                    lightbox.classList.add('opacity-100', 'visible');
                    document.body.style.overflow = 'hidden';
                };

                // Navigation photo précédente
                function prevPhotoFunc() {
                    showPhoto(currentPhotoIndex - 1);
                }

                // Navigation photo suivante
                function nextPhotoFunc() {
                    showPhoto(currentPhotoIndex + 1);
                }

                // Fermer la lightbox
                function closeLightboxFunc() {
                    lightbox.classList.add('opacity-0', 'invisible');
                    lightbox.classList.remove('opacity-100', 'visible');
                    document.body.style.overflow = 'auto';
                }

                // Fonction closeLightbox pour compatibilité
                window.closeLightbox = closeLightboxFunc;

                // Événements
                closeLightbox.addEventListener('click', closeLightboxFunc);
                prevPhoto.addEventListener('click', prevPhotoFunc);
                nextPhoto.addEventListener('click', nextPhotoFunc);

                // Fermer au clic en dehors de l'image
                lightbox.addEventListener('click', function(e) {
                    if (e.target === lightbox) {
                        closeLightboxFunc();
                    }
                });

                // Navigation au clavier
                document.addEventListener('keydown', function(e) {
                    if (lightbox.classList.contains('invisible')) return;

                    switch (e.key) {
                        case 'Escape':
                            closeLightboxFunc();
                            break;
                        case 'ArrowLeft':
                            prevPhotoFunc();
                            break;
                        case 'ArrowRight':
                            nextPhotoFunc();
                            break;
                    }
                });

                // Navigation avec swipe/touch (mobile)
                let touchStartX = 0;
                let touchEndX = 0;

                lightbox.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].screenX;
                });

                lightbox.addEventListener('touchend', function(e) {
                    touchEndX = e.changedTouches[0].screenX;
                    handleSwipe();
                });

                function handleSwipe() {
                    const swipeThreshold = 50;
                    const diff = touchStartX - touchEndX;

                    if (Math.abs(diff) > swipeThreshold) {
                        if (diff > 0) {
                            // Swipe vers la gauche = photo suivante
                            nextPhotoFunc();
                        } else {
                            // Swipe vers la droite = photo précédente
                            prevPhotoFunc();
                        }
                    }
                }

                // Before/After Slider (code existant préservé)
                const sliders = document.querySelectorAll('.before-after-slider');

                sliders.forEach(slider => {
                    const handle = slider.querySelector('.slider-handle');
                    const afterImage = slider.querySelector('.after-image');

                    if (!handle || !afterImage) return;

                    let isDragging = false;

                    function updateSlider(x) {
                        const rect = slider.getBoundingClientRect();
                        const percent = Math.max(0, Math.min(100, ((x - rect.left) / rect.width) * 100));

                        handle.style.left = percent + '%';
                        afterImage.style.clipPath = `inset(0 ${100 - percent}% 0 0)`;
                    }

                    handle.addEventListener('mousedown', () => isDragging = true);
                    document.addEventListener('mouseup', () => isDragging = false);
                    document.addEventListener('mousemove', (e) => {
                        if (isDragging) updateSlider(e.clientX);
                    });

                    // Touch support
                    handle.addEventListener('touchstart', () => isDragging = true);
                    document.addEventListener('touchend', () => isDragging = false);
                    document.addEventListener('touchmove', (e) => {
                        if (isDragging) updateSlider(e.touches[0].clientX);
                    });

                    // Initialize at 50%
                    updateSlider(slider.getBoundingClientRect().left + slider.getBoundingClientRect().width /
                        2);
                });
            });
        </script>
    @endpush
@endsection

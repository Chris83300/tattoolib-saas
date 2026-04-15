<div class="space-y-6">

    {{-- ═══ BARRE DE RECHERCHE ═══ --}}
    <div class="flex flex-col sm:flex-row gap-3">

        {{-- Recherche textuelle (temps réel) --}}
        <div class="relative flex-1">
            <label for="search-input" class="sr-only">Rechercher un artiste</label>
            <div class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-titane pointer-events-none" role="img"
                aria-label="Icône recherche">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0" />
                </svg>
            </div>
            <input id="search-input" wire:model.live.debounce.400ms="search" type="text"
                placeholder="Nom, style, ville..." aria-label="Rechercher un artiste par nom, style ou ville"
                aria-describedby="search-help" autocomplete="off"
                class="w-full pl-10 pr-4 py-3 bg-noir-profond border border-titane/30 rounded-xl
                       text-ivoire-text placeholder-ivoire-text/40
                       focus:outline-none focus:border-beige-peau focus:ring-1 focus:ring-beige-peau transition">
            <div id="search-help" class="sr-only">
                Tapez un nom de tatoueur, style de tatouage ou ville pour trouver des artistes
            </div>
        </div>

        {{-- Filtre ville --}}
        <div class="relative">
            <label for="city-input" class="sr-only">Filtrer par ville</label>
            <div class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-titane pointer-events-none" role="img"
                aria-label="Icône localisation">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <input id="city-input" wire:model.live.debounce.400ms="city" type="text" placeholder="Ville..."
                aria-label="Filtrer les artistes par ville" autocomplete="address-level2"
                class="pl-9 pr-4 py-3 bg-noir-profond border border-titane/30 rounded-xl
                       text-ivoire-text placeholder-ivoire-text/40
                       focus:outline-none focus:border-beige-peau focus:ring-1 focus:ring-beige-peau
                       transition w-full sm:w-48">
        </div>
    </div>

    {{-- FILTRES TYPE --}}
    <div class="flex flex-wrap gap-2" role="group" aria-label="Filtrer par type d'artiste">
        @foreach ([
        'all' => ['label' => 'Tous', 'icon' => ''],
        'tattooer' => ['label' => 'Tatoueurs', 'icon' => ''],
        'piercer' => ['label' => 'Pierceurs', 'icon' => ''],
        'studio' => ['label' => 'Studios', 'icon' => ''],
    ] as $value => $item)
            <button wire:click="$set('type', '{{ $value }}')" type="button" role="radio"
                aria-checked="{{ $type === $value ? 'true' : 'false' }}"
                aria-label="Filtrer pour afficher {{ $item['label'] }}"
                class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium transition border
                   {{ $type === $value
                       ? 'bg-beige-peau text-noir-profond border-beige-peau'
                       : 'bg-noir-profond text-ivoire-text/70 border-titane/30 hover:border-beige-peau/50 hover:text-beige-peau' }}">
                @if ($value === 'all')
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM13 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2h-2z" />
                    </svg>
                @elseif ($value === 'tattooer')
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                    </svg>
                @elseif ($value === 'piercer')
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.293l-3-3a1 1 0 00-1.414 1.414L10.586 9.5H7a1 1 0 100 2h3.586l-1.293 1.293a1 1 0 101.414 1.414l3-3a1 1 0 000-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                @else
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                    </svg>
                @endif
                {{ $item['label'] }}
            </button>
        @endforeach
    </div>

    {{-- ═══ FILTRES AVANCÉS ═══ --}}
    <div x-data="{ open: false }" class="space-y-3">
        <button @click="open = !open" type="button" aria-expanded="false" :aria-expanded="open"
            aria-controls="advanced-filters"
            class="flex items-center gap-2 text-sm text-ivoire-text/60 hover:text-beige-peau transition">
            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none"
                stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
            </svg>
            Filtres avancés
            @php
                $activeFilters = count(
                    array_filter([
                        !empty($styles),
                        !empty($piercings),
                        $minPrice !== '',
                        $maxPrice !== '',
                        $proOnly,
                        $certifiedOnly,
                    ]),
                );
            @endphp
            @if ($activeFilters > 0)
                <span class="px-1.5 py-0.5 bg-beige-peau/20 text-beige-peau text-xs rounded-full"
                    aria-label="{{ $activeFilters }} filtres actifs">
                    {{ $activeFilters }}
                </span>
            @endif
        </button>

        <div x-show="open" x-transition
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 p-4
                    bg-gris-fonde/50 rounded-xl border border-titane/20"
            id="advanced-filters">

            {{-- Styles tatouage (si type = tattooer ou all) --}}
            @if (in_array($type, ['all', 'tattooer']) && !empty($availableStyles))
                <div class="col-span-full">
                    <p class="text-xs font-medium text-titane mb-2 uppercase tracking-wide">
                        Styles de tatouage
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($availableStyles as $style)
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" wire:model.live="styles" value="{{ $style }}"
                                    class="rounded border-titane/40 bg-noir-profond text-beige-peau focus:ring-beige-peau/40">
                                <span class="text-sm text-ivoire-text/80 hover:text-beige-peau transition">
                                    {{ $style }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Types piercing (si type = piercer ou all) --}}
            @if (in_array($type, ['all', 'piercer']) && !empty($availablePiercings))
                <div class="col-span-full">
                    <p class="text-xs font-medium text-titane mb-2 uppercase tracking-wide">
                        Types de piercing
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($availablePiercings as $pType)
                            <label class="flex items-center gap-1.5 cursor-pointer">
                                <input type="checkbox" wire:model.live="piercings" value="{{ $pType }}"
                                    class="rounded border-titane/40 bg-noir-profond text-beige-peau focus:ring-beige-peau/40">
                                <span class="text-sm text-ivoire-text/80 hover:text-beige-peau transition">
                                    {{ $pType }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Prix (uniquement pour artistes, pas studios) --}}
            @if ($type !== 'studio')
                <div>
                    <label class="block text-xs font-medium text-titane mb-1 uppercase tracking-wide">
                        Prix min (€)
                    </label>
                    <input wire:model.live.debounce.500ms="minPrice" type="number" min="0" placeholder="0"
                        class="w-full bg-noir-profond border border-titane/30 rounded-lg px-3 py-2 text-sm
                              text-ivoire-text placeholder-ivoire-text/40
                              focus:outline-none focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                </div>

                <div>
                    <label class="block text-xs font-medium text-titane mb-1 uppercase tracking-wide">
                        Prix max (€)
                    </label>
                    <input wire:model.live.debounce.500ms="maxPrice" type="number" min="0"
                        placeholder="Illimité"
                        class="w-full bg-noir-profond border border-titane/30 rounded-lg px-3 py-2 text-sm
                              text-ivoire-text placeholder-ivoire-text/40
                              focus:outline-none focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                </div>
            @endif

            {{-- Options booléennes --}}
            <div class="space-y-2">
                @if (in_array($type, ['all', 'tattooer']))
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" wire:model.live="proOnly"
                            class="rounded border-titane/40 bg-noir-profond text-beige-peau focus:ring-beige-peau/40">
                        <span class="text-sm text-ivoire-text/80">
                            ⭐ Plan PRO uniquement
                        </span>
                    </label>
                @endif

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="certifiedOnly"
                        class="rounded border-titane/40 bg-noir-profond text-beige-peau focus:ring-beige-peau/40">
                    <span class="text-sm text-ivoire-text/80">
                        ✓ Certifiés conformes uniquement
                    </span>
                </label>
            </div>
        </div>
    </div>

    {{-- ═══ BARRE DE RÉSULTATS + TRI ═══ --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <p class="text-sm text-ivoire-text/60">
                <span class="font-semibold text-beige-peau">{{ $results['total'] }}</span>
                {{ $results['total'] > 1 ? 'résultats' : 'résultat' }}
            </p>

            @if (
                $search ||
                    $city ||
                    !empty($styles) ||
                    !empty($piercings) ||
                    $minPrice !== '' ||
                    $maxPrice !== '' ||
                    $proOnly ||
                    $certifiedOnly)
                <button wire:click="resetFilters"
                    class="text-xs text-rouge-alerte hover:text-rouge-alerte/80 underline transition">
                    Réinitialiser
                </button>
            @endif
        </div>

        <select wire:model.live="sortBy"
            class="text-sm bg-noir-profond border border-titane/30 rounded-lg px-3 py-2
                       text-ivoire-text focus:outline-none focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
            aria-label="Trier les résultats">
            <option value="avis_first" aria-label="Trier par avis">Par avis</option>
            <option value="price_asc" aria-label="Trier par prix croissant">Prix croissant</option>
            <option value="newest" aria-label="Trier par plus récents">Plus récents</option>
        </select>
    </div>

    {{-- ═══ GRILLE DE RÉSULTATS ═══ --}}
    <div wire:loading.class="opacity-50 pointer-events-none"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 transition-opacity">

        @forelse ($results['items'] as $item)
            @if ($item->_type === 'tattooer')
                @include('marketplace.partials.tattooer-card', ['tattooer' => $item])
            @elseif ($item->_type === 'piercer')
                @include('marketplace.partials.piercer-card', ['piercer' => $item])
            @elseif ($item->_type === 'studio')
                @include('marketplace.partials.studio-card', ['studio' => $item])
            @endif
        @empty
            <div class="col-span-full py-16 text-center">
                <p class="text-5xl mb-4">🔍</p>
                <p class="text-ivoire-text/60 font-medium text-lg mb-2">
                    Aucun résultat pour ces critères
                </p>
                <button wire:click="resetFilters" class="text-sm text-beige-peau hover:underline transition">
                    Réinitialiser les filtres
                </button>
            </div>
        @endforelse
    </div>

    {{-- Indicateur chargement --}}
    <div wire:loading class="flex justify-center py-4">
        <div class="w-6 h-6 border-2 border-beige-peau border-t-transparent rounded-full animate-spin"></div>
    </div>

    {{-- ═══ PAGINATION ═══ --}}
    @if ($results['lastPage'] > 1)
        <div class="flex items-center justify-center gap-2 pt-4 flex-wrap">
            @if ($results['currentPage'] > 1)
                <button wire:click="previousPage"
                    class="px-3 py-2 rounded-lg text-sm bg-noir-profond text-ivoire-text
                       border border-titane/30 hover:border-beige-peau transition">
                    ←
                </button>
            @endif

            @for ($p = max(1, $results['currentPage'] - 2); $p <= min($results['lastPage'], $results['currentPage'] + 2); $p++)
                <button wire:click="gotoPage({{ $p }})"
                    class="w-9 h-9 rounded-lg text-sm font-medium transition
                   {{ $results['currentPage'] === $p
                       ? 'bg-beige-peau text-noir-profond font-bold'
                       : 'bg-noir-profond text-ivoire-text border border-titane/30 hover:border-beige-peau' }}">
                    {{ $p }}
                </button>
            @endfor

            @if ($results['currentPage'] < $results['lastPage'])
                <button wire:click="nextPage"
                    class="px-3 py-2 rounded-lg text-sm bg-noir-profond text-ivoire-text
                       border border-titane/30 hover:border-beige-peau transition">
                    →
                </button>
            @endif
        </div>
    @endif
</div>

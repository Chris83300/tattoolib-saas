@extends('layouts.app')

@section('title', 'Marketplace - Trouver un artiste')

@section('content')
    <!-- Hero Section Marketplace -->
    <section class="bg-noir-profond py-16 px-4">
        <div class="container-custom px-4">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-display font-bold text-beige-peau mb-6">
                    Trouvez l'artiste<br>
                    <span class="text-titane">fait pour vous</span>
                </h1>
                <p class="text-xl text-ivoire-text/70 mb-8 max-w-2xl mx-auto">
                    Des artistes vérifiés et professionnels près de chez vous
                </p>

                <!-- Stats rapides -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto mb-12">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="total-artists">-</div>
                        <div class="text-ivoire-text/60 text-sm">Artistes</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="verified-artists">-</div>
                        <div class="text-ivoire-text/60 text-sm">Vérifiés</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="pro-artists">-</div>
                        <div class="text-ivoire-text/60 text-sm">Pro</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-beige-peau" id="total-appointments">-</div>
                        <div class="text-ivoire-text/60 text-sm">Rendez-vous</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Recherche et Filtres -->
    <section class="bg-gris-fonde py-8 px-4 border-b border-titane/20">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <form id="search-form" class="space-y-4">
                    <!-- Barre de recherche principale -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1 relative">
                            <input type="text" name="search" id="search-input"
                                placeholder="Rechercher par pseudo, nom, ville, région..."
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:outline-none focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            <!-- Suggestions de recherche dynamiques -->
                            <div id="search-suggestions"
                                class="absolute top-full left-0 right-0 bg-noir-profond border border-titane/30 rounded-lg shadow-lg mt-1 hidden z-50 max-h-60 overflow-y-auto">
                                <!-- Les suggestions seront chargées ici -->
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <x-ui.button type="submit" variant="primary" size="lg">
                                Rechercher
                            </x-ui.button>
                            <x-ui.button type="button" id="toggle-filters" variant="secondary" size="lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                    </path>
                                </svg>
                            </x-ui.button>
                        </div>
                    </div>

                    <!-- Filtres avancés (cachés par défaut) -->
                    <div id="advanced-filters" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Type d'artiste -->
                            <div>
                                <label class="block text-beige-peau text-sm mb-2">Type d'artiste</label>
                                <select name="artisan_type"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    <option value="">Tous</option>
                                    <option value="tattooer"
                                        {{ ($filters['artisan_type'] ?? '') === 'tattooer' ? 'selected' : '' }}>Tatoueurs
                                    </option>
                                    <option value="piercer"
                                        {{ ($filters['artisan_type'] ?? '') === 'piercer' ? 'selected' : '' }}>Pierceurs
                                    </option>
                                    <option value="bodemodeur"
                                        {{ ($filters['artisan_type'] ?? '') === 'bodemodeur' ? 'selected' : '' }}>Bodemodeurs
                                    </option>
                                    <option value="studio"
                                        {{ ($filters['artisan_type'] ?? '') === 'studio' ? 'selected' : '' }}>Studios
                                    </option>
                                </select>
                            </div>

                            <!-- Styles -->
                            <div>
                                <label class="block text-beige-peau text-sm mb-2">Styles</label>
                                <select name="styles" multiple
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau"
                                    size="4">
                                </select>
                            </div>

                            <!-- Région -->
                            <div>
                                <label class="block text-beige-peau text-sm mb-2">Région</label>
                                <select name="region"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    <option value="">Toutes</option>
                                </select>
                            </div>

                            <!-- Tri -->
                            <div>
                                <label class="block text-beige-peau text-sm mb-2">Tri</label>
                                <select name="sort"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                </select>
                            </div>
                        </div>

                        <!-- Options supplémentaires -->
                        <div class="flex items-center gap-4 mt-4">
                            <label class="flex items-center text-ivoire-text/70">
                                <input type="checkbox" name="verified_only" class="mr-2">
                                Artistes vérifiés uniquement
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    @if (($filters['artisan_type'] ?? '') === 'studio')
    <!-- Studios -->
    <section class="bg-noir-profond py-12 px-4">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-display font-bold text-beige-peau mb-4">
                        Studios de tatouage
                    </h2>
                    <p class="text-ivoire-text/70">
                        {{ $studios->count() }} studio{{ $studios->count() > 1 ? 's' : '' }} référencé{{ $studios->count() > 1 ? 's' : '' }}
                    </p>
                </div>

                @if ($studios->isEmpty())
                    <p class="text-center text-titane py-12">Aucun studio trouvé pour ces critères.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($studios as $studio)
                            <div class="bg-gris-fonde rounded-2xl border border-titane/20 overflow-hidden hover:border-beige-peau/40 transition-all">
                                {{-- Cover --}}
                                <div class="h-40 bg-gradient-to-br from-beige-peau/20 via-titane/30 to-noir-profond relative overflow-hidden">
                                    @if ($studio->getFirstMediaUrl('cover'))
                                        <img src="{{ $studio->getFirstMediaUrl('cover') }}" alt="{{ $studio->name }}"
                                             class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/70 to-transparent"></div>
                                    @endif
                                    {{-- Logo --}}
                                    <div class="absolute bottom-0 left-4 translate-y-1/2 w-16 h-16 rounded-xl border-2 border-titane/30 bg-gris-fonde overflow-hidden flex items-center justify-center shadow-lg">
                                        @if ($studio->getFirstMediaUrl('logo'))
                                            <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="{{ $studio->name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl">🏢</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-4 pt-10">
                                    <h3 class="text-beige-peau font-semibold text-lg mb-1">{{ $studio->name }}</h3>
                                    @if ($studio->city)
                                        <p class="text-titane text-sm mb-2">📍 {{ $studio->city }}{{ $studio->postal_code ? ' (' . $studio->postal_code . ')' : '' }}</p>
                                    @endif
                                    @if ($studio->description || $studio->bio)
                                        <p class="text-ivoire-text/70 text-sm mb-3 line-clamp-2">
                                            {{ \Illuminate\Support\Str::limit($studio->description ?? $studio->bio, 100) }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-titane mb-4">
                                        👥 {{ $studio->studioArtists->count() }} artiste{{ $studio->studioArtists->count() > 1 ? 's' : '' }}
                                    </p>
                                    <a href="{{ route('studio.public.show', $studio->slug) }}"
                                       class="block w-full text-center px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors text-sm">
                                        Voir le studio
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
    @else
    <!-- Artistes mis en avant -->
    <section id="featured-section" class="bg-noir-profond py-12 px-4">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-display font-bold text-beige-peau mb-4">
                        Artistes mis en avant
                    </h2>
                    <p class="text-ivoire-text/70">
                        Les meilleurs artistes de notre plateforme
                    </p>
                </div>

                <div id="featured-artists" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Les cartes d'artistes seront chargées ici -->
                    <div class="text-center col-span-full py-8">
                        @foreach ($artists as $artist)
                            <x-ui.artistCard :artist="$artist" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Résultats de recherche -->
    <section id="search-results-section" class="bg-gris-fonde py-12 px-4 hidden">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-display font-bold text-beige-peau">
                        <span id="results-count">-</span> artistes trouvés
                    </h2>
                    <div class="text-ivoire-text/70">
                        <span id="current-page">1</span> / <span id="total-pages">1</span>
                    </div>
                </div>

                <!-- Tous les artistes (triés par pertinence) -->
                <div id="all-artists" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Les cartes d'artistes seront chargées ici -->
                    <div class="text-center col-span-full py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-beige-peau"></div>
                    </div>
                </div>

                <!-- Pagination -->
                <div id="pagination" class="flex justify-center mt-12">
                    <!-- La pagination sera chargée ici -->
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        // Marketplace JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const marketplace = {
                currentPage: 1,
                currentFilters: {},

                init() {
                    this.loadStats();
                    this.loadFilters();
                    this.loadFeaturedArtists();
                    this.setupEventListeners();
                    // Ne pas lancer de recherche automatiquement au chargement
                },

                setupEventListeners() {
                    // Toggle filtres avancés
                    document.getElementById('toggle-filters').addEventListener('click', () => {
                        document.getElementById('advanced-filters').classList.toggle('hidden');
                    });

                    // Recherche dynamique avec suggestions
                    const searchInput = document.getElementById('search-input');
                    const suggestionsContainer = document.getElementById('search-suggestions');
                    let searchTimeout;

                    searchInput.addEventListener('input', (e) => {
                        clearTimeout(searchTimeout);
                        const query = e.target.value.trim();

                        if (query.length >= 2) {
                            searchTimeout = setTimeout(() => {
                                this.fetchSearchSuggestions(query);
                            }, 300);
                        } else {
                            suggestionsContainer.classList.add('hidden');
                        }
                    });

                    // Fermer les suggestions au clic extérieur
                    document.addEventListener('click', (e) => {
                        if (!suggestionsContainer.contains(e.target)) {
                            suggestionsContainer.classList.add('hidden');
                        }
                    });

                    // Formulaire de recherche
                    document.getElementById('search-form').addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.currentPage = 1;
                        this.performSearch();
                        suggestionsContainer.classList.add('hidden');
                    });

                    // Changement de filtres
                    document.querySelectorAll('#advanced-filters select, #advanced-filters input').forEach(
                        input => {
                            input.addEventListener('change', () => {
                                this.currentPage = 1;
                                this.performSearch();
                            });
                        });
                },

                async loadStats() {
                    try {
                        const response = await fetch('/api/marketplace/stats');
                        const stats = await response.json();

                        document.getElementById('total-artists').textContent = stats.total_artists || '-';
                        document.getElementById('verified-artists').textContent = stats.verified_artists ||
                            '-';
                        document.getElementById('pro-artists').textContent = stats.pro_artists || '-';
                        document.getElementById('total-appointments').textContent = stats
                            .total_appointments || '-';
                    } catch (error) {
                        console.error('Erreur chargement stats:', error);
                    }
                },

                async loadFilters() {
                    try {
                        const response = await fetch('/api/marketplace/filters');
                        const filters = await response.json();

                        // Remplir les selects
                        this.populateSelect('specialization', filters.specializations);
                        this.populateSelect('region', filters.regions);
                        this.populateSelect('sort', filters.sort_options);
                        this.populateMultiSelect('styles', filters.styles);
                    } catch (error) {
                        console.error('Erreur chargement filtres:', error);
                    }
                },

                populateSelect(name, options) {
                    const select = document.querySelector(`select[name="${name}"]`);
                    if (!select) return;

                    Object.entries(options).forEach(([value, label]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = label;
                        select.appendChild(option);
                    });
                },

                populateMultiSelect(name, options) {
                    const select = document.querySelector(`select[name="${name}"]`);
                    if (!select) return;

                    options.forEach(option => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option;
                        optionElement.textContent = option;
                        select.appendChild(optionElement);
                    });
                },

                async fetchSearchSuggestions(query) {
                    try {
                        const response = await fetch(
                            `/api/marketplace/suggestions?q=${encodeURIComponent(query)}`);
                        const suggestions = await response.json();
                        this.displaySearchSuggestions(suggestions);
                    } catch (error) {
                        console.error('Erreur suggestions:', error);
                    }
                },

                displaySearchSuggestions(suggestions) {
                    const container = document.getElementById('search-suggestions');

                    if (suggestions.length === 0) {
                        container.classList.add('hidden');
                        return;
                    }

                    const html = suggestions.map(suggestion => `
                        <div class="px-4 py-3 hover:bg-titane/20 cursor-pointer transition-colors search-suggestion"
                             onclick="marketplace.selectSuggestion('${suggestion.value}')">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-beige-peau/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-noir-profond" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-ivoire-text">${suggestion.label}</div>
                                    <div class="text-xs text-ivoire-text/70">${suggestion.type}</div>
                                </div>
                            </div>
                        </div>
                    `).join('');

                    container.innerHTML = html;
                    container.classList.remove('hidden');

                    // Ajouter les écouteurs pour les suggestions
                    container.querySelectorAll('.search-suggestion').forEach(item => {
                        item.addEventListener('click', () => {
                            const value = item.getAttribute('onclick').match(/'([^']+)'/)[1];
                            document.getElementById('search-input').value = value;
                            container.classList.add('hidden');
                            this.performSearch();
                        });
                    });
                },

                async performSearch() {
                    this.currentFilters = this.getFormData();

                    try {
                        const params = new URLSearchParams({
                            ...this.currentFilters,
                            page: this.currentPage,
                            per_page: 12
                        });

                        const response = await fetch(`/api/marketplace/search?${params}`);
                        const data = await response.json();

                        this.renderSearchResults(data);
                    } catch (error) {
                        console.error('Erreur recherche:', error);
                    }
                },

                getFormData() {
                    const form = document.getElementById('search-form');
                    const data = {};

                    // Parcourir tous les champs du formulaire, même cachés
                    const allInputs = form.querySelectorAll('input, select, textarea');

                    allInputs.forEach(input => {
                        const name = input.name;
                        if (!name) return;

                        let value = input.value;

                        // Gérer les checkboxes
                        if (input.type === 'checkbox') {
                            if (input.checked) {
                                value = input.checked ? '1' : '';
                            } else {
                                return; // Skip unchecked checkboxes
                            }
                        }

                        // Gérer les selects multiples
                        if (input.type === 'select-multiple') {
                            const selectedOptions = Array.from(input.selectedOptions);
                            value = selectedOptions.map(option => option.value);
                        }

                        // Pour artisan_type, toujours inclure la valeur (même vide pour "Tous")
                        if (name === 'artisan_type') {
                            data[name] = value;
                        } else {
                            // Pour les autres champs, ignorer les valeurs vides
                            if (value) {
                                if (data[name]) {
                                    // Pour les selects multiples
                                    if (Array.isArray(data[name])) {
                                        data[name].push(value);
                                    } else {
                                        data[name] = [data[name], value];
                                    }
                                } else {
                                    data[name] = value;
                                }
                            }
                        }
                    });

                    return data;
                },

                renderSearchResults(data) {
                    // Afficher la section des résultats et cacher featured
                    document.getElementById('search-results-section').classList.remove('hidden');
                    document.getElementById('featured-section').classList.add('hidden');

                    // Mettre à jour les compteurs
                    document.getElementById('results-count').textContent = data.pagination.total;
                    document.getElementById('current-page').textContent = data.pagination.current_page;
                    document.getElementById('total-pages').textContent = data.pagination.last_page;

                    // Générer le HTML pour les artistes
                    const artistsHtml = data.data.map(artist => {
                        return `
                            <div class="bg-noir-profond rounded-[2rem] border border-titane/40 shadow-lg shadow-electric-blue/30 overflow-hidden hover:ring-2 hover:ring-beige-peau hover:shadow-cuivre/50 transition-all relative m-2 mb-4">
                                <!-- Badges -->
                                <div class="absolute top-2 left-2 space-y-1 z-10">
                                    ${artist.is_subscribed ? '<span class="badge-pro">PRO</span>' : ''}
                                    ${artist.is_verified ? '<span class="badge-verified">Vérifié</span>' : ''}
                                </div>

                                <!-- Image de bannière -->
                                <div class="h-64 md:h-80 bg-gradient-to-br from-titane/40 to-noir-profond relative overflow-hidden">
                                    ${artist.avatar_url ?
                                        `<img src="${artist.avatar_url}" alt="${artist.name}" class="w-full h-full object-cover">
                                                                                         <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 via-noir-profond/40 to-transparent"></div>` :
                                        `<div class="absolute inset-0 bg-gradient-to-br from-beige-peau/20 via-titane/30 to-noir-profond"></div>
                                                                                         <div class="absolute inset-0 bg-black/20"></div>`
                                    }
                                </div>

                                <!-- Avatar et Stats -->
                                <div class="px-4 -mt-12 relative z-20">
                                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4">
                                        <!-- Avatar -->
                                        <div class="w-32 h-32 md:w-36 md:h-36 rounded-full border-2 border-titane/30 shadow-lg shadow-titane/20 overflow-hidden bg-titane/40 flex items-center justify-center flex-shrink-0">
                                            ${artist.avatar_url ?
                                                `<img src="${artist.avatar_url}" alt="${artist.name}" class="w-full h-full object-cover">` :
                                                `<svg class="w-16 h-16 md:w-18 md:h-18 text-ivoire-text/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                                </svg>`
                                            }
                                        </div>

                                        <!-- Stats compactes -->
                                        <div class="flex-1 flex flex-wrap gap-2 text-xs text-ivoire-text/70">
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>${artist.wait_time_display || 'N/A'}</span>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>${artist.minimum_price ? 'À partir de ' + artist.minimum_price + '€' : 'N/A'}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-4 pt-2">
                                    <!-- Header -->
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 mb-4">
                                        <div class="text-center sm:text-left">
                                            <h3 class="text-beige-peau font-semibold text-lg mb-1">
                                                ${artist.name}
                                            </h3>
                                            <p class="text-ivoire-text text-sm mb-1">
                                                ${artist.specialization_label}
                                            </p>
                                            <p class="text-titane text-sm">
                                                ${artist.city}
                                            </p>
                                        </div>
                                        <div class="text-center sm:text-right">
                                            <div class="flex items-center justify-center sm:justify-end gap-1 text-beige-peau text-sm mb-1">
                                                ⭐ ${Number(artist.rating).toFixed(1)}
                                            </div>
                                            <div class="text-ivoire-text/60 text-xs">
                                                ${artist.reviews_count} avis
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Styles -->
                                    <div class="flex flex-wrap justify-center gap-3 mb-3">
                                        ${artist.styles.map(style =>
                                            `<span class="badge-style text-xs bg-beige-peau/10 text-beige-peau px-2 py-1 rounded-full">${style}</span>`
                                        ).join('')}
                                    </div>

                                    <!-- Bio -->
                                    ${artist.bio ?
                                        `<div class="text-ivoire-text text-sm mb-3 line-clamp-2">
                                                                                            ${artist.bio.length > 100 ? artist.bio.substring(0, 100) + '...' : artist.bio}
                                                                                        </div>` : ''
                                    }

                                    <!-- CTA -->
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <a href="${artist.profile_url}" class="flex-1 px-4 py-2 bg-noir-profond text-beige-peau border border-beige-peau/30 text-beige-peau font-semibold rounded-lg hover:bg-noir-profond/80 transition-colors text-center">
                                            Voir le profil
                                        </a>
                                        <a href="${artist.contact_url}" class="flex-1 px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors text-center">
                                            Contacter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');

                    // Insérer le HTML dans le conteneur
                    document.getElementById('all-artists').innerHTML = artistsHtml;

                    // Pagination (à implémenter si nécessaire)
                    document.getElementById('pagination').innerHTML = '';
                },

                goToPage(page) {
                    this.currentPage = page;
                    this.performSearch();
                },

                // Initialisation
                init() {
                    this.loadStats();
                    this.loadFilters();
                    // Les artistes en vedette sont déjà chargés par PHP
                    this.setupEventListeners();
                    // Ne pas lancer de recherche automatiquement au chargement
                }
            };

            // Démarrer le marketplace
            marketplace.init();
        });
    </script>
@endpush

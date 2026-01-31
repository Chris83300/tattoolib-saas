@extends('layouts.app')

@section('title', 'Marketplace - Trouver un artiste')

@section('content')
    <!-- Hero Section Marketplace -->
    <section class="bg-noir-profond py-16 px-4">
        <div class="container-custom px-4">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl md:text-6xl font-display font-bold text-ivoire-text mb-6">
                    Trouvez l'artiste<br>
                    <span class="text-beige-peau">fait pour vous</span>
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
    <section class="bg-gris-fonde py-8 px-4 sticky top-0 z-40 border-b border-titane/20">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <form id="search-form" class="space-y-4">
                    <!-- Barre de recherche principale -->
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <input type="text" name="city" id="city-search" placeholder="Rechercher par ville..."
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:outline-none focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-6 py-3 bg-beige-peau text-noir-profond font-semibold rounded-lg hover:bg-beige-peau/90 transition-colors">
                                Rechercher
                            </button>
                            <button type="button" id="toggle-filters"
                                class="px-6 py-3 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg hover:bg-noir-profond/80 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Filtres avancés (cachés par défaut) -->
                    <div id="advanced-filters" class="hidden">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Spécialisation -->
                            <div>
                                <label class="block text-ivoire-text/70 text-sm mb-2">Spécialisation</label>
                                <select name="specialization"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    <option value="">Toutes</option>
                                </select>
                            </div>

                            <!-- Styles -->
                            <div>
                                <label class="block text-ivoire-text/70 text-sm mb-2">Styles</label>
                                <select name="styles" multiple
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau"
                                    size="4">
                                </select>
                            </div>

                            <!-- Région -->
                            <div>
                                <label class="block text-ivoire-text/70 text-sm mb-2">Région</label>
                                <select name="region"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    <option value="">Toutes</option>
                                </select>
                            </div>

                            <!-- Tri -->
                            <div>
                                <label class="block text-ivoire-text/70 text-sm mb-2">Tri</label>
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

    <!-- Artistes mis en avant -->
    <section class="bg-noir-profond py-12 px-4">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-display font-bold text-ivoire-text mb-4">
                        Artistes mis en avant
                    </h2>
                    <p class="text-ivoire-text/70">
                        Les meilleurs artistes de notre plateforme
                    </p>
                </div>

                <div id="featured-artists" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Les cartes d'artistes seront chargées ici -->
                    <div class="text-center col-span-full py-8">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-beige-peau"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Résultats de recherche -->
    <section class="bg-gris-fonde py-12 px-4">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-display font-bold text-ivoire-text">
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

    <!-- Template pour carte d'artiste (uniforme) -->
    <template id="artist-card-template">
        <div class="bg-noir-profond rounded-xl overflow-hidden hover:ring-2 hover:ring-beige-peau transition-all relative">
            <!-- Badges (vérifié, top notes uniquement) -->
            <div class="absolute top-2 left-2 space-y-1 z-10">
                <!-- Badges seront insérés ici -->
            </div>

            <!-- Image portfolio -->
            <div class="aspect-square bg-gray-800 relative">
                <img src="" alt="Portfolio" class="w-full h-full object-cover artist-image">
            </div>

            <!-- Infos -->
            <div class="p-4">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h3 class="text-ivoire-text font-semibold text-lg artist-name"></h3>
                        <p class="text-ivoire-text/70 text-sm artist-specialization"></p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-1 text-beige-peau text-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>
                            <span class="artist-rating"></span>
                        </div>
                        <div class="text-ivoire-text/60 text-xs artist-reviews"></div>
                    </div>
                </div>

                <!-- Localisation -->
                <div class="flex items-center gap-1 text-ivoire-text/60 text-sm mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="artist-location"></span>
                </div>

                <!-- Styles -->
                <div class="flex flex-wrap gap-1 mb-3 artist-styles">
                    <!-- Styles seront insérés ici -->
                </div>

                <!-- Stats -->
                <div class="flex justify-between text-ivoire-text/60 text-xs mb-3">
                    <span class="artist-appointments"></span>
                    <span class="artist-experience"></span>
                </div>

                <!-- CTA Buttons -->
                <div class="flex gap-2">
                    <x-ui.button variant="secondary" size="sm" href="#" class="flex-1 artist-profile-link">
                        Voir le profil
                    </x-ui.button>
                    <x-ui.button variant="primary" size="sm" href="/contact" class="flex-1">
                        📅 Prendre RDV
                    </x-ui.button>
                </div>
            </div>
        </div>
    </template>

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
                    this.performSearch();
                },

                setupEventListeners() {
                    // Toggle filtres avancés
                    document.getElementById('toggle-filters').addEventListener('click', () => {
                        document.getElementById('advanced-filters').classList.toggle('hidden');
                    });

                    // Formulaire de recherche
                    document.getElementById('search-form').addEventListener('submit', (e) => {
                        e.preventDefault();
                        this.currentPage = 1;
                        this.performSearch();
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

                async loadFeaturedArtists() {
                    try {
                        const response = await fetch('/api/marketplace/featured?limit=6');
                        const data = await response.json();

                        this.renderArtists(data.data, 'featured-artists', 'artist-card-template');
                    } catch (error) {
                        console.error('Erreur chargement featured:', error);
                    }
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
                    const formData = new FormData(document.getElementById('search-form'));
                    const data = {};

                    for (let [key, value] of formData.entries()) {
                        if (value) {
                            if (data[key]) {
                                // Pour les selects multiples
                                if (Array.isArray(data[key])) {
                                    data[key].push(value);
                                } else {
                                    data[key] = [data[key], value];
                                }
                            } else {
                                data[key] = value;
                            }
                        }
                    }

                    return data;
                },

                renderSearchResults(data) {
                    document.getElementById('results-count').textContent = data.pagination.total;
                    document.getElementById('current-page').textContent = data.pagination.current_page;
                    document.getElementById('total-pages').textContent = data.pagination.last_page;

                    // Rendre tous les artistes ensemble (déjà triés par pertinence)
                    this.renderArtists(data.data, 'all-artists', 'artist-card-template');

                    // Pagination
                    this.renderPagination(data.pagination);
                },

                renderArtists(artists, containerId, templateId) {
                    const container = document.getElementById(containerId);
                    const template = document.getElementById(templateId);

                    if (!container || !template) return;

                    container.innerHTML = '';

                    artists.forEach(artist => {
                        const card = this.createArtistCard(artist, template);
                        container.appendChild(card);
                    });
                },

                createArtistCard(artist, template) {
                    const card = template.content.cloneNode(true);

                    // Image
                    const image = card.querySelector('.artist-image');
                    if (artist.portfolio_images && artist.portfolio_images.length > 0) {
                        image.src = artist.portfolio_images[0].url;
                        image.alt = `Portfolio de ${artist.name}`;
                    } else {
                        image.src = artist.avatar_url;
                        image.alt = artist.name;
                    }

                    // Infos de base
                    card.querySelector('.artist-name').textContent = artist.name;
                    card.querySelector('.artist-specialization').textContent = artist.specialization_label;
                    card.querySelector('.artist-rating').textContent = artist.rating;
                    card.querySelector('.artist-reviews').textContent = `(${artist.reviews_count} avis)`;
                    card.querySelector('.artist-location').textContent =
                        `${artist.city}, ${artist.region_label}`;

                    // Styles
                    const stylesContainer = card.querySelector('.artist-styles');
                    artist.styles.slice(0, 3).forEach(style => {
                        const badge = document.createElement('span');
                        badge.className = 'px-2 py-1 bg-titane/20 text-ivoire-text/80 text-xs rounded';
                        badge.textContent = style;
                        stylesContainer.appendChild(badge);
                    });

                    // Stats
                    card.querySelector('.artist-appointments').textContent =
                        `${artist.appointments_count} rendez-vous`;
                    card.querySelector('.artist-experience').textContent =
                        `${artist.stats.years_experience} ans d'exp`;

                    // Badges (uniquement vérifié et top notes, pas de badge Pro/Free)
                    const badgesContainer = card.querySelector('.absolute.top-2.left-2');
                    artist.badges.forEach(badge => {
                        if (badge.type === 'verified' || badge.type === 'top_rated') {
                            const badgeElement = document.createElement('div');
                            badgeElement.className =
                                `bg-${badge.color}/10 text-${badge.color} px-2 py-1 rounded text-xs font-semibold`;
                            badgeElement.textContent = badge.label;
                            badgesContainer.appendChild(badgeElement);
                        }
                    });

                    // Lien profil
                    const profileLink = card.querySelector('.artist-profile-link');
                    profileLink.href = artist.profile_url;

                    return card;
                },

                renderPagination(pagination) {
                    const container = document.getElementById('pagination');
                    container.innerHTML = '';

                    if (pagination.last_page <= 1) return;

                    const paginationDiv = document.createElement('div');
                    paginationDiv.className = 'flex gap-2';

                    // Bouton précédent
                    if (pagination.current_page > 1) {
                        const prevBtn = this.createPaginationButton(pagination.current_page - 1, 'Précédent');
                        paginationDiv.appendChild(prevBtn);
                    }

                    // Pages
                    const startPage = Math.max(1, pagination.current_page - 2);
                    const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

                    for (let i = startPage; i <= endPage; i++) {
                        const btn = this.createPaginationButton(i, i.toString(), i === pagination.current_page);
                        paginationDiv.appendChild(btn);
                    }

                    // Bouton suivant
                    if (pagination.current_page < pagination.last_page) {
                        const nextBtn = this.createPaginationButton(pagination.current_page + 1, 'Suivant');
                        paginationDiv.appendChild(nextBtn);
                    }

                    container.appendChild(paginationDiv);
                },

                createPaginationButton(page, text, isActive = false) {
                    const button = document.createElement('button');
                    button.textContent = text;
                    button.className = isActive ?
                        'px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold' :
                        'px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg hover:bg-noir-profond/80';

                    if (!isActive) {
                        button.addEventListener('click', () => {
                            this.currentPage = page;
                            this.performSearch();
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        });
                    }

                    return button;
                }
            };

            // Initialiser
            marketplace.init();
        });
    </script>
@endpush

<?php $__env->startSection('title', 'Marketplace - Trouver un artiste'); ?>

<?php $__env->startSection('content'); ?>
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
                            <!-- Type d'artiste -->
                            <div>
                                <label class="block text-ivoire-text/70 text-sm mb-2">Type d'artiste</label>
                                <select name="artisan_type"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    <option value="">Tous</option>
                                    <option value="tattooer"
                                        <?php echo e(($filters['artisan_type'] ?? '') === 'tattooer' ? 'selected' : ''); ?>>🎨 Tatoueurs
                                    </option>
                                    <option value="piercer"
                                        <?php echo e(($filters['artisan_type'] ?? '') === 'piercer' ? 'selected' : ''); ?>>💉 Pierceurs
                                    </option>
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
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if (isset($component)) { $__componentOriginal8fc052fcae0ec32d28b41c64f25fb4fa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fc052fcae0ec32d28b41c64f25fb4fa = $attributes; } ?>
<?php $component = App\View\Components\Ui\ArtistCard::resolve(['artist' => $artist] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.artistCard'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Ui\ArtistCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8fc052fcae0ec32d28b41c64f25fb4fa)): ?>
<?php $attributes = $__attributesOriginal8fc052fcae0ec32d28b41c64f25fb4fa; ?>
<?php unset($__attributesOriginal8fc052fcae0ec32d28b41c64f25fb4fa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8fc052fcae0ec32d28b41c64f25fb4fa)): ?>
<?php $component = $__componentOriginal8fc052fcae0ec32d28b41c64f25fb4fa; ?>
<?php unset($__componentOriginal8fc052fcae0ec32d28b41c64f25fb4fa); ?>
<?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Résultats de recherche -->
    <section id="search-results-section" class="bg-gris-fonde py-12 px-4 hidden">
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

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
                    // Afficher la section des résultats
                    document.getElementById('search-results-section').classList.remove('hidden');

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
                    image.src = artist.avatar_url;
                    image.alt = artist.name

                    // Infos de base
                    card.querySelector('.artist-name').textContent = artist.name;
                    card.querySelector('.artist-specialization').textContent = artist.specialization_label;
                    card.querySelector('.artist-rating').textContent = artist.rating;
                    card.querySelector('.artist-reviews').textContent = `(${artist.reviews_count} avis)`;
                    card.querySelector('.artist-location').textContent =
                        (artist.region_label || artist.city) + (artist.postal_code ? ` ${artist.postal_code}` :
                            '');

                    // Styles
                    const stylesContainer = card.querySelector('.artist-styles');
                    artist.styles.slice(0, 3).forEach(style => {
                        const badge = document.createElement('span');
                        badge.className = 'px-2 py-1 bg-titane/20 text-ivoire-text/80 text-xs rounded';
                        badge.textContent = style;
                        stylesContainer.appendChild(badge);
                    });

                    // Stats complètes
                    card.querySelector('.artist-experience').textContent =
                        `${artist.stats.years_experience} ans d'exp`;

                    // Prix minimum
                    const priceElement = card.querySelector('.artist-price');
                    if (artist.minimum_price) {
                        priceElement.textContent = `À partir de ${artist.minimum_price}€`;
                    } else {
                        priceElement.textContent = '';
                    }

                    // Délai d'attente
                    const waitTimeElement = card.querySelector('.artist-wait-time');
                    if (artist.wait_time_weeks_min) {
                        if (artist.wait_time_weeks_max) {
                            waitTimeElement.textContent =
                                `${artist.wait_time_weeks_min} à ${artist.wait_time_weeks_max} semaines`;
                        } else {
                            waitTimeElement.textContent =
                                `${artist.wait_time_weeks_min} semaine${artist.wait_time_weeks_min > 1 ? 's' : ''}`;
                        }
                    } else {
                        waitTimeElement.textContent = '';
                    }

                    // Horaires d'ouverture
                    const openingHoursElement = card.querySelector('.artist-opening-hours');

                    if (artist.working_hours) {
                        try {
                            // Les horaires sont stockés en JSON dans le champ working_hours de la table tattooer
                            const workingHoursData = typeof artist.working_hours === 'string' ?
                                JSON.parse(artist.working_hours) :
                                artist.working_hours;

                            // Jours de la semaine en français (clés du JSON)
                            const daysOfWeek = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi',
                                'samedi'
                            ];
                            const today = daysOfWeek[new Date().getDay()];
                            const todayData = workingHoursData[today];

                            if (todayData && todayData.open && todayData.close) {
                                // Vérifier si actuellement ouvert
                                const now = new Date();
                                const currentTime =
                                    `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
                                const isOpenNow = currentTime >= todayData.open && currentTime <= todayData
                                    .close;

                                openingHoursElement.textContent = isOpenNow ? '🟢 Ouvert actuellement' :
                                    '🕐 Horaires disponibles';
                            } else if (todayData && (todayData.open === null || todayData.close === null)) {
                                // L'artiste a des horaires mais pas pour aujourd'hui (valeurs null = fermé)
                                openingHoursElement.textContent = '🔴 Fermé actuellement';
                            } else {
                                openingHoursElement.textContent = '🕐 Horaires disponibles';
                            }

                            // Jours d'ouverture
                            const openDaysElement = card.querySelector('.artist-open-days');
                            const openDays = [];

                            daysOfWeek.forEach(day => {
                                const dayData = workingHoursData[day];
                                if (dayData && dayData.open && dayData.close) {
                                    openDays.push(day.charAt(0).toUpperCase() + day.slice(1));
                                }
                            });

                            if (openDays.length > 0) {
                                openDaysElement.textContent = `Ouvert : ${openDays.join(', ')}`;
                            } else {
                                openDaysElement.textContent = 'Non spécifié';
                            }

                        } catch (error) {
                            openingHoursElement.textContent = '🕐 Horaires disponibles';
                        }
                    } else {
                        openingHoursElement.textContent = '';
                    }

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

                    // Bouton Contacter dynamique
                    const contactContainer = card.querySelector('.artist-contact-container');

                    if (artist.has_active_request) {
                        // Badge "Demande en cours"
                        contactContainer.innerHTML = `
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-beige-peau/20 text-beige-peau rounded-lg text-sm font-semibold w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Demande en cours
                            </span>
                        `;
                    } else if (artist.contact_url) {
                        // Bouton "Prendre RDV"
                        contactContainer.innerHTML = `
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'sm','href' => '${artist.contact_url}','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'sm','href' => '${artist.contact_url}','class' => 'flex-1']); ?>
                                Prendre RDV
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                        `;
                    } else {
                        // Pas de bouton (non connecté ou autre)
                        contactContainer.innerHTML = `
                            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'sm','href' => '/login','class' => 'flex-1']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'sm','href' => '/login','class' => 'flex-1']); ?>
                                Se connecter
                             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                        `;
                    }

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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/marketplace/index.blade.php ENDPATH**/ ?>
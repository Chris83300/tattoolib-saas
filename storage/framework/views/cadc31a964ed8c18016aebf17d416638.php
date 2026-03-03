<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <section class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
        <div class="text-center max-w-2xl">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Ink&Pik" />
            <h1 class="text-titre md:text-10xl font-Satoshi font-bold text-titane mb-10">
                Ink <span class="text-beige-peau">& Pik</span>
            </h1>
            <h2 class="text-4xl md:text-6xl font-Satoshi font-bold text-ivoire-text mb-4">
                <span class="text-titane">Notre art,</span> <span class="text-beige-peau">votre peau.</span>
            </h2>

            <!-- Sous-titre -->
            <p class="text-lg md:text-xl text-ivoire-text/90 mb-8">
                La plateforme professionnelle qui connecte clients
                et artistes des arts corporels.
            </p>

            <!-- 2 CTA distincts -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <!-- CTA Client -->
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg','href' => '/marketplace']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','href' => '/marketplace']); ?>
                    Trouver un artiste
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

                <!-- CTA Pro -->
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg','href' => '/professionnels']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => '/professionnels']); ?>
                    Je suis un pro
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
            </div>
        </div>
    </section>

    <!-- Section Trust (pictogrammes) -->
    <section class="bg-gris-fonde py-16 px-4">
        <div class="container-custom">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-center text-beige-peau mb-12">
                Une plateforme de confiance
            </h2>

            <!-- Grid 3 colonnes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Item 1 : Artistes vérifiés -->
                <div class="text-center">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 btn-shadow rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-titane mb-2">
                        Artistes vérifiés
                    </h3>
                    <p class="text-ivoire-text/70 text-sm">
                        Conformité réglementaire garantie (SIRET, ARS, Hygiène)
                    </p>
                </div>

                <!-- Item 2 : Paiements sécurisés -->
                <div class="text-center">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 btn-shadow rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-vert-succes mb-2">
                        Paiements sécurisés
                    </h3>
                    <p class="text-ivoire-text/70 text-sm">
                        Acomptes gérés via Stripe (3D Secure)
                    </p>
                </div>

                <!-- Item 3 : Conformité -->
                <div class="text-center">
                    <div
                        class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 btn-shadow rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-beige-peau mb-2">
                        Conformité réglementaire
                    </h3>
                    <p class="text-ivoire-text/70 text-sm">
                        Traçabilité complète (ARS, hygiène)
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Artistes mis en avant -->
    <section class="bg-noir-profond py-16 px-4">
        <div class="container-custom">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-beige-peau mb-4">
                    Découvrez nos artistes
                </h2>
                <p class="text-ivoire-text/70">
                    Des professionnels certifiés près de chez vous
                </p>
            </div>

            <!-- Artistes mis en avant -->
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
    </section>
    <!-- Section CTA Pro (Teaser uniquement) -->
    <section id="pour-les-pros" class="bg-gris-fonde py-16 px-4">
        <div class="container-custom max-w-3xl text-center">

            <!-- Titre teaser -->
            <h2 class="text-3xl md:text-4xl font-display font-bold text-beige-peau mb-4">
                Vous êtes tatoueur, pierceur ou gérant de studio ?
            </h2>

            <p class="text-lg text-ivoire-text/80 mb-8">
                Développez votre activité avec un outil professionnel tout-en-un
            </p>

            <!-- Liste bénéfices courte -->
            <div class="grid grid-cols-2 gap-4 max-w-xl mx-auto mb-10">

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Planning intelligent</span>
                </div>

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Paiements sécurisés</span>
                </div>

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Conformité ARS</span>
                </div>

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Visibilité marketplace</span>
                </div>

            </div>

            <!-- CTA unique vers page dédiée -->
            <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg','href' => '/pour-les-professionnels']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => '/pour-les-professionnels']); ?>
                Découvrir l'offre professionnelle
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

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Welcome Page JavaScript - Inspiré de la marketplace
        document.addEventListener('DOMContentLoaded', function() {
            const welcomePage = {
                currentPage: 1,
                currentFilters: {},

                init() {
                    this.loadStats();
                    this.loadFeaturedArtists();
                    this.setupEventListeners();
                },

                setupEventListeners() {
                    // Pas de filtres sur la welcome page pour le moment
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

                async loadFeaturedArtists() {
                    try {
                        const response = await fetch('/api/marketplace/featured?limit=6');
                        const data = await response.json();

                        this.renderArtists(data.data, 'featured-artists', 'artist-card-template');
                    } catch (error) {
                        console.error('Erreur chargement featured:', error);
                    }
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
                    image.alt = artist.name;

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
                            const workingHoursData = typeof artist.working_hours === 'string' ?
                                JSON.parse(artist.working_hours) :
                                artist.working_hours;

                            const daysOfWeek = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi',
                                'samedi'
                            ];
                            const today = daysOfWeek[new Date().getDay()];
                            const todayData = workingHoursData[today];

                            if (todayData && todayData.open && todayData.close) {
                                const now = new Date();
                                const currentTime =
                                    `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;
                                const isOpenNow = currentTime >= todayData.open && currentTime <= todayData
                                    .close;

                                openingHoursElement.textContent = isOpenNow ? '🟢 Ouvert actuellement' :
                                    '🕐 Horaires disponibles';
                            } else if (todayData && (todayData.open === null || todayData.close === null)) {
                                openingHoursElement.textContent = '🕐 Fermé actuellement';
                            } else {
                                openingHoursElement.textContent = '🕐 Horaires disponibles';
                            }

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

                    // Badges (PRO, vérifié, top notes)
                    const badgesContainer = card.querySelector('.absolute.top-2.left-2');
                    artist.badges.forEach(badge => {
                        const badgeElement = document.createElement('div');
                        badgeElement.className =
                            `bg-${badge.color}/10 text-${badge.color} px-2 py-1 rounded text-xs font-semibold`;
                        badgeElement.textContent = badge.label;
                        badgesContainer.appendChild(badgeElement);
                    });

                    // Lien profil
                    const profileLink = card.querySelector('.artist-profile-link');
                    profileLink.href = artist.profile_url;

                    // Bouton Contacter dynamique
                    const contactContainer = card.querySelector('.artist-contact-container');

                    if (artist.has_active_request) {
                        contactContainer.innerHTML = `
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-beige-peau/20 text-beige-peau rounded-lg text-sm font-semibold w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Demande en cours
                            </span>
                        `;
                    } else if (artist.contact_url) {
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
                }
            };

            // Initialiser
            welcomePage.init();
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/welcome.blade.php ENDPATH**/ ?>
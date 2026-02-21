<?php $__env->startSection('title', $artist->user->name . ' - Artiste ' . ucfirst($artist->type ?? 'Tatoueur')); ?>

<?php $__env->startSection('meta'); ?>
    <meta name="description"
        content="Découvrez <?php echo e($artist->user->name); ?>, artiste <?php echo e($artist->type ?? 'tatoueur'); ?> à <?php echo e($artist->city); ?>. Portfolio, avis clients et réservation en ligne.">
    <meta property="og:title" content="<?php echo e($artist->user->name); ?> - Ink&Pik">
    <meta property="og:image" content="<?php echo e($artist->getFirstMediaUrl('avatar')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond pb-20">

        <!-- Bannière de prévisualisation (si mode preview) -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->get('preview') === 'true'): ?>
            <div
                class="fixed top-0 left-0 right-0 z-50 bg-gradient-to-r from-beige-peau to-cuivre text-noir-profond px-4 py-3 text-center font-semibold shadow-lg">
                📋 MODE PRÉVISUALISATION - Ceci est un aperçu de votre profil public
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <!-- Header Sticky Mobile -->
        <div
            class="sticky top-0 z-40 bg-noir-profond/95 backdrop-blur-sm border-b border-titane/20 md:hidden <?php echo e(request()->get('preview') === 'true' ? 'mt-12' : ''); ?>">
            <div class="container mx-auto px-4 h-14 flex items-center justify-between">
                <a href="/marketplace" class="text-ivoire-text">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <span class="text-ivoire-text font-semibold"><?php echo e($artist->user->displayName()); ?></span>

                <button x-data
                    @click="navigator.share({title: '<?php echo e($artist->user->displayName()); ?>', url: window.location.href})"
                    class="text-ivoire-text">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8 max-w-6xl">

            <!-- Header Profil -->
            <div class="text-center mb-8">

                <!-- Avatar Cercle (Spatie Media) -->
                <div
                    class="w-32 h-32 mx-auto mb-4 rounded-full overflow-hidden bg-beige-peau/10 border-4 border-beige-peau/20">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->hasMedia('avatar')): ?>
                        <img src="<?php echo e($artist->getFirstMediaUrl('avatar', 'thumb')); ?>"
                            alt="<?php echo e($artist->user->displayName()); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-5xl">
                            🎨
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Pseudo (displayName) -->
                <h1 class="text-4xl font-Satoshi font-bold text-ivoire-text mb-2">
                    <?php echo e($artist->user->displayName()); ?>

                </h1>

                <!-- Localisation -->
                <p class="text-ivoire-text/70 mb-4 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-beige-peau" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span><?php echo e($artist->city); ?>, <?php echo e($artist->postal_code); ?></span>
                </p>

                <!-- Badge Conformité (cliquable) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->has_compliance_badge): ?>
                    <div class="inline-block mb-4">
                        <button x-data @click="$dispatch('open-compliance-modal')"
                            class="inline-flex items-center gap-2 bg-vert-succes/20 text-vert-succes px-4 py-2 rounded-full font-semibold text-sm hover:bg-vert-succes/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Conforme Ink&Pik</span>
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Styles / Spécialités (chips) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->specialties && is_array($artist->specialties)): ?>
                    <div class="flex flex-wrap gap-2 justify-center mb-6">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artist->specialties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specialty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="bg-beige-peau/20 text-beige-peau px-3 py-1 rounded-full text-sm font-medium">
                                <?php echo e($specialty); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- CTA Principal -->
                <a href="#"
                    class="inline-block w-full md:w-auto px-8 py-4 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold text-lg rounded-lg transition-colors shadow-lg">
                    📅 Réserver un rendez-vous
                </a>

            </div>

            <!-- Stats (3 colonnes) -->
            <div class="grid grid-cols-3 gap-4 mb-8">

                <!-- Délai d'attente -->
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl mb-1">⏰</div>
                    <p class="text-2xl font-bold text-beige-peau mb-1"><?php echo e($stats['average_delay']); ?></p>
                    <p class="text-ivoire-text/70 text-xs">semaines</p>
                </div>

                <!-- Prix minimum -->
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl mb-1">💰</div>
                    <p class="text-2xl font-bold text-beige-peau mb-1"><?php echo e($stats['min_price']); ?>€</p>
                    <p class="text-ivoire-text/70 text-xs">minimum</p>
                </div>

                <!-- Avis -->
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl mb-1">⭐</div>
                    <p class="text-2xl font-bold text-beige-peau mb-1"><?php echo e(number_format($stats['rating'], 1)); ?>/5</p>
                    <p class="text-ivoire-text/70 text-xs cursor-pointer hover:text-beige-peau"
                        onclick="document.getElementById('reviews').scrollIntoView({behavior: 'smooth'})">
                        (<?php echo e($stats['reviews_count']); ?> avis)
                    </p>
                </div>

            </div>

            <!-- Stats Plateforme (si ≥ 15) -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['platform_tattoos'] >= 15): ?>
                <div class="bg-beige-peau/10 border border-beige-peau/30 rounded-xl p-4 mb-8 text-center">
                    <p class="text-ivoire-text/70 text-sm mb-1">Tattoos réalisés via Ink&Pik</p>
                    <p class="text-3xl font-bold text-beige-peau"><?php echo e($stats['platform_tattoos']); ?></p>
                </div>
            <?php else: ?>
                <div class="bg-beige-peau/10 border border-beige-peau/30 rounded-xl p-4 mb-8 text-center">
                    <p class="text-beige-peau font-semibold">🆕 Nouveau sur Ink&Pik</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- À Propos -->
            <div class="bg-gris-fonde rounded-xl p-6 mb-8">
                <h2 class="text-2xl font-Satoshi font-bold text-ivoire-text mb-4">À propos</h2>

                <p class="text-ivoire-text/80 leading-relaxed mb-6">
                    <?php echo e($artist->bio ?? 'Aucune description disponible.'); ?>

                </p>

                <!-- Infos complémentaires -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <!-- Adresse (si studio ou partagée) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->address): ?>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            <div>
                                <p class="text-ivoire-text/50 text-xs mb-1">Adresse</p>
                                <p class="text-ivoire-text"><?php echo e($artist->address); ?></p>
                                <p class="text-ivoire-text"><?php echo e($artist->city); ?>, <?php echo e($artist->postal_code); ?></p>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Téléphone (si public) -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->phone): ?>
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <p class="text-ivoire-text/50 text-xs mb-1">Téléphone</p>
                                <a href="tel:<?php echo e($artist->phone); ?>" class="text-ivoire-text hover:text-beige-peau">
                                    <?php echo e($artist->phone); ?>

                                </a>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>
            </div>

            <!-- Portfolio (Onglets) -->
            <div class="bg-gris-fonde rounded-xl p-6 mb-8" x-data="{ tab: 'realizations' }">
                <h2 class="text-2xl font-Satoshi font-bold text-ivoire-text mb-6">Portfolio</h2>

                <!-- Navigation Onglets -->
                <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
                    <button @click="tab = 'realizations'"
                        :class="tab === 'realizations' ? 'bg-beige-peau text-noir-profond' :
                            'bg-noir-profond text-ivoire-text hover:bg-beige-peau/10'"
                        class="px-4 py-2 rounded-lg font-semibold text-sm whitespace-nowrap transition-colors">
                        Réalisations (<?php echo e($portfolioRealizations->count()); ?>)
                    </button>

                    <button @click="tab = 'drawings'"
                        :class="tab === 'drawings' ? 'bg-beige-peau text-noir-profond' :
                            'bg-noir-profond text-ivoire-text hover:bg-beige-peau/10'"
                        class="px-4 py-2 rounded-lg font-semibold text-sm whitespace-nowrap transition-colors">
                        Dessins (<?php echo e($portfolioDrawings->count()); ?>)
                    </button>

                    <button @click="tab = 'beforeafter'"
                        :class="tab === 'beforeafter' ? 'bg-beige-peau text-noir-profond' :
                            'bg-noir-profond text-ivoire-text hover:bg-beige-peau/10'"
                        class="px-4 py-2 rounded-lg font-semibold text-sm whitespace-nowrap transition-colors">
                        Avant/Après Soins (<?php echo e($portfolioBeforeAfter->count() / 2); ?>)
                    </button>
                </div>

                <!-- TAB 1 : Réalisations -->
                <div x-show="tab === 'realizations'">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portfolioRealizations->isNotEmpty()): ?>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" x-data="{ lightbox: false, currentImage: '' }">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $portfolioRealizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div @click="lightbox = true; currentImage = '<?php echo e($media->getUrl()); ?>'"
                                    class="aspect-square rounded-lg overflow-hidden bg-noir-profond cursor-pointer group">
                                    <img src="<?php echo e($media->getUrl()); ?>" alt="Portfolio"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Lightbox Alpine.js -->
                            <div x-show="lightbox" x-cloak @click.away="lightbox = false"
                                @keydown.escape.window="lightbox = false"
                                class="fixed inset-0 z-50 bg-noir-profond/95 backdrop-blur-sm flex items-center justify-center p-4"
                                x-transition>

                                <!-- Close button -->
                                <button @click="lightbox = false"
                                    class="absolute top-4 right-4 text-ivoire-text hover:text-beige-peau z-10">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>

                                <!-- Image -->
                                <img :src="currentImage" alt="Portfolio" class="max-w-full max-h-[90vh] rounded-lg">
                            </div>

                        </div>
                    <?php else: ?>
                        <p class="text-ivoire-text/50 text-center py-8">Aucune réalisation pour le moment</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- TAB 2 : Dessins -->
                <div x-show="tab === 'drawings'" x-cloak>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portfolioDrawings->isNotEmpty()): ?>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4" x-data="{ lightbox: false, currentImage: '' }">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $portfolioDrawings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div @click="lightbox = true; currentImage = '<?php echo e($media->getUrl()); ?>'"
                                    class="aspect-square rounded-lg overflow-hidden bg-noir-profond cursor-pointer group">
                                    <img src="<?php echo e($media->getUrl()); ?>" alt="Dessin"
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Lightbox -->
                            <div x-show="lightbox" x-cloak @click.away="lightbox = false"
                                @keydown.escape.window="lightbox = false"
                                class="fixed inset-0 z-50 bg-noir-profond/95 backdrop-blur-sm flex items-center justify-center p-4"
                                x-transition>
                                <button @click="lightbox = false"
                                    class="absolute top-4 right-4 text-ivoire-text hover:text-beige-peau z-10">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <img :src="currentImage" alt="Dessin" class="max-w-full max-h-[90vh] rounded-lg">
                            </div>

                        </div>
                    <?php else: ?>
                        <p class="text-ivoire-text/50 text-center py-8">Aucun dessin pour le moment</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- TAB 3 : Avant/Après (Before/After Slider) -->
                <div x-show="tab === 'beforeafter'" x-cloak>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portfolioBeforeAfter->isNotEmpty()): ?>
                        <div class="space-y-8">

                            <?php
                                // Grouper par paires (avant/après)
                                $pairs = $portfolioBeforeAfter->chunk(2);
                            ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pair->count() === 2): ?>
                                    <div class="max-w-2xl mx-auto">
                                        <!-- Titre optionnel -->
                                        <p class="text-ivoire-text/70 text-sm mb-2 text-center">
                                            <?php echo e($pair[0]->getCustomProperty('title') ?? 'Avant / Après cicatrisation'); ?>

                                        </p>

                                        <!-- Web Component img-comparison-slider -->
                                        <img-comparison-slider class="rounded-lg overflow-hidden">
                                            <img slot="first" src="<?php echo e($pair[0]->getUrl()); ?>" alt="Avant" />
                                            <img slot="second" src="<?php echo e($pair[1]->getUrl()); ?>" alt="Après" />
                                        </img-comparison-slider>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>
                    <?php else: ?>
                        <p class="text-ivoire-text/50 text-center py-8">Aucun avant/après pour le moment</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

            </div>

            <!-- CTA Final -->
            <div
                class="bg-gradient-to-br from-beige-peau/20 to-beige-peau/5 border border-beige-peau/30 rounded-xl p-8 text-center">
                <h3 class="text-2xl font-Satoshi font-bold text-ivoire-text mb-3">
                    Prêt à réserver avec <?php echo e($artist->user->displayName()); ?> ?
                </h3>
                <p class="text-ivoire-text/70 mb-6">
                    Réservez en ligne en quelques clics et recevez une confirmation immédiate
                </p>
                <a href="#"
                    class="inline-block px-10 py-4 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold text-lg rounded-lg transition-colors shadow-lg">
                    📅 Demander un rendez-vous
                </a>
            </div>

        </div>

        <!-- CTA Sticky Mobile (Bottom) -->
        <div
            class="fixed bottom-0 left-0 right-0 z-40 bg-noir-profond/95 backdrop-blur-sm border-t border-titane/20 p-4 md:hidden">
            <a href="#"
                class="block w-full text-center px-6 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors">
                📅 Réserver
            </a>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- img-comparison-slider Web Component -->
    <script type="module" src="https://unpkg.com/img-comparison-slider@7/dist/index.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/img-comparison-slider@7/dist/styles.css">

    <style>
        /* Custom styling pour img-comparison-slider */
        img-comparison-slider {
            --divider-width: 2px;
            --divider-color: #D4B59E;
            /* beige-peau */
            --default-handle-opacity: 1;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\artists\show.blade.php ENDPATH**/ ?>
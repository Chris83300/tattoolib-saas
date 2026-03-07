<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto space-y-6 py-6 px-4">
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->getFirstMediaUrl('cover')): ?>
            <div
                class="relative rounded-2xl border border-cuivre/60 shadow-md shadow-cuivre/40 overflow-hidden h-48 sm:h-64">
                <img src="<?php echo e($studio->getFirstMediaUrl('cover')); ?>" alt="<?php echo e($studio->name); ?>"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-noir-profond/80 to-transparent"></div>
                <div class="absolute bottom-4 left-4 flex items-center gap-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->getFirstMediaUrl('logo')): ?>
                        <img src="<?php echo e($studio->getFirstMediaUrl('logo')); ?>" alt="Logo"
                            class="w-20 h-20 rounded-full object-cover border-2 border-cuivre/70 shadow-md shadow-cuivre/40">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div>
                        <h1 class="text-2xl font-bold text-cuivre"><?php echo e($studio->name); ?></h1>
                        <p class="text-sm text-ivoire-text/80">
                            <?php echo e($studio->city); ?><?php echo e($studio->postal_code ? ' (' . $studio->postal_code . ')' : ''); ?>

                        </p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="flex items-center gap-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->getFirstMediaUrl('logo')): ?>
                    <img src="<?php echo e($studio->getFirstMediaUrl('logo')); ?>" alt="Logo"
                        class="w-16 h-16 rounded-xl object-cover">
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text"><?php echo e($studio->name); ?></h1>
                    <p class="text-sm text-titane">
                        <?php echo e($studio->city); ?><?php echo e($studio->postal_code ? ' (' . $studio->postal_code . ')' : ''); ?>

                    </p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->description): ?>
            <div class="bg-gris-fonde rounded-xl p-4">
                <p class="text-sm text-ivoire-text leading-relaxed"><?php echo e($studio->description); ?></p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="flex flex-wrap gap-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->address): ?>
                <div class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2">
                    <span class="text-sm">📍</span>
                    <span class="text-sm text-ivoire-text"><?php echo e($studio->address); ?>, <?php echo e($studio->city); ?>

                        <?php echo e($studio->postal_code); ?></span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->phone): ?>
                <a href="tel:<?php echo e($studio->phone); ?>"
                    class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2 hover:bg-gris-fonde/80 transition-colors">
                    <span class="text-sm">📞</span>
                    <span class="text-sm text-ivoire-text"><?php echo e($studio->phone); ?></span>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->website): ?>
                <a href="<?php echo e($studio->website); ?>" target="_blank" rel="noopener noreferrer"
                    class="flex items-center gap-1.5 bg-gris-fonde rounded-lg px-3 py-2 hover:bg-gris-fonde/80 transition-colors">
                    <span class="text-sm">🌐</span>
                    <span class="text-sm text-beige-peau">Site web</span>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <section>
            <h2 class="text-xl text-center flex justify-center font-bold text-beige-peau mb-4">Nos artistes</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sa->user && $sa->is_active): ?>
                        <?php $artisan = $sa->user->artisan(); ?>
                        <a href="<?php echo e($artisan?->getProfileUrl() ?? '#'); ?>"
                            class="bg-gris-fonde rounded-xl overflow-hidden border border-beige-peau/60 hover:ring-2 hover:ring-beige-peau/50 transition-all group">
                            
                            <div class="h-40 bg-noir-profond overflow-hidden">
                                <?php
                                    $imageUrl =
                                        $artisan?->getFirstMediaUrl('portfolio') ?:
                                        $sa->user->getFirstMediaUrl('avatar') ?:
                                        asset('images/default-avatar.png');
                                ?>
                                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($sa->user->name); ?>"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                            <div class="p-3">
                                <div class="flex items-center gap-2">
                                    <img src="<?php echo e($sa->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                                        alt="" class="w-8 h-8 rounded-full object-cover">
                                    <div>
                                        <p class="text-sm font-semibold text-ivoire-text"><?php echo e($sa->user->name); ?></p>
                                        <p class="text-xs text-beige-peau">
                                            <?php echo e($sa->artisan_type === 'piercer' ? ' Pierceur' : ' Tatoueur'); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-titane col-span-full text-center py-8">Aucun artiste pour le moment</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </section>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->opening_hours && count($studio->opening_hours) > 0): ?>
            <?php
                $workingHoursData = [];
                if ($studio->opening_hours) {
                    if (is_string($studio->opening_hours)) {
                        $workingHoursData = json_decode($studio->opening_hours, true, 512) ?: [];
                    } elseif (is_array($studio->opening_hours)) {
                        $workingHoursData = $studio->opening_hours;
                    }
                }
                $daysOrder = ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];
            ?>

            <h2 class="text-xl font-bold text-beige-peau flex justify-center tracking-wider mt-8 mb-4">Horaires d'ouverture
            </h2>

            <div class="bg-gris-fonde rounded-xl p-6">
                <!-- Mobile: Carousel -->
                <div class="md:hidden">
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $daysOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dayData = $workingHoursData[$day] ?? null;
                                $isOpen = $dayData && !empty($dayData['open']) && !empty($dayData['close']);
                            ?>

                            <div class="flex-shrink-0 w-32 p-3 bg-noir-profond rounded-lg border border-titane/20">
                                <div class="text-center">
                                    <div class="font-semibold text-ivoire-text text-sm mb-1">
                                        <?php echo e(ucfirst($day)); ?>

                                    </div>
                                    <div class="text-xs <?php echo e($isOpen ? 'text-vert-succes' : 'text-rouge-alerte'); ?> mb-1">
                                        <?php echo e($isOpen ? 'Ouvert' : 'Fermé'); ?>

                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOpen && $dayData): ?>
                                        <div class="text-ivoire-text/70 text-xs">
                                            <div><?php echo e($dayData['open'] ?? ''); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($dayData['break_start']) && !empty($dayData['break_end'])): ?>
                                                <div class="text-amber-warning mt-1">
                                                    🍴 <?php echo e($dayData['break_start']); ?>-<?php echo e($dayData['break_end']); ?>

                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div><?php echo e($dayData['close'] ?? ''); ?></div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Desktop: Grid -->
                <div class="hidden md:block">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $daysOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $dayData = $workingHoursData[$day] ?? null;
                                $isOpen = $dayData && !empty($dayData['open']) && !empty($dayData['close']);
                            ?>

                            <div
                                class="bg-noir-profond rounded-xl p-4 border border-titane/20 hover:border-beige-peau/30 shadow-md shadow-titane/10 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="font-semibold text-ivoire-text">
                                        <?php echo e(ucfirst($day)); ?>

                                    </div>
                                    <div class="flex items-center gap-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOpen): ?>
                                            <div class="w-2 h-2 bg-vert-succes rounded-full"></div>
                                            <span class="text-xs text-vert-succes font-medium">Ouvert</span>
                                        <?php else: ?>
                                            <div class="w-2 h-2 bg-rouge-alerte rounded-full"></div>
                                            <span class="text-xs text-rouge-alerte font-medium">Fermé</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isOpen && $dayData): ?>
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span><?php echo e($dayData['open']); ?> - <?php echo e($dayData['close']); ?></span>
                                        </div>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($dayData['break_start']) && !empty($dayData['break_end'])): ?>
                                            <div class="flex items-center gap-2 text-amber-warning/80 text-xs">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332-.477-4.5-1.253">
                                                    </path>
                                                </svg>
                                                <span>Pause: <?php echo e($dayData['break_start']); ?> -
                                                    <?php echo e($dayData['break_end']); ?></span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-ivoire-text/40 text-sm italic">
                                        Fermé aujourd'hui
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <!-- Note pour mobile -->
                <div class="mt-4 text-center md:hidden">
                    <p class="text-ivoire-text/60 text-xs">
                        💡 Faites glisser pour voir tous les jours
                    </p>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->getMedia('photos')->count() > 0): ?>
            <section>
                <h2 class="text-xl font-bold text-beige-peau flex justify-center mb-4">Le salon</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $studio->getMedia('photos'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <img src="<?php echo e($photo->getUrl()); ?>" data-full="<?php echo e($photo->getUrl()); ?>" alt="Photo salon"
                            class="w-full h-32 sm:h-40 rounded-lg object-cover hover:opacity-90 transition-opacity cursor-pointer salon-photo">
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </section>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    </section>

    <!-- Lightbox -->
    <div id="salon-lightbox"
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
            <img id="lightbox-image" src="" alt="Photo salon" class="max-w-full max-h-full rounded-lg">

            <!-- Compteur de photos -->
            <div id="photo-counter"
                class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white text-sm bg-black/50 px-3 py-1 rounded-full">
                1 / 1
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lightbox = document.getElementById('salon-lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const closeLightbox = document.getElementById('close-lightbox');
            const prevPhoto = document.getElementById('prev-photo');
            const nextPhoto = document.getElementById('next-photo');
            const photoCounter = document.getElementById('photo-counter');
            const salonPhotos = document.querySelectorAll('.salon-photo');

            let currentPhotoIndex = 0;
            const photosArray = Array.from(salonPhotos);

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

            // Ouvrir la lightbox au clic sur une photo
            photosArray.forEach((photo, index) => {
                photo.addEventListener('click', function() {
                    showPhoto(index);
                    lightbox.classList.remove('opacity-0', 'invisible');
                    lightbox.classList.add('opacity-100', 'visible');
                    document.body.style.overflow = 'hidden';
                });
            });

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
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\public-profile.blade.php ENDPATH**/ ?>
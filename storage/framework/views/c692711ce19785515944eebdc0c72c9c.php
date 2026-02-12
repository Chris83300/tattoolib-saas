<?php $__env->startSection('title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-ivoire-text mb-2">Tableau de bord</h1>
                <p class="text-ivoire-text/70">
                    Bienvenue <?php echo e(Auth::user()->name); ?> ! Gérez vos demandes de tatouage.
                </p>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-beige-peau mb-1"><?php echo e($stats['total_requests']); ?></div>
                    <div class="text-ivoire-text/60 text-xs">Total demandes</div>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-ambre-warning mb-1"><?php echo e($stats['pending']); ?></div>
                    <div class="text-ivoire-text/60 text-xs">En attente</div>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-vert-succes mb-1"><?php echo e($stats['accepted']); ?></div>
                    <div class="text-ivoire-text/60 text-xs">Acceptées</div>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-beige-peau mb-1"><?php echo e($stats['active']); ?></div>
                    <div class="text-ivoire-text/60 text-xs">En cours</div>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <div class="text-2xl font-bold text-ivoire-text/50 mb-1"><?php echo e($stats['completed']); ?></div>
                </div>
                <div class="text-ivoire-text/60 text-xs">Terminées</div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Nouvelle demande -->
            <a href="<?php echo e(route('booking-request.form')); ?>"
                class="bg-beige-peau/10 border border-beige-peau/30 rounded-xl p-6 hover:bg-beige-peau/20 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-beige-peau rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">Nouvelle demande</h3>
                <p class="text-ivoire-text/70 text-sm">Faire une nouvelle demande de tatouage</p>
            </a>

            <!-- Mes demandes -->
            <a href="<?php echo e(route('client.booking-requests')); ?>"
                class="bg-titane/20 border border-titane/30 rounded-xl p-6 hover:bg-titane/30 transition-colors">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-titane rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-ivoire-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <svg class="w-5 h-5 text-ivoire-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">Mes demandes</h3>
                <p class="text-ivoire-text/70 text-sm">Voir toutes mes demandes de tatouage</p>
            </a>

            <!-- Messages -->
            <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-vert-succes rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-vert-succes">Actif</div>
                    </div>
                </div>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">Messages</h3>
                <p class="text-ivoire-text/70 text-sm">Discutez avec vos artistes</p>
            </div>
        </div>

        <!-- Projets récents -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-ivoire-text">Projets récents</h2>
                <a href="<?php echo e(route('client.booking-requests')); ?>" class="text-beige-peau hover:text-beige-peau/80 text-sm">
                    Voir tout →
                </a>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentBookingRequests->isEmpty()): ?>
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-noir-profond rounded-full mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">Aucune demande</h3>
                    <p class="text-ivoire-text/70 mb-4">Vous n'avez pas encore fait de demande de tatouage</p>
                    <a href="<?php echo e(route('booking-request.form')); ?>"
                        class="inline-flex items-center px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Faire une demande
                    </a>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentBookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bookingRequest): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-noir-profond rounded-lg p-4 border border-titane/30">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-semibold text-ivoire-text">
                                            <?php echo e(Str::limit($bookingRequest->tattoo_description, 60)); ?>

                                        </h3>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                            style="background-color: <?php echo e($bookingRequest->status->color()); ?>15; color: <?php echo e($bookingRequest->status->color()); ?>; border: 1px solid <?php echo e($bookingRequest->status->color()); ?>30;">
                                            <?php echo e($bookingRequest->status->label()); ?>

                                        </span>
                                    </div>

                                    <div class="flex items-center gap-4 text-sm text-ivoire-text/60 mb-2">
                                        <span>📍 <?php echo e($bookingRequest->tattoo_location); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->tattoo_style): ?>
                                            <span>🎨 <?php echo e($bookingRequest->tattoo_style); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->estimated_price): ?>
                                            <span>💰 <?php echo e(number_format($bookingRequest->estimated_price, 0)); ?>€</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="flex items-center gap-2 text-xs text-ivoire-text/50">
                                        <span>Artiste:
                                            <?php echo e($bookingRequest->bookable->user->name ?? 'Non assigné'); ?></span>
                                        <span>•</span>
                                        <span><?php echo e($bookingRequest->created_at->format('d/m/Y')); ?></span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 ml-4">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->unread_messages > 0): ?>
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 bg-rouge-alerte text-ivoire-text rounded-full text-xs font-bold">
                                            <?php echo e($bookingRequest->unread_messages); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->status === 'accepted'): ?>
                                        <a href="<?php echo e(route('client.chat', $bookingRequest)); ?>"
                                            class="p-2 bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/90 transition-colors"
                                            title="Discuter">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <a href="<?php echo e(route('client.booking-request.show', $bookingRequest)); ?>"
                                        class="p-2 bg-titane text-ivoire-text rounded-lg hover:bg-titane/80 transition-colors"
                                        title="Voir détails">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\dashboard.blade.php ENDPATH**/ ?>
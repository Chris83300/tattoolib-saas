<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Tableau de bord</h1>
        <p class="text-sm text-titane mt-1">Bienvenue, <?php echo e(auth()->user()->name); ?></p>
    </div>

    
    <?php
        $checklist = $studio->getOnboardingChecklist();
        $progress = $studio->onboardingProgress();
        $showChecklist = $studio->onTrial() && !$studio->onboardingComplete();
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showChecklist): ?>
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 border border-beige-peau/20">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-bold text-beige-peau uppercase tracking-wider">🚀 Démarrage rapide</h2>
                    <p class="text-xs text-titane mt-0.5">Configurez votre studio en quelques étapes</p>
                </div>
                <span class="text-sm font-bold text-beige-peau"><?php echo e($progress); ?>%</span>
            </div>

            <div class="w-full bg-noir-profond rounded-full h-2 mb-4">
                <div class="bg-beige-peau h-2 rounded-full transition-all duration-500"
                    style="width: <?php echo e($progress); ?>%"></div>
            </div>

            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $checklist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-3 py-2 <?php echo e($step['done'] ? 'opacity-60' : ''); ?>">
                        <span class="text-lg"><?php echo e($step['done'] ? '✅' : $step['icon']); ?></span>
                        <span class="text-sm <?php echo e($step['done'] ? 'text-titane line-through' : 'text-ivoire-text font-medium'); ?>">
                            <?php echo e($step['label']); ?>

                        </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$step['done']): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($step['key']):
                                case ('logo'): ?>
                                    <a href="<?php echo e(route('studio.settings')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                <?php break; ?>
                                <?php case ('artist'): ?>
                                    <a href="<?php echo e(route('studio.artists.create')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Ajouter →</a>
                                <?php break; ?>
                                <?php case ('payment'): ?>
                                    <a href="<?php echo e(route('studio.settings')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                <?php break; ?>
                                <?php case ('profile'): ?>
                                    <a href="<?php echo e(route('studio.settings')); ?>" class="ml-auto text-xs text-beige-peau hover:underline">Personnaliser →</a>
                                <?php break; ?>
                                <?php case ('booking'): ?>
                                    <span class="ml-auto text-xs text-titane">En attente...</span>
                                <?php break; ?>
                            <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Compteurs principaux -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-beige-peau"><?php echo e($activeArtists); ?></div>
            <div class="text-sm text-titane mt-1">Artiste<?php echo e($activeArtists > 1 ? 's' : ''); ?> actif<?php echo e($activeArtists > 1 ? 's' : ''); ?></div>
        </div>

        <a href="<?php echo e(route('studio.requests')); ?>" class="bg-gris-fonde rounded-xl p-5 border border-titane/20 hover:border-yellow-500/40 transition-colors">
            <div class="flex items-center gap-2">
                <div class="text-3xl font-bold text-yellow-400"><?php echo e($pendingCount); ?></div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingCount > 0): ?>
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div class="text-sm text-titane mt-1">En attente</div>
        </a>

        <a href="<?php echo e(route('studio.requests')); ?>" class="bg-gris-fonde rounded-xl p-5 border border-titane/20 hover:border-vert-validation/40 transition-colors">
            <div class="text-3xl font-bold text-vert-validation"><?php echo e($confirmedCount); ?></div>
            <div class="text-sm text-titane mt-1">Confirmée<?php echo e($confirmedCount > 1 ? 's' : ''); ?></div>
        </a>

        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-ivoire-text"><?php echo e(number_format($monthlyRevenue, 0, ',', ' ')); ?>€</div>
            <div class="text-sm text-titane mt-1">Revenu ce mois</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Artistes actifs -->
        <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
            <div class="p-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Artistes</h2>
                <a href="<?php echo e(route('studio.artists')); ?>" class="text-xs text-beige-peau hover:underline">Gérer →</a>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $artists->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-beige-peau/20 flex items-center justify-center shrink-0">
                        <span class="text-beige-peau text-xs font-bold">
                            <?php echo e(mb_strtoupper(mb_substr($artist->user?->name ?? 'A', 0, 1))); ?>

                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-ivoire-text truncate"><?php echo e($artist->user?->name ?? 'Artiste'); ?></p>
                        <p class="text-xs text-titane"><?php echo e($artist->artisan_type === 'piercer' ? 'Pierceur' : 'Tatoueur'); ?></p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artist->joined_at): ?>
                        <span class="text-xs text-titane shrink-0"><?php echo e($artist->joined_at->format('d/m/Y')); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-6 text-center">
                    <p class="text-sm text-titane">Aucun artiste</p>
                    <a href="<?php echo e(route('studio.artists.create')); ?>" class="text-xs text-beige-peau hover:underline mt-1 inline-block">
                        Ajouter votre premier artiste →
                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Dernières demandes en attente -->
        <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
            <div class="p-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Demandes en attente</h2>
                <a href="<?php echo e(route('studio.requests')); ?>" class="text-xs text-beige-peau hover:underline">Toutes →</a>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $latestRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-ivoire-text truncate">
                            <?php echo e($request->client?->first_name); ?> <?php echo e($request->client?->last_name); ?>

                        </p>
                        <p class="text-xs text-titane mt-0.5">
                            → <?php echo e($request->bookable?->user?->name ?? 'Artiste'); ?>

                            • <?php echo e($request->created_at?->diffForHumans()); ?>

                        </p>
                    </div>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-500/20 text-yellow-400 font-semibold shrink-0">
                        En attente
                    </span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-titane text-center py-6">Aucune demande en attente</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\dashboard.blade.php ENDPATH**/ ?>
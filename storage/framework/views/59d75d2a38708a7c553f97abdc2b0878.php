<div class="space-y-6">
    
    <?php
        $checklist = $studio->getOnboardingChecklist();
        $progress = $studio->onboardingProgress();
        $showChecklist = $studio->onTrial() && !$studio->onboardingComplete();
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showChecklist): ?>
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 border border-beige-peau/20 mb-8">
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
                        <span
                            class="text-sm <?php echo e($step['done'] ? 'text-titane line-through' : 'text-ivoire-text font-medium'); ?>">
                            <?php echo e($step['label']); ?>

                        </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$step['done']): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($step['key']):
                                case ('logo'): ?>
                                    <a href="<?php echo e(route('studio.settings')); ?>"
                                        class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                <?php break; ?>

                                <?php case ('artist'): ?>
                                    <a href="<?php echo e(route('studio.artists.create')); ?>"
                                        class="ml-auto text-xs text-beige-peau hover:underline">Ajouter →</a>
                                <?php break; ?>

                                <?php case ('payment'): ?>
                                    <a href="<?php echo e(route('studio.settings')); ?>"
                                        class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                <?php break; ?>

                                <?php case ('profile'): ?>
                                    <a href="<?php echo e(route('studio.settings')); ?>"
                                        class="ml-auto text-xs text-beige-peau hover:underline">Personnaliser →</a>
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

    
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Tableau de bord</h1>
        <p class="text-sm text-titane mt-1"><?php echo e($studio->name); ?></p>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Artistes</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1"><?php echo e($artistCount); ?></p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->onTrial()): ?>
            <div class="bg-ambre-warning/20 border border-ambre-warning/30 rounded-xl p-4">
                <p class="text-xs text-titane uppercase tracking-wider">⏱️ Essai restant</p>
                <p class="text-lg font-bold text-ambre-warning mt-1"><?php echo e($studio->trialDaysLeft()); ?> jours</p>
                <p class="text-xs text-titane mt-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio->trialDaysLeft() > 0): ?>
                        Reste <?php echo e($studio->trialDaysLeft()); ?> jours avant la fin de la période d'essai
                    <?php else: ?>
                        Essai expiré - <a href="<?php echo e(route('studio.subscribe')); ?>"
                            class="text-beige-peau hover:underline">S'abonner</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="bg-gris-fonde rounded-xl p-4">
                <p class="text-xs text-titane uppercase tracking-wider">Ce mois</p>
                <p class="text-2xl font-bold text-beige-peau mt-1"><?php echo e(number_format($monthlyPrice, 2)); ?>€</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Demandes en cours</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1"><?php echo e($pendingRequests); ?></p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">RDV aujourd'hui</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1"><?php echo e($todayAppointments); ?></p>
        </div>
    </div>

    
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">👥 Artistes</h2>
            <a href="<?php echo e(route('studio.artists')); ?>"
                class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">Gérer →</a>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studioArtist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 py-3 <?php echo e(!$loop->last ? 'border-b border-titane/10' : ''); ?>">
                <img src="<?php echo e($studioArtist->user?->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                    alt="<?php echo e($studioArtist->user?->name); ?>" class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">
                        <?php echo e($studioArtist->user?->name ?? 'Invitation en attente'); ?></p>
                    <p class="text-xs text-titane">
                        <?php echo e($studioArtist->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur'); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$studioArtist->is_active): ?>
                            <span class="text-rouge-alerte ml-1">• Inactif</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-titane">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studioArtist->user): ?>
                            Actif
                        <?php else: ?>
                            ⏳ En attente
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-sm text-titane text-center py-4">
                Aucun artiste.
                <a href="<?php echo e(route('studio.artists.create')); ?>" class="text-beige-peau hover:underline">Ajouter un
                    artiste</a>
            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <a href="<?php echo e(route('studio.artists.create')); ?>"
            class="bg-beige-peau text-noir-profond rounded-xl p-4 font-semibold text-center hover:bg-beige-peau/90 transition-colors active:scale-95">
            + Ajouter un artiste
        </a>
        <a href="<?php echo e(route('studio.planning')); ?>"
            class="bg-gris-fonde text-ivoire-text rounded-xl p-4 font-semibold text-center hover:bg-gris-fonde/80 transition-colors border border-titane/20">
            📅 Voir le planning
        </a>
    </div>

    
    <div class="bg-gris-fonde/50 rounded-xl p-4 border border-titane/10">
        <p class="text-xs text-titane">
            💡 Abonnement Studio : <strong class="text-ivoire-text">1 artiste inclus</strong>.
            Artistes supplémentaires : <strong class="text-beige-peau">39,99€/mois</strong> chacun.
            <a href="<?php echo e(route('studio.billing')); ?>" class="text-beige-peau hover:underline ml-1">Voir la facturation
                →</a>
        </p>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/studio/dashboard.blade.php ENDPATH**/ ?>
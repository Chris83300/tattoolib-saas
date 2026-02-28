<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Tableau de bord</h1>
        <p class="text-sm text-titane mt-1"><?php echo e($studio->name); ?></p>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Artistes</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1"><?php echo e($artistCount); ?></p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Ce mois</p>
            <p class="text-2xl font-bold text-beige-peau mt-1"><?php echo e(number_format($monthlyPrice, 2)); ?>€</p>
        </div>
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
            <a href="<?php echo e(route('studio.artists')); ?>" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">Gérer →</a>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $studioArtist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex items-center gap-3 py-3 <?php echo e(!$loop->last ? 'border-b border-titane/10' : ''); ?>">
                <img src="<?php echo e($studioArtist->user?->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                    alt="<?php echo e($studioArtist->user?->name); ?>"
                    class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate"><?php echo e($studioArtist->user?->name ?? 'Invitation en attente'); ?></p>
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
                <a href="<?php echo e(route('studio.artists.create')); ?>" class="text-beige-peau hover:underline">Ajouter un artiste</a>
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
            <a href="<?php echo e(route('studio.billing')); ?>" class="text-beige-peau hover:underline ml-1">Voir la facturation →</a>
        </p>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/studio/dashboard.blade.php ENDPATH**/ ?>
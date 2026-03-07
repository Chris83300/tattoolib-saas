<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-ivoire-text">Artistes</h1>
                <p class="text-sm text-titane mt-1">
                    <?php echo e($activeArtists->count()); ?> artiste<?php echo e($activeArtists->count() > 1 ? 's' : ''); ?>

                    actif<?php echo e($activeArtists->count() > 1 ? 's' : ''); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paidArtistCount > 0): ?>
                        <span class="text-beige-peau">(dont <?php echo e($paidArtistCount); ?>

                            supplémentaire<?php echo e($paidArtistCount > 1 ? 's' : ''); ?> à 24,99€/mois)</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canAddArtist): ?>
                <a href="<?php echo e(route('studio.artists.create')); ?>"
                    class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                    + Ajouter
                </a>
            <?php elseif($needsSubscriptionForNewArtist): ?>
                <a href="<?php echo e(route('studio.subscribe')); ?>"
                    class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                    🔓 Souscrire pour ajouter
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activeArtists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php if (isset($component)) { $__componentOriginal8fc052fcae0ec32d28b41c64f25fb4fa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fc052fcae0ec32d28b41c64f25fb4fa = $attributes; } ?>
<?php $component = App\View\Components\Ui\ArtistCard::resolve(['studioArtist' => $sa] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-12">
                    <div class="text-titane text-lg mb-4">
                        Aucun artiste actif dans votre studio
                    </div>
                    <a href="<?php echo e(route('studio.artists.create')); ?>"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter votre premier artiste
                    </a>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingInvitations->count() > 0): ?>
            <div class="mt-8">
                <h2 class="text-lg font-bold text-ivoire-text mb-4">⏳ Invitations en attente</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pendingInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal8fc052fcae0ec32d28b41c64f25fb4fa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8fc052fcae0ec32d28b41c64f25fb4fa = $attributes; } ?>
<?php $component = App\View\Components\Ui\ArtistCard::resolve(['studioArtist' => $inv] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="bg-gris-fonde/50 rounded-xl p-4 border border-titane/10">
            <p class="text-xs text-titane">
                💡 Votre abonnement Studio inclut <strong class="text-ivoire-text">1 artiste</strong>.
                Chaque artiste supplémentaire coûte <strong class="text-beige-peau">24,99€/mois</strong>.
                Facturation actuelle : <strong class="text-ivoire-text"><?php echo e(number_format($monthlyPrice, 2)); ?>€/mois</strong>
            </p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\artists.blade.php ENDPATH**/ ?>
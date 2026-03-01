<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Planning</h1>
    <p class="text-sm text-titane">Vue globale des rendez-vous de tous vos artistes</p>

    
    <div class="bg-gris-fonde rounded-xl p-6 text-center">
        <p class="text-sm text-titane">📅 Le planning global sera disponible prochainement.</p>
        <p class="text-xs text-titane/60 mt-2">En attendant, chaque artiste peut gérer son propre calendrier depuis son dashboard.</p>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($artists) && $artists->count() > 0): ?>
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">👥 Artistes actifs</h2>
            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sa->user): ?>
                        <div class="flex items-center gap-3 py-2 border-b border-titane/10 last:border-0">
                            <img src="<?php echo e($sa->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                                alt="<?php echo e($sa->user->name); ?>" class="w-8 h-8 rounded-full object-cover">
                            <span class="text-sm text-ivoire-text"><?php echo e($sa->user->name); ?></span>
                            <span class="text-xs text-titane"><?php echo e($sa->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur'); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/studio/planning.blade.php ENDPATH**/ ?>
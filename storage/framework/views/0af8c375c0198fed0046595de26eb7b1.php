<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-ivoire-text mb-6">Dashboard Studio</h1>
    
    <!-- Stats globales -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
            <div class="text-beige-peau text-2xl font-bold"><?php echo e($totalArtists); ?></div>
            <div class="text-ivoire-text/70">Artistes total</div>
        </div>
        
        <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
            <div class="text-beige-peau text-2xl font-bold"><?php echo e($activeArtists); ?></div>
            <div class="text-ivoire-text/70">Artistes actifs</div>
        </div>
        
        <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
            <div class="text-beige-peau text-2xl font-bold">€<?php echo e(number_format($totalRevenue, 2)); ?></div>
            <div class="text-ivoire-text/70">Revenu total</div>
        </div>
    </div>
    
    <!-- Artistes récents -->
    <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
        <h2 class="text-xl font-bold text-beige-peau mb-4">Artistes</h2>
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-noir-profond rounded-lg p-4 border border-ivoire-text/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-beige-peau"><?php echo e($artist->user->name); ?></h3>
                            <p class="text-ivoire-text/70 text-sm"><?php echo e($artist->role); ?></p>
                        </div>
                        <div class="text-ivoire-text/70 text-sm">
                            Rejoint le <?php echo e($artist->joined_at->format('d/m/Y')); ?>

                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\dashboard.blade.php ENDPATH**/ ?>
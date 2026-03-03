<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Statistiques</h1>

    
    <div class="bg-gris-fonde rounded-xl p-6 text-center">
        <p class="text-sm text-titane">📈 Les statistiques détaillées seront accessibles depuis votre panel de gestion avancé.</p>
        <p class="text-xs text-titane/60 mt-2">Disponible prochainement.</p>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">Studio</p>
            <p class="text-lg font-bold text-ivoire-text"><?php echo e($studio->name); ?></p>
            <p class="text-xs text-titane mt-1"><?php echo e($studio->city ?? '—'); ?></p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider mb-1">Abonnement mensuel</p>
            <p class="text-lg font-bold text-beige-peau"><?php echo e(number_format($studio->monthlyPrice(), 2)); ?>€</p>
            <p class="text-xs text-titane mt-1"><?php echo e($studio->artistCount()); ?> artiste(s) actif(s)</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/studio/stats.blade.php ENDPATH**/ ?>
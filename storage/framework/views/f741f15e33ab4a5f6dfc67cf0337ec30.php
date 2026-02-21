<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-ivoire-text mb-6">Dashboard Artiste</h1>
    
    <!-- Navigation -->
    <div class="bg-gris-fonde rounded-xl p-4 mb-6">
        <div class="flex space-x-4">
            <div class="flex-1">
                <a href="<?php echo e(route('studio.dashboard')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12h13l9 9 13 4h9m-9 13 4 018 0z"></path>
                    </svg>
                    Dashboard
                </a>
            </div>
            <div class="flex-1">
                <a href="<?php echo e(route('studio.artists')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h13v9a2 2 011-8h4a2 2 011-8z"></path>
                    </svg>
                    Artistes
                </a>
            </div>
            <div class="flex-1">
                <a href="<?php echo e(route('studio.planning')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m2a2 2 011-8h4a2 2 011-8z"></path>
                    </svg>
                    Planning
                </a>
            </div>
            <div class="flex-1">
                <a href="<?php echo e(route('studio.requests')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H6a2 2 011-8h4a2 2 011-8z"></path>
                    </svg>
                    Demandes
                </a>
            </div>
            <div class="flex-1">
                <a href="<?php echo e(route('studio.transactions')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9h16m-1 1.01-1H4a2 2 011-8z"></path>
                    </svg>
                    Transactions
                </a>
            </div>
            <div class="flex-1">
                <a href="<?php echo e(route('studio.stats')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19h16m-1 1.01-1H4a2 2 011-8z"></path>
                    </svg>
                    Statistiques
                </a>
            </div>
            <div class="flex-1">
                <a href="<?php echo e(route('studio.exports')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7h16a2 2 011-8z"></path>
                    </svg>
                    Exports
                </a>
            </div>
            <div class="flex-1">
                <a href="<?php echo e(route('studio.settings')); ?>" class="text-beige-peau hover:text-beige-peau/90 px-3 py-2 rounded-lg">
                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.293 3.293a2 2 011-8z"></path>
                    </svg>
                    Paramètres
                </a>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\dashboard-artist.blade.php ENDPATH**/ ?>
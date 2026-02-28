<?php $__env->startSection('title', 'Activer votre studio'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-lg mx-auto space-y-6 py-6">
    <div class="text-center">
        <h1 class="text-2xl font-bold text-ivoire-text">Activez votre studio</h1>
        <p class="text-sm text-titane mt-2">Continuez à utiliser toutes les fonctionnalités sans interruption.</p>
    </div>

    <div class="bg-gris-fonde rounded-2xl p-6 space-y-4 border border-titane/20">
        <h2 class="text-lg font-bold text-ivoire-text text-center">Studio Ink&Pik</h2>

        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-ivoire-text">Abonnement Studio</span>
                <span class="text-sm font-semibold text-ivoire-text">79,99€/mois</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-titane">1 artiste inclus</span>
                <span class="text-sm text-green-400">✓ Inclus</span>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paidArtistCount > 0): ?>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-ivoire-text">
                        <?php echo e($paidArtistCount); ?> artiste<?php echo e($paidArtistCount > 1 ? 's' : ''); ?> supplémentaire<?php echo e($paidArtistCount > 1 ? 's' : ''); ?>

                    </span>
                    <span class="text-sm font-semibold text-beige-peau">
                        <?php echo e(number_format($paidArtistCount * 39.99, 2, ',', ' ')); ?>€/mois
                    </span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="border-t border-titane/20 pt-3 flex justify-between items-center">
                <span class="font-bold text-ivoire-text">Total</span>
                <span class="text-xl font-bold text-beige-peau">
                    <?php echo e(number_format($monthlyPrice, 2, ',', ' ')); ?>€<span class="text-sm text-titane font-normal">/mois</span>
                </span>
            </div>
        </div>

        <div class="space-y-2 pt-2">
            <p class="text-xs text-titane">✓ Dashboard complet et gestion avancée</p>
            <p class="text-xs text-titane">✓ Traçabilité et fiches clients</p>
            <p class="text-xs text-titane">✓ Visibilité marketplace</p>
            <p class="text-xs text-titane">✓ Stripe Connect intégré</p>
            <p class="text-xs text-titane">✓ Sans engagement, résiliable à tout moment</p>
        </div>

        <form action="<?php echo e(route('studio.subscribe.process')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit"
                class="w-full py-3.5 bg-beige-peau text-noir-profond rounded-xl font-bold text-base hover:bg-beige-peau/90 transition-colors active:scale-95">
                Activer l'abonnement
            </button>
        </form>

        <p class="text-xs text-titane text-center">Paiement sécurisé par Stripe. Facture mensuelle automatique.</p>
    </div>

    <div class="text-center">
        <a href="<?php echo e(route('studio.billing')); ?>" class="text-xs text-titane hover:text-ivoire-text">← Retour à la facturation</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/studio/subscribe.blade.php ENDPATH**/ ?>
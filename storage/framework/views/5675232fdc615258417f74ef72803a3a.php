<?php $__env->startSection('title', 'Demande envoyée - Ink&Pik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="bg-noir-profond min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full text-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="bg-vert-succes/20 border border-vert-succes/50 text-vert-succes px-4 py-3 rounded-lg text-sm mb-6">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="w-24 h-24 bg-vert-succes/20 rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-12 h-12 text-vert-succes" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
            </div>

            <h1 class="text-3xl md:text-4xl font-bold text-ivoire-text mb-4">
                Demande envoyée
            </h1>

            <p class="text-lg text-ivoire-text/80 mb-8">
                Votre demande a bien été transmise. L'artiste vous répondra rapidement.
            </p>

            <div class="bg-gris-fonde rounded-xl p-6 md:p-8 mb-8 text-left">
                <h2 class="text-xl font-bold text-ivoire-text mb-4">Prochaines étapes</h2>
                <div class="space-y-3 text-ivoire-text/70 text-sm">
                    <div class="flex items-start gap-3">
                        <span class="text-beige-peau font-bold">1.</span>
                        <span>L'artiste reçoit votre demande et l'étudie</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-beige-peau font-bold">2.</span>
                        <span>Vous serez contacté pour confirmer les détails et une date</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-beige-peau font-bold">3.</span>
                        <span>Vous pourrez ensuite échanger par message pour finaliser le projet</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('marketplace.index')); ?>"
                    class="px-8 py-4 bg-beige-peau text-noir-profond rounded-lg font-bold hover:bg-beige-peau/90 transition-colors">
                    Retour à la marketplace
                </a>
                <a href="<?php echo e(route('home')); ?>"
                    class="px-8 py-4 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-bold hover:bg-noir-profond/80 transition-colors">
                    Accueil
                </a>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/booking-request/success.blade.php ENDPATH**/ ?>
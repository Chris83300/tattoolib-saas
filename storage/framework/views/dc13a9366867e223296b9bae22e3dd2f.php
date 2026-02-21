<?php $__env->startSection('title', 'Paiement annulé'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-titane/20 rounded-xl border border-titane/30 p-8 text-center">
                <!-- Icône d'annulation -->
                <div class="inline-flex items-center justify-center w-16 h-16 bg-jaune-alerte/20 rounded-full mb-6">
                    <svg class="w-8 h-8 text-jaune-alerte" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <h1 class="text-3xl font-bold text-ivoire-text mb-4">Paiement annulé</h1>

                <p class="text-ivoire-text/70 mb-8">
                    Le paiement de l'acompte a été annulé. Vous pouvez réessayer plus tard si vous le souhaitez.
                </p>

                <!-- Actions -->
                <div class="space-y-4">
                    <a href="<?php echo e(route('deposit.payment', $bookingRequest)); ?>"
                        class="inline-flex items-center px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Réessayer le paiement
                    </a>

                    <div class="pt-4">
                        <a href="<?php echo e(route('client.booking-request.show', $bookingRequest)); ?>"
                            class="text-ivoire-text/60 hover:text-ivoire-text transition-colors">
                            ← Retour à la demande
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\deposit-cancel.blade.php ENDPATH**/ ?>
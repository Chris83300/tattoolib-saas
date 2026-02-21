<?php $__env->startSection('content'); ?>
    <div class="max-w-lg mx-auto py-8 px-4">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-vert-succes rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-noir-profond mb-2">Paiement réussi !</h1>
            <p class="text-noir-profond/70">Votre paiement du solde a été traité avec succès.</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="font-semibold text-noir-profond mb-4">Récapitulatif</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-noir-profond/70">Référence</span>
                    <span class="font-medium">#<?php echo e($bookingRequest->id); ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-noir-profond/70">Statut</span>
                    <span class="font-medium text-vert-succes">Prestation complète</span>
                </div>
            </div>
        </div>

        <div class="space-y-3">
            <a href="<?php echo e(route('client.dashboard')); ?>"
                class="block w-full py-3 bg-noir-profond text-white rounded-xl font-semibold text-center hover:bg-noir-profond/90 transition">
                Retour au tableau de bord
            </a>
            <a href="<?php echo e(route('client.booking-request.show', $bookingRequest)); ?>"
                class="block w-full py-3 border border-noir-profond text-noir-profond rounded-xl font-semibold text-center hover:bg-noir-profond/10 transition">
                Voir les détails
            </a>
        </div>
    </div>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal)): ?>
<?php $attributes = $__attributesOriginal; ?>
<?php unset($__attributesOriginal); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal)): ?>
<?php $component = $__componentOriginal; ?>
<?php unset($__componentOriginal); ?>
<?php endif; ?>

<?php echo $__env->make('layouts.client', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\balance-payment-success.blade.php ENDPATH**/ ?>
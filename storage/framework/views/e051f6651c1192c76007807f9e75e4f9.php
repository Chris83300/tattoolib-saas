<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="bg-gris-fonde rounded-xl p-8 text-center">
            <!-- Icône -->
            <div class="w-20 h-20 bg-ambre-warning/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-ambre-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Titre -->
            <h1 class="text-2xl font-bold text-ivoire-text mb-4">
                Inscription en cours de validation
            </h1>

            <!-- Message -->
            <p class="text-ivoire-text/70 mb-6">
                Votre compte pierceur a bien été créé et est actuellement en attente de validation par notre équipe.
            </p>

            <p class="text-ivoire-text/70 mb-8">
                Vous recevrez un email dès que votre compte sera validé.
            </p>

            <!-- Actions -->
            <div class="space-y-4">
                <a href="<?php echo e(route('login')); ?>" 
                   class="block w-full px-6 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                    Se connecter
                </a>
                
                <a href="<?php echo e(route('register')); ?>" 
                   class="block w-full px-6 py-3 border border-titane text-ivoire-text hover:bg-titane/10 font-semibold rounded-lg transition-colors">
                    Retour à l'accueil
                </a>
            </div>

            <!-- Infos -->
            <div class="mt-8 p-4 bg-beige-peau/5 rounded-lg">
                <p class="text-ivoire-text/60 text-sm">
                    <strong>Informations :</strong><br>
                    • Validation sous 24-48h<br>
                    • Vérification de votre SIRET<br>
                    • Email de confirmation envoyé
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\auth\pending-verification-pierceur.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full text-center">

            <!-- Header -->
            <div class="mb-8">
                <a href="/" class="text-beige-peau font-display text-2xl font-bold">
                    Ink&Pik
                </a>
            </div>

            <!-- Icone d'attente -->
            <div class="w-20 h-20 mx-auto mb-6 bg-ambre-warning/20 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-ambre-warning animate-pulse" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Titre -->
            <h1 class="text-2xl font-display font-bold text-ivoire-text mb-4">
                Inscription en cours de validation
            </h1>

            <!-- Message -->
            <div class="bg-gris-fonde rounded-xl p-6 mb-6">
                <p class="text-ivoire-text mb-4">
                    Bonjour <?php echo e(auth()->user()->displayName()); ?>,
                </p>

                <p class="text-ivoire-text/70 mb-4">
                    Votre compte <?php echo e($role); ?> a bien été créé et est actuellement en attente de validation par notre
                    équipe.
                </p>

                <div class="space-y-3 text-left">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-vert-succes mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-ivoire-text font-medium">SIRET vérifié</p>
                            <p class="text-ivoire-text/50 text-sm">Votre numéro SIRET a été validé avec succès</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-ambre-warning mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        <div>
                            <p class="text-ivoire-text font-medium">Validation manuelle</p>
                            <p class="text-ivoire-text/50 text-sm">Notre équipe vérifie vos informations professionnelles
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Délai -->
            <div class="bg-beige-peau/5 border border-beige-peau/30 rounded-lg p-4 mb-6">
                <p class="text-beige-peau font-medium mb-1">
                    ⏱️ Délai de validation
                </p>
                <p class="text-ivoire-text/70 text-sm">
                    Généralement sous 24-48h ouvrées
                </p>
            </div>

            <!-- Actions -->
            <div class="space-y-3">
                <button onclick="window.location.reload()"
                    class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold py-3 rounded-lg transition-colors">
                    Vérifier le statut
                </button>

                <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')"
                        class="w-full border border-titane text-ivoire-text hover:border-beige-peau hover:text-beige-peau font-semibold py-3 rounded-lg transition-colors">
                        Se déconnecter
                    </button>
                </form>
            </div>

            <!-- Contact -->
            <div class="mt-6">
                <p class="text-ivoire-text/50 text-sm">
                    Questions ? Contactez-nous à
                    <a href="mailto:support@inkpik.fr" class="text-beige-peau hover:underline">
                        support@inkpik.fr
                    </a>
                </p>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\auth\pending-verification.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Profil en attente de validation - Ink&Pik'); ?>

<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
        <div class="max-w-md w-full">

            <!-- Card principale -->
            <div class="bg-gris-fonde rounded-2xl p-8 text-center">

                <!-- Icône d'attente -->
                <div class="w-20 h-20 bg-beige-peau/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-beige-peau animate-pulse" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Titre personnalisé -->
                <h1 class="text-2xl font-bold text-ivoire-text mb-2">
                    Bonjour <?php echo e($artist->user->first_name); ?> <?php echo e($artist->user->last_name); ?> !
                </h1>

                <h2 class="text-xl font-semibold text-beige-peau mb-4">
                    Profil en attente de validation
                </h2>

                <!-- Message principal -->
                <p class="text-ivoire-text/70 mb-6 leading-relaxed">
                    Votre profil est actuellement en cours de validation par notre équipe.
                    Nous vérifions vos informations pour garantir la qualité et la sécurité de la plateforme.
                </p>

                <!-- URL future du profil -->
                <div class="bg-noir-profond rounded-xl p-4 mb-6">
                    <p class="text-ivoire-text/50 text-sm mb-2">Votre futur URL publique :</p>
                    <p class="text-beige-peau font-mono text-sm break-all">
                        <?php echo e(url('/artistes/' . $artist->slug)); ?>

                    </p>
                </div>

                <!-- Détails du processus -->
                <div class="bg-noir-profond rounded-xl p-4 mb-6 text-left">
                    <h3 class="text-beige-peau font-semibold mb-3">Notre processus de validation :</h3>
                    <ul class="space-y-2 text-sm text-ivoire-text/80">
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-beige-peau mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Vérification de votre identité et SIRET</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-beige-peau mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Validation de vos compétences et portfolio</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-4 h-4 text-beige-peau mt-0.5 flex-shrink-0" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Contrôle de conformité réglementaire</span>
                        </li>
                    </ul>
                </div>

                <!-- Délai -->
                <div class="bg-beige-peau/10 border border-beige-peau/30 rounded-lg p-4 mb-6">
                    <p class="text-beige-peau font-semibold mb-1">
                        ⏱️ Délai de traitement
                    </p>
                    <p class="text-ivoire-text/70 text-sm">
                        Généralement sous 24-48h ouvrés
                    </p>
                </div>

                <!-- Actions -->
                <div class="space-y-3">
                    <!-- Prévisualiser le profil (mode brouillon) -->
                    <button
                        onclick="window.open('<?php echo e(route('marketplace.tattooer.show', $artist->slug)); ?>?preview=true', '_blank')"
                        class="block w-full px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        👁️ Prévisualiser mon profil (brouillon)
                    </button>

                    <!-- Retour dashboard -->
                    <a href="<?php echo e(route('tattooer.dashboard')); ?>"
                        class="block w-full px-6 py-3 bg-noir-profond text-ivoire-text rounded-lg font-semibold hover:bg-noir-profond/80 transition-colors">
                        ← Retour à mon espace
                    </a>

                    <!-- Contact support -->
                    <button
                        onclick="window.location.href='mailto:support@ink-pik.com?subject=Question validation profil - <?php echo e($artist->user->name); ?>'"
                        class="block w-full px-6 py-3 border border-beige-peau text-beige-peau rounded-lg font-semibold hover:bg-beige-peau/10 transition-colors">
                        📧 Contacter le support
                    </button>
                </div>

                <!-- Notification -->
                <p class="text-ivoire-text/50 text-xs mt-6">
                    Vous recevrez un email dès que votre profil sera validé.
                </p>

            </div>

            <!-- Infos complémentaires -->
            <div class="mt-6 text-center">
                <p class="text-ivoire-text/50 text-sm">
                    Pendant l'attente, vous pouvez :
                </p>
                <ul class="text-ivoire-text/70 text-sm mt-2 space-y-1">
                    <li>✏️ Compléter votre profil</li>
                    <li>📸 Ajouter des photos à votre portfolio</li>
                    <li>⚙️ Configurer vos horaires</li>
                </ul>
            </div>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <style>
        /* Animation personnalisée pour l'icône d'attente */
        @keyframes gentle-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.05);
            }
        }

        .animate-pulse {
            animation: gentle-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\artists\pending-validation.blade.php ENDPATH**/ ?>
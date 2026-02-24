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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 'studio'): ?>
                    Votre studio est en cours de validation
                <?php elseif($role === 'pierceur'): ?>
                    Votre compte pierceur est en cours de validation
                <?php else: ?>
                    Votre compte tatoueur est en cours de validation
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </h1>

            <!-- Message -->
            <div class="bg-gris-fonde rounded-xl p-6 mb-6">
                <p class="text-ivoire-text mb-4">
                    Bonjour <?php echo e(auth()->user()->displayName()); ?>,
                </p>

                <p class="text-ivoire-text/70 mb-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 'studio'): ?>
                        Votre studio a bien été créé et est actuellement en attente de validation par notre équipe.
                    <?php elseif($role === 'pierceur'): ?>
                        Votre compte pierceur a bien été créé et est actuellement en attente de validation par notre équipe.
                    <?php else: ?>
                        Votre compte tatoueur a bien été créé et est actuellement en attente de validation par notre équipe.
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>

                <div class="space-y-3 text-left">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 'studio' || $role === 'pierceur'): ?>
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
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

            <!-- Informations reçues -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 'studio'): ?>
                <div class="bg-noir-profond rounded-lg p-6 mb-6 text-left">
                    <h3 class="text-ivoire-text font-semibold mb-3">Informations reçues :</h3>
                    <ul class="space-y-2 text-sm text-ivoire-text/70">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong class="text-ivoire-text">SIRET :</strong>
                                <?php echo e(auth()->user()->studio?->siret ?? 'Non renseigné'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong class="text-ivoire-text">Nom du studio :</strong>
                                <?php echo e(auth()->user()->studio?->name ?? 'Non renseigné'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong class="text-ivoire-text">Gérant :</strong>
                                <?php echo e(auth()->user()->studio?->first_name ?? auth()->user()->first_name); ?>

                                <?php echo e(auth()->user()->studio?->last_name ?? auth()->user()->last_name); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong class="text-ivoire-text">Ville :</strong>
                                <?php echo e(auth()->user()->studio?->city ?? 'Non renseignée'); ?></span>
                        </li>
                    </ul>
                </div>
            <?php elseif($role === 'pierceur'): ?>
                <div class="bg-noir-profond rounded-lg p-6 mb-6 text-left">
                    <h3 class="text-ivoire-text font-semibold mb-3">Informations reçues :</h3>
                    <ul class="space-y-2 text-sm text-ivoire-text/70">
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong class="text-ivoire-text">SIRET :</strong>
                                <?php echo e(auth()->user()->piercer?->siret ?? 'Non renseigné'); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong class="text-ivoire-text">Nom :</strong>
                                <?php echo e(auth()->user()->piercer?->name ?? auth()->user()->name); ?></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><strong class="text-ivoire-text">Ville :</strong>
                                <?php echo e(auth()->user()->piercer?->city ?? 'Non renseignée'); ?></span>
                        </li>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
                <a href="<?php if($role === 'studio'): ?> <?php echo e(route('studio.profile')); ?><?php elseif($role === 'pierceur'): ?><?php echo e(route('piercer.profile')); ?><?php else: ?><?php echo e(route('tattooer.profile')); ?> <?php endif; ?>"
                    class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold py-3 rounded-lg transition-colors text-center block">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role === 'studio'): ?>
                        Accéder à mon profil studio
                    <?php elseif($role === 'pierceur'): ?>
                        Accéder à mon profil pierceur
                    <?php else: ?>
                        Accéder à mon profil tatoueur
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/auth/pending-verification.blade.php ENDPATH**/ ?>
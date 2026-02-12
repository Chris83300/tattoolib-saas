<?php $__env->startSection('title', 'Conformité - Ink&Pik'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-noir-profond py-8">
    <div class="container-custom px-4">
        <div class="max-w-4xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-display font-bold text-ivoire-text mb-4">
                    Badge de Conformité
                </h1>
                <p class="text-xl text-ivoire-text/70">
                    Obtenez votre certification officielle Ink&Pik
                </p>
            </div>

            <!-- Badge actuel -->
            <div class="bg-gris-fonde rounded-xl p-8 mb-8 text-center">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->tattooer?->is_verified): ?>
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-2 bg-vert-succes/20 border border-vert-succes text-vert-succes px-6 py-3 rounded-full font-bold">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            Badge Conforme Ink&Pik Actif
                        </div>
                    </div>
                    <p class="text-ivoire-text">
                        Votre profil est certifié conforme aux normes ARS et d'hygiène.
                    </p>
                <?php else: ?>
                    <div class="mb-6">
                        <div class="inline-flex items-center gap-2 bg-titane/20 border border-titane text-titane px-6 py-3 rounded-full font-bold">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            Badge Non Actif
                        </div>
                    </div>
                    <p class="text-ivoire-text/70 mb-6">
                        Completez les étapes ci-dessous pour obtenir votre badge de conformité.
                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Étapes de conformité -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!auth()->user()->tattooer?->is_verified): ?>
            <div class="space-y-6">
                <!-- Étape 1 : SIRET -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <?php if(auth()->user()->tattooer?->siret): ?>
                                <div class="w-8 h-8 bg-vert-succes text-noir-profond rounded-full flex items-center justify-center font-bold">
                                    ✓
                                </div>
                            <?php else: ?>
                                <div class="w-8 h-8 bg-titane text-ivoire-text rounded-full flex items-center justify-center font-bold">
                                    1
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-ivoire-text mb-2">Numéro SIRET</h3>
                            <p class="text-ivoire-text/70 mb-3">
                                Indiquez votre numéro SIRET pour vérifier votre statut professionnel.
                            </p>
                            <?php if(auth()->user()->tattooer?->siret): ?>
                                <p class="text-vert-succes font-semibold">✓ SIRET renseigné</p>
                            <?php else: ?>
                                <a href="<?php echo e(route('tattooer.profile')); ?>" class="text-beige-peau hover:underline">
                                    Renseigner mon SIRET →
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Étape 2 : Documents ARS -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-titane text-ivoire-text rounded-full flex items-center justify-center font-bold">
                                2
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-ivoire-text mb-2">Documents Légaux</h3>
                            <p class="text-ivoire-text/70 mb-3">
                                Téléchargez vos documents de conformité (ARS, Hygiène, etc.).
                            </p>
                            <a href="<?php echo e(route('tattooer.profile')); ?>" class="text-beige-peau hover:underline">
                                Télécharger mes documents →
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Étape 3 : Vérification -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-titane text-ivoire-text rounded-full flex items-center justify-center font-bold">
                                3
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-ivoire-text mb-2">Vérification admin</h3>
                            <p class="text-ivoire-text/70 mb-3">
                                Notre équipe vérifiera vos documents dans un délai de 48h.
                            </p>
                            <p class="text-ivoire-text/60 text-sm">
                                Vous recevrez une notification une fois la validation effectuée.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bouton d'action -->
            <div class="text-center mt-8">
                <a href="<?php echo e(route('tattooer.profile')); ?>" class="inline-block px-8 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                    Compléter mon profil
                </a>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Avantages du badge -->
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-ivoire-text mb-8 text-center">
                    Pourquoi obtenir le badge ?
                </h2>
                <div class="grid md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-ivoire-text font-bold mb-2">Confiance client</h3>
                        <p class="text-ivoire-text/60 text-sm">
                            Les clients font confiance aux artistes certifiés
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-ivoire-text font-bold mb-2">Visibilité accrue</h3>
                        <p class="text-ivoire-text/60 text-sm">
                            Mise en avant dans la recherche et plus de demandes
                        </p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c1.11 0 2.08-.402 2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-ivoire-text font-bold mb-2">Tarifs premium</h3>
                        <p class="text-ivoire-text/60 text-sm">
                            Justifiez des tarifs plus élevés avec votre certification
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\tattooer\compliance.blade.php ENDPATH**/ ?>
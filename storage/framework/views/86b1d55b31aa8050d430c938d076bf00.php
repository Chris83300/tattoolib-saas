<?php $__env->startSection('title', 'Professionnels - Ink&Pik'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section / Promesse Principale -->
    <section class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
        <div class="text-center max-w-4xl">
            <!-- Titre principal -->
            <h1 class="text-4xl md:text-6xl font-Satoshi font-bold text-titane mb-6">
                L'outil métier pour les artistes<br>
                <span class="text-ivoire-text">qui prennent leur travail au sérieux</span>
            </h1>

            <!-- Sous-titre -->
            <p class="text-xl md:text-2xl text-ivoire-text/80 mb-8 max-w-3xl mx-auto">
                Ink&Pik sécurise vos rendez-vous, votre temps et votre travail.<br>
                Fini les no-show, les acomptes impayés, le désorganisation.
            </p>

            <!-- Promesses clés -->
            <div class="grid md:grid-cols-3 gap-6 mb-12 max-w-3xl mx-auto">
                <div class="text-center">
                    <div class="text-cuivre font-bold text-lg mb-2">0 no-show</div>
                    <p class="text-ivoire-text/60 text-sm">Acompte obligatoire pour tout rendez-vous</p>
                </div>
                <div class="text-center">
                    <div class="text-cuivre font-bold text-lg mb-2">Temps gagné</div>
                    <p class="text-ivoire-text/60 text-sm">Automatisation de la gestion client</p>
                </div>
                <div class="text-center">
                    <div class="text-cuivre font-bold text-lg mb-2">Cadre pro</div>
                    <p class="text-ivoire-text/60 text-sm">Outils pensés pour votre métier</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg','href' => '/register/tattooer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','href' => '/register/tattooer']); ?>
                    Je suis tatoueur
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg','href' => '/register/piercer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => '/register/piercer']); ?>
                    Je suis pierceur
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Section Fonctionnalités -->
    <section id="features" class="py-20 bg-gris-fonde">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">
                <div class="text-center mb-16">
                    <h2 class="text-4xl md:text-5xl font-display font-bold text-ivoire-text mb-6">
                        Un outil métier, pas un réseau social
                    </h2>
                    <p class="text-xl text-ivoire-text/70 max-w-3xl mx-auto">
                        Des fonctionnalités conçues pour résoudre les vrais problèmes du quotidien
                    </p>
                </div>

                <!-- Fonctionnalités essentielles -->
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    <!-- Booking intelligent -->
                    <div class="bg-noir-profond rounded-xl p-6">
                        <div class="w-12 h-12 bg-beige-peau/10 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-cuivre" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-3">Booking intelligent</h3>
                        <p class="text-ivoire-text/70 mb-3">Acompte obligatoire, validation de projet avant ouverture du
                            chat.</p>
                        <ul class="text-ivoire-text/60 text-sm space-y-1">
                            <li>• Plus de no-show</li>
                            <li>• Filtrage des demandes sérieuses</li>
                            <li>• Paiement sécurisé</li>
                        </ul>
                    </div>

                    <!-- Formulaire projet structuré -->
                    <div class="bg-noir-profond rounded-xl p-6">
                        <div class="w-12 h-12 bg-beige-peau/10 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-cuivre" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-3">Formulaire projet</h3>
                        <p class="text-ivoire-text/70 mb-3">Toutes les infos nécessaires avant de commencer.</p>
                        <ul class="text-ivoire-text/60 text-sm space-y-1">
                            <li>• Budget et style</li>
                            <li>• Zone du corps</li>
                            <li>• Contraintes et allergies</li>
                        </ul>
                    </div>

                    <!-- Chat cadré -->
                    <div class="bg-noir-profond rounded-xl p-6">
                        <div class="w-12 h-12 bg-beige-peau/10 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-cuivre" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-3">Chat projet</h3>
                        <p class="text-ivoire-text/70 mb-3">Communication structurée, pas de DM approximatifs.</p>
                        <ul class="text-ivoire-text/60 text-sm space-y-1">
                            <li>• Contexte projet toujours visible</li>
                            <li>• Suppression après RDV</li>
                            <li>• Professionnalisme garanti</li>
                        </ul>
                    </div>

                    <!-- Fiche client -->
                    <div class="bg-noir-profond rounded-xl p-6">
                        <div class="w-12 h-12 bg-beige-peau/10 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-cuivre" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-3">Fiche client</h3>
                        <p class="text-ivoire-text/70 mb-3">Historique complet et informations essentielles.</p>
                        <ul class="text-ivoire-text/60 text-sm space-y-1">
                            <li>• Consentement signé</li>
                            <li>• Fiche de soin auto-envoyée</li>
                            <li>• Historique permanent</li>
                        </ul>
                    </div>

                    <!-- Avis vérifiés -->
                    <div class="bg-noir-profond rounded-xl p-6">
                        <div class="w-12 h-12 bg-beige-peau/10 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-cuivre" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-3">Avis vérifiés</h3>
                        <p class="text-ivoire-text/70 mb-3">Uniquement après rendez-vous validé.</p>
                        <ul class="text-ivoire-text/60 text-sm space-y-1">
                            <li>• Pas de faux avis</li>
                            <li>• Crédibilité renforcée</li>
                            <li>• Confiance client</li>
                        </ul>
                    </div>

                    <!-- Portfolio pro -->
                    <div class="bg-noir-profond rounded-xl p-6">
                        <div class="w-12 h-12 bg-beige-peau/10 rounded-lg flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-cuivre" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ivoire-text mb-3">Portfolio intégré</h3>
                        <p class="text-ivoire-text/70 mb-3">Vitrine professionnelle de vos réalisations.</p>
                        <ul class="text-ivoire-text/60 text-sm space-y-1">
                            <li>• Classement par style</li>
                            <li>• Partage facile</li>
                            <li>• Visibilité marketplace</li>
                        </ul>
                    </div>
                </div>

                <!-- Fonctionnalités avancées -->
                <div class="text-center mb-12">
                    <h3 class="text-3xl font-bold text-ivoire-text mb-4">Fonctionnalités avancées (plans Pro & Salon)</h3>
                    <p class="text-ivoire-text/70">Pour les professionnels qui veulent aller plus loin</p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-beige-peau/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-cuivre" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-ivoire-text mb-2">Traçabilité complète</h4>
                        <p class="text-ivoire-text/60 text-sm">Encres, aiguilles, lots</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-beige-peau/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-cuivre" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-ivoire-text mb-2">Historique long terme</h4>
                        <p class="text-ivoire-text/60 text-sm">Conservation des échanges</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-beige-peau/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-cuivre" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-ivoire-text mb-2">Statistiques</h4>
                        <p class="text-ivoire-text/60 text-sm">Performance et chiffre d'affaires</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-beige-peau/10 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-cuivre" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-ivoire-text mb-2">Gestion multi-agendas</h4>
                        <p class="text-ivoire-text/60 text-sm">Pour les salons</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php echo $__env->make('professionnels.partials.pricing', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('professionnels.partials.compliance', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Conclusion -->
    <section class="py-20 bg-noir-profond">
        <div class="container-custom px-4">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-display font-bold text-ivoire-text mb-6">
                    Ink&Pik<br>
                    <span class="text-beige-peau">Conçu par et pour les professionnels</span>
                </h2>
                <p class="text-xl text-ivoire-text/80 mb-8">
                    Un outil qui comprend vraiment votre métier, vos contraintes et vos ambitions.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg','href' => '/register/tattooer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','href' => '/register/tattooer']); ?>
                        Découvrir la plateforme
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg','href' => '/register/piercer']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => '/register/piercer']); ?>
                        Créer mon compte
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\professionnels\index.blade.php ENDPATH**/ ?>
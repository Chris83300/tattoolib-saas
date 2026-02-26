<!-- Formules et Tarifs -->
<section id="pricing" class="py-20 bg-noir-profond">
    <div class="container-custom px-4">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-Satoshi font-bold text-beige-peau mb-6">
                    <span class="text-titane">Formules</span> & Tarifs
                </h2>
                <p class="text-xl text-ivoire-text/70 max-w-3xl mx-auto">
                    Des tarifs transparents, sans engagement ni pression commerciale
                </p>
            </div>

            <div class="grid md:grid-cols-3gap-8 mb-12">
                <!-- Formule Free -->
                <div class="bg-gris-fonde rounded-2xl btn-shadow mb-10 overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-8">
                        <h3 class="text-2xl font-bold text-beige-peau mb-2">Free</h3>
                        <p class="text-ivoire-text/70 mb-6">Idéal pour découvrir</p>
                        <div class="mb-8">
                            <span class="text-4xl font-bold text-beige-peau">0€</span>
                            <span class="text-ivoire-text/70">/mois</span>
                            <div class="text-ivoire-text/60 text-sm mt-2">7% de commission sur chaque transaction</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-beige-peau mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>demande de rendez-vous</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-beige-peau mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Portfolio de 20 photos</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-beige-peau mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Planning intéligent</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-titane mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Badges de conformité</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-beige-peau mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Notifications email + app</span>
                            </li>
                        </ul>

                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg','href' => '/register/tattooer','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => '/register/tattooer','class' => 'w-full']); ?>
                            Commencer gratuitement
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

                <!-- Formule Pro -->
                <div class="bg-cuivre/90 btn-shadow border border-cuivre/10 shadow-lg shadow-cuivre text-noir-profond mb-10 rounded-2xl overflow-hidden transform scale-105">
                    <div class="bg-noir-profond text-cuivre text-lg font-bold text-center py-2">
                        LE PLUS CHOISI
                    </div>
                    <div class="p-8">
                        <h3 class="text-4xl text-ivoire-text text-center font-bold mb-2">Pro</h3>
                        <p class="noir-profond text-center font-semibold mb-6">Pour les professionnels établis</p>
                        <div class="mb-8 text-center">
                            <span class="text-4xl text-ivoire-text font-bold">49,99€</span>
                            <span class="text-titane">/mois</span>
                        </div>

                        <div class="bg-ivoire-text/20 rounded-lg p-3 border border-beige-peau/40 mb-6 text-center">
                            <div class="font-bold">0% de commission</div>
                            <div class="noir-profond/60 text-sm">sur toutes les transactions</div>
                        </div>

                        <ul class="space-y-4 mb-8 font-semibold">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Tout inclus Free</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Portfolio illimité</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Traçabilité complète</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Suivi client avancé</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Historique et images conservés</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Statistiques détaillées</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Gestion de stock</span>
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Comptabilité simplifiée</span>
                            </li>
                        </ul>

                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg','href' => '/register/tattooer','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','href' => '/register/tattooer','class' => 'w-full']); ?>
                            Choisir Pro
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

                <!-- Formule Studio -->
                <div class="bg-gris-fonde rounded-2xl btn-shadow overflow-hidden hover:shadow-xl transition-shadow">
                    <div class="p-8">
                        <h3 class="text-4xl font-bold text-beige-peau text-center mb-2">Studio</h3>
                        <p class="text-ivoire-text/70 mb-6 text-center">Pour les équipes et studios</p>
                        <div class="mb-8 text-center">
                            <span class="text-4xl font-bold text-beige-peau">79,99€</span>
                            <span class="text-titane">/mois</span>
                            <div class="text-beige-peau text-sm mt-1">1 artiste inclus</div>
                            <div class="text-ivoire-text/80 text-sm">+ 39,99€ par artiste supplémentaire</div>
                        </div>

                        <ul class="space-y-4 mb-8">
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-ivoire-text">Tout inclus Pro</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-ivoire-text">Multi-planning</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-ivoire-text">Statistiques par artiste</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-ivoire-text">Vision globale direction</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-ivoire-text">Organisation interne</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-ivoire-text">Formation équipe</span>
                            </li>
                            <li class="flex items-center text-ivoire-text/80">
                                <svg class="w-5 h-5 text-vert-succes mr-3 flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-ivoire-text">Et d'autres fonctionnalités avancées</span>
                            </li>
                        </ul>

                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg','href' => '/register/studio','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => '/register/studio','class' => 'w-full']); ?>
                            S'inscrire
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

            <!-- Tableau comparatif simplifié -->
            <div class="bg-gris-fonde rounded-xl btn-shadow p-8">
                <h3 class="text-2xl font-bold text-beige-peau mb-6 text-center">Comparatif rapide</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-titane/20">
                                <th class="text-left py-3 px-4 text-ivoire-text">Fonctionnalité</th>
                                <th class="text-center py-3 px-4 text-ivoire-text">Free</th>
                                <th class="text-center py-3 px-4 text-ivoire-text">Pro</th>
                                <th class="text-center py-3 px-4 text-ivoire-text">Studio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-titane/10">
                                <td class="py-3 px-4 text-ivoire-text/80">Tarif mensuel</td>
                                <td class="text-center py-3 px-4 text-beige-peau font-bold">0€</td>
                                <td class="text-center py-3 px-4 text-beige-peau font-bold">49,99€</td>
                                <td class="text-center py-3 px-4 text-beige-peau font-bold">79,99€</td>
                            </tr>
                            <tr class="border-b border-titane/10">
                                <td class="py-3 px-4 text-ivoire-text/80">Commission</td>
                                <td class="text-center py-3 px-4 text-ivoire-text">7%</td>
                                <td class="text-center py-3 px-4 text-beige-peau font-bold">0%</td>
                                <td class="text-center py-3 px-4 text-beige-peau font-bold">0%</td>
                            </tr>
                            <tr class="border-b border-titane/10">
                                <td class="py-3 px-4 text-ivoire-text/80">Traçabilité</td>
                                <td class="text-center py-3 px-4 text-ivoire-text">❌</td>
                                <td class="text-center py-3 px-4 text-beige-peau">✅</td>
                                <td class="text-center py-3 px-4 text-beige-peau">✅</td>
                            </tr>
                            <tr class="border-b border-titane/10">
                                <td class="py-3 px-4 text-ivoire-text/80">Multi-agendas</td>
                                <td class="text-center py-3 px-4 text-ivoire-text">❌</td>
                                <td class="text-center py-3 px-4 text-ivoire-text">❌</td>
                                <td class="text-center py-3 px-4 text-beige-peau">✅</td>
                            </tr>
                            <tr>
                                <td class="py-3 px-4 text-ivoire-text/80">Support</td>
                                <td class="text-center py-3 px-4 text-ivoire-text">Email</td>
                                <td class="text-center py-3 px-4 text-beige-peau">Prioritaire</td>
                                <td class="text-center py-3 px-4 text-beige-peau">Dédié</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/professionnels/partials/pricing.blade.php ENDPATH**/ ?>
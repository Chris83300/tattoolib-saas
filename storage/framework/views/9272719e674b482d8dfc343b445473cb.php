<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        <?php echo $__env->make('partials.trial-banner', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <!-- Header avec salutation -->
        <div class="bg-gris-fonde rounded-xl border border-cuivre/10 shadow-md shadow-cuivre/20 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                        Bonjour <span class="font-bold text-4xl text-beige-peau"><?php echo e(auth()->user()->pseudo); ?></span>
                    </h1>
                    <p class="text-ivoire-text/70">
                        Voici votre activité du jour
                    </p>
                </div>

                <!-- Actions rapides -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg','href' => ''.e(route('marketplace.tattooer.show', $tattooer->slug)).'','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','href' => ''.e(route('marketplace.tattooer.show', $tattooer->slug)).'','target' => '_blank']); ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Voir mon profil public
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'lg','href' => ''.e(route('marketplace.index')).'','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'lg','href' => ''.e(route('marketplace.index')).'','target' => '_blank']); ?>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                        Voir la marketplace
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

        <!-- Stats KPI (Grid 4 colonnes) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Demandes en attente -->
            <div class="bg-ambre-warning/5 rounded-xl p-6 hover:ring-2 border border-ambre-warning/20 hover:border-ambre-warning shadow-md shadow-ambre-warning/20 hover:ring-ambre-warning transition-all cursor-pointer"
                onclick="window.location.href='<?php echo e(route($tattooer->routePrefix() . '.requests')); ?>'">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-ambre-warning/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-ambre-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats['active_projects'] > 0): ?>
                        <span class="bg-rouge-alerte text-noir-profond px-2 py-1 rounded-full text-xs font-bold">
                            <?php echo e($stats['active_projects']); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <h3 class="text-3xl font-bold text-ivoire-text mb-1">
                    <?php echo e($stats['active_projects']); ?>

                </h3>
                <p class="text-ivoire-text/60 text-sm">Demandes en attente</p>
            </div>

            <!-- RDV à venir -->
            <div class="rounded-xl bg-beige-peau/5 border border-beige-peau/20 hover:border-beige-peau shadow-md shadow-beige-peau/20 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-ivoire-text mb-1">
                    <?php echo e(count($upcomingAppointments)); ?>

                </h3>
                <p class="text-ivoire-text/60 text-sm">Rendez-vous à venir</p>
            </div>

            <!-- Clients totaux -->
            <div class="rounded-xl bg-vert-succes/5 border border-vert-succes/20 hover:border-vert-succes shadow-md shadow-vert-succes/20 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-vert-succes/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-ivoire-text mb-1">
                    <?php echo e($stats['total_clients']); ?>

                </h3>
                <p class="text-ivoire-text/60 text-sm">Clients totaux</p>
            </div>

            <!-- Revenus du mois -->
            <div class="rounded-xl bg-electric-blue/10 border border-electric-blue/50 hover:border-electric-blue shadow-md shadow-electric-blue/40 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-electric-blue/20 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-vert-succes/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-3xl font-bold text-ivoire-text mb-1">
                    <?php echo e(number_format($stats['total_earnings'], 0)); ?>€
                </h3>
                <p class="text-ivoire-text/60 text-sm">Revenus ce mois</p>
            </div>
        </div>

        <!-- Grid 2 colonnes (Prochains RDV + Activité récente) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Prochains rendez-vous -->
            <div class="bg-gris-fonde rounded-xl border border-titane/20 hover:border-titane/50 shadow-md shadow-titane/20 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-ivoire-text">
                        📅 Prochains rendez-vous
                    </h2>
                    <a href="<?php echo e(route($tattooer->routePrefix() . '.calendar')); ?>"
                        class="text-beige-peau text-sm font-semibold hover:underline">
                        Voir tout →
                    </a>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $upcomingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $isConfirmed = $appointment->status === \App\Enums\AppointmentStatus::CONFIRMED;
                        $isPast = $appointment->end_datetime && $appointment->end_datetime->isPast();
                    ?>

                    <div class="border-b border-titane/20 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="font-semibold text-ivoire-text mb-1">
                                    <?php echo e($appointment->client->first_name); ?> <?php echo e($appointment->client->last_name); ?>

                                </h3>
                                <p class="text-ivoire-text/70 text-sm mb-2">
                                    <?php echo e($appointment->tattoo_description ?? 'Nouveau tattoo'); ?>

                                </p>
                                <div class="flex items-center gap-4 text-xs text-ivoire-text/60">
                                    <span>📅 <?php echo e($appointment->appointment_datetime->format('d/m/Y à H:i')); ?></span>
                                    <span>⏱️ <?php echo e($appointment->estimated_duration ?? '60'); ?>min</span>
                                </div>

                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isConfirmed && $isPast): ?>
                                    <div class="mt-3 flex flex-wrap gap-2">
                                        <form action="<?php echo e(route($tattooer->routePrefix() . '.appointments.complete', $appointment)); ?>"
                                            method="POST"
                                            onsubmit="return confirm('Confirmer que le RDV s\'est bien passé ?')">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-vert-succes text-white rounded-lg text-xs font-medium hover:bg-vert-succes/90 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                Terminé
                                            </button>
                                        </form>
                                        <button x-data
                                            @click="$dispatch('open-modal', 'no-show-modal-<?php echo e($appointment->id); ?>')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-rouge-alerte text-white rounded-lg text-xs font-medium hover:bg-rouge-alerte/90 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            No-show
                                        </button>
                                    </div>

                                    
                                    <div x-data="{ open: false }"
                                        @open-modal.window="if ($event.detail === 'no-show-modal-<?php echo e($appointment->id); ?>') open = true"
                                        x-show="open" x-cloak x-transition
                                        class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                        <div class="absolute inset-0 bg-noir-profond/60" @click="open = false"></div>
                                        <div class="relative bg-white rounded-2xl p-6 max-w-md w-full shadow-xl">
                                            <h3 class="text-lg font-bold text-noir-profond mb-1">Signaler un no-show</h3>
                                            <p class="text-sm text-noir-profond/60 mb-4">Le client ne s'est pas présenté au
                                                rendez-vous ?</p>
                                            <form action="<?php echo e(route($tattooer->routePrefix() . '.appointments.no-show', $appointment)); ?>"
                                                method="POST">
                                                <?php echo csrf_field(); ?>
                                                <textarea name="no_show_reason" rows="3" placeholder="Décrivez la situation (optionnel)..."
                                                    class="w-full border border-noir-profond/20 rounded-xl p-3 mb-4 text-sm focus:ring-2 focus:ring-orange-terre-cuite/50 focus:border-orange-terre-cuite"></textarea>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" @click="open = false"
                                                        class="px-4 py-2 border border-noir-profond/20 rounded-lg text-sm text-noir-profond/70 hover:bg-noir-profond/5">
                                                        Annuler
                                                    </button>
                                                    <button type="submit"
                                                        class="px-4 py-2 bg-rouge-alerte text-white rounded-lg text-sm font-medium hover:bg-rouge-alerte/90">
                                                        Confirmer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <span class="bg-vert-succes/20 text-vert-succes px-2 py-1 rounded text-xs font-semibold">
                                Confirmé
                            </span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 mx-auto mb-4 text-ivoire-text/30" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="text-ivoire-text/60">Aucun rendez-vous à venir</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Activité récente -->
            <div class="bg-gris-fonde rounded-xl border border-titane/20 hover:border-titane/50 shadow-md shadow-titane/20 p-6">
                <h2 class="text-xl font-bold text-ivoire-text mb-6">
                    📊 Activité cette semaine
                </h2>

                <div class="space-y-4">
                    <!-- Nouvelles demandes -->
                    <div class="flex items-center justify-between p-4 bg-noir-profond rounded-xl border border-ambre-warning/40">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-ambre-warning/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-ambre-warning" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-ivoire-text font-semibold">Nouvelles demandes</p>
                                <p class="text-ivoire-text/60 text-sm">7 derniers jours</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-ambre-warning">
                            <?php echo e($recentActivity['new_requests']); ?>

                        </span>
                    </div>

                    <!-- RDV réalisés -->
                    <div class="flex items-center justify-between p-4 bg-noir-profond rounded-xl border border-vert-succes/40">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-vert-succes/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-vert-succes" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-ivoire-text font-semibold">RDV réalisés</p>
                                <p class="text-ivoire-text/60 text-sm">7 derniers jours</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-vert-succes">
                            <?php echo e($recentActivity['completed_appointments']); ?>

                        </span>
                    </div>

                    <!-- Messages non lus -->
                    <div class="flex items-center justify-between p-4 bg-noir-profond rounded-xl border border-beige-peau/40">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-beige-peau/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-beige-peau" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-ivoire-text font-semibold">Messages non lus</p>
                                <p class="text-ivoire-text/60 text-sm">À répondre</p>
                            </div>
                        </div>
                        <span class="text-2xl font-bold text-beige-peau">
                            <?php echo e($stats['unread_messages'] ?? 0); ?>

                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upgrade PRO (si FREE) -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer->isFree()): ?>
            <div class="bg-gradient-to-r from-beige-peau/20 to-beige-peau/5 border-2 border-beige-peau/30 hover:border-beige-peau/50 shadow-md shadow-beige-peau/20 rounded-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-2xl">⭐</span>
                            <h3 class="text-xl font-bold text-ivoire-text">Passez au plan PRO</h3>
                        </div>
                        <p class="text-ivoire-text/70 mb-4">
                            0% de commission + fonctionnalités avancées + stockage illimité
                        </p>
                        <ul class="space-y-2 text-ivoire-text/80 text-sm">
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-vert-succes" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                0% de commission sur vos réservations
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-vert-succes" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Stockage photos illimité (chat + portfolio)
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-vert-succes" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Badge PRO sur votre profil public
                            </li>
                        </ul>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-cuivre mb-2">29,99€</div>
                        <div class="text-ivoire-text/60 text-sm mb-4">/mois</div>
                        <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'lg','href' => ''.e(route($tattooer->routePrefix() . '.subscription.plans')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'lg','href' => ''.e(route($tattooer->routePrefix() . '.subscription.plans')).'']); ?>Passer PRO maintenant <?php echo $__env->renderComponent(); ?>
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
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\tattooer\dashboard.blade.php ENDPATH**/ ?>
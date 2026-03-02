<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Studio'); ?> - Ink&Pik</title>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<?php
    $studio = auth()->user()->studio ?? null;
    $studioName = $studio?->name ?? 'Mon Studio';
?>

<body class="bg-noir-profond">

    <div class="flex min-h-screen max-w-full overflow-x-hidden">

        <!-- Sidebar Desktop (cachée sur mobile) -->
        <aside
            class="hidden lg:flex lg:flex-col lg:w-64 bg-gris-fonde border-r border-titane/20 fixed h-full top-0 left-0 z-10">

            <!-- Logo -->
            <div class="p-6 border-b border-titane/20">
                <a href="<?php echo e(route('studio.dashboard')); ?>" class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center">
                        <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Ink&Pik" class="w-12 h-12">
                    </div>
                    <span class="text-beige-peau font-bold font-Satoshi text-lg">
                        <span class="text-titane">Ink</span> & <span class="text-beige-peau">Pik</span>
                    </span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="<?php echo e(route('studio.dashboard')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.dashboard') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="font-semibold">Tableau de bord</span>
                </a>

                <a href="<?php echo e(route('studio.artists')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.artists*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span class="font-semibold">Artistes</span>
                </a>

                <a href="<?php echo e(route('studio.planning')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.planning') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="font-semibold">Planning</span>
                </a>

                <a href="<?php echo e(route('studio.requests')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.requests') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="font-semibold">Demandes</span>
                </a>

                <a href="<?php echo e(route('studio.messages')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.messages*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="font-semibold">Messages</span>
                </a>

                <div class="pt-4 mt-4 border-t border-titane/20 space-y-1">
                    <a href="<?php echo e(route('studio.settings')); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.settings') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-semibold">Paramètres</span>
                    </a>

                    <a href="<?php echo e(route('studio.billing')); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.billing') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        <span class="font-semibold">Facturation</span>
                    </a>

                    <a href="<?php echo e(route('studio.stats')); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.stats') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span class="font-semibold">Statistiques</span>
                    </a>

                    <a href="<?php echo e($studio ? route('studio.public', $studio->slug) : '#'); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('studio.public') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                            </path>
                        </svg>
                        <span class="font-semibold">Profil public</span>
                    </a>
                </div>
            </nav>

            <!-- User info -->
            <div class="p-4 border-t border-titane/20">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-noir-profond">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio?->getFirstMediaUrl('logo')): ?>
                        <img src="<?php echo e($studio->getFirstMediaUrl('logo')); ?>" alt="Logo"
                            class="w-10 h-10 rounded-full object-cover">
                    <?php else: ?>
                        <div
                            class="w-10 h-10 rounded-full bg-beige-peau/20 flex items-center justify-center text-beige-peau font-bold text-sm">
                            <?php echo e(mb_substr($studioName, 0, 1)); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex-1 min-w-0">
                        <p class="text-ivoire-text font-semibold truncate text-sm"><?php echo e($studioName); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio && $studio->onTrial()): ?>
                            <span
                                class="text-xs bg-beige-peau/20 text-beige-peau rounded-full px-2 py-0.5 font-semibold">
                                Essai • <?php echo e($studio->trialDaysLeft()); ?>j
                            </span>
                        <?php elseif($studio && $studio->hasActiveSubscription()): ?>
                            <span
                                class="text-xs bg-green-500/20 text-green-400 rounded-full px-2 py-0.5 font-semibold">
                                Pro
                            </span>
                        <?php elseif($studio && $studio->trialExpired()): ?>
                            <span
                                class="text-xs bg-rouge-alerte/20 text-rouge-alerte rounded-full px-2 py-0.5 font-semibold">
                                Expiré
                            </span>
                        <?php else: ?>
                            <p class="text-ivoire-text/60 text-xs">Studio</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <form action="<?php echo e(route('logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="text-ivoire-text/60 hover:text-rouge-alerte transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                </path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 lg:ml-64 overflow-x-hidden overflow-y-auto min-w-0 w-full h-screen">

            <!-- Header Mobile (visible uniquement sur mobile) -->
            <header class="lg:hidden bg-gris-fonde border-b border-titane/20 p-4 sticky top-0 z-40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg flex items-center justify-center">
                            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Ink&Pik" class="w-10 h-10">
                        </div>
                        <span
                            class="text-beige-peau font-bold text-sm truncate max-w-[140px]"><?php echo e($studioName); ?></span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Avatar -->
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio?->getFirstMediaUrl('logo')): ?>
                            <img src="<?php echo e($studio->getFirstMediaUrl('logo')); ?>" alt="Logo"
                                class="w-8 h-8 rounded-full object-cover">
                        <?php else: ?>
                            <div
                                class="w-8 h-8 rounded-full bg-beige-peau/20 flex items-center justify-center text-beige-peau font-bold text-xs">
                                <?php echo e(mb_substr($studioName, 0, 1)); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Bouton déconnexion mobile -->
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="shrink-0">
                            <?php echo csrf_field(); ?>
                            <button type="submit"
                                class="text-ivoire-text/60 hover:text-rouge-alerte transition-colors p-1 rounded-lg"
                                title="Se déconnecter">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3 3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio && $studio->onTrial()): ?>
                <div class="bg-beige-peau/10 border-b border-beige-peau/20 px-4 py-2">
                    <div class="flex items-center justify-between">
                        <p class="text-xs text-beige-peau">
                            ⏳ <strong>Essai gratuit</strong> — <?php echo e($studio->trialDaysLeft()); ?>

                            jour<?php echo e($studio->trialDaysLeft() > 1 ? 's' : ''); ?>

                            restant<?php echo e($studio->trialDaysLeft() > 1 ? 's' : ''); ?>

                        </p>
                        <a href="<?php echo e(route('studio.billing')); ?>"
                            class="text-xs font-semibold text-beige-peau hover:text-beige-peau/80">
                            Activer l'abonnement →
                        </a>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($studio && $studio->trialExpired()): ?>
                <div class="bg-rouge-alerte/10 border-b border-rouge-alerte/30 px-4 py-3">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-rouge-alerte">⚠️ Votre essai est terminé</p>
                            <p class="text-xs text-rouge-alerte/80 mt-0.5">Votre studio est en lecture seule. Activez
                                votre abonnement pour continuer.</p>
                        </div>
                        <a href="<?php echo e(route('studio.billing')); ?>"
                            class="shrink-0 px-4 py-2 bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold hover:bg-beige-peau/90 transition-colors">
                            Activer — 79,99€/mois
                        </a>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Content -->
            <div class="p-4 lg:p-8 pb-24 lg:pb-8 max-w-full overflow-y-auto">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div
                        class="mb-4 p-3 bg-vert-succes/20 border border-vert-succes/40 rounded-lg text-sm text-vert-succes">
                        <?php echo e(session('success')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                    <div
                        class="mb-4 p-3 bg-rouge-alerte/20 border border-rouge-alerte/40 rounded-lg text-sm text-rouge-alerte">
                        <?php echo e(session('error')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php echo e($slot ?? ''); ?>

                <?php echo $__env->yieldContent('content'); ?>
                <?php echo $__env->make('partials.footer-legal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        </main>

        <!-- Bottom Navigation Mobile (visible uniquement sur mobile) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-gris-fonde border-t border-titane/20 z-50">
            <div class="grid grid-cols-5 gap-1 p-2">
                <a href="<?php echo e(route('studio.dashboard')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('studio.dashboard') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Accueil</span>
                </a>

                <a href="<?php echo e(route('studio.artists')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('studio.artists*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Artistes</span>
                </a>

                <a href="<?php echo e(route('studio.planning')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('studio.planning') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Planning</span>
                </a>

                <a href="<?php echo e(route('studio.messages')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('studio.messages*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Messages</span>
                </a>

                <button type="button" onclick="openStudioMoreMenu()"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('studio.settings') || request()->routeIs('studio.billing') || request()->routeIs('studio.stats') || request()->routeIs('studio.profile*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-[10px] font-semibold">Plus</span>
                </button>
            </div>
        </nav>

        <!-- Menu mobile overlay "Plus" -->
        <div id="studio-more-menu" class="hidden lg:hidden fixed inset-0 bg-black/80 z-[60]">
            <div class="absolute inset-0" onclick="closeStudioMoreMenu()"></div>
            <div class="absolute bottom-0 left-0 right-0 bg-gris-fonde border-t border-titane/20 rounded-t-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-ivoire-text font-bold">Menu</div>
                    <button type="button" class="text-ivoire-text/70 text-xl"
                        onclick="closeStudioMoreMenu()">×</button>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <a href="<?php echo e(route('studio.settings')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20 hover:border-beige-peau/40 transition-colors">
                        <div class="font-semibold text-sm">⚙️ Paramètres</div>
                    </a>
                    <a href="<?php echo e(route('studio.billing')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20 hover:border-beige-peau/40 transition-colors">
                        <div class="font-semibold text-sm">💳 Facturation</div>
                    </a>
                    <a href="<?php echo e(route('studio.stats')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20 hover:border-beige-peau/40 transition-colors">
                        <div class="font-semibold text-sm">📈 Statistiques</div>
                    </a>
                    <a href="<?php echo e(route('studio.profile')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20 hover:border-beige-peau/40 transition-colors">
                        <div class="font-semibold text-sm">🌐 Profil public</div>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <script>
        function openStudioMoreMenu() {
            const el = document.getElementById('studio-more-menu');
            if (el) el.classList.remove('hidden');
        }

        function closeStudioMoreMenu() {
            const el = document.getElementById('studio-more-menu');
            if (el) el.classList.add('hidden');
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>

</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/layouts/studio.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - Ink&Pik</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/fr.global.min.js'></script>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body class="bg-noir-profond">

    <div class="flex min-h-screen">

        <!-- Sidebar Desktop (cachée sur mobile) -->
        <aside
            class="hidden lg:flex lg:flex-col lg:w-64 bg-gris-fonde border-r border-titane/20 fixed h-full top-0 left-0 z-10">

            <!-- Logo -->
            <div class="p-6 border-b border-titane/20">
                <a href="<?php echo e(route('tattooer.dashboard')); ?>" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-beige-peau rounded-lg flex items-center justify-center">
                        <span class="text-noir-profond font-bold text-xl">I&P</span>
                    </div>
                    <span class="text-ivoire-text font-bold text-lg">Ink&Pik</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="<?php echo e(route('tattooer.dashboard')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.dashboard') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="font-semibold">Vue d'ensemble</span>
                </a>

                <a href="<?php echo e(route('tattooer.profile')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.profile') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    <span class="font-semibold">Mon Profil</span>
                </a>

                <a href="<?php echo e(route('tattooer.requests')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.requests*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="font-semibold">Demandes</span>
                    <?php
                        $pendingCount = \App\Models\BookingRequest::where('bookable_id', auth()->user()->tattooer->id)
                            ->where('bookable_type', 'App\Models\Tattooer')
                            ->where('status', 'pending')
                            ->count();
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingCount > 0): ?>
                        <span
                            class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                            <?php echo e($pendingCount); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <a href="<?php echo e(route('tattooer.calendar')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.calendar') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="font-semibold">Calendrier</span>
                </a>

                <a href="<?php echo e(route('tattooer.messages')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.messages*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="font-semibold">Messages</span>
                    <?php
                        $tattooer = auth()->user()->tattooer;

                        // Récupérer toutes les conversations du tattooer
                        $conversationIds = \App\Models\Conversation::whereHas('bookingRequest', function ($q) use (
                            $tattooer,
                        ) {
                            $q->where('bookable_type', 'App\\Models\\Tattooer')->where('bookable_id', $tattooer->id);
                        })->pluck('id');

                        // Compter messages non-lus
                        $unreadCount = 0;

                        foreach ($conversationIds as $conversationId) {
                            $conversation = \App\Models\Conversation::find($conversationId);

                            if ($conversation) {
                                $pivot = $conversation
                                    ->participants()
                                    ->where('user_id', auth()->id())
                                    ->first()?->pivot;

                                $lastReadAt = $pivot?->last_read_at ?? now()->subYears(10);

                                $unreadCount += $conversation
                                    ->messages()
                                    ->where('sender_id', '!=', auth()->id())
                                    ->where('created_at', '>', $lastReadAt)
                                    ->count();
                            }
                        }
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                        <span
                            class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                            <?php echo e($unreadCount); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <a href="<?php echo e(route('tattooer.clients')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.clients*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span class="font-semibold">Clients</span>
                </a>

                <a href="<?php echo e(route('tattooer.portfolio')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.portfolio') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="font-semibold">Portfolio</span>
                </a>

                <a href="<?php echo e(route('tattooer.payments')); ?>"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.payments') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    <span class="font-semibold">Paiements</span>
                </a>

                <div class="pt-4 mt-4 border-t border-titane/20">
                    <a href="<?php echo e(route('tattooer.settings')); ?>"
                        class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('tattooer.settings') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond'); ?> transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="font-semibold">Paramètres</span>
                    </a>
                </div>
            </nav>

            <!-- User info -->
            <div class="p-4 border-t border-titane/20">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-noir-profond">
                    <img src="<?php echo e(auth()->user()->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                        alt="Avatar" class="w-10 h-10 rounded-full">
                    <div class="flex-1 min-w-0">
                        <p class="text-ivoire-text font-semibold truncate"><?php echo e(auth()->user()->name); ?></p>
                        <p class="text-ivoire-text/60 text-xs"><?php echo e(auth()->user()->tattooer->city); ?></p>
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
        <main class="flex-1 lg:ml-64">

            <!-- Header Mobile (visible uniquement sur mobile) -->
            <header class="lg:hidden bg-gris-fonde border-b border-titane/20 p-4 sticky top-0 z-40">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-beige-peau rounded-lg flex items-center justify-center">
                            <span class="text-noir-profond font-bold">I&P</span>
                        </div>
                        <span class="text-ivoire-text font-bold">Ink&Pik</span>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Notifs -->
                        <button class="relative text-ivoire-text">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                </path>
                            </svg>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-rouge-alerte rounded-full"></span>
                        </button>

                        <!-- Avatar -->
                        <img src="<?php echo e(auth()->user()->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                            alt="Avatar" class="w-8 h-8 rounded-full">
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="p-4 lg:p-8 pb-24 lg:pb-8">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>

        <!-- Bottom Navigation Mobile (visible uniquement sur mobile) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-gris-fonde border-t border-titane/20 z-50">
            <div class="grid grid-cols-5 gap-1 p-2">
                <a href="<?php echo e(route('tattooer.dashboard')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('tattooer.dashboard') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Accueil</span>
                </a>

                <a href="<?php echo e(route('tattooer.requests')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg relative <?php echo e(request()->routeIs('tattooer.requests*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Demandes</span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingCount > 0): ?>
                        <span
                            class="absolute top-0 right-0 w-4 h-4 bg-rouge-alerte text-noir-profond rounded-full text-[8px] font-bold flex items-center justify-center">
                            <?php echo e($pendingCount); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <a href="<?php echo e(route('tattooer.calendar')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('tattooer.calendar') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Agenda</span>
                </a>

                <a href="<?php echo e(route('tattooer.messages')); ?>"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg relative <?php echo e(request()->routeIs('tattooer.messages*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">Messages</span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                        <span
                            class="absolute top-0 right-0 w-4 h-4 bg-rouge-alerte text-noir-profond rounded-full text-[8px] font-bold flex items-center justify-center">
                            <?php echo e($unreadCount); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <button type="button" onclick="openMobileMoreMenu()"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg <?php echo e(request()->routeIs('tattooer.settings') || request()->routeIs('tattooer.clients*') || request()->routeIs('tattooer.portfolio') || request()->routeIs('tattooer.payments') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text'); ?>">
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

        <div id="mobile-more-menu" class="hidden lg:hidden fixed inset-0 bg-black/80 z-[60]">
            <div class="absolute inset-0" onclick="closeMobileMoreMenu()"></div>
            <div class="absolute bottom-0 left-0 right-0 bg-gris-fonde border-t border-titane/20 rounded-t-2xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="text-ivoire-text font-bold">Menu</div>
                    <button type="button" class="text-ivoire-text/70" onclick="closeMobileMoreMenu()">×</button>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <a href="<?php echo e(route('tattooer.clients')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20">
                        <div class="font-semibold">Clients</div>
                    </a>
                    <a href="<?php echo e(route('tattooer.portfolio')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20">
                        <div class="font-semibold">Portfolio</div>
                    </a>
                    <a href="<?php echo e(route('tattooer.payments')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20">
                        <div class="font-semibold">Paiements</div>
                    </a>
                    <a href="<?php echo e(route('tattooer.settings')); ?>"
                        class="p-4 rounded-xl bg-noir-profond text-ivoire-text border border-titane/20">
                        <div class="font-semibold">Paramètres</div>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <script>
        function openMobileMoreMenu() {
            const el = document.getElementById('mobile-more-menu');
            if (el) el.classList.remove('hidden');
        }

        function closeMobileMoreMenu() {
            const el = document.getElementById('mobile-more-menu');
            if (el) el.classList.add('hidden');
        }
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/layouts/tattooer.blade.php ENDPATH**/ ?>
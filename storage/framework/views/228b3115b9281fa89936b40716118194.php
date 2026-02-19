<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond">
        <!-- Navigation mobile bottom bar -->
        <nav class="fixed bottom-0 left-0 right-0 bg-gris-fonde border-t border-titane/30 md:hidden z-40">
            <div class="flex justify-around items-center h-16">
                <a href="<?php echo e(route('client.dashboard')); ?>"
                    class="relative flex flex-col items-center justify-center flex-1 <?php echo e(request()->routeIs('client.dashboard') ? 'text-beige-peau' : 'text-ivoire-text/70'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-xs mt-1">Tableau</span>
                </a>

                <a href="<?php echo e(route('client.messages')); ?>"
                    class="relative flex flex-col items-center justify-center flex-1 <?php echo e(request()->routeIs('client.messages') ? 'text-beige-peau' : 'text-ivoire-text/70'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="text-xs mt-1">Messages</span>
                    <?php
                        // Debug: Vérifier si le client existe
                        if (!auth()->check()) {
                            $unreadCount = 0;
                        } elseif (!auth()->user()->client) {
                            $unreadCount = 0;
                        } else {
                            $client = auth()->user()->client;

                            // Récupérer toutes les conversations du client
                            $conversationIds = \App\Models\Conversation::whereHas('bookingRequest', function ($q) use (
                                $client,
                            ) {
                                $q->where('client_id', $client->id);
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
                        }
                    ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCount > 0): ?>
                        <span
                            class="absolute top-0 right-1/4 bg-rouge-alerte text-noir-profond w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold">
                            <?php echo e($unreadCount > 99 ? '99+' : $unreadCount); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </a>

                <a href="<?php echo e(route('client.bookings')); ?>"
                    class="relative flex flex-col items-center justify-center flex-1 <?php echo e(request()->routeIs('client.bookings') ? 'text-beige-peau' : 'text-ivoire-text/70'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-xs mt-1">RDV</span>
                </a>

                <a href="<?php echo e(route('client.profile')); ?>"
                    class="relative flex flex-col items-center justify-center flex-1 <?php echo e(request()->routeIs('client.profile') ? 'text-beige-peau' : 'text-ivoire-text/70'); ?>">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-xs mt-1">Profil</span>
                </a>
            </div>
        </nav>

        <!-- Main content with sidebar -->
        <div class="flex h-screen pt-16 md:pt-0">
            <!-- Sidebar desktop -->
            <aside class="hidden md:flex md:w-64 bg-gris-fonde border-r border-titane/30 flex-shrink-0">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="p-6 border-b border-titane/20">
                        <a href="/"
                            class="flex items-center gap-2 text-beige-peau font-bold text-xl hover:text-beige-peau/90 transition-colors">
                            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Ink&Pik" class="w-12 h-12">
                            Ink&Pik
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 p-4 space-y-2">
                        <a href="<?php echo e(route('client.dashboard')); ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('client.dashboard') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/70 hover:bg-gris-fonde'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            Tableau de bord
                        </a>

                        <a href="<?php echo e(route('client.messages')); ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('client.messages') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/70 hover:bg-gris-fonde'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                            Messages
                            <?php
                                $client = auth()->user()->client;

                                // Récupérer toutes les conversations du client
                                $conversationIds = \App\Models\Conversation::whereHas('bookingRequest', function (
                                    $q,
                                ) use ($client) {
                                    $q->where('client_id', $client->id);
                                })->pluck('id');

                                // Compter messages non-lus
                                $unreadCountSidebar = 0;

                                foreach ($conversationIds as $conversationId) {
                                    $conversation = \App\Models\Conversation::find($conversationId);

                                    if ($conversation) {
                                        $pivot = $conversation
                                            ->participants()
                                            ->where('user_id', auth()->id())
                                            ->first()?->pivot;

                                        $lastReadAt = $pivot?->last_read_at ?? now()->subYears(10);

                                        $unreadCountSidebar += $conversation
                                            ->messages()
                                            ->where('sender_id', '!=', auth()->id())
                                            ->where('created_at', '>', $lastReadAt)
                                            ->count();
                                    }
                                }
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($unreadCountSidebar > 0): ?>
                                <span
                                    class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                                    <?php echo e($unreadCountSidebar > 99 ? '99+' : $unreadCountSidebar); ?>

                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </a>

                        <a href="<?php echo e(route('client.bookings')); ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('client.bookings') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/70 hover:bg-gris-fonde'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Mes RDV
                        </a>

                        <a href="<?php echo e(route('client.booking-requests')); ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('client.booking-requests') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/70 hover:bg-gris-fonde'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            Demandes
                        </a>

                        <a href="<?php echo e(route('client.profile')); ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('client.profile') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/70 hover:bg-gris-fonde'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Profil
                        </a>

                        <a href="<?php echo e(route('client.settings')); ?>"
                            class="flex items-center gap-3 px-4 py-3 rounded-lg <?php echo e(request()->routeIs('client.settings') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/70 hover:bg-gris-fonde'); ?>">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Paramètres
                        </a>
                    </nav>

                    <!-- User menu bottom -->
                    <div class="p-4 border-t border-titane/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-beige-peau/20 rounded-full flex items-center justify-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->client && auth()->user()->client->getFirstMediaUrl('avatar')): ?>
                                    <img src="<?php echo e(auth()->user()->client->getFirstMediaUrl('avatar')); ?>"
                                        alt="<?php echo e(auth()->user()->name); ?>" class="w-10 h-10 rounded-full object-cover">
                                <?php else: ?>
                                    <svg class="w-5 h-5 text-beige-peau" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-ivoire-text truncate">
                                    <?php echo e(auth()->user()->client->pseudo ?? (auth()->user()->pseudo ?? auth()->user()->first_name . ' ' . auth()->user()->last_name)); ?>

                                </p>
                                <p class="text-xs text-ivoire-text/50">
                                    Client
                                </p>
                            </div>
                            <form method="POST" action="<?php echo e(route('logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                    class="text-ivoire-text/50 hover:text-rouge-alerte transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main content area -->
            <main class="flex-1 overflow-y-auto pb-16 md:pb-0">
                <div class="container-custom py-6">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </main>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/layouts/client.blade.php ENDPATH**/ ?>
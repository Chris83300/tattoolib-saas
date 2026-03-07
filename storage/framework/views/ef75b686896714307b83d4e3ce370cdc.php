<header class="sticky top-0 z-50 bg-noir-profond/95 backdrop-blur-sm border-b border-titane/20">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="text-beige-peau font-display text-xl font-bold">
            Ink&Pik
        </a>

        <!-- Navigation principale (sans duplication de profil) -->
        <nav class="hidden md:flex items-center gap-6">
            <a href="/marketplace" class="text-ivoire-text hover:text-beige-peau transition-colors">
                Explorer
            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <!-- Navigation selon rôle (sans lien profil) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'client'): ?>
                    <a href="<?php echo e(route('client.profile')); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Mon profil
                    </a>
                    <a href="<?php echo e(route('client.booking-requests')); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Mes demandes
                    </a>
                    <a href="<?php echo e(route('client.bookings')); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Mes RDV
                    </a>
                    <a href="<?php echo e(route('client.messages')); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors relative">
                        Messages
                        <?php if(auth()->user()->unread_messages_count > 0): ?>
                            <span
                                class="absolute -top-1 -right-1 bg-rouge-alerte text-noir-profond text-xs font-bold px-1.5 rounded-full">
                                <?php echo e(auth()->user()->unread_messages_count); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(auth()->user()->role, ['tattooer', 'pierceur', 'studio_artist'])): ?>
                    <a href="<?php echo e(auth()->user()->getProfileRoute()); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Mon espace pro
                    </a>
                    <a href="<?php echo e(route(auth()->user()->isPiercer() ? 'pierceur.demandes' : 'tattooer.demandes')); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Demandes
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(auth()->user()->role === 'studio'): ?>
                    <a href="/admin/studio" target="_blank"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Dashboard Studio
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Déconnexion uniquement -->
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Déconnexion
                    </button>
                </form>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </nav>

        <!-- Mobile menu burger -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-ivoire-text">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>

    </div>
</header>

<!-- Mobile menu -->
<div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-2" @click.away="mobileMenuOpen = false"
    class="fixed inset-0 z-40 md:hidden">
    <div class="fixed inset-0 bg-black/50" @click="mobileMenuOpen = false"></div>
    <div class="fixed right-0 top-0 h-full w-64 bg-noir-profond shadow-xl overflow-y-auto">
        <div class="p-4">
            <div class="flex items-center justify-between mb-6">
                <span class="text-beige-peau font-display text-xl font-bold">Ink&Pik</span>
                <button @click="mobileMenuOpen = false" class="text-ivoire-text">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <nav class="space-y-2">
                <a href="/marketplace" class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                    Explorer
                </a>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'client'): ?>
                        <a href="<?php echo e(route('client.profile')); ?>"
                            class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                            Mon profil
                        </a>
                        <a href="<?php echo e(route('client.booking-requests')); ?>"
                            class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                            Mes demandes
                        </a>
                        <a href="<?php echo e(route('client.bookings')); ?>"
                            class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                            Mes RDV
                        </a>
                        <a href="<?php echo e(route('client.messages')); ?>"
                            class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                            Messages
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(auth()->user()->role, ['tattooer', 'pierceur', 'studio_artist'])): ?>
                        <a href="<?php echo e(auth()->user()->getProfileRoute()); ?>"
                            class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                            Mon espace pro
                        </a>
                        <a href="<?php echo e(route(auth()->user()->isPiercer() ? 'pierceur.demandes' : 'tattooer.demandes')); ?>"
                            class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                            Demandes
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(auth()->user()->role === 'studio'): ?>
                        <a href="/admin/studio" target="_blank"
                            class="block px-4 py-2 text-ivoire-text hover:bg-titane/20 rounded-lg">
                            Dashboard Studio
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="w-full px-4 py-2 text-rouge-alerte hover:bg-rouge-alerte/20 rounded-lg text-left">
                            Déconnexion
                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\components\layouts\navigation-profile.blade.php ENDPATH**/ ?>
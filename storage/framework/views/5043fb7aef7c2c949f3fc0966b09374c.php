<header class="sticky top-0 z-50 bg-noir-profond/95 backdrop-blur-sm border-b border-titane/20">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="text-beige-peau font-Satoshi text-xl font-bold">
            Ink&Pik
        </a>

        <!-- Navigation principale -->
        <nav class="hidden md:flex items-center gap-6">
            <a href="/marketplace" class="text-ivoire-text hover:text-beige-peau transition-colors">
                Explorer
            </a>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                <a href="/professionnels" class="text-ivoire-text hover:text-beige-peau transition-colors">
                    Pour les pros
                </a>
                <a href="<?php echo e(route('login')); ?>" class="text-ivoire-text hover:text-beige-peau transition-colors">
                    Connexion
                </a>
                <a href="<?php echo e(route('register')); ?>"
                    class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                    S'inscrire
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                <!-- Navigation selon rôle -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'client'): ?>
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
                    <a href="<?php echo e(route('tattooer.dashboard')); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Mon espace pro
                    </a>
                    <a href="<?php echo e(route('tattooer.demandes')); ?>"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Demandes
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(auth()->user()->role === 'studio'): ?>
                    <a href="/admin/studio" class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Dashboard Studio
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Lien profil direct + Déconnexion -->
                <a href="<?php echo e(auth()->user()->role === 'client' ? route('client.profile') : route('tattooer.profile')); ?>"
                    class="text-ivoire-text hover:text-beige-peau transition-colors">
                    Mon profil
                </a>

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
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\components\layouts\navigation.blade.php ENDPATH**/ ?>
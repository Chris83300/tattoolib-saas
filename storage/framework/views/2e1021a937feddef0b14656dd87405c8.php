<!DOCTYPE html>
<html lang="fr" class="scroll-smooth" x-data="{ menuOpen: false }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description"
        content="Ink&Pik - Marketplace professionnelle pour tatoueurs, pierceurs et studios. Artistes vérifiés, conformité ARS, paiements sécurisés.">
    <meta name="theme-color" content="#D4B59E">

    <!-- PWA Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Ink&Pik">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- PWA Manifest -->
    <link rel="manifest" href="<?php echo e(asset('manifest.json')); ?>">
    <link rel="apple-touch-icon" href="<?php echo e(asset('images/icon-192x192.png')); ?>">

    <?php echo $__env->yieldPushContent('styles'); ?>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body class="bg-noir-profond text-ivoire-text safe-top safe-bottom">

    <!-- Header Sticky -->
    <header class="fixed top-0 w-full bg-noir-profond/95 backdrop-blur-sm z-50 border-b border-titane/20">
        <div class="container-custom h-16 flex items-center justify-between">

            <!-- Logo -->
            <a href="/"
                class="flex items-center gap-2 text-beige-peau font-Satoshi text-xl font-bold hover:text-beige-peau/90 transition-colors">
                <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Ink&Pik" class="w-12 h-12">
                Ink&Pik
            </a>

            <!-- Menu Burger (Mobile) -->
            <button @click="menuOpen = !menuOpen"
                class="md:hidden text-ivoire-text hover:text-beige-peau transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>

            <!-- Navigation Desktop -->
            <nav class="hidden md:flex items-center gap-6 text-ivoire-text">
                <a href="/marketplace" class="hover:text-beige-peau transition-colors">Trouver un artiste</a>
                <a href="/professionnels" class="hover:text-beige-peau transition-colors">Pour les pros</a>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('login')); ?>"
                        class="text-beige-peau font-semibold hover:text-beige-peau/90">Connexion</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <!-- Navigation selon rôle -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'client'): ?>
                        <a href="<?php echo e(route('client.bookings')); ?>" class="hover:text-beige-peau transition-colors">Mes RDV</a>
                        <a href="<?php echo e(route('client.messages')); ?>" class="hover:text-beige-peau transition-colors relative">
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
                        <a href="/tattooer/dashboard" class="hover:text-beige-peau transition-colors">Mon
                            espace pro</a>
                        <a href="/tattooer/demandes" class="hover:text-beige-peau transition-colors">Demandes</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(auth()->user()->role === 'studio'): ?>
                        <a href="/admin/studio" class="hover:text-beige-peau transition-colors">Dashboard Studio</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Lien profil direct + Déconnexion -->
                    <a href="<?php echo e(auth()->user()->role === 'client' ? '/client/profile' : '/tattooer/profil'); ?>"
                        class="hover:text-beige-peau transition-colors">Mon profil</a>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="hover:text-beige-peau transition-colors">Déconnexion</button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div x-show="menuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="md:hidden border-t border-titane/20 bg-noir-profond absolute top-full left-0 right-0 shadow-lg z-50">
            <div class="container-custom py-4 space-y-3">
                <a href="/marketplace"
                    class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Trouver un artiste</a>
                <a href="/professionnels"
                    class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Pour les pros</a>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->guest()): ?>
                    <a href="<?php echo e(route('login')); ?>"
                        class="block py-2 text-beige-peau font-semibold hover:text-beige-peau/90">Connexion</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <!-- Navigation selon rôle -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->role === 'client'): ?>
                        <a href="<?php echo e(route('client.bookings')); ?>"
                            class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Mes RDV</a>
                        <a href="<?php echo e(route('client.messages')); ?>"
                            class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Messages</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(auth()->user()->role, ['tattooer', 'pierceur', 'studio_artist'])): ?>
                        <a href="/tattooer/dashboard"
                            class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Mon espace pro</a>
                        <a href="/tattooer/demandes"
                            class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Demandes</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if(auth()->user()->role === 'studio'): ?>
                        <a href="/admin/studio"
                            class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Dashboard Studio</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <!-- Lien profil direct + Déconnexion -->
                    <a href="<?php echo e(auth()->user()->role === 'client' ? '/client/profile' : '/tattooer/profil'); ?>"
                        class="block py-2 text-ivoire-text hover:text-beige-peau transition-colors">Mon profil</a>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="block">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="py-2 text-ivoire-text hover:text-beige-peau transition-colors">Déconnexion</button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-16">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- PWA Install Prompt -->
    <?php echo $__env->make('partials.pwa-install-prompt', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Alpine.js Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                isInstalled: false,
                showInstallPrompt: false,
                notificationsOpen: false,
                unreadCount: 0
            });
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tattooer.booking-quick-create');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3637868502-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('tattooer.appointment-detail-modal');

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3637868502-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
</body>

</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/layouts/app.blade.php ENDPATH**/ ?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="description" content="Tableau de bord Ink&Pik - Gérez votre activité d'artiste corporel">
    <meta name="theme-color" content="#D4B59E">

    <!-- PWA Meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Ink&Pik">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- PWA Manifest -->
    <link rel="manifest" href="/build/manifest.webmanifest" crossorigin="use-credentials">
    <link rel="apple-touch-icon" href="{{ asset('images/icon-192x192.png') }}">

    @stack('styles')
</head>
<body class="bg-noir-profond text-ivoire-text safe-top safe-bottom">

    <!-- Mobile Header -->
    <header class="fixed top-0 w-full bg-noir-profond/95 backdrop-blur-sm z-40 border-b border-titane/20 md:hidden">
        <div class="container-custom h-16 flex items-center justify-between">

            <!-- Logo -->
            <a href="/dashboard" class="flex items-center gap-2 text-beige-peau font-Satoshi text-xl font-bold">
                <img src="{{ asset('images/logo.png') }}" alt="Ink&Pik" class="w-12 h-12">
                <span class="text-titane">Ink</span> <span class="text-beige-peau">& Pik</span>
            </a>

            <!-- Notifications -->
            <button @click="notificationsOpen = !notificationsOpen" class="relative text-ivoire-text hover:text-beige-peau transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                <span x-show="unreadCount > 0" class="absolute -top-1 -right-1 w-2 h-2 bg-rouge-alerte rounded-full"></span>
            </button>
        </div>
    </header>

    <!-- Desktop Sidebar -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-gris-fonde border-r border-titane/20 hidden md:block z-30 pt-16">
        <nav class="p-4 space-y-2">
            <!-- Navigation items selon rôle -->
            <a href="/dashboard" class="flex items-center gap-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 hover:text-beige-peau transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Accueil</span>
            </a>

            <a href="/planning" class="flex items-center gap-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 hover:text-beige-peau transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span>Planning</span>
            </a>

            <a href="/messages" class="flex items-center gap-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 hover:text-beige-peau transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <span>Messages</span>
            </a>

            <a href="/profile" class="flex items-center gap-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 hover:text-beige-peau transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span>Profil</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="md:ml-64 pt-16 md:pt-0">
        <div class="container-custom py-6">
            @yield('content')
            @include('partials.footer-legal')
        </div>
    </main>

    <!-- Bottom Navigation (Mobile) -->
    <x-ui.bottom-nav />

    <!-- Notifications Dropdown -->
    <div x-show="notificationsOpen" x-cloak
         @click.away="notificationsOpen = false"
         class="fixed top-16 right-4 w-80 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-50 md:hidden">
        <div class="p-4">
            <h3 class="text-ivoire-text font-semibold mb-3">Notifications</h3>
            <div class="space-y-2">
                <div class="p-3 bg-noir-profond rounded-lg">
                    <p class="text-ivoire-text text-sm">Nouvelle demande de rendez-vous</p>
                    <p class="text-ivoire-text/50 text-xs mt-1">Il y a 5 minutes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Store -->
    <script nonce="{{ csp_nonce() }}">
        document.addEventListener('alpine:init', () => {
            Alpine.store('app', {
                notificationsOpen: false,
                unreadCount: 1,

                toggleNotifications() {
                    this.notificationsOpen = !this.notificationsOpen;
                }
            });
        });
    </script>

    @stack('scripts')

    @include('partials.pwa-install-prompt')
</body>
</html>

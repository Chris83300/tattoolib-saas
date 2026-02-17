<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-noir-profond">
    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden hidden"></div>

    <!-- Sidebar Navigation -->
    <aside id="sidebar"
        class="fixed left-0 top-0 h-full w-64 bg-gris-fonde border-r border-ivoire-text/10 z-50 transform -translate-x-full transition-transform duration-300 lg:translate-x-0 lg:static lg:z-0">
        <!-- Mobile Menu Header -->
        <div class="lg:hidden p-4 border-b border-ivoire-text/10 flex justify-between items-center">
            <span class="text-beige-peau font-bold font-display">Menu</span>
            <button id="close-sidebar" class="text-ivoire-text/70 hover:text-beige-peau transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <!-- Logo -->
        <div class="p-6 border-b border-ivoire-text/10">
            <a href="{{ getDashboardRoute() }}"
                class="flex items-center space-x-3 text-beige-peau hover:text-beige-peau/80 transition-colors">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
                <span class="text-xl font-bold font-display">Ink&Pik</span>
            </a>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-2">
            <a href="{{ getDashboardRoute() }}"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('*.dashboard') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>Dashboard</span>
            </a>

            @if (auth()->user()->role === 'tattooer')
                <!-- Profil -->
                <a href="{{ route('tattooer.profile') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.profile*') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    <span>Profil</span>
                </a>

                <!-- Demandes -->
                <a href="{{ route('tattooer.demandes') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.booking-requests') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span>Demandes</span>
                </a>

                <!-- Messages -->
                <a href="{{ route('tattooer.messages') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.messages') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <span>Messages</span>
                </a>

                <!-- Portfolio -->
                <a href="{{ route('tattooer.portfolio') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.portfolio') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 00-2.828 0L6 16m-2-2l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l-2 2m0 0l-2-2m2 2l-2-2">
                        </path>
                    </svg>
                    <span>Portfolio</span>
                </a>

                <!-- Disponibilités -->
                <a href="{{ route('tattooer.availability') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.availability') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>Disponibilités</span>
                </a>

                <!-- Calendrier -->
                <a href="{{ route('tattooer.calendar') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.calendar') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Calendrier</span>
                </a>

                <!-- Réservations -->
                <a href="{{ route('tattooer.bookings') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.bookings') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356 1.857M12 12h.01M12 8h.01">
                        </path>
                    </svg>
                    <span>Réservations</span>
                </a>

                <!-- Clients (si PRO) -->
                @if (auth()->user()->tattooer && auth()->user()->tattooer->isPro())
                    <a href="{{ route('tattooer.clients') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.clients') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356 1.857M12 12h.01M12 8h.01">
                            </path>
                        </svg>
                        <span>Clients</span>
                    </a>

                    <!-- Statistiques -->
                    <a href="{{ route('tattooer.analytics') }}"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.analytics') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <span>Statistiques</span>
                    </a>
                @endif

                <!-- Paramètres -->
                <a href="{{ route('tattooer.settings') }}"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-ivoire-text hover:bg-beige-peau/10 transition-colors {{ request()->routeIs('tattooer.settings') ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                        </path>
                    </svg>
                    <span>Paramètres</span>
                </a>
            @endif
        </nav>

        <!-- User Menu -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-ivoire-text/10">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-beige-peau/20 rounded-full flex items-center justify-center">
                    <span class="text-beige-peau font-semibold">
                        {{ auth()->user()->name ? substr(auth()->user()->name, 0, 2) : 'U' }}
                    </span>
                </div>
                <div class="flex-1">
                    <p class="text-ivoire-text font-medium">{{ auth()->user()->name }}</p>
                    <p class="text-ivoire-text/60 text-sm">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Mobile Menu Button -->
    <button id="mobile-menu-button"
        class="lg:hidden fixed top-4 left-4 z-30 p-2 bg-gris-fonde rounded-lg border border-ivoire-text/20">
        <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
    </button>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        <!-- Mobile Header -->
        <div class="lg:hidden px-4 py-4 bg-noir-profonde border-b border-ivoire-text/10">
            <div class="flex items-center justify-between">
                <h1 class="text-xl font-bold text-beige-peau font-display">Ink&Pik</h1>
                <div class="text-ivoire-text/70 text-sm">
                    {{ auth()->user()->name }}
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="p-4 lg:p-6">
            {{ $slot }}
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const closeSidebar = document.getElementById('close-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebarFunc() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }

            mobileMenuButton.addEventListener('click', openSidebar);
            closeSidebar.addEventListener('click', closeSidebarFunc);
            overlay.addEventListener('click', closeSidebarFunc);

            // Close sidebar when clicking on a link (mobile only)
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        closeSidebarFunc();
                    }
                });
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebarFunc();
                }
            });
        });
    </script>
</body>

</html>

@php
    function getDashboardRoute()
    {
        $user = auth()->user();
        switch ($user->role) {
            case 'tattooer':
                return route('tattooer.dashboard');
            case 'pierceur':
                return route('pierceur.dashboard');
            case 'studio':
                return route('studio.dashboard');
            case 'studio_artist':
                return route('studio-artist.dashboard');
            case 'client':
                return route('client.profile');
            default:
                return route('home');
        }
    }

    function getProfileEditRoute()
    {
        $user = auth()->user();
        switch ($user->role) {
            case 'tattooer':
                return route('tattooer.profile.edit');
            case 'pierceur':
                return route('pierceur.profile.edit');
            case 'studio':
                return route('studio.profile.edit');
            case 'studio_artist':
                return route('studio-artist.profile.edit');
            case 'client':
                return route('client.profile.edit');
            default:
                return route('home');
        }
    }
@endphp

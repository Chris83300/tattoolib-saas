<header class="sticky top-0 z-50 bg-noir-profond/95 backdrop-blur-sm border-b border-titane/20">
    <div class="container mx-auto px-4 h-16 flex items-center justify-between">

        <!-- Logo -->
        <a href="/" class="text-beige-peau font-Satoshi text-xl font-bold">
            <span class="text-titane">Ink</span> & Pik
        </a>

        <!-- Navigation principale -->
        <nav class="hidden md:flex items-center gap-6">
            <a href="/marketplace" class="text-ivoire-text hover:text-beige-peau transition-colors">
                Explorer
            </a>

            @guest
                <a href="/professionnels" class="text-ivoire-text hover:text-beige-peau transition-colors">
                    Pour les pros
                </a>
                <a href="{{ route('login') }}" class="text-ivoire-text hover:text-beige-peau transition-colors">
                    Connexion
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                    S'inscrire
                </a>
            @endguest

            @auth
                <!-- Navigation selon rôle -->
                @if (auth()->user()->role === 'client')
                    <a href="{{ route('client.bookings') }}"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Mes RDV
                    </a>
                    <a href="{{ route('client.messages') }}"
                        class="text-ivoire-text hover:text-beige-peau transition-colors relative">
                        Messages
                        @if (auth()->user()->unread_messages_count > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-rouge-alerte text-noir-profond text-xs font-bold px-1.5 rounded-full">
                                {{ auth()->user()->unread_messages_count }}
                            </span>
                        @endif
                    </a>
                @endif

                @if (in_array(auth()->user()->role, ['tattooer', 'pierceur', 'studio_artist']))
                    @php $artisanPrefix = auth()->user()->isPiercer() ? 'pierceur' : 'tattooer'; @endphp
                    <a href="{{ route($artisanPrefix . '.dashboard') }}"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Mon espace pro
                    </a>
                    <a href="{{ route($artisanPrefix . '.demandes') }}"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Demandes
                    </a>
                @endif

                @if (auth()->user()->role === 'studio')
                    <a href="{{ route('studio.dashboard') }}"
                        class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Dashboard Studio
                    </a>
                @endif

                <!-- Lien profil direct + Déconnexion -->
                <a href="{{ auth()->user()->getDashboardRoute() }}"
                    class="text-ivoire-text hover:text-beige-peau transition-colors">
                    Mon profil
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-ivoire-text hover:text-beige-peau transition-colors">
                        Déconnexion
                    </button>
                </form>
            @endauth
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

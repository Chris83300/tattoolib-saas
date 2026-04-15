@php
    $user = auth()->user();
    $userRole = 'client';

    if ($user) {
        if ($user->isClient())       $userRole = 'client';
        elseif ($user->isTattooer()) $userRole = 'tattooer';
        elseif ($user->isPiercer())  $userRole = 'piercer';
        elseif ($user->isStudio())   $userRole = 'studio';
    }

    // Badge demandes en attente (artistes uniquement)
    $pendingCount = 0;
    if ($user && $user->isArtisan()) {
        $artisan = $user->artisan();
        if ($artisan) {
            $pendingCount = \App\Models\BookingRequest::where('bookable_id', $artisan->id)
                ->where('bookable_type', get_class($artisan))
                ->where('status', 'pending')
                ->count();
        }
    }
@endphp

<div class="fixed bottom-0 left-0 right-0 lg:invisible bg-noir-profond/95 backdrop-blur-sm border-t border-titane/20 z-50">
    <div class="flex items-center justify-around py-2">

        @switch($userRole)
            @case('client')
                <a href="{{ route('home') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('home') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs">Accueil</span>
                </a>

                <a href="{{ route('marketplace.index') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('marketplace.*') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span class="text-xs">Explorer</span>
                </a>

                <a href="/client/bookings"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs">RDV</span>
                </a>

                <a href="{{ route('client.messages') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('client.messages') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="text-xs">Messages</span>
                </a>

                <a href="/client/profile"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span class="text-xs">Profil</span>
                </a>
            @break

            @case('tattooer')
                <a href="{{ route('tattooer.dashboard') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('tattooer.dashboard') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>

                <a href="{{ route('tattooer.calendar') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('tattooer.calendar') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs">Planning</span>
                </a>

                <a href="{{ route('tattooer.requests') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors relative {{ request()->routeIs('tattooer.requests') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs">Demandes</span>
                    @if ($pendingCount > 0)
                        <span class="absolute -top-1 -right-1 bg-rouge-alerte text-ivoire-text text-xs px-1.5 py-0.5 rounded-full leading-none">
                            {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('tattooer.messages') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('tattooer.messages*') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="text-xs">Messages</span>
                </a>

                <!-- Menu Plus -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="text-xs">Plus</span>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute bottom-full right-0 mb-2 w-48 bg-noir-profond border border-titane/20 rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <a href="{{ route('tattooer.portfolio') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Portfolio
                            </a>
                            <a href="{{ route('tattooer.clients') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Fiches clients
                            </a>
                            <a href="{{ route('tattooer.payments') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Paiements
                            </a>
                            <a href="{{ route('tattooer.settings') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Paramètres
                            </a>
                            <a href="{{ route('tattooer.profile') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Mon profil
                            </a>
                        </div>
                    </div>
                </div>
            @break

            @case('piercer')
                <a href="{{ route('pierceur.dashboard') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('pierceur.dashboard') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>

                <a href="{{ route('pierceur.calendar') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('pierceur.calendar') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs">Planning</span>
                </a>

                <a href="{{ route('pierceur.requests') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors relative {{ request()->routeIs('pierceur.requests') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-xs">Demandes</span>
                    @if ($pendingCount > 0)
                        <span class="absolute -top-1 -right-1 bg-rouge-alerte text-ivoire-text text-xs px-1.5 py-0.5 rounded-full leading-none">
                            {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('pierceur.messages') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('pierceur.messages*') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span class="text-xs">Messages</span>
                </a>

                <!-- Menu Plus -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="text-xs">Plus</span>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute bottom-full right-0 mb-2 w-48 bg-noir-profond border border-titane/20 rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <a href="{{ route('pierceur.portfolio') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Portfolio
                            </a>
                            <a href="{{ route('pierceur.settings') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Paramètres
                            </a>
                            <a href="{{ route('pierceur.profile') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Mon profil
                            </a>
                        </div>
                    </div>
                </div>
            @break

            @case('studio')
                <a href="{{ route('studio.dashboard') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('studio.dashboard') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="text-xs">Dashboard</span>
                </a>

                <a href="{{ route('studio.artists') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('studio.artists') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="text-xs">Artistes</span>
                </a>

                <a href="{{ route('studio.planning') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('studio.planning') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-xs">Planning</span>
                </a>

                <a href="{{ route('studio.stats') }}"
                    class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors {{ request()->routeIs('studio.stats') ? 'text-beige-peau' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-xs">Stats</span>
                </a>

                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex flex-col items-center gap-1 p-2 text-ivoire-text/70 hover:text-beige-peau transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="text-xs">Plus</span>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="absolute bottom-full right-0 mb-2 w-48 bg-noir-profond border border-titane/20 rounded-lg shadow-lg z-50">
                        <div class="py-1">
                            <a href="{{ route('studio.requests') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Demandes
                            </a>
                            <a href="{{ route('studio.clients.index') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Fiches clients
                            </a>
                            <a href="{{ route('studio.messages') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Messages
                            </a>
                            <a href="{{ route('studio.settings') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Paramètres
                            </a>
                            <a href="{{ route('studio.profile') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Profil public
                            </a>
                            <a href="{{ route('studio.billing') }}"
                                class="flex items-center gap-3 px-4 py-2 text-sm text-ivoire-text/70 hover:text-beige-peau hover:bg-gris-fonde transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Facturation
                            </a>
                        </div>
                    </div>
                </div>
            @break
        @endswitch

    </div>
</div>

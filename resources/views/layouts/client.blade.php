<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Ink&Pik</title>
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-noir-profond">

    <div class="flex min-h-screen max-w-full overflow-x-hidden">

        <!-- Sidebar Desktop (cachée sur mobile) -->
        <aside
            class="hidden lg:flex lg:flex-col lg:w-64 bg-gris-fonde border-r border-titane/20 fixed h-full top-0 left-0 z-10">

            <!-- Logo -->
            <div class="p-6 border-b border-titane/20">
                <a href="{{ route('client.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-lg flex items-center justify-center">
                        <img src="{{ asset('images/logo.png') }}" alt="Ink&Pik" class="w-12 h-12">
                    </div>
                    <span class="text-ivoire-text font-bold text-lg">Ink&Pik</span>
                </a>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('home') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('home') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="font-semibold">🏠 Accueil</span>
                </a>

                <a href="{{ route('marketplace.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('marketplace.*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2zm14 0V9a2 2 0 00-2-2M5 11a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2z">
                        </path>
                    </svg>
                    <span class="font-semibold">🛍️ Marketplace</span>
                </a>

                <a href="{{ route('client.booking-requests') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.booking-requests*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="font-semibold">📋 Mes demandes</span>
                </a>

                <a href="{{ route('client.messages') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.messages*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="font-semibold">💬 Messages</span>
                    @php
                        // Badge messages non-lus
                        if (!auth()->check()) {
                            $unreadCount = 0;
                        } elseif (!auth()->user()->client) {
                            $unreadCount = 0;
                        } else {
                            $client = auth()->user()->client;
                            $conversationIds = \App\Models\Conversation::whereHas('bookingRequest', function ($q) use (
                                $client,
                            ) {
                                $q->where('client_id', $client->id);
                            })->pluck('id');

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
                    @endphp
                    @if ($unreadCount > 0)
                        <span
                            class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('client.profile') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.profile') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    <span class="font-semibold">👤 Mon profil</span>
                </a>

                <a href="{{ route('client.settings') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-lg {{ request()->routeIs('client.settings') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text hover:bg-noir-profond' }} transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 002.573 1.066c1.543-.94 3.31-.826 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                    </svg>
                    <span class="font-semibold">⚙️ Paramètres</span>
                </a>
            </nav>

            <!-- User info -->
            <div class="p-4 border-t border-titane/20">
                <div class="flex items-center gap-3 p-3 rounded-lg bg-noir-profond">
                    @if (auth()->user()->client && auth()->user()->client->getFirstMediaUrl('avatar'))
                        <img src="{{ auth()->user()->client->getFirstMediaUrl('avatar') }}" alt="Avatar"
                            class="w-10 h-10 rounded-full">
                    @else
                        <svg class="w-10 h-10 text-beige-peau" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                clip-rule="evenodd"></path>
                        </svg>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-ivoire-text font-semibold truncate">{{ auth()->user()->name }}</p>
                        <p class="text-ivoire-text/60 text-xs">Client</p>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
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

            <!-- Content -->
            <div class="p-4 lg:p-8 pb-24 lg:pb-8 max-w-full overflow-y-auto">
                @yield('content')
            </div>
        </main>

        <!-- Bottom Navigation Mobile (visible sur toutes les pages) -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 bg-gris-fonde border-t border-titane/20 z-50">
            <div class="grid grid-cols-6 gap-1 p-2">
                <a href="{{ route('home') }}"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg {{ request()->routeIs('home') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">🏠</span>
                </a>

                <a href="{{ route('marketplace.index') }}"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg {{ request()->routeIs('marketplace.*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2zm14 0V9a2 2 0 00-2-2M5 11a2 2 0 00-2 2v6a2 2 0 002 2h14a2 2 0 002-2v-6a2 2 0 00-2-2z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">🛍️</span>
                </a>

                <a href="{{ route('client.booking-requests') }}"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg {{ request()->routeIs('client.booking-requests*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002 2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">📋</span>
                </a>

                <a href="{{ route('client.messages') }}"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg relative {{ request()->routeIs('client.messages*') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">💬</span>
                    @if ($unreadCount > 0)
                        <span
                            class="absolute top-0 right-0 w-4 h-4 bg-rouge-alerte text-noir-profond rounded-full text-[8px] font-bold flex items-center justify-center">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('client.profile') }}"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg {{ request()->routeIs('client.profile') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">👤</span>
                </a>

                <a href="{{ route('client.settings') }}"
                    class="flex flex-col items-center gap-1 p-2 rounded-lg {{ request()->routeIs('client.settings') ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 002.573 1.066c1.543-.94 3.31-.826 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                    </svg>
                    <span class="text-[10px] font-semibold">⚙️</span>
                </a>
            </div>
        </nav>
    </div>

    @stack('scripts')
    @livewireScripts
</body>

</html>

<div class="min-h-screen bg-noir-profond py-8">
    <div class="container mx-auto px-4 max-w-4xl">

        <!-- Header Profil -->
        <div class="bg-gris-fonde rounded-xl p-6 mb-6">
            <div class="flex items-start gap-6">

                <!-- Avatar (Spatie Media) -->
                <div class="w-24 h-24 rounded-full overflow-hidden flex-shrink-0 bg-beige-peau/10">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->displayName() }}" class="w-full h-full object-cover">
                </div>

                <!-- Infos -->
                <div class="flex-1">
                    <!-- Pseudo affiché (ou nom si pas de pseudo) -->
                    <h1 class="text-3xl font-Satoshi font-bold text-ivoire-text mb-1">
                        {{ $user->displayName() }}
                    </h1>

                    <p class="text-ivoire-text/70 text-sm mb-3">
                        {{ $user->email }}
                    </p>

                    <!-- Badge client -->
                    <span
                        class="inline-block bg-beige-peau/20 text-beige-peau px-3 py-1 rounded-full text-sm font-semibold">
                        👤 Client
                    </span>
                </div>

                <!-- Actions -->
                <div class="flex gap-3">
                    <a href="{{ route('client.settings') }}"
                        class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                        Modifier mon profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="text-center">
                    <p class="text-4xl font-bold text-beige-peau mb-2">{{ $totalBookings }}</p>
                    <p class="text-ivoire-text/70 text-sm">Réservations totales</p>
                </div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="text-center">
                    <p class="text-4xl font-bold text-beige-peau mb-2">{{ $upcomingAppointments }}</p>
                    <p class="text-ivoire-text/70 text-sm">RDV à venir</p>
                </div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="text-center">
                    <p class="text-4xl font-bold text-beige-peau mb-2">{{ $favoriteArtists }}</p>
                    <p class="text-ivoire-text/70 text-sm">Artistes favoris</p>
                </div>
            </div>

        </div>

        <!-- Navigation onglets -->
        <div class="flex gap-2 mb-6 overflow-x-auto" x-data="{ tab: 'bookings' }">
            <button @click="tab = 'bookings'"
                :class="tab === 'bookings' ? 'bg-beige-peau text-noir-profond' :
                    'bg-gris-fonde text-ivoire-text hover:bg-beige-peau/10'"
                class="px-4 py-2 rounded-lg font-semibold text-sm whitespace-nowrap transition-colors">
                Mes réservations
            </button>

            <button @click="tab = 'messages'"
                :class="tab === 'messages' ? 'bg-beige-peau text-noir-profond' :
                    'bg-gris-fonde text-ivoire-text hover:bg-beige-peau/10'"
                class="px-4 py-2 rounded-lg font-semibold text-sm whitespace-nowrap transition-colors">
                Messages
            </button>

            <button @click="tab = 'favorites'"
                :class="tab === 'favorites' ? 'bg-beige-peau text-noir-profond' :
                    'bg-gris-fonde text-ivoire-text hover:bg-beige-peau/10'"
                class="px-4 py-2 rounded-lg font-semibold text-sm whitespace-nowrap transition-colors">
                Favoris
            </button>
        </div>

        <!-- Contenu onglets -->
        <div x-show="tab === 'bookings'">
            <livewire:client.bookings />
        </div>

        <div x-show="tab === 'messages'" x-cloak>
            <livewire:client.messages />
        </div>

        <div x-show="tab === 'favorites'" x-cloak>
            <p class="text-ivoire-text/50 text-center py-8">Fonctionnalité à venir...</p>
        </div>

    </div>
</div>

<div class="min-h-screen bg-noir-profond">

    <!-- Container principal -->
    <div class="container mx-auto px-4 py-8 max-w-4xl">

        <!-- Header profil -->
        <div class="bg-gris-fonde rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <!-- Avatar -->
                <div class="w-20 h-20 bg-beige-peau/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-beige-peau font-bold text-3xl">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </span>
                </div>

                <!-- Infos -->
                <div class="flex-1">
                    <h1 class="text-ivoire-text font-display font-bold text-2xl mb-1">
                        {{ auth()->user()->name }}
                    </h1>
                    <p class="text-ivoire-text/70 text-sm mb-3">
                        {{ auth()->user()->email }}
                    </p>

                    <a href="{{ route('client.settings') }}"
                        class="inline-flex items-center gap-2 text-beige-peau text-sm font-semibold hover:underline">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Modifier mon profil
                    </a>
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
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-4">Mes réservations</h3>
                <p class="text-ivoire-text/70">
                    Vous n'avez pas encore de réservation.
                </p>
            </div>
        </div>

        <div x-show="tab === 'messages'" x-cloak>
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-4">Messages</h3>
                <p class="text-ivoire-text/70">
                    Vous n'avez pas encore de messages.
                </p>
            </div>
        </div>

        <div x-show="tab === 'favorites'" x-cloak>
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-4">Artistes favoris</h3>
                <p class="text-ivoire-text/70">
                    Vous n'avez pas encore d'artistes favoris.
                </p>
            </div>
        </div>

    </div>

</div>

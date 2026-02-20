<div class="max-w-6xl mx-auto">
    <!-- Header Profil -->
    <div
        class="bg-gradient-to-br from-gris-fonde to-gris-fonce/60 rounded-2xl border border-beige-peau/20 shadow-2xl shadow-beige-peau/10 p-6 mb-6">
        <div class="flex items-center justify-between">
            <!-- Avatar + Infos -->
            <div class="flex items-center gap-6">
                <!-- Avatar -->
                <div class="relative">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-cuivre/60 bg-beige-peau/10 shadow-lg shadow-cuivre/40">
                        @if (auth()->user()->getFirstMediaUrl('avatar'))
                            <img src="{{ auth()->user()->getFirstMediaUrl('avatar') }}" alt="Avatar"
                                class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-10 h-10 text-beige-peau" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Nom + Stats rapides -->
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text">
                        {{ $this->client->pseudo ?? ($this->user->pseudo ?? $this->user->first_name . ' ' . $this->user->last_name) }}
                    </h1>
                    <p class="text-ivoire-text/60 text-sm mt-1">{{ $this->user->email }}</p>

                    <div class="flex items-center gap-4 mt-3">
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <span class="text-ivoire-text/80">{{ $totalBookings }} demande(s)</span>
                        </div>

                        @if ($upcomingAppointments > 0)
                            <div class="flex items-center gap-2 text-sm">
                                <svg class="w-4 h-4 text-vert-succes" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span class="text-vert-succes font-semibold">{{ $upcomingAppointments }} RDV à
                                    venir</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Bouton Éditer -->
            <a href="{{ route('client.settings') }}"
                class="px-6 py-3 bg-beige-peau shadow-md shadow-beige-peau/40 text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
                Modifier
            </a>
        </div>
    </div>

    <!-- Navigation onglets -->
    <div class="bg-gris-fonde rounded-xl border border-beige-peau/20 shadow-md shadow-beige-peau/20 mb-6" x-data="{ activeTab: 'demandes' }">
        <div class="flex border-b border-beige-peau/10">
            <button @click="activeTab = 'demandes'"
                :class="activeTab === 'demandes' ? 'text-beige-peau border-b-2 border-beige-peau' :
                    'text-ivoire-text/60 hover:text-ivoire-text'"
                class="flex-1 px-6 py-4 font-semibold transition-all">
                📋 Mes Demandes
            </button>
            <button @click="activeTab = 'historique'"
                :class="activeTab === 'historique' ? 'text-beige-peau border-b-2 border-beige-peau' :
                    'text-ivoire-text/60 hover:text-ivoire-text'"
                class="flex-1 px-6 py-4 font-semibold transition-all">
                📜 Historique
            </button>
            <button @click="activeTab = 'favoris'"
                :class="activeTab === 'favoris' ? 'text-beige-peau border-b-2 border-beige-peau' :
                    'text-ivoire-text/60 hover:text-ivoire-text'"
                class="flex-1 px-6 py-4 font-semibold transition-all">
                ⭐ Favoris
            </button>
        </div>

        <!-- Contenu onglets -->
        <div>
            <div x-show="activeTab === 'demandes'" x-cloak>
                @include('livewire.client.profile-tabs.demandes')
            </div>
            <div x-show="activeTab === 'historique'" x-cloak>
                @include('livewire.client.profile-tabs.historique')
            </div>
            <div x-show="activeTab === 'favoris'" x-cloak>
                @include('livewire.client.profile-tabs.favoris')
            </div>
        </div>
    </div>
</div>

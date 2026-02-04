<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Stat Card : En attente -->
    <a href="{{ route('client.booking-requests', ['status' => 'pending']) }}"
        class="bg-gradient-to-br from-ambre-warning/20 to-ambre-warning/10 rounded-xl border border-ambre-warning/30 p-6 hover:from-ambre-warning/30 hover:to-ambre-warning/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="w-12 h-12 bg-ambre-warning/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-ambre-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-ambre-warning">{{ $pendingBookings }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold">En attente</h3>
        <p class="text-ivoire-text/60 text-sm mt-1">Réponse de l'artiste</p>
        <div class="mt-3 text-ambre-warning text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les demandes en attente
        </div>
    </a>

    <!-- Stat Card : Acceptées -->
    <a href="{{ route('client.booking-requests', ['status' => 'accepted']) }}"
        class="bg-gradient-to-br from-vert-succes/20 to-vert-succes/10 rounded-xl border border-vert-succes/30 p-6 hover:from-vert-succes/30 hover:to-vert-succes/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="w-12 h-12 bg-vert-succes/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-vert-succes">{{ $acceptedBookings }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold">Acceptées</h3>
        <p class="text-ivoire-text/60 text-sm mt-1">Prêtes à planifier</p>
        <div class="mt-3 text-vert-succes text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les demandes acceptées
        </div>
    </a>

    <!-- Stat Card : Terminées -->
    <a href="{{ route('client.booking-requests', ['status' => 'completed']) }}"
        class="bg-gradient-to-br from-beige-peau/20 to-beige-peau/10 rounded-xl border border-beige-peau/30 p-6 hover:from-beige-peau/30 hover:to-beige-peau/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="w-12 h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-beige-peau">{{ $completedBookings }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold">Terminées</h3>
        <p class="text-ivoire-text/60 text-sm mt-1">Projets réalisés</p>
        <div class="mt-3 text-beige-peau text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les projets terminés
        </div>
    </a>
</div>

<!-- Actions rapides -->
<div class="bg-gris-fonde rounded-xl border border-beige-peau/20 shadow-lg p-6 mb-6">
    <h2 class="text-xl font-bold text-ivoire-text mb-4">Actions rapides</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('client.booking-requests') }}"
            class="group p-4 bg-gradient-to-r from-beige-peau/10 to-beige-peau/5 border border-beige-peau/20 rounded-xl hover:border-beige-peau/40 transition-all">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-beige-peau rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-ivoire-text">Mes demandes</h3>
                    <p class="text-sm text-ivoire-text/60">Voir toutes les demandes</p>
                </div>
            </div>
        </a>

        <a href="{{ route('client.messages') }}"
            class="group p-4 bg-gradient-to-r from-vert-succes/10 to-vert-succes/5 border border-vert-succes/20 rounded-xl hover:border-vert-succes/40 transition-all">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-vert-succes rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-ivoire-text">Mes conversations</h3>
                    <p class="text-sm text-ivoire-text/60">Échanger avec les artistes</p>
                </div>
            </div>
        </a>

        <a href="{{ route('client.bookings') }}"
            class="group p-4 bg-gradient-to-r from-ambre-warning/10 to-ambre-warning/5 border border-ambre-warning/20 rounded-xl hover:border-ambre-warning/40 transition-all">
            <div class="flex items-center gap-4">
                <div
                    class="w-12 h-12 bg-ambre-warning rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-ivoire-text">Mes rendez-vous</h3>
                    <p class="text-sm text-ivoire-text/60">Voir les RDV confirmés</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Liste des demandes récentes -->
<div class="bg-gris-fonde rounded-xl border border-beige-peau/20 shadow-lg p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-xl font-bold text-ivoire-text">Demandes récentes</h2>
        <a href="{{ route('client.booking-requests') }}"
            class="text-beige-peau hover:text-beige-peau/80 text-sm font-semibold">
            Voir tout →
        </a>
    </div>

    @if ($this->client->bookingRequests->count() > 0)
        <div class="space-y-4">
            @foreach ($this->client->bookingRequests->take(5) as $booking)
                <a href="{{ route('client.booking-request.show', $booking) }}"
                    class="block p-4 bg-noir-profond rounded-lg border border-beige-peau/10 hover:border-beige-peau/30 transition-all group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <img src="{{ $booking->bookable->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                                alt="Artiste" class="w-12 h-12 rounded-full">
                            <div>
                                <h3
                                    class="font-semibold text-ivoire-text group-hover:text-beige-peau transition-colors">
                                    {{ $booking->bookable->user->name }}</h3>
                                <p class="text-sm text-ivoire-text/60">{{ $booking->description }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $booking->status === 'pending' ? 'bg-ambre-warning/20 text-ambre-warning' : '' }}
                            {{ $booking->status === 'accepted' ? 'bg-vert-succes/20 text-vert-succes' : '' }}
                            {{ $booking->status === 'completed' ? 'bg-beige-peau/20 text-beige-peau' : '' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                            <p class="text-xs text-ivoire-text/60 mt-1">{{ $booking->created_at->diffForHumans() }}
                            </p>

                            @if ($booking->status === 'accepted' && $booking->conversation)
                                <div class="mt-2">
                                    <a href="{{ route('client.chat', $booking->conversation) }}"
                                        class="text-xs text-vert-succes hover:text-vert-succes/80 font-semibold"
                                        onclick="event.stopPropagation()">
                                        💬 Ouvrir le chat
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-ivoire-text/20 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                </path>
            </svg>
            <p class="text-ivoire-text/60 mb-4">Aucune demande pour le moment</p>
            <a href="{{ route('marketplace.index') }}"
                class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold inline-block hover:bg-beige-peau/90">
                Trouver un artiste
            </a>
        </div>
    @endif
</div>

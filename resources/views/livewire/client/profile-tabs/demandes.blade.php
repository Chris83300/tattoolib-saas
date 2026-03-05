<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
    <!-- Stat Card : En attente -->
    <a href="{{ route('client.booking-requests', ['status' => 'pending']) }}"
        class="bg-gradient-to-br from-ambre-warning/25 to-ambre-warning/10 m-2 shadow-md shadow-ambre-warning/10 rounded-xl border border-ambre-warning/30 p-4 sm:p-6 hover:from-ambre-warning/30 hover:to-ambre-warning/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 bg-ambre-warning/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-ambre-warning" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-2xl sm:text-3xl font-bold text-ambre-warning">{{ $pendingBookings }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold text-sm sm:text-base">En attente</h3>
        <p class="text-ivoire-text/60 text-xs sm:text-sm mt-1">Réponse de l'artiste</p>
        <div
            class="mt-2 sm:mt-3 text-ambre-warning text-xs sm:text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les demandes
        </div>
    </a>

    <!-- Stat Card : Acceptées -->
    <a href="{{ route('client.booking-requests', ['status' => 'accepted']) }}"
        class="bg-gradient-to-br from-vert-succes/25 to-vert-succes/10 rounded-xl m-2 shadow-md shadow-vert-succes/10 border border-vert-succes/30 p-4 sm:p-6 hover:from-vert-succes/30 hover:to-vert-succes/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 bg-vert-succes/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-vert-succes" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <span class="text-2xl sm:text-3xl font-bold text-vert-succes">{{ $acceptedBookings }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold text-sm sm:text-base">Acceptées</h3>
        <p class="text-ivoire-text/60 text-xs sm:text-sm mt-1">Prêtes à planifier</p>
        <div
            class="mt-2 sm:mt-3 text-vert-succes text-xs sm:text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les demandes
        </div>
    </a>

    <!-- Stat Card : Terminées -->
    <a href="{{ route('client.booking-requests', ['status' => 'completed']) }}"
        class="bg-gradient-to-br from-beige-peau/25 to-beige-peau/10 rounded-xl m-2 shadow-md shadow-beige-peau/10 border border-beige-peau/30 p-4 sm:p-6 hover:from-beige-peau/30 hover:to-beige-peau/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-beige-peau" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-2xl sm:text-3xl font-bold text-beige-peau">{{ $completedBookings }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold text-sm sm:text-base">Terminées</h3>
        <p class="text-ivoire-text/60 text-xs sm:text-sm mt-1">Projets réalisés</p>
        <div
            class="mt-2 sm:mt-3 text-beige-peau text-xs sm:text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les projets
        </div>
    </a>

    <!-- Stat Card : Refusées -->
    <a href="{{ route('client.booking-requests', ['status' => 'rejected']) }}"
        class="bg-gradient-to-br from-rouge-alerte/25 to-rouge-alerte/10 rounded-xl m-2 shadow-md shadow-rouge-alerte/10 border border-rouge-alerte/30 p-4 sm:p-6 hover:from-rouge-alerte/30 hover:to-rouge-alerte/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 bg-rouge-alerte/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-rouge-alerte" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </div>
            <span class="text-2xl sm:text-3xl font-bold text-rouge-alerte">{{ $rejectedBookings ?? 0 }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold text-sm sm:text-base">Refusées</h3>
        <p class="text-ivoire-text/60 text-xs sm:text-sm mt-1">Non retenues</p>
        <div
            class="mt-2 sm:mt-3 text-rouge-alerte text-xs sm:text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les refus
        </div>
    </a>

    <!-- Stat Card : Annulées -->
    <a href="{{ route('client.booking-requests', ['status' => 'cancelled']) }}"
        class="bg-gradient-to-br from-orange-500/25 to-orange-500/10 rounded-xl m-2 shadow-md shadow-orange-500/10 border border-orange-500/30 p-4 sm:p-6 hover:from-orange-500/30 hover:to-orange-500/20 transition-all cursor-pointer group">
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <div
                class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-orange-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                    </path>
                </svg>
            </div>
            <span class="text-2xl sm:text-3xl font-bold text-orange-500">{{ $cancelledBookings ?? 0 }}</span>
        </div>
        <h3 class="text-ivoire-text font-semibold text-sm sm:text-base">Annulées</h3>
        <p class="text-ivoire-text/60 text-xs sm:text-sm mt-1">Après acceptation</p>
        <div
            class="mt-2 sm:mt-3 text-orange-500 text-xs sm:text-sm font-medium opacity-0 group-hover:opacity-100 transition-opacity">
            → Voir les annulations
        </div>
    </a>
</div>

<!-- Demandes en cours -->
@if ($this->client->bookingRequests()->whereIn('status', ['pending', 'accepted'])->count() > 0)
    <div class="bg-beige-peau/10 border border-beige-peau/30 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-2 mb-2">
            <span class="relative flex h-3 w-3">
                <span
                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-beige-peau opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-beige-peau"></span>
            </span>
            <h3 class="font-bold text-ivoire-text">
                {{ $this->client->bookingRequests()->whereIn('status', ['pending', 'accepted'])->count() }} demande(s)
                en cours</h3>
        </div>
        <!-- Liste des demandes actives -->
        <div class="space-y-4">
            @foreach ($this->client->bookingRequests()->whereIn('status', ['pending', 'accepted'])->take(3)->get() as $bookingRequest)
                <div class="bg-gris-fonde rounded-lg p-4 border border-titane/30">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-ivoire-text mb-1">
                                {{ Str::limit($bookingRequest->tattoo_description, 60) }}
                            </h3>
                            <div class="flex items-center gap-2 text-sm text-ivoire-text/70 mb-1">
                                <span>📍 {{ $bookingRequest->tattoo_location }}</span>
                                @if ($bookingRequest->tattoo_style)
                                    <span>• 🎨 {{ $bookingRequest->tattoo_style }}</span>
                                @endif
                                @if ($bookingRequest->estimated_price)
                                    <span>• 💰
                                        {{ number_format($bookingRequest->estimated_price, 0, ',', ' ') }}€</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 text-sm text-ivoire-text/70 mb-2">
                                @if ($bookingRequest->bookable)
                                    <span class="flex items-center gap-1">
                                        <img src="{{ $bookingRequest->bookable->user->getFirstMediaUrl('avatar') }}"
                                            alt="{{ $bookingRequest->bookable->user->pseudo }}"
                                            class="w-8 h-8 rounded-full object-cover">
                                    </span>
                                    <span>
                                        <span class="font-medium">{{ $bookingRequest->bookable->user->pseudo }}</span>
                                        <span class="text-ivoire-text/60">•
                                            {{ $bookingRequest->bookable_type_label }}</span>
                                    </span>
                                @endif
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full {{ $bookingRequest->status->color() }}20 text-white text-xs font-medium">
                                            {{ $bookingRequest->status->label() }}
                                        </span>
                                        @if ($bookingRequest->unread_messages > 0)
                                            <span
                                                class="ml-2 inline-flex items-center justify-center w-5 h-5 bg-rouge-alerte rounded-full text-noir-profond text-xs font-bold">
                                                {{ $bookingRequest->unread_messages }}
                                            </span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($bookingRequest->status === \App\Enums\BookingRequestStatus::ACCEPTED)
                                    <a href="{{ route('client.chat', $bookingRequest) }}"
                                        class="inline-flex items-center px-3 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 12h.01M2 9a1 1 0 01.01 1.414A1 1 0 01.01 4.141V8a2 2 0 01.01 2 2A2 2 0 01.01zm0 6a2 2 0 00-2 2v2a2 2 0 00-2 2z" />
                                        </svg>
                                        <span>Discuter</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Actions rapides -->
<div class="bg-gris-fonde g p-6 mb-6">
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
<div class="bg-gris-fonde p-6">
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
                            <img src="{{ $booking->bookable->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                                alt="Artiste" class="w-12 h-12 rounded-full">
                            <div>
                                <h3
                                    class="font-semibold text-ivoire-text group-hover:text-beige-peau transition-colors">
                                    {{ $booking->bookable->user->pseudo }}</h3>
                                <p class="text-sm text-ivoire-text/60">{{ $booking->description }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span
                                class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $booking->status === \App\Enums\BookingRequestStatus::PENDING ? 'bg-ambre-warning/20 text-ambre-warning' : '' }}
                            {{ $booking->status === \App\Enums\BookingRequestStatus::ACCEPTED ? 'bg-vert-succes/20 text-vert-succes' : '' }}
                            {{ $booking->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID ? 'bg-vert-succes/20 text-vert-succes' : '' }}
                            {{ $booking->status === \App\Enums\BookingRequestStatus::DATE_CONFIRMED ? 'bg-vert-succes/20 text-vert-succes' : '' }}
                            {{ $booking->status === \App\Enums\BookingRequestStatus::COMPLETED ? 'bg-beige-peau/20 text-beige-peau' : '' }}
                            {{ $booking->status === \App\Enums\BookingRequestStatus::CANCELLED ? 'bg-rouge-alerte/20 text-rouge-alerte' : '' }}
                            {{ $booking->status === \App\Enums\BookingRequestStatus::NO_SHOW ? 'bg-rouge-alerte/20 text-rouge-alerte' : '' }}">
                                {{ $booking->status->label() }}
                            </span>
                            <p class="text-xs text-ivoire-text/60 mt-1">{{ $booking->created_at->diffForHumans() }}
                            </p>

                            @if ($booking->status === \App\Enums\BookingRequestStatus::ACCEPTED && $booking->conversation)
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

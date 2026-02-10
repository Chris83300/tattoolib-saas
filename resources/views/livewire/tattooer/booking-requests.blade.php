<div class="container mx-auto max-w-6xl">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-ivoire-text font-display mb-2">
            Demandes de réservation
        </h1>
        <a href="{{ route('tattooer.dashboard') }}" class="text-ivoire-text/70 hover:text-beige-peau transition-colors">
            ← Retour au dashboard
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-gris-fonde rounded-xl p-6 mb-6">
        <div class="flex flex-wrap gap-4">
            <button class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold">
                En attente ({{ $pendingRequests ?? 0 }})
            </button>
            <button
                class="px-4 py-2 bg-gris-fonde text-ivoire-text/70 rounded-lg hover:bg-beige-peau/10 transition-colors">
                Acceptées
            </button>
            <button
                class="px-4 py-2 bg-gris-fonde text-ivoire-text/70 rounded-lg hover:bg-beige-peau/10 transition-colors">
                Rejetées
            </button>
        </div>
    </div>

    <!-- Liste des demandes -->
    <div class="space-y-4">
        @forelse ($bookingRequests ?? [] as $request)
            <div class="bg-gris-fonde rounded-xl p-6 hover:bg-beige-peau/5 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-ivoire-text mb-1">
                            {{ $request->client->pseudo ?? ($request->client->first_name . ' ' . $request->client->last_name ?? 'Client') }}
                        </h3>
                        <p class="text-ivoire-text/70 text-sm">
                            Demande du {{ $request->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-sm font-semibold">
                        En attente
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-ivoire-text/70 text-sm mb-1">Tatouage</p>
                        <p class="text-ivoire-text">{{ $request->tattoo_description ?? 'Non spécifié' }}</p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/70 text-sm mb-1">Zone du corps</p>
                        <p class="text-ivoire-text">{{ $request->body_zone ?? 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/70 text-sm mb-1">Taille</p>
                        <p class="text-ivoire-text">{{ $request->tattoo_size ?? 'Non spécifiée' }}</p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/70 text-sm mb-1">Budget estimé</p>
                        <p class="text-ivoire-text">
                            {{ $request->estimated_total_price ? number_format($request->estimated_total_price, 2) . '€' : 'Non spécifié' }}
                        </p>
                    </div>
                </div>

                @if ($request->notes)
                    <div class="mb-4">
                        <p class="text-ivoire-text/70 text-sm mb-1">Notes du client</p>
                        <p class="text-ivoire-text">{{ $request->notes }}</p>
                    </div>
                @endif

                {{-- Dates sélectionnées par le client -- Afficher si client_selected_dates existe --}}
                @if ($request->client_selected_dates && $request->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID)
                    <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4 mb-4">
                        <h4 class="text-sm font-bold text-vert-succes mb-2">📅 Le client est disponible :</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($request->client_selected_dates as $dateInfo)
                                <button
                                    wire:click="openBookingModal('{{ $dateInfo['date'] }}', '{{ $dateInfo['period'] ?? '' }}', {{ $request->id }})"
                                    class="group flex items-center gap-2 px-4 py-2.5 bg-gris-fonde border border-titane/30 rounded-lg hover:border-beige-peau hover:bg-beige-peau/10 transition-all">
                                    <span class="text-ivoire-text font-medium">
                                        {{ \Carbon\Carbon::parse($dateInfo['date'])->translatedFormat('D d M') }}
                                    </span>
                                    <span class="text-xs text-titane">
                                        {{ match ($dateInfo['period'] ?? '') {'morning' => '☀️ Matin','afternoon' => '🌤️ Après-midi',default => '🔄'} }}
                                    </span>
                                    <span
                                        class="text-beige-peau opacity-0 group-hover:opacity-100 transition text-sm font-bold">
                                        → Booker
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-3">
                    <button
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        Accepter
                    </button>
                    <button
                        class="px-4 py-2 bg-red-500/20 text-red-400 rounded-lg font-semibold hover:bg-red-500/30 transition-colors">
                        Refuser
                    </button>
                    <button
                        class="px-4 py-2 bg-gris-fonde text-ivoire-text/70 rounded-lg font-semibold hover:bg-beige-peau/10 transition-colors">
                        Contacter
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-gris-fonde rounded-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-beige-peau" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                    </path>
                </svg>
                <h3 class="text-xl font-bold text-ivoire-text mb-2">
                    Aucune demande en attente
                </h3>
                <p class="text-ivoire-text/70">
                    Vous n'avez pas de nouvelles demandes de réservation pour le moment.
                </p>
            </div>
        @endforelse
    </div>
</div>

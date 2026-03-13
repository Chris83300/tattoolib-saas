@extends('layouts.tattooer')

@section('title', 'Demandes de projet')

@section('content')
    <div class="space-y-6">
        {{-- Composant Livewire — Modale acceptation --}}
        <livewire:tattooer.accept-booking-modal />

        <!-- Header + Onglets -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text mb-2">
                        @if (auth()->user()->isPiercer())
                            Demandes de piercing
                        @else
                            Demandes de projet
                        @endif
                    </h1>
                    <p class="text-ivoire-text/70">
                        @if (auth()->user()->isPiercer())
                            Gérez vos demandes de piercing
                        @else
                            Gérez vos demandes de réservation
                        @endif
                    </p>
                </div>
            </div>

            <!-- ONGLETS -->
            <div class="flex gap-2 border-b border-titane/30 pb-4 mb-6">
                @foreach ([
            'pending' => 'En attente',
            'accepted' => 'Acceptées',
            'confirmed' => 'Confirmées',
            'completed' => 'Terminées',
            'expired' => 'Expirées',
            'cancelled' => 'Annulées',
            'rejected' => 'Refusées',
        ] as $key => $label)
                    <a href="{{ route($tattooer->routePrefix() . '.requests') }}?status={{ $key }}"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-all relative
                              {{ $filter === $key ? 'bg-beige-peau text-noir-profond' : 'text-ivoire-text/60 hover:text-ivoire-text' }}">
                        {{ $label }}
                        @if (($counts[$key] ?? 0) > 0)
                            <span
                                class="ml-2 bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs">{{ $counts[$key] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- Filtres -->
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="search-client" placeholder="Rechercher un client..."
                        class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:outline-none">
                </div>

                <select id="filter-status"
                    class="px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                    <option value="">Tous les statuts</option>
                    <option value="pending">En attente</option>
                    <option value="accepted">Acceptées</option>
                    <option value="confirmed">Confirmées</option>
                    <option value="completed">Terminées</option>
                    <option value="expired">Expirées</option>
                    <option value="rejected">Refusées</option>
                    <option value="cancelled">Annulées</option>
                </select>
            </div>
        </div>

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            @php
                $statusCounts = $requests->groupBy('status')->map->count();
            @endphp

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div id="count-pending" class="text-2xl font-bold text-ambre-warning mb-1">
                    {{ $statusCounts->get('pending', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">En attente</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div id="count-accepted" class="text-2xl font-bold text-beige-peau mb-1">
                    {{ $statusCounts->get('accepted', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Acceptées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div id="count-confirmed" class="text-2xl font-bold text-vert-succes mb-1">
                    {{ $statusCounts->get('date_confirmed', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Confirmées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div id="count-completed" class="text-2xl font-bold text-vert-succes mb-1">
                    {{ $statusCounts->get('completed', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Terminées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div id="count-expired" class="text-2xl font-bold text-rouge-alerte mb-1">
                    {{ $statusCounts->get('expired', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Expirées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div id="count-cancelled" class="text-2xl font-bold text-rouge-alerte mb-1">
                    {{ $statusCounts->get('cancelled', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Annulées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div id="count-rejected" class="text-2xl font-bold text-rouge-alerte mb-1">
                    {{ $statusCounts->get('rejected', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Refusées</div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="space-y-4">
            @forelse ($requests as $request)
                <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30 hover:border-beige-peau/30 transition-colors"
                    data-status="{{ $request->status }}">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Image client -->
                        <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-cuivre/60 bg-beige-peau/10">
                            @if ($request->client->user && $request->client->user->hasMedia('avatar'))
                                <img src="{{ $request->client->user->getFirstMediaUrl('avatar') }}"
                                    alt="Avatar de {{ $request->client->first_name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-ivoire-text/40">
                                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Infos demande -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2 mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-ivoire-text mb-1">
                                        {{ $request->client->pseudo }}
                                    </h3>
                                    <p class="text-ivoire-text/70 text-sm">
                                        {{ $request->client->user->email }} • {{ $request->client->phone }}
                                    </p>
                                </div>

                                <!-- Badge statut -->
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold inline-block
                            {{ match ($request->status->value) {
                                'pending' => 'bg-ambre-warning/20 text-ambre-warning',
                                'accepted' => 'bg-beige-peau/20 text-beige-peau',
                                'rejected', 'no_show', 'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                'completed', 'deposit_paid', 'confirmed_date' => 'bg-vert-succes/20 text-vert-succes',
                                default => 'bg-titane/20 text-ivoire-text/60',
                            } }}">
                                    {{ match ($request->status->value) {
                                        'pending' => '⏳ En attente',
                                        'accepted' => '✓ Acceptée',
                                        'rejected' => '✕ Refusée',
                                        'completed' => '✅ Terminée',
                                        'cancelled' => '❌ Annulée',
                                        default => $request->status->value,
                                    } }}
                                </span>
                            </div>

                            <!-- Description projet -->
                            <div class="mb-3">
                                @if (auth()->user()->isPiercer())
                                    @php
                                        $descriptionLines = explode("\n", $request->description);
                                        $typeLine = collect($descriptionLines)->first(
                                            fn($line) => str_contains($line, 'Type :'),
                                        );
                                        $precisionsLine = collect($descriptionLines)->first(
                                            fn($line) => str_contains($line, 'Précisions :'),
                                        );
                                        $specialRequestLine = collect($descriptionLines)->first(
                                            fn($line) => str_contains($line, 'Demande spécifique :'),
                                        );
                                    @endphp
                                    <p class="text-ivoire-text/80 line-clamp-2">
                                        @if ($typeLine)
                                            <strong>Type de piercing :</strong> {{ str_replace('Type : ', '', $typeLine) }}
                                        @endif
                                        @if ($precisionsLine)
                                            <br><strong>Précisions :</strong>
                                            {{ str_replace('Précisions : ', '', $precisionsLine) }}
                                        @endif
                                        @if ($specialRequestLine)
                                            <br><strong>Demande spécifique :</strong>
                                            {{ str_replace('Demande spécifique : ', '', $specialRequestLine) }}
                                        @endif
                                    </p>
                                @else
                                    <p class="text-ivoire-text/80 line-clamp-2">
                                        <strong>Projet :</strong> {{ $request->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Détails -->
                            <div class="flex flex-wrap gap-4 text-sm text-ivoire-text/60 mb-4">
                                <span>📍 {{ $request->body_zone }}</span>
                                @if (auth()->user()->isTattooer())
                                    @if ($request->total_deposit_amount)
                                        <span>💰 Acompte :
                                            {{ number_format($request->total_deposit_amount, 2, ',', ' ') }}€</span>
                                    @endif
                                @else
                                    <span>� {{ $request->tattoo_size }}</span>
                                    @if ($request->price_estimate_max)
                                        <span>💰 {{ number_format($request->price_estimate_max, 2, ',', ' ') }}€</span>
                                    @endif
                                @endif
                                @if ($request->preferred_date)
                                    <span>📅 {{ $request->preferred_date->format('d/m/Y') }}</span>
                                @endif
                                <span>🕒 {{ $request->created_at->diffForHumans() }}</span>
                            </div>

                            <!-- Images référence (si présentes) -->
                            @if ($request->getMedia('reference_images')->isNotEmpty())
                                <div class="flex gap-2 mb-4">
                                    @foreach ($request->getMedia('reference_images')->take(4) as $media)
                                        <img src="{{ $media->getUrl() }}" alt="Référence"
                                            class="w-16 h-16 rounded-lg object-cover cursor-pointer hover:ring-2 hover:ring-beige-peau"
                                            onclick="openLightbox('{{ $media->getUrl() }}')">
                                    @endforeach
                                    @if ($request->getMedia('reference_images')->count() > 4)
                                        <div
                                            class="w-16 h-16 rounded-lg bg-noir-profond flex items-center justify-center text-ivoire-text/60 text-xs">
                                            +{{ $request->getMedia('reference_images')->count() - 4 }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Actions -->
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route($tattooer->routePrefix() . '.request.show', $request) }}"
                                    class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                    Voir détails
                                </a>

                                @if ($request->status->value === 'pending')
                                    <button type="button"
                                        onclick="Livewire.dispatch('open-accept-modal', { bookingRequestId: {{ $request->id }} })"
                                        class="px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                        ✓ Accepter
                                    </button>

                                    <form action="{{ route($tattooer->routePrefix() . '.request-reject', $request) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30 transition-colors"
                                            onclick="return confirm('Refuser cette demande ?')">
                                            ✕ Refuser
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gris-fonde rounded-xl p-12 text-center">
                    <div class="text-6xl mb-4">📭</div>
                    <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucune demande</h3>
                    <p class="text-ivoire-text/60">
                        @if (auth()->user()->isPiercer())
                            Vous n'avez pas encore reçu de demandes de piercing.
                        @else
                            Vous n'avez pas encore reçu de demandes de projet.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="fixed inset-0 z-50 bg-noir-profond/95 backdrop-blur-sm hidden" onclick="closeLightbox()">
        <button class="absolute top-4 right-4 text-ivoire-text hover:text-rouge-alerte text-4xl"
            onclick="closeLightbox()">×</button>
        <div class="flex items-center justify-center h-full p-4">
            <img id="lightbox-image" src="" alt="Image agrandie"
                class="max-w-full max-h-full object-contain rounded-lg">
        </div>
    </div>

    <script>
        function openLightbox(imageUrl) {
            document.getElementById('lightbox-image').src = imageUrl;
            document.getElementById('lightbox').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeLightbox();
        });

        // Filtrage
        document.getElementById('filter-status').addEventListener('change', function() {
            const status = this.value;
            const cards = document.querySelectorAll('[data-status]');

            // Mettre à jour les compteurs
            const statusCounts = {
                pending: 0,
                accepted: 0,
                completed: 0,
                expired: 0,
                cancelled: 0,
                rejected: 0
            };

            cards.forEach(card => {
                const cardStatus = card.dataset.status;

                // Compter pour les stats
                if (cardStatus === 'pending') statusCounts.pending++;
                else if (cardStatus === 'accepted' || cardStatus === 'deposit_requested' || cardStatus ===
                    'deposit_paid') statusCounts.accepted++;
                else if (cardStatus === 'date_confirmed') statusCounts.date_confirmed++;
                else if (cardStatus === 'completed') statusCounts.completed++;
                else if (cardStatus === 'cancelled') statusCounts.cancelled++;
                else if (cardStatus === 'rejected') statusCounts.rejected++;

                // Afficher/masquer la carte
                if (!status || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Mettre à jour les compteurs dans le DOM
            document.getElementById('count-pending').textContent = statusCounts.pending;
            document.getElementById('count-accepted').textContent = statusCounts.accepted;
            document.getElementById('count-date_confirmed').textContent = statusCounts.date_confirmed;
            document.getElementById('count-completed').textContent = statusCounts.completed;
            document.getElementById('count-cancelled').textContent = statusCounts.cancelled;
            document.getElementById('count-rejected').textContent = statusCounts.rejected;
        });

        // Mettre à jour les compteurs au chargement
        updateCounters();

        // Recherche
        document.getElementById('search-client').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            const cards = document.querySelectorAll('.bg-gris-fonde.rounded-xl.p-6');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(search)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });

            // Mettre à jour les compteurs après recherche
            updateCounters();
        });
    </script>
@endsection

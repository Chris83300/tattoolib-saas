@extends('layouts.tattooer')

@section('title', 'Demandes de projet')

@section('content')
    <div class="space-y-6">

        <!-- Header + Onglets -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text mb-2">Demandes de projet</h1>
                    <p class="text-ivoire-text/70">Gérez vos demandes de réservation</p>
                </div>
            </div>

            <!-- ONGLETS -->
            <div class="flex gap-2 border-b border-titane/30 pb-4 mb-6">
                @foreach ([
            'pending' => 'En attente',
            'accepted' => 'Acceptées',
            'confirmed' => 'Confirmées',
            'completed' => 'Terminées',
            'cancelled' => 'Annulées',
        ] as $key => $label)
                    <a href="{{ route('tattooer.requests') }}?status={{ $key }}"
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
                    <option value="rejected">Refusées</option>
                    <option value="cancelled">Annulées</option>
                </select>
            </div>
        </div>

        <!-- Stats rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $statusCounts = $requests->groupBy('status')->map->count();
            @endphp

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-ambre-warning mb-1">
                    {{ $statusCounts->get('pending', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">En attente</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-beige-peau mb-1">
                    {{ $statusCounts->get('accepted', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Acceptées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-vert-succes mb-1">
                    {{ $statusCounts->get('completed', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Terminées</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-rouge-alerte mb-1">
                    {{ $statusCounts->get('cancelled', 0) }}
                </div>
                <div class="text-ivoire-text/60 text-xs">Annulées</div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="space-y-4">
            @forelse ($requests as $request)
                <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30 hover:border-beige-peau/30 transition-colors"
                    data-status="{{ $request->status }}">
                    <div class="flex flex-col lg:flex-row gap-6">
                        <!-- Image client -->
                        <div class="flex-shrink-0">
                            <img src="{{ $request->client->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                                alt="{{ $request->client->user->name }}"
                                class="w-20 h-20 rounded-full border-2 border-beige-peau/20">
                        </div>

                        <!-- Infos demande -->
                        <div class="flex-1">
                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2 mb-3">
                                <div>
                                    <h3 class="text-lg font-semibold text-ivoire-text mb-1">
                                        {{ $request->client->first_name }} {{ $request->client->last_name }}
                                    </h3>
                                    <p class="text-ivoire-text/70 text-sm">
                                        {{ $request->client->user->email }} • {{ $request->client->phone }}
                                    </p>
                                </div>

                                <!-- Badge statut -->
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold inline-block
                            {{ match ($request->status) {
                                'pending' => 'bg-ambre-warning/20 text-ambre-warning',
                                'accepted' => 'bg-beige-peau/20 text-beige-peau',
                                'rejected' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                'completed' => 'bg-vert-succes/20 text-vert-succes',
                                'cancelled' => 'bg-gris-fonde/50 text-ivoire-text/50',
                                default => 'bg-titane/20 text-ivoire-text/60',
                            } }}">
                                    {{ match ($request->status) {
                                        'pending' => '⏳ En attente',
                                        'accepted' => '✓ Acceptée',
                                        'rejected' => '✕ Refusée',
                                        'completed' => '✅ Terminée',
                                        'cancelled' => '❌ Annulée',
                                        default => $request->status,
                                    } }}
                                </span>
                            </div>

                            <!-- Description projet -->
                            <div class="mb-3">
                                <p class="text-ivoire-text/80 line-clamp-2">
                                    <strong>Projet :</strong> {{ $request->description }}
                                </p>
                            </div>

                            <!-- Détails -->
                            <div class="flex flex-wrap gap-4 text-sm text-ivoire-text/60 mb-4">
                                <span>📍 {{ $request->body_zone }}</span>
                                <span>📏 {{ $request->tattoo_size }}</span>
                                @if ($request->estimated_total_price)
                                    <span>💰 {{ number_format($request->estimated_total_price, 2, ',', ' ') }}€</span>
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
                                <a href="{{ route('tattooer.request.show', $request) }}"
                                    class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                    Voir détails
                                </a>

                                @if ($request->status === 'pending')
                                    <form action="{{ route('tattooer.request.accept', $request) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors"
                                            onclick="return confirm('Accepter cette demande ?')">
                                            ✓ Accepter
                                        </button>
                                    </form>

                                    <form action="{{ route('tattooer.request-reject', $request) }}" method="POST"
                                        class="inline">
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
                    <p class="text-ivoire-text/60">Vous n'avez pas encore reçu de demandes de projet.</p>
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

            cards.forEach(card => {
                if (!status || card.dataset.status === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

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
        });
    </script>
@endsection

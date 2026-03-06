@extends('layouts.tattooer')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                    Clients
                </h1>
                <p class="text-ivoire-text/70">
                    Gérez vos clients et leur historique
                </p>
            </div>

            <div class="flex gap-3">
                <!-- Recherche -->
                <form action="{{ route($tattooer->routePrefix() . '.clients') }}" method="GET" class="flex gap-3">
                    <div class="relative flex-1 max-w-md">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Rechercher un client..."
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-ivoire-text/50" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button type="submit"
                        class="px-4 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        🔍 Rechercher
                    </button>
                </form>

                <!-- Bouton création client -->
                @if (auth()->user()->isTattooer() ? auth()->user()->tattooer?->isPro() : auth()->user()->piercer?->isPro())
                    <a href="{{ route($tattooer->routePrefix() . '.clients.create') }}"
                        class="px-4 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold text-sm hover:bg-beige-peau/90 transition-colors">
                        ➕ Créer une fiche client
                    </a>
                @else
                    <a href="{{ route($tattooer->routePrefix() . '.subscription.plans') }}"
                        class="px-4 py-3 bg-beige-peau/30 text-beige-peau rounded-lg font-semibold text-sm hover:bg-beige-peau/40 transition-colors">
                        🔒 Créer une fiche client (PRO)
                    </a>
                @endif
            </div>
        </div>

        <!-- Liste des clients -->
        @if ($clients->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($clients as $client)
                    <a href="{{ route($tattooer->routePrefix() . '.client.show', $client) }}"
                        class="block bg-gris-fonde rounded-xl p-4 hover:bg-gris-fonde/80 transition-all hover:scale-[1.02] active:scale-[0.98]">

                        <div class="flex items-center gap-3 mb-3">
                            {{-- Avatar --}}
                            <div
                                class="w-12 h-12 rounded-full overflow-hidden bg-titane/30 flex-shrink-0 flex items-center justify-center">
                                @php
                                    // Avatar : d'abord sur User, sinon sur Client
$avatarUrl = null;
if ($client->user && $client->user->getFirstMediaUrl('avatar')) {
    $avatarUrl = $client->user->getFirstMediaUrl('avatar');
} elseif ($client->getFirstMediaUrl('avatar')) {
    $avatarUrl = $client->getFirstMediaUrl('avatar');
                                    }
                                @endphp

                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="text-lg font-bold text-beige-peau">
                                        {{ strtoupper(substr($client->first_name ?? ($client->user?->name ?? '?'), 0, 1)) }}{{ strtoupper(substr($client->last_name ?? '', 0, 1)) }}
                                    </span>
                                @endif
                            </div>

                            {{-- Nom + pseudo --}}
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-ivoire-text truncate">
                                    @php
                                        $pseudo = $client->pseudo ?? ($client->user?->pseudo ?? null);
                                        $fullName = trim(
                                            ($client->first_name ?? '') . ' ' . ($client->last_name ?? ''),
                                        );
                                        if (!$fullName) {
                                            $fullName = $client->user?->name ?? 'Client';
                                        }
                                    @endphp

                                    @if ($pseudo)
                                        {{ $pseudo }}
                                    @else
                                        {{ $fullName }}
                                    @endif
                                </h3>

                                @if ($pseudo && $fullName && $fullName !== $pseudo)
                                    <p class="text-xs text-ivoire-text/50 truncate">{{ $fullName }}</p>
                                @endif

                                <p class="text-xs text-titane mt-0.5">
                                    {{ $client->artisan_stats->total_requests }}
                                    demande{{ $client->artisan_stats->total_requests > 1 ? 's' : '' }}
                                    @if ($client->artisan_stats->completed > 0)
                                        · {{ $client->artisan_stats->completed }}
                                        terminée{{ $client->artisan_stats->completed > 1 ? 's' : '' }}
                                    @endif
                                </p>
                            </div>

                            {{-- Badges --}}
                            <div class="flex-shrink-0">
                                @if ($client->is_blacklisted)
                                    <span
                                        class="px-2 py-1 bg-rouge-alerte/20 text-rouge-alerte rounded-full text-xs font-semibold">
                                        ⛔ Blacklisté
                                    </span>
                                @elseif($client->no_show_count > 2)
                                    <span
                                        class="px-2 py-1 bg-ambre-warning/20 text-ambre-warning rounded-full text-xs font-semibold">
                                        ⚠️ {{ $client->no_show_count }} no-shows
                                    </span>
                                @else
                                    <svg class="w-5 h-5 text-titane" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                @endif
                            </div>
                        </div>

                        {{-- Infos complémentaires --}}
                        <div class="flex items-center justify-between text-xs text-titane border-t border-titane/20 pt-2">
                            <span>
                                @if ($client->artisan_stats->total_paid > 0)
                                    💰 {{ number_format($client->artisan_stats->total_paid, 0) }}€ versés
                                @else
                                    Aucun paiement
                                @endif
                            </span>
                            <span>
                                @if ($client->artisan_stats->last_request_at)
                                    {{ \Carbon\Carbon::parse($client->artisan_stats->last_request_at)->diffForHumans() }}
                                @endif
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center">
                {{ $clients->withQueryString()->links() }}
            </div>
        @else
            <div class="bg-gris-fonde rounded-xl p-12 text-center">
                <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                    @if (request('search'))
                        Aucun résultat pour "{{ request('search') }}"
                    @else
                        Aucun client pour le moment
                    @endif
                </h3>
                <p class="text-ivoire-text/60 text-sm">
                    Les clients apparaissent ici après le versement de leur acompte.
                </p>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            // Gestion de la recherche en temps réel (optionnel)
            let searchTimeout;
            const searchInput = document.querySelector('input[name="search"]');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        this.form.submit();
                    }, 500);
                });
            }
        </script>
    @endpush
@endsection

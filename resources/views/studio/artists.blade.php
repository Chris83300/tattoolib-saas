@extends('layouts.studio')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-ivoire-text">Artistes</h1>
                <p class="text-sm text-titane mt-1">
                    {{ $activeArtists->count() }} artiste{{ $activeArtists->count() > 1 ? 's' : '' }}
                    actif{{ $activeArtists->count() > 1 ? 's' : '' }}
                    @if ($paidArtistCount > 0)
                        <span class="text-beige-peau">(dont {{ $paidArtistCount }}
                            supplémentaire{{ $paidArtistCount > 1 ? 's' : '' }} à 39,99€/mois)</span>
                    @endif
                </p>
            </div>
            @if ($canAddArtist)
                <a href="{{ route('studio.artists.create') }}"
                    class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                    + Ajouter
                </a>
            @elseif ($needsSubscriptionForNewArtist)
                <a href="{{ route('studio.subscribe') }}"
                    class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                    🔓 Souscrire pour ajouter
                </a>
            @endif
        </div>

        {{-- Artistes actifs --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($activeArtists as $sa)
                <x-ui.artistCard :studioArtist="$sa" />
            @empty
                <div class="col-span-full text-center py-12">
                    <div class="text-titane text-lg mb-4">
                        Aucun artiste actif dans votre studio
                    </div>
                    <a href="{{ route('studio.artists.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter votre premier artiste
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Invitations en attente --}}
        @if ($pendingInvitations->count() > 0)
            <div class="mt-8">
                <h2 class="text-lg font-bold text-ivoire-text mb-4">⏳ Invitations en attente</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($pendingInvitations as $inv)
                        <x-ui.artistCard :studioArtist="$inv" />
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Pricing info --}}
        <div class="bg-gris-fonde/50 rounded-xl p-4 border border-titane/10">
            <p class="text-xs text-titane">
                💡 Votre abonnement Studio inclut <strong class="text-ivoire-text">1 artiste</strong>.
                Chaque artiste supplémentaire coûte <strong class="text-beige-peau">39,99€/mois</strong>.
                Facturation actuelle : <strong class="text-ivoire-text">{{ number_format($monthlyPrice, 2) }}€/mois</strong>
            </p>
        </div>
    </div>
@endsection

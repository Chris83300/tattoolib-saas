@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Artistes</h1>
            <p class="text-sm text-titane mt-1">
                {{ $activeArtists->count() }} artiste{{ $activeArtists->count() > 1 ? 's' : '' }} actif{{ $activeArtists->count() > 1 ? 's' : '' }}
                @if ($paidArtistCount > 0)
                    <span class="text-beige-peau">(dont {{ $paidArtistCount }} supplémentaire{{ $paidArtistCount > 1 ? 's' : '' }} à 39,99€/mois)</span>
                @endif
            </p>
        </div>
        @if ($canAddArtist)
            <a href="{{ route('studio.artists.create') }}"
                class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                + Ajouter
            </a>
        @endif
    </div>

    {{-- Artistes actifs --}}
    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        @forelse ($activeArtists as $sa)
            <div class="flex items-center gap-3 p-4">
                <img src="{{ $sa->user?->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                    alt="{{ $sa->user?->name }}" class="w-12 h-12 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">{{ $sa->user?->name }}</p>
                    <p class="text-xs text-titane">
                        {{ $sa->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                        • Rejoint {{ $sa->joined_at?->diffForHumans() ?? '—' }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    {{-- Toggle actif/inactif --}}
                    <form action="{{ route('studio.artists.toggle', $sa) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit"
                            class="text-xs px-3 py-1.5 rounded-lg font-semibold transition-colors {{ $sa->is_active ? 'bg-vert-validation/20 text-vert-validation' : 'bg-rouge-alerte/20 text-rouge-alerte' }}">
                            {{ $sa->is_active ? 'Actif' : 'Inactif' }}
                        </button>
                    </form>
                    {{-- Retirer --}}
                    <form action="{{ route('studio.artists.remove', $sa) }}" method="POST"
                        onsubmit="return confirm('Retirer cet artiste du studio ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rouge-alerte/60 hover:text-rouge-alerte p-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm text-titane text-center py-8">Aucun artiste actif</p>
        @endforelse
    </div>

    {{-- Invitations en attente --}}
    @if ($pendingInvitations->count() > 0)
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">⏳ Invitations en attente</h2>
            @foreach ($pendingInvitations as $inv)
                <div class="flex items-center gap-3 py-2">
                    <div class="w-10 h-10 rounded-full bg-titane/20 flex items-center justify-center text-titane">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-ivoire-text">{{ $inv->invitation_email }}</p>
                        <p class="text-xs text-titane">
                            {{ $inv->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                            • Invité {{ $inv->invited_at?->diffForHumans() ?? '—' }}
                        </p>
                    </div>
                    <form action="{{ route('studio.artists.remove', $inv) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-rouge-alerte/60 hover:text-rouge-alerte transition-colors">Annuler</button>
                    </form>
                </div>
            @endforeach
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

@extends('layouts.studio')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('studio.artists') }}" class="text-titane hover:text-ivoire-text transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div class="flex-1">
            <h1 class="text-2xl font-bold text-ivoire-text">
                {{ $studioArtist->artist_name ?: $studioArtist->user?->name ?? 'Artiste' }}
            </h1>
            <p class="text-sm text-titane">
                {{ $studioArtist->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                @if($studioArtist->joined_at) • Rejoint le {{ $studioArtist->joined_at->format('d/m/Y') }} @endif
            </p>
        </div>

        <div class="flex items-center gap-2">
            <!-- Toggle actif/inactif -->
            <form action="{{ route('studio.artists.toggle', $studioArtist) }}" method="POST">
                @csrf @method('PUT')
                <button type="submit"
                    class="px-3 py-1.5 text-xs rounded-lg font-semibold transition-colors
                        {{ $studioArtist->is_active
                            ? 'bg-rouge-alerte/20 text-rouge-alerte hover:bg-rouge-alerte/30'
                            : 'bg-vert-validation/20 text-vert-validation hover:bg-vert-validation/30' }}">
                    {{ $studioArtist->is_active ? 'Désactiver' : 'Activer' }}
                </button>
            </form>

            <!-- Retirer du studio -->
            <form action="{{ route('studio.artists.remove', $studioArtist) }}" method="POST"
                onsubmit="return confirm('Retirer cet artiste du studio ?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="px-3 py-1.5 text-xs rounded-lg font-semibold bg-titane/20 text-titane hover:bg-rouge-alerte/20 hover:text-rouge-alerte transition-colors">
                    Retirer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Infos artiste -->
        <div class="space-y-4">
            <div class="bg-gris-fonde rounded-xl p-5 space-y-3">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Informations</h2>

                <div class="flex justify-between text-sm">
                    <span class="text-titane">Statut</span>
                    <span class="{{ $studioArtist->is_active ? 'text-vert-validation' : 'text-rouge-alerte' }} font-semibold">
                        {{ $studioArtist->is_active ? 'Actif' : 'Inactif' }}
                    </span>
                </div>

                @if($studioArtist->user?->email)
                    <div class="text-sm">
                        <p class="text-titane mb-0.5">Email</p>
                        <a href="mailto:{{ $studioArtist->user->email }}" class="text-ivoire-text/80 hover:text-beige-peau transition-colors text-xs">
                            {{ $studioArtist->user->email }}
                        </a>
                    </div>
                @endif

                @if($studioArtist->role)
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Rôle</span>
                        <span class="text-ivoire-text">{{ ucfirst($studioArtist->role) }}</span>
                    </div>
                @endif
            </div>

            <!-- Stats -->
            <div class="bg-gris-fonde rounded-xl p-5">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide mb-3">Statistiques</h2>
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">Demandes reçues</span>
                        <span class="text-ivoire-text font-semibold">{{ $stats['total_requests'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-titane">En attente</span>
                        <span class="{{ $stats['pending_requests'] > 0 ? 'text-yellow-400' : 'text-ivoire-text' }} font-semibold">
                            {{ $stats['pending_requests'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières demandes -->
        <div class="lg:col-span-2">
            <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
                <div class="p-4 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">
                        Dernières demandes
                    </h2>
                    <a href="{{ route('studio.requests') }}" class="text-xs text-beige-peau hover:underline">
                        Toutes →
                    </a>
                </div>

                @forelse ($requests as $request)
                    @php
                        $status = is_object($request->status) ? $request->status->value : $request->status;
                    @endphp
                    <a href="{{ route('studio.demandes.show', $request) }}" class="flex items-center gap-3 p-4 hover:bg-noir-profond/40 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-ivoire-text truncate">
                                {{ $request->client?->first_name }} {{ $request->client?->last_name }}
                            </p>
                            <p class="text-xs text-titane mt-0.5">{{ $request->created_at?->diffForHumans() }}</p>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold shrink-0
                            {{ $status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                            {{ in_array($status, ['accepted', 'deposit_paid', 'date_confirmed']) ? 'bg-vert-validation/20 text-vert-validation' : '' }}
                            {{ in_array($status, ['completed', 'fully_completed']) ? 'bg-vert-succes/20 text-vert-succes' : '' }}
                            {{ in_array($status, ['cancelled', 'rejected']) ? 'bg-rouge-alerte/20 text-rouge-alerte' : '' }}
                            {{ !in_array($status, ['pending','accepted','deposit_paid','date_confirmed','completed','fully_completed','cancelled','rejected']) ? 'bg-titane/20 text-titane' : '' }}">
                            {{ str_replace('_', ' ', ucfirst($status)) }}
                        </span>
                    </a>
                @empty
                    <p class="text-sm text-titane text-center py-8">Aucune demande</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

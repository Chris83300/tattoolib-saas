@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Tableau de bord</h1>
            <p class="text-sm text-titane mt-1">Bienvenue, {{ auth()->user()->name }}</p>
        </div>
        @include('partials.export-buttons', ['type' => 'studio', 'year' => now()->year])
    </div>

    {{-- Checklist onboarding (visible pendant le trial, si non complète) --}}
    @php
        $checklist = $studio->getOnboardingChecklist();
        $progress = $studio->onboardingProgress();
        $showChecklist = $studio->onTrial() && !$studio->onboardingComplete();
    @endphp

    @if ($showChecklist)
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6 border border-beige-peau/20">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-sm font-bold text-beige-peau uppercase tracking-wider">🚀 Démarrage rapide</h2>
                    <p class="text-xs text-titane mt-0.5">Configurez votre studio en quelques étapes</p>
                </div>
                <span class="text-sm font-bold text-beige-peau">{{ $progress }}%</span>
            </div>

            <div class="w-full bg-noir-profond rounded-full h-2 mb-4">
                <div class="bg-beige-peau h-2 rounded-full transition-all duration-500"
                    style="width: {{ $progress }}%"></div>
            </div>

            <div class="space-y-2">
                @foreach ($checklist as $step)
                    <div class="flex items-center gap-3 py-2 {{ $step['done'] ? 'opacity-60' : '' }}">
                        <span class="text-lg">{{ $step['done'] ? '✅' : $step['icon'] }}</span>
                        <span class="text-sm {{ $step['done'] ? 'text-titane line-through' : 'text-ivoire-text font-medium' }}">
                            {{ $step['label'] }}
                        </span>
                        @if (!$step['done'])
                            @switch($step['key'])
                                @case('logo')
                                    <a href="{{ route('studio.settings') }}" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                @break
                                @case('artist')
                                    <a href="{{ route('studio.artists.create') }}" class="ml-auto text-xs text-beige-peau hover:underline">Ajouter →</a>
                                @break
                                @case('payment')
                                    <a href="{{ route('studio.settings') }}" class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                @break
                                @case('profile')
                                    <a href="{{ route('studio.settings') }}" class="ml-auto text-xs text-beige-peau hover:underline">Personnaliser →</a>
                                @break
                                @case('booking')
                                    <span class="ml-auto text-xs text-titane">En attente...</span>
                                @break
                            @endswitch
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Compteurs principaux -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-beige-peau">{{ $activeArtists }}</div>
            <div class="text-sm text-titane mt-1">Artiste{{ $activeArtists > 1 ? 's' : '' }} actif{{ $activeArtists > 1 ? 's' : '' }}</div>
        </div>

        <a href="{{ route('studio.requests') }}" class="bg-gris-fonde rounded-xl p-5 border border-titane/20 hover:border-yellow-500/40 transition-colors">
            <div class="flex items-center gap-2">
                <div class="text-3xl font-bold text-yellow-400">{{ $pendingCount }}</div>
                @if($pendingCount > 0)
                    <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                @endif
            </div>
            <div class="text-sm text-titane mt-1">En attente</div>
        </a>

        <a href="{{ route('studio.requests') }}" class="bg-gris-fonde rounded-xl p-5 border border-titane/20 hover:border-vert-validation/40 transition-colors">
            <div class="text-3xl font-bold text-vert-validation">{{ $confirmedCount }}</div>
            <div class="text-sm text-titane mt-1">Confirmée{{ $confirmedCount > 1 ? 's' : '' }}</div>
        </a>

        <div class="bg-gris-fonde rounded-xl p-5 border border-titane/20">
            <div class="text-3xl font-bold text-ivoire-text">{{ number_format($monthlyRevenue, 0, ',', ' ') }}€</div>
            <div class="text-sm text-titane mt-1">Revenu ce mois</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Artistes actifs -->
        <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
            <div class="p-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Artistes</h2>
                <a href="{{ route('studio.artists') }}" class="text-xs text-beige-peau hover:underline">Gérer →</a>
            </div>
            @forelse($artists->take(5) as $artist)
                <div class="p-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-beige-peau/20 flex items-center justify-center shrink-0">
                        <span class="text-beige-peau text-xs font-bold">
                            {{ mb_strtoupper(mb_substr($artist->user?->name ?? 'A', 0, 1)) }}
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-ivoire-text truncate">{{ $artist->user?->name ?? 'Artiste' }}</p>
                        <p class="text-xs text-titane">{{ $artist->artisan_type === 'piercer' ? 'Pierceur' : 'Tatoueur' }}</p>
                    </div>
                    @if($artist->joined_at)
                        <span class="text-xs text-titane shrink-0">{{ $artist->joined_at->format('d/m/Y') }}</span>
                    @endif
                </div>
            @empty
                <div class="p-6 text-center">
                    <p class="text-sm text-titane">Aucun artiste</p>
                    <a href="{{ route('studio.artists.create') }}" class="text-xs text-beige-peau hover:underline mt-1 inline-block">
                        Ajouter votre premier artiste →
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Dernières demandes en attente -->
        <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
            <div class="p-4 flex items-center justify-between">
                <h2 class="text-sm font-semibold text-ivoire-text uppercase tracking-wide">Demandes en attente</h2>
                <a href="{{ route('studio.requests') }}" class="text-xs text-beige-peau hover:underline">Toutes →</a>
            </div>
            @forelse($latestRequests as $request)
                <div class="p-4 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-ivoire-text truncate">
                            {{ $request->client?->first_name }} {{ $request->client?->last_name }}
                        </p>
                        <p class="text-xs text-titane mt-0.5">
                            → {{ $request->bookable?->user?->name ?? 'Artiste' }}
                            • {{ $request->created_at?->diffForHumans() }}
                        </p>
                    </div>
                    <span class="px-2 py-0.5 text-xs rounded-full bg-yellow-500/20 text-yellow-400 font-semibold shrink-0">
                        En attente
                    </span>
                </div>
            @empty
                <p class="text-sm text-titane text-center py-6">Aucune demande en attente</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

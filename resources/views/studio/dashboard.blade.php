@extends('layouts.studio')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-ivoire-text mb-6">Dashboard Studio</h1>

        {{-- Checklist onboarding (visible pendant le trial, si non complète) --}}
        @php
            $checklist = $studio->getOnboardingChecklist();
            $progress = $studio->onboardingProgress();
            $showChecklist = $studio->onTrial() && !$studio->onboardingComplete();
        @endphp

        @if ($showChecklist)
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6 border border-beige-peau/20 mb-8">
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
                            <span
                                class="text-sm {{ $step['done'] ? 'text-titane line-through' : 'text-ivoire-text font-medium' }}">
                                {{ $step['label'] }}
                            </span>
                            @if (!$step['done'])
                                @switch($step['key'])
                                    @case('logo')
                                        <a href="{{ route('studio.settings') }}"
                                            class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                    @break

                                    @case('artist')
                                        <a href="{{ route('studio.artists.create') }}"
                                            class="ml-auto text-xs text-beige-peau hover:underline">Ajouter →</a>
                                    @break

                                    @case('payment')
                                        <a href="{{ route('studio.settings') }}"
                                            class="ml-auto text-xs text-beige-peau hover:underline">Configurer →</a>
                                    @break

                                    @case('profile')
                                        <a href="{{ route('studio.settings') }}"
                                            class="ml-auto text-xs text-beige-peau hover:underline">Personnaliser →</a>
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

        <!-- Stats globales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
                <div class="text-beige-peau text-2xl font-bold">{{ $totalArtists }}</div>
                <div class="text-ivoire-text/70">Artistes total</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
                <div class="text-beige-peau text-2xl font-bold">{{ $activeArtists }}</div>
                <div class="text-ivoire-text/70">Artistes actifs</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
                <div class="text-beige-peau text-2xl font-bold">€{{ number_format($totalRevenue, 2) }}</div>
                <div class="text-ivoire-text/70">Revenu total</div>
            </div>
        </div>

        <!-- Artistes récents -->
        <div class="bg-gris-fonde rounded-xl p-6 border border-ivoire-text/20">
            <h2 class="text-xl font-bold text-beige-peau mb-4">Artistes</h2>
            <div class="space-y-4">
                @forelse($artists as $artist)
                    <div class="bg-noir-profond rounded-lg p-4 border border-ivoire-text/20">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-beige-peau">
                                    {{ $artist->user?->name ?? 'Nom non disponible' }}</h3>
                                <p class="text-ivoire-text/70 text-sm">
                                    {{ $artist->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}</p>
                            </div>
                            <div class="text-ivoire-text/70 text-sm">
                                @if ($artist->joined_at)
                                    Rejoint le {{ $artist->joined_at->format('d/m/Y') }}
                                @else
                                    Date non disponible
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-titane py-8">
                        <p>Aucun artiste trouvé</p>
                        <a href="{{ route('studio.artists.create') }}"
                            class="text-beige-peau hover:underline mt-2 inline-block">
                            Ajouter votre premier artiste →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

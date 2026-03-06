<div class="space-y-6">
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
                    <h2 class="text-sm font-bold text-beige-peau uppercase tracking-wider"> Démarrage rapide</h2>
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

    {{-- En-tête --}}
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Tableau de bord</h1>
        <p class="text-sm text-titane mt-1">{{ $studio->name }}</p>
    </div>

    {{-- Stats rapides --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Artistes</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $artistCount }}</p>
        </div>
        @if ($studio->onTrial())
            <div class="bg-ambre-warning/20 border border-ambre-warning/30 rounded-xl p-4">
                <p class="text-xs text-titane uppercase tracking-wider">Essai restant</p>
                <p class="text-lg font-bold text-ambre-warning mt-1">{{ $studio->trialDaysLeft() }} jours</p>
                <p class="text-xs text-titane mt-1">
                    @if ($studio->trialDaysLeft() > 0)
                        Reste {{ $studio->trialDaysLeft() }} jours avant la fin de la période d'essai
                    @else
                        Essai expiré - <a href="{{ route('studio.subscribe') }}"
                            class="text-beige-peau hover:underline">S'abonner</a>
                    @endif
                </p>
            </div>
        @else
            <div class="bg-gris-fonde rounded-xl p-4">
                <p class="text-xs text-titane uppercase tracking-wider">Ce mois</p>
                <p class="text-2xl font-bold text-beige-peau mt-1">{{ number_format($monthlyPrice, 2) }}€</p>
            </div>
        @endif
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">Demandes en cours</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $pendingRequests }}</p>
        </div>
        <div class="bg-gris-fonde rounded-xl p-4">
            <p class="text-xs text-titane uppercase tracking-wider">RDV aujourd'hui</p>
            <p class="text-2xl font-bold text-ivoire-text mt-1">{{ $todayAppointments }}</p>
        </div>
    </div>

    {{-- Artistes du studio --}}
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">👥 Artistes</h2>
            <a href="{{ route('studio.artists') }}"
                class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">Gérer →</a>
        </div>

        @forelse ($artists as $studioArtist)
            <div class="flex items-center gap-3 py-3 {{ !$loop->last ? 'border-b border-titane/10' : '' }}">
                <img src="{{ $studioArtist->user?->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}"
                    alt="{{ $studioArtist->user?->name }}" class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">
                        {{ $studioArtist->user?->name ?? 'Invitation en attente' }}</p>
                    <p class="text-xs text-titane">
                        {{ $studioArtist->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur' }}
                        @if (!$studioArtist->is_active)
                            <span class="text-rouge-alerte ml-1">• Inactif</span>
                        @endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-titane">
                        @if ($studioArtist->user)
                            Actif
                        @else
                            ⏳ En attente
                        @endif
                    </p>
                </div>
            </div>
        @empty
            <p class="text-sm text-titane text-center py-4">
                Aucun artiste.
                <a href="{{ route('studio.artists.create') }}" class="text-beige-peau hover:underline">Ajouter un
                    artiste</a>
            </p>
        @endforelse
    </div>

    {{-- Actions rapides --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <a href="{{ route('studio.artists.create') }}"
            class="bg-beige-peau text-noir-profond rounded-xl p-4 font-semibold text-center hover:bg-beige-peau/90 transition-colors active:scale-95">
            + Ajouter un artiste
        </a>
        <a href="{{ route('studio.planning') }}"
            class="bg-gris-fonde text-ivoire-text rounded-xl p-4 font-semibold text-center hover:bg-gris-fonde/80 transition-colors border border-titane/20">
            📅 Voir le planning
        </a>
    </div>

    {{-- Info abonnement --}}
    <div class="bg-gris-fonde/50 rounded-xl p-4 border border-titane/10">
        <p class="text-xs text-titane">
            💡 Abonnement Studio : <strong class="text-ivoire-text">1 artiste inclus</strong>.
            Artistes supplémentaires : <strong class="text-beige-peau">24,99€/mois</strong> chacun.
            <a href="{{ route('studio.billing') }}" class="text-beige-peau hover:underline ml-1"> <br>Voir la facturation
                →</a>
        </p>
    </div>
</div>

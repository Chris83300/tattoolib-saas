@props([
    'feature' => 'cette fonctionnalité',
    'blur' => true,
    'compact' => false,
    'proOnly' => false,  // true = réservé PRO uniquement (pas Starter)
])

@php
    $user = auth()->user();
    $isAllowed = false;
    $subscribeRoute = 'studio.subscribe'; // Route par défaut pour les studios

    // Studio owner — TOUJOURS PRO par définition (vérifier en premier)
    if ($user && $user->isStudioOwner()) {
        $isAllowed = true;
        $subscribeRoute = 'studio.subscribe';
    }
    // Artiste rattaché à un studio — hérite du PRO du studio
    elseif ($user && $user->hasRole('studio_artist')) {
        $isAllowed = true;
    }
    // Tattooer indépendant
    elseif ($user && $user->tattooer) {
        $isAllowed = $proOnly
            ? $user->tattooer->isPro()
            : $user->tattooer->canAccessStarterFeature();
        $subscribeRoute = 'tattooer.subscription.plans';
    }
    // Pierceur indépendant
    elseif ($user && $user->piercer) {
        $isAllowed = $proOnly
            ? $user->piercer->isPro()
            : $user->piercer->canAccessStarterFeature();
        $subscribeRoute = 'tattooer.subscription.plans';
    }
@endphp

@if ($isAllowed)
    {{-- PRO : afficher le contenu normalement --}}
    {{ $slot }}
@else
    {{-- FREE : overlay flou avec CTA --}}
    <div class="relative">
        {{-- Contenu flouté --}}
        <div
            class="{{ $blur ? 'blur-sm pointer-events-none select-none' : 'opacity-40 pointer-events-none select-none' }}">
            {{ $slot }}
        </div>

        {{-- Overlay CTA --}}
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <div
                class="bg-gris-fonde/95 backdrop-blur-sm border border-beige-peau/30 rounded-2xl p-6 text-center max-w-sm mx-4 shadow-xl">
                {{-- Icône --}}
                <div class="w-12 h-12 bg-beige-peau/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                @if ($compact)
                    {{-- Version compacte (pour petits espaces) --}}
                    <p class="text-sm text-ivoire-text/80 mb-3">
                        Abonnement requis pour {{ $feature }}
                    </p>
                    <a href="{{ route($subscribeRoute) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-bold hover:bg-beige-peau/90 transition-colors">
                        🚀 S'abonner
                    </a>
                @else
                    {{-- Version complète --}}
                    <h4 class="text-lg font-bold text-ivoire-text mb-1">Abonnement requis</h4>
                    <p class="text-sm text-ivoire-text/70 mb-4">
                        Abonnez-vous au plan Starter ou PRO pour accéder à {{ $feature }}.
                    </p>
                    <a href="{{ route($subscribeRoute) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-beige-peau text-noir-profond rounded-xl text-sm font-bold hover:bg-beige-peau/90 transition-colors active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Voir les plans — dès 9.99€/mois
                    </a>
                    <p class="text-xs text-titane mt-2">Annulable à tout moment</p>
                @endif
            </div>
        </div>
    </div>
@endif

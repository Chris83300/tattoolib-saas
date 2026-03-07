@php
    $artisan = auth()->user()->tattooer ?? (auth()->user()->piercer ?? null);
    $trialService = app(\App\Services\TrialService::class);

    // Abonnement payé actif ? → pas de bannière trial
    $hasPaidSubscription = $artisan?->is_subscribed ?? false;

    $isOnTrial = $artisan && !$hasPaidSubscription && $trialService->isOnTrial($artisan);
    $daysRemaining = $artisan ? $trialService->trialDaysRemaining($artisan) : 0;
    $isBlocked = $artisan?->is_blocked ?? false;

    // Route vers la page d'abonnement selon le type d'artiste
    $subscriptionRoute =
        $artisan instanceof \App\Models\Piercer
            ? route('pierceur.subscription.plans')
            : route('tattooer.subscription.plans');
@endphp

@if ($hasPaidSubscription)
    {{-- Abonnement actif payé — aucune bannière --}}
@elseif($isOnTrial && $daysRemaining === 0)
    {{-- Bannière fin d'essai : 0 jour --}}
    <div
        class="bg-gradient-to-r from-rouge-alerte/20 to-rouge-alerte/5 border border-rouge-alerte/30 rounded-xl p-4 mb-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-2xl">🔒</span>
                <div>
                    <p class="text-sm font-semibold text-ivoire-text">
                        Votre essai gratuit est terminé
                    </p>
                    <p class="text-xs text-titane mt-0.5">
                        Votre profil est masqué de la marketplace et vous n'avez plus accès aux fonctionnalités.
                        Choisissez un abonnement
                        pour continuer.
                    </p>
                </div>
            </div>
            <a href="{{ $subscriptionRoute }}"
                class="flex-shrink-0 px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                Activer mon abonnement
            </a>
        </div>
    </div>
@elseif ($isOnTrial && $daysRemaining <= 7 && $daysRemaining > 0)
    {{-- Bannière urgente : moins de 7 jours --}}
    <div
        class="bg-gradient-to-r from-rouge-alerte/20 to-rouge-alerte/5 border border-rouge-alerte/30 rounded-xl p-4 mb-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div>
                    <p class="text-sm font-semibold text-ivoire-text">
                        Plus que {{ $daysRemaining }} jour{{ $daysRemaining > 1 ? 's' : '' }} d'essai gratuit
                    </p>
                    <p class="text-xs text-titane mt-0.5">
                        Votre profil sera masqué de la marketplace après la fin de l'essai et vous n'aurez plus accès au
                        fonctionnalitées. Choisissez un abonnement
                        pour continuer.
                    </p>
                </div>
            </div>
            <a href="{{ $subscriptionRoute }}"
                class="flex-shrink-0 px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                Activer mon abonnement
            </a>
        </div>
    </div>
@elseif ($isOnTrial)
    {{-- Bannière info : trial actif --}}
    <div class="bg-beige-peau/5 border border-beige-peau/20 rounded-xl p-4 mb-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-lg">🎁</span>
                <p class="text-sm text-titane">
                    Essai gratuit — <strong class="text-ivoire-text">{{ $daysRemaining }}
                        jour{{ $daysRemaining > 1 ? 's' : '' }} restant{{ $daysRemaining > 1 ? 's' : '' }}</strong>
                </p>
            </div>
            <a href="{{ $subscriptionRoute }}" class="text-xs text-beige-peau hover:underline whitespace-nowrap">Activer
                mon abonnement</a>
        </div>
    </div>
@elseif ($isBlocked)
    {{-- Bannière compte bloqué --}}
    <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl p-6 mb-6 text-center">
        <span class="text-3xl">🔒</span>
        <h3 class="text-lg font-semibold text-ivoire-text mt-3">Votre essai gratuit est terminé</h3>
        <p class="text-sm text-titane mt-2 max-w-md mx-auto">
            Votre profil n'est plus visible dans la marketplace. Choisissez un abonnement pour réactiver votre compte et
            recevoir des demandes.
        </p>
        <a href="{{ $subscriptionRoute }}"
            class="inline-block mt-4 px-6 py-2.5 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
            Choisir mon abonnement
        </a>
    </div>
@endif

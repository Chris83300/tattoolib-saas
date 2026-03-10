@extends('layouts.studio')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-ivoire-text mb-6">Facturation & Abonnement</h1>

        {{-- Messages flash --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/30 rounded-xl text-sm text-green-400">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl text-sm text-rouge-alerte">
                {{ session('error') }}
            </div>
        @endif
        @if (session('warning'))
            <div class="mb-6 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl text-sm text-yellow-400">
                {{ session('warning') }}
            </div>
        @endif
        @if (session('info'))
            <div class="mb-6 p-4 bg-ambre-warning/10 border border-ambre-warning/30 rounded-xl text-sm text-ambre-warning">
                {{ session('info') }}
            </div>
        @endif

        {{-- ÉTAT 1 : Abonnement actif (non annulé) --}}
        @if ($isSubscribed && $subscriptionInfo && !($subscriptionInfo['canceled'] ?? false))
            <div class="bg-gris-fonde rounded-xl border border-green-500/30 p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="flex h-3 w-3 rounded-full bg-green-400"></span>
                    <h2 class="text-lg font-semibold text-ivoire-text">Abonnement actif</h2>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-titane">Plan</p>
                        <p class="text-ivoire-text font-medium">Studio —
                            {{ number_format($basePrice ?? \App\Enums\SubscriptionPlan::STUDIO->price(), 2, ',', '') }}€/mois
                        </p>
                        <p class="text-xs text-titane mt-1">
                            {{ (int) ($includedArtists ?? 1) }} artiste inclus
                            @if (($extraArtists ?? 0) > 0)
                                • {{ (int) $extraArtists }} artiste(s) supplémentaire(s)
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-titane">Statut</p>
                        <p class="text-vert-succes font-medium">
                            @if (($subscriptionInfo['stripe_status'] ?? '') === 'active')
                                Actif
                            @elseif ($subscriptionInfo['on_trial'] ?? false)
                                Essai gratuit
                            @else
                                {{ ucfirst($subscriptionInfo['stripe_status'] ?? 'Actif') }}
                            @endif
                        </p>
                    </div>
                    @if (
                        ($subscriptionInfo['on_trial'] ?? false) &&
                            ($subscriptionInfo['stripe_status'] ?? '') !== 'active' &&
                            $subscriptionInfo['trial_ends_at']
                    )
                        <div>
                            <p class="text-titane">Fin de l'essai</p>
                            <p class="text-ivoire-text">{{ $subscriptionInfo['trial_ends_at']->format('d/m/Y') }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-titane">Depuis le</p>
                        <p class="text-ivoire-text">{{ $subscriptionInfo['created_at']?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-titane">Artistes actifs</p>
                        <p class="text-ivoire-text">{{ (int) ($totalArtists ?? 0) }}</p>
                    </div>
                    <div>
                        <p class="text-titane">Total mensuel estimé</p>
                        <p class="text-ivoire-text font-medium">
                            {{ number_format($totalPrice ?? \App\Enums\SubscriptionPlan::STUDIO->price() + (int) ($extraArtists ?? 0) * \App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist(), 2, ',', '') }}€/mois
                        </p>
                        @if (($extraArtists ?? 0) > 0)
                            <p class="text-xs text-titane mt-1">
                                +{{ number_format($extraPrice ?? \App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist(), 2, ',', '') }}€
                                / artiste supp.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- DÉTAIL DE LA FACTURATION --}}
                <div class="mt-6 pt-6 border-t border-titane/10">
                    <h3 class="text-sm font-semibold text-ivoire-text mb-4">Détail de la facturation</h3>
                    <div class="space-y-2 text-sm">
                        @php
                            $studioPriceId = config('inkpik.pricing.studio.stripe_price_id');
                            $extraPriceId = config('inkpik.pricing.studio.stripe_price_id_extra');
                            $studioItem = collect($subscriptionItems ?? [])->firstWhere('stripe_price', $studioPriceId);
                            $extraItem = collect($subscriptionItems ?? [])->firstWhere('stripe_price', $extraPriceId);
                        @endphp

                        <div class="flex justify-between items-center">
                            <span class="text-titane">Ink&Pik Salon (STUDIO)</span>
                            <span class="text-ivoire-text">
                                {{ number_format($basePrice, 2, ',', '') }}€
                                <span class="text-xs text-titane">× {{ $studioItem['quantity'] ?? 1 }}</span>
                            </span>
                        </div>

                        @if (($extraArtists ?? 0) > 0)
                            <div class="flex justify-between items-center">
                                <span class="text-titane">Ink&Pik Artiste supp. (EXTRA)</span>
                                <span class="text-ivoire-text">
                                    {{ number_format($extraPrice, 2, ',', '') }}€
                                    <span class="text-xs text-titane">×
                                        {{ $extraItem['quantity'] ?? $extraArtists }}</span>
                                </span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center pt-2 border-t border-titane/10 font-semibold">
                            <span class="text-ivoire-text">Total mensuel</span>
                            <span class="text-ivoire-text">
                                {{ number_format($totalPrice, 2, ',', '') }}€
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 mt-6 pt-4 border-t border-titane/10">
                    @if ($portalUrl)
                        <a href="{{ $portalUrl }}" target="_blank"
                            class="px-4 py-2 text-sm bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:text-ivoire-text hover:border-beige-peau/30 transition-colors">
                            Gérer le paiement (Stripe)
                        </a>
                    @endif

                    <div x-data="{ showCancel: false }">
                        <button @click="showCancel = true"
                            class="px-4 py-2 text-sm text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors">
                            Annuler l'abonnement
                        </button>

                        <div x-show="showCancel" x-transition x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center bg-noir-profond/80 p-4">
                            <div class="bg-gris-fonde rounded-2xl border border-titane/20 p-6 max-w-md w-full"
                                @click.away="showCancel = false">
                                <h3 class="text-lg font-semibold text-ivoire-text mb-3">Annuler votre abonnement ?</h3>

                                <div class="space-y-4">
                                    <form method="POST" action="{{ route('studio.subscription.cancel') }}">
                                        @csrf
                                        <div class="p-4 bg-noir-profond/30 rounded-lg">
                                            <p class="text-sm text-ivoire-text font-medium">Annuler à la fin de la période
                                            </p>
                                            <p class="text-xs text-titane mt-1">
                                                Vous conservez l'accès jusqu'à la fin de votre période payée.
                                                Aucun prélèvement supplémentaire.
                                            </p>
                                            <button type="submit"
                                                class="mt-3 px-4 py-2 text-sm text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors">
                                                Annuler à la fin de la période
                                            </button>
                                        </div>
                                    </form>

                                    <form method="POST" action="{{ route('studio.subscription.cancel') }}">
                                        @csrf
                                        <input type="hidden" name="immediate" value="1">
                                        <div class="p-4 bg-rouge-alerte/5 border border-rouge-alerte/20 rounded-lg">
                                            <p class="text-sm text-rouge-alerte font-medium">Annuler immédiatement</p>
                                            <p class="text-xs text-titane mt-1">
                                                L'abonnement est arrêté tout de suite. Vos artistes seront masqués de la
                                                marketplace.
                                                Pas de remboursement au prorata.
                                            </p>
                                            <button type="submit"
                                                class="mt-3 px-4 py-2 text-sm text-white bg-rouge-alerte rounded-lg hover:bg-rouge-alerte/80 transition-colors"
                                                onclick="return confirm('Êtes-vous sûr ? Cette action est irréversible.')">
                                                Annuler maintenant
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <button @click="showCancel = false"
                                    class="w-full mt-4 px-4 py-2 text-sm text-titane hover:text-ivoire-text transition-colors">
                                    Garder mon abonnement
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ÉTAT 2 : Abonnement annulé mais en grace period --}}
        @elseif ($subscriptionInfo && ($subscriptionInfo['on_grace_period'] ?? false))
            <div class="bg-gris-fonde rounded-xl border border-yellow-500/30 p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xl">⏳</span>
                    <h2 class="text-lg font-semibold text-ivoire-text">Abonnement annulé</h2>
                </div>
                <p class="text-sm text-titane mb-2">
                    Votre abonnement est annulé mais vous conservez l'accès jusqu'au
                    <strong class="text-ivoire-text">{{ $subscriptionInfo['ends_at']?->format('d/m/Y') ?? '—' }}</strong>.
                </p>
                <p class="text-xs text-titane mb-4">Après cette date, votre studio et vos artistes seront masqués de la
                    marketplace.</p>

                <form method="POST" action="{{ route('studio.subscription.resume') }}">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                        Réactiver l'abonnement
                    </button>
                </form>
            </div>

            {{-- ÉTAT 3 : Pas d'abonnement --}}
        @else
            {{-- Trial local actif --}}
            @if ($studio->onTrial())
                <div class="mb-4 p-4 bg-beige-peau/10 border border-beige-peau/30 rounded-xl text-sm text-beige-peau">
                    Période d'essai en cours — {{ $studio->trialDaysLeft() }} jour(s) restant(s).
                    Souscrivez pour ne pas perdre l'accès.
                </div>
            @endif

            <div class="bg-gris-fonde rounded-xl border border-titane/10 p-6 mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-xl">💳</span>
                    <h2 class="text-lg font-semibold text-ivoire-text">Aucun abonnement actif</h2>
                </div>
                <p class="text-sm text-titane mb-4">
                    Choisissez le plan Studio pour gérer vos artistes, accéder au planning global et aux statistiques.
                </p>

                <div class="p-5 bg-noir-profond/30 rounded-xl border border-beige-peau/20 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-lg font-bold text-beige-peau">Plan Studio</h3>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-beige-peau">
                                {{ number_format(\App\Enums\SubscriptionPlan::STUDIO->price(), 2, ',', '') }}€<span
                                    class="text-sm text-titane font-normal">/mois</span></p>
                            <p class="text-xs text-beige-peau">+
                                {{ number_format(\App\Enums\SubscriptionPlan::STUDIO->pricePerExtraArtist(), 2, ',', '') }}€<span
                                    class="text-sm text-titane font-normal"> par artiste supplémentaire</span></p>
                        </div>
                    </div>
                    <ul class="space-y-1.5 text-sm text-titane mb-4">
                        @foreach (\App\Enums\SubscriptionPlan::STUDIO->features() as $feature)
                            <li class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-vert-succes flex-shrink-0" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <form method="POST" action="{{ route('studio.subscribe.post') }}">
                        @csrf
                        <button type="submit"
                            class="w-full px-6 py-3 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                            Souscrire à l'abonnement
                        </button>
                    </form>
                    <p class="text-xs text-titane text-center mt-2">Sans engagement. Annulable à tout moment.</p>
                </div>
            </div>
        @endif
    </div>
@endsection

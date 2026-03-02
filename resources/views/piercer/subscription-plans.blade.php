@extends('layouts.tattooer')

@section('title', 'Gérer mon abonnement - Ink&Pik')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">

        <h1 class="text-2xl font-bold text-ivoire-text">Gérer mon abonnement</h1>

        {{-- Messages flash --}}
        @if (session('success'))
            <div class="bg-vert-succes/20 border border-vert-succes/30 text-vert-succes rounded-xl p-4">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl p-4">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="bg-beige-peau/20 border border-beige-peau/30 text-beige-peau rounded-xl p-4">
                {{ session('info') }}
            </div>
        @endif

        {{-- Status actuel --}}
        <div class="bg-gris-fonde rounded-xl p-6 border border-titane/20">
            <h2 class="text-lg font-bold text-ivoire-text mb-2">Mon plan actuel</h2>
            @if ($artist->isPro())
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-beige-peau text-noir-profond rounded-full text-sm font-bold">PRO</span>
                    <span class="text-ivoire-text/70">49.99€/mois · Commission 0%</span>
                </div>

                @if ($activeSubscription?->isOnGracePeriod())
                    <div class="mt-3 bg-ambre-warning/10 border border-ambre-warning/30 rounded-lg p-3">
                        <p class="text-sm text-ambre-warning">
                            ⚠️ Abonnement annulé. Accès PRO jusqu'au
                            <strong>{{ $activeSubscription->ends_at->translatedFormat('d F Y') }}</strong>
                        </p>
                        <form action="{{ route('pierceur.subscription.resume') }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 bg-vert-succes text-white rounded-lg text-sm font-semibold hover:bg-vert-succes/90">
                                Réactiver mon abonnement
                            </button>
                        </form>
                    </div>
                @else
                    <div class="mt-3 flex flex-wrap gap-3">
                        <a href="{{ route('pierceur.subscription.manage') }}"
                            class="px-4 py-2 bg-titane/20 text-ivoire-text rounded-lg text-sm font-semibold hover:bg-titane/30 transition-colors">
                            💳 Gérer le paiement
                        </a>
                        <form action="{{ route('pierceur.subscription.cancel') }}" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ? Vous gardez l\'accès PRO jusqu\'à la fin de la période.')">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 border border-rouge-alerte/30 text-rouge-alerte rounded-lg text-sm hover:bg-rouge-alerte/10 transition-colors">
                                Annuler l'abonnement
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="flex items-center gap-3">
                    <span class="px-3 py-1 bg-titane/30 text-titane rounded-full text-sm font-bold">FREE</span>
                    <span class="text-ivoire-text/70">Gratuit · Commission 7%</span>
                </div>
            @endif
        </div>

        {{-- Comparaison plans --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Plan FREE --}}
            <div
                class="bg-gris-fonde rounded-xl p-6 border {{ $artist->isFree() ? 'border-titane/40' : 'border-titane/20' }}">
                <h3 class="text-xl font-bold text-ivoire-text mb-1">Free</h3>
                <p class="text-3xl font-bold text-ivoire-text mb-4">0€<span
                        class="text-sm font-normal text-titane">/mois</span></p>

                <ul class="space-y-2 text-sm text-ivoire-text/80 mb-6">
                    <li class="flex items-center gap-2">✅ Profil marketplace</li>
                    <li class="flex items-center gap-2">✅ Réception de demandes</li>
                    <li class="flex items-center gap-2">✅ Chat client</li>
                    <li class="flex items-center gap-2">✅ Calendrier</li>
                    <li class="flex items-center gap-2">✅ Paiements sécurisés</li>
                    <li class="flex items-center gap-2 text-rouge-alerte/80">❌ Commission 7% par transaction</li>
                    <li class="flex items-center gap-2 text-ivoire-text/40">❌ Fiche client avancée</li>
                    <li class="flex items-center gap-2 text-ivoire-text/40">❌ Analytics</li>
                </ul>

                @if ($artist->isFree())
                    <div class="px-4 py-2.5 bg-titane/20 text-titane rounded-lg text-center text-sm font-semibold">
                        Plan actuel
                    </div>
                @endif
            </div>

            {{-- Plan PRO --}}
            <div class="bg-gris-fonde rounded-xl p-6 border-2 border-beige-peau relative">
                <div
                    class="absolute -top-3 left-4 px-3 py-0.5 bg-beige-peau text-noir-profond text-xs font-bold rounded-full">
                    RECOMMANDÉ
                </div>

                <h3 class="text-xl font-bold text-ivoire-text mb-1">PRO</h3>
                <p class="text-3xl font-bold text-beige-peau mb-4">49.99€<span
                        class="text-sm font-normal text-titane">/mois</span></p>

                <ul class="space-y-2 text-sm text-ivoire-text/80 mb-6">
                    <li class="flex items-center gap-2">✅ Tout le plan Free</li>
                    <li class="flex items-center gap-2 text-vert-succes font-semibold">✅ Commission 0%</li>
                    <li class="flex items-center gap-2 text-vert-succes">✅ Fiche client (automatique) + manuelle</li>
                    <li class="flex items-center gap-2 text-vert-succes">✅ Traçabilité lier à la fiche client (automatique) + manuelle</li>
                    <li class="flex items-center gap-2 text-vert-succes">✅ Analytics & statistiques</li>
                    <li class="flex items-center gap-2 text-vert-succes">✅ Support prioritaire</li>
                    <li class="flex items-center gap-2 text-vert-succes">✅ Portfolio ilimité</li>
                    <li class="flex items-center gap-2 text-vert-succes">✅ Conservation des données + export PDF des fiche client complètes</li>
                    <li class="flex items-center gap-2 text-vert-succes">✅ Export CSV/Excel pour la comptabilité</li>
                </ul>

                @if ($artist->isPro() && !$activeSubscription?->isOnGracePeriod())
                    <div class="px-4 py-2.5 bg-beige-peau/20 text-beige-peau rounded-lg text-center text-sm font-semibold">
                        ✅ Plan actuel
                    </div>
                @elseif (!$artist->isPro())
                    <form action="{{ route('pierceur.subscription.subscribe') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-lg font-bold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                            🚀 Passer PRO maintenant
                        </button>
                    </form>
                    <p class="text-xs text-titane text-center mt-2">Annulable à tout moment · Paiement sécurisé Stripe</p>
                @endif
            </div>
        </div>

        {{-- ROI Calculator --}}
        @if ($artist->isFree())
            <div class="bg-gris-fonde rounded-xl p-6 border border-titane/20">
                <h3 class="text-lg font-bold text-beige-peau mb-3">"Les piercers PRO économisent en moyenne 150€/mois en
                    commission sur leurs réservations."</h3>

            </div>
        @endif

    </div>
@endsection

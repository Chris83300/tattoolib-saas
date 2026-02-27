@extends('layouts.studio')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-bold text-ivoire-text">Facturation</h1>

    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-4">Récapitulatif mensuel</h2>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-ivoire-text">Abonnement Studio</span>
                <span class="text-sm font-semibold text-ivoire-text">79,99€/mois</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-sm text-ivoire-text">Artistes inclus</span>
                <span class="text-sm text-titane">1</span>
            </div>
            @if ($paidArtistCount > 0)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-ivoire-text">Artistes supplémentaires ({{ $paidArtistCount }})</span>
                    <span class="text-sm font-semibold text-beige-peau">{{ number_format($paidArtistCount * 39.99, 2) }}€/mois</span>
                </div>
            @endif
            <div class="border-t border-titane/20 pt-3 flex justify-between items-center">
                <span class="text-sm font-bold text-ivoire-text">Total mensuel</span>
                <span class="text-lg font-bold text-beige-peau">{{ number_format($monthlyPrice, 2) }}€</span>
            </div>
        </div>
    </div>

    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📊 Artistes facturés</h2>
        <div class="space-y-2 text-sm text-titane">
            <div class="flex justify-between">
                <span>Artiste(s) actif(s)</span>
                <span class="text-ivoire-text font-semibold">{{ $artistCount }}</span>
            </div>
            <div class="flex justify-between">
                <span>Inclus dans l'abonnement</span>
                <span class="text-ivoire-text font-semibold">1</span>
            </div>
            <div class="flex justify-between">
                <span>Supplémentaires facturés</span>
                <span class="{{ $paidArtistCount > 0 ? 'text-beige-peau' : 'text-ivoire-text' }} font-semibold">{{ $paidArtistCount }}</span>
            </div>
        </div>
    </div>

    {{-- Stripe Customer Portal --}}
    @if ($isSubscribed && $portalUrl)
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ $portalUrl }}" target="_blank"
                class="w-full sm:w-auto px-6 py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95 text-center inline-block">
                Gérer mon abonnement (Stripe)
            </a>
        </div>
    @elseif (!$isSubscribed && $studio->trialExpired())
        <a href="{{ route('studio.subscribe') }}"
            class="w-full py-3.5 bg-beige-peau text-noir-profond rounded-xl font-bold text-center block hover:bg-beige-peau/90 transition-colors active:scale-95">
            Activer l'abonnement — {{ number_format($monthlyPrice, 2, ',', ' ') }}€/mois
        </a>
    @elseif (!$isSubscribed && $studio->onTrial())
        <a href="{{ route('studio.subscribe') }}"
            class="w-full py-3 bg-gris-fonde text-ivoire-text rounded-xl font-semibold text-center block border border-beige-peau/30 hover:bg-beige-peau/10 transition-colors">
            Activer maintenant ({{ $studio->trialDaysLeft() }} jours restants)
        </a>
    @elseif (!$isSubscribed)
        <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-4">
            <p class="text-sm text-orange-400 font-semibold">Aucun abonnement actif</p>
            <p class="text-xs text-titane mt-1">Pour activer votre studio, veuillez souscrire à l'abonnement.</p>
        </div>
    @endif
</div>
@endsection

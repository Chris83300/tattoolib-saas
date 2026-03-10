@extends('layouts.studio')

@section('title', 'Activer votre studio')

@section('content')
    <div class="max-w-lg mx-auto space-y-6 py-6">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-ivoire-text">Activez votre studio</h1>
            <p class="text-sm text-titane mt-2">Continuez à utiliser toutes les fonctionnalités sans interruption.</p>
        </div>

        <div class="bg-gris-fonde rounded-2xl p-6 space-y-4 border border-titane/20">
            <h2 class="text-lg font-bold text-ivoire-text text-center">Studio <span class="text-titane">Ink</span><span
                    class="text-beige-peau">&Pik</span></h2>

            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-ivoire-text">Abonnement Studio</span>
                    <span class="text-sm font-semibold text-ivoire-text">59,99€/mois</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-titane">1 artiste inclus</span>
                    <span class="text-sm text-vert-succes">✓ Inclus</span>
                </div>
                @if ($paidArtistCount > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-ivoire-text">
                            {{ $paidArtistCount }} artiste{{ $paidArtistCount > 1 ? 's' : '' }}
                            supplémentaire{{ $paidArtistCount > 1 ? 's' : '' }}
                        </span>
                        <span class="text-sm font-semibold text-beige-peau">
                            {{ number_format($paidArtistCount * 24.99, 2, ',', ' ') }}€/mois
                        </span>
                    </div>
                @endif
                <div class="border-t border-titane/20 pt-3 flex justify-between items-center">
                    <span class="font-bold text-ivoire-text">Total</span>
                    <span class="text-xl font-bold text-beige-peau">
                        {{ number_format($monthlyPrice, 2, ',', ' ') }}€<span
                            class="text-sm text-titane font-normal">/mois</span>
                    </span>
                </div>
            </div>

            <div class="space-y-2 pt-2">
                <p class="text-xs text-titane font-semibold"><span class="text-sm text-vert-succes">✓</span> Tout le plan
                    Pro</p>
                <p class="text-xs text-titane"><span class="text-sm text-vert-succes">✓</span> Dashboard complet et gestion
                    avancée</p>
                <p class="text-xs text-titane"><span class="text-sm text-vert-succes">✓</span> Traçabilité et fiches clients
                </p>
                <p class="text-xs text-titane"><span class="text-sm text-vert-succes">✓</span> Visibilité marketplace</p>
                <p class="text-xs text-titane"><span class="text-sm text-vert-succes">✓</span> Stripe Connect intégré</p>
                <p class="text-xs text-titane"><span class="text-sm text-vert-succes">✓</span> Sans engagement, résiliable à
                    tout moment</p>
            </div>

            <form action="{{ route('studio.subscribe.post') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full py-3.5 bg-beige-peau text-noir-profond rounded-xl font-bold text-base hover:bg-beige-peau/90 transition-colors active:scale-95">
                    Activer l'abonnement
                </button>
            </form>

            <p class="text-xs text-titane text-center">Paiement sécurisé par Stripe. Facture mensuelle automatique.</p>
        </div>

        <div class="text-center">
            <a href="{{ route('studio.billing') }}" class="text-xs text-titane hover:text-ivoire-text">← Retour à la
                facturation</a>
        </div>
    </div>
@endsection

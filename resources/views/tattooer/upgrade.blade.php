@extends('layouts.tattooer')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    
    <!-- Hero -->
    <div class="text-center">
        <div class="inline-block px-4 py-2 bg-beige-peau/20 text-beige-peau rounded-full font-semibold mb-4">
            ⭐ Passez au niveau supérieur
        </div>
        <h1 class="text-4xl md:text-5xl font-bold text-ivoire-text mb-4">
            Devenez PRO
        </h1>
        <p class="text-xl text-ivoire-text/70 max-w-2xl mx-auto">
            Maximisez votre potentiel avec toutes les fonctionnalités avancées et 0% de commission
        </p>
    </div>
    
    <!-- Comparaison Plans -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Plan FREE (actuel) -->
        <div class="bg-gris-fonde rounded-2xl p-8 relative">
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-ivoire-text mb-2">Gratuit</h3>
                <div class="text-4xl font-bold text-ivoire-text mb-4">0€<span class="text-lg text-ivoire-text/60">/mois</span></div>
                <div class="inline-block px-3 py-1 bg-ivoire-text/20 text-ivoire-text rounded-full text-sm font-semibold">
                    Plan actuel
                </div>
            </div>
            
            <ul class="space-y-3">
                <li class="flex items-start gap-2 text-ivoire-text/70">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Profil public marketplace</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text/70">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Demandes de projet</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text/70">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Messagerie clients</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text/70">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Calendrier basique</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text/40 line-through">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>7% de commission sur réservations</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text/40 line-through">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Stockage limité (30j chat)</span>
                </li>
            </ul>
        </div>
        
        <!-- Plan PRO (recommandé) -->
        <div class="bg-gradient-to-br from-beige-peau/20 to-beige-peau/5 border-2 border-beige-peau rounded-2xl p-8 relative">
            <div class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-beige-peau text-noir-profond rounded-full text-sm font-bold">
                ⭐ RECOMMANDÉ
            </div>
            
            <div class="text-center mb-6">
                <h3 class="text-2xl font-bold text-ivoire-text mb-2">PRO</h3>
                <div class="text-4xl font-bold text-beige-peau mb-4">
                    49,99€
                    <span class="text-lg text-ivoire-text/60">/mois</span>
                </div>
                <p class="text-sm text-ivoire-text/60">Sans engagement</p>
            </div>
            
            <ul class="space-y-3 mb-8">
                <li class="flex items-start gap-2 text-ivoire-text">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>0% de commission</strong> sur vos réservations</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>Stockage illimité</strong> photos & messages</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>Badge PRO</strong> sur profil public</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>Priorité</strong> dans les résultats de recherche</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>Analytics avancées</strong> & statistiques détaillées</span>
                </li>
                <li class="flex items-start gap-2 text-ivoire-text">
                    <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span><strong>Support prioritaire</strong> 7j/7</span>
                </li>
            </ul>
            
            <form action="{{ route($tattooer->routePrefix() . '.upgrade.process') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full px-6 py-4 bg-beige-peau text-noir-profond rounded-xl font-bold text-lg hover:bg-beige-peau/90 transition-all transform hover:scale-105">
                    🚀 Passer PRO maintenant
                </button>
            </form>
            
            <p class="text-center text-ivoire-text/60 text-sm mt-4">
                Paiement sécurisé par Stripe • Annulez à tout moment
            </p>
        </div>
    </div>
    
    <!-- FAQ -->
    <div class="bg-gris-fonde rounded-xl p-8">
        <h3 class="text-2xl font-bold text-ivoire-text mb-6">Questions fréquentes</h3>
        
        <div class="space-y-4">
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer p-4 bg-noir-profond rounded-lg hover:bg-noir-profond/80">
                    <span class="font-semibold text-ivoire-text">Puis-je annuler mon abonnement à tout moment ?</span>
                    <svg class="w-5 h-5 text-ivoire-text/60 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <div class="p-4 text-ivoire-text/70">
                    Oui, vous pouvez annuler à tout moment depuis vos paramètres. L'abonnement restera actif jusqu'à la fin de la période payée.
                </div>
            </details>
            
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer p-4 bg-noir-profond rounded-lg hover:bg-noir-profond/80">
                    <span class="font-semibold text-ivoire-text">Que se passe-t-il si je repasse en FREE ?</span>
                    <svg class="w-5 h-5 text-ivoire-text/60 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <div class="p-4 text-ivoire-text/70">
                    Vos données sont conservées, mais les fonctionnalités PRO seront désactivées. Les photos stockées resteront accessibles pendant 30 jours.
                </div>
            </details>
            
            <details class="group">
                <summary class="flex items-center justify-between cursor-pointer p-4 bg-noir-profond rounded-lg hover:bg-noir-profond/80">
                    <span class="font-semibold text-ivoire-text">Y a-t-il vraiment 0% de commission PRO ?</span>
                    <svg class="w-5 h-5 text-ivoire-text/60 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </summary>
                <div class="p-4 text-ivoire-text/70">
                    Absolument ! Les membres PRO ne paient aucune commission sur les réservations. Vous gardez 100% de vos revenus (hors frais Stripe standards ~2%).
                </div>
            </details>
        </div>
    </div>
    
</div>
@endsection

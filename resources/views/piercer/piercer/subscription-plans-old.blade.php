@extends('layouts.tattooer')

@section('title', 'Gérer mon abonnement - Ink&Pik')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">

        <!-- Header -->
        <div class="text-center">
            <div class="inline-block px-4 py-2 bg-beige-peau/20 text-beige-peau rounded-full font-semibold mb-4">
                ⭐ Choisissez votre abonnement
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-ivoire-text mb-4">
                Devenez PRO
            </h1>
            <p class="text-xl text-ivoire-text/70 max-w-2xl mx-auto">
                Maximisez votre potentiel avec toutes les fonctionnalités avancées
            </p>
        </div>
        @endif
        @if (session('error'))
            <div class="bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl p-4">
                {{ session('error') }}
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 text-ivoire-text/70">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Profil public marketplace</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text/70">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Demandes de projet</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text/70">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Messagerie clients</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text/70">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Calendrier basique</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text/40 line-through">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        <span>7% de commission sur réservations</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text/40 line-through">
                        <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                        <span>Stockage limité (30j chat)</span>
                    </li>
                </ul>
            </div>

            <!-- Plan PRO -->
            <div
                class="bg-gradient-to-br from-beige-peau/20 to-beige-peau/5 border-2 border-beige-peau rounded-2xl p-8 relative">
                <div
                    class="absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 bg-beige-peau text-noir-profond rounded-full text-sm font-bold">
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
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>0% de commission</strong> sur vos réservations</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>Stockage illimité</strong> photos & messages</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>Badge PRO</strong> sur profil public</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>Priorité</strong> dans les résultats de recherche</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>Analytics avancées</strong> & statistiques détaillées</span>
                    </li>
                    <li class="flex items-start gap-2 text-ivoire-text">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span><strong>Support prioritaire</strong> 7j/7</span>
                    </li>
                </ul>

                <!-- Bouton d'action -->
                <form action="{{ route('tattooer.subscription.subscribe') }}" method="POST">
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

    <!-- Bouton retour -->
    <div class="text-center mt-8">
        <a href="{{ route('tattooer.profile') }}"
            class="px-6 py-3 bg-gris-fonde hover:bg-titane/20 text-ivoire-text font-semibold rounded-lg transition-colors border border-titane/30">
            ← Revenir à mon profil
        </a>
    </div>
    </div>
@endsection

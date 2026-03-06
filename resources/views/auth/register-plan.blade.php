@extends('layouts.guest')

@section('content')
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
        <div class="max-w-4xl w-full">
            <!-- Logo + Titre -->
            <div class="text-center mb-10">
                <a href="/" class="text-beige-peau font-Satoshi text-3xl font-bold">
                    <span class="text-titane">Ink </span>& Pik
                </a>
                <h1 class="text-ivoire-text text-2xl font-display font-bold mt-6 mb-2">
                    Choisissez votre formule
                </h1>
                <p class="text-beige-peau">
                    @if (request()->get('type') === 'piercer')
                        En tant que Piercer / Bodemodeur
                    @else
                        En tant que Tatoueur
                    @endif
                </p>
            </div>

            <!-- Plans disponibles -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

                <!-- STARTER -->
                <div
                    class="bg-gris-fonde border-2 border-titane/20 rounded-xl p-6 hover:border-beige-peau/50 transition-all group">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 mx-auto mb-4 bg-vert-succes/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-beige-peau font-display font-bold text-2xl mb-2">STARTER</h3>
                        <p class="text-ivoire-text/70 text-sm">Idéal pour commencer</p>
                    </div>

                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-beige-peau mb-2">9,99€<span
                                class="text-lg font-normal text-ivoire-text/70">/mois</span></div>
                        <p class="mb-2">14 jours d'essai gratuit <span class="text-sm text-ambre-warning">sans CB</span>
                        </p>
                        <div class="text-ivoire-text/60 text-sm">7% de commission sur chaque transaction</div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Profil marketplace</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Portfolio limiter à 15 images</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Réception de demandes</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Chat client</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Calendrier</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Paiements sécurisés</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-titane flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Commission 7%</span>
                        </div>

                    </div>

                    <div class="text-center">
                        <a href="{{ request()->get('type') === 'piercer' ? route('register.pierceur', ['plan' => 'starter']) : route('register.tattooer', ['plan' => 'starter']) }}"
                            class=" text-lg inline-block w-full px-6 py-3 bg-vert-succes text-noir-profond font-semibold rounded-lg hover:bg-vert-succes/90 transition-colors">
                            Commencer avec Starter
                        </a>
                    </div>
                </div>

                <!-- PRO -->
                <div class="bg-gris-fonde border-2 border-beige-peau rounded-xl p-6 relative overflow-hidden group">
                    <!-- Badge populaire -->
                    <div
                        class="absolute top-4 right-4 bg-ambre-warning text-noir-profond text-xs font-bold px-3 py-1 rounded-full">
                        LE PLUS CHOISI
                    </div>

                    <div class="text-center mb-6">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-ambre-warning/20 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-ambre-warning" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-beige-peau font-display font-bold text-2xl mb-2">PRO</h3>
                        <p class="text-ivoire-text/70 text-sm">Pour les professionnels établis</p>
                    </div>

                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-beige-peau mb-2">29,99€<span
                                class="text-lg font-normal text-ivoire-text/70">/mois</span></div>
                        <p class="mb-2">14 jours d'essai gratuit <span class="text-sm text-ambre-warning">sans CB</span>
                        </p>
                        <div class="bg-ivoire-text/20 rounded-lg p-3 border border-beige-peau/40 mb-4 text-center">
                            <div class="font-bold">0% de commission</div>
                            <div class="noir-profond/60 text-sm">sur toutes les transactions</div>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Tout ce qui est inclus dans Starter</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Mise en avant sur la marketplace</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Portfolio illimité</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            <span class="text-ivoire-text text-md">Traçabilité complète</span>
                        </div>
                        <div class="space-y-3 mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                <span class="text-ivoire-text text-md">Fiche client avancée</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                <span class="text-ivoire-text text-md">Export PDF fiches clients</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                <span class="text-ivoire-text text-md">Export CSV/Excel comptabilité</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-vert-succes flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7">
                                    </path>
                                </svg>
                                <span class="text-ivoire-text text-md">Support prioritaire</span>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="{{ request()->get('type') === 'piercer' ? route('register.pierceur', ['plan' => 'pro']) : route('register.tattooer', ['plan' => 'pro']) }}"
                                class="text-lg inline-block w-full px-6 py-3 bg-ambre-warning text-noir-profond font-semibold rounded-lg hover:bg-ambre-warning/90 transition-colors">
                                Commencer avec Pro
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Retour -->
                <div class="text-center">
                    <a href="{{ route('register') }}"
                        class="text-ivoire-text/70 hover:text-beige-peau transition-colors text-sm">
                        ← Retour au choix du rôle
                    </a>
                </div>
            </div>
        </div>
    @endsection

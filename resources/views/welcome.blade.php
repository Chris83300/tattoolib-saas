@extends('layouts.app')

@section('content')
    <!-- Hero Section -->
    <section class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
        <div class="text-center max-w-2xl">
            <img src="{{ asset('images/logo.png') }}" alt="Ink&Pik"
            <!-- Titre principal -->
            <h1 class="text-titre md:text-10xl font-Satoshi font-bold text-titane mb-10">
                Ink <span class="text-beige-peau">& Pik</span>
            </h1>
            <h2 class="text-4xl md:text-6xl font-Satoshi font-bold text-ivoire-text mb-4">
                Notre art, votre peau.
            </h2>

            <!-- Sous-titre -->
            <p class="text-lg md:text-xl text-ivoire-text/70 mb-8">
                La plateforme professionnelle qui connecte clients
                et artistes des arts corporels.
            </p>

            <!-- 2 CTA distincts -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <!-- CTA Client -->
                <x-ui.button variant="primary" size="lg" href="/marketplace">
                    Trouver un artiste
                </x-ui.button>

                <!-- CTA Pro -->
                <x-ui.button variant="secondary" size="lg" href="/professionnels">
                    Je suis un pro
                </x-ui.button>
            </div>
        </div>
    </section>

    <!-- Section Trust (pictogrammes) -->
    <section class="bg-gris-fonde py-16 px-4">
        <div class="container-custom">
            <h2 class="text-3xl md:text-4xl font-display font-bold text-center text-ivoire-text mb-12">
                Une plateforme de confiance
            </h2>

            <!-- Grid 3 colonnes -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <!-- Item 1 : Artistes vérifiés -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                        Artistes vérifiés
                    </h3>
                    <p class="text-ivoire-text/70 text-sm">
                        Conformité réglementaire garantie (SIRET, ARS, Hygiène)
                    </p>
                </div>

                <!-- Item 2 : Paiements sécurisés -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                        Paiements sécurisés
                    </h3>
                    <p class="text-ivoire-text/70 text-sm">
                        Acomptes gérés via Stripe (3D Secure)
                    </p>
                </div>

                <!-- Item 3 : Conformité -->
                <div class="text-center">
                    <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                        Conformité réglementaire
                    </h3>
                    <p class="text-ivoire-text/70 text-sm">
                        Traçabilité complète (ARS, hygiène)
                    </p>
                </div>

            </div>
        </div>
    </section>

    <!-- Aperçu Marketplace (cartes artistes) -->
    <section id="marketplace" class="bg-noir-profond py-16 px-4">
        <div class="container-custom">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-display font-bold text-ivoire-text mb-4">
                    Découvrez nos artistes
                </h2>
                <p class="text-ivoire-text/70">
                    Des professionnels vérifiés près de chez vous
                </p>
            </div>

            <!-- Grid cartes artistes -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">

                @for ($i = 0; $i < 6; $i++)
                    <div class="bg-gris-fonde rounded-lg overflow-hidden hover:ring-2 hover:ring-beige-peau transition-all">
                        <!-- Image portfolio -->
                        <div class="aspect-square bg-gray-800 relative">
                            <img src="https://picsum.photos/seed/tattoo{{ $i }}/400/400.jpg" alt="Portfolio"
                                class="w-full h-full object-cover">

                            <!-- Badge conformité -->
                            <div class="absolute top-2 right-2">
                                <x-ui.badge variant="conformite" size="sm">
                                    ✓ Conforme
                                </x-ui.badge>
                            </div>
                        </div>

                        <!-- Infos -->
                        <div class="p-4">
                            <h3 class="text-ivoire-text font-semibold text-lg mb-1">
                                Artiste {{ $i + 1 }}
                            </h3>
                            <p class="text-ivoire-text/70 text-sm mb-2 flex items-center gap-1">
                                <svg class="w-4 h-4 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Paris 11ème • 2.5km
                            </p>

                            <!-- Styles -->
                            <div class="flex flex-wrap gap-1 mb-3">
                                <x-ui.badge size="sm">Japonais</x-ui.badge>
                                <x-ui.badge size="sm">Réalisme</x-ui.badge>
                            </div>

                            <!-- Prix indicatif -->
                            <p class="text-ivoire-text/50 text-xs mb-3">
                                À partir de 150€
                            </p>

                            <!-- CTA -->
                            <x-ui.button variant="primary" size="sm" fullWidth>
                                Voir le profil
                            </x-ui.button>
                        </div>
                    </div>
                @endfor

            </div>

            <!-- CTA Voir plus -->
            <div class="text-center">
                <x-ui.button variant="secondary" href="/marketplace">
                    Voir tous les artistes
                </x-ui.button>
            </div>
        </div>
    </section>

    <!-- Section CTA Pro (Teaser uniquement) -->
    <section id="pour-les-pros" class="bg-gris-fonde py-16 px-4">
        <div class="container-custom max-w-3xl text-center">

            <!-- Titre teaser -->
            <h2 class="text-3xl md:text-4xl font-display font-bold text-ivoire-text mb-4">
                Vous êtes tatoueur, pierceur ou gérant de studio ?
            </h2>

            <p class="text-lg text-ivoire-text/70 mb-8">
                Développez votre activité avec un outil professionnel tout-en-un
            </p>

            <!-- Liste bénéfices courte -->
            <div class="grid grid-cols-2 gap-4 max-w-xl mx-auto mb-10">

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Planning intelligent</span>
                </div>

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Paiements sécurisés</span>
                </div>

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Conformité ARS</span>
                </div>

                <div class="flex items-center gap-2 text-ivoire-text/80 text-sm">
                    <svg class="w-5 h-5 text-beige-peau flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Visibilité marketplace</span>
                </div>

            </div>

            <!-- CTA unique vers page dédiée -->
            <x-ui.button variant="primary" size="lg" href="/pour-les-professionnels">
                Découvrir l'offre professionnelle
            </x-ui.button>

        </div>
    </section>
@endsection

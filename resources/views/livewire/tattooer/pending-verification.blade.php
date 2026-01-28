<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
    <div class="max-w-2xl w-full">

        <!-- Card -->
        <div class="bg-gris-fonde rounded-xl p-8 text-center">

            <!-- Icon -->
            <div class="w-20 h-20 bg-ambre-warning/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-ambre-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <!-- Titre -->
            <h1 class="text-ivoire-text font-display font-bold text-2xl mb-4">
                Votre compte est en cours de vérification
            </h1>

            <!-- Message -->
            <p class="text-ivoire-text/70 mb-6 leading-relaxed">
                Merci de votre inscription ! Votre profil sera examiné par notre équipe dans les <strong
                    class="text-beige-peau">24-48 heures</strong>.
            </p>

            <!-- Message de succès si présent -->
            @if (session('success'))
                <div class="bg-vert-succes/20 border border-vert-succes text-vert-succes px-4 py-3 rounded-lg mb-6">
                    <p class="font-semibold">{{ session('success') }}</p>
                    <p class="text-sm mt-2">
                        <a href="{{ route('tattooer.profile') }}" class="underline hover:text-beige-peau">
                            Accéder à mon profil tatoueur →
                        </a>
                    </p>
                </div>
            @endif

            <!-- Infos reçues -->
            <div class="bg-noir-profond rounded-lg p-6 mb-6 text-left">
                <h3 class="text-ivoire-text font-semibold mb-3">Informations reçues :</h3>
                <ul class="space-y-2 text-sm text-ivoire-text/70">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><strong class="text-ivoire-text">SIRET :</strong>
                            {{ $tattooer?->siret ?? 'Non renseigné' }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><strong class="text-ivoire-text">Nom :</strong>
                            {{ $tattooer?->name ?? auth()->user()->name }}</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-vert-succes flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span><strong class="text-ivoire-text">Ville :</strong>
                            {{ $tattooer?->city ?? 'Non renseignée' }}</span>
                    </li>
                </ul>
            </div>

            <!-- Prochaines étapes -->
            <div class="bg-beige-peau/5 border border-beige-peau/30 rounded-lg p-6 mb-6">
                <h3 class="text-ivoire-text font-semibold mb-3 text-left">En attendant, vous pouvez :</h3>
                <ul class="space-y-2 text-sm text-ivoire-text/80 text-left">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        Compléter votre profil (bio, styles, portfolio)
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        Préparer vos documents de conformité (ARS, Hygiène)
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        Découvrir les fonctionnalités de la plateforme
                    </li>
                </ul>
            </div>

            <!-- CTA -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('tattooer.profile') }}"
                    class="px-6 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors">
                    Accéder à mon profil tatoueur
                </a>
                <a href="/"
                    class="px-6 py-3 bg-gris-fonde hover:bg-titane/20 text-ivoire-text font-semibold rounded-lg transition-colors">
                    Retour à l'accueil
                </a>
            </div>

            <!-- Contact support -->
            <p class="text-ivoire-text/50 text-sm mt-6">
                Une question ? <a href="/contact" class="text-beige-peau hover:underline">Contactez-nous</a>
            </p>

        </div>

    </div>
</div>

<!-- Redirection automatique après 5 secondes -->
<script>
    setTimeout(function() {
        window.location.href = "{{ route('tattooer.profile') }}";
    }, 10000);
</script>

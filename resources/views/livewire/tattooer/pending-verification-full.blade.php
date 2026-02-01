<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification en attente - Ink&Pik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'noir-profond': '#0A0A0A',
                        'gris-fonde': '#1A1A1A',
                        'titane': '#2A2A2A',
                        'ivoire-text': '#F5F5F5',
                        'beige-peau': '#D4B59E',
                        'ambre-warning': '#F59E0B',
                        'vert-succes': '#06D6A0'
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'satoshi': ['Satoshi', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Satoshi:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body class="bg-noir-profond text-ivoire-text min-h-screen">
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
        <div class="max-w-2xl w-full">

            <!-- Card -->
            <div class="bg-gris-fonde rounded-xl p-8 text-center border border-titane/20">

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
                            Vous serez notifié par email dès que votre compte sera validé.
                        </p>
                    </div>
                @endif

                <!-- Infos utilisateur -->
                <div class="bg-titane/30 rounded-lg p-4 mb-6 text-left">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-ivoire-text/60">Nom:</span>
                        <span class="text-beige-peau font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-ivoire-text/60">Email:</span>
                        <span class="text-beige-peau font-medium">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-ivoire-text/60">Statut:</span>
                        <span class="text-ambre-warning font-medium">En attente de vérification</span>
                    </div>
                </div>

                <!-- Étapes suivantes -->
                <div class="bg-titane/20 rounded-lg p-6 mb-6">
                    <h3 class="text-beige-peau font-semibold mb-4">Pendant l'attente, vous pouvez :</h3>
                    <ul class="space-y-2 text-left text-ivoire-text/80">
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
                        class="px-6 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors transform hover:scale-105">
                        Accéder à mon profil tatoueur
                    </a>
                    <a href="/"
                        class="px-6 py-3 bg-gris-fonde hover:bg-titane/20 text-ivoire-text font-semibold rounded-lg transition-colors border border-titane/30">
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

    <!-- Redirection automatique après 10 secondes -->
    <script>
        setTimeout(function() {
            window.location.href = "{{ route('tattooer.profile') }}";
        }, 10000);
    </script>

</body>
</html>

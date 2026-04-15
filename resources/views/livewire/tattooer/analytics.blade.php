<div class="min-h-screen bg-noir-profond py-8">
    <div class="container-custom px-4">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <h1 class="text-3xl font-display font-bold text-ivoire-text">
                    Statistiques
                </h1>
                <a href="{{ route('tattooer.dashboard') }}"
                    class="text-ivoire-text/70 hover:text-beige-peau transition-colors">
                    ← Retour au dashboard
                </a>
            </div>

            <!-- Statistiques -->
            <x-pro-gate feature="les analytics et statistiques avancées" :pro-only="true">
                <div class="bg-gris-fonde rounded-xl p-6">
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-beige-peau" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        <h2 class="text-xl font-bold text-ivoire-text mb-2">
                            Statistiques en cours de développement
                        </h2>
                        <p class="text-ivoire-text/70">
                            Cette fonctionnalité sera bientôt disponible pour les utilisateurs PRO.
                        </p>
                    </div>
                </div>
            </x-pro-gate>
        </div>
    </div>
</div>

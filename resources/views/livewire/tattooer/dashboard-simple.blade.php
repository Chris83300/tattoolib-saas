<div>
    <style>
        /* Styles Ink&Pik */
        .bg-noir-profond {
            background-color: #0A0A0A;
        }

        .bg-gris-fonde {
            background-color: #1a1a1a;
        }

        .bg-beige-peau {
            background-color: #D4B59E;
        }

        .text-ivoire-text {
            color: #f5f5f5;
        }

        .text-beige-peau {
            color: #D4B59E;
        }

        .border-beige-peau {
            border-color: #D4B59E;
        }

        .text-noir-profond {
            color: #0A0A0A;
        }

        .hover\:bg-beige-peau:hover {
            background-color: #e0c5a8;
        }

        .hover\:text-beige-peau:hover {
            color: #e0c5a8;
        }
    </style>

    <div class="container mx-auto max-w-6xl p-8">
        <h1 class="text-4xl font-bold text-ivoire-text mb-8">Dashboard Tattooer</h1>

        <div class="bg-gris-fonde rounded-xl p-6">
            <h2 class="text-2xl font-bold text-beige-peau mb-4">Bienvenue sur votre espace pro !</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-noir-profond rounded-lg p-4">
                    <h3 class="text-xl font-semibold text-ivoire-text mb-2">Demandes</h3>
                    <p class="text-3xl font-bold text-beige-peau">0</p>
                </div>

                <div class="bg-noir-profond rounded-lg p-4">
                    <h3 class="text-xl font-semibold text-ivoire-text mb-2">Rendez-vous</h3>
                    <p class="text-3xl font-bold text-beige-peau">0</p>
                </div>

                <div class="bg-noir-profond rounded-lg p-4">
                    <h3 class="text-xl font-semibold text-ivoire-text mb-2">Clients</h3>
                    <p class="text-3xl font-bold text-beige-peau">0</p>
                </div>
            </div>

            <div class="mt-6 flex gap-4">
                <a href="/tattooer/demandes" class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold">
                    Voir les demandes
                </a>
                <a href="/tattooer/profil"
                    class="px-4 py-2 bg-noir-profond text-beige-peau border border-beige-peau rounded-lg font-semibold">
                    Mon profil
                </a>
            </div>
        </div>
    </div>
</div>

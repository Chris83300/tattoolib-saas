<div x-data="cookieConsent()" x-show="showBanner" x-transition.opacity x-cloak
    class="fixed bottom-0 inset-x-0 z-[100] p-4 sm:p-6" role="dialog" aria-label="Gestion des cookies">

    <div class="max-w-3xl mx-auto bg-gris-fonde border border-titane/20 rounded-2xl shadow-2xl overflow-hidden">
        {{-- Contenu principal --}}
        <div class="p-5 sm:p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 text-2xl">🍪</div>
                <div class="flex-1">
                    <h3 class="text-base font-semibold text-ivoire-text mb-2">Nous respectons votre vie privée</h3>
                    <p class="text-sm text-titane leading-relaxed">
                        Ink&Pik utilise des cookies pour assurer le bon fonctionnement du site et améliorer votre
                        expérience.
                        Certains cookies sont strictement nécessaires et ne peuvent pas être désactivés.
                        <a href="{{ route('legal.politique-cookies') }}" class="text-beige-peau hover:underline">
                            En savoir plus
                        </a>
                    </p>
                </div>
            </div>

            {{-- Détails (toggle) --}}
            <div x-show="showDetails" x-transition class="mt-4 ml-10 space-y-3">
                {{-- Cookie nécessaire --}}
                <div class="flex items-center justify-between p-3 bg-noir-profond/40 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-ivoire-text">Cookies strictement nécessaires</p>
                        <p class="text-xs text-titane mt-0.5">Authentification, sécurité CSRF, session. Indispensables
                            au fonctionnement.</p>
                    </div>
                    <span class="text-xs text-green-400 font-medium px-2 py-0.5 bg-green-500/10 rounded">Toujours
                        actifs</span>
                </div>

                {{-- Cookies analytics --}}
                <div class="flex items-center justify-between p-3 bg-noir-profond/40 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-ivoire-text">Cookies de mesure d'audience</p>
                        <p class="text-xs text-titane mt-0.5">Nous aident à comprendre comment vous utilisez le site
                            (anonymisé).</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="analytics" class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-titane/30 peer-checked:bg-beige-peau rounded-full transition-colors
                            after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white
                            after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>

                {{-- Cookies tiers (Stripe) --}}
                <div class="flex items-center justify-between p-3 bg-noir-profond/40 rounded-lg">
                    <div>
                        <p class="text-sm font-medium text-ivoire-text">Cookies tiers (Stripe)</p>
                        <p class="text-xs text-titane mt-0.5">Prévention de la fraude lors des paiements.</p>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="thirdParty" class="sr-only peer">
                        <div
                            class="w-9 h-5 bg-titane/30 peer-checked:bg-beige-peau rounded-full transition-colors
                            after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white
                            after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-full">
                        </div>
                    </label>
                </div>
            </div>
        </div>

        {{-- Boutons --}}
        <div class="px-5 pb-5 sm:px-6 sm:pb-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3">
                <button @click="acceptAll()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors">
                    Tout accepter
                </button>
                <button @click="acceptNecessaryOnly()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:border-beige-peau/50 hover:text-ivoire-text transition-colors">
                    Nécessaires uniquement
                </button>
                <button @click="rejectAll()"
                    class="flex-1 px-4 py-2.5 text-sm font-medium bg-gris-fonde text-titane border border-titane/30 rounded-lg hover:border-rouge-alerte/50 hover:text-rouge-alerte transition-colors">
                    Tout refuser
                </button>
                <button @click="showDetails = !showDetails" class="px-4 py-2.5 text-sm text-beige-peau hover:underline">
                    <span x-text="showDetails ? 'Masquer' : 'Personnaliser'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- cookieConsent Alpine component registered globally in resources/js/app.js --}}

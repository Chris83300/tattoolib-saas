@props([
    'deleteRoute',
    'exportRoute'  => null,
    'isArtist'     => false,
    'isStudio'     => false,
    'isClient'     => false,
])

<div class="mt-12 pt-8 border-t border-rouge-alerte/20" x-data="{ open: false, step: 1 }">
    <h3 class="text-lg font-semibold text-rouge-alerte/80 mb-1">Zone de danger</h3>
    <p class="text-sm text-ivoire-text/50 mb-4">
        La suppression est <strong class="text-ivoire-text/70">définitive et irréversible</strong>.
        Toutes vos données seront effacées.
    </p>

    @if ($exportRoute)
        <div class="bg-ambre-warning/10 border border-ambre-warning/20 rounded-lg p-3 mb-4 text-sm text-ambre-warning">
            💾 <strong>Recommandation :</strong> Téléchargez vos données avant de supprimer votre compte.
            <a href="{{ route($exportRoute) }}" class="underline font-medium ml-1">Exporter mes données →</a>
        </div>
    @endif

    @if ($errors->has('password') || $errors->has('confirmation'))
        <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-lg p-3 mb-4 text-sm text-rouge-alerte">
            {{ $errors->first('password') ?: $errors->first('confirmation') }}
        </div>
    @endif

    <button @click="open = true; step = 1"
        class="px-4 py-2.5 bg-rouge-alerte/10 border border-rouge-alerte/30 text-rouge-alerte rounded-lg text-sm font-semibold hover:bg-rouge-alerte/20 transition-colors">
        🗑️ Supprimer mon compte
    </button>

    {{-- Overlay --}}
    <div x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-noir-profond/80 p-4">
        <div class="bg-gris-fonde rounded-2xl shadow-2xl w-full max-w-md">

            {{-- Étape 1 : Avertissement --}}
            <div x-show="step === 1" class="p-6">
                <div class="flex items-center gap-3 mb-5">
                    <span class="text-4xl">⚠️</span>
                    <h2 class="text-xl font-bold text-rouge-alerte">Supprimer mon compte</h2>
                </div>

                <div class="bg-rouge-alerte/10 border border-rouge-alerte/20 rounded-xl p-4 mb-4 text-sm space-y-1">
                    <p class="font-semibold text-rouge-alerte mb-2">Cette action supprimera définitivement :</p>
                    <ul class="list-disc list-inside text-rouge-alerte/80 space-y-1">
                        <li>Votre profil et toutes vos informations</li>
                        <li>Votre historique de conversations</li>
                        @if ($isArtist)
                            <li>Vos fiches clients et données de traçabilité</li>
                            <li>Votre portfolio et médias</li>
                            <li>Votre abonnement (annulé immédiatement)</li>
                            <li>Les acomptes en cours seront remboursés automatiquement</li>
                        @endif
                        @if ($isStudio)
                            <li>Le studio et toutes ses configurations</li>
                            <li>Les artistes seront détachés du studio</li>
                            <li>Votre abonnement (annulé immédiatement)</li>
                        @endif
                        @if ($isClient)
                            <li>Vos demandes en cours (acomptes remboursés)</li>
                        @endif
                    </ul>
                </div>

                <div class="flex gap-3">
                    <button @click="open = false"
                        class="flex-1 px-4 py-2 bg-titane/20 border border-titane/30 text-ivoire-text rounded-lg text-sm hover:bg-titane/30 transition-colors">
                        Annuler
                    </button>
                    <button @click="step = 2"
                        class="flex-1 px-4 py-2 bg-rouge-alerte text-white rounded-lg text-sm font-semibold hover:bg-rouge-alerte/90 transition-colors">
                        Continuer →
                    </button>
                </div>
            </div>

            {{-- Étape 2 : Confirmation --}}
            <div x-show="step === 2" class="p-6">
                <h2 class="text-xl font-bold text-rouge-alerte mb-4">Confirmation requise</h2>

                <form method="POST" action="{{ route($deleteRoute) }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label class="block text-sm text-ivoire-text/70 mb-1">
                            Tapez <span class="font-mono font-bold text-ivoire-text">SUPPRIMER</span> pour confirmer
                        </label>
                        <input type="text" name="confirmation"
                            placeholder="SUPPRIMER"
                            autocomplete="off"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-rouge-alerte focus:outline-none">
                        @error('confirmation')
                            <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm text-ivoire-text/70 mb-1">Votre mot de passe actuel</label>
                        <input type="password" name="password"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-rouge-alerte focus:outline-none">
                        @error('password')
                            <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="open = false"
                            class="flex-1 px-4 py-2 bg-titane/20 border border-titane/30 text-ivoire-text rounded-lg text-sm hover:bg-titane/30 transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-rouge-alerte text-white rounded-lg text-sm font-semibold hover:bg-rouge-alerte/90 transition-colors">
                            Supprimer définitivement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

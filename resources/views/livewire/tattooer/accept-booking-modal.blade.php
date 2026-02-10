<div>
    @if ($showModal)
        <div x-data="{ open: true }" x-show="open" x-cloak
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="open = false; $wire.closeModal()" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div class="bg-gris-fonde rounded-2xl border border-beige-peau/20 shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto"
                @click.stop>

                {{-- Header --}}
                <div
                    class="sticky top-0 bg-gris-fonde border-b border-beige-peau/20 p-6 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-ivoire-text">Accepter la demande</h2>
                    <button type="button" wire:click="closeModal" class="text-ivoire-text/60 hover:text-rouge-alerte">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Formulaire --}}
                <form wire:submit="submitAcceptance" class="p-6 space-y-8">

                    {{-- ══════════════════════════════════════════ --}}
                    {{-- Section 1 — Estimation du projet          --}}
                    {{-- ══════════════════════════════════════════ --}}
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">1</span>
                            💰 Estimation du projet
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Prix minimum (€) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="priceEstimateMin" step="0.01" required
                                        placeholder="300"
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                    @error('priceEstimateMin')
                                        <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Prix maximum (€) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="priceEstimateMax" step="0.01" required
                                        placeholder="500"
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                    @error('priceEstimateMax')
                                        <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Montant acompte (€) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="totalDepositAmount" step="0.01" required
                                        placeholder="100"
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                    @error('totalDepositAmount')
                                        <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Délai paiement (jours) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <select wire:model="clientPaymentDeadlineDays" required
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau">
                                        <option value="3">3 jours</option>
                                        <option value="5">5 jours</option>
                                        <option value="7">7 jours (recommandé)</option>
                                        <option value="14">14 jours</option>
                                        <option value="30">30 jours</option>
                                    </select>
                                    <p class="text-xs text-ivoire-text/60 mt-1">Le chat se fermera automatiquement si le
                                        client ne paie pas dans ce délai.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════ --}}
                    {{-- Section 2 — Proposition de dates          --}}
                    {{-- ══════════════════════════════════════════ --}}
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">2</span>
                            📅 Proposition de rendez-vous
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4">
                            <p class="text-ivoire-text/60 text-sm mb-4">
                                Cliquez sur 1 à 3 dates disponibles pour les proposer au client.
                                Le calendrier affiche vos disponibilités réelles.
                            </p>

                            {{-- ⭐ Calendrier interactif — mode multi-max-3 --}}
                            {{-- Le tattooer clique directement sur les jours disponibles --}}
                            {{-- L'événement 'dates-updated' est écouté par onDatesUpdated() --}}
                            @if ($bookingRequest)
                                <livewire:components.availability-calendar :tattooer-id="$bookingRequest->bookable_id" mode="multi-max-3"
                                    :show-period-selector="true" :key="'calendar-accept-' . $bookingRequest->id" />
                            @endif

                            @error('proposedDates')
                                <span class="text-rouge-alerte text-sm mt-2 block">{{ $message }}</span>
                            @enderror

                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════ --}}
                    {{-- Section 3 — Options de design              --}}
                    {{-- ══════════════════════════════════════════ --}}
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">3</span>
                            🎨 Options de design
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Versions de design incluses <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="includedDesignVersions" min="1"
                                        max="10" required
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                </div>
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Modifications par version <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="modificationsPerDesign" min="0"
                                        max="10" required
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ══════════════════════════════════════════ --}}
                    {{-- Section 4 — Notes pour le client           --}}
                    {{-- ══════════════════════════════════════════ --}}
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">4</span>
                            📝 Notes pour le client
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4">
                            <textarea wire:model="tattooerNotes" rows="4" placeholder="Informations complémentaires pour le client..."
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20"></textarea>
                            @error('tattooerNotes')
                                <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex gap-4 pt-6 border-t border-beige-peau/20">
                        <button type="button" wire:click="closeModal"
                            class="flex-1 px-6 py-4 bg-noir-profond border-2 border-beige-peau/20 text-ivoire-text rounded-xl font-semibold hover:bg-beige-peau/10 transition-all">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-4 bg-vert-succes text-noir-profond rounded-xl font-bold text-lg hover:bg-vert-succes/90 transition-all shadow-lg"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>✓ Valider et envoyer au client</span>
                            <span wire:loading>⏳ Traitement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

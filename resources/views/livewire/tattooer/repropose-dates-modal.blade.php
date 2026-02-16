<div>
    @if ($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-data="{
             selectedDates: [],
             init() {
                 Livewire.on('dates-updated', (data) => {
                     // Le calendrier envoie les dates
                     if (Array.isArray(data)) {
                         // Livewire 3 envoie un tableau de paramètres
                         this.selectedDates = data[0]?.selectedDates ?? data[0] ?? data;
                     } else {
                         this.selectedDates = data.selectedDates ?? data;
                     }
                     // S'assurer que c'est un tableau
                     if (!Array.isArray(this.selectedDates)) {
                         this.selectedDates = [];
                     }
                 });
             },
             formatDate(d) {
                 if (!d) return '';
                 const date = d.date ?? d;
                 const period = d.period === 'morning' ? '(Matin)' : (d.period === 'afternoon' ? '(Après-midi)' : '');
                 try {
                     const formatted = new Date(date).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
                     return formatted + (period ? ' ' + period : '');
                 } catch(e) { return date; }
             },
             submit() {
                    if (this.selectedDates.length === 0) return;
                    // $wire n'est pas dispo ici, utiliser Livewire.dispatch
                    Livewire.dispatch('submitReproposedDates', { dates: this.selectedDates });
                }
         }"
         x-transition x-cloak>

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-noir-profond/70" wire:click="closeModal"></div>

        {{-- Modal --}}
        <div class="relative bg-gris-fonde rounded-2xl p-6 max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-titane/20">

            {{-- Header --}}
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                    📅 Proposer de nouvelles dates
                </h3>
                <button wire:click="closeModal"
                        class="text-ivoire-text/50 hover:text-ivoire-text transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Instructions --}}
            <div class="bg-noir-profond/50 rounded-xl p-4 mb-5">
                <p class="text-ivoire-text/60 text-sm">
                    Le client a refusé les dates précédentes.
                    Cliquez sur <strong class="text-ivoire-text">1 à 3 dates</strong> disponibles.
                </p>
            </div>

            {{-- Calendrier — clé STATIQUE, pas de re-render --}}
            @if ($bookingRequestId)
                @php $br = \App\Models\BookingRequest::find($bookingRequestId); @endphp
                @if ($br)
                    <div class="mb-5" wire:ignore>
                        <livewire:components.availability-calendar
                            :tattooer-id="$br->bookable_id"
                            mode="multi-max-3"
                            :show-period-selector="true"
                            :key="'calendar-repropose-' . $bookingRequestId" />
                    </div>
                @endif
            @endif

            {{-- Dates sélectionnées (géré par Alpine, pas Livewire) --}}
            <template x-if="selectedDates.length > 0">
                <div class="bg-vert-succes/10 rounded-xl p-4 mb-5 border border-vert-succes/20">
                    <p class="text-sm text-vert-succes font-semibold mb-2">
                        ✅ <span x-text="selectedDates.length"></span> date(s) sélectionnée(s)
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <template x-for="(date, index) in selectedDates" :key="index">
                            <span class="px-3 py-1 bg-vert-succes/20 text-vert-succes rounded-full text-xs font-medium"
                                  x-text="formatDate(date)"></span>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="selectedDates.length === 0">
                <div class="bg-titane/10 rounded-xl p-4 mb-5 border border-titane/20">
                    <p class="text-sm text-ivoire-text/40 italic text-center">
                        Aucune date sélectionnée — cliquez sur les jours disponibles
                    </p>
                </div>
            </template>

            {{-- Boutons --}}
            <div class="flex justify-end gap-3 pt-4 border-t border-titane/20">
                <button wire:click="closeModal"
                        class="px-4 py-2.5 border border-titane/30 text-ivoire-text/70 rounded-xl text-sm hover:bg-titane/10 transition-colors">
                    Annuler
                </button>
                <button @click="submit()"
                        :disabled="selectedDates.length === 0"
                        wire:loading.attr="disabled"
                        class="px-5 py-2.5 bg-beige-peau text-noir-profond rounded-xl text-sm font-semibold hover:bg-beige-peau/90 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                    <span wire:loading.remove wire:target="submitNewDates">
                        📅 Envoyer (<span x-text="selectedDates.length">0</span>)
                    </span>
                    <span wire:loading wire:target="submitNewDates">
                        Envoi en cours...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

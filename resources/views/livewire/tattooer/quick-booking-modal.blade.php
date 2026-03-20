<div>
    @if ($showQuickBookingModal)
        <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4" x-data="{
            calculateDuration() {
                const start = document.querySelector('[wire\\:model=startTime]')?.value || '';
                const end = document.querySelector('[wire\\:model=endTime]')?.value || '';
                if (!start || !end) return '0';
                const [sh, sm] = start.split(':').map(Number);
                const [eh, em] = end.split(':').map(Number);
                const diff = (eh * 60 + em) - (sh * 60 + sm);
                return diff > 0 ? diff : '0';
            }
        }"
            x-trap.noscroll="true" @click.self="$wire.set('showQuickBookingModal', false)">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-ivoire-text">📅 Fixer le rendez-vous</h3>
                <button type="button" wire:click="$set('showQuickBookingModal', false)"
                    class="text-titane hover:text-ivoire-text transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Client info -->
            <div class="bg-noir-profond/50 rounded-lg p-3 mb-4">
                <p class="text-sm text-ivoire-text/80 mb-1">Client</p>
                <p class="text-ivoire-text font-medium">{{ $bookingRequest?->client?->user?->pseudo ?? 'Client' }}
                </p>
                <p class="text-xs text-titane mt-1">{{ $bookingRequest?->tattoo_description }}</p>
            </div>

            <!-- Date verrouillée -->
            <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-lg p-3 mb-4">
                <p class="text-sm text-vert-succes font-medium mb-1">📅 Date choisie par le client</p>
                <p class="text-ivoire-text font-bold">{{ $appointmentDateDisplay }}</p>
            </div>

            <!-- Formulaire -->
            <form wire:submit.prevent="createAppointment" class="space-y-4">

                <!-- Titre -->
                <div>
                    <label class="block text-sm font-medium text-ivoire-text/80 mb-1">
                        Titre du rendez-vous
                    </label>
                    <input type="text" wire:model="appointmentTitle" required
                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                </div>

                <!-- Heures -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-ivoire-text/80 mb-1">
                            Heure de début
                        </label>
                        <input type="time" wire:model="startTime" required
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ivoire-text/80 mb-1">
                            Heure de fin
                        </label>
                        <input type="time" wire:model="endTime" required
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                    </div>
                </div>

                <!-- Durée calculée -->
                <div class="bg-noir-profond/30 rounded-lg p-3">
                    <p class="text-xs text-titane">Durée estimée</p>
                    <p class="text-ivoire-text font-medium">
                        <span x-text="calculateDuration()"></span> minutes
                    </p>
                </div>

                <!-- Erreurs -->
                @error('startTime')
                    <p class="text-rouge-alerte text-sm">{{ $message }}</p>
                @enderror
                @error('endTime')
                    <p class="text-rouge-alerte text-sm">{{ $message }}</p>
                @enderror

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="$set('showQuickBookingModal', false)"
                        class="flex-1 px-4 py-2 border border-titane/30 text-titane rounded-lg hover:bg-noir-profond transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        wire:loading.attr="disabled"
                        wire:target="createAppointment"
                        class="flex-1 px-4 py-2 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="createAppointment">✅ Confirmer le RDV</span>
                        <span wire:loading wire:target="createAppointment">Création...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>

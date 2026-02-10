<div>
    <!-- Modal booking rapide -->
    <div 
        x-data="{ show: @entangle('showModal') }"
        x-show="show" 
        x-cloak
        class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-gris-fonde rounded-xl max-w-md w-full p-6" @click.away="show = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-ivoire-text">📅 Fixer le rendez-vous</h3>
                <button @click="show = false" class="text-titane hover:text-ivoire-text">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form wire:submit="createAppointmentFromBooking">
                <!-- Titre (pré-rempli, non modifiable) -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-ivoire-text mb-1">Titre</label>
                    <input type="text" 
                           wire:model="appointmentTitle" 
                           readonly
                           class="w-full px-4 py-2.5 bg-noir-profond/50 border border-titane/20 rounded-lg text-ivoire-text opacity-80 cursor-not-allowed">
                </div>

                <!-- Date (pré-remplie, non modifiable) -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-ivoire-text mb-1">Date</label>
                    <input type="text" 
                           wire:model="appointmentDate" 
                           readonly
                           class="w-full px-4 py-2.5 bg-noir-profond/50 border border-titane/20 rounded-lg text-ivoire-text opacity-80 cursor-not-allowed">
                </div>

                <!-- Heure de début (SEUL CHAMP À REMPLIR) -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-1">Début *</label>
                        <input type="time" 
                               wire:model="appointmentStartTime"
                               required
                               class="w-full px-4 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                        @error('appointmentStartTime')
                            <span class="text-rouge-alerte text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-1">Fin *</label>
                        <input type="time" 
                               wire:model="appointmentEndTime"
                               required
                               class="w-full px-4 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                        @error('appointmentEndTime')
                            <span class="text-rouge-alerte text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Bouton confirmer -->
                <button type="submit" 
                        class="w-full py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition"
                        wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>✅ Confirmer le rendez-vous</span>
                    <span wire:loading>⏳ Création...</span>
                </button>
            </form>
        </div>
    </div>
</div>

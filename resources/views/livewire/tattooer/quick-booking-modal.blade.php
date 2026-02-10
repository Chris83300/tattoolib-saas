<div x-data="quickBookingModal()" x-init="init()" x-cloak>
    <!-- Modal -->
    <template x-if="showModal">
        <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
             @click.self="closeModal()">
            <div class="bg-gris-fonde rounded-xl max-w-md w-full p-6" @click.stop>
                
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-ivoire-text">📅 Fixer le rendez-vous</h3>
                    <button type="button" @click="closeModal()"
                        class="text-titane hover:text-ivoire-text transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Client info -->
                <div class="bg-noir-profond/50 rounded-lg p-3 mb-4">
                    <p class="text-sm text-ivoire-text/80 mb-1">Client</p>
                    <p class="text-ivoire-text font-medium">{{ $bookingRequest?->client?->user?->pseudo ?? 'Client' }}</p>
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
                        <button type="button" @click="closeModal()"
                            class="flex-1 px-4 py-2 border border-titane/30 text-titane rounded-lg hover:bg-noir-profond transition-colors">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                            ✅ Confirmer le RDV
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <script>
    function quickBookingModal() {
        return {
            showModal: false,
            startTime: '{{ $startTime }}',
            endTime: '{{ $endTime }}',
            
            init() {
                // Écouter les événements Livewire
                this.$wire.$on('refresh-quick-booking', () => {
                    this.showModal = false;
                });
                
                // Mettre à jour les heures quand elles changent dans Livewire
                this.$watch('startTime', (value) => {
                    this.startTime = value;
                });
                
                this.$watch('endTime', (value) => {
                    this.endTime = value;
                });
            },
            
            closeModal() {
                this.showModal = false;
                this.$wire.set('showModal', false);
            },
            
            calculateDuration() {
                if (!this.startTime || !this.endTime) return '0';
                
                const [startHour, startMin] = this.startTime.split(':').map(Number);
                const [endHour, endMin] = this.endTime.split(':').map(Number);
                
                const startMinutes = startHour * 60 + startMin;
                const endMinutes = endHour * 60 + endMin;
                
                return endMinutes > startMinutes ? (endMinutes - startMinutes) : '0';
            }
        }
    }
    </script>
</div>

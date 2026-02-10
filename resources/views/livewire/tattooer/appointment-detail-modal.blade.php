<div>
    @if($appointment)
    <div 
        x-data="{ show: @entangle('showModal'), editMode: @entangle('editMode') }"
        x-show="show" 
        x-cloak
        class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-gris-fonde rounded-xl max-w-lg w-full p-6" @click.away="show = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-ivoire-text">{{ $appointment->title }}</h3>
                <div class="flex items-center gap-2">
                    {{-- Badge statut --}}
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $appointment->status->color() }}">
                        {{ $appointment->status->label() }}
                    </span>
                    <button @click="show = false" class="text-titane hover:text-ivoire-text text-xl">&times;</button>
                </div>
            </div>

            <!-- Infos RDV -->
            <div x-show="!editMode" class="space-y-3 mb-6">
                <div class="flex items-center gap-3 text-ivoire-text">
                    <span class="text-titane">📅</span>
                    <span>{{ $appointment->start_datetime->translatedFormat('l d F Y') }}</span>
                </div>
                <div class="flex items-center gap-3 text-ivoire-text">
                    <span class="text-titane">🕐</span>
                    <span>{{ $appointment->start_datetime->format('H:i') }} → {{ $appointment->end_datetime->format('H:i') }}</span>
                    <span class="text-titane text-sm">({{ $appointment->start_datetime->diffInMinutes($appointment->end_datetime) }} min)</span>
                </div>
                @if($appointment->bookingRequest?->client)
                    <div class="flex items-center gap-3 text-ivoire-text">
                        <span class="text-titane">👤</span>
                        <span>{{ $appointment->bookingRequest->client->user->pseudo ?? $appointment->bookingRequest->client->user->name }}</span>
                    </div>
                @endif
            </div>

            <!-- Formulaire modification (toggle) -->
            <div x-show="editMode" class="space-y-3 mb-6">
                <div>
                    <label class="text-sm text-titane">Nouvelle date</label>
                    <input type="date" wire:model="editDate" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text">
                    @error('editDate')
                        <span class="text-rouge-alerte text-xs">{{ $message }}</span>
                    @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-titane">Début</label>
                        <input type="time" wire:model="editStartTime" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text">
                        @error('editStartTime')
                            <span class="text-rouge-alerte text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm text-titane">Fin</label>
                        <input type="time" wire:model="editEndTime" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text">
                        @error('editEndTime')
                            <span class="text-rouge-alerte text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="updateAppointment" class="flex-1 py-2 bg-beige-peau text-noir-profond font-bold rounded-lg">
                        ✅ Sauvegarder
                    </button>
                    <button @click="editMode = false" class="flex-1 py-2 border border-titane/30 text-titane rounded-lg">
                        Annuler
                    </button>
                </div>
            </div>

            <!-- 3 Boutons d'action -->
            <div x-show="!editMode" class="grid grid-cols-3 gap-3">
                {{-- 1. Voir détails → page demande --}}
                <a href="{{ route('tattooer.booking-requests.show', $appointment->booking_request_id) }}"
                   class="flex flex-col items-center gap-1 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-beige-peau transition text-center">
                    <span class="text-lg">📋</span>
                    <span class="text-xs text-ivoire-text font-medium">Voir détails</span>
                </a>
                
                {{-- 2. Modifier --}}
                <button @click="editMode = true"
                        class="flex flex-col items-center gap-1 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-ambre-warning transition text-center">
                    <span class="text-lg">✏️</span>
                    <span class="text-xs text-ivoire-text font-medium">Modifier</span>
                </button>
                
                {{-- 3. Annuler --}}
                <button wire:click="openCancelConfirm"
                        class="flex flex-col items-center gap-1 py-3 bg-noir-profond border border-rouge-alerte/30 rounded-lg hover:border-rouge-alerte transition text-center">
                    <span class="text-lg">❌</span>
                    <span class="text-xs text-ivoire-text font-medium">Annuler</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Sous-modale confirmation annulation -->
    @if($showCancelConfirm)
    <div class="fixed inset-0 bg-black/90 z-[60] flex items-center justify-center p-4">
        <div class="bg-gris-fonde rounded-xl max-w-sm w-full p-6">
            <h4 class="text-lg font-bold text-rouge-alerte mb-3">Annuler le rendez-vous ?</h4>
            <p class="text-sm text-titane mb-4">Le client sera notifié. Un remboursement peut s'appliquer selon vos conditions.</p>
            <textarea wire:model="cancelReason" placeholder="Motif (optionnel)" rows="2"
                      class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text mb-4"></textarea>
            <div class="flex gap-3">
                <button wire:click="$set('showCancelConfirm', false)" class="flex-1 py-2 border border-titane/30 text-titane rounded-lg">
                    Retour
                </button>
                <button wire:click="cancelAppointment" class="flex-1 py-2 bg-rouge-alerte text-ivoire-text font-bold rounded-lg">
                    Confirmer l'annulation
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

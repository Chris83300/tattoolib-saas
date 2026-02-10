<div x-data="{ 
    showAcceptModal: @entangle('showAcceptModal') 
}}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    @click.self="showAcceptModal = false"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <!-- Modal container -->
    <div class="bg-gris-fonde rounded-2xl border border-beige-peau/20 shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto mx-4 p-6"
        @click.stop>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-ivoire-text flex items-center gap-2">
                <span class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">1</span>
                📋 Acceptation de la demande
            </h2>
            <button @click="showAcceptModal = false" class="text-ivoire-text/60 hover:text-rouge-alerte">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form wire:submit="acceptBookingRequest">
            @csrf
            
            <!-- Prix -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Prix min (€) <span class="text-rouge-alerte">*</span></label>
                    <input type="number" wire:model="priceEstimateMin" step="0.01" min="0" required
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Prix max (€) <span class="text-rouge-alerte">*</span></label>
                    <input type="number" wire:model="priceEstimateMax" step="0.01" min="0" required
                           class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <!-- Acompte -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Montant acompte (€) <span class="text-rouge-alerte">*</span></label>
                <input type="number" wire:model="depositAmount" step="0.01" min="0" required
                           class="w-full border rounded-lg px-3 py-2">
            </div>

            <!-- Délai -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Délai de paiement</label>
                <select wire:model="depositDeadlineHours" class="w-full border rounded-lg px-3 py-2">
                    <option value="24">24 heures</option>
                    <option value="48">48 heures</option>
                    <option value="72" selected>72 heures (recommandé)</option>
                    <option value="120">5 jours</option>
                    <option value="168">7 jours</option>
                </select>
            </div>

            <!-- Dessins -->
            <div class="grid grid-cols-2 gap-3 mb-4">
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Dessins inclus</label>
                    <input type="number" wire:model="includedDesigns" min="1" max="5" required
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm text-gray-600 mb-1">Modifs / dessin</label>
                    <input type="number" wire:model="modificationsPerDesign" min="1" max="10" required
                           class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <!-- Dates proposées -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-2">Dates proposées (jusqu'à 3)</label>
                @for($i = 0; $i < 3; $i++)
                    <div class="flex gap-2 mb-2">
                        <input type="date" wire:model="proposedDates.{{ $i }}.date"
                               min="{{ now()->addDays(3)->format('Y-m-d') }}"
                               class="flex-1 border rounded-lg px-3 py-2">
                        <select wire:model="proposedDates.{{ $i }}.period" class="border rounded-lg px-3 py-2">
                            <option value="">Période</option>
                            <option value="morning">Matin</option>
                            <option value="afternoon">Après-midi</option>
                            <option value="evening">Soirée</option>
                        </select>
                    </div>
                @endfor
            </div>

            <!-- Message -->
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Message au client</label>
                <textarea wire:model="acceptanceMessage" rows="3" 
                          placeholder="Super projet ! Voici mes conditions..."
                          class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <!-- Boutons -->
            <div class="flex gap-3">
                <button type="button" @click="showAcceptModal = false"
                            class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-600">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 py-2 bg-vert-succes text-white rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>✅ Confirmer</span>
                        <span wire:loading>⏳ Envoi...</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de rejet -->
<div x-data="{ 
    showRejectModal: @entangle('showRejectModal') 
}}" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
    @click.self="showRejectModal = false"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <!-- Modal container -->
    <div class="bg-gris-fonde rounded-2xl border border-rouge-alerte/20 shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto mx-4 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-ivoire-text flex items-center gap-2">
                <span class="w-8 h-8 bg-rouge-alerte rounded-full flex items-center justify-center text-white font-bold">2</span>
                ❌ Refuser la demande
            </h2>
            <button @click="showRejectModal = false" class="text-ivoire-text/60 hover:text-rouge-alerte">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form wire:submit="rejectBookingRequest">
            @csrf
            
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-1">Raison du refus</label>
                <textarea wire:model="rejectReason" rows="3" 
                          placeholder="Expliquez pourquoi vous refusez cette demande..."
                          class="w-full border rounded-lg px-3 py-2"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="showRejectModal = false"
                            class="flex-1 py-2 border border-gray-300 rounded-lg text-gray-600">
                    Annuler
                </button>
                <button type="submit"
                        class="flex-1 py-2 bg-rouge-alerte text-white rounded-lg font-semibold hover:bg-rouge-alerte/90 transition-colors"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove>❌ Confirmer le refus</span>
                        <span wire:loading>⏳ Envoi...</span>
                </button>
            </div>
        </form>
    </div>
</div>

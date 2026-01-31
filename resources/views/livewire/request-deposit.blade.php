<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-[#0A0A0A]">Demander un acompte</h2>
        <span class="text-sm text-gray-500">
            Client: {{ $project->client->full_name }}
        </span>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Résumé du projet -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h3 class="font-medium text-gray-900 mb-2">Résumé du projet</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Description:</span>
                <p class="font-medium">{{ $project->tattoo_description }}</p>
            </div>
            <div>
                <span class="text-gray-600">Emplacement:</span>
                <p class="font-medium">{{ $project->tattoo_location }}</p>
            </div>
            <div>
                <span class="text-gray-600">Style:</span>
                <p class="font-medium">{{ $project->tattoo_style }}</p>
            </div>
            <div>
                <span class="text-gray-600">Statut:</span>
                <p class="font-medium">{{ $project->status_formatted }}</p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form wire:submit="requestDeposit" class="space-y-6">
        <!-- Prix et acompte -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tarification</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Prix total estimé (€) *
                    </label>
                    <div class="relative">
                        <input type="number" wire:model="estimatedPrice" 
                               wire:change="calculateDeposit"
                               min="10" step="5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E] pr-12">
                        <span class="absolute right-3 top-2 text-gray-500">€</span>
                    </div>
                    @error('estimatedPrice') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Acompte demandé (€) *
                        <span class="text-xs text-gray-500">({{ $depositPercentage }}%)</span>
                    </label>
                    <div class="relative">
                        <input type="number" wire:model="depositAmount" 
                               min="10" step="5"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E] pr-12">
                        <span class="absolute right-3 top-2 text-gray-500">€</span>
                    </div>
                    @error('depositAmount') <span class="text-red-500 text-sm">{{ $message }}</span> @endif
                    
                    <!-- Bouton calcul automatique -->
                    <button type="button" wire:click="calculateDeposit"
                            class="mt-2 text-sm text-[#D4B59E] hover:text-[#C4A68E]">
                        Calculer 30% automatiquement
                    </button>
                </div>
            </div>

            <!-- Résumé financier -->
            @if($estimatedPrice && $depositAmount)
                <div class="mt-4 p-4 bg-[#D4B59E]/10 rounded-lg">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-sm text-gray-600">Acompte</p>
                            <p class="font-semibold">{{ number_format($depositAmount, 2) }}€</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Reste dû</p>
                            <p class="font-semibold">{{ number_format($remainingAmount, 2) }}€</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total</p>
                            <p class="font-semibold">{{ number_format($estimatedPrice, 2) }}€</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Durée et rendez-vous -->
        <div class="border-b pb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Planning</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Durée estimée (minutes) *
                    </label>
                    <input type="number" wire:model="estimatedDuration" 
                           wire:change="calculateDuration"
                           min="30" step="15"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                    @error('estimatedDuration') <span class="text-red-500 text-sm">{{ $message }}</span> @endif
                    <p class="text-xs text-gray-500 mt-1">{{ floor($estimatedDuration / 60) }}h {{ $estimatedDuration % 60 }}min</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Date du rendez-vous *
                    </label>
                    <input type="date" wire:model="appointmentDate" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                    @error('appointmentDate') <span class="text-red-500 text-sm">{{ $message }}</span> @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Heure de début *
                    </label>
                    <select wire:model="appointmentTime" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                        <option value="">Sélectionnez une heure</option>
                        @foreach($availableTimeSlots as $slot)
                            <option value="{{ $slot }}">{{ $slot }}</option>
                        @endforeach
                    </select>
                    @error('appointmentTime') <span class="text-red-500 text-sm">{{ $message }}</span> @endif
                    
                    @if($appointmentEndTime)
                        <p class="text-xs text-gray-500 mt-1">
                            Fin: {{ $appointmentEndTime }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-4">
            <button type="button" onclick="history.back()"
                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                Annuler
            </button>
            
            <button type="submit" 
                    wire:loading.attr="disabled"
                    class="px-6 py-2 bg-[#D4B59E] text-white rounded-md hover:bg-[#C4A68E] transition-colors disabled:opacity-50">
                <span wire:loading.remove>Envoyer la demande d'acompte</span>
                <span wire:loading>Envoi en cours...</span>
            </button>
        </div>
    </form>

    <!-- Informations -->
    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
        <h4 class="font-medium text-blue-900 mb-2">
            <svg class="w-5 h-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Important
        </h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• Le client recevra un email avec un lien de paiement sécurisé</li>
            <li>• Le rendez-vous sera confirmé uniquement après paiement de l'acompte</li>
            <li>• Le client aura 48h pour payer l'acompte</li>
            <li>• En cas de non-paiement, la demande sera automatiquement annulée</li>
        </ul>
    </div>
</div>

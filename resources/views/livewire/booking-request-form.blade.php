<div>
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#D4B59E] to-[#0A0A0A] text-white p-6 rounded-t-lg">
        <h1 class="text-2xl font-bold">Demander un projet</h1>
        <p class="text-[#F5F5DC] mt-2">{{ $bookableName }}</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-white p-6 rounded-b-lg shadow-lg">
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

        <form wire:submit="submitRequest" class="space-y-6">
            <!-- Informations client -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4 text-[#0A0A0A]">Vos informations</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prénom *</label>
                        <input type="text" wire:model="firstName" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                        @error('firstName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                        <input type="text" wire:model="lastName" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                        @error('lastName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" wire:model="email" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone *</label>
                        <input type="tel" wire:model="phone" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date de naissance *</label>
                        <input type="date" wire:model="birthDate" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                        @error('birthDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                        <textarea wire:model="address" rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]"></textarea>
                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Détails du projet -->
            <div class="border-b pb-6">
                <h2 class="text-xl font-semibold mb-4 text-[#0A0A0A]">Détails du projet</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description du tattoo *</label>
                        <textarea wire:model="description" rows="4" 
                                  placeholder="Décrivez en détail le tattoo que vous souhaitez..."
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <p class="text-sm text-gray-500 mt-1">{{ strlen($description ?? '') }}/1000 caractères</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Emplacement *</label>
                            <select wire:model="location" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                                <option value="">Sélectionnez une zone</option>
                                @foreach($bodyLocations as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('location') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Style *</label>
                            <select wire:model="style" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                                <option value="">Sélectionnez un style</option>
                                @foreach($styles as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('style') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Budget estimé (€) *</label>
                            <input type="number" wire:model="estimatedBudget" min="50" step="10"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                            @error('estimatedBudget') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date souhaitée</label>
                            <input type="date" wire:model="proposedDate" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-[#D4B59E] focus:border-[#D4B59E]">
                            @error('proposedDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            <p class="text-sm text-gray-500 mt-1">Optionnel - L'artiste vous proposera les dates disponibles</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images de référence -->
            <div>
                <h2 class="text-xl font-semibold mb-4 text-[#0A0A0A]">Images de référence</h2>
                <p class="text-sm text-gray-600 mb-4">Ajoutez jusqu'à 5 images pour inspirer l'artiste (PNG, JPG, WebP - Max 10MB)</p>
                
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                    <input type="file" wire:model="referenceImages" multiple accept="image/*" 
                           class="hidden" id="reference-images">
                    
                    <label for="reference-images" class="cursor-pointer">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <p class="mt-2 text-sm text-gray-600">Cliquez pour ajouter des images</p>
                            <p class="text-xs text-gray-500">ou glissez-déposez</p>
                        </div>
                    </label>

                    @if (count($referenceImages) > 0)
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($referenceImages as $index => $image)
                                <div class="relative group">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Reference {{ $index + 1 }}" 
                                         class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" wire:click="removeReferenceImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                @error('referenceImages.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Bouton de soumission -->
            <div class="pt-6">
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="w-full bg-[#D4B59E] text-white py-3 px-6 rounded-md font-semibold hover:bg-[#C4A68E] transition-colors disabled:opacity-50">
                    <span wire:loading.remove>Envoyer ma demande</span>
                    <span wire:loading>Envoi en cours...</span>
                </button>
                
                <p class="text-xs text-gray-500 mt-2 text-center">
                    En envoyant cette demande, vous acceptez les conditions générales de vente.
                </p>
            </div>
        </form>
    </div>
</div>

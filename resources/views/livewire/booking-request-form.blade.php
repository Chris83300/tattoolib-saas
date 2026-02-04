<div>
    <!-- Header -->
    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
        <h1 class="text-xl md:text-2xl font-bold text-ivoire-text mb-1">Demande de projet</h1>
        <p class="text-ivoire-text/70 text-sm">{{ $bookableName }}</p>
    </div>

    <!-- Formulaire -->
    <div class="bg-gris-fonde p-4 md:p-6 rounded-xl mt-4">
        @if (session()->has('success'))
            <div
                class="bg-vert-succes/20 border border-vert-succes/50 text-vert-succes px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div
                class="bg-rouge-alerte/20 border border-rouge-alerte/50 text-rouge-alerte px-4 py-3 rounded-lg mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit="submitRequest" class="space-y-6">
            <!-- Informations client -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-bold mb-4 text-ivoire-text">Vos informations</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Prénom *</label>
                        <input type="text" wire:model="firstName"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        @error('firstName')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Nom *</label>
                        <input type="text" wire:model="lastName"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        @error('lastName')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Email *</label>
                        <input type="email" wire:model="email"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        @error('email')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Pseudo</label>
                        <input type="text" wire:model="pseudo"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        @error('pseudo')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Téléphone</label>
                        <input type="tel" wire:model="phone"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        @error('phone')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Date de naissance *</label>
                        <input type="date" wire:model="birthDate"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        @error('birthDate')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Adresse</label>
                        <textarea wire:model="address" rows="2"
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"></textarea>
                        @error('address')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Détails du projet -->
            <div class="border-b pb-6">
                <h2 class="text-lg font-bold mb-4 text-ivoire-text">Détails du projet</h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Description du tattoo
                            *</label>
                        <textarea wire:model="description" rows="4" placeholder="Décrivez en détail le tattoo que vous souhaitez..."
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"></textarea>
                        @error('description')
                            <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                        @enderror
                        <p class="text-xs text-ivoire-text/50 mt-2">{{ strlen($description ?? '') }}/1000 caractères
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Taille du Tattoo en
                                cm *</label>
                            <input type="number" wire:model="tattoo_size"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            @error('tattoo_size')
                                <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Emplacement *</label>
                            <select wire:model="location"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                                <option value="">Sélectionnez une zone</option>
                                @foreach ($bodyLocations as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('location')
                                <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Style *</label>
                            <select wire:model="style"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                                <option value="">Sélectionnez un style</option>
                                @foreach ($styles as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('style')
                                <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Budget estimé (€)
                                *</label>
                            <input type="number" wire:model="estimatedBudget" min="50" step="10"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            @error('estimatedBudget')
                                <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-ivoire-text/80 mb-2">Date souhaitée</label>
                            <input type="date" wire:model="proposedDate"
                                class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            @error('proposedDate')
                                <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                            @enderror
                            <p class="text-xs text-ivoire-text/50 mt-2">Optionnel - L'artiste vous proposera les dates
                                disponibles</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images de référence -->
            <div>
                <h2 class="text-lg font-bold mb-2 text-ivoire-text">Images de référence</h2>
                <p class="text-sm text-ivoire-text/70 mb-4">Ajoutez jusqu'à 5 images (PNG, JPG, WebP, HEIC, GIF, SVG -
                    Max 10MB)
                </p>

                <div class="border-2 border-dashed border-titane/30 rounded-lg p-6 bg-noir-profond">
                    <input type="file" wire:model="referenceImages" multiple accept="image/*" class="hidden"
                        id="reference-images">

                    <label for="reference-images" class="cursor-pointer">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-ivoire-text/40" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm text-ivoire-text/80">Cliquez pour ajouter des images</p>
                            <p class="text-xs text-ivoire-text/50">ou glissez-déposez</p>
                        </div>
                    </label>

                    @if (count($referenceImages) > 0)
                        <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($referenceImages as $index => $image)
                                @if ($image)
                                    <div class="relative group">
                                        @if ($image->extension() && in_array($image->extension(), ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg']))
                                            <img src="{{ $image->temporaryUrl() }}"
                                                alt="Reference {{ $index + 1 }}"
                                                class="w-full h-32 object-cover rounded-lg"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="w-full h-32 bg-titane/20 rounded-lg flex items-center justify-center"
                                                style="display:none;">
                                                <span class="text-ivoire-text/50 text-sm">
                                                    Image {{ $index + 1 }}<br>
                                                    <small>{{ $image->extension() }}</small>
                                                </span>
                                            </div>
                                        @else
                                            <div
                                                class="w-full h-32 bg-titane/20 rounded-lg flex items-center justify-center">
                                                <span class="text-ivoire-text/50 text-sm">
                                                    Image {{ $index + 1 }}<br>
                                                    <small>{{ $image->extension() ?: 'Inconnu' }}</small>
                                                </span>
                                            </div>
                                        @endif
                                        <button type="button" wire:click="removeReferenceImage({{ $index }})"
                                            class="absolute top-2 right-2 bg-rouge-alerte text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                @error('referenceImages.*')
                    <span class="text-rouge-alerte text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Bouton de soumission -->
            <div class="pt-6">
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full min-h-11 bg-beige-peau text-noir-profond py-3 px-6 rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors disabled:opacity-50">
                    <span wire:loading.remove>Envoyer ma demande</span>
                    <span wire:loading>Envoi en cours...</span>
                </button>

                <p class="text-xs text-ivoire-text/50 mt-2 text-center">
                    En envoyant cette demande, vous acceptez les conditions générales de vente.
                </p>
            </div>
        </form>
    </div>
</div>

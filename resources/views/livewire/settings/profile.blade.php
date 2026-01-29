<div class="min-h-screen bg-noir-profond py-8">
    <div class="container mx-auto px-4 max-w-2xl">

        <h1 class="text-3xl font-Satoshi font-bold text-ivoire-text mb-8">Paramètres du profil</h1>

        <form wire:submit.prevent="updateProfileInformation" class="bg-gris-fonde rounded-xl p-6 space-y-6">

            <!-- Avatar -->
            <div>
                <label class="block text-ivoire-text font-semibold mb-3">Photo de profil</label>

                <div class="flex items-center gap-6">
                    <!-- Avatar actuel -->
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-beige-peau/10 flex-shrink-0">
                        <img src="{{ $currentAvatar }}" alt="Avatar" class="w-full h-full object-cover">
                    </div>

                    <!-- Upload -->
                    <div class="flex-1">
                        <input type="file" wire:model="avatar" accept="image/*"
                            class="block w-full text-sm text-ivoire-text file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-beige-peau file:text-noir-profond hover:file:bg-beige-peau/90">
                        @error('avatar')
                            <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                        @enderror

                        @if ($avatar)
                            <p class="text-vert-succes text-xs mt-2">✓ Nouvelle image sélectionnée</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Pseudo (public) -->
            <div>
                <label class="block text-ivoire-text font-semibold mb-2">
                    Pseudo <span class="text-beige-peau">(affiché publiquement)</span>
                </label>
                <input type="text" wire:model="pseudo" placeholder="Ex: InkMaster83"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau transition-colors">
                <p class="text-ivoire-text/50 text-xs mt-1">
                    Ce pseudo sera affiché sur votre profil public et dans les messages
                </p>
                @error('pseudo')
                    <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nom réel (privé pour pros) -->
            <div>
                <label class="block text-ivoire-text font-semibold mb-2">
                    Nom réel
                    @if (in_array(auth()->user()->role, ['tattooer', 'pierceur', 'studio']))
                        <span class="text-xs text-ivoire-text/50 font-normal">(requis pour conformité ARS)</span>
                    @endif
                </label>
                <input type="text" wire:model="name"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau transition-colors"
                    required>
                @error('name')
                    <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-ivoire-text font-semibold mb-2">Email</label>
                <input type="email" wire:model="email"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau transition-colors"
                    required>
                @error('email')
                    <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <div class="flex gap-3 pt-4">
                <button type="submit" wire:loading.attr="disabled"
                    class="flex-1 bg-beige-peau hover:bg-beige-peau/90 disabled:bg-titane disabled:cursor-not-allowed text-noir-profond font-bold py-3 rounded-lg transition-colors">
                    <span wire:loading.remove wire:target="updateProfileInformation">Enregistrer les
                        modifications</span>
                    <span wire:loading wire:target="updateProfileInformation">Enregistrement...</span>
                </button>

                <a href="{{ auth()->user()->role === 'client' ? route('client.profile') : route('tattooer.profile') }}"
                    class="px-6 py-3 border border-titane text-ivoire-text hover:bg-titane/10 font-semibold rounded-lg transition-colors">
                    Annuler
                </a>
            </div>

            <!-- Message succès -->
            @if (session()->has('success'))
                <div class="bg-vert-succes/10 border border-vert-succes rounded-lg p-4">
                    <p class="text-vert-succes font-semibold">{{ session('success') }}</p>
                </div>
            @endif

        </form>

    </div>
</div>

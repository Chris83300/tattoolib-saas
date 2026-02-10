<div class="container mx-auto max-w-4xl p-6">

    <!-- Header -->
    <div class="bg-gris-fonde rounded-xl p-6 mb-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('tattooer.dashboard') }}" class="text-ivoire-text hover:text-beige-peau transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-ivoire-text">Paramètres du profil</h1>
        </div>
    </div>

    <!-- Messages de feedback -->
    @if ($successMessage)
        <div class="mb-6 p-4 bg-vert-succes text-noir-profond rounded-lg shadow-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ $successMessage }}
            </div>
        </div>
    @endif

    @if ($errorMessage)
        <div class="mb-6 p-4 bg-red-500 text-white rounded-lg shadow-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd"></path>
                </svg>
                {{ $errorMessage }}
            </div>
        </div>
    @endif

    <!-- Contenu principal -->
    <div class="bg-noir-profond rounded-xl p-8 shadow-xl">

        <!-- Photo de profil -->
        <div class="flex items-center gap-6 mb-8">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-beige-peau/10 border-4 border-beige-peau/20">
                @if (auth()->user()->tattooer->getFirstMediaUrl('avatar') &&
                        auth()->user()->tattooer->getFirstMediaUrl('avatar') !== '/images/default-tattooer-avatar.png')
                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'thumb') }}" alt="Photo de profil"
                        class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-3xl">
                        🎨
                    </div>
                @endif
            </div>

            <div class="flex-1">
                <h3 class="text-ivoire-text font-semibold mb-4">Photo de profil</h3>

                <!-- Upload Avatar -->
                <form wire:submit="uploadAvatar" class="mb-4">
                    <input type="file" wire:model="testAvatar" accept="image/*"
                        class="w-full px-4 py-2 bg-noir-profond border border-titane/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-beige-peau file:text-noir-profond hover:file:bg-beige-peau/90">

                    @if ($testAvatar)
                        <div class="text-vert-succes text-sm mt-2">
                            ✅ Fichier sélectionné: {{ $testAvatar->getClientOriginalName() }}
                        </div>
                    @endif

                    <button type="submit" wire:loading.attr="disabled"
                        class="mt-2 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold disabled:opacity-50">
                        <span wire:loading>
                            Upload en cours...
                        </span>
                        <span wire:loading.remove>
                            Uploader l'avatar
                        </span>
                    </button>
                </form>

                <!-- Suppression -->
                @if (auth()->user()->hasMedia('avatar'))
                    <button wire:click="removeAvatar" type="button"
                        class="mt-2 px-4 py-2 bg-red-500 text-white rounded-lg font-semibold hover:bg-red-600">
                        Supprimer l'avatar
                    </button>
                @endif
            </div>
        </div>

        <!-- Formulaire principal -->
        <form wire:submit="updateProfile">
            <h3 class="text-ivoire-text font-semibold mb-6">Informations personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-ivoire-text font-medium mb-2">Nom complet</label>
                    <input type="text" wire:model="name"
                        class="w-full px-4 py-2 bg-noir-profond border border-titane/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none"
                        placeholder="Votre nom">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-ivoire-text font-medium mb-2">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full px-4 py-2 bg-noir-profond border border-titane/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none"
                        placeholder="votre@email.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-ivoire-text font-medium mb-2">Téléphone</label>
                    <input type="tel" wire:model="phone"
                        class="w-full px-4 py-2 bg-noir-profond border border-titane/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none"
                        placeholder="Votre téléphone">
                </div>

                <div>
                    <label class="block text-ivoire-text font-medium mb-2">Bio</label>
                    <textarea wire:model="bio" rows="3"
                        class="w-full px-4 py-2 bg-noir-profond border border-titane/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none resize-none"
                        placeholder="Parlez-nous de vous..."></textarea>
                    <p class="text-ivoire-text/50 text-xs mt-1">{{ strlen($bio) }}/1000 caractères</p>
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 disabled:opacity-50">
                    <span wire:loading>
                        Enregistrement...
                    </span>
                    <span wire:loading.remove>
                        Enregistrer les modifications
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

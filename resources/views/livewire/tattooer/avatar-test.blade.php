<div class="p-6 bg-white rounded-lg max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Test Avatar Settings</h2>
    
    <!-- Messages de feedback -->
    @if($successMessage)
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
            ✅ {{ $successMessage }}
        </div>
    @endif
    
    @if($errorMessage)
        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">
            ❌ {{ $errorMessage }}
        </div>
    @endif
    
    <form wire:submit="updateProfile" class="space-y-6">
        <!-- Avatar -->
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-gray-200 border-4 border-gray-300">
                @if($avatar)
                    @if($avatar->temporaryUrl())
                        <img src="{{ $avatar->temporaryUrl() }}" alt="Aperçu" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-2xl">📁</div>
                    @endif
                @elseif(auth()->user()->tattooer->getFirstMediaUrl('avatar'))
                    <img src="{{ auth()->user()->tattooer->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-2xl">🎨</div>
                @endif
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium mb-2">Avatar</label>
                <input type="file" wire:model="avatar" accept="image/*" class="block w-full">
                
                @if($avatar)
                    @if($avatar->getSize() > 2048 * 1024)
                        <p class="text-orange-600 text-sm mt-1">⚠️ Trop volumineux: {{ round($avatar->getSize() / 1024 / 1024, 2) }}MB</p>
                    @else
                        <p class="text-green-600 text-sm mt-1">✅ {{ $avatar->getClientOriginalName() }} ({{ round($avatar->getSize() / 1024) }}KB)</p>
                    @endif
                @endif
                
                @if(auth()->user()->tattooer->getFirstMediaUrl('avatar'))
                    <button type="button" wire:click="removeAvatar" class="mt-2 text-red-600 text-sm">Supprimer avatar</button>
                @endif
            </div>
        </div>
        
        <!-- Champs simples -->
        <div>
            <label class="block text-sm font-medium mb-2">Nom</label>
            <input type="text" wire:model="name" class="w-full p-2 border rounded">
        </div>
        
        <div>
            <label class="block text-sm font-medium mb-2">Email</label>
            <input type="email" wire:model="email" class="w-full p-2 border rounded">
        </div>
        
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Enregistrer
        </button>
    </form>
    
    <!-- Debug info -->
    <div class="mt-6 p-4 bg-gray-100 rounded text-sm">
        <h3 class="font-bold mb-2">Debug Info:</h3>
        <p>Avatar présent: {{ $avatar ? 'OUI' : 'NON' }}</p>
        @if($avatar)
            <p>Nom: {{ $avatar->getClientOriginalName() }}</p>
            <p>Taille: {{ $avatar->getSize() }} octets</p>
            <p>Type: {{ $avatar->getMimeType() }}</p>
            <p>Temporary URL: {{ $avatar->temporaryUrl() ?: 'NON' }}</p>
        @endif
        <p>Avatar existant: {{ auth()->user()->tattooer->getFirstMediaUrl('avatar') ? 'OUI' : 'NON' }}</p>
    </div>
</div>

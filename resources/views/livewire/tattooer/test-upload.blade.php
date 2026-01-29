<div class="p-6 bg-white rounded-lg">
    <h2 class="text-xl font-bold mb-4">Test Upload Avatar</h2>
    
    <form wire:submit="uploadAvatar" class="space-y-4">
        <div>
            <label class="block text-sm font-medium mb-2">Avatar</label>
            <input 
                type="file" 
                wire:model="testAvatar"
                accept="image/*"
                class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100"
            >
        </div>
        
        @if($testAvatar)
            <div class="text-sm text-green-600">
                Fichier sélectionné: {{ $testAvatar->getClientOriginalName() }}
            </div>
        @endif
        
        <button 
            type="submit"
            wire:loading.attr="disabled"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg disabled:opacity-50">
            <span wire:loading>
                Upload en cours...
            </span>
            <span wire:loading.remove>
                Uploader
            </span>
        </button>
    </form>
    
    @if($message)
        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
            {{ $message }}
        </div>
    @endif
    
    @if($error)
        <div class="mt-4 p-3 bg-red-100 text-red-800 rounded">
            {{ $error }}
        </div>
    @endif
    
    @if(auth()->user()->tattooer->getFirstMediaUrl('avatar'))
        <div class="mt-6">
            <h3 class="text-lg font-semibold mb-2">Avatar actuel:</h3>
            <img 
                src="{{ auth()->user()->tattooer->getFirstMediaUrl('avatar', 'thumb') }}" 
                alt="Avatar"
                class="w-24 h-24 rounded-full object-cover"
            >
        </div>
    @endif
</div>

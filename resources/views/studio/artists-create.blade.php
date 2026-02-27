@extends('layouts.studio')

@section('content')
<div class="max-w-xl mx-auto space-y-6" x-data="{ mode: 'create' }">
    <div>
        <a href="{{ route('studio.artists') }}" class="text-xs text-titane hover:text-ivoire-text transition-colors">← Retour</a>
        <h1 class="text-2xl font-bold text-ivoire-text mt-2">Ajouter un artiste</h1>
    </div>

    {{-- Choix du mode --}}
    <div class="flex gap-2">
        <button @click="mode = 'create'"
            :class="mode === 'create' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
            class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-colors">
            ✏️ Créer un compte
        </button>
        <button @click="mode = 'invite'"
            :class="mode === 'invite' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
            class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-colors">
            📧 Envoyer une invitation
        </button>
    </div>

    {{-- Mode : Création directe --}}
    <form x-show="mode === 'create'" action="{{ route('studio.artists.store') }}" method="POST"
        class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
        @csrf
        <p class="text-xs text-titane">Créez un compte pour votre artiste. Il recevra un mot de passe temporaire.</p>

        <div>
            <label class="text-xs text-titane block mb-1">Nom complet *</label>
            <input type="text" name="name" required placeholder="Prénom Nom" value="{{ old('name') }}"
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
            @error('name') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-titane block mb-1">Email *</label>
            <input type="email" name="email" required placeholder="artiste@email.com" value="{{ old('email') }}"
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
            @error('email') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-titane block mb-1">Type de profil *</label>
            <div class="flex gap-3">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">🎨</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Tatoueur</p>
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">💎</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Pierceur</p>
                    </div>
                </label>
            </div>
        </div>
        <button type="submit"
            class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Créer l'artiste
        </button>
    </form>

    {{-- Mode : Invitation --}}
    <form x-show="mode === 'invite'" action="{{ route('studio.artists.invite') }}" method="POST"
        class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
        @csrf
        <p class="text-xs text-titane">Envoyez une invitation par email. L'artiste créera son propre compte et sera automatiquement rattaché à votre studio.</p>

        <div>
            <label class="text-xs text-titane block mb-1">Email de l'artiste *</label>
            <input type="email" name="email" required placeholder="artiste@email.com" value="{{ old('email') }}"
                class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
            @error('email') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="text-xs text-titane block mb-1">Type de profil *</label>
            <div class="flex gap-3">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">🎨</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Tatoueur</p>
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
                    <div class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                        <span class="text-lg">💎</span>
                        <p class="text-xs font-semibold text-ivoire-text mt-1">Pierceur</p>
                    </div>
                </label>
            </div>
        </div>
        <button type="submit"
            class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
            Envoyer l'invitation
        </button>
    </form>
</div>
@endsection

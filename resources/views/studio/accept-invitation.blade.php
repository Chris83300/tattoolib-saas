@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto py-12 px-4">
    <div class="bg-gris-fonde rounded-2xl p-6 space-y-4">
        {{-- Logo studio --}}
        @if ($studio->getFirstMediaUrl('logo'))
            <img src="{{ $studio->getFirstMediaUrl('logo') }}" alt="{{ $studio->name }}"
                class="w-16 h-16 rounded-xl object-cover mx-auto">
        @endif

        <div class="text-center">
            <h1 class="text-xl font-bold text-ivoire-text">Invitation</h1>
            <p class="text-sm text-titane mt-1">
                <strong class="text-beige-peau">{{ $studio->name }}</strong> vous invite à rejoindre son studio en tant que
                <strong class="text-ivoire-text">{{ $invitation->artisan_type === 'piercer' ? 'Pierceur' : 'Tatoueur' }}</strong>.
            </p>
        </div>

        <form action="{{ route('studio.invitation.process', $invitation->invitation_token) }}" method="POST" class="space-y-3">
            @csrf
            <div>
                <label class="text-xs text-titane block mb-1">Nom complet *</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                @error('name') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Email *</label>
                <input type="email" name="email" required value="{{ old('email', $invitation->invitation_email) }}"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                @error('email') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Mot de passe *</label>
                <input type="password" name="password" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
                @error('password') <p class="text-xs text-rouge-alerte mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Confirmer le mot de passe *</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:outline-none">
            </div>
            <button type="submit"
                class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
                Rejoindre le studio
            </button>
        </form>
    </div>
</div>
@endsection

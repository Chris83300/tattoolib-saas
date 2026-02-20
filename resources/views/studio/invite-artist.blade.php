@extends('layouts.studio')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-ivoire-text mb-6">Inviter un Artiste</h1>
    
    <div class="bg-gris-fonde rounded-xl p-6">
        <form method="POST" action="{{ route('studio.artists.invite') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-ivoire-text mb-2">Email de l'artiste</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 bg-noir-profond border border-ivoire-text/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-beige-peau" required>
                </div>
                
                <div>
                    <label for="role" class="block text-sm font-medium text-ivoire-text mb-2">Rôle</label>
                    <select name="role" id="role" class="w-full px-4 py-2 bg-noir-profond border border-ivoire-text/20 rounded-lg focus:outline-none focus:ring-2 focus:ring-beige-peau" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="artist">Artiste</option>
                        <option value="manager">Manager</option>
                        <option value="receptionist">Réceptionniste</option>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="w-full bg-beige-peau text-noir-profond px-4 py-2 rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        Envoyer l'invitation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

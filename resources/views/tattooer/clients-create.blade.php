@extends('layouts.tattooer')

@section('title', 'Créer un client')

@section('content')
    <div class="max-w-2xl mx-auto space-y-6">

        <!-- Header -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center gap-4">
                <a href="{{ route($tattooer->routePrefix() . '.clients') }}" class="p-2 rounded-lg hover:bg-noir-profond transition-colors">
                    <svg class="w-5 h-5 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text">Créer une fiche client</h1>
                    <p class="text-ivoire-text/70">Ajouter un nouveau client manuellement</p>
                </div>
            </div>
        </div>

        <!-- Erreurs globales -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                <p class="text-red-400 font-semibold mb-2">Veuillez corriger les erreurs suivantes :</p>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-300 text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                <p class="text-red-400 text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Formulaire -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <form action="{{ route($tattooer->routePrefix() . '.clients.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">Prénom</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="w-full px-4 py-3 bg-noir-profond border @error('first_name') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="Jean">
                        @error('first_name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">Nom</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="w-full px-4 py-3 bg-noir-profond border @error('last_name') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="Dupont">
                        @error('last_name')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">Pseudo</label>
                        <input type="text" name="pseudo" value="{{ old('pseudo') }}"
                            class="w-full px-4 py-3 bg-noir-profond border @error('pseudo') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="jean_ink">
                        @error('pseudo')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-noir-profond border @error('email') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="jean.dupont@email.com">
                        @error('email')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">Téléphone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 bg-noir-profond border @error('phone') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="06 12 34 56 78">
                        @error('phone')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informations complémentaires -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">Date de naissance</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                            class="w-full px-4 py-3 bg-noir-profond border @error('birth_date') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                        @error('birth_date')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-ivoire-text mb-2">Adresse</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="w-full px-4 py-3 bg-noir-profond border @error('address') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau"
                            placeholder="123 Rue du Salon, 75011 Paris">
                        @error('address')
                            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-ivoire-text mb-2">Notes privées</label>
                    <textarea name="notes" rows="4"
                        class="w-full px-4 py-3 bg-noir-profond border @error('notes') border-red-500 @else border-titane/30 @enderror rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-y"
                        placeholder="Allergies, préférences, comportement au salon, informations utiles...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-titane mt-1">
                        Visibles uniquement par vous. Ces notes vous aideront à mieux connaître votre client.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4">
                    <a href="{{ route($tattooer->routePrefix() . '.clients') }}"
                        class="px-6 py-3 border border-titane/30 text-titane rounded-lg font-semibold hover:bg-noir-profond transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-bold hover:bg-beige-peau/90 transition-colors active:scale-95">
                        Créer la fiche client
                    </button>
                </div>
            </form>
        </div>

        <!-- Info -->
        <div class="bg-titane/10 rounded-xl p-4 border border-titane/20">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-beige-peau flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm text-ivoire-text/80">
                    <p class="font-semibold text-beige-peau mb-1">Fonctionnalité PRO</p>
                    <p>La création manuelle de fiches clients vous permet d'ajouter des clients sans qu'ils aient à passer
                        par une demande de réservation.</p>
                </div>
            </div>
        </div>

    </div>
@endsection

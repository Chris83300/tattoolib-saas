@extends('layouts.guest')

@section('content')
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="{{ route('register') }}" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
                    ← Retour au choix du rôle
                </a>
                <h1 class="text-beige-peau font-display text-2xl font-bold">
                    Inscription Pierceur Professionnel
                </h1>
                <p class="text-ivoire-text/70 text-sm mt-2">
                    SIRET obligatoire pour vous inscrire
                </p>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('register.pierceur.submit') }}" method="POST"
                class="bg-gris-fonde rounded-xl border border-cuivre/40 shadow-md shadow-cuivre/20 p-6 md:p-8 space-y-6">
                @csrf

                <!-- Champ caché pour le plan -->
                <input type="hidden" name="plan" value="{{ request('plan', 'starter') }}" />

                <!-- Affichage des erreurs de validation -->
                @if ($errors->any())
                    <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-lg p-4 mb-4">
                        <div class="text-rouge-alerte text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- SECTION 1 : SIRET + SPÉCIALISATION -->
                <div class="border-b border-titane/20 pb-6">
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        1. Informations professionnelles
                    </h2>

                    <!-- SIRET -->
                    <div class="mb-4">
                        <label class="block text-ivoire-text text-sm font-semibold mb-2">
                            Numéro SIRET <span class="text-rouge-alerte">*</span> <span
                                class="text-ambre-warning/80 font-normal">(14 chiffres)</span>
                        </label>
                        <input type="text" name="siret" required maxlength="14" placeholder="12345678901234"
                            value="{{ old('siret') }}"
                            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors font-mono">
                        @error('siret')
                            <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Spécialisation -->
                    <div>
                        <label class="block text-ivoire-text text-sm font-semibold mb-2">
                            Spécialisation <span class="text-rouge-alerte">*</span>
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            @foreach ([
            'pierceur' => ['label' => 'Pierceur'],
            'bodemodeur' => ['label' => 'Body Modeur'],
            'pierceur_bodemodeur' => ['label' => 'Pierceur & Body Modeur'],
        ] as $value => $opt)
                                <label
                                    class="flex items-center gap-3 p-3 bg-noir-profond rounded-lg cursor-pointer border border-titane/30 hover:border-beige-peau/50 transition-colors has-[:checked]:border-beige-peau has-[:checked]:bg-beige-peau/10">
                                    <input type="radio" name="specialization" value="{{ $value }}"
                                        {{ old('specialization', 'pierceur') === $value ? 'checked' : '' }}
                                        class="w-4 h-4 text-beige-peau focus:ring-beige-peau">
                                    <span class="text-ivoire-text text-sm font-semibold">{{ $opt['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('specialization')
                            <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SECTION 2 : COMPTE -->
                <div class="border-b border-titane/20 pb-6">
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        2. Informations de compte
                    </h2>

                    <div class="space-y-4">
                        <!-- Prénom -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">Prénom <span
                                    class="text-rouge-alerte">*</span></label>
                            <input type="text" name="first_name" required placeholder="Jean"
                                value="{{ old('first_name') }}"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            @error('first_name')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">Nom <span
                                    class="text-rouge-alerte">*</span></label>
                            <input type="text" name="last_name" required placeholder="Dupont"
                                value="{{ old('last_name') }}"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            @error('last_name')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pseudo -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Pseudo <span class="text-ivoire-text/50 font-normal">(affiché publiquement)</span>
                            </label>
                            <input type="text" name="pseudo" placeholder="Ex: PierceurPro75"
                                value="{{ old('pseudo') }}"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            <p class="text-ivoire-text/50 text-xs mt-1">Ce pseudo sera affiché sur votre profil public</p>
                            @error('pseudo')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">Email professionnel <span
                                    class="text-rouge-alerte">*</span></label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            @error('email')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Mot de passe <span class="text-rouge-alerte">*</span> <span
                                    class="text-ivoire-text/50 font-normal">(8 car. min, 1 majuscule, 1 chiffre, 1
                                    spécial)</span>
                            </label>
                            <input type="password" name="password" required minlength="8" placeholder="•••••••••"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            @error('password')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password confirmation -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">Confirmer mot de passe <span
                                    class="text-rouge-alerte">*</span></label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                placeholder="•••••••••"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            @error('password_confirmation')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION 3 : LOCALISATION -->
                <div>
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        3. Localisation
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">Ville <span
                                    class="text-rouge-alerte">*</span></label>
                            <input type="text" name="city" required value="{{ old('city') }}"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            @error('city')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">Code postal <span
                                    class="text-rouge-alerte">*</span></label>
                            <input type="text" name="postal_code" required value="{{ old('postal_code') }}"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            @error('postal_code')
                                <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-ivoire-text text-sm font-semibold mb-2">Téléphone <span
                                class="text-ivoire-text/50 font-normal">(optionnel)</span></label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                    </div>
                </div>

                <!-- Submit -->
                <x-ui.button type="submit" variant="primary" size="md" class="w-full">
                    Créer mon compte pierceur
                </x-ui.button>

            </form>
        </div>
    </div>
@endsection

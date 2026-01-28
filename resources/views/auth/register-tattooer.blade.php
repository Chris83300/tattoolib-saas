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
                    Inscription Tatoueur Professionnel
                </h1>
                <p class="text-ivoire-text/70 text-sm mt-2">
                    SIRET obligatoire pour vous inscrire
                </p>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('register.tattooer.submit') }}" method="POST"
                class="bg-gris-fonde rounded-xl p-6 md:p-8 space-y-6">
                @csrf

                <!-- SECTION 1 : SIRET (PRIORITÉ) -->
                <div class="border-b border-titane/20 pb-6">
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        1. Informations professionnelles
                    </h2>

                    <!-- SIRET -->
                    <div>
                        <label class="block text-ivoire-text text-sm font-semibold mb-2">
                            Numéro SIRET * <span class="text-ivoire-text/50 font-normal">(14 chiffres)</span>
                        </label>
                        <input type="text" name="siret" required maxlength="14" placeholder="12345678901234"
                            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors font-mono">
                    </div>
                </div>

                <!-- SECTION 2 : COMPTE -->
                <div class="border-b border-titane/20 pb-6">
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        2. Informations de compte
                    </h2>

                    <div class="space-y-4">
                        <!-- Nom -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Nom / Nom d'artiste *
                            </label>
                            <input type="text" name="name" required placeholder="Ex: Jean Dupont ou JD Ink"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Email professionnel *
                            </label>
                            <input type="email" name="email" required
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Mot de passe *
                            </label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Password confirmation -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Confirmer mot de passe *
                            </label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>
                    </div>
                </div>

                <!-- SECTION 3 : LOCALISATION -->
                <div>
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        3. Localisation
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ville -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Ville *
                            </label>
                            <input type="text" name="city" required
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Code postal -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Code postal *
                            </label>
                            <input type="text" name="postal_code" required
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="mt-4">
                        <label class="block text-ivoire-text text-sm font-semibold mb-2">
                            Téléphone (optionnel)
                        </label>
                        <input type="tel" name="phone"
                            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors">
                    Créer mon compte tatoueur
                </button>

            </form>
        </div>
    </div>
@endsection

@extends('layouts.guest')

@section('content')
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="{{ route('register') }}" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
                    ← Retour au choix du rôle
                </a>
                <h1 class="text-beige-peau font-display text-2xl font-bold">
                    Inscription Client
                </h1>
                <p class="text-ivoire-text/70 text-sm mt-2">
                    Créez votre compte pour accéder à la plateforme
                </p>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('register.client.submit') }}" method="POST" class="bg-gris-fonde rounded-xl p-6 space-y-4">
                @csrf

                <!-- Prénom -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Prénom *
                    </label>
                    <input type="text" name="first_name" required
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="Jean">
                </div>

                <!-- Nom -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Nom *
                    </label>
                    <input type="text" name="last_name" required
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="Dupont">
                </div>

                <!-- Pseudo -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Pseudo <span class="text-ivoire-text/50 font-normal">(affiché publiquement)</span>
                    </label>
                    <input type="text" name="pseudo" placeholder="Ex: Client123"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                    <p class="text-ivoire-text/50 text-xs mt-1">
                        Ce pseudo sera affiché sur votre profil public et dans les messages
                    </p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Email *
                    </label>
                    <input type="email" name="email" required
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="jean.dupont@email.com">
                </div>

                <!-- Téléphone (optionnel) -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Téléphone (optionnel)
                    </label>
                    <input type="tel" name="phone"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="06 12 34 56 78">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Mot de passe *
                    </label>
                    <input type="password" name="password" required minlength="8"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="•••••••••">
                </div>

                <!-- Password confirmation -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Confirmer mot de passe *
                    </label>
                    <input type="password" name="password_confirmation" required minlength="8"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="•••••••••">
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors">
                    Créer mon compte
                </button>

            </form>
        </div>
    </div>
@endsection

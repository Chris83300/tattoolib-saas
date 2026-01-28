@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="text-beige-peau font-Satoshi text-3xl font-bold">
                Ink&Pik
            </a>
        </div>

        <!-- Titre -->
        <h1 class="text-2xl md:text-3xl font-display font-bold text-ivoire-text mb-2 text-center">
            Connexion
        </h1>
        <p class="text-ivoire-text/70 text-center mb-8">
            Connectez-vous à votre compte Ink&Pik
        </p>

        <!-- Formulaire -->
        <form wire:submit="login" class="bg-gris-fonde rounded-xl p-6 space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Email
                </label>
                <input
                    type="email"
                    wire:model="email"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    required
                    placeholder="votre@email.com"
                >
                @error('email')
                    <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Mot de passe
                </label>
                <input
                    type="password"
                    wire:model="password"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    required
                    placeholder="••••••••••••"
                >
                @error('password')
                    <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember me -->
            <div class="flex items-center">
                <input
                    type="checkbox"
                    wire:model="remember"
                    id="remember"
                    class="w-4 h-4 text-beige-peau bg-noir-profond border-titane/30 rounded focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50">
                >
                <label for="remember" class="text-ivoire-text/70 text-sm">
                    Se souvenir de moi
                </label>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors"
            >
                Se connecter
            </button>
        </form>

        <!-- Lien inscription -->
        <div class="text-center mt-6">
            <p class="text-ivoire-text/70 text-sm">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-beige-peau font-semibold hover:underline">
                    Créer un compte
                </a>
            </p>
        </div>

        <!-- Lien mot de passe oublié -->
        <div class="text-center mt-4">
            <a href="#" class="text-ivoire-text/50 text-sm hover:text-beige-peau hover:underline">
                Mot de passe oublié ?
            </a>
        </div>
    </div>
</div>
@endsection

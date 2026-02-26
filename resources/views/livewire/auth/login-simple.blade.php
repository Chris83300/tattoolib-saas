@extends('layouts.guest')

@section('content')
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center flex flex-col items-center justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Ink&Pik" class="w-30 h-30 mb-4">
                <a href="/" class="text-beige-peau font-Satoshi text-4xl font-bold ">
                    <span class="text-titane">Ink</span> & Pik
                </a>
            </div>

            <!-- Titre -->
            <h1 class="text-2xl md:text-3xl font-display font-bold text-beige-peau mb-2 text-center">
                Connexion
            </h1>
            <p class="text-ivoire-text/70 text-center mb-8">
                Connectez-vous à votre compte Ink&Pik
            </p>

            <!-- Formulaire Laravel Fortify -->
            <form method="POST" action="{{ route('login.authenticate') }}"
                class="bg-gris-fonde rounded-xl p-6 space-y-4 border boder-cuivre shadow-lg shadow-cuivre/30">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        required placeholder="votre@email.com" autocomplete="email" autofocus>
                    @error('email')
                        <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Mot de passe
                    </label>
                    <input type="password" name="password"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        required placeholder="••••••••••••" autocomplete="current-password">
                    @error('password')
                        <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember me -->
                <div class="flex items-center">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                        class="w-4 h-4 text-beige-peau bg-noir-profond border-titane/30 rounded focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50">

                    <label for="remember" class="text-ivoire-text/70 text-sm mx-4">
                        Se souvenir de moi
                    </label>
                </div>

                <!-- Submit -->
                <x-ui.button type="submit" variant="primary" size="md" class="w-full">
                    Se connecter
                </x-ui.button>
            </form>

            <!-- Lien inscription -->
            <div class="text-center mt-6">
                <p class="text-ivoire-text/70 text-sm">
                    Pas encore de compte ?
                    <x-ui.button type="submit" variant="secondary" size="sm" href="{{ route('register') }}">
                        Créer un compte
                    </x-ui.button>
                </p>
            </div>

            <!-- Lien mot de passe oublié -->
            <div class="text-center mt-4">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-ivoire-text/50 text-sm hover:text-beige-peau hover:underline">
                        Mot de passe oublié ?
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

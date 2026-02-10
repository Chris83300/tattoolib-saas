@extends('layouts.guest')

@section('title', 'Réinitialiser le mot de passe - Ink&Pik')

<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('login') }}" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
                ← Retour à la connexion
            </a>
            <h1 class="text-beige-peau font-display text-2xl font-bold">
                Réinitialiser le mot de passe
            </h1>
            <p class="text-ivoire-text/70 text-sm mt-2">
                Choisissez votre nouveau mot de passe
            </p>
        </div>

        <!-- Formulaire -->
        <form action="{{ route('password.reset') }}" method="POST" class="bg-gris-fonde rounded-xl p-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Email *
                </label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    placeholder="votre@email.com">
            </div>

            <!-- Nouveau mot de passe -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Nouveau mot de passe * <span class="text-ivoire-text/50 font-normal">(8 caractères minimum, 1 majuscule, 1 chiffre, 1 caractère spécial)</span>
                </label>
                <input type="password" name="password" required minlength="8"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    placeholder="•••••••••">
            </div>

            <!-- Confirmation mot de passe -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Confirmer le mot de passe *
                </label>
                <input type="password" name="password_confirmation" required minlength="8"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    placeholder="•••••••••">
            </div>

            <!-- Erreurs -->
            @if ($errors->any())
                <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-lg p-3">
                    @foreach ($errors->all() as $error)
                        <p class="text-rouge-alerte text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors">
                Réinitialiser le mot de passe
            </button>
        </form>
    </div>
</div>

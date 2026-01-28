@extends('components.layouts.site')

@section('title', 'Connexion - Ink&Pik')

<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
                ← Retour à l'accueil
            </a>
            <h1 class="text-beige-peau font-display text-2xl font-bold">
                Connexion
            </h1>
            <p class="text-ivoire-text/70 text-sm mt-2">
                Accédez à votre compte Ink&Pik
            </p>
        </div>
        
        <!-- Formulaire -->
        <form action="{{ route('login.authenticate') }}" method="POST" class="bg-gris-fonde rounded-xl p-6 space-y-4">
            @csrf
            
            <!-- Email -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Email *
                </label>
                <input 
                    type="email" 
                    name="email"
                    required
                    value="{{ old('email') }}"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    placeholder="votre@email.com">
            </div>
            
            <!-- Password -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Mot de passe *
                </label>
                <input 
                    type="password" 
                    name="password"
                    required
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
            <button 
                type="submit"
                class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors">
                Se connecter
            </button>
            
        </form>
        
        <!-- Lien inscription -->
        <div class="text-center mt-6">
            <p class="text-ivoire-text/70 text-sm">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-beige-peau font-semibold hover:underline">
                    S'inscrire
                </a>
            </p>
        </div>
    </div>
</div>

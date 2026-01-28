<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
  <div class="max-w-md w-full">

    <!-- Header -->
    <div class="text-center mb-8">
      <a href="/" class="text-beige-peau font-Satoshi text-2xl font-bold">
        Ink&Pik
      </a>
      <h1 class="text-ivoire-text text-xl font-display font-bold mt-4">
        Inscription Client
      </h1>
    </div>

    <!-- Formulaire -->
    <form wire:submit="register" class="bg-gris-fonde rounded-xl p-6 space-y-4">

      <!-- Prénom -->
      <div>
        <label class="block text-ivoire-text text-sm font-semibold mb-2">
          Prénom *
        </label>
        <input
          type="text"
          wire:model="first_name"
          class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
          required
        >
        @error('first_name')
          <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Nom -->
      <div>
        <label class="block text-ivoire-text text-sm font-semibold mb-2">
          Nom *
        </label>
        <input
          type="text"
          wire:model="last_name"
          class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
          required
        >
        @error('last_name')
          <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Email -->
      <div>
        <label class="block text-ivoire-text text-sm font-semibold mb-2">
          Email *
        </label>
        <input
          type="email"
          wire:model="email"
          class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
          required
        >
        @error('email')
          <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Téléphone (optionnel) -->
      <div>
        <label class="block text-ivoire-text text-sm font-semibold mb-2">
          Téléphone (optionnel)
        </label>
        <input
          type="tel"
          wire:model="phone"
          class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
        >
      </div>

      <!-- Password -->
      <div>
        <label class="block text-ivoire-text text-sm font-semibold mb-2">
          Mot de passe *
        </label>
        <input
          type="password"
          wire:model="password"
          class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
          required
        >
        @error('password')
          <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
        @enderror
      </div>

      <!-- Password confirmation -->
      <div>
        <label class="block text-ivoire-text text-sm font-semibold mb-2">
          Confirmer mot de passe *
        </label>
        <input
          type="password"
          wire:model="password_confirmation"
          class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
          required
        >
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors">
        Créer mon compte
      </button>

    </form>

    <!-- Retour -->
    <div class="text-center mt-6">
      <a href="{{ route('register') }}" class="text-ivoire-text/70 text-sm hover:text-beige-peau">
        ← Retour au choix du rôle
      </a>
    </div>

  </div>
</div>

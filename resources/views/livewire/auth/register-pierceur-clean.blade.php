<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
  <div class="max-w-2xl w-full">

    <!-- Header -->
    <div class="text-center mb-8">
      <a href="/" class="text-beige-peau font-Satoshi text-2xl font-bold">
        Ink&Pik
      </a>
      <h1 class="text-ivoire-text text-xl font-display font-bold mt-4 mb-2">
        Inscription Pierceur Professionnel
      </h1>
      <p class="text-ivoire-text/70 text-sm">
        SIRET obligatoire pour vous inscrire
      </p>
    </div>

    <!-- Formulaire -->
    <form wire:submit="register" class="bg-gris-fonde rounded-xl p-6 md:p-8 space-y-6">

      <!-- SECTION 1 : SIRET (PRIORITÉ) -->
      <div class="border-b border-titane/20 pb-6">
        <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
          1. Informations professionnelles
        </h2>

        <!-- SIRET avec validation -->
        <div>
          <label class="block text-ivoire-text text-sm font-semibold mb-2">
            Numéro SIRET * <span class="text-ivoire-text/50 font-normal">(14 chiffres)</span>
          </label>

          <div class="flex gap-2">
            <input
              type="text"
              wire:model="siret"
              maxlength="14"
              placeholder="12345678901234"
              class="flex-1 bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors font-mono"
              required
              @if($siret_valid) readonly @endif
            >

            @if(!$siret_valid)
            <button
              type="button"
              wire:click="validateSiret"
              wire:loading.attr="disabled"
              wire:target="validateSiret"
              class="px-6 py-3 bg-beige-peau hover:bg-beige-peau/90 disabled:bg-titane disabled:cursor-not-allowed text-noir-profond font-semibold rounded-lg transition-colors whitespace-nowrap">
              <span wire:loading.remove wire:target="validateSiret">Vérifier</span>
              <span wire:loading wire:target="validateSiret">...</span>
            </button>
            @else
            <div class="flex items-center gap-2 px-4 bg-vert-succes/20 border border-vert-succes rounded-lg">
              <svg class="w-5 h-5 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <span class="text-vert-succes font-semibold text-sm">Valide</span>
            </div>
            @endif
          </div>

          @error('siret')
            <p class="text-rouge-alerte text-xs mt-1 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ $message }}
            </p>
          @enderror

          @if(session()->has('siret_success'))
            <p class="text-vert-succes text-xs mt-1 flex items-center gap-1">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ session('siret_success') }}
            </p>
          @endif
        </div>

        <!-- Infos entreprise (auto-remplies après validation SIRET) -->
        @if($siret_valid)
        <div class="mt-4 p-4 bg-beige-peau/5 border border-beige-peau/30 rounded-lg">
          <p class="text-ivoire-text/70 text-xs mb-2">Informations récupérées :</p>
          <p class="text-ivoire-text font-semibold">{{ $company_name }}</p>
          <p class="text-ivoire-text/70 text-sm">{{ $company_address }}</p>
        </div>
        @endif
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
            <input
              type="text"
              wire:model="name"
              placeholder="Ex: Marie Durand ou MD Piercing"
              class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
              required
            >
            @error('name')
              <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Email -->
          <div>
            <label class="block text-ivoire-text text-sm font-semibold mb-2">
              Email professionnel *
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
            <input
              type="text"
              wire:model="city"
              class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
              required
            >
            @error('city')
              <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Code postal -->
          <div>
            <label class="block text-ivoire-text text-sm font-semibold mb-2">
              Code postal *
            </label>
            <input
              type="text"
              wire:model="postal_code"
              class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
              required
            >
            @error('postal_code')
              <p class="text-rouge-alerte text-xs mt-1">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- Téléphone -->
        <div class="mt-4">
          <label class="block text-ivoire-text text-sm font-semibold mb-2">
            Téléphone (optionnel)
          </label>
          <input
            type="tel"
            wire:model="phone"
            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
          >
        </div>
      </div>

      <!-- Submit -->
      <button
        type="submit"
        class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors">
        Créer mon compte pierceur
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

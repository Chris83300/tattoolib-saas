<div class="min-h-screen bg-noir-profond">
  
  <!-- Container principal -->
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <!-- Header -->
    <div class="mb-6">
      <a href="{{ route('client.profile') }}" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
        ← Retour à mon profil
      </a>
      <h1 class="text-beige-peau font-display text-2xl font-bold">
        Paramètres du compte
      </h1>
    </div>
    
    <!-- Formulaire paramètres -->
    <div class="bg-gris-fonde rounded-xl p-6 space-y-6">
      
      <!-- Informations personnelles -->
      <div>
        <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
          Informations personnelles
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-ivoire-text text-sm font-semibold mb-2">
              Prénom
            </label>
            <input 
              type="text" 
              value="{{ auth()->user()->name }}"
              disabled
              class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 opacity-60">
          </div>
          
          <div>
            <label class="block text-ivoire-text text-sm font-semibold mb-2">
              Email
            </label>
            <input 
              type="email" 
              value="{{ auth()->user()->email }}"
              disabled
              class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 opacity-60">
          </div>
        </div>
        
        <p class="text-ivoire-text/50 text-sm mt-2">
          Pour modifier vos informations personnelles, contactez le support.
        </p>
      </div>
      
      <!-- Sécurité -->
      <div class="border-t border-titane/20 pt-6">
        <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
          Sécurité
        </h2>
        
        <div class="space-y-3">
          <button class="w-full text-left px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-beige-peau transition-colors">
            <div class="flex items-center justify-between">
              <span class="text-ivoire-text">Changer le mot de passe</span>
              <svg class="w-5 h-5 text-ivoire-text/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </button>
          
          <button class="w-full text-left px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-beige-peau transition-colors">
            <div class="flex items-center justify-between">
              <span class="text-ivoire-text">Activer l'authentification à deux facteurs</span>
              <svg class="w-5 h-5 text-ivoire-text/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </button>
        </div>
      </div>
      
      <!-- Préférences -->
      <div class="border-t border-titane/20 pt-6">
        <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
          Préférences
        </h2>
        
        <div class="space-y-3">
          <div class="flex items-center justify-between">
            <span class="text-ivoire-text">Recevoir les notifications par email</span>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" checked class="sr-only peer">
              <div class="w-11 h-6 bg-noir-profond border border-titane/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-beige-peau"></div>
            </label>
          </div>
          
          <div class="flex items-center justify-between">
            <span class="text-ivoire-text">Afficher le profil publiquement</span>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" class="sr-only peer">
              <div class="w-11 h-6 bg-noir-profond border border-titane/30 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-beige-peau"></div>
            </label>
          </div>
        </div>
      </div>
      
      <!-- Danger zone -->
      <div class="border-t border-rouge-alerte/20 pt-6">
        <h2 class="text-rouge-alerte font-display font-bold text-lg mb-4">
          Zone de danger
        </h2>
        
        <div class="space-y-3">
          <button class="w-full text-left px-4 py-3 bg-noir-profond border border-rouge-alerte/30 rounded-lg hover:border-rouge-alerte transition-colors">
            <div class="flex items-center justify-between">
              <span class="text-rouge-alerte">Supprimer mon compte</span>
              <svg class="w-5 h-5 text-rouge-alerte/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </button>
        </div>
        
        <p class="text-ivoire-text/50 text-sm mt-2">
          La suppression de votre compte est irréversible.
        </p>
      </div>
      
    </div>
    
  </div>
  
</div>

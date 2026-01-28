<div class="min-h-screen bg-noir-profond">
  
  <!-- Container principal -->
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-beige-peau font-display text-2xl font-bold">
        Mes réservations
      </h1>
      <p class="text-ivoire-text/70 text-sm mt-2">
        Gérez vos rendez-vous et demandes de réservation
      </p>
    </div>
    
    <!-- Liste des réservations -->
    <div class="space-y-4">
      
      <!-- Exemple de réservation -->
      <div class="bg-gris-fonde rounded-xl p-6 border border-titane/20">
        <div class="flex items-start justify-between">
          <div class="flex-1">
            <div class="flex items-center gap-3 mb-3">
              <div class="w-12 h-12 bg-beige-peau/20 rounded-full flex items-center justify-center">
                <span class="text-beige-peau font-bold">JD</span>
              </div>
              <div>
                <h3 class="text-ivoire-text font-semibold">Jean Dupont</h3>
                <p class="text-ivoire-text/70 text-sm">Tatoueur professionnel</p>
              </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
              <div>
                <span class="text-ivoire-text/50 text-xs">Date</span>
                <p class="text-ivoire-text font-medium">15 Mars 2024</p>
              </div>
              <div>
                <span class="text-ivoire-text/50 text-xs">Heure</span>
                <p class="text-ivoire-text font-medium">14:00</p>
              </div>
              <div>
                <span class="text-ivoire-text/50 text-xs">Statut</span>
                <span class="inline-block px-2 py-1 bg-vert-succes/20 text-vert-succes text-xs rounded-full">
                  Confirmé
                </span>
              </div>
            </div>
            
            <div class="mt-4">
              <span class="text-ivoire-text/50 text-xs">Design</span>
              <p class="text-ivoire-text text-sm mt-1">Dragon tribal sur avant-bras</p>
            </div>
          </div>
          
          <div class="flex flex-col gap-2 ml-4">
            <button class="px-3 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond text-sm font-medium rounded-lg transition-colors">
              Voir détails
            </button>
            <button class="px-3 py-2 border border-titane/30 text-ivoire-text text-sm font-medium rounded-lg hover:border-beige-peau transition-colors">
              Contacter
            </button>
          </div>
        </div>
      </div>
      
    </div>
    
    <!-- Message si aucune réservation -->
    <div class="bg-gris-fonde rounded-xl p-12 text-center">
      <div class="w-16 h-16 bg-beige-peau/20 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
      </div>
      <h3 class="text-ivoire-text font-display font-bold text-lg mb-2">
        Aucune réservation
      </h3>
      <p class="text-ivoire-text/70 text-sm mb-4">
        Vous n'avez pas encore de réservation programmée.
      </p>
      <a href="/marketplace" class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-medium rounded-lg transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
        Trouver un artiste
      </a>
    </div>
    
  </div>
  
</div>

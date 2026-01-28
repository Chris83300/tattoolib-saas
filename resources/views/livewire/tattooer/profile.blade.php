<div class="min-h-screen bg-noir-profond">
  
  <div class="container mx-auto px-4 py-8 max-w-6xl">
    
    <!-- Header profil artiste -->
    <div class="bg-gris-fonde rounded-xl p-6 mb-8">
      <div class="flex flex-col md:flex-row md:items-center gap-6">
        
        <!-- Avatar -->
        <div class="flex-shrink-0">
          <div class="w-24 h-24 bg-titane/30 rounded-full flex items-center justify-center">
            <span class="text-2xl font-bold text-beige-peau">
              {{ substr(auth()->user()->name, 0, 2) }}
            </span>
          </div>
        </div>
        
        <!-- Infos -->
        <div class="flex-1">
          <h1 class="text-ivoire-text font-display font-bold text-3xl mb-2">
            {{ auth()->user()->name }}
          </h1>
          <p class="text-ivoire-text/70 mb-4">
            Tatoueur professionnel à {{ auth()->user()->tattooer?->city ?? 'Votre ville' }}
          </p>
          
          <!-- Statut -->
          <div class="flex flex-wrap gap-2">
            @if(auth()->user()->status === 'pending_verification')
              <div class="bg-ambre-warning/20 border border-ambre-warning text-ambre-warning px-3 py-1 rounded-lg text-sm font-semibold">
                ⏳ En attente de validation
              </div>
            @elseif(auth()->user()->status === 'active')
              <div class="bg-vert-succes/20 border border-vert-succes text-vert-succes px-3 py-1 rounded-lg text-sm font-semibold">
                ✓ Compte actif
              </div>
            @endif
            
            <div class="bg-noir-profond border border-titane/30 text-ivoire-text px-3 py-1 rounded-lg text-sm">
              Plan: {{ auth()->user()->tattooer?->current_plan ?? 'free' }}
            </div>
          </div>
        </div>
        
        <!-- Actions -->
        <div class="flex flex-col gap-2">
          <a href="{{ route('tattooer.dashboard') }}" 
             class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors text-center">
            Dashboard
          </a>
          <a href="{{ route('tattooer.settings') }}" 
             class="px-4 py-2 bg-gris-fonde hover:bg-titane/20 text-ivoire-text font-semibold rounded-lg transition-colors text-center">
            Paramètres
          </a>
        </div>
      </div>
    </div>
    
    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      
      <!-- Colonne principale -->
      <div class="lg:col-span-2 space-y-8">
        
        <!-- Bio -->
        <div class="bg-gris-fonde rounded-xl p-6">
          <h2 class="text-ivoire-text font-display font-bold text-xl mb-4">À propos</h2>
          <div class="text-ivoire-text/70 leading-relaxed">
            @if(auth()->user()->tattooer?->bio)
              {{ auth()->user()->tattooer->bio }}
            @else
              <p class="italic">Votre bio n'est pas encore renseignée.</p>
              <a href="{{ route('tattooer.settings') }}" class="text-beige-peau hover:underline">
                Ajouter une bio →
              </a>
            @endif
          </div>
        </div>
        
        <!-- Portfolio -->
        <div class="bg-gris-fonde rounded-xl p-6">
          <h2 class="text-ivoire-text font-display font-bold text-xl mb-4">Portfolio</h2>
          
          @if(auth()->user()->tattooer?->getMedia('portfolio')->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
              @foreach(auth()->user()->tattooer->getMedia('portfolio') as $media)
                <div class="aspect-square rounded-lg overflow-hidden bg-titane/20">
                  <img src="{{ $media->getUrl() }}" alt="Tatouage" class="w-full h-full object-cover">
                </div>
              @endforeach
            </div>
          @else
            <div class="text-center py-8">
              <div class="w-16 h-16 bg-titane/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
              </div>
              <p class="text-ivoire-text/70 mb-4">Aucune photo dans votre portfolio</p>
              <a href="{{ route('tattooer.settings') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajouter des photos
              </a>
            </div>
          @endif
        </div>
        
        <!-- Informations professionnelles -->
        <div class="bg-gris-fonde rounded-xl p-6">
          <h2 class="text-ivoire-text font-display font-bold text-xl mb-4">Informations professionnelles</h2>
          
          <div class="space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-titane/20">
              <span class="text-ivoire-text/70">SIRET</span>
              <span class="text-ivoire-text font-mono">{{ auth()->user()->tattooer?->siret ?? 'Non renseigné' }}</span>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-titane/20">
              <span class="text-ivoire-text/70">Ville</span>
              <span class="text-ivoire-text">{{ auth()->user()->tattooer?->city ?? 'Non renseignée' }}</span>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-titane/20">
              <span class="text-ivoire-text/70">Code postal</span>
              <span class="text-ivoire-text">{{ auth()->user()->tattooer?->postal_code ?? 'Non renseigné' }}</span>
            </div>
            
            <div class="flex justify-between items-center py-2 border-b border-titane/20">
              <span class="text-ivoire-text/70">Téléphone</span>
              <span class="text-ivoire-text">{{ auth()->user()->tattooer?->phone ?? 'Non renseigné' }}</span>
            </div>
            
            <div class="flex justify-between items-center py-2">
              <span class="text-ivoire-text/70">Email</span>
              <span class="text-ivoire-text">{{ auth()->user()->email }}</span>
            </div>
          </div>
        </div>
        
      </div>
      
      <!-- Colonne latérale -->
      <div class="space-y-8">
        
        <!-- Statistiques -->
        <div class="bg-gris-fonde rounded-xl p-6">
          <h3 class="text-ivoire-text font-display font-bold text-lg mb-4">Mes statistiques</h3>
          
          <div class="space-y-4">
            <div class="text-center">
              <div class="text-3xl font-bold text-beige-peau">0</div>
              <div class="text-ivoire-text/70 text-sm">Tatouages réalisés</div>
            </div>
            
            <div class="text-center">
              <div class="text-3xl font-bold text-beige-peau">0</div>
              <div class="text-ivoire-text/70 text-sm">Clients satisfaits</div>
            </div>
            
            <div class="text-center">
              <div class="text-3xl font-bold text-beige-peau">0</div>
              <div class="text-ivoire-text/70 text-sm">Demandes en attente</div>
            </div>
          </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="bg-gris-fonde rounded-xl p-6">
          <h3 class="text-ivoire-text font-display font-bold text-lg mb-4">Actions rapides</h3>
          
          <div class="space-y-3">
            <a href="{{ route('tattooer.booking-requests') }}" 
               class="w-full px-4 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors text-center block">
              Voir mes demandes
            </a>
            
            <a href="{{ route('tattooer.settings') }}" 
               class="w-full px-4 py-3 bg-gris-fonde hover:bg-titane/20 text-ivoire-text font-semibold rounded-lg transition-colors text-center block">
              Modifier mon profil
            </a>
            
            @if(!auth()->user()->tattooer?->has_compliance_badge)
              <a href="#" 
               class="w-full px-4 py-3 bg-vert-succes/20 hover:bg-vert-succes/30 text-vert-succes font-semibold rounded-lg transition-colors text-center block">
                Obtenir badge conformité
              </a>
            @endif
          </div>
        </div>
        
        <!-- Lien profil public -->
        <div class="bg-gris-fonde rounded-xl p-6">
          <h3 class="text-ivoire-text font-display font-bold text-lg mb-4">Mon profil public</h3>
          
          @if(auth()->user()->tattooer?->slug)
            <div class="space-y-3">
              <div class="text-center">
                <a href="/marketplace/{{ auth()->user()->tattooer->slug }}" 
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                  </svg>
                  Voir mon profil
                </a>
              </div>
              <p class="text-ivoire-text/50 text-xs text-center">
                /marketplace/{{ auth()->user()->tattooer->slug }}
              </p>
            </div>
          @else
            <p class="text-ivoire-text/70 text-center">
              Votre profil public sera disponible une fois votre compte validé
            </p>
          @endif
        </div>
        
      </div>
      
    </div>
    
  </div>
</div>

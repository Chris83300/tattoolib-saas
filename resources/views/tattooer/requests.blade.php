@extends('layouts.tattooer')

@section('content')
<div class="space-y-6">
    
    <!-- Header + Filtres -->
    <div class="bg-gris-fonde rounded-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-ivoire-text mb-2">Demandes de projet</h1>
                <p class="text-ivoire-text/70">Gérez vos demandes de réservation</p>
            </div>
        </div>
        
        <!-- Filtres -->
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" 
                       id="search-client"
                       placeholder="Rechercher un client..."
                       class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:outline-none">
            </div>
            
            <select id="filter-status" 
                    class="px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                <option value="">Tous les statuts</option>
                <option value="pending">En attente</option>
                <option value="accepted">Acceptées</option>
                <option value="in_progress">En cours</option>
                <option value="completed">Terminées</option>
                <option value="cancelled">Annulées</option>
            </select>
        </div>
    </div>
    
    <!-- Stats rapides -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $statusCounts = $requests->groupBy('status')->map->count();
        @endphp
        
        <div class="bg-gris-fonde rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-ambre-warning mb-1">
                {{ $statusCounts->get('pending', 0) }}
            </div>
            <div class="text-ivoire-text/60 text-xs">En attente</div>
        </div>
        
        <div class="bg-gris-fonde rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-beige-peau mb-1">
                {{ $statusCounts->get('accepted', 0) }}
            </div>
            <div class="text-ivoire-text/60 text-xs">Acceptées</div>
        </div>
        
        <div class="bg-gris-fonde rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-vert-succes mb-1">
                {{ $statusCounts->get('in_progress', 0) }}
            </div>
            <div class="text-ivoire-text/60 text-xs">En cours</div>
        </div>
        
        <div class="bg-gris-fonde rounded-xl p-4 text-center">
            <div class="text-2xl font-bold text-ivoire-text/50 mb-1">
                {{ $statusCounts->get('completed', 0) }}
            </div>
            <div class="text-ivoire-text/60 text-xs">Terminées</div>
        </div>
    </div>
    
    <!-- Liste des demandes -->
    <div class="space-y-4">
        @forelse($requests as $request)
            <div class="bg-gris-fonde rounded-xl p-6 hover:ring-2 hover:ring-beige-peau/50 transition-all">
                <div class="flex flex-col md:flex-row md:items-start gap-4">
                    
                    <!-- Avatar client -->
                    <div class="w-16 h-16 rounded-full bg-beige-peau/20 flex-shrink-0 flex items-center justify-center text-2xl">
                        {{ substr($request->client->first_name, 0, 1) }}{{ substr($request->client->last_name, 0, 1) }}
                    </div>
                    
                    <!-- Infos demande -->
                    <div class="flex-1">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-2 mb-3">
                            <div>
                                <h3 class="text-lg font-semibold text-ivoire-text mb-1">
                                    {{ $request->client->first_name }} {{ $request->client->last_name }}
                                </h3>
                                <p class="text-ivoire-text/70 text-sm">
                                    {{ $request->client->email }} • {{ $request->client->phone }}
                                </p>
                            </div>
                            
                            <!-- Badge statut -->
                            <span class="px-3 py-1 rounded-full text-xs font-semibold inline-block
                                {{ match($request->status) {
                                    'pending' => 'bg-ambre-warning/20 text-ambre-warning',
                                    'accepted' => 'bg-beige-peau/20 text-beige-peau',
                                    'in_progress' => 'bg-vert-succes/20 text-vert-succes',
                                    'completed' => 'bg-ivoire-text/20 text-ivoire-text',
                                    'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                    default => 'bg-titane/20 text-ivoire-text/60'
                                } }}">
                                {{ match($request->status) {
                                    'pending' => '⏳ En attente',
                                    'accepted' => '✓ Acceptée',
                                    'in_progress' => '🎨 En cours',
                                    'completed' => '✅ Terminée',
                                    'cancelled' => '❌ Annulée',
                                    default => $request->status
                                } }}
                            </span>
                        </div>
                        
                        <!-- Description projet -->
                        <div class="mb-3">
                            <p class="text-ivoire-text/80 line-clamp-2">
                                <strong>Projet :</strong> {{ $request->tattoo_description }}
                            </p>
                        </div>
                        
                        <!-- Détails -->
                        <div class="flex flex-wrap gap-4 text-sm text-ivoire-text/60 mb-4">
                            <span>📍 {{ $request->tattoo_location }}</span>
                            @if($request->tattoo_style)
                                <span>🎨 {{ $request->tattoo_style }}</span>
                            @endif
                            @if($request->estimated_price)
                                <span>💰 {{ number_format($request->estimated_price, 0) }}€</span>
                            @endif
                            @if($request->proposed_date)
                                <span>📅 Proposé : {{ $request->proposed_date->format('d/m/Y') }}</span>
                            @endif
                            <span>🕒 {{ $request->created_at->diffForHumans() }}</span>
                        </div>
                        
                        <!-- Images référence (si présentes) -->
                        @if($request->getMedia('reference_images')->isNotEmpty())
                            <div class="flex gap-2 mb-4">
                                @foreach($request->getMedia('reference_images')->take(4) as $media)
                                    <img src="{{ $media->getUrl('thumb') }}" 
                                         alt="Référence"
                                         class="w-16 h-16 rounded-lg object-cover cursor-pointer hover:ring-2 hover:ring-beige-peau"
                                         onclick="openLightbox('{{ $media->getUrl() }}')">
                                @endforeach
                                @if($request->getMedia('reference_images')->count() > 4)
                                    <div class="w-16 h-16 rounded-lg bg-noir-profond flex items-center justify-center text-ivoire-text/60 text-xs">
                                        +{{ $request->getMedia('reference_images')->count() - 4 }}
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <!-- Actions -->
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('tattooer.request.show', $request) }}" 
                               class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                Voir détails
                            </a>
                            
                            @if($request->status === 'pending')
                                <form action="{{ route('tattooer.request-accept', $request) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors"
                                            onclick="return confirm('Accepter cette demande ?')">
                                        ✓ Accepter
                                    </button>
                                </form>
                                
                                <button onclick="openRejectModal({{ $request->id }})"
                                        class="px-4 py-2 bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30 transition-colors">
                                    ✕ Refuser
                                </button>
                            @endif
                            
                            @if($request->status === 'accepted')
                                <a href="{{ route('tattooer.request.show', $request) }}#deposit" 
                                   class="px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                    💰 Demander acompte
                                </a>
                            @endif
                            
                            @if(in_array($request->status, ['accepted', 'in_progress']))
                                <a href="{{ route('tattooer.message.show', $request) }}" 
                                   class="px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold hover:bg-noir-profond/80 transition-colors">
                                    💬 Chat
                                    @php
                                        $unreadCount = $request->messages()->where('sender_type', 'client')->count();
                                    @endphp
                                    @if($unreadCount > 0)
                                        <span class="ml-1 bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-gris-fonde rounded-xl p-12 text-center">
                <svg class="w-20 h-20 mx-auto mb-4 text-ivoire-text/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucune demande</h3>
                <p class="text-ivoire-text/60">Vous n'avez pas encore reçu de demande de projet.</p>
            </div>
        @endforelse
    </div>
    
    <!-- Pagination -->
    <div class="flex justify-center">
        {{ $requests->links() }}
    </div>
    
</div>

<!-- Modal Refus -->
<div id="reject-modal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
    <div class="bg-gris-fonde rounded-xl p-6 max-w-md w-full">
        <h3 class="text-xl font-bold text-ivoire-text mb-4">Refuser la demande</h3>
        <form id="reject-form" method="POST">
            @csrf
            <textarea name="rejection_reason" 
                      rows="4"
                      placeholder="Raison du refus (optionnel, sera envoyée au client)..."
                      class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:outline-none mb-4"></textarea>
            
            <div class="flex gap-3">
                <button type="submit" 
                        class="flex-1 px-4 py-2 bg-rouge-alerte text-noir-profond rounded-lg font-semibold hover:bg-rouge-alerte/90">
                    Confirmer le refus
                </button>
                <button type="button" 
                        onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold hover:bg-noir-profond/80">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRejectModal(requestId) {
    document.getElementById('reject-form').action = `/tattooer/requests/${requestId}/reject`;
    document.getElementById('reject-modal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('reject-modal').classList.add('hidden');
}

function openLightbox(imageUrl) {
    // Simple lightbox implementation
    const lightbox = document.createElement('div');
    lightbox.className = 'fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4';
    lightbox.innerHTML = `
        <img src="${imageUrl}" class="max-w-full max-h-full rounded-lg">
        <button onclick="this.parentElement.remove()" class="absolute top-4 right-4 text-white text-2xl">×</button>
    `;
    document.body.appendChild(lightbox);
}
</script>
@endpush
@endsection

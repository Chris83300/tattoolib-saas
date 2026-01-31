@extends('layouts.tattooer')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                Clients
            </h1>
            <p class="text-ivoire-text/70">
                Gérez vos clients et leur historique
            </p>
        </div>
        
        <!-- Recherche -->
        <form action="{{ route('tattooer.clients') }}" method="GET" class="flex gap-3">
            <div class="relative flex-1 max-w-md">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Rechercher un client..."
                       class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-ivoire-text/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <button type="submit" class="px-4 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                🔍 Rechercher
            </button>
        </form>
    </div>
    
    <!-- Liste des clients -->
    <div class="bg-gris-fonde rounded-xl p-6">
        @if($clients->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($clients as $client)
                    <a href="{{ route('tattooer.client.show', $client->id) }}" 
                       class="block p-6 bg-noir-profond rounded-xl hover:bg-noir-profond/80 transition-all hover:scale-105">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-beige-peau/20 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-beige-peau" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-ivoire-text">
                                        {{ $client->first_name }} {{ $client->last_name }}
                                    </h3>
                                    @if($client->phone)
                                        <p class="text-sm text-ivoire-text/60">{{ $client->phone }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="text-right">
                                <span class="inline-block px-2 py-1 bg-beige-peau/20 text-beige-peau rounded-full text-xs font-semibold">
                                    {{ $client->tattoos_count ?? 0 }} tattoos
                                </span>
                            </div>
                        </div>
                        
                        @if($client->tattooHistory->count() > 0)
                            @php
                                $lastTattoo = $client->tattooHistory->first();
                            @endphp
                            <div class="mb-4">
                                <p class="text-sm text-ivoire-text/70 mb-1">Dernier tattoo</p>
                                <p class="text-sm text-ivoire-text">
                                    {{ $lastTattoo->tattoo_date->format('d/m/Y') }}
                                    @if($lastTattoo->tattoo_location)
                                        - {{ $lastTattoo->tattoo_location }}
                                    @endif
                                </p>
                            </div>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <div class="flex gap-2">
                                @if($client->email)
                                    <a href="mailto:{{ $client->email }}" 
                                       class="p-2 bg-noir-profond/50 rounded-lg hover:bg-noir-profond transition-colors"
                                       onclick="event.stopPropagation()">
                                        <svg class="w-4 h-4 text-ivoire-text/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0l7.89-5.26a2 2 0 002.22 0L3 8zm0 0l7.89 5.26a2 2 0 002.22 0L3 8zm0 0L3 8l7.89 5.26a2 2 0 002.22 0L3 8z"></path>
                                        </svg>
                                    </a>
                                @endif
                                
                                @if($client->phone)
                                    <a href="tel:{{ $client->phone }}" 
                                       class="p-2 bg-noir-profond/50 rounded-lg hover:bg-noir-profond transition-colors"
                                       onclick="event.stopPropagation()">
                                        <svg class="w-4 h-4 text-ivoire-text/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 2.493a1 1 0 01.684.948l1.498-2.493a1 1 0 00.684-.948H19a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5l6 6m0 0l6 6m-6-6v12"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            
                            @if($client->is_blacklisted)
                                <span class="px-2 py-1 bg-rouge-alerte/20 text-rouge-alerte rounded-full text-xs font-semibold">
                                    ⛔ Blacklisté
                                </span>
                            @elseif($client->no_show_count > 2)
                                <span class="px-2 py-1 bg-ambre-warning/20 text-ambre-warning rounded-full text-xs font-semibold">
                                    ⚠️ {{ $client->no_show_count }} no-shows
                                </span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                {{ $clients->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0A15.003 15.003 0 0010.607 7.055M17 20H7m0 0a5.002 5.002 0 00-9.288 0A15.003 15.003 0 0013.393 12.945m0 0V12A15.003 15.003 0 0010.607 7.055m0 0a5.002 5.002 0 009.288 0A15.003 15.003 0 006.607 12.945m0 0V12a15.003 15.003 0 003.393 12.945"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                    Aucun client
                </h3>
                <p class="text-ivoire-text/60">
                    Vous n'avez pas encore de clients enregistrés.
                </p>
                @if(request('search'))
                    <p class="text-sm text-ivoire-text/50 mt-2">
                        Aucun résultat pour "{{ request('search') }}"
                    </p>
                @endif
            </div>
        @endif
    </div>
    
</div>

@push('scripts')
<script>
// Gestion de la recherche en temps réel (optionnel)
let searchTimeout;
const searchInput = document.querySelector('input[name="search"]');

if (searchInput) {
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            this.form.submit();
        }, 500);
    });
}
</script>
@endpush
@endsection

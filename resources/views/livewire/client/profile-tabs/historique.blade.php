<div class="bg-gris-fonde rounded-xl border border-beige-peau/20 shadow-lg p-6">
    <h2 class="text-xl font-bold text-ivoire-text mb-6">Historique complet</h2>
    
    @if($this->client->bookingRequests->where('status', 'completed')->count() > 0)
        <div class="space-y-4">
            @foreach($this->client->bookingRequests->where('status', 'completed') as $booking)
            <div class="p-6 bg-noir-profond rounded-xl border border-beige-peau/10">
                <div class="flex items-start gap-4">
                    <img src="{{ $booking->bookable->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png') }}" 
                         alt="Artiste" 
                         class="w-16 h-16 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold text-ivoire-text">{{ $booking->bookable->user->name }}</h3>
                            <span class="text-sm text-vert-succes font-semibold">✅ Terminé</span>
                        </div>
                        <p class="text-ivoire-text/80 mb-2">{{ $booking->tattoo_description }}</p>
                        <div class="flex items-center gap-4 text-sm text-ivoire-text/60">
                            <span>📅 {{ $booking->appointment_datetime?->format('d/m/Y') ?? 'Date non définie' }}</span>
                            <span>💰 {{ $booking->estimated_price }}€</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-ivoire-text/20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-ivoire-text/60">Aucun projet terminé</p>
        </div>
    @endif
</div>

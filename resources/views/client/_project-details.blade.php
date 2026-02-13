{{-- client/_project-details.blade.php --}}
<div class="space-y-2 text-sm">
    @if ($bookingRequest->body_zone)
        <div class="flex justify-between">
            <span class="text-ivoire-text/70">Emplacement:</span>
            <span class="text-ivoire-text">{{ $bookingRequest->body_zone }}</span>
        </div>
    @endif
    @if ($bookingRequest->tattoo_size)
        <div class="flex justify-between">
            <span class="text-ivoire-text/70">Taille:</span>
            <span class="text-ivoire-text">{{ $bookingRequest->tattoo_size }}</span>
        </div>
    @endif
    @if ($bookingRequest->tattoo_style)
        <div class="flex justify-between">
            <span class="text-ivoire-text/70">Style:</span>
            <span class="text-ivoire-text">{{ $bookingRequest->tattoo_style }}</span>
        </div>
    @endif
    @if ($bookingRequest->price_range_min && $bookingRequest->price_range_max)
        <div class="flex justify-between">
            <span class="text-ivoire-text/70">Estimation tattoo:</span>
            <span class="text-ivoire-text">{{ number_format($bookingRequest->price_range_min, 0) }}€ - {{ number_format($bookingRequest->price_range_max, 0) }}€</span>
        </div>
    @elseif ($bookingRequest->estimated_total_price)
        <div class="flex justify-between">
            <span class="text-ivoire-text/70">Estimation tattoo:</span>
            <span class="text-ivoire-text">{{ number_format($bookingRequest->estimated_total_price, 0) }}€</span>
        </div>
    @endif
    <div class="flex justify-between">
        <span class="text-ivoire-text/70">Statut:</span>
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
            {{ match ($bookingRequest->status) {
                'pending' => 'bg-ambre-warning/20 text-ambre-warning',
                'accepted' => 'bg-vert-succes/20 text-vert-succes',
                'awaiting_deposit' => 'bg-vert-succes/20 text-vert-succes',
                'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                'in_progress' => 'bg-beige-peau/20 text-beige-peau',
                'completed' => 'bg-vert-succes/20 text-vert-succes',
                'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                default => 'bg-gris-fonde/20 text-ivoire-text',
            } }}">
            {{ match ($bookingRequest->status) {
                'pending' => '⏳ En attente',
                'accepted' => '✅ Acceptée',
                'awaiting_deposit' => '⏳ Acompte attendu',
                'deposit_paid' => '💰 Acompte payé',
                'in_progress' => '🎨 En cours',
                'completed' => '✅ Terminé',
                'cancelled' => '❌ Annulée',
                default => ucfirst($bookingRequest->status->value),
            } }}
        </span>
    </div>
</div>

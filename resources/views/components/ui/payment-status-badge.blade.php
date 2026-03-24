@props(['booking'])

@php
    $balancePaid     = $booking->balance_paid_at !== null;
    $balanceRequested = $booking->balance_requested_at !== null;
    $depositPaid     = $booking->deposit_paid_at !== null;
    $remaining       = $booking->balance_remaining ?? 0;
    $depositAmount   = $booking->total_deposit_amount ?? 0;
@endphp

@if ($balancePaid)
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-vert-succes/15 text-vert-succes rounded-lg text-xs font-semibold">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Solde payé
    </span>
@elseif ($balanceRequested)
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-orange-terre-cuite/15 text-orange-terre-cuite rounded-lg text-xs font-semibold">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Solde à payer ({{ number_format($remaining, 2, ',', ' ') }} €)
    </span>
@elseif ($depositPaid)
    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-vert-succes/15 text-vert-succes rounded-lg text-xs font-semibold">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Acompte payé ({{ number_format($depositAmount, 2, ',', ' ') }} €)
    </span>
@endif

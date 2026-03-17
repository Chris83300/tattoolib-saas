@props(['booking', 'cancelledBy' => 'client'])

@php
    $refundInfo = app(\App\Services\CancellationService::class)
                    ->calculateRefund($booking, $cancelledBy);
    $cancelRoute = $cancelledBy === 'artist'
        ? route('tattooer.requests.cancel', $booking)
        : route('client.requests.cancel', $booking);
@endphp

<div x-data="{ open: false }" @open-cancel-modal-{{ $booking->id }}.window="open = true">

    {{-- Bouton déclencheur --}}
    <button @click="open = true"
        class="px-3 py-1.5 text-sm text-rouge-alerte border border-rouge-alerte/30 rounded-lg
               hover:bg-rouge-alerte/10 transition flex items-center gap-1.5">
        ✕ Annuler
    </button>

    {{-- Modal --}}
    <div x-show="open" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4">
        <div class="bg-noir-profond rounded-2xl shadow-2xl w-full max-w-lg border border-titane/20">
            <div class="p-6">
                <h2 class="text-xl font-bold mb-1 text-ivoire-text">Annuler la demande</h2>
                <p class="text-sm text-ivoire-text/60 mb-5">
                    Ref. #{{ $booking->id }}
                    @if($booking->description)
                        — {{ Str::limit($booking->description, 60) }}
                    @endif
                </p>

                {{-- Conditions de remboursement --}}
                <div class="rounded-xl border p-4 mb-5 {{ $refundInfo['refund_percent'] > 0 ? 'bg-vert-succes/10 border-vert-succes/30' : 'bg-rouge-alerte/10 border-rouge-alerte/30' }}">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl mt-0.5">
                            {{ $refundInfo['refund_percent'] > 0 ? '💰' : '⚠️' }}
                        </span>
                        <div class="flex-1">
                            <p class="font-semibold text-sm {{ $refundInfo['refund_percent'] > 0 ? 'text-vert-succes' : 'text-rouge-alerte' }}">
                                @if ($refundInfo['refund_percent'] === 100)
                                    Remboursement intégral : {{ number_format($refundInfo['refund_amount'], 2, ',', ' ') }} €
                                @elseif ($refundInfo['refund_percent'] > 0)
                                    Remboursement partiel : {{ number_format($refundInfo['refund_amount'], 2, ',', ' ') }} €
                                    ({{ $refundInfo['refund_percent'] }}% de l'acompte)
                                @else
                                    Aucun remboursement
                                @endif
                            </p>
                            <p class="text-xs mt-1 {{ $refundInfo['refund_percent'] > 0 ? 'text-vert-succes/80' : 'text-rouge-alerte/80' }}">
                                {{ $refundInfo['reason'] }}
                            </p>

                            @if ($booking->total_deposit_amount > 0)
                            <div class="mt-2 text-xs space-y-0.5 {{ $refundInfo['refund_percent'] > 0 ? 'text-vert-succes/70' : 'text-rouge-alerte/70' }}">
                                <div class="flex justify-between">
                                    <span>Acompte versé</span>
                                    <span>{{ number_format($booking->total_deposit_amount, 2, ',', ' ') }} €</span>
                                </div>
                                @if ($refundInfo['refund_amount'] < $booking->total_deposit_amount)
                                <div class="flex justify-between">
                                    <span>Retenu par l'artiste</span>
                                    <span>{{ number_format($booking->total_deposit_amount - $refundInfo['refund_amount'], 2, ',', ' ') }} €</span>
                                </div>
                                @endif
                                <div class="flex justify-between font-semibold border-t border-current/20 pt-1 mt-1">
                                    <span>Vous recevrez</span>
                                    <span>{{ number_format($refundInfo['refund_amount'], 2, ',', ' ') }} €</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Formulaire --}}
                <form method="POST" action="{{ $cancelRoute }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-ivoire-text mb-1">
                            Message d'annulation
                            <span class="text-ivoire-text/40 font-normal">(optionnel)</span>
                        </label>
                        <textarea
                            name="cancellation_message"
                            rows="3"
                            placeholder="Expliquez la raison de votre annulation..."
                            class="w-full bg-gris-fonde border border-titane/30 rounded-lg px-3 py-2 text-sm text-ivoire-text
                                   focus:outline-none focus:ring-2 focus:ring-beige-peau/30 resize-none placeholder:text-ivoire-text/30">
                        </textarea>
                    </div>

                    <p class="text-xs text-ivoire-text/40 mb-4">
                        Cette annulation sera transmise à l'équipe Ink&amp;Pik pour traitement.
                        @if ($refundInfo['can_refund'] && $refundInfo['refund_amount'] > 0)
                        Le remboursement sera traité sous 5-10 jours ouvrés.
                        @endif
                    </p>

                    <div class="flex gap-3">
                        <button type="button" @click="open = false"
                            class="flex-1 px-4 py-2 border border-titane/30 rounded-lg text-sm text-ivoire-text
                                   hover:bg-titane/10 transition">
                            Retour
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-2 bg-rouge-alerte text-white rounded-lg text-sm
                                   font-medium hover:bg-rouge-alerte/80 transition">
                            Confirmer l'annulation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

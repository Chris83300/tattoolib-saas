<div>
    @if ($show && $bookingRequest)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-data x-transition>

            {{-- Overlay --}}
            <div class="fixed inset-0 bg-noir-profond/70" wire:click="closeModal"></div>

            {{-- Modal --}}
            <div class="relative bg-gris-fonde rounded-2xl shadow-xl border border-titane/20 w-full max-w-md z-10">

                {{-- Header --}}
                <div class="p-5 border-b border-titane/10">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-beige-peau">
                            Demande de paiement du solde
                        </h3>
                        <button wire:click="closeModal" class="text-titane hover:text-ivoire-text transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <p class="text-sm text-ivoire-text/60 mt-1">
                        Client : {{ $bookingRequest->client?->user?->name ?? 'Client' }}
                    </p>
                </div>

                {{-- Body --}}
                <div class="p-5 space-y-5">

                    {{-- Récap booking --}}
                    <div class="p-3 bg-noir-profond/30 rounded-xl text-sm">
                        @if ($bookingRequest->body_zone ?? false)
                            <div class="flex justify-between text-ivoire-text/60">
                                <span>Zone</span>
                                <span class="text-titane">{{ $bookingRequest->body_zone }}</span>
                            </div>
                        @endif
                        @if ($bookingRequest->tattoo_size ?? false)
                            <div class="flex justify-between text-ivoire-text/60 mt-1">
                                <span>Taille</span>
                                <span class="text-titane">{{ $bookingRequest->tattoo_size }}</span>
                            </div>
                        @endif
                        @if ($estimatedPrice > 0)
                            <div class="flex justify-between text-ivoire-text/60 mt-1">
                                <span>Prix estimé initial</span>
                                <span class="text-titane">{{ number_format($estimatedPrice, 2, ',', ' ') }} €</span>
                            </div>
                        @endif
                    </div>

                    {{-- Input prix définitif --}}
                    <div>
                        <label class="block text-sm font-medium text-ivoire-text/80 mb-2">
                            Prix définitif (€)
                        </label>
                        <input type="number"
                               wire:model.live.debounce.300ms="finalPrice"
                               step="0.01"
                               min="1"
                               placeholder="Ex: 350.00"
                               class="w-full bg-noir-profond border border-titane/20 rounded-xl px-4 py-3
                                      text-xl text-center text-beige-peau font-bold
                                      focus:outline-none focus:ring-2 focus:ring-beige-peau/40 focus:border-beige-peau/40
                                      placeholder:text-titane/30">
                        @error('finalPrice')
                            <p class="text-rouge-alerte text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Récap financier --}}
                    <div class="p-4 bg-noir-profond/50 rounded-xl space-y-2">
                        <div class="flex justify-between text-sm text-ivoire-text/60">
                            <span>Prix définitif</span>
                            <span class="text-titane font-medium">
                                {{ $finalPrice ? number_format((float) $finalPrice, 2, ',', ' ') : '—' }} €
                            </span>
                        </div>

                        @if ($depositAmount > 0)
                            <div class="flex justify-between text-sm text-ivoire-text/60">
                                <span>Acompte versé</span>
                                <span class="text-vert-succes font-medium">
                                    - {{ number_format($depositAmount, 2, ',', ' ') }} €
                                </span>
                            </div>
                        @endif

                        <div class="border-t border-titane/10 pt-2">
                            <div class="flex justify-between">
                                <span class="text-sm font-semibold text-ivoire-text">Reste à payer</span>
                                <span class="text-xl font-bold text-beige-peau">
                                    {{ number_format($remainingBalance, 2, ',', ' ') }} €
                                </span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-ivoire-text/40 text-center">
                        Le client recevra un message et un email avec le détail et un lien de paiement sécurisé (Stripe).
                    </p>
                </div>

                {{-- Footer --}}
                <div class="p-5 border-t border-titane/10 flex gap-3">
                    <button wire:click="closeModal"
                            class="flex-1 py-3 border border-titane/20 text-titane rounded-xl
                                   text-sm hover:bg-titane/5 transition">
                        Annuler
                    </button>
                    <button wire:click="submitBalanceRequest"
                            wire:loading.attr="disabled"
                            @if(!$finalPrice || (float)$finalPrice <= 0) disabled @endif
                            class="flex-1 py-3 bg-beige-peau text-noir-profond rounded-xl
                                   text-sm font-semibold hover:bg-beige-peau/90 transition
                                   disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="submitBalanceRequest">
                            Envoyer la demande
                        </span>
                        <span wire:loading wire:target="submitBalanceRequest">
                            Envoi en cours...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

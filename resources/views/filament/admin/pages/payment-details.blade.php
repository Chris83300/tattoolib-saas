<div class="space-y-6">
    <!-- Informations principales -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations du paiement</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">ID du paiement</p>
                <p class="font-medium">{{ $payment->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Montant</p>
                <p class="font-medium text-lg">{{ number_format($payment->amount, 2) }} €</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Type de paiement</p>
                <p class="font-medium">
                    @if($payment->payment_type === 'deposit')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            Acompte
                        </span>
                    @elseif($payment->payment_type === 'full_payment')
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Paiement complet
                        </span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ ucfirst($payment->payment_type) }}
                        </span>
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Statut</p>
                <p class="font-medium">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        Succès
                    </span>
                </p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Date de paiement</p>
                <p class="font-medium">{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Artiste</p>
                <p class="font-medium">{{ $payment->recipient_name ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Informations Stripe -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations Stripe</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">Payment Intent ID</p>
                <p class="font-mono text-sm bg-gray-50 p-2 rounded">{{ $payment->stripe_payment_intent_id }}</p>
            </div>
            @if($payment->stripe_charge_id)
            <div>
                <p class="text-sm text-gray-500">Charge ID</p>
                <p class="font-mono text-sm bg-gray-50 p-2 rounded">{{ $payment->stripe_charge_id }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Informations client et demande -->
    @if($payment->bookingRequest)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informations de la demande</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-500">ID de la demande</p>
                <p class="font-medium">#{{ $payment->bookingRequest->id }}</p>
            </div>
            @if($payment->bookingRequest->user)
            <div>
                <p class="text-sm text-gray-500">Client</p>
                <p class="font-medium">{{ $payment->bookingRequest->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $payment->bookingRequest->user->email }}</p>
            </div>
            @endif
            @if($payment->bookingRequest->tattooer)
            <div>
                <p class="text-sm text-gray-500">Tatoueur</p>
                <p class="font-medium">{{ $payment->bookingRequest->tattooer->first_name }} {{ $payment->bookingRequest->tattooer->last_name }}</p>
            </div>
            @endif
            @if($payment->bookingRequest->piercer)
            <div>
                <p class="text-sm text-gray-500">Piercer</p>
                <p class="font-medium">{{ $payment->bookingRequest->piercer->first_name }} {{ $payment->bookingRequest->piercer->last_name }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Métadonnées -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Métadonnées système</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Créé le</p>
                <p class="font-medium">{{ $payment->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <p class="text-gray-500">Modifié le</p>
                <p class="font-medium">{{ $payment->updated_at->format('d/m/Y H:i:s') }}</p>
            </div>
        </div>
    </div>
</div>

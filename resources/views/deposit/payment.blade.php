@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h1 class="text-2xl font-bold text-[#0A0A0A]">Payer l'acompte</h1>
                    <a href="{{ route('client.booking-requests.show', $bookingRequest->id) }}"
                        class="text-gray-600 hover:text-gray-800">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                </div>

                <!-- Résumé du projet -->
                <div class="bg-[#D4B59E]/10 rounded-lg p-4">
                    <h2 class="font-semibold text-[#0A0A0A] mb-2">Résumé du projet</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Artiste:</span>
                            <p class="font-medium">{{ $bookingRequest->bookable->user->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Description:</span>
                            <p class="font-medium">{{ $bookingRequest->tattoo_description }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Emplacement:</span>
                            <p class="font-medium">{{ $bookingRequest->tattoo_location }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Date du RDV:</span>
                            <p class="font-medium">
                                @if ($bookingRequest->appointment_datetime)
                                    {{ $bookingRequest->appointment_datetime->format('d/m/Y à H:i') }}
                                @else
                                    À définir
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails du paiement -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <h2 class="text-xl font-semibold text-[#0A0A0A] mb-4">Détails du paiement</h2>

                <div class="space-y-4">
                    <!-- Prix total -->
                    <div class="flex justify-between items-center py-3 border-b">
                        <span class="text-gray-600">Prix total estimé</span>
                        <span class="font-semibold text-lg">{{ number_format($bookingRequest->estimated_price, 2) }}€</span>
                    </div>

                    <!-- Acompte -->
                    <div class="flex justify-between items-center py-3 border-b">
                        <div>
                            <span class="text-gray-600">Acompte à payer</span>
                            <p class="text-xs text-gray-500">
                                {{ round(($bookingRequest->deposit_amount / $bookingRequest->estimated_price) * 100, 1) }}%
                                du total
                            </p>
                        </div>
                        <span
                            class="font-semibold text-lg text-[#D4B59E]">{{ number_format($bookingRequest->deposit_amount, 2) }}€</span>
                    </div>

                    <!-- Reste à payer -->
                    <div class="flex justify-between items-center py-3">
                        <span class="text-gray-600">Reste à payer le jour du RDV</span>
                        <span class="font-semibold text-lg">
                            {{ number_format($bookingRequest->estimated_price - $bookingRequest->deposit_amount, 2) }}€
                        </span>
                    </div>
                </div>

                <!-- Images de référence -->
                @if ($bookingRequest->getMedia('reference_images')->count() > 0)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Images de référence</h3>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach ($bookingRequest->getMedia('reference_images')->take(3) as $media)
                                <img src="{{ $media->getUrl('thumbnail') }}" alt="Reference"
                                    class="w-full h-20 object-cover rounded">
                            @endforeach
                            @if ($bookingRequest->getMedia('reference_images')->count() > 3)
                                <div
                                    class="w-full h-20 bg-gray-200 rounded flex items-center justify-center text-sm text-gray-600">
                                    +{{ $bookingRequest->getMedia('reference_images')->count() - 3 }}
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Zone de paiement -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="text-center">
                    <!-- Bouton de paiement -->
                    <button id="checkout-button"
                        class="w-full md:w-auto px-8 py-3 bg-[#D4B59E] text-white font-semibold rounded-lg hover:bg-[#C4A68E] transition-colors disabled:opacity-50">
                        <span id="button-text">Payer l'acompte de
                            {{ number_format($bookingRequest->total_deposit_amount, 2) }}€</span>
                        <span id="button-loading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Traitement en cours...
                        </span>
                    </button>

                    <!-- Informations de sécurité -->
                    <div class="mt-6 flex items-center justify-center space-x-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            Paiement sécurisé
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            SSL/TLS
                        </div>
                    </div>

                    <!-- Méthodes de paiement acceptées -->
                    <div class="mt-4">
                        <p class="text-xs text-gray-500 mb-2">Méthodes de paiement acceptées:</p>
                        <div class="flex items-center justify-center space-x-2">
                            <!-- Cartes bancaires -->
                            <div class="flex items-center space-x-1 text-gray-400">
                                <svg class="w-8 h-5" viewBox="0 0 24 16" fill="currentColor">
                                    <rect x="1" y="4" width="22" height="12" rx="2" stroke="currentColor"
                                        stroke-width="2" fill="none" />
                                    <rect x="1" y="7" width="22" height="3" fill="currentColor" />
                                </svg>
                                <span class="text-xs">Carte</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conditions -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-medium text-gray-900 mb-2">Conditions de paiement</h4>
                    <ul class="text-sm text-gray-600 space-y-1">
                        <li>• Le paiement de l'acompte confirme votre rendez-vous</li>
                        <li>• L'acompte est non remboursable sauf annulation par l'artiste</li>
                        <li>• Le solde sera à payer le jour du rendez-vous</li>
                        <li>• Vous disposez de 48h pour effectuer le paiement</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Stripe -->
    <script src="https://js.stripe.com/v3/"></script>
    <script nonce="{{ csp_nonce() }}">
        const stripe = Stripe('{{ config('services.stripe.publishable_key') }}');
        const checkoutButton = document.getElementById('checkout-button');
        const buttonText = document.getElementById('button-text');
        const buttonLoading = document.getElementById('button-loading');

        checkoutButton.addEventListener('click', async () => {
            // Afficher l'état de chargement
            checkoutButton.disabled = true;
            buttonText.classList.add('hidden');
            buttonLoading.classList.remove('hidden');

            try {
                // Créer la session de paiement
                const response = await fetch('{{ route('deposit.process', $bookingRequest->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Erreur lors de la création de la session de paiement');
                }

                // Rediriger vers Stripe Checkout
                const {
                    error
                } = await stripe.redirectToCheckout({
                    sessionId: data.session_id
                });

                if (error) {
                    throw new Error(error.message);
                }

            } catch (error) {
                console.error('Payment error:', error);
                alert('Erreur: ' + error.message);

                // Restaurer le bouton
                checkoutButton.disabled = false;
                buttonText.classList.remove('hidden');
                buttonLoading.classList.add('hidden');
            }
        });
    </script>
@endsection

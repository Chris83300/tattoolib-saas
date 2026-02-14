<x-app-layout>
    <div class="max-w-lg mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold text-noir-profond mb-6">Paiement du solde</h1>

        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <span class="text-noir-profond/70">Prix total</span>
                <span class="font-semibold">{{ number_format($bookingRequest->total_price, 2, ',', ' ') }} €</span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-noir-profond/70">Acompte versé</span>
                <span class="text-vert-succes font-semibold">- {{ number_format($bookingRequest->total_deposit_amount, 2, ',', ' ') }} €</span>
            </div>
            <hr class="my-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold">Solde restant</span>
                <span class="text-lg font-bold text-orange-terre-cuite">{{ number_format($balanceRemaining, 2, ',', ' ') }} €</span>
            </div>
        </div>

        <form action="{{ route('client.balance-payment.checkout', $bookingRequest) }}" method="POST">
            @csrf
            <button type="submit" 
                    class="w-full py-3 bg-noir-profond text-white rounded-xl font-semibold hover:bg-noir-profond/90 transition">
                💳 Payer {{ number_format($balanceRemaining, 2, ',', ' ') }} € en ligne
            </button>
        </form>

        <p class="text-center text-sm text-noir-profond/50 mt-4">
            Paiement sécurisé par Stripe. Vous pouvez aussi régler directement auprès de votre artiste.
        </p>
    </div>
</x-app-layout>

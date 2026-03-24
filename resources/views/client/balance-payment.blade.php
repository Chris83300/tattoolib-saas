@extends('layouts.client')

@section('content')
    <div class="max-w-lg mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold text-noir-profond mb-6">Paiement du solde</h1>

        @if (session('error'))
            <div class="bg-ambre-warning/10 border border-ambre-warning/30 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">⚠️</span>
                    <div>
                        <p class="font-semibold text-ambre-warning">Paiement en ligne indisponible</p>
                        <p class="text-sm text-ambre-warning/80 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-ivoire-text border-2 border-beige-peau rounded-xl shadow-md shadow-beige-peau/20 p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <span class="text-noir-profond/70">Prix total</span>
                <span
                    class="font-semibold">{{ number_format($bookingRequest->confirmed_final_price ?? ($bookingRequest->total_price ?? 0), 2, ',', ' ') }}
                    €</span>
            </div>
            <div class="flex justify-between items-center mb-4">
                <span class="text-noir-profond/70">Acompte versé</span>
                <span class="text-vert-succes font-semibold">-
                    {{ number_format($bookingRequest->total_deposit_amount, 2, ',', ' ') }} €</span>
            </div>
            <hr class="my-4">
            <div class="flex justify-between items-center">
                <span class="text-lg font-bold">Solde restant</span>
                <span class="text-lg font-bold text-orange-terre-cuite">{{ number_format($balanceRemaining, 2, ',', ' ') }}
                    €</span>
            </div>
        </div>

        <form action="{{ route('client.balance-payment.checkout', $bookingRequest) }}" method="POST" class="flex items-center justify-center">
            @csrf
            <button type="submit"
                class="inline-flex items-center justify-center font-semibold btn-shadow rounded-full transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-noir-profond bg-beige-peau text-noir-profond hover:bg-beige-peau/80 shadow-md shadow-beige-peau/20 focus:ring-beige-peau px-4 py-2 text-base cursor-pointer no-underline">
                💳 Payer {{ number_format($balanceRemaining, 2, ',', ' ') }} € en ligne
            </button>
        </form>

        <p class="text-center text-sm text-ivoire-text/50 mt-4">
            Paiement sécurisé par Stripe. Vous pouvez aussi régler directement auprès de votre artiste.
        </p>
    </div>
@endsection

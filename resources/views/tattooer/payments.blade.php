@extends('layouts.tattooer')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                    Paiements
                </h1>
                <p class="text-ivoire-text/70">
                    Gérez vos transactions et votre compte Stripe Connect
                </p>
            </div>
            @include('partials.export-buttons', ['type' => 'artist', 'year' => now()->year])
        </div>

        <!-- Stats KPI -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-noir-profond rounded-xl p-6 border border-titane/30">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-beige-peau/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm text-ivoire-text/60">Total gagné</span>
                </div>
                <div class="text-2xl font-bold text-ivoire-text">
                    {{ number_format($paymentStats['total_earned'], 2, ',', ' ') }} €
                </div>
                <p class="text-sm text-ivoire-text/50 mt-2">
                    Depuis le début
                </p>
            </div>

            <div class="bg-noir-profond rounded-xl p-6 border border-titane/30">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-vert-succes/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm text-ivoire-text/60">Ce mois</span>
                </div>
                <div class="text-2xl font-bold text-vert-succes">
                    {{ number_format($paymentStats['this_month'], 2, ',', ' ') }} €
                </div>
                <p class="text-sm text-ivoire-text/50 mt-2">
                    {{ now()->format('F Y') }}
                </p>
            </div>

            <div class="bg-noir-profond rounded-xl p-6 border border-titane/30">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-sm text-ivoire-text/60">Commissions</span>
                </div>
                <div class="text-2xl font-bold text-purple-400">
                    {{ number_format($paymentStats['total_commissions'], 2, ',', ' ') }} €
                </div>
                <p class="text-sm text-ivoire-text/50 mt-2">
                    7% des transactions
                </p>
            </div>

            <div class="bg-noir-profond rounded-xl p-6 border border-titane/30">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-ambre-warning/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-ambre-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-sm text-ivoire-text/60">Acomptes en attente</span>
                </div>
                <div class="text-2xl font-bold text-ambre-warning">
                    {{ number_format($paymentStats['pending_deposits'], 2, ',', ' ') }} €
                </div>
                <p class="text-sm text-ivoire-text/50 mt-2">
                    En attente de paiement
                </p>
            </div>
        </div>

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="p-4 bg-vert-succes/10 border border-vert-succes/30 rounded-xl text-vert-succes text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-rouge-erreur/10 border border-rouge-erreur/30 rounded-xl text-rouge-erreur text-sm">
                {{ session('error') }}
            </div>
        @endif
        @if (session('info'))
            <div class="p-4 bg-beige-peau/10 border border-beige-peau/30 rounded-xl text-beige-peau text-sm">
                {{ session('info') }}
            </div>
        @endif

        <!-- Stripe Connect -->
        @php
            $connectStatus = $tattooer->stripe_connect_status ?? 'not_connected';
            $isActive = $connectStatus === 'active' && !empty($tattooer->stripe_connect_account_id);
            $isPending =
                in_array($connectStatus, ['onboarding', 'pending', 'restricted']) &&
                !empty($tattooer->stripe_connect_account_id);
        @endphp

        <div class="bg-gris-fonde rounded-xl p-6">
            <h2 class="text-xl font-semibold text-ivoire-text mb-4">
                Stripe Connect
            </h2>

            @if (!$tattooer->needsOwnStripeConnect())
                {{-- Studio centralisé --}}
                <div class="flex items-start gap-3 p-4 bg-noir-profond rounded-xl border border-titane/20">
                    <span class="text-2xl">🏢</span>
                    <div class="flex-1">
                        <p class="text-sm text-titane mb-2">
                            Les paiements sont gérés par votre studio
                            <strong class="text-ivoire-text">{{ $tattooer->studio?->name }}</strong>.
                            Vous n'avez pas besoin de configurer Stripe Connect.
                        </p>

                        @if ($tattooer->studio)
                            <div class="bg-beige-peau/10 rounded-lg p-3 border border-beige-peau/20">
                                <p class="text-xs text-beige-peau font-semibold mb-1">💰 Paiement direct Artiste</p>
                                @php
                                    $commissionRate = $tattooer->studio->commission_rate ?? 0;
                                    $takesCommission = $commissionRate > 0;
                                @endphp
                                @if ($takesCommission)
                                    <p class="text-xs text-ivoire-text/80">
                                        Votre studio prend une commission de
                                        <span
                                            class="text-beige-peau font-bold">{{ number_format($commissionRate, 1) }}%</span>
                                        sur vos paiements.
                                    </p>
                                @else
                                    <p class="text-xs text-vert-succes">
                                        ✅ Votre studio ne prend aucune commission sur vos paiements.
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @elseif ($isActive)
                {{-- Compte actif --}}
                <div class="flex items-center gap-3 p-4 bg-vert-succes/10 rounded-xl border border-vert-succes/30">
                    <span class="text-2xl">✅</span>
                    <div class="flex-1">
                        <p class="font-semibold text-vert-succes">Stripe Connect actif</p>
                        <p class="text-sm text-ivoire-text/60">Vous recevez les paiements directement sur votre compte
                            bancaire.</p>
                    </div>
                    <form action="{{ route($tattooer->routePrefix() . '.stripe.connect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-titane text-ivoire-text rounded-lg hover:bg-titane/80 transition-colors text-sm">
                            Gérer le compte
                        </button>
                    </form>
                </div>
            @elseif ($isPending)
                {{-- Vérification en cours --}}
                <div class="flex items-center gap-3 p-4 bg-ambre-warning/10 rounded-xl border border-ambre-warning/30">
                    <span class="text-2xl">⏳</span>
                    <div class="flex-1">
                        <p class="font-semibold text-ambre-warning">Vérification en cours</p>
                        <p class="text-sm text-ivoire-text/60">Stripe peut vous demander des documents. Vérifiez votre
                            email.</p>
                    </div>
                    <form action="{{ route($tattooer->routePrefix() . '.stripe.connect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 bg-ambre-warning/20 text-ambre-warning rounded-lg hover:bg-ambre-warning/30 transition-colors text-sm">
                            Compléter mon profil
                        </button>
                    </form>
                </div>
            @else
                {{-- Pas encore configuré --}}
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                        Connectez votre compte Stripe
                    </h3>
                    <p class="text-ivoire-text/60 mb-6">
                        Recevez vos paiements directement sur votre compte bancaire
                    </p>
                    <form action="{{ route($tattooer->routePrefix() . '.stripe.connect') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                            Connecter Stripe Connect
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <!-- Transactions récentes -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-ivoire-text">
                    Transactions récentes
                </h2>
                <button class="px-4 py-2 bg-titane text-ivoire-text rounded-lg hover:bg-titane/80 transition-colors">
                    Exporter
                </button>
            </div>

            @if ($payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-titane/30">
                                <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Date</th>
                                <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Client</th>
                                <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Description</th>
                                <th class="text-left py-3 px-4 text-ivoire-text/60 font-medium">Méthode</th>
                                <th class="text-right py-3 px-4 text-ivoire-text/60 font-medium">Montant</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                @php
                                    $depositTx = $payment->bookingTransactions
                                        ->where('type', 'deposit')
                                        ->where('status', 'completed')
                                        ->first();
                                    $balanceTx = $payment->bookingTransactions
                                        ->where('type', 'final_payment')
                                        ->where('status', 'completed')
                                        ->first();
                                    $depositAmount = $depositTx?->amount ?? 0;
                                    $balanceAmount = $balanceTx?->amount ?? 0;
                                    $paidAmount = $depositAmount + $balanceAmount;
                                    $paymentMethod =
                                        $balanceTx?->payment_method ?? ($depositTx?->payment_method ?? 'stripe');
                                    $lastPaymentDate = $payment->balance_paid_at ?? $payment->deposit_paid_at;
                                @endphp
                                <tr class="border-b border-titane/20 hover:bg-noir-profond/50 transition-colors">
                                    <td class="py-3 px-4 text-ivoire-text">
                                        {{ $lastPaymentDate?->format('d/m/Y') ?? 'N/A' }}
                                    </td>
                                    <td class="py-3 px-4 text-ivoire-text">
                                        {{ $payment->client->first_name }} {{ $payment->client->last_name }}
                                    </td>
                                    <td class="py-3 px-4 text-ivoire-text">
                                        <div class="flex flex-col gap-1">
                                            <span>{{ $payment->description }}</span>
                                            @if ($payment->body_zone)
                                                <span class="text-xs text-ivoire-text/60">
                                                    ({{ $payment->body_zone }})
                                                </span>
                                            @endif
                                            <div class="flex gap-2 mt-1">
                                                @if ($depositAmount > 0)
                                                    <span
                                                        class="px-2 py-1 bg-beige-peau/20 text-beige-peau rounded-full text-xs font-medium">
                                                        Acompte: {{ number_format($depositAmount, 2, ',', ' ') }} €
                                                    </span>
                                                @endif
                                                @if ($balanceAmount > 0)
                                                    <span
                                                        class="px-2 py-1 bg-vert-succes/20 text-vert-succes rounded-full text-xs font-medium">
                                                        Solde: {{ number_format($balanceAmount, 2, ',', ' ') }} €
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="px-2 py-1 bg-titane/20 text-titane rounded-full text-xs font-medium">
                                            {{ $paymentMethod }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="font-semibold text-vert-succes">
                                                {{ number_format($paidAmount, 2, ',', ' ') }} €
                                            </span>
                                            @if ($payment->balance_paid_at)
                                                <span class="text-xs text-vert-succes/80">✅ Complété</span>
                                            @else
                                                <span class="text-xs text-ambre-warning/80">⏳ En attente solde</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                        Aucune transaction
                    </h3>
                    <p class="text-ivoire-text/60">
                        Vous n'avez pas encore de transactions enregistrées.
                    </p>
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
        <script>
            // Gestion du bouton "Gérer le compte" (placeholder)
            document.querySelectorAll('button').forEach(button => {
                if (button.textContent.includes('Gérer')) {
                    button.addEventListener('click', function() {
                        // Placeholder pour la gestion du compte existant
                        alert('Fonctionnalité de gestion du compte à implémenter');
                    });
                }
            });
        </script>
    @endpush
@endsection

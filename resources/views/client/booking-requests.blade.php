@extends('layouts.app')

@section('title', 'Mes demandes')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-8">
                <a href="{{ route('client.dashboard') }}"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour au tableau de bord
                </a>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text mb-2">Mes demandes</h1>
                        <p class="text-ivoire-text/70">Historique de toutes vos demandes de tatouage</p>
                    </div>

                    <a href="{{ route('booking-request.form') }}"
                        class="inline-flex items-center px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle demande
                    </a>
                </div>

                <!-- Filtres -->
                <div class="bg-gris-fonde rounded-xl p-6 mb-6">
                    <form method="GET" action="{{ route('client.booking-requests') }}"
                        class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <select name="status"
                                class="w-full px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:outline-none">
                                <option value="">Tous les statuts</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente
                                </option>
                                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Acceptées
                                </option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En
                                    cours</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                    Terminées</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulées
                                </option>
                            </select>
                        </div>
                        <button type="submit"
                            class="px-6 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                            Filtrer
                        </button>
                        @if (request('status'))
                            <a href="{{ route('client.booking-requests') }}"
                                class="px-6 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold hover:bg-noir-profond/80 transition-colors">
                                Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Liste des demandes -->
            @if ($bookingRequests->isEmpty())
                <div class="bg-gris-fonde rounded-xl p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-noir-profond rounded-full mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-ivoire-text mb-2">Aucune demande trouvée</h3>
                    <p class="text-ivoire-text/70 mb-6">
                        @if (request('status'))
                            Aucune demande avec ce statut
                        @else
                            Vous n'avez pas encore fait de demande de tatouage
                        @endif
                    </p>
                    <a href="{{ route('booking-request.form') }}"
                        class="inline-flex items-center px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Faire ma première demande
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($bookingRequests as $bookingRequest)
                        <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                                <!-- Informations principales -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-xl font-semibold text-ivoire-text mb-2">
                                                {{ $bookingRequest->tattoo_description }}
                                            </h3>
                                            <div class="flex items-center gap-3 mb-3">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                {{ $bookingRequest->status === 'pending' ? 'bg-ambre-warning/20 text-ambre-warning' : '' }}
                                                {{ $bookingRequest->status === 'accepted' ? 'bg-vert-succes/20 text-vert-succes' : '' }}
                                                {{ $bookingRequest->status === 'in_progress' ? 'bg-beige-peau/20 text-beige-peau' : '' }}
                                                {{ $bookingRequest->status === 'completed' ? 'bg-vert-succes/20 text-vert-succes' : '' }}
                                                {{ $bookingRequest->status === 'cancelled' ? 'bg-rouge-alerte/20 text-rouge-alerte' : '' }}">
                                                    {{ match ($bookingRequest->status) {
                                                        'pending' => '⏳ En attente',
                                                        'accepted' => '✓ Acceptée',
                                                        'in_progress' => '🎨 En cours',
                                                        'completed' => '✅ Terminée',
                                                        'cancelled' => '❌ Annulée',
                                                        default => $bookingRequest->status,
                                                    } }}
                                                </span>
                                                @if ($bookingRequest->unread_messages > 0)
                                                    <span
                                                        class="inline-flex items-center justify-center px-2 py-1 bg-rouge-alerte text-ivoire-text rounded-full text-xs font-bold">
                                                        {{ $bookingRequest->unread_messages }} nouveau(x) message(s)
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div>
                                            <span class="text-ivoire-text/70 block text-sm mb-1">Emplacement</span>
                                            <span
                                                class="text-ivoire-text font-medium">{{ $bookingRequest->tattoo_location }}</span>
                                        </div>
                                        @if ($bookingRequest->tattoo_style)
                                            <div>
                                                <span class="text-ivoire-text/70 block text-sm mb-1">Style</span>
                                                <span
                                                    class="text-ivoire-text font-medium">{{ $bookingRequest->tattoo_style }}</span>
                                            </div>
                                        @endif
                                        @if ($bookingRequest->estimated_price)
                                            <div>
                                                <span class="text-ivoire-text/70 block text-sm mb-1">Budget estimé</span>
                                                <span
                                                    class="text-ivoire-text font-medium">{{ number_format($bookingRequest->estimated_price, 0) }}€</span>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="text-ivoire-text/70 block text-sm mb-1">Date demande</span>
                                            <span
                                                class="text-ivoire-text font-medium">{{ $bookingRequest->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    @if ($bookingRequest->bookable)
                                        <div class="flex items-center gap-2 text-sm text-ivoire-text/70 mb-4">
                                            <div
                                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center">
                                                <svg class="w-4 h-4 text-noir-profond" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <span>
                                                Artiste: {{ $bookingRequest->bookable->user->name }}
                                                @if ($bookingRequest->bookable_type === 'App\Models\Tattooer')
                                                    (Tatoueur)
                                                @elseif($bookingRequest->bookable_type === 'App\Models\StudioArtist')
                                                    (Artiste studio)
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col gap-2 lg:w-48">
                                    @if ($bookingRequest->status === 'accepted')
                                        <a href="{{ route('client.chat', $bookingRequest->conversation) }}"
                                            class="flex items-center justify-center px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Discuter
                                        </a>
                                    @endif

                                    @if ($bookingRequest->deposit_requested_at && !$bookingRequest->deposit_paid_at)
                                        <a href="{{ route('deposit.payment', $bookingRequest) }}"
                                            class="flex items-center justify-center px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Payer l'acompte
                                        </a>
                                    @endif

                                    <a href="{{ route('client.booking-request.show', $bookingRequest) }}"
                                        class="flex items-center justify-center px-4 py-2 bg-titane text-ivoire-text rounded-lg font-semibold hover:bg-titane/80 transition-colors">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Voir détails
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bookingRequests->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

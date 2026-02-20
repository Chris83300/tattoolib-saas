@extends('layouts.client')

@section('title', 'Mes demandes')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-8">
                <a href="{{ route('client.profile') }}"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour au profile
                </a>

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text mb-2">Mes demandes</h1>
                        <p class="text-ivoire-text/70">Historique de toutes vos demandes de tatouage</p>
                    </div>

                    <a href="{{ route('marketplace.index') }}"
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
                    <a href="{{ route('marketplace.index') }}"
                        class="inline-flex items-center px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Faire une demande de tatouage
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($bookingRequests as $bookingRequest)
                        <div
                            class="bg-gris-fonde rounded-xl p-6 border border-titane/30 {{ $bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID ? 'ring-2 ring-vert-succes/50' : '' }} {{ $bookingRequest->status === \App\Enums\BookingRequestStatus::CANCELLED ? 'ring-2 ring-rouge-alerte/50' : '' }}">
                            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                                <!-- Informations principales -->
                                <div class="flex-1">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-xl font-semibold text-ivoire-text mb-2">
                                                {{ $bookingRequest->tattoo_description }}
                                            </h3>
                                            <div class="flex items-center gap-3 mb-3">
                                                @if ($bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID)
                                                    <!-- Statut deposit_paid - design spécial -->
                                                    <div
                                                        class="inline-flex items-center px-4 py-2 bg-vert-succes/20 border border-vert-succes/30 rounded-full">
                                                        <svg class="w-5 h-5 mr-2 text-vert-succes" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        <span class="text-vert-succes font-medium">💰 Acompte payé</span>
                                                    </div>
                                                @elseif ($bookingRequest->status === \App\Enums\BookingRequestStatus::CANCELLED)
                                                    <!-- Statut rejected - design spécial -->
                                                    <div
                                                        class="inline-flex items-center px-4 py-2 bg-rouge-alerte/20 border border-rouge-alerte/30 rounded-full">
                                                        <svg class="w-5 h-5 mr-2 text-rouge-alerte" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                        <span class="text-rouge-alerte font-medium">❌ Demande refusée</span>
                                                    </div>
                                                @else
                                                    <!-- Autres statuts - design existant -->
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                        @if ($bookingRequest->status->value === 'pending') bg-jaune-alerte/20 text-jaune-alerte border border-jaune-alerte/30
                                                        @elseif($bookingRequest->status->value === 'accepted') bg-beige-peau/20 text-beige-peau border border-beige-peau/30
                                                        @elseif($bookingRequest->status->value === 'deposit_requested') bg-ambre-warning/20 text-ambre-warning border border-ambre-warning/30
                                                        @elseif($bookingRequest->status->value === 'deposit_paid') bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                                        @elseif($bookingRequest->status->value === 'date_confirmed') bg-beige-peau/20 text-beige-peau border border-beige-peau/30
                                                        @elseif($bookingRequest->status->value === 'completed') bg-vert-succes/20 text-vert-succes border border-vert-succes/30
                                                        @elseif($bookingRequest->status->value === 'rejected') bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30
                                                        @elseif($bookingRequest->status->value === 'cancelled') bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30
                                                        @elseif($bookingRequest->status->value === 'expired') bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30
                                                        @elseif($bookingRequest->status->value === 'no_show') bg-rouge-alerte/20 text-rouge-alerte border border-rouge-alerte/30 @endif">
                                                        {{ $bookingRequest->status->label() }}
                                                    </span>
                                                @endif

                                                @if ($bookingRequest->unread_messages > 0)
                                                    <span
                                                        class="inline-flex items-center justify-center px-2 py-1 bg-rouge-alerte text-ivoire-text rounded-full text-xs font-bold">
                                                        {{ $bookingRequest->unread_messages }} nouveau(x) message(s)
                                                    </span>
                                                @endif
                                            </div>

                                            <!-- Message informatif selon statut -->
                                            @if ($bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID)
                                                <div
                                                    class="bg-vert-succes/10 border border-vert-succes/20 rounded-lg p-3 mb-3">
                                                    <p class="text-vert-succes text-sm font-medium">
                                                        🎉 Super ! Votre acompte a été payé. Le chat est maintenant
                                                        permanent pour finaliser votre projet.
                                                    </p>
                                                </div>
                                            @elseif ($bookingRequest->status === \App\Enums\BookingRequestStatus::CANCELLED)
                                                <div
                                                    class="bg-rouge-alerte/10 border border-rouge-alerte/20 rounded-lg p-3 mb-3">
                                                    <p class="text-rouge-alerte text-sm font-medium">
                                                        😔 Cette demande n'a pas pu être acceptée. Vous pouvez faire une
                                                        nouvelle demande.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        @if ($bookingRequest->body_zone)
                                            <div>
                                                <span class="text-ivoire-text/70 block text-sm mb-1">Emplacement</span>
                                                <span
                                                    class="text-ivoire-text font-medium">{{ $bookingRequest->body_zone }}</span>
                                            </div>
                                        @endif
                                        @if ($bookingRequest->tattoo_size)
                                            <div>
                                                <span class="text-ivoire-text/70 block text-sm mb-1">Taille</span>
                                                <span
                                                    class="text-ivoire-text font-medium">{{ $bookingRequest->tattoo_size }}</span>
                                            </div>
                                        @endif
                                        @if ($bookingRequest->tattoo_style)
                                            <div>
                                                <span class="text-ivoire-text/70 block text-sm mb-1">Style</span>
                                                <span
                                                    class="text-ivoire-text font-medium">{{ $bookingRequest->tattoo_style }}</span>
                                            </div>
                                        @endif
                                        @if ($bookingRequest->price_range_min && $bookingRequest->price_range_max)
                                            <div>
                                                <span class="text-ivoire-text/70 block text-sm mb-1">Estimation
                                                    tattoo</span>
                                                <span
                                                    class="text-ivoire-text font-medium">{{ number_format($bookingRequest->price_range_min, 0) }}€
                                                    - {{ number_format($bookingRequest->price_range_max, 0) }}€</span>
                                            </div>
                                        @elseif ($bookingRequest->estimated_total_price)
                                            <div>
                                                <span class="text-ivoire-text/70 block text-sm mb-1">Estimation
                                                    tattoo</span>
                                                <span
                                                    class="text-ivoire-text font-medium">{{ number_format($bookingRequest->estimated_total_price, 0) }}€</span>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="text-ivoire-text/70 block text-sm mb-1">Date demande</span>
                                            <span
                                                class="text-ivoire-text font-medium">{{ $bookingRequest->created_at->format('d/m/Y') }}</span>
                                        </div>
                                    </div>

                                    @if ($bookingRequest->bookable)
                                        <div class="flex items-center gap-3 text-sm text-ivoire-text/70 mb-4">
                                            <div class="w-10 h-10 rounded-full overflow-hidden bg-beige-peau/10">
                                                @if ($bookingRequest->bookable->user->getFirstMediaUrl('avatar'))
                                                    <img src="{{ $bookingRequest->bookable->user->getFirstMediaUrl('avatar') }}"
                                                        alt="Avatar de {{ $bookingRequest->bookable->user->name }}"
                                                        class="w-full h-full object-cover">
                                                @elseif ($bookingRequest->bookable->getFirstMediaUrl('avatar'))
                                                    <img src="{{ $bookingRequest->bookable->getFirstMediaUrl('avatar') }}"
                                                        alt="Avatar de {{ $bookingRequest->bookable->user->name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div
                                                        class="w-full h-full bg-beige-peau rounded-full flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-noir-profond" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <span
                                                    class="font-medium text-ivoire-text">{{ $bookingRequest->bookable->user->name }}</span>
                                                <div class="text-xs">
                                                    @if ($bookingRequest->bookable_type === 'App\Models\Tattooer')
                                                        Tatoueur indépendant
                                                    @elseif($bookingRequest->bookable_type === 'App\Models\StudioArtist')
                                                        Artiste de studio
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col gap-2 lg:w-48">
                                    @if ($bookingRequest->status === 'deposit_paid')
                                        <!-- Actions pour deposit_paid -->
                                        <a href="{{ route('client.chat', $bookingRequest->conversation) }}"
                                            class="flex items-center justify-center px-4 py-3 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                            </svg>
                                            Discuter
                                        </a>
                                    @elseif ($bookingRequest->status === 'rejected')
                                        <!-- Actions pour rejected -->
                                        <a href="{{ route('marketplace.index') }}"
                                            class="flex items-center justify-center px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                            Nouvelle demande
                                        </a>
                                        <button onclick="confirmDelete({{ $bookingRequest->id }})"
                                            class="flex items-center justify-center px-4 py-2 bg-rouge-alerte text-ivoire-text rounded-lg font-semibold hover:bg-rouge-alerte/90 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Supprimer
                                        </button>
                                    @elseif (
                                        $bookingRequest->status === \App\Enums\BookingRequestStatus::ACCEPTED ||
                                            $bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_REQUESTED)
                                        <!-- Actions existantes -->
                                        @if ($bookingRequest->conversation)
                                            <a href="{{ route('client.chat', $bookingRequest->conversation) }}"
                                                class="flex items-center justify-center px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                Chat
                                            </a>
                                        @else
                                            <button disabled
                                                class="flex items-center justify-center px-4 py-2 bg-titane/30 text-ivoire-text/50 rounded-lg font-semibold cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                                </svg>
                                                Chat (bientôt disponible)
                                            </button>
                                        @endif
                                    @endif

                                    @if (
                                        ($bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_REQUESTED ||
                                            $bookingRequest->deposit_requested_at) &&
                                            !$bookingRequest->deposit_paid_at)
                                        <a href="{{ route('deposit.payment', $bookingRequest->id) }}"
                                            class="flex items-center justify-center px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                            Payer l'acompte
                                        </a>
                                    @endif

                                    @if ($bookingRequest->isCompleted())
                                        @php
                                            $reviewed = \App\Models\Review::where(
                                                'reviewable_type',
                                                'App\Models\BookingRequest',
                                            )
                                                ->where('reviewable_id', $bookingRequest->id)
                                                ->where('client_id', auth()->id())
                                                ->exists();
                                        @endphp
                                        @if (!$reviewed)
                                            <button type="button" onclick="openReviewModal({{ $bookingRequest->id }})"
                                                class="flex items-center justify-center px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 01-.69.69l-4.416-1.416a1 1 0 01-.688-.69l1.517-4.674z" />
                                                </svg>
                                                ⭐ Laisser un avis
                                            </button>
                                        @else
                                            <div
                                                class="flex items-center justify-center px-4 py-2 bg-vert-succes/20 text-vert-succes rounded-lg font-semibold">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                ✅ Avis laissé
                                            </div>
                                        @endif
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

    <!-- Script pour la suppression -->
    <script>
        function confirmDelete(bookingRequestId) {
            if (confirm(
                    'Êtes-vous sûr de vouloir supprimer définitivement cette demande ?\n\nCette action est irréversible.'
                )) {
                // Créer un formulaire temporaire pour la suppression
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/client/booking-request/${bookingRequestId}/delete`;

                // Ajouter le token CSRF
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken.getAttribute('content');
                    form.appendChild(csrfInput);
                }

                // Ajouter la méthode DELETE (spoofing)
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection

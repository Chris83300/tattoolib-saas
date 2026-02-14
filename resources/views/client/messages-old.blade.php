@extends('layouts.app')

@section('title', 'Mes conversations')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                            💬 Mes conversations
                        </h1>
                        <p class="text-ivoire-text/70">
                            Échangez avec vos tatoueurs et perceurs
                        </p>
                    </div>

                    @if ($conversations->count() > 0)
                        <a href="{{ route('client.conversations') }}"
                            class="inline-flex items-center px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond rounded-lg font-medium transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            Voir toutes les conversations
                        </a>
                    @endif
                </div>
            </div>

            <!-- Liste des conversations -->
            <div class="bg-gris-fonde rounded-xl p-6">
                @if ($conversations->count() > 0)
                    <div class="space-y-4">
                        @foreach ($conversations as $conversation)
                            @php
                                // Récupérer l'artiste (tattooer ou pierceur) via bookingRequest
$bookingRequest = $conversation->bookingRequest;
$artist = $bookingRequest?->bookable; // MorphTo : Tattooer | Pierceur
$artistUser = $artist?->user;
$artistName = $artistUser?->name ?? 'Artiste inconnu';

// Type d'artiste
                                $artistType = $bookingRequest?->bookable_type;
                                $artistTypeLabel = match ($artistType) {
                                    'App\Models\Tattooer' => '🎨 Tattooer',
                                    'App\Models\Pierceur' => '💎 Pierceur',
                                    default => '👤 Artiste',
                                };

                                // Dernier message
                                $lastMessage = $conversation->lastMessage;

                                // Badge statut conversation
                                $expiryBadge = match ($conversation->expiry_type) {
                                    'permanent' => [
                                        'text' => '✅ Actif',
                                        'class' => 'bg-vert-succes/20 text-vert-succes',
                                    ],
                                    'deposit_pending' => [
                                        'text' => '⏱️ En attente acompte',
                                        'class' => 'bg-ambre-warning/20 text-ambre-warning',
                                    ],
                                    'post_appointment' => [
                                        'text' => '📋 RDV terminé',
                                        'class' => 'bg-titane/30 text-ivoire-text/60',
                                    ],
                                    'archived' => [
                                        'text' => '📦 Archivé',
                                        'class' => 'bg-gris-fonde text-ivoire-text/50',
                                    ],
                                    default => ['text' => '❓ Inconnu', 'class' => 'bg-titane/20 text-ivoire-text/40'],
                                };
                            @endphp

                            <a href="{{ route('client.chat', $conversation->id) }}"
                                class="block p-4 bg-noir-profond rounded-lg hover:bg-noir-profond/80 transition-colors border border-titane/20 hover:border-beige-peau/30">
                                <div class="flex items-start justify-between gap-4">
                                    <!-- Colonne gauche : Info artiste + dernier message -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <!-- Avatar artiste -->
                                            <div
                                                class="w-10 h-10 bg-beige-peau/20 rounded-full flex items-center justify-center flex-shrink-0">
                                                @if ($artist && $artist->getFirstMediaUrl('avatar'))
                                                    <img src="{{ $artist->getFirstMediaUrl('avatar') }}"
                                                        alt="{{ $artistName }}"
                                                        class="w-10 h-10 rounded-full object-cover">
                                                @else
                                                    <svg class="w-5 h-5 text-beige-peau" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <h3 class="font-semibold text-ivoire-text truncate">
                                                    {{ $artistName }}
                                                </h3>
                                                <p class="text-xs text-ivoire-text/50">
                                                    {{ $artistTypeLabel }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Sujet conversation -->
                                        @if ($conversation->subject || $bookingRequest?->tattoo_description)
                                            <p class="text-sm text-ivoire-text/60 mb-2 truncate">
                                                {{ $conversation->subject ?? \Illuminate\Support\Str::limit($bookingRequest->tattoo_description, 60) }}
                                            </p>
                                        @endif

                                        <!-- Dernier message -->
                                        @if ($lastMessage)
                                            <div class="mt-2 bg-gris-fonde/50 rounded-lg p-2">
                                                <p class="text-sm text-ivoire-text/70 line-clamp-2">
                                                    @if ($lastMessage->sender_id === auth()->id())
                                                        <span class="font-semibold text-beige-peau">Vous :</span>
                                                    @else
                                                        <span class="font-semibold text-ivoire-text">{{ $artistName }}
                                                            :</span>
                                                    @endif
                                                    {{ $lastMessage->content ?? '📎 Fichier joint' }}
                                                </p>
                                                <p class="text-xs text-ivoire-text/50 mt-1">
                                                    {{ $lastMessage->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        @else
                                            <p class="text-xs text-ivoire-text/50 italic">
                                                Aucun message pour le moment
                                            </p>
                                        @endif

                                        <!-- Alerte expiration (si applicable) -->
                                        @if ($conversation->getExpiryWarningMessage())
                                            <div
                                                class="mt-3 bg-ambre-warning/10 border border-ambre-warning/30 rounded-lg p-2">
                                                <p class="text-xs text-ambre-warning font-medium">
                                                    {{ $conversation->getExpiryWarningMessage() }}
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Colonne droite : Badges statut -->
                                    <div class="flex flex-col items-end gap-2 flex-shrink-0">
                                        <!-- Badge non-lus -->
                                        @if ($conversation->unread_count > 0)
                                            <span
                                                class="bg-rouge-alerte text-noir-profond px-2.5 py-1 rounded-full text-xs font-bold shadow-lg">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif

                                        <!-- Badge statut conversation -->
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-medium {{ $expiryBadge['class'] }}">
                                            {{ $expiryBadge['text'] }}
                                        </span>

                                        <!-- Jours restants (si expiration proche) -->
                                        @if ($conversation->expiry_type === 'deposit_pending' && $conversation->getDaysUntilExpiry())
                                            @php
                                                $daysLeft = $conversation->getDaysUntilExpiry();
                                            @endphp
                                            @if ($daysLeft > 0 && $daysLeft <= 7)
                                                <span class="text-xs text-ambre-warning font-medium">
                                                    ⏰ {{ $daysLeft }}j restants
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination (si besoin) -->
                    {{-- @if ($conversations->hasPages())
                <div class="mt-6">
                    {{ $conversations->links() }}
                </div>
            @endif --}}
                @else
                    <!-- État vide -->
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                            Aucune conversation
                        </h3>
                        <p class="text-ivoire-text/60 mb-6">
                            Vous n'avez pas encore de messages avec vos artistes.
                        </p>
                        <a href="{{ route('marketplace.index') }}"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Trouver un artiste
                        </a>
                    </div>
                @endif
            </div>

        </div>
    @endsection

    @push('scripts')
        <script>
            // Optionnel : Auto-refresh toutes les 30 secondes pour voir nouveaux messages
            // (Désactivé par défaut, active uniquement si besoin)
            /*
            setInterval(() => {
                window.location.reload();
            }, 30000);
            */
        </script>
    @endpush

@extends('layouts.tattooer')

@section('content')
    <div class="space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                Messages
            </h1>
            <p class="text-ivoire-text/70">
                Conversations avec vos clients
            </p>
        </div>

        <!-- Liste des conversations -->
        <div class="bg-gris-fonde rounded-xl p-6">
            @if ($conversations->count() > 0)
                <div class="space-y-4">
                    @foreach ($conversations as $conversation)
                        <a href="{{ route($tattooer->routePrefix() . '.message.show', $conversation) }}"
                            class="block p-4 bg-noir-profond rounded-lg hover:bg-noir-profond/80 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <!-- Avatar -->
                                        <div
                                            class="w-10 h-10 rounded-full overflow-hidden bg-titane/30 flex items-center justify-center">
                                            @if ($conversation->client->user->getFirstMedia('avatar'))
                                                <img src="{{ $conversation->client->user->getFirstMedia('avatar')->getUrl() }}"
                                                    alt="{{ $conversation->client->user->name }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-5 h-5 text-ivoire-text/40" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <!-- PSEUDO (pas nom/prénom) -->
                                            <h3 class="font-semibold text-ivoire-text">
                                                {{ $conversation->client->user->pseudo ?? $conversation->client->user->first_name . ' ' . $conversation->client->user->last_name }}
                                            </h3>
                                            <p class="text-sm text-ivoire-text/60">
                                                {{ $conversation->description ? Str::limit($conversation->description, 50) : 'Nouvelle demande de projet' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if ($conversation->messages->count() > 0)
                                        @php
                                            $lastMessage = $conversation->messages->first();
                                        @endphp
                                        <div class="mt-2">
                                            <p class="text-sm text-ivoire-text/70">
                                                {{ $lastMessage->content ? Str::limit($lastMessage->content, 80) : 'Message sans texte' }}
                                            </p>
                                            <p class="text-xs text-ivoire-text/50 mt-1">
                                                {{ $lastMessage->created_at->format('d/m/Y à H:i') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <!-- Badge statut acompte -->
                                    @php
                                        $br = $conversation->bookingRequest;
                                    @endphp

                                    @if ($br)
                                        <div class="flex flex-col gap-1">
                                            @if ($br->deposit_paid_at)
                                                <span
                                                    class="px-2.5 py-0.5 bg-vert-succes/20 text-vert-succes rounded-full text-xs font-bold">
                                                    💰 Acompte payé
                                                </span>
                                            @elseif (in_array($br->status, ['accepted', 'awaiting_deposit']) && $br->deposit_amount)
                                                <span
                                                    class="px-2.5 py-0.5 bg-jaune-alerte/20 text-jaune-alerte rounded-full text-xs font-bold">
                                                    ⏳ Acompte en attente
                                                </span>
                                            @endif

                                            <!-- Badge statut demande -->
                                            <span
                                                class="px-2.5 py-0.5 bg-titane/30 text-ivoire-text/80 rounded-full text-xs font-semibold">
                                                {{ match ($br->status->value) {
                                                    'pending' => 'En attente',
                                                    'accepted' => 'Acceptée',
                                                    'deposit_requested' => 'Acompte attendu',
                                                    'deposit_paid' => 'Acompte payé',
                                                    'design_sent' => 'Dessin envoyé',
                                                    'date_confirmed' => 'Confirmé',
                                                    'completed' => 'Terminé',
                                                    'cancelled' => 'Annulé',
                                                    default => ucfirst($br->status->value),
                                                } }}
                                            </span>
                                        </div>
                                    @endif

                                    @if ($conversation->unread_count > 0)
                                        <span
                                            class="bg-rouge-alerte text-noir-profond px-2 py-1 rounded-full text-xs font-bold">
                                            {{ $conversation->unread_count }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                        Aucune conversation
                    </h3>
                    <p class="text-ivoire-text/60">
                        Vous n'avez pas encore de messages avec vos clients.
                    </p>
                </div>
            @endif
        </div>

    </div>

    {{-- Section messages privés de l'équipe Ink&Pik --}}
    {{-- @php
        $adminConversation = \App\Models\Conversation::where('type', 'admin_private')
            ->whereHas('participants', fn($q) => $q->where('users.id', auth()->id()))
            ->with(['messages' => fn($q) => $q->latest()->limit(5)])
            ->first();
    @endphp
    @if ($adminConversation && $adminConversation->messages->count() > 0)
        <div class="bg-gris-fonde rounded-xl overflow-hidden border border-blue-500/20 mt-6">
            <div class="px-6 py-4 border-b border-blue-500/20 flex items-center gap-2 bg-blue-500/5">
                <span>🛡️</span>
                <span class="font-semibold text-blue-300 text-sm">Messages de l'équipe Ink&amp;Pik</span>
            </div>
            <div class="p-6 space-y-4">
                @foreach ($adminConversation->messages as $msg)
                    <div class="text-sm">
                        <p class="text-ivoire-text/40 text-xs">{{ $msg->created_at->diffForHumans() }}</p>
                        <p class="text-ivoire-text mt-0.5 whitespace-pre-wrap">{{ $msg->content }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif --}}
    

    @push('scripts')
        <script>
            // Auto-rafraîchissement toutes les 30 secondes pour les nouveaux messages
            setInterval(() => {
                // Optionnel : recharger la page pour voir les nouveaux messages
                // window.location.reload();
            }, 30000);
        </script>
    @endpush
@endsection

@extends('layouts.tattooer')

@section('title', 'Chat - ' . $bookingRequest->client->full_name)

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-6">
                <a href="{{ route('tattooer.requests') }}"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux demandes
                </a>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text">Chat avec {{ $bookingRequest->client->full_name }}
                        </h1>
                        <p class="text-ivoire-text/70 mt-1">Projet: {{ $bookingRequest->tattoo_description }}</p>
                    </div>

                    @if ($bookingRequest->status === 'accepted')
                        <a href="{{ route('booking-request.deposit.request', $bookingRequest) }}"
                            class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                            💰 Demander acompte
                        </a>
                    @endif
                </div>
            </div>

            <!-- Zone de chat -->
            <div class="bg-titane/20 rounded-xl border border-titane/30">
                <!-- Messages -->
                <div class="h-96 overflow-y-auto p-6 space-y-4">
                    @if ($messages->isEmpty())
                        <div class="text-center py-12">
                            <div
                                class="inline-flex items-center justify-center w-16 h-16 bg-noir-profond rounded-full mb-4">
                                <svg class="w-8 h-8 text-ivoire-text/50" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.009 9.009 0 00-2.617-.656L4 19l1.383-5.344A9.002 9.002 0 016 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                                @if (
                                    $bookingRequest->status === 'accepted' &&
                                        $bookingRequest->accepted_at &&
                                        $bookingRequest->accepted_at->diffInDays(now()) <= 7 &&
                                        !$bookingRequest->deposit_paid_at)
                                    Chat ouvert
                                @else
                                    Chat fermé
                                @endif
                            </h3>
                            <p class="text-ivoire-text/70">
                                @if ($bookingRequest->status !== 'accepted')
                                    Le chat sera disponible lorsque le projet sera accepté
                                @elseif ($bookingRequest->deposit_paid_at)
                                    Le chat est fermé car l'acompte a été payé
                                @elseif ($bookingRequest->accepted_at && $bookingRequest->accepted_at->diffInDays(now()) > 7)
                                    Le chat est fermé (délai de 7 jours dépassé)
                                @else
                                    Le chat est ouvert pour discuter avec le client
                                @endif
                            </p>
                        </div>
                    @else
                        @foreach ($messages as $message)
                            <div class="flex {{ $message->sender_type === 'client' ? 'justify-start' : 'justify-end' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div
                                        class="{{ $message->sender_type === 'client' ? 'bg-noir-profond text-ivoire-text' : 'bg-beige-peau text-noir-profond' }} rounded-lg px-4 py-2">
                                        <p class="text-sm">{{ $message->content }}</p>
                                    </div>
                                    <p class="text-xs text-ivoire-text/50 mt-1">
                                        {{ $message->created_at->format('H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Zone de saisie -->
                <div class="border-t border-titane/30 p-4">
                    <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3 mb-4">
                        <p class="text-jaune-alerte text-sm">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Le chat sera bientôt disponible. Pour l'instant, utilisez la demande d'acompte pour finaliser le
                            projet.
                        </p>
                    </div>

                    @php
                        $chatOpen =
                            $bookingRequest->status === 'accepted' &&
                            $bookingRequest->accepted_at &&
                            (!$bookingRequest->deposit_requested_at ||
                                ($bookingRequest->deposit_requested_at &&
                                    !$bookingRequest->deposit_paid_at &&
                                    $bookingRequest->deposit_deadline > now()));
                    @endphp

                    @if ($chatOpen)
                        <form action="{{ route('tattooer.message.send', $bookingRequest) }}" method="POST"
                            enctype="multipart/form-data" class="flex gap-2">
                            @csrf

                            <input type="file" name="attachments[]" id="attachments" multiple
                                accept="image/*,application/pdf" class="hidden">

                            <button type="button" onclick="document.getElementById('attachments').click()"
                                class="px-4 py-3 bg-noir-profond text-ivoire-text rounded-lg hover:bg-noir-profond/80 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                            </button>

                            <textarea name="content" rows="1" placeholder="Votre message..." required
                                class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none"
                                onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>

                            <button type="submit"
                                class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                Envoyer
                            </button>
                        </form>

                        <div id="file-preview" class="mt-2 flex gap-2 hidden"></div>
                    @else
                        <form class="flex space-x-4 opacity-50 pointer-events-none">
                            <input type="text" placeholder="Chat en cours de développement..."
                                class="flex-1 px-4 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:outline-none focus:ring-2 focus:ring-beige-peau focus:border-transparent"
                                disabled>
                            <button type="submit" disabled
                                class="px-6 py-2 bg-gris-fonde text-ivoire-text/50 rounded-lg font-semibold cursor-not-allowed">
                                Bientôt disponible
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Informations projet -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Détails du projet</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Emplacement:</span>
                            <span class="text-ivoire-text">{{ $bookingRequest->tattoo_location }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Style:</span>
                            <span class="text-ivoire-text">{{ $bookingRequest->tattoo_style }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Prix estimé:</span>
                            <span
                                class="text-ivoire-text">{{ $bookingRequest->estimated_price ? number_format($bookingRequest->estimated_price, 2, ',', ' ') . ' €' : 'Non défini' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Statut:</span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            @if ($bookingRequest->status === 'pending') bg-jaune-alerte/20 text-jaune-alerte
                            @elseif($bookingRequest->status === 'accepted') bg-vert-succes/20 text-vert-succes
                            @elseif($bookingRequest->status === 'in_progress') bg-beige-peau/20 text-beige-peau
                            @elseif($bookingRequest->status === 'completed') bg-vert-succes/20 text-vert-succes
                            @elseif($bookingRequest->status === 'cancelled') bg-rouge-alerte/20 text-rouge-alerte @endif">
                                {{ ucfirst($bookingRequest->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Informations client</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Email:</span>
                            <span class="text-ivoire-text">{{ $bookingRequest->client->user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Téléphone:</span>
                            <span class="text-ivoire-text">{{ $bookingRequest->client->phone ?: 'Non renseigné' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Date de naissance:</span>
                            <span
                                class="text-ivoire-text">{{ $bookingRequest->client->birth_date ? $bookingRequest->client->birth_date->format('d/m/Y') : 'Non renseignée' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

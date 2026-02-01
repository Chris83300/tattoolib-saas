@extends('layouts.app')

@section('title', 'Chat avec ' . $bookingRequest->bookable->user->name)

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-6">
                <a href="{{ route('client.booking-requests') }}"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à mes demandes
                </a>

                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-ivoire-text">Chat avec
                            {{ $bookingRequest->bookable->user->name }}</h1>
                        <p class="text-ivoire-text/70 mt-1">Projet:
                            {{ Str::limit($bookingRequest->tattoo_description, 80) }}</p>
                    </div>
                </div>
            </div>

            <!-- Zone de chat -->
            <div class="bg-titane/20 rounded-xl border border-titane/30">
                <!-- Messages -->
                <div id="messages-container" class="h-96 overflow-y-auto p-6 space-y-4">
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
                                {{ $chatOpen ? 'Aucun message' : 'Chat fermé' }}
                            </h3>
                            <p class="text-ivoire-text/70">
                                @if (!$chatOpen)
                                    @if ($bookingRequest->status !== 'accepted')
                                        Le chat sera disponible après acceptation du projet
                                    @elseif($bookingRequest->deposit_paid_at)
                                        Le chat est fermé (acompte payé)
                                    @elseif($bookingRequest->deposit_deadline && $bookingRequest->deposit_deadline < now())
                                        Le délai de paiement est expiré
                                    @endif
                                @else
                                    Commencez la conversation avec l'artiste
                                @endif
                            </p>
                        </div>
                    @else
                        @foreach ($messages as $message)
                            <div class="flex {{ $message->sender_type === 'tattooer' ? 'justify-start' : 'justify-end' }}">
                                <div class="max-w-xs lg:max-w-md">
                                    <div
                                        class="{{ $message->sender_type === 'tattooer'
                                            ? 'bg-noir-profond text-ivoire-text'
                                            : 'bg-beige-peau text-noir-profond' }} rounded-lg px-4 py-2">
                                        <p class="text-sm whitespace-pre-wrap">{{ $message->content }}</p>

                                        @if ($message->getMedia('attachments')->isNotEmpty())
                                            <div class="mt-2 space-y-1">
                                                @foreach ($message->getMedia('attachments') as $media)
                                                    @if (str_starts_with($media->mime_type, 'image/'))
                                                        <img src="{{ $media->getUrl() }}" alt="Pièce jointe"
                                                            class="rounded max-w-full cursor-pointer hover:opacity-90"
                                                            onclick="window.open('{{ $media->getUrl() }}', '_blank')">
                                                    @else
                                                        <a href="{{ $media->getUrl() }}" target="_blank"
                                                            class="block text-xs underline">
                                                            📎 {{ $media->file_name }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
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
                    @if ($chatOpen)
                        <form action="{{ route('client.message.send', $bookingRequest) }}" method="POST"
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
                        <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3">
                            <p class="text-jaune-alerte text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @if ($bookingRequest->status !== 'accepted')
                                    En attente d'acceptation du projet
                                @elseif($bookingRequest->deposit_paid_at)
                                    Chat fermé (acompte payé). Utilisez les détails du projet pour plus d'informations.
                                @else
                                    Le délai de paiement est expiré. Contactez l'artiste pour plus d'informations.
                                @endif
                            </p>
                        </div>
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
                        @if ($bookingRequest->tattoo_style)
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Style:</span>
                                <span class="text-ivoire-text">{{ $bookingRequest->tattoo_style }}</span>
                            </div>
                        @endif
                        @if ($bookingRequest->estimated_price)
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Budget estimé:</span>
                                <span
                                    class="text-ivoire-text">{{ number_format($bookingRequest->estimated_price, 0) }}€</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Statut:</span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
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
                        </div>
                    </div>
                </div>

                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Artiste</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-beige-peau rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-ivoire-text">{{ $bookingRequest->bookable->user->name }}</p>
                            <p class="text-sm text-ivoire-text/70">
                                @if ($bookingRequest->bookable_type === 'App\Models\Tattooer')
                                    Tatoueur indépendant
                                @elseif($bookingRequest->bookable_type === 'App\Models\StudioArtist')
                                    Artiste de studio
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Scroll auto vers dernier message
            const container = document.getElementById('messages-container');
            if (container) container.scrollTop = container.scrollHeight;

            // Preview fichiers
            document.getElementById('attachments')?.addEventListener('change', function(e) {
                const preview = document.getElementById('file-preview');
                if (!preview) return;

                preview.innerHTML = '';
                preview.classList.remove('hidden');

                Array.from(e.target.files).forEach(file => {
                    const div = document.createElement('div');
                    div.className = 'px-3 py-1 bg-noir-profond text-ivoire-text rounded text-sm';
                    div.textContent = file.name;
                    preview.appendChild(div);
                });
            });
        </script>
    @endpush
@endsection

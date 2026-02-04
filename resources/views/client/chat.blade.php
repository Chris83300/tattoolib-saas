@extends('layouts.app')

@section('title', 'Chat avec ' . $bookingRequest->bookable->user->name)

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête avec alerte expiration -->
            <div class="mb-6">
                <a href="{{ route('client.booking-requests') }}"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour à mes demandes
                </a>

                @if ($expiryInfo && $expiryInfo['warning_message'])
                    <div
                        class="mb-4 p-4 rounded-lg border {{ $expiryInfo['is_expired'] ? 'bg-rouge-alerte/10 border-rouge-alerte/30' : 'bg-jaune-alerte/10 border-jaune-alerte/30' }}">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 {{ $expiryInfo['is_expired'] ? 'text-rouge-alerte' : 'text-jaune-alerte' }}"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                @if ($expiryInfo['is_expired'])
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @endif
                            </svg>
                            <div class="flex-1">
                                <h3
                                    class="font-semibold {{ $expiryInfo['is_expired'] ? 'text-rouge-alerte' : 'text-jaune-alerte' }} mb-1">
                                    @if ($expiryInfo['is_expired'])
                                        ❌ Conversation expirée
                                    @elseif($expiryInfo['expiry_type'] === 'deposit_pending')
                                        ⏰ Délai d'acompte
                                    @else
                                        ℹ️ Information
                                    @endif
                                </h3>
                                <p
                                    class="{{ $expiryInfo['is_expired'] ? 'text-rouge-alerte' : 'text-jaune-alerte' }} text-sm">
                                    {{ $expiryInfo['warning_message'] }}
                                </p>
                                @if (!$expiryInfo['is_expired'] && $expiryInfo['time_remaining'] !== '')
                                    <div class="mt-2">
                                        <div class="flex items-center justify-between text-sm">
                                            <span
                                                class="{{ $expiryInfo['days_remaining'] <= 2 ? 'text-rouge-alerte font-semibold' : 'text-jaune-alerte' }}">
                                                {{ $expiryInfo['time_remaining'] }} restant(es)
                                            </span>
                                            @if ($bookingRequest->status === 'awaiting_deposit')
                                                <a href="{{ route('deposit.payment', $bookingRequest->id) }}"
                                                    class="inline-flex items-center px-3 py-1 bg-beige-peau text-noir-profond rounded text-sm font-medium hover:bg-beige-peau/90 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                    </svg>
                                                    Payer l'acompte
                                                </a>
                                            @endif
                                        </div>
                                        @if ($expiryInfo['days_remaining'] <= 2)
                                            <div class="mt-2 bg-rouge-alerte/20 rounded p-2">
                                                <p class="text-rouge-alerte text-xs font-medium">
                                                    ⚠️ Urgent : Payer l'acompte avant la suppression du chat
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if (!$expiryInfo && $bookingRequest->status === 'awaiting_deposit')
                    <div class="mb-4 p-4 bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 text-jaune-alerte" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="flex-1">
                                <h3 class="font-semibold text-jaune-alerte mb-1">⏰ Acompte en attente</h3>
                                <p class="text-jaune-alerte text-sm">
                                    Payer l'acompte pour finaliser votre réservation
                                </p>
                                <div class="mt-2">
                                    <a href="{{ route('deposit.payment', $bookingRequest->id) }}"
                                        class="inline-flex items-center px-3 py-1 bg-beige-peau text-noir-profond rounded text-sm font-medium hover:bg-beige-peau/90 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                        Payer l'acompte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

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
                                    @if ($bookingRequest->status === 'pending')
                                        Le chat sera disponible après acceptation du projet
                                    @elseif($bookingRequest->deposit_paid_at)
                                        Le chat est fermé (acompte payé)
                                    @elseif($bookingRequest->client_payment_deadline && $bookingRequest->client_payment_deadline->lt(now()->subHours(24)))
                                        Le délai de paiement est expiré. Contactez l'artiste pour plus d'informations.
                                    @else
                                        Le chat est temporairement indisponible
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
                        @if (!$bookingRequest->deposit_paid_at)
                            <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3 mb-4">
                                <p class="text-jaune-alerte text-sm">
                                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Les pièces jointes sont désactivées jusqu'au paiement de l'acompte
                                </p>
                            </div>
                        @endif

                        <form action="{{ route('client.message.send', $conversation) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-3" x-data="{
                                message: '',
                                attachments: [],
                                resizeTextarea() {
                                    const textarea = this.$refs.messageInput;
                                    textarea.style.height = 'auto';
                                    textarea.style.height = textarea.scrollHeight + 'px';
                                },
                                handleFileSelect(event) {
                                    const files = event.target.files;
                                    this.attachments = Array.from(files);
                                },
                                removeFile(index) {
                                    this.attachments.splice(index, 1);
                                }
                            }"
                            @submit="message = ''">
                            @csrf

                            @if ($bookingRequest->deposit_paid_at)
                                <div class="flex gap-2">
                                    <input type="file" name="attachments[]" id="attachments" multiple
                                        accept="image/*,application/pdf" class="hidden"
                                        @change="handleFileSelect($event)">

                                    <button type="button" onclick="document.getElementById('attachments').click()"
                                        class="px-4 py-3 bg-noir-profond text-ivoire-text rounded-lg hover:bg-noir-profond/80 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                    </button>

                                    <textarea x-ref="messageInput" x-model="message" @input="resizeTextarea()" name="content" rows="1"
                                        placeholder="Votre message..."
                                        class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none overflow-hidden"
                                        style="min-height: 3rem; max-height: 10rem;"
                                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>

                                    <button type="submit"
                                        class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                        Envoyer
                                    </button>
                                </div>
                            @else
                                <div class="flex gap-2">
                                    <textarea x-ref="messageInput" x-model="message" @input="resizeTextarea()" name="content" rows="1"
                                        placeholder="Votre message..."
                                        class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none overflow-hidden"
                                        style="min-height: 3rem; max-height: 10rem;"
                                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"></textarea>

                                    <button type="submit"
                                        class="px-6 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                        Envoyer
                                    </button>
                                </div>
                            @endif

                            <!-- Prévisualisation des fichiers -->
                            <div x-show="attachments.length > 0" class="mt-2 flex gap-2 flex-wrap">
                                <template x-for="(file, index) in attachments" :key="index">
                                    <div class="relative inline-block">
                                        <img :src="URL.createObjectURL(file)" :alt="file.name"
                                            class="w-20 h-20 rounded-lg object-cover">
                                        <button type="button" @click="removeFile(index)"
                                            class="absolute -top-2 -right-2 bg-rouge-alerte text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-rouge-alerte/80">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </form>
                    @else
                        <div class="bg-jaune-alerte/10 border border-jaune-alerte/30 rounded-lg p-3">
                            <p class="text-jaune-alerte text-sm">
                                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @if ($bookingRequest->status === 'pending')
                                    En attente d'acceptation du projet
                                @elseif($bookingRequest->deposit_paid_at)
                                    Chat fermé (acompte payé). Utilisez les détails du projet pour plus d'informations.
                                @elseif($bookingRequest->client_payment_deadline && $bookingRequest->client_payment_deadline->lt(now()->subHours(24)))
                                    Le délai de paiement est expiré. Contactez l'artiste pour plus d'informations.
                                @else
                                    Le chat est temporairement indisponible
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Informations projet et artiste (accordéons mobile uniquement) -->
            <div class="md:hidden mt-6 space-y-4">
                <!-- Détails du projet -->
                <div class="bg-titane/20 rounded-xl border border-titane/30 overflow-hidden" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-titane/30 transition-colors">
                        <h3 class="text-lg font-bold text-ivoire-text">Détails du projet</h3>
                        <svg class="w-5 h-5 text-ivoire-text transition-transform" :class="{ 'rotate-180': open }"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                        <div class="space-y-2 text-sm">
                            @if ($bookingRequest->body_zone)
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Emplacement:</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->body_zone }}</span>
                                </div>
                            @endif
                            @if ($bookingRequest->tattoo_size)
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Taille:</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->tattoo_size }}</span>
                                </div>
                            @endif
                            @if ($bookingRequest->tattoo_style)
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Style:</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->tattoo_style }}</span>
                                </div>
                            @endif
                            @if ($bookingRequest->price_range_min && $bookingRequest->price_range_max)
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                    <span
                                        class="text-ivoire-text">{{ number_format($bookingRequest->price_range_min, 0) }}€
                                        -
                                        {{ number_format($bookingRequest->price_range_max, 0) }}€</span>
                                </div>
                            @elseif ($bookingRequest->estimated_total_price)
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                    <span
                                        class="text-ivoire-text">{{ number_format($bookingRequest->estimated_total_price, 0) }}€</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Statut:</span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ match ($bookingRequest->status) {
                                    'pending' => 'bg-ambre-warning/20 text-ambre-warning',
                                    'accepted' => 'bg-vert-succes/20 text-vert-succes',
                                    'awaiting_deposit' => 'bg-vert-succes/20 text-vert-succes',
                                    'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                                    'in_progress' => 'bg-beige-peau/20 text-beige-peau',
                                    'completed' => 'bg-vert-succes/20 text-vert-succes',
                                    'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                    default => 'bg-gris-fonde/20 text-ivoire-text',
                                } }}">
                                    {{ match ($bookingRequest->status) {
                                        'pending' => '⏳ En attente',
                                        'accepted' => '✅ Acceptée',
                                        'awaiting_deposit' => '⏳ Acompte attendu',
                                        'deposit_paid' => '💰 Acompte payé',
                                        'in_progress' => '🎨 En cours',
                                        'completed' => '✅ Terminé',
                                        'cancelled' => '❌ Annulée',
                                        default => ucfirst($bookingRequest->status),
                                    } }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations artiste -->
                @if ($bookingRequest->bookable)
                    <div class="bg-titane/20 rounded-xl border border-titane/30 overflow-hidden" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="w-full px-6 py-4 flex items-center justify-between hover:bg-titane/30 transition-colors">
                            <h3 class="text-lg font-bold text-ivoire-text">Informations artiste</h3>
                            <svg class="w-5 h-5 text-ivoire-text transition-transform" :class="{ 'rotate-180': open }"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2" class="px-6 pb-4">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Nom:</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->bookable->user->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Type:</span>
                                    <span class="text-ivoire-text">
                                        @if ($bookingRequest->bookable_type === 'App\Models\Tattooer')
                                            Tatoueur indépendant
                                        @elseif($bookingRequest->bookable_type === 'App\Models\StudioArtist')
                                            Artiste de studio
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Email:</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->bookable->user->email }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Version desktop (toujours visible) -->
            <div class="hidden md:grid md:grid-cols-2 md:gap-6 md:mt-6">
                <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Détails du projet</h3>
                    <div class="space-y-2 text-sm">
                        @if ($bookingRequest->body_zone)
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Emplacement:</span>
                                <span class="text-ivoire-text">{{ $bookingRequest->body_zone }}</span>
                            </div>
                        @endif
                        @if ($bookingRequest->tattoo_size)
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Taille:</span>
                                <span class="text-ivoire-text">{{ $bookingRequest->tattoo_size }}</span>
                            </div>
                        @endif
                        @if ($bookingRequest->tattoo_style)
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Style:</span>
                                <span class="text-ivoire-text">{{ $bookingRequest->tattoo_style }}</span>
                            </div>
                        @endif
                        @if ($bookingRequest->price_range_min && $bookingRequest->price_range_max)
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                <span class="text-ivoire-text">{{ number_format($bookingRequest->price_range_min, 0) }}€ -
                                    {{ number_format($bookingRequest->price_range_max, 0) }}€</span>
                            </div>
                        @elseif ($bookingRequest->estimated_total_price)
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Estimation tattoo:</span>
                                <span
                                    class="text-ivoire-text">{{ number_format($bookingRequest->estimated_total_price, 0) }}€</span>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-ivoire-text/70">Statut:</span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ match ($bookingRequest->status) {
                                'pending' => 'bg-ambre-warning/20 text-ambre-warning',
                                'accepted' => 'bg-vert-succes/20 text-vert-succes',
                                'awaiting_deposit' => 'bg-vert-succes/20 text-vert-succes',
                                'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                                'in_progress' => 'bg-beige-peau/20 text-beige-peau',
                                'completed' => 'bg-vert-succes/20 text-vert-succes',
                                'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                default => 'bg-gris-fonde/20 text-ivoire-text',
                            } }}">
                                {{ match ($bookingRequest->status) {
                                    'pending' => '⏳ En attente',
                                    'accepted' => '✅ Acceptée',
                                    'awaiting_deposit' => '⏳ Acompte attendu',
                                    'deposit_paid' => '💰 Acompte payé',
                                    'in_progress' => '🎨 En cours',
                                    'completed' => '✅ Terminé',
                                    'cancelled' => '❌ Annulée',
                                    default => ucfirst($bookingRequest->status),
                                } }}
                            </span>
                        </div>
                    </div>
                </div>

                @if ($bookingRequest->bookable)
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Informations artiste</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Nom:</span>
                                <span class="text-ivoire-text">{{ $bookingRequest->bookable->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Type:</span>
                                <span class="text-ivoire-text">
                                    @if ($bookingRequest->bookable_type === 'App\Models\Tattooer')
                                        Tatoueur indépendant
                                    @elseif($bookingRequest->bookable_type === 'App\Models\StudioArtist')
                                        Artiste de studio
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Email:</span>
                                <span class="text-ivoire-text">{{ $bookingRequest->bookable->user->email }}</span>
                            </div>
                            class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-beige-peau rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                @endif
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

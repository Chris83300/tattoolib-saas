@extends('layouts.app')

@section('title', 'Détails de la demande')

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
                        <h1 class="text-3xl font-bold text-ivoire-text">Détails de la demande</h1>
                        <p class="text-ivoire-text/70 mt-1">Artiste: {{ $bookingRequest->bookable->user->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Détails du projet -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h2 class="text-xl font-bold text-ivoire-text mb-4">Détails du projet</h2>

                        <div class="space-y-4">
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Description :</span>
                                <p class="text-ivoire-text">{{ $bookingRequest->description ?: 'Non définie' }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Emplacement :</span>
                                    <span
                                        class="text-ivoire-text font-semibold">{{ $bookingRequest->body_zone ?: 'Non défini' }}</span>
                                </div>
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Taille :</span>
                                    <span
                                        class="text-ivoire-text font-semibold">{{ $bookingRequest->tattoo_size ?: 'Non défini' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Prix estimé :</span>
                                    <span class="text-ivoire-text font-semibold">
                                        {{ $bookingRequest->estimated_total_price ? number_format($bookingRequest->estimated_total_price, 2, ',', ' ') . ' €' : 'Non défini' }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Date souhaitée :</span>
                                    <span class="text-ivoire-text font-semibold">
                                        {{ $bookingRequest->preferred_date ? $bookingRequest->preferred_date->format('d/m/Y') : 'Non définie' }}
                                    </span>
                                </div>
                            </div>

                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Statut :</span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if ($bookingRequest->status === 'pending') bg-jaune-alerte/20 text-jaune-alerte
                            @elseif($bookingRequest->status === 'accepted') bg-vert-succes/20 text-vert-succes
                            @elseif($bookingRequest->status === 'awaiting_deposit') bg-jaune-alerte/20 text-jaune-alerte
                            @elseif($bookingRequest->status === 'in_progress') bg-beige-peau/20 text-beige-peau
                            @elseif($bookingRequest->status === 'completed') bg-vert-succes/20 text-vert-succes
                            @elseif($bookingRequest->status === 'cancelled') bg-rouge-alerte/20 text-rouge-alerte @endif">
                                    {{ match ($bookingRequest->status) {
                                        'pending' => '⏳ En attente',
                                        'accepted' => '✓ Acceptée',
                                        'awaiting_deposit' => '💰 Acompte en attente',
                                        'in_progress' => '🎨 En cours',
                                        'completed' => '✅ Terminée',
                                        'cancelled' => '❌ Annulée',
                                        default => ucfirst($bookingRequest->status),
                                    } }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Images de référence -->
                    @if ($bookingRequest->getMedia('reference_images')->isNotEmpty())
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h2 class="text-xl font-bold text-ivoire-text mb-4">Images de référence</h2>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($bookingRequest->getMedia('reference_images') as $media)
                                    <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond">
                                        <img src="{{ $media->getUrl() }}" alt="Référence"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300 cursor-pointer"
                                            onclick="openLightbox('{{ $media->getUrl() }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- PROPOSITION TATTOOER — visible dès que status >= accepted -->
                    @if (in_array($bookingRequest->status, [
                            'accepted',
                            'awaiting_deposit',
                            'deposit_paid',
                            'design_sent',
                            'confirmed',
                            'completed',
                        ]))
                        <div
                            class="bg-gradient-to-br from-vert-succes/10 to-vert-succes/5 rounded-xl p-6 border border-vert-succes/30">
                            <h3 class="text-xl font-bold text-ivoire-text mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Proposition du tattooer
                            </h3>

                            <div class="space-y-4">
                                <!-- 💰 Fourchette prix -->
                                @if ($bookingRequest->price_range_min || $bookingRequest->price_range_max)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif estimé</h4>
                                        <p class="text-ivoire-text">
                                            Entre <span
                                                class="font-bold text-beige-peau">{{ number_format($bookingRequest->price_range_min, 2, ',', ' ') }}
                                                €</span>
                                            et <span
                                                class="font-bold text-beige-peau">{{ number_format($bookingRequest->price_range_max, 2, ',', ' ') }}
                                                €</span>
                                        </p>
                                        @if ($bookingRequest->estimated_total_price)
                                            <p class="text-ivoire-text/60 text-sm mt-1">
                                                Estimation finale : <span
                                                    class="text-ivoire-text font-semibold">{{ number_format($bookingRequest->estimated_total_price, 2, ',', ' ') }}
                                                    €</span>
                                            </p>
                                        @endif
                                    </div>
                                @endif

                                <!-- 📅 Dates proposées -->
                                @if (
                                    $bookingRequest->proposed_dates &&
                                        is_array($bookingRequest->proposed_dates) &&
                                        count($bookingRequest->proposed_dates) > 0)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">📅 Dates proposées</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($bookingRequest->proposed_dates as $date)
                                                <span
                                                    class="px-3 py-1 bg-beige-peau/20 text-beige-peau rounded-full text-sm font-medium">
                                                    {{ \Carbon\Carbon::parse($date)->format('l d/m/Y') }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- 🎨 Phase création -->
                                @if ($bookingRequest->included_design_versions)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">🎨 Phase création</h4>
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-ivoire-text/60">Dessins inclus :</span>
                                                <span
                                                    class="text-ivoire-text font-semibold ml-2">{{ $bookingRequest->included_design_versions }}</span>
                                            </div>
                                            <div>
                                                <span class="text-ivoire-text/60">Modifs/dessin :</span>
                                                <span
                                                    class="text-ivoire-text font-semibold ml-2">{{ $bookingRequest->modifications_per_version ?? 2 }}</span>
                                            </div>
                                            @if ($bookingRequest->design_modification_rules)
                                                <div>
                                                    <span class="text-ivoire-text/60">Règles de modification :</span>
                                                    <p class="text-ivoire-text mt-1">
                                                        {{ $bookingRequest->design_modification_rules }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Acompte -->
                                @if ($bookingRequest->total_deposit_amount)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text mb-2">Acompte</h4>
                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-ivoire-text/60">Montant :</span>
                                                <span
                                                    class="text-ivoire-text font-semibold">{{ number_format($bookingRequest->total_deposit_amount, 2, ',', ' ') }}€</span>
                                            </div>
                                            @if ($bookingRequest->client_payment_deadline)
                                                <div class="flex justify-between">
                                                    <span class="text-ivoire-text/60">Date limite :</span>
                                                    <span class="text-ivoire-text">
                                                        {{ is_string($bookingRequest->client_payment_deadline)
                                                            ? \Carbon\Carbon::parse($bookingRequest->client_payment_deadline)->format('d/m/Y')
                                                            : $bookingRequest->client_payment_deadline->format('d/m/Y') }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if ($bookingRequest->deposit_covers_description)
                                                <div>
                                                    <span class="text-ivoire-text/60">Ce que couvre l'acompte :</span>
                                                    <p class="text-ivoire-text mt-1">
                                                        {{ $bookingRequest->deposit_covers_description }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- SECTION ACTIONS -->
                    @if (in_array($bookingRequest->status, ['accepted', 'awaiting_deposit']) && !$bookingRequest->deposit_paid_at)
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">

                                <!-- BOUTON PAYER ACOMPTE -->
                                @if ($bookingRequest->total_deposit_amount && $bookingRequest->total_deposit_amount > 0)
                                    <a href="{{ route('deposit.payment', $bookingRequest) }}"
                                        class="block w-full px-4 py-3 bg-vert-succes text-noir-profond rounded-xl font-bold text-center hover:bg-vert-succes/90 transition-all">
                                        💰 Payer l'acompte —
                                        {{ number_format($bookingRequest->total_deposit_amount, 2, ',', ' ') }} €
                                    </a>

                                    <!-- Délai paiement -->
                                    @if ($bookingRequest->client_payment_deadline)
                                        <p class="text-ivoire-text/60 text-sm text-center">
                                            Délai : jusqu'au
                                            {{ $bookingRequest->client_payment_deadline->format('d/m/Y') }}
                                        </p>
                                    @endif
                                @endif

                                <!-- BOUTON ANNULER -->
                                <form action="{{ route('client.booking-request.cancel', $bookingRequest) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Annuler cette demande ? Cette action est irréversible.')"
                                        class="block w-full px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl font-semibold text-center hover:bg-rouge-alerte/30 transition-all">
                                        ✕ Annuler la demande
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Si acompte déjà payé, montrer le chat + option annulation différente -->
                    @elseif ($bookingRequest->deposit_paid_at)
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">
                                <!-- Acompte payé - badge confirmation -->
                                <div
                                    class="bg-vert-succes/10 border border-vert-succes/30 rounded-lg px-4 py-2 text-center">
                                    <span class="text-vert-succes font-semibold">✓ Acompte payé</span>
                                </div>

                                <!-- Chat avec l'artiste -->
                                @if ($bookingRequest->conversation)
                                    <a href="{{ route('client.chat', $bookingRequest->conversation) }}"
                                        class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-xl font-bold text-center hover:bg-beige-peau/90 transition-all">
                                        💬 Discuter avec l'artiste
                                    </a>
                                @endif

                                <!-- Annulation après acompte -->
                                <form action="{{ route('client.booking-request.cancel', $bookingRequest) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        onclick="return confirm('Annuler après paiement d\'acompte implique des conditions de remboursement. Continuer ?')"
                                        class="block w-full px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl font-semibold text-center hover:bg-rouge-alerte/30 transition-all">
                                        ✕ Annuler la demande
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Colonne latérale -->
                <div class="space-y-6">
                    <!-- Informations artiste -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Artiste</h3>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-beige-peau/10">
                                @if ($bookingRequest->bookable->getFirstMediaUrl('avatar'))
                                    <img src="{{ $bookingRequest->bookable->getFirstMediaUrl('avatar') }}"
                                        alt="Avatar de {{ $bookingRequest->bookable->user->name }}"
                                        class="w-full h-full object-cover">
                                @elseif ($bookingRequest->bookable->user->getFirstMediaUrl('avatar'))
                                    <img src="{{ $bookingRequest->bookable->user->getFirstMediaUrl('avatar') }}"
                                        alt="Avatar de {{ $bookingRequest->bookable->user->name }}"
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

                    <!-- Timeline -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Historique</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div class="w-2 h-2 bg-beige-peau rounded-full mt-2"></div>
                                <div>
                                    <p class="text-ivoire-text font-semibold">Demande créée</p>
                                    <p class="text-ivoire-text/70 text-sm">
                                        {{ $bookingRequest->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                            </div>

                            @if ($bookingRequest->accepted_at)
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-vert-succes rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-ivoire-text font-semibold">Demande acceptée</p>
                                        <p class="text-ivoire-text/70 text-sm">
                                            @if ($bookingRequest->accepted_at)
                                                {{ is_string($bookingRequest->accepted_at) ? \Carbon\Carbon::parse($bookingRequest->accepted_at)->format('d/m/Y à H:i') : $bookingRequest->accepted_at->format('d/m/Y à H:i') }}
                                            @else
                                                Non défini
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($bookingRequest->cancelled_at)
                                <div class="flex items-start space-x-3">
                                    <div class="w-2 h-2 bg-rouge-alerte rounded-full mt-2"></div>
                                    <div>
                                        <p class="text-ivoire-text font-semibold">Demande refusée</p>
                                        <p class="text-ivoire-text/70 text-sm">
                                            @if ($bookingRequest->cancelled_at)
                                                {{ is_string($bookingRequest->cancelled_at) ? \Carbon\Carbon::parse($bookingRequest->cancelled_at)->format('d/m/Y à H:i') : $bookingRequest->cancelled_at->format('d/m/Y à H:i') }}
                                            @else
                                                Non défini
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox -->
    <script>
        function openLightbox(imageUrl) {
            const lightbox = document.createElement('div');
            lightbox.className = 'fixed inset-0 bg-black/90 flex items-center justify-center z-50 p-4';
            lightbox.onclick = () => lightbox.remove();

            const img = document.createElement('img');
            img.src = imageUrl;
            img.className = 'max-w-full max-h-full object-contain rounded-lg';

            lightbox.appendChild(img);
            document.body.appendChild(lightbox);
        }
    </script>
@endsection

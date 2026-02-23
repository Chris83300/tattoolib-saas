@extends('layouts.client')

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
                        <p class="text-ivoire-text/70 mt-1">Artiste: <span
                                class="font-semibold text-cuivre">{{ $bookingRequest->bookable->user->pseudo }}</span></p>
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

                            @if ($bookingRequest->bookable_type === 'App\\Models\\Piercer')
                                <!-- Champs spécifiques aux piercings -->
                                <div class="bg-noir-profond/30 rounded-lg p-4">
                                    <h4 class="text-ivoire-text font-semibold mb-3">💍 Détails du piercing</h4>
                                    <div class="space-y-2 text-sm">
                                        {{-- Parser la description pour extraire les infos de piercing --}}
                                        @php
                                            $description = $bookingRequest->description;
                                            $type = '';
                                            $precision = '';
                                            $specialRequest = '';

                                            // Extraire les informations de la description formatée
                                            if (preg_match('/Type\s*:\s*([^\n]+)/', $description, $matches)) {
                                                $type = trim($matches[1]);
                                            }
                                            if (preg_match('/Précisions\s*:\s*([^\n]+)/', $description, $matches)) {
                                                $precision = trim($matches[1]);
                                            }
                                            if (
                                                preg_match(
                                                    '/Demande spécifique\s*:\s*([^\n]+)/',
                                                    $description,
                                                    $matches,
                                                )
                                            ) {
                                                $specialRequest = trim($matches[1]);
                                            }
                                        @endphp

                                        @if ($type)
                                            <div class="flex justify-between">
                                                <span class="text-ivoire-text/60">Type :</span>
                                                <span class="text-ivoire-text font-semibold">{{ $type }}</span>
                                            </div>
                                        @endif

                                        @if ($precision)
                                            <div class="flex justify-between">
                                                <span class="text-ivoire-text/60">Précisions :</span>
                                                <span class="text-ivoire-text font-semibold">{{ $precision }}</span>
                                            </div>
                                        @endif

                                        @if ($specialRequest)
                                            <div>
                                                <span class="text-ivoire-text/60 block mb-1">Demande spécifique :</span>
                                                <span class="text-ivoire-text">{{ $specialRequest }}</span>
                                            </div>
                                        @endif

                                        {{-- Afficher le tarif si disponible --}}
                                        @if ($type && $bookingRequest->bookable && method_exists($bookingRequest->bookable, 'getPricingForType'))
                                            @php
                                                $price = $bookingRequest->bookable->getPricingForType($type);
                                            @endphp
                                            @if ($price)
                                                <div class="mt-3 pt-3 border-t border-titane/30">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-ivoire-text/60">Tarif :</span>
                                                        <span
                                                            class="text-beige-peau font-bold text-lg">{{ number_format($price, 2, ',', ' ') }}
                                                            €</span>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Champs spécifiques aux tatouages -->
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
                            @endif

                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Statut :</span>
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



                    <!-- SECTION ACTIONS -->
                    @if (in_array($bookingRequest->status->value, [
                            \App\Enums\BookingRequestStatus::ACCEPTED->value,
                            \App\Enums\BookingRequestStatus::DEPOSIT_REQUESTED->value,
                        ]) && !$bookingRequest->deposit_paid_at)
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
                                @if ($bookingRequest->bookable->user->getFirstMediaUrl('avatar'))
                                    <img src="{{ $bookingRequest->bookable->user->getFirstMediaUrl('avatar') }}"
                                        alt="Avatar de {{ $bookingRequest->bookable->user->pseudo }}"
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
                                <p class="font-semibold text-ivoire-text">{{ $bookingRequest->bookable->user->pseudo }}</p>
                                <p class="text-sm text-ivoire-text/70">
                                    @if ($bookingRequest->bookable_type === 'App\\Models\\Tattooer')
                                        Tatoueur indépendant
                                    @elseif($bookingRequest->bookable_type === 'App\\Models\\StudioArtist')
                                        Artiste de studio
                                    @elseif($bookingRequest->bookable_type === 'App\\Models\\Piercer')
                                        Piercer professionnel
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        @if ($bookingRequest->bookable->studio_name)
                            <div class="mb-3">
                                <span class="text-ivoire-text/70 text-sm">Studio :</span>
                                <p class="text-ivoire-text font-semibold">{{ $bookingRequest->bookable->studio_name }}</p>
                            </div>
                        @endif

                        @if ($bookingRequest->bookable->specialties && $bookingRequest->bookable->specialties->isNotEmpty())
                            <div>
                                <span class="text-ivoire-text/70 text-sm">Spécialités :</span>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach ($bookingRequest->bookable->specialties->take(3) as $specialty)
                                        <span class="px-2 py-1 bg-beige-peau/20 text-beige-peau text-xs rounded-full">
                                            {{ $specialty->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- PROPOSITION TATTOOER — visible dès que status >= accepted -->
                    @if (in_array($bookingRequest->status->value, [
                            \App\Enums\BookingRequestStatus::ACCEPTED->value,
                            \App\Enums\BookingRequestStatus::DEPOSIT_REQUESTED->value,
                            \App\Enums\BookingRequestStatus::DEPOSIT_PAID->value,
                            \App\Enums\BookingRequestStatus::DATE_CONFIRMED->value,
                            \App\Enums\BookingRequestStatus::COMPLETED->value,
                        ]))
                        <div
                            class="bg-gradient-to-br from-vert-succes/10 to-vert-succes/5 rounded-xl p-6 border border-vert-succes/30">
                            <h3 class="text-xl font-bold text-ivoire-text mb-4 flex items-center gap-2">
                                <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Proposition du @if ($bookingRequest->bookable_type === 'App\\Models\\Piercer')
                                    piercer
                                @else
                                    tattooer
                                @endif
                            </h3>

                            <div class="space-y-4">
                                <!-- 💰 Fourchette prix -->
                                @if ($bookingRequest->bookable_type === 'App\\Models\\Piercer' && $bookingRequest->total_deposit_amount)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif</h4>
                                        <p class="text-ivoire-text">
                                            <span
                                                class="font-bold text-beige-peau">{{ number_format($bookingRequest->total_deposit_amount, 2, ',', ' ') }}€</span>
                                        </p>
                                    </div>
                                @elseif ($bookingRequest->price_estimate_min || $bookingRequest->price_estimate_max)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif estimé</h4>
                                        <p class="text-ivoire-text">
                                            Entre <span
                                                class="font-bold text-beige-peau">{{ number_format($bookingRequest->price_estimate_min, 2, ',', ' ') }}
                                                €</span>
                                            et <span
                                                class="font-bold text-beige-peau">{{ number_format($bookingRequest->price_estimate_max, 2, ',', ' ') }}
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

                                <!-- 📅 Sélection de dates -->
                                @if (
                                    $bookingRequest->status->value === 'deposit_paid' &&
                                        $bookingRequest->proposed_dates &&
                                        !$bookingRequest->confirmed_date)

                                    <div class="bg-gris-fonde rounded-xl p-4 border border-beige-peau/20 mt-4">
                                        <h4 class="font-semibold text-ivoire-text mb-2">📅 Choisissez votre date de
                                            rendez-vous</h4>
                                        <p class="text-sm text-titane mb-4">
                                            L'artiste vous propose {{ count($bookingRequest->proposed_dates) }} date(s).
                                            Sélectionnez celle qui vous convient — l'artiste fixera ensuite l'horaire exact.
                                        </p>

                                        <div class="space-y-2">
                                            @foreach ($bookingRequest->proposed_dates as $index => $proposal)
                                                @php
                                                    $proposalDate = \Carbon\Carbon::parse($proposal['date']);
                                                    $periodLabel = match ($proposal['period'] ?? '') {
                                                        'morning' => '☀️ Matin',
                                                        'afternoon' => '🌤️ Après-midi',
                                                        'evening' => '🌙 Soirée',
                                                        default => '🔄 Flexible',
                                                    };
                                                    $medal = match ($index) {
                                                        0 => '🥇',
                                                        1 => '🥈',
                                                        2 => '🥉',
                                                        default => '📅',
                                                    };
                                                @endphp

                                                <form
                                                    action="{{ route('client.booking-request.select-date', $bookingRequest) }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" name="index" value="{{ $index }}">
                                                    <button type="submit"
                                                        onclick="return confirm('Confirmer la date du {{ $proposalDate->translatedFormat('l d F Y') }} ({{ strip_tags($periodLabel) }}) ?')"
                                                        class="w-full flex items-center justify-between p-4 rounded-lg border border-titane/30
                                                               hover:border-beige-peau hover:bg-beige-peau/10 cursor-pointer transition-all">
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-2xl">{{ $medal }}</span>
                                                            <div class="text-left">
                                                                <p class="text-ivoire-text font-medium">
                                                                    {{ $proposalDate->translatedFormat('l d F Y') }}
                                                                </p>
                                                                <p class="text-xs text-titane">{{ $periodLabel }}</p>
                                                            </div>
                                                        </div>
                                                        <span class="text-beige-peau font-bold text-sm">Choisir →</span>
                                                    </button>
                                                </form>
                                            @endforeach
                                        </div>

                                        <form
                                            action="{{ route('client.booking-request.request-alternatives', $bookingRequest) }}"
                                            method="POST" class="mt-3">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs text-titane underline hover:text-ivoire-text">
                                                Aucune date ne me convient — demander d'autres propositions
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                <!-- Date confirmée (en attente de fixation horaire par le tattooer) -->
                                @if ($bookingRequest->confirmed_date && !$bookingRequest->appointment_datetime)
                                    <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4 mt-4">
                                        <h4 class="font-semibold text-vert-succes mb-1">✅ Date choisie</h4>
                                        <p class="text-ivoire-text">
                                            {{ \Carbon\Carbon::parse($bookingRequest->confirmed_date)->translatedFormat('l d F Y') }}
                                            @if ($bookingRequest->confirmed_period)
                                                —
                                                {{ match ($bookingRequest->confirmed_period) {
                                                    'morning' => '☀️ Matin',
                                                    'afternoon' => '🌤️ Après-midi',
                                                    'evening' => '🌙 Soirée',
                                                    default => '',
                                                } }}
                                            @endif
                                        </p>
                                        <p class="text-xs text-titane mt-1">⏳ L'artiste va fixer l'horaire exact. Vous
                                            serez notifié.</p>
                                    </div>
                                @endif

                                <!-- RDV confirmé avec horaire -->
                                @if ($bookingRequest->appointment_datetime)
                                    <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4 mt-4">
                                        <h4 class="font-semibold text-vert-succes mb-1">✅ Rendez-vous confirmé</h4>
                                        <p class="text-ivoire-text">
                                            {{ \Carbon\Carbon::parse($bookingRequest->appointment_datetime)->translatedFormat('l d F Y') }}
                                            de {{ $bookingRequest->scheduled_start_time }} à
                                            {{ $bookingRequest->scheduled_end_time }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Affichage simple si statut différent -->
                                @if (
                                    $bookingRequest->status !== 'deposit_paid' &&
                                        $bookingRequest->proposed_dates &&
                                        is_array($bookingRequest->proposed_dates) &&
                                        count($bookingRequest->proposed_dates) > 0)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">📅 Dates proposées</h4>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($bookingRequest->proposed_dates as $dateProposed)
                                                <span
                                                    class="px-3 py-1 bg-beige-peau/20 text-beige-peau rounded-full text-sm font-medium">
                                                    {{ \Carbon\Carbon::parse($dateProposed['date'])->format('l d/m/Y') }}
                                                    @if ($dateProposed['period'])
                                                        -
                                                        {{ $dateProposed['period'] === 'morning' ? 'Matin' : 'Après-midi' }}
                                                    @endif
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- 🎨 Phase création -->
                                @if ($bookingRequest->included_design_versions && $bookingRequest->bookable_type !== 'App\\Models\\Piercer')
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
                                                    class="text-ivoire-text font-semibold ml-2">{{ $bookingRequest->modifications_per_design ?? 2 }}</span>
                                            </div>
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
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- SECTION ACTIONS - Pour statut ACCEPTED -->
                    @if ($bookingRequest->status->value === \App\Enums\BookingRequestStatus::ACCEPTED->value)
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">
                                <!-- Chat avec l'artiste -->
                                @if ($bookingRequest->conversation)
                                    <a href="{{ route('client.chat', $bookingRequest->conversation) }}"
                                        class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-xl font-bold text-center hover:bg-beige-peau/90 transition-all">
                                        💬 Discuter avec l'artiste
                                    </a>
                                @endif

                                <!-- Annulation -->
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
                    @endif

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

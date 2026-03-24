@extends('layouts.tattooer')

@section('title', 'Détails de la demande')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Messages flash -->
            @if (session()->has('success'))
                <div class="mb-6 bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-vert-succes mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-vert-succes font-medium">{{ session()->get('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-rouge-alerte mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-rouge-alerte font-medium">{{ session()->get('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- En-tête -->
            <div class="mb-8">
                <a href="{{ route($tattooer->routePrefix() . '.requests') }}"
                    class="inline-flex items-center text-ivoire-text/80 hover:text-ivoire-text mb-4">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux demandes
                </a>

                <h1 class="text-3xl font-bold text-ivoire-text">Détails de la demande</h1>
            </div>

            <!-- Contenu principal -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Colonne principale -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Informations client -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h2 class="text-xl font-bold text-ivoire-text mb-4">Informations client</h2>

                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Nom complet :</span>
                                <span class="text-ivoire-text font-semibold">
                                    {{ $bookingRequest->client?->first_name }} {{ $bookingRequest->client?->last_name }}
                                </span>
                            </div>
                            @if ($bookingRequest->client?->pseudo)
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Pseudo :</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->client->pseudo }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Email :</span>
                                <span
                                    class="text-ivoire-text">{{ $bookingRequest->client?->user?->email ?: 'Non renseigné' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Téléphone :</span>
                                <span
                                    class="text-ivoire-text">{{ $bookingRequest->client?->phone ?: 'Non renseigné' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-ivoire-text/70">Date de naissance :</span>
                                <span class="text-ivoire-text">
                                    @if ($bookingRequest->client?->birth_date)
                                        {{ $bookingRequest->client->birth_date->format('d/m/Y') }}
                                        ({{ $bookingRequest->client->birth_date->age }} ans)
                                    @else
                                        Non renseignée
                                    @endif
                                </span>
                            </div>
                            @if ($bookingRequest->client?->address)
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Adresse :</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->client->address }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Détails du projet -->
                    <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30">
                        <h2 class="text-xl font-bold text-ivoire-text mb-4">
                            @if (auth()->user()->isPiercer())
                                Détails du piercing
                            @else
                                Détails du projet
                            @endif
                        </h2>

                        @if (auth()->user()->isPiercer())
                            @php
                                $descriptionLines = explode("\n", $bookingRequest->description);
                                $typeLine = collect($descriptionLines)->first(
                                    fn($line) => str_contains($line, 'Type :'),
                                );
                                $precisionsLine = collect($descriptionLines)->first(
                                    fn($line) => str_contains($line, 'Précisions :'),
                                );
                                $specialRequestLine = collect($descriptionLines)->first(
                                    fn($line) => str_contains($line, 'Demande spécifique :'),
                                );
                            @endphp

                            <div class="space-y-3">
                                @if ($typeLine)
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Type de piercing :</p>
                                        <p class="text-ivoire-text font-semibold">
                                            {{ str_replace('Type : ', '', $typeLine) }}</p>
                                    </div>
                                @endif

                                @if ($precisionsLine)
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Précisions :</p>
                                        <p class="text-ivoire-text">{{ str_replace('Précisions : ', '', $precisionsLine) }}
                                        </p>
                                    </div>
                                @endif

                                @if ($specialRequestLine)
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Demande spécifique :</p>
                                        <p class="text-ivoire-text">
                                            {{ str_replace('Demande spécifique : ', '', $specialRequestLine) }}</p>
                                    </div>
                                @endif

                                <div>
                                    <p class="text-ivoire-text/60 text-sm">Zone du corps :</p>
                                    <p class="text-ivoire-text font-semibold">{{ $bookingRequest->body_zone }}</p>
                                </div>

                                @if ($bookingRequest->total_deposit_amount)
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">💰 Tarif pour ce type</p>
                                        <p class="text-ivoire-text font-bold text-beige-peau">
                                            {{ number_format($bookingRequest->total_deposit_amount, 2, ',', ' ') }} €</p>
                                    </div>
                                @endif

                                @if ($bookingRequest->estimated_budget)
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Budget estimé (optionnel) :</p>
                                        <p class="text-ivoire-text">
                                            {{ number_format($bookingRequest->estimated_budget, 2, ',', ' ') }} €</p>
                                    </div>
                                @else
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Budget estimé (optionnel) :</p>
                                        <p class="text-ivoire-text/60">Non défini</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Contenu original pour les tattooers -->
                            <div class="space-y-4">
                                <div>
                                    <p class="text-ivoire-text/60 text-sm">Description</p>
                                    <p class="text-ivoire-text">{{ $bookingRequest->description }}</p>
                                </div>

                                <div>
                                    <p class="text-ivoire-text/60 text-sm">Zone du corps</p>
                                    <p class="text-ivoire-text font-semibold">{{ $bookingRequest->body_zone }}</p>
                                </div>

                                @if (!auth()->user()->isPiercer())
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Style</p>
                                        <p class="text-ivoire-text">{{ $bookingRequest->tattoo_style ?? 'Non défini' }}</p>
                                    </div>

                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Taille</p>
                                        <p class="text-ivoire-text">{{ $bookingRequest->tattoo_size }}</p>
                                    </div>
                                @endif

                                @if ($bookingRequest->price_estimate_max)
                                    <div>
                                        <p class="text-ivoire-text/60 text-sm">Prix estimé</p>
                                        <p class="text-ivoire-text font-bold text-beige-peau">
                                            {{ number_format($bookingRequest->price_estimate_max, 2, ',', ' ') }} €</p>
                                    </div>
                                @endif
                            </div>
                        @endif
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
                    <div>
                        <span class="text-ivoire-text/70 block mb-1">Date souhaitée :</span>
                        <span class="text-ivoire-text font-semibold">
                            @if ($bookingRequest->preferred_date)
                                {{ $bookingRequest->preferred_date->format('d/m/Y') }}
                            @else
                                Non définie
                            @endif
                        </span>
                    </div>
                    <div class="space-y-3 flex justify-between">
                        @if ($bookingRequest->status->value === 'pending')
                            <button type="button"
                                x-data @click="$dispatch('open-accept-modal', { bookingRequestId: {{ $bookingRequest->id }} })"
                                class="px-4 py-2 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                ✓ Accepter
                            </button>

                            <form action="{{ route($tattooer->routePrefix() . '.request-reject', $bookingRequest) }}"
                                method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    class="px-4 py-2 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30 transition-colors"
                                    onclick="return confirm('Refuser cette demande ?')">
                                    ✕ Refuser
                                </button>
                            </form>
                        @else
                            <!-- Message pour demandes déjà traitées -->
                            <div class="text-ivoire-text/60 text-sm">
                                <p>
                                    @if ($bookingRequest->status->value === 'accepted')
                                        ✅ <strong>Demande acceptée</strong> - Le client a été notifié.
                                    @elseif ($bookingRequest->status->value === 'rejected')
                                        ❌ <strong>Demande refusée</strong>
                                    @else
                                        📋 <strong>Demande {{ $bookingRequest->status->value }}</strong>
                                    @endif
                                </p>

                                @if ($bookingRequest->conversation)
                                    <a href="{{ route($tattooer->routePrefix() . '.message.show', $bookingRequest) }}"
                                        class="inline-block px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                        💬 Voir la conversation
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Affichage standard pour les tattooers -->
                <div class="space-y-4">
                    <div>
                        <span class="text-ivoire-text/70 block mb-1">Description :</span>
                        <p class="text-ivoire-text">{{ $bookingRequest->description }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-ivoire-text/70 block mb-1">Emplacement :</span>
                            <span class="text-ivoire-text font-semibold">{{ $bookingRequest->body_zone }}</span>
                        </div>
                        <div>
                            <span class="text-ivoire-text/70 block mb-1">Taille :</span>
                            <span class="text-ivoire-text font-semibold">{{ $bookingRequest->tattoo_size }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-ivoire-text/70 block mb-1">Budget estimé :</span>
                            <span
                                class="text-ivoire-text font-semibold">{{ $bookingRequest->estimated_total_price ? number_format($bookingRequest->estimated_total_price, 2, ',', ' ') . ' €' : 'Non défini' }}</span>
                        </div>
                        <div>
                            <span class="text-ivoire-text/70 block mb-1">Date souhaitée :</span>
                            <span class="text-ivoire-text font-semibold">
                                @if ($bookingRequest->preferred_date)
                                    {{ $bookingRequest->preferred_date->format('d/m/Y') }}
                                @else
                                    Non définie
                                @endif
                            </span>
                        </div>
                    </div>

                    <div>
                        <span class="text-ivoire-text/70 block mb-1">Statut :</span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if ($bookingRequest->status->value === 'pending') bg-jaune-alerte/20 text-jaune-alerte
                                @elseif($bookingRequest->status->value === 'accepted') bg-vert-succes/20 text-vert-succes
                                @elseif($bookingRequest->status->value === 'in_progress') bg-beige-peau/20 text-beige-peau
                                @elseif($bookingRequest->status->value === 'completed') bg-vert-succes/20 text-vert-succes
                                @elseif($bookingRequest->status->value === 'date_confirmed') bg-vert-succes/20 text-vert-succes
                                @elseif($bookingRequest->status->value === 'cancelled') bg-rouge-alerte/20 text-rouge-alerte @endif">
                            {{ ucfirst($bookingRequest->status->value) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Colonne latérale -->
            <div class="space-y-6">
                <!-- Actions -->
                <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30">
                    <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>

                        @if ($bookingRequest->status->value === 'pending')
                            <div class="space-y-3">
                                <button type="button"
                                    x-data @click="$dispatch('open-accept-modal', { bookingRequestId: {{ $bookingRequest->id }} })"
                                    class="px-4 py-3 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors">
                                    ✓ Accepter
                                </button>

                                <form action="{{ route($tattooer->routePrefix() . '.request-reject', $bookingRequest) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-lg font-semibold hover:bg-rouge-alerte/30 transition-colors"
                                        onclick="return confirm('Refuser cette demande ?')">
                                        ✕ Refuser
                                    </button>
                                </form>
                            </div>
                        @else
                            <!-- Affichage pour les demandes acceptées/résolues -->
                            <div class="text-ivoire-text/60 text-sm">
                                <p class="mb-2">
                                    @if ($bookingRequest->status->value === 'accepted')
                                        ✅ <strong>Demande acceptée</strong> - Le client a été notifié et peut procéder au
                                        paiement.
                                        💬 Voir la conversation
                                        </a>
                                    @endif
                            </div>
                        @endif
                    </div>

                    <!-- Section détails de proposition pour demandes acceptées -->
                    @if (in_array($bookingRequest->status->value, [
                            'accepted',
                            'awaiting_deposit',
                            'deposit_paid',
                            'design_sent',
                            'confirmed',
                            'date_confirmed',
                        ]))
                        <!-- Ici : AFFICHER les détails de la demande (infos remplies dans modal) -->
                        <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-6">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">📋 Détails de votre proposition</h3>

                            <div class="space-y-4">
                                <!-- 💰 Fourchette prix -->
                                @if (!auth()->user()->isPiercer() && ($bookingRequest->price_range_min || $bookingRequest->price_range_max))
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
                                                    {{ \Carbon\Carbon::parse($date['date'])->format('l d/m/Y') }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- 💬 Message tattooer -->
                                @if ($bookingRequest->tattooer_notes)
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💬 Votre message</h4>
                                        <p class="text-ivoire-text/80 whitespace-pre-wrap">
                                            {{ $bookingRequest->tattooer_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>


                        <!-- Chat + Actions -->
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30 mt-4">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>
                            <div class="space-y-3">
                                @if ($bookingRequest->conversation)
                                    <a href="{{ route($tattooer->routePrefix() . '.message.show', $bookingRequest) }}"
                                        class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-xl font-bold text-center hover:bg-beige-peau/90 transition-all">
                                        💬 Chat avec le client
                                    </a>
                                @endif

                                <form action="{{ route($tattooer->routePrefix() . '.request-reject', $bookingRequest) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-rouge-alerte/20 border border-rouge-alerte/30 text-rouge-alerte rounded-xl font-semibold text-center hover:bg-rouge-alerte/30 transition-all"
                                        onclick="return confirm('Annuler ce projet ? Cette action est irréversible.')">
                                        ❌ Annuler la demande
                                    </button>
                                </form>

                                @if ($bookingRequest->status->value === 'date_confirmed')
                                    <form action="{{ route('tattooer.booking-requests.complete', $bookingRequest) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-4 py-3 bg-vert-succes text-white rounded-xl font-bold text-center hover:bg-vert-succes/90 transition-all">
                                            ✅ RDV Validé
                                        </button>
                                    </form>

                                    <form action="{{ route('tattooer.booking-requests.no-show', $bookingRequest) }}"
                                        method="POST"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir déclarer ce client comme absent ? Cette action est irréversible et incrémentera son compteur de no-show.')">
                                        @csrf
                                        <button type="submit"
                                            class="w-full px-4 py-3 bg-rouge-alerte text-white rounded-xl font-bold text-center hover:bg-rouge-alerte/90 transition-all">
                                            ❌ Déclarer No-show
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </div>
                        @if ($bookingRequest->status->value === 'completed')
                            <!-- Afficher résumé final -->
                            <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-6">
                                <h3 class="text-lg font-bold text-ivoire-text mb-4">✅ Projet terminé</h3>
                                <p class="text-ivoire-text">Ce projet a été réalisé avec succès.</p>
                                <div class="mt-4">
                                    @include('partials.pdf-download-button', [
                                        'url' => route('pdf.receipt', $bookingRequest),
                                        'label' => 'Télécharger le reçu (PDF)',
                                    ])
                                </div>
                            </div>
                        @elseif (in_array($bookingRequest->status->value, ['cancelled', 'rejected']))
                            <!-- Afficher statut final avec raison -->
                            <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-xl p-6">
                                <h3 class="text-lg font-bold text-ivoire-text mb-4">❌ Projet annulé</h3>
                                <p class="text-ivoire-text">
                                    @if ($bookingRequest->cancellation_reason)
                                        Raison : {{ $bookingRequest->cancellation_reason }}
                                    @else
                                        Ce projet a été annulé.
                                    @endif
                                </p>
                            </div>
                        @endif

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
                                    Proposition faite au client
                                </h3>

                                <div class="space-y-4">
                                    <!-- 💰 Fourchette prix -->
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Tarif estimé</h4>
                                        @if ($bookingRequest->price_estimate_min || $bookingRequest->price_estimate_max)
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
                                                        class="text-ivoire-text font-semibold">{{ number_format($bookingRequest->price_estimate_max, 2, ',', ' ') }}
                                                        €</span>
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-ivoire-text/60">Tarif non encore défini</p>
                                        @endif
                                    </div>

                                    <!-- 📅 Sélection des dates -->
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">📅 Date choisie par le
                                            client</h4>
                                        @if ($bookingRequest->client_selected_dates && !empty($bookingRequest->client_selected_dates))
                                            <div class="space-y-2">
                                                @foreach ($bookingRequest->client_selected_dates as $selectedDate)
                                                    <div
                                                        class="flex items-center justify-between text-sm bg-beige-peau/10 border border-beige-peau/30 rounded-lg p-3">
                                                        <span class="text-ivoire-text">
                                                            {{ \Carbon\Carbon::parse($selectedDate['date'])->format('d/m/Y') }}
                                                        </span>
                                                        @if ($selectedDate['period'])
                                                            <span
                                                                class="px-2 py-1 bg-beige-peau text-noir-profond text-xs rounded-full">
                                                                {{ $selectedDate['period'] === 'morning' ? 'Matin' : ($selectedDate['period'] === 'afternoon' ? 'Après-midi' : 'Soir') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-ivoire-text/60">Le client n'a pas encore choisi de date</p>
                                        @endif
                                    </div>

                                    <!-- 📅 Dates proposées -->
                                    <div class="bg-noir-profond/50 rounded-lg p-4">
                                        <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">📅 Dates proposées</h4>
                                        @if ($bookingRequest->proposed_dates && !empty($bookingRequest->proposed_dates))
                                            <div class="space-y-2">
                                                @foreach ($bookingRequest->proposed_dates as $index => $date)
                                                    <div class="flex items-center justify-between text-sm">
                                                        <span class="text-ivoire-text">
                                                            {{ \Carbon\Carbon::parse($date['date'])->format('d/m/Y') }}
                                                        </span>
                                                        @if ($date['period'])
                                                            <span
                                                                class="px-2 py-1 bg-beige-peau/20 text-beige-peau text-xs rounded-full">
                                                                {{ $date['period'] === 'morning' ? 'Matin' : ($date['period'] === 'afternoon' ? 'Après-midi' : 'Soir') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                            @if ($bookingRequest->date_selection_deadline)
                                                <p class="text-ivoire-text/60 text-xs mt-2">
                                                    Date limite de sélection :
                                                    {{ \Carbon\Carbon::parse($bookingRequest->date_selection_deadline)->format('d/m/Y à H:i') }}
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-ivoire-text/60">Dates non encore proposées</p>
                                        @endif
                                    </div>

                                    <!-- 🎨 Phase création -->
                                    @if (!auth()->user()->isPiercer() && $bookingRequest->included_design_versions)
                                        <div class="bg-noir-profond/50 rounded-lg p-4">
                                            <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">🎨 Phase création
                                            </h4>
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
                                                @if ($bookingRequest->design_modification_rules)
                                                    <div class="col-span-2">
                                                        <span class="text-ivoire-text/60">Règles de modification :</span>
                                                        <p class="text-ivoire-text mt-1">
                                                            {{ $bookingRequest->design_modification_rules }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Acompte/Paiement pour pierceurs -->
                                    @if ($tattooer instanceof \App\Models\Piercer)
                                        <div class="bg-noir-profond/50 rounded-lg p-4">
                                            <h4 class="font-semibold text-ivoire-text/80 text-sm mb-2">💰 Options de
                                                paiement
                                            </h4>
                                            <div class="space-y-3 text-sm">
                                                <div>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="payment_type" value="deposit"
                                                            class="text-beige-peau" checked>
                                                        <span class="text-ivoire-text">Acompte uniquement</span>
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="flex items-center space-x-2">
                                                        <input type="radio" name="payment_type" value="full"
                                                            class="text-beige-peau" checked>
                                                        <span class="text-ivoire-text">Paiement total</span>
                                                    </label>
                                                </div>
                                                <div class="mt-3 pt-3 border-t border-titane/30">
                                                    <span class="text-ivoire-text/60">Délai de paiement : </span>
                                                    <span class="text-ivoire-text font-semibold">1 jour</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                        @endif

                        <div class="bg-noir-profond/50 rounded-lg p-4">
                            <h4 class="font-semibold text-ivoire-text mb-2">Acompte</h4>
                            @if ($bookingRequest->total_deposit_amount)
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-ivoire-text/60">Montant :</span>
                                        <span
                                            class="text-ivoire-text font-semibold">{{ number_format($bookingRequest->total_deposit_amount, 2, ',', ' ') }}€</span>
                                    </div>
                                    @if ($bookingRequest->deposit_deadline)
                                        <div class="flex justify-between">
                                            <span class="text-ivoire-text/60">Date limite :</span>
                                            <span class="text-ivoire-text">
                                                {{ is_string($bookingRequest->deposit_deadline)
                                                    ? \Carbon\Carbon::parse($bookingRequest->deposit_deadline)->format('d/m/Y')
                                                    : $bookingRequest->deposit_deadline->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($bookingRequest->deposit_covers_description)
                                        <div>
                                            <span class="text-ivoire-text/60">Ce que couvre l'acompte :</span>
                                            <p class="text-ivoire-text mt-1">
                                                {{ $bookingRequest->deposit_covers_description ? 'Dessin et RDV' : 'Non spécifié' }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                </div>
            </div>


            {{-- === BLOC PAIEMENT SOLDE (tattooer) === --}}
            @php
                $allowsBalance = $bookingRequest->status === \App\Enums\BookingRequestStatus::COMPLETED;
                $hasBalance = ($bookingRequest->balance_remaining ?? 0) > 0;
            @endphp

            @if ($allowsBalance && $hasBalance)
                <div class="mt-4 p-4 rounded-xl bg-orange-terre-cuite/5 border border-orange-terre-cuite/20">
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <h4 class="text-sm font-semibold text-noir-profond">Solde restant</h4>
                            <p class="text-2xl font-bold text-orange-terre-cuite">
                                {{ number_format($bookingRequest->balance_remaining, 2, ',', ' ') }} €</p>
                        </div>
                        @if ($bookingRequest->isBalanceRequested())
                            <span class="text-xs text-orange-terre-cuite bg-orange-terre-cuite/10 px-2 py-1 rounded">
                                Demandé le {{ $bookingRequest->balance_requested_at->format('d/m/Y') }}
                            </span>
                        @else
                            <span class="text-xs text-noir-profond/50 bg-noir-profond/5 px-2 py-1 rounded">En attente</span>
                        @endif
                    </div>
                    <p class="text-xs text-noir-profond/60 mb-3">Le client peut payer en ligne, ou vous pouvez
                        confirmer un paiement direct.</p>

                    {{-- Bouton demander le solde en ligne --}}
                    <button x-data
                            @click="$dispatch('open-balance-modal', { bookingRequestId: {{ $bookingRequest->id }} })"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau text-noir-profond
                                   rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $bookingRequest->isBalanceRequested() ? 'Renvoyer la demande de solde' : 'Demander le solde' }}
                    </button>

                    <button x-data @click="$dispatch('open-modal', 'offline-payment-modal')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-orange-terre-cuite text-white rounded-lg text-sm font-medium hover:bg-orange-terre-cuite/90 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                        </svg>
                        Confirmer paiement reçu
                    </button>
                </div>

                {{-- Modal paiement hors plateforme --}}
                <div x-data="{ open: false }"
                    @open-modal.window="if ($event.detail === 'offline-payment-modal') open = true" x-show="open" x-cloak
                    x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div class="absolute inset-0 bg-noir-profond/60" @click="open = false"></div>
                    <div class="relative bg-white rounded-2xl p-6 max-w-md w-full shadow-xl">
                        <h3 class="text-lg font-bold text-noir-profond mb-1">Confirmer le paiement du solde</h3>
                        <p class="text-sm text-noir-profond/60 mb-4">Le client vous a réglé directement ?</p>
                        <form
                            action="{{ route($tattooer->routePrefix() . '.balance-payment.confirm-offline', $bookingRequest) }}"
                            method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-noir-profond mb-1">Montant reçu
                                        (€)</label>
                                    <input type="number" name="amount" step="0.01"
                                        value="{{ $bookingRequest->balance_remaining }}"
                                        class="w-full border border-noir-profond/20 rounded-xl p-3 text-sm focus:ring-2 focus:ring-orange-terre-cuite/50 focus:border-orange-terre-cuite">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-noir-profond mb-1">Mode de
                                        paiement</label>
                                    <select name="payment_method"
                                        class="w-full border border-noir-profond/20 rounded-xl p-3 text-sm focus:ring-2 focus:ring-orange-terre-cuite/50 focus:border-orange-terre-cuite">
                                        <option value="cash">💵 Espèces</option>
                                        <option value="card_direct">💳 Carte bancaire (TPE)</option>
                                        <option value="transfer">🏦 Virement bancaire</option>
                                        <option value="other">📋 Autre</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-noir-profond mb-1">Notes
                                        (optionnel)</label>
                                    <textarea name="notes" rows="2" placeholder="Ex: Pourboire inclus, paiement partiel..."
                                        class="w-full border border-noir-profond/20 rounded-xl p-3 text-sm focus:ring-2 focus:ring-orange-terre-cuite/50 focus:border-orange-terre-cuite"></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end gap-3 mt-5">
                                <button type="button" @click="open = false"
                                    class="px-4 py-2 border border-noir-profond/20 rounded-lg text-sm text-noir-profond/70 hover:bg-noir-profond/5">
                                    Annuler
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-vert-succes text-white rounded-lg text-sm font-medium hover:bg-vert-succes/90">
                                    ✅ Confirmer le paiement
                                </button>
                            </div>
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
                                    {{ $bookingRequest->accepted_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($bookingRequest->cancelled_at)
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-rouge-alerte rounded-full mt-2"></div>
                            <div>
                                <p class="text-ivoire-text font-semibold">Demande refusée</p>
                                <p class="text-ivoire-text/70 text-sm">
                                    {{ $bookingRequest->cancelled_at->format('d/m/Y à H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Lightbox -->
        <div id="lightbox" class="fixed inset-0 z-50 bg-noir-profond/95 backdrop-blur-sm hidden"
            onclick="closeLightbox()">
            <div class="flex items-center justify-center h-full p-4">
                <img id="lightbox-image" src="" alt="Image agrandie"
                    class="max-w-full max-h-full object-contain rounded-lg">
            </div>
        </div>

        <script>
            function openLightbox(imageUrl) {
                document.getElementById('lightbox-image').src = imageUrl;
                document.getElementById('lightbox').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeLightbox() {
                document.getElementById('lightbox').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Fermer avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeLightbox();
                }
            });

            // Écouter l'événement de redirection Livewire
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('redirect', (event) => {
                    window.location.href = event.url;
                });
                Livewire.on('booking-accepted', () => {
                    window.location.reload();
                });
            });

            // Alternative : écouter aussi au cas où livewire:initialized ne se déclenche pas
            if (typeof Livewire !== 'undefined') {
                Livewire.on('redirect', (event) => {
                    window.location.href = event.url;
                });
            }
        </script>

        <!-- Modal d'acceptation Livewire -->
        <livewire:tattooer.accept-booking-modal />

        <!-- Modal demande de solde Livewire -->
        <livewire:tattooer.request-balance-payment />

    @endsection

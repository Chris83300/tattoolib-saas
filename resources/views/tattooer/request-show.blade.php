@extends('layouts.tattooer')

@section('title', 'Détails de la demande')

@section('content')
    <div class="min-h-screen bg-noir-profond">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- En-tête -->
            <div class="mb-8">
                <a href="{{ route('tattooer.requests') }}"
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

                        @if ($bookingRequest->client)
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Nom complet :</span>
                                    <span class="text-ivoire-text font-semibold">
                                        {{ $bookingRequest->client->first_name }} {{ $bookingRequest->client->last_name }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Email :</span>
                                    <span class="text-ivoire-text">{{ $bookingRequest->client->user->email }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Téléphone :</span>
                                    <span
                                        class="text-ivoire-text">{{ $bookingRequest->client->phone ?: 'Non renseigné' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-ivoire-text/70">Date de naissance :</span>
                                    <span class="text-ivoire-text">
                                        {{ $bookingRequest->client->birth_date ? $bookingRequest->client->birth_date->format('d/m/Y') . ' (' . $bookingRequest->client->birth_date->age . ' ans)' : 'Non renseignée' }}
                                    </span>
                                </div>
                                @if ($bookingRequest->client->address)
                                    <div class="flex justify-between">
                                        <span class="text-ivoire-text/70">Adresse :</span>
                                        <span class="text-ivoire-text">{{ $bookingRequest->client->address }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-ivoire-text/50">Informations client non disponibles</p>
                        @endif
                    </div>

                    <!-- Détails du projet -->
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h2 class="text-xl font-bold text-ivoire-text mb-4">Détails du projet</h2>

                        <div class="space-y-4">
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Description :</span>
                                <p class="text-ivoire-text">{{ $bookingRequest->tattoo_description }}</p>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Emplacement :</span>
                                    <span
                                        class="text-ivoire-text font-semibold">{{ $bookingRequest->tattoo_location }}</span>
                                </div>
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Style :</span>
                                    <span class="text-ivoire-text font-semibold">{{ $bookingRequest->tattoo_style }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Prix estimé :</span>
                                    <span
                                        class="text-ivoire-text font-semibold">{{ $bookingRequest->estimated_price ? number_format($bookingRequest->estimated_price, 2, ',', ' ') . ' €' : 'Non défini' }}</span>
                                </div>
                                <div>
                                    <span class="text-ivoire-text/70 block mb-1">Date proposée :</span>
                                    <span
                                        class="text-ivoire-text font-semibold">{{ $bookingRequest->proposed_date ? $bookingRequest->proposed_date->format('d/m/Y') : 'Non définie' }}</span>
                                </div>
                            </div>

                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Statut :</span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
                </div>

                <!-- Colonne latérale -->
                <div class="space-y-6">
                    <!-- Actions -->
                    @if ($bookingRequest->status === 'pending')
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>

                            <div class="space-y-3">
                                <form action="{{ route('tattooer.request-accept', $bookingRequest) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors"
                                        onclick="return confirm('Accepter cette demande ?')">
                                        ✓ Accepter
                                    </button>
                                </form>

                                <form action="{{ route('tattooer.request-reject', $bookingRequest) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-rouge-alerte text-ivoire-text rounded-lg font-semibold hover:bg-rouge-alerte/90 transition-colors"
                                        onclick="return confirm('Refuser cette demande ?')">
                                        ✕ Refuser
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if ($bookingRequest->status === 'accepted')
                        <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                            <h3 class="text-lg font-bold text-ivoire-text mb-4">Prochaines étapes</h3>

                            <div class="space-y-3">
                                <!-- Bouton Chat -->
                                <a href="{{ route('tattooer.message.show', $bookingRequest) }}"
                                    class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors text-center">
                                    💬 Discuter avec le client
                                </a>

                                <!-- Bouton Demande acompte -->
                                <a href="{{ route('booking-request.deposit.request', $bookingRequest) }}"
                                    class="block w-full px-4 py-3 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors text-center">
                                    💰 Demander un acompte
                                </a>
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
            </div>
        </div>
    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="fixed inset-0 z-50 bg-noir-profond/95 backdrop-blur-sm hidden" onclick="closeLightbox()">
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
    </script>
@endsection

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
                            <p class="text-ivoire-text">{{ $bookingRequest->tattoo_description }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Emplacement :</span>
                                <span class="text-ivoire-text font-semibold">{{ $bookingRequest->tattoo_location }}</span>
                            </div>
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Style :</span>
                                <span class="text-ivoire-text font-semibold">{{ $bookingRequest->tattoo_style }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Prix estimé :</span>
                                <span class="text-ivoire-text font-semibold">
                                    {{ $bookingRequest->estimated_price ? number_format($bookingRequest->estimated_price, 2, ',', ' ') . ' €' : 'Non défini' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-ivoire-text/70 block mb-1">Date proposée :</span>
                                <span class="text-ivoire-text font-semibold">
                                    {{ $bookingRequest->proposed_date ? $bookingRequest->proposed_date->format('d/m/Y') : 'Non définie' }}
                                </span>
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
                                {{ match ($bookingRequest->status) {
                                    'pending' => '⏳ En attente',
                                    'accepted' => '✓ Acceptée',
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

                <!-- Actions -->
                @if ($bookingRequest->status === 'accepted')
                    <div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
                        <h3 class="text-lg font-bold text-ivoire-text mb-4">Actions</h3>

                        <div class="space-y-3">
                            <!-- Bouton Chat -->
                            <a href="{{ route('client.chat', $bookingRequest->conversation) }}"
                                class="block w-full px-4 py-3 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors text-center">
                                💬 Discuter avec l'artiste
                            </a>

                            <!-- Bouton Payer acompte -->
                            @if ($bookingRequest->deposit_amount && !$bookingRequest->isDepositPaid())
                                <a href="{{ route('deposit.payment', $bookingRequest) }}"
                                    class="block w-full px-4 py-3 bg-vert-succes text-noir-profond rounded-lg font-semibold hover:bg-vert-succes/90 transition-colors text-center">
                                    💰 Payer l'acompte
                                </a>
                            @endif
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
                        <div class="w-12 h-12 bg-beige-peau rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-noir-profond" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

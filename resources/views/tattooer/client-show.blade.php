@extends('layouts.tattooer')

@section('content')
    <div class="space-y-6">

        <!-- Header client -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="flex items-start gap-4">
                    <!-- Avatar -->
                    <div class="w-20 h-20 rounded-full bg-beige-peau/20 flex items-center justify-center flex-shrink-0">
                        <span class="text-3xl font-bold text-beige-peau">
                            {{ substr($client->first_name, 0, 1) }}{{ substr($client->last_name, 0, 1) }}
                        </span>
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-ivoire-text mb-2">
                            {{ $client->first_name }} {{ $client->last_name }}
                        </h1>
                        <div class="space-y-1 text-ivoire-text/70">
                            <p>📧 {{ $client->email }}</p>
                            <p>📱 {{ $client->phone }}</p>
                            @if ($client->birth_date)
                                <p>🎂 {{ $client->birth_date->format('d/m/Y') }} ({{ $client->birth_date->age }} ans)</p>
                            @endif
                            @if ($client->address)
                                <p>📍 {{ $client->address }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col gap-2">
                    <a href="{{ route('tattooer.clients') }}"
                        class="px-4 py-2 border border-beige-peau text-beige-peau rounded-lg font-semibold text-center hover:bg-beige-peau/10 transition-colors">
                        ← Retour aux clients
                    </a>
                    <a href="{{ route('tattooer.messages') }}"
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold text-center hover:bg-beige-peau/90 transition-colors">
                        💬 Envoyer message
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-gris-fonde rounded-xl p-2">
            <div class="flex gap-2 overflow-x-auto">
                <button onclick="switchTab('history')"
                    class="tab-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap transition-colors"
                    data-tab="history">
                    📜 Historique ({{ $history->count() }})
                </button>
                <button onclick="switchTab('consent')"
                    class="tab-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap transition-colors"
                    data-tab="consent">
                    📝 Consentement
                </button>
                <button onclick="switchTab('notes')"
                    class="tab-btn px-4 py-2 rounded-lg font-semibold whitespace-nowrap transition-colors" data-tab="notes">
                    📋 Notes privées
                </button>
            </div>
        </div>

        <!-- TAB: Historique Tattoos -->
        <div id="tab-history" class="tab-content">
            <div class="space-y-4">
                @forelse($history as $tattoo)
                    <div class="bg-gris-fonde rounded-xl p-6">
                        <div class="flex flex-col md:flex-row gap-6">

                            <!-- Photos -->
                            <div class="flex gap-2 md:w-1/3">
                                @forelse($tattoo->getMedia('photos')->take(3) as $media)
                                    <img src="{{ $media->getUrl() }}" alt="Tattoo"
                                        class="flex-1 aspect-square rounded-lg object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                        onclick="openLightbox('{{ $media->getUrl() }}')">
                                @empty
                                    <div
                                        class="flex-1 aspect-square rounded-lg bg-noir-profond flex items-center justify-center text-ivoire-text/30">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Infos -->
                            <div class="flex-1">
                                <div class="flex items-start justify-between mb-4">
                                    <div>
                                        <h3 class="text-xl font-bold text-ivoire-text mb-1">
                                            {{ $tattoo->tattoo_date->format('d/m/Y') }}
                                        </h3>
                                        <p class="text-ivoire-text/70">{{ $tattoo->body_location }}</p>
                                    </div>
                                    <span
                                        class="px-3 py-1 bg-vert-succes/20 text-vert-succes rounded-full text-sm font-semibold">
                                        {{ number_format($tattoo->total_paid, 0) }}€
                                    </span>
                                </div>

                                <p class="text-ivoire-text/80 mb-4">{{ $tattoo->description }}</p>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-ivoire-text/60">Durée</p>
                                        <p class="font-semibold text-ivoire-text">{{ $tattoo->duration }}min</p>
                                    </div>
                                    <div>
                                        <p class="text-ivoire-text/60">Paiement</p>
                                        <p class="font-semibold text-ivoire-text capitalize">{{ $tattoo->payment_method }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-ivoire-text/60">Photos</p>
                                        <p class="font-semibold text-ivoire-text">
                                            {{ $tattoo->getMedia('photos')->count() }}</p>
                                    </div>
                                    <div>
                                        <p class="text-ivoire-text/60">Traçabilité</p>
                                        @if ($tattoo->bookingRequest && $tattoo->bookingRequest->traceability)
                                            <button
                                                onclick="showTraceability({{ $tattoo->bookingRequest->traceability->id }})"
                                                class="text-beige-peau font-semibold hover:underline">
                                                Voir détails
                                            </button>
                                        @else
                                            <p class="text-ivoire-text/40">Non renseignée</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-gris-fonde rounded-xl p-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-ivoire-text/30" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-ivoire-text/60">Aucun tattoo réalisé pour ce client</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB: Consentement -->
        <div id="tab-consent" class="tab-content hidden">
            @if ($client->consent)
                <div class="bg-gris-fonde rounded-xl p-6">
                    <h3 class="text-xl font-bold text-ivoire-text mb-4">Consentement éclairé</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-ivoire-text/60 mb-2">Signé le</p>
                            <p class="font-semibold text-ivoire-text">
                                {{ $client->consent->signed_at->format('d/m/Y à H:i') }}</p>
                        </div>

                        @if ($client->consent->is_minor)
                            <div>
                                <p class="text-ivoire-text/60 mb-2">Consentement parental</p>
                                <p class="font-semibold text-ivoire-text">{{ $client->consent->parent_name }}
                                    ({{ $client->consent->parent_relation }})</p>
                            </div>
                        @endif
                    </div>

                    <!-- Conditions médicales -->
                    @if ($client->consent->medical_conditions && count($client->consent->medical_conditions) > 0)
                        <div class="mb-6">
                            <p class="font-semibold text-ivoire-text mb-2">Conditions médicales déclarées</p>
                            <ul class="list-disc list-inside text-ivoire-text/70">
                                @foreach ($client->consent->medical_conditions as $condition)
                                    <li>{{ $condition }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Signature client -->
                    <div class="mb-4">
                        <p class="font-semibold text-ivoire-text mb-2">Signature client</p>
                        <div class="bg-white p-4 rounded-lg inline-block">
                            <img src="{{ $client->consent->signature_data }}" alt="Signature" class="max-w-xs">
                        </div>
                    </div>

                    <!-- Signature parent si mineur -->
                    @if ($client->consent->is_minor && $client->consent->parent_signature_data)
                        <div>
                            <p class="font-semibold text-ivoire-text mb-2">Signature parent</p>
                            <div class="bg-white p-4 rounded-lg inline-block">
                                <img src="{{ $client->consent->parent_signature_data }}" alt="Signature parent"
                                    class="max-w-xs">
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="bg-gris-fonde rounded-xl p-12 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-ivoire-text/30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-ivoire-text/60">Aucun consentement signé</p>
                </div>
            @endif
        </div>

        <!-- TAB: Notes privées -->
        <div id="tab-notes" class="tab-content hidden">
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-xl font-bold text-ivoire-text mb-4">Notes privées</h3>
                <form action="{{ route('tattooer.client.update-notes', $client) }}" method="POST">
                    @csrf
                    <textarea name="notes" rows="10"
                        placeholder="Notes personnelles sur ce client (allergies, préférences, comportement...)"
                        class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-ivoire-text/50 focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">{{ $client->notes ?? '' }}</textarea>

                    <button type="submit"
                        class="mt-4 px-6 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        Enregistrer les notes
                    </button>
                </form>
            </div>
        </div>

    </div>

    <!-- Lightbox -->
    <div id="lightbox" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
        onclick="closeLightbox()">
        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full">
    </div>

    @push('scripts')
        <script>
            function switchTab(tabName) {
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.tab-btn').forEach(el => {
                    el.classList.remove('bg-beige-peau', 'text-noir-profond');
                    el.classList.add('text-ivoire-text');
                });

                document.getElementById('tab-' + tabName).classList.remove('hidden');
                document.querySelector(`[data-tab="${tabName}"]`).classList.add('bg-beige-peau', 'text-noir-profond');
                document.querySelector(`[data-tab="${tabName}"]`).classList.remove('text-ivoire-text');
            }

            switchTab('history');

            function openLightbox(url) {
                document.getElementById('lightbox-img').src = url;
                document.getElementById('lightbox').classList.remove('hidden');
            }

            function closeLightbox() {
                document.getElementById('lightbox').classList.add('hidden');
            }

            function showTraceability(id) {
                // Implémentation modal traçabilité
                alert('Modal traçabilité à implémenter pour l\'ID: ' + id);
            }
        </script>
    @endpush
@endsection

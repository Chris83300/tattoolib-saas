@extends('layouts.studio')

@section('content')
    <div x-data="{ activeTab: '{{ request()->get('tab', 'info') }}', editMode: false }" class="space-y-4 pb-20">
        {{-- ═══════════════════════════════════════════════════════════════
             HEADER CLIENT (toujours visible)
             ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                <a href="{{ route('studio.clients.index') }}"
                    class="mt-1 p-2 rounded-lg hover:bg-noir-profond transition-colors flex-shrink-0">
                    <svg class="w-5 h-5 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                @php
                    $avatarUrl = $client->user?->getFirstMediaUrl('avatar') ?: $client->getFirstMediaUrl('avatar');
                    $pseudo = $client->pseudo ?? ($client->user?->pseudo ?? null);
                    $fullName = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));
                    if (!$fullName) {
                        $fullName = $client->user?->name ?? 'Client';
                    }
                @endphp

                <div
                    class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden bg-titane/30 flex-shrink-0 flex items-center justify-center">
                    @if ($avatarUrl)
                        <img src="{{ $avatarUrl }}" alt="{{ $pseudo ?? $fullName }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-bold text-beige-peau">
                            {{ strtoupper(substr($client->first_name ?? '?', 0, 1)) }}{{ strtoupper(substr($client->last_name ?? '', 0, 1)) }}
                        </span>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    @if ($pseudo)
                        <h1 class="text-xl md:text-2xl font-bold text-ivoire-text truncate">{{ $pseudo }}</h1>
                        <p class="text-sm text-ivoire-text/60">{{ $fullName }}</p>
                    @else
                        <h1 class="text-xl md:text-2xl font-bold text-ivoire-text truncate">{{ $fullName }}</h1>
                    @endif
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-0.5 bg-beige-peau/20 text-beige-peau rounded text-xs font-medium">
                            {{ $stats->total_requests }} demande{{ $stats->total_requests > 1 ? 's' : '' }}
                        </span>
                        @if ($stats->completed > 0)
                            <span class="px-2 py-0.5 bg-vert-succes/20 text-vert-succes rounded text-xs font-medium">
                                {{ $stats->completed }} terminée{{ $stats->completed > 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if ($stats->total_paid > 0)
                            <span class="px-2 py-0.5 bg-titane/20 text-titane rounded text-xs font-medium">
                                {{ number_format($stats->total_paid, 0) }}€
                            </span>
                        @endif
                        @if ($client->is_blacklisted)
                            <span class="px-2 py-0.5 bg-rouge-alerte/20 text-rouge-alerte rounded text-xs font-semibold">⛔
                                Blacklisté</span>
                        @endif
                        @if ($client->no_show_count > 0)
                            <span class="px-2 py-0.5 bg-ambre-warning/20 text-ambre-warning rounded text-xs font-medium">
                                {{ $client->no_show_count }} no-show{{ $client->no_show_count > 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>
                    <div class="mt-3">
                        @include('partials.pdf-download-button', [
                            'url' => route('pdf.client-summary', $client),
                            'label' => 'Fiche client PDF',
                            'size' => 'xs',
                        ])
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════
             TABS NAVIGATION
             ═══════════════════════════════════════════════════════════════ --}}
        <div class="bg-gris-fonde rounded-xl p-1.5 sticky top-0 z-10 min-w-0">
            <div class="flex gap-1 overflow-x-auto pb-1 min-w-0" style="-webkit-overflow-scrolling: touch;">
                @php
                    $tabs = [
                        'info' => ['label' => 'Infos', 'icon' => '👤'],
                        'history' => ['label' => 'Historique', 'icon' => '📜', 'count' => $bookingRequests->count()],
                        'consent' => [
                            'label' => 'Consentement',
                            'icon' => '📝',
                            'count' => $consents->count() + $consentDocuments->count(),
                        ],
                        'trace' => ['label' => 'Traçabilité', 'icon' => '🔬', 'count' => $traceabilities->count()],
                        'notes' => ['label' => 'Notes', 'icon' => '📋'],
                    ];
                @endphp

                @foreach ($tabs as $key => $tab)
                    <button @click="activeTab = '{{ $key }}'"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg font-semibold whitespace-nowrap transition-all text-sm flex-shrink-0"
                        :class="activeTab === '{{ $key }}' ? 'bg-beige-peau text-noir-profond' :
                            'text-titane hover:text-ivoire-text hover:bg-noir-profond'">
                        <span>{{ $tab['icon'] }}</span>
                        <span>{{ $tab['label'] }}</span>
                        @if (isset($tab['count']) && $tab['count'] > 0)
                            <span class="px-1.5 py-0.5 rounded-full text-xs"
                                :class="activeTab === '{{ $key }}' ? 'bg-noir-profond/20 text-noir-profond' :
                                    'bg-titane/20 text-titane'">
                                {{ $tab['count'] }}
                            </span>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════
             TAB: INFOS CLIENT
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'info'" x-cloak class="space-y-4">

            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">Contact</h3>
                    <button @click="editMode = !editMode"
                        class="p-1.5 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors">
                        <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </button>
                </div>

                {{-- MODE ÉDITION --}}
                <div x-show="editMode" x-cloak>
                    <form action="{{ route('studio.clients.update', $client) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Prénom</label>
                                <input type="text" name="first_name" value="{{ $client->first_name ?? '' }}"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Nom</label>
                                <input type="text" name="last_name" value="{{ $client->last_name ?? '' }}"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Pseudo</label>
                                <input type="text" name="pseudo" value="{{ $client->pseudo ?? '' }}"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Email</label>
                                <input type="email" name="email" value="{{ $client->user?->email ?? $client->email }}"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Téléphone</label>
                                <input type="tel" name="phone" value="{{ $client->phone ?? '' }}"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Date de
                                    naissance</label>
                                <input type="date" name="birth_date"
                                    value="{{ $client->birth_date?->format('Y-m-d') ?? '' }}"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Adresse</label>
                                <input type="text" name="address" value="{{ $client->address ?? '' }}"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit"
                                class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">✅
                                Enregistrer</button>
                            <button type="button" @click="editMode = false"
                                class="px-4 py-2 border border-titane/30 text-titane rounded-lg text-sm hover:bg-noir-profond transition-colors">Annuler</button>
                        </div>
                    </form>
                </div>

                {{-- MODE LECTURE --}}
                <div x-show="!editMode" class="space-y-3">
                    @if ($client->user?->email ?? $client->email)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-titane flex-shrink-0">📧</span>
                                <span
                                    class="text-ivoire-text text-sm truncate">{{ $client->user?->email ?? $client->email }}</span>
                            </div>
                            <a href="mailto:{{ $client->user?->email ?? $client->email }}"
                                class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </a>
                        </div>
                    @endif
                    @if ($client->phone)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-titane flex-shrink-0">📱</span>
                                <span class="text-ivoire-text text-sm">{{ $client->phone }}</span>
                            </div>
                            <a href="tel:{{ $client->phone }}"
                                class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </a>
                        </div>
                    @endif
                    @if ($client->birth_date)
                        <div class="flex items-center gap-2">
                            <span class="text-titane flex-shrink-0">🎂</span>
                            <span class="text-ivoire-text text-sm">
                                {{ $client->birth_date->format('d/m/Y') }} ({{ $client->birth_date->age }} ans)
                                @if ($client->birth_date->age < 18)
                                    <span
                                        class="ml-1 px-1.5 py-0.5 bg-ambre-warning/20 text-ambre-warning rounded text-xs font-semibold">MINEUR</span>
                                @endif
                            </span>
                        </div>
                    @endif
                    @if ($client->address)
                        <div class="flex items-center gap-2">
                            <span class="text-titane flex-shrink-0">📍</span>
                            <span class="text-ivoire-text text-sm">{{ $client->address }}</span>
                        </div>
                    @endif
                    @if (!($client->user?->email ?? $client->email) && !$client->phone && !$client->birth_date && !$client->address)
                        <p class="text-sm text-titane italic">Aucune information de contact. <button
                                @click="editMode = true" class="text-beige-peau hover:underline">Ajouter</button></p>
                    @endif
                </div>
            </div>

            {{-- Statistiques --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-beige-peau">{{ $stats->total_requests }}</p>
                    <p class="text-xs text-titane mt-1">Demandes</p>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-vert-succes">{{ $stats->completed }}</p>
                    <p class="text-xs text-titane mt-1">Réalisés</p>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-ivoire-text">{{ number_format($stats->total_paid, 0) }}€</p>
                    <p class="text-xs text-titane mt-1">Total versé</p>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-ivoire-text">{{ $stats->total_appointments }}</p>
                    <p class="text-xs text-titane mt-1">RDV</p>
                </div>
            </div>

        </div>{{-- Fin TAB INFOS --}}

        {{-- ═══════════════════════════════════════════════════════════════
             TAB: HISTORIQUE DEMANDES (STUDIO - TOUS LES ARTISTES)
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'history'" x-cloak class="space-y-3">
            @forelse ($bookingRequests as $br)
                <div class="bg-gris-fonde rounded-xl p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-semibold text-ivoire-text text-sm">{{ $br->tattoo_style ?? 'Tattoo' }} —
                                    {{ $br->body_zone ?? 'Non précisé' }}</h4>
                                @php
                                    $statusConfig = match ($br->status->value ?? $br->status) {
                                        'pending' => [
                                            'bg' => 'bg-ambre-warning/20',
                                            'text' => 'text-ambre-warning',
                                            'label' => '⏳ En attente',
                                        ],
                                        'accepted' => [
                                            'bg' => 'bg-vert-succes/20',
                                            'text' => 'text-vert-succes',
                                            'label' => '✅ Acceptée',
                                        ],
                                        'deposit_paid' => [
                                            'bg' => 'bg-vert-succes/20',
                                            'text' => 'text-vert-succes',
                                            'label' => '💰 Acompte payé',
                                        ],
                                        'date_confirmed' => [
                                            'bg' => 'bg-beige-peau/20',
                                            'text' => 'text-beige-peau',
                                            'label' => '📅 Date confirmée',
                                        ],
                                        'in_progress' => [
                                            'bg' => 'bg-beige-peau/20',
                                            'text' => 'text-beige-peau',
                                            'label' => '🎨 En cours',
                                        ],
                                        'completed' => [
                                            'bg' => 'bg-vert-succes/20',
                                            'text' => 'text-vert-succes',
                                            'label' => '✅ Terminé',
                                        ],
                                        'cancelled' => [
                                            'bg' => 'bg-rouge-alerte/20',
                                            'text' => 'text-rouge-alerte',
                                            'label' => '❌ Annulée',
                                        ],
                                        'rejected' => [
                                            'bg' => 'bg-rouge-alerte/20',
                                            'text' => 'text-rouge-alerte',
                                            'label' => '❌ Refusée',
                                        ],
                                        default => [
                                            'bg' => 'bg-titane/20',
                                            'text' => 'text-titane',
                                            'label' => ucfirst($br->status->value ?? $br->status),
                                        ],
                                    };
                                @endphp
                                <span
                                    class="px-2 py-0.5 {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} rounded text-xs font-semibold">{{ $statusConfig['label'] }}</span>
                            </div>
                            <p class="text-xs text-titane mt-1">{{ $br->created_at->translatedFormat('d F Y') }}</p>
                            <p class="text-xs text-titane/60 mt-1">
                                Artiste: {{ $br->bookable?->user?->name ?? 'Non assigné' }}
                                @if ($br->bookable instanceof \App\Models\Piercer)
                                    💎
                                @else
                                    🎨
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('studio.demandes.show', $br) }}"
                            class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-titane">
                        @if ($br->total_deposit_amount)
                            <span>💰 Acompte : {{ number_format($br->total_deposit_amount, 0) }}€ @if ($br->deposit_paid_at)
                                    <span class="text-vert-succes">(payé)</span>
                                @endif
                            </span>
                        @endif
                        @if ($br->price_estimate_min && $br->price_estimate_max)
                            <span>🏷️
                                {{ number_format($br->price_estimate_min, 0) }}-{{ number_format($br->price_estimate_max, 0) }}€</span>
                        @endif
                        @if ($br->tattoo_size)
                            <span>📐 {{ $br->tattoo_size }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-gris-fonde rounded-xl p-8 text-center">
                    <p class="text-titane">Aucune demande enregistrée</p>
                </div>
            @endforelse
        </div>{{-- Fin TAB HISTORIQUE --}}

        {{-- ═══════════════════════════════════════════════════════════════
             TAB: CONSENTEMENT
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'consent'" x-cloak class="space-y-4">
            <x-pro-gate feature="la gestion des consentements SNAT">

                {{-- Consentements scannés --}}
                @if ($consentDocuments->count() > 0)
                    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📄 Consentements
                            scannés</h3>
                        <div class="space-y-3">
                            @foreach ($consentDocuments as $document)
                                <div class="flex items-center justify-between p-3 bg-noir-profond/50 rounded-lg">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="text-2xl flex-shrink-0">📄</span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-ivoire-text truncate">
                                                {{ $document->file_name }}</p>
                                            <p class="text-xs text-titane">
                                                Uploadé le {{ $document->created_at->format('d/m/Y H:i') }}
                                                @if ($document->getCustomProperty('consent_date'))
                                                    · Signé le
                                                    {{ \Carbon\Carbon::parse($document->getCustomProperty('consent_date'))->format('d/m/Y') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="{{ $document->getUrl() }}" target="_blank"
                                            class="p-2 bg-beige-peau/20 text-beige-peau rounded-lg hover:bg-beige-peau/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Consentements via booking requests --}}
                @forelse ($bookingRequests as $br)
                    @php $consent = $consents[$br->id] ?? null; @endphp
                    <div class="bg-gris-fonde rounded-xl p-4" x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }">
                        <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                            <div class="flex items-center gap-3">
                                @if ($consent && $consent->isValid())
                                    <span
                                        class="w-8 h-8 bg-vert-succes/20 text-vert-succes rounded-full flex items-center justify-center text-sm">✅</span>
                                @else
                                    <span
                                        class="w-8 h-8 bg-ambre-warning/20 text-ambre-warning rounded-full flex items-center justify-center text-sm">⚠️</span>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-ivoire-text">
                                        {{ $br->tattoo_style ?? 'Tattoo' }} — {{ $br->body_zone ?? 'Non précisé' }}
                                        · @if ($consent && $consent->isValid())
                                            <span class="text-vert-succes">Signé</span>
                                        @else
                                            <span class="text-ambre-warning">En attente</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-titane">{{ $br->created_at->format('d/m/Y') }} @if ($consent && $consent->signed_at)
                                            · Signé le {{ $consent->signed_at->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-titane transition-transform" :class="expanded ? 'rotate-180' : ''"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                        <div x-show="expanded" x-cloak x-collapse class="mt-4 pt-4 border-t border-titane/20">
                            @if ($consent && $consent->isValid())
                                <div class="space-y-3 text-ivoire-text">
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">📋 Identité</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                            <p><span class="text-ivoire-text/60">Nom:</span>
                                                {{ $consent->client_full_name ?? 'N/R' }}</p>
                                            <p><span class="text-ivoire-text/60">Né(e) le:</span>
                                                {{ $consent->client_birth_date?->format('d/m/Y') ?? 'N/R' }}</p>
                                            <p><span class="text-ivoire-text/60">Tél:</span>
                                                {{ $consent->client_phone ?? 'N/R' }}</p>
                                            <p><span class="text-ivoire-text/60">Email:</span>
                                                {{ $consent->client_email ?? 'N/R' }}</p>
                                            <p class="md:col-span-2"><span class="text-ivoire-text/60">Adresse:</span>
                                                {{ $consent->client_address ?? 'N/R' }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">✍️ Signature</p>
                                        @if ($consent->signature_data)
                                            <img src="{{ $consent->signature_data }}" alt="Signature"
                                                class="h-16 bg-white rounded mb-2">
                                        @endif
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-ivoire-text/60">Date:</span>
                                                {{ $consent->signed_at?->format('d/m/Y à H:i') }}</p>
                                            <p><span class="text-ivoire-text/60">IP:</span>
                                                {{ $consent->signed_ip ?? 'N/R' }}</p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-titane text-center">Consentement verrouillé après signature.</p>
                                    <div class="flex justify-center mt-3">
                                        @include('partials.pdf-download-button', [
                                            'url' => route('pdf.consent-form', $consent),
                                            'label' => 'Télécharger le consentement (PDF)',
                                        ])
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-6">
                                    <span class="text-3xl mb-2 block">⏳</span>
                                    <p class="text-sm text-ivoire-text/70">En attente de signature du client</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    @if ($consentDocuments->count() === 0)
                        <div class="bg-gris-fonde rounded-xl p-8 text-center">
                            <span class="text-3xl mb-2 block">📝</span>
                            <p class="text-titane">Aucun consentement</p>
                        </div>
                    @endif
                @endforelse

            </x-pro-gate>
        </div>{{-- Fin TAB CONSENTEMENT --}}

        {{-- ═══════════════════════════════════════════════════════════════
             TAB: TRAÇABILITÉ
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'trace'" x-cloak class="space-y-4">
            <x-pro-gate feature="la traçabilité réglementaire">

                @forelse ($traceabilities as $trace)
                    <div class="bg-gris-fonde rounded-xl p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-semibold text-ivoire-text text-sm">
                                        @if ($trace->procedure_notes)
                                            {{ \Illuminate\Support\Str::limit($trace->procedure_notes, 50) }}
                                        @else
                                            Séance du {{ $trace->procedure_date->format('d/m/Y') }}
                                        @endif
                                    </h4>
                                    <span
                                        class="px-2 py-0.5 bg-beige-peau/20 text-beige-peau rounded text-xs font-semibold">
                                        {{ $trace->procedure_date->format('d/m/Y') }}
                                    </span>
                                </div>
                                <p class="text-xs text-titane mt-1">
                                    Artiste: {{ $trace->tattooer?->user?->name ?? 'Non spécifié' }}
                                    @if ($trace->procedure_start_time && $trace->procedure_end_time)
                                        · {{ $trace->procedure_start_time }} - {{ $trace->procedure_end_time }}
                                    @endif
                                </p>
                                @if ($trace->room_number)
                                    <p class="text-xs text-titane/60 mt-1">Salle: {{ $trace->room_number }}</p>
                                @endif
                            </div>
                        </div>

                        @if ($trace->needles && $trace->needles->count() > 0)
                            <div class="mt-3">
                                <p class="text-xs font-semibold text-ivoire-text/60 uppercase mb-2">
                                    {{ $trace->bookable instanceof \App\Models\Piercer ? 'Canules' : 'Aiguilles' }}
                                    utilisées
                                </p>
                                <div class="space-y-1">
                                    @foreach ($trace->needles as $needle)
                                        <p class="text-xs text-titane">
                                            #{{ $loop->iteration }}: {{ $needle->brand ?? 'N/R' }} -
                                            Lot: {{ $needle->lot_number ?? 'N/R' }}
                                            @if ($needle->type)
                                                ({{ $needle->type }})
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if ($trace->inks_used && is_array($trace->inks_used) && count($trace->inks_used) > 0)
                            <div class="mt-3">
                                <p class="text-xs font-semibold text-ivoire-text/60 uppercase mb-2">Encres utilisées</p>
                                <div class="space-y-1">
                                    @foreach ($trace->inks_used as $ink)
                                        <p class="text-xs text-titane">
                                            #{{ $loop->iteration }}:
                                            @if ($ink['brand'] ?? null)
                                                {{ $ink['brand'] }} -
                                            @endif
                                            @if ($ink['color'] ?? null)
                                                {{ $ink['color'] }}
                                            @endif
                                            @if ($ink['lot_number'] ?? null)
                                                · Lot: {{ $ink['lot_number'] }}
                                            @endif
                                            @if ($ink['quantity_ml'] ?? null)
                                                · {{ $ink['quantity_ml'] }}ml
                                            @endif
                                        </p>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-gris-fonde rounded-xl p-8 text-center">
                        <span class="text-3xl mb-2 block">🔬</span>
                        <p class="text-titane">Aucune traçabilité enregistrée</p>
                    </div>
                @endforelse

            </x-pro-gate>
        </div>{{-- Fin TAB TRAÇABILITÉ --}}

        {{-- ═══════════════════════════════════════════════════════════════
             TAB: NOTES
             ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="activeTab === 'notes'" x-cloak class="space-y-4">
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">Notes internes</h3>
                </div>
                <form action="{{ route('studio.clients.update', $client) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <textarea name="notes" rows="6" placeholder="Ajouter des notes sur ce client..."
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none">{{ $client->notes ?? '' }}</textarea>
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">💾
                        Enregistrer les notes</button>
                </form>
            </div>
        </div>{{-- Fin TAB NOTES --}}

    </div>
@endsection

@extends('layouts.tattooer')

@section('title', 'Mon Profil')

@section('content')
    <div class="space-y-6">

        <!-- Header avec actions rapides -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                        Mon Profil
                    </h1>
                    <p class="text-ivoire-text/70">
                        Gérez votre profil public et votre présence sur la marketplace
                    </p>
                </div>

                <!-- Actions rapides -->
                <div class="flex flex-col sm:flex-row gap-2">
                    <a href="{{ route('marketplace.tattooer.show', $tattooer->slug) }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                        Voir mon profil public
                    </a>
                    <a href="{{ route('marketplace.index') }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold hover:bg-noir-profond/80 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                        Voir la marketplace
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Profil -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-vert-succes/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-vert-succes" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-ivoire-text">{{ $stats['completed_projects'] }}</div>
                <div class="text-ivoire-text/60 text-sm">Projets complétés</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-ambre-warning/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-ambre-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-ivoire-text">{{ $stats['active_projects'] }}</div>
                <div class="text-ivoire-text/60 text-sm">Projets actifs</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-ivoire-text">{{ $stats['total_clients'] }}</div>
                <div class="text-ivoire-text/60 text-sm">Clients totaux</div>
            </div>

            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-titane/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-bold text-ivoire-text">{{ $stats['portfolio_count'] }}</div>
                <div class="text-ivoire-text/60 text-sm">Photos portfolio</div>
            </div>
        </div>

        <!-- Informations du profil -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Infos principales -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Carte profil -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <h2 class="text-xl font-bold text-ivoire-text mb-4">Informations publiques</h2>

                    <div class="flex items-start gap-6 mb-6">
                        <!-- Avatar -->
                        <div class="flex-shrink-0">
                            @if ($tattooer->getFirstMediaUrl('avatar'))
                                <img src="{{ $tattooer->getFirstMediaUrl('avatar') }}" alt="Avatar"
                                    class="w-24 h-24 rounded-full object-cover border-4 border-titane/20">
                            @else
                                <div class="w-24 h-24 rounded-full bg-titane/20 flex items-center justify-center">
                                    <span class="text-2xl font-bold text-beige-peau">
                                        {{ substr($tattooer->user->pseudo, 0, 2) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <!-- Infos -->
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-ivoire-text mb-2">{{ $tattooer->user->pseudo }}</h3>
                            <p class="text-ivoire-text/70 mb-4">{{ $tattooer->bio ?: 'Aucune biographie' }}</p>

                            <div class="flex flex-wrap gap-4 text-sm text-ivoire-text/60">
                                <div class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $tattooer->city ?: 'Non spécifié' }}
                                </div>

                                @if ($tattooer->siret_verified)
                                    <div class="flex items-center gap-1 text-vert-succes">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Vérifié
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3">
                        <a href="{{ route('tattooer.settings') }}"
                            class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                            Modifier mon profil
                        </a>
                        <a href="{{ route('tattooer.portfolio') }}"
                            class="px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold hover:bg-noir-profond/80 transition-colors">
                            Gérer mon portfolio
                        </a>
                    </div>
                </div>

                <!-- Portfolio récent -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-ivoire-text">Portfolio récent</h2>
                        <a href="{{ route('tattooer.portfolio') }}"
                            class="text-beige-peau hover:text-beige-peau/80 text-sm">
                            Voir tout →
                        </a>
                    </div>

                    @if ($portfolio->count() > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($portfolio as $media)
                                <div class="aspect-square rounded-lg overflow-hidden group cursor-pointer">
                                    <img src="{{ $media->getUrl('thumb') }}" alt="Portfolio"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-titane/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-titane" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <p class="text-ivoire-text/60 mb-4">Aucune photo dans votre portfolio</p>
                            <a href="{{ route('tattooer.portfolio') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Ajouter des photos
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Statut marketplace -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Statut marketplace</h3>

                    <div class="space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-ivoire-text/70">Visibilité</span>
                            @if ($tattooer->user->status === 'active')
                                <span
                                    class="px-2 py-1 bg-vert-succes/20 text-vert-succes text-xs rounded-full font-semibold">
                                    Visible
                                </span>
                            @else
                                <span
                                    class="px-2 py-1 bg-ambre-warning/20 text-ambre-warning text-xs rounded-full font-semibold">
                                    En attente
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-ivoire-text/70">Vérification</span>
                            @if ($tattooer->siret_verified)
                                <span
                                    class="px-2 py-1 bg-vert-succes/20 text-vert-succes text-xs rounded-full font-semibold">
                                    Vérifié
                                </span>
                            @else
                                <span class="px-2 py-1 bg-titane/20 text-titane text-xs rounded-full font-semibold">
                                    Non vérifié
                                </span>
                            @endif
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-ivoire-text/70">Plan</span>
                            <span
                                class="px-2 py-1 bg-beige-peau/20 text-beige-peau text-xs rounded-full font-semibold uppercase">
                                {{ $tattooer->current_plan }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Liens utiles -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <h3 class="text-lg font-bold text-ivoire-text mb-4">Accès rapides</h3>

                    <div class="space-y-2">
                        <a href="{{ route('tattooer.dashboard') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-titane/10 transition-colors">
                            <svg class="w-5 h-5 text-ivoire-text/60" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                            <span class="text-ivoire-text">Dashboard</span>
                        </a>

                        <a href="{{ route('tattooer.requests') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-titane/10 transition-colors">
                            <svg class="w-5 h-5 text-ivoire-text/60" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <span class="text-ivoire-text">Demandes</span>
                        </a>

                        <a href="{{ route('tattooer.calendar') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-titane/10 transition-colors">
                            <svg class="w-5 h-5 text-ivoire-text/60" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="text-ivoire-text">Calendrier</span>
                        </a>

                        <a href="{{ route('tattooer.settings') }}"
                            class="flex items-center gap-3 p-3 rounded-lg hover:bg-titane/10 transition-colors">
                            <svg class="w-5 h-5 text-ivoire-text/60" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-ivoire-text">Paramètres</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

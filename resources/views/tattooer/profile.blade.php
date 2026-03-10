@extends('layouts.tattooer')

@section('title', 'Mon Profil')

@section('content')
    <div class="container mx-auto max-w-4xl">

        <!-- Header Profil Tattooer -->
        <div class="bg-gris-fonde border border-titane/20 shadow-md shadow-titane/20 rounded-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center gap-6">

                <!-- Avatar + Infos -->
                <div class="flex items-center gap-4 flex-1">
                    <!-- Avatar Spatie -->
                    <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 bg-beige-peau/10">
                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'thumb') ?: asset('images/default-tattooer-avatar.png') }}"
                            alt="{{ $tattooer->user->name }}"
                            class="w-full h-full object-cover border border-beige-peau/40 rounded-full shadow-lg shadow-beige-peau/20">
                    </div>

                    <div>
                        <!-- Pseudo affiché publiquement -->
                        <h1 class="text-3xl font-Satoshi font-bold text-cuivre mb-1">
                            {{ $tattooer->user->pseudo ?? $tattooer->user->first_name . ' ' . $tattooer->user->last_name }}
                        </h1>

                        <p class="text-ivoire-text/70 mb-4">
                            {{ $tattooer->city ?? '' }}{{ $tattooer->postal_code ? ', ' . $tattooer->postal_code : '' }}
                        </p>

                        <!-- Badges -->
                        <div class="flex gap-2 flex-wrap">
                            @if ($tattooer->has_compliance_badge)
                                <span
                                    class="bg-vert-succes/20 text-vert-succes px-3 py-1 rounded-full text-xs font-semibold">
                                    ✓ Conforme
                                </span>
                            @endif

                            <span
                                class="bg-beige-peau/20 text-beige-peau px-3 py-1 rounded-full border border-beige-peau/20 shadow-sm shadow-beige-peau/20 text-xs font-semibold">
                                @if ($tattooer->isOnTrial())
                                    ⭐ Plan PRO
                                @elseif ($tattooer->isPro())
                                    ⭐ Plan PRO
                                @else
                                    🟡 Plan STARTER
                                @endif
                            </span>

                            @if ($tattooer->user->status === 'pending_verification')
                                <span
                                    class="bg-orange-attention/20 text-orange-attention px-3 py-1 rounded-full text-xs font-semibold">
                                    ⏳ En attente validation
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 items-center justify-center">

                    <a href="{{ route($tattooer->routePrefix() . '.settings') }}"
                        class="text-black btn-shadow rounded-full px-2.5 py-1 font-semibold bg-beige-peau hover:bg-beige-peau/80 transition-colors duration-300 shadow-md shadow-beige-peau/20">
                        Modifier
                    </a>
                    @if ($tattooer->slug)
                        <a href="{{ route('marketplace.tattooer.show', $tattooer->slug) }}"
                            class="text-ivoire-text btn-shadow rounded-full px-2.5 py-1 font-semibold bg-titane/10 hover:bg-titane/20 transition-colors duration-300 shadow-md shadow-titane/20"
                            target="_blank">
                            Voir profil public
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Grid Principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Colonne Principale (2/3) -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Bio -->
                @if ($tattooer->bio)
                    <div class="bg-gris-fonde rounded-xl border border-titane/20 shadow-md shadow-titane/20 p-6">
                        <h2 class="text-xl font-Satoshi font-bold text-beige-peau mb-4">Bio</h2>
                        <p class="text-ivoire-text/80 leading-relaxed">{{ $tattooer->bio }}</p>
                    </div>
                @endif

                <!-- Portfolio (Spatie Media) -->
                <div class="bg-gris-fonde rounded-xl border border-titane/20 shadow-md shadow-titane/20 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-Satoshi font-bold text-beige-peau">Portfolio</h2>
                        <a href="{{ route($tattooer->routePrefix() . '.portfolio') }}"
                            class="text-cuivre text-sm font-semibold hover:underline">
                            Gérer →
                        </a>
                    </div>

                    @if (!empty($portfolio))
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($portfolio as $media)
                                <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond">
                                    @if (isset($media['url']) && !empty($media['url']))
                                        <img src="{{ $media['url'] }}" alt="Portfolio"
                                            class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
                                            loading="lazy">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-titane text-xs">
                                            URL manquante
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-ivoire-text/50 mb-4">Aucune image dans votre portfolio</p>
                            <a href="{{ route($tattooer->routePrefix() . '.portfolio') }}"
                                class="inline-block px-4 py-2 bg-beige-peau text-noir-profond font-semibold rounded-lg">
                                Ajouter des images
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Infos Professionnelles (privées pour tattooer uniquement) -->
                <div class="bg-gris-fonde rounded-xl border border-titane/20 shadow-md shadow-titane/20 p-6">
                    <h2 class="text-xl font-Satoshi font-bold text-beige-peau mb-4">
                        Informations professionnelles
                        <span class="text-xs text-ivoire-text/50 font-normal">(Informations privées)</span>
                    </h2>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-titane/20">
                            <span class="text-titane">Nom réel</span>
                            <span
                                class="text-ivoire-text">{{ $tattooer->user->first_name . ' ' . $tattooer->user->last_name }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-titane/20">
                            <span class="text-titane">SIRET</span>
                            <span class="text-ivoire-text font-mono">{{ $tattooer->siret ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-titane/20">
                            <span class="text-titane">Email</span>
                            <span class="text-ivoire-text">{{ $tattooer->user->email }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-titane">Téléphone</span>
                            <span class="text-ivoire-text">{{ $tattooer->phone ?? 'Non renseigné' }}</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Colonne Latérale (1/3) -->
            <div class="space-y-6">

                <!-- Statistiques -->
                <div class="bg-gris-fonde rounded-xl border border-titane/20 shadow-md shadow-titane/20 p-6">
                    <h3 class="text-lg font-Satoshi font-bold text-beige-peau mb-4">Mes stats</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-titane text-md underline underline-offset-4 mb-1">RDV ce mois</p>
                            <p class="text-2xl font-bold text-beige-peau">{{ $stats['completed_projects'] ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-titane text-md underline underline-offset-4 mb-1">Clients totaux</p>
                            <p class="text-2xl font-bold text-beige-peau">{{ $stats['total_clients'] ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-titane text-md underline underline-offset-4 mb-1">Projets actifs</p>
                            <p class="text-2xl font-bold text-beige-peau">{{ $stats['active_projects'] ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-titane text-md underline underline-offset-4 mb-1">Portfolio</p>
                            <p class="text-2xl font-bold text-beige-peau">{{ $stats['portfolio_count'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Actions Rapides -->
                <div class="bg-gris-fonde rounded-xl border border-titane/20 shadow-md shadow-titane/20 p-6">
                    <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-4">Actions rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route($tattooer->routePrefix() . '.dashboard') }}"
                            class="w-full flex btn-shadow items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-full transition-colors">
                            <span class="text-xl">📊</span>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                        <a href="{{ route($tattooer->routePrefix() . '.requests') }}"
                            class="w-full flex btn-shadow items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-full transition-colors">
                            <span class="text-xl">📅</span>
                            <span class="font-semibold">Demandes RDV</span>
                            @if (($stats['active_projects'] ?? 0) > 0)
                                <span
                                    class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                                    {{ $stats['active_projects'] ?? 0 }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route($tattooer->routePrefix() . '.calendar') }}"
                            class="w-full flex btn-shadow items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-full transition-colors">
                            <span class="text-xl">🗓️</span>
                            <span class="font-semibold">Calendrier</span>
                        </a>
                        @if (!$tattooer->has_compliance_badge)
                            <a href="{{ route($tattooer->routePrefix() . '.compliance') }}"
                                class="w-full flex btn-shadow items-center gap-3 px-4 py-3 bg-vert-succes/20 hover:bg-vert-succes/30 text-vert-succes rounded-full transition-colors">
                                <span class="text-xl">✓</span>
                                <span class="font-semibold">Obtenir badge conformité</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Upgrade PRO (si FREE) -->
                @if ($tattooer->isFree())
                    <div
                        class="bg-gradient-to-br from-beige-peau/20 to-beige-peau/5 border border-beige-peau/30 shadow-md shadow-beige-peau/20 rounded-xl p-6">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="text-2xl">⭐</span>
                            <div>
                                <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-1">Passez PRO</h3>
                                <p class="text-ivoire-text/70 text-sm">
                                    0% commission + fonctionnalités avancées
                                </p>
                            </div>
                        </div>
                        <x-ui.button variant="primary" class="flex justify-center items-center !w-full" size="lg"
                            href="{{ route($tattooer->routePrefix() . '.subscription.plans') }}">
                            Voir les abonnements
                        </x-ui.button>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

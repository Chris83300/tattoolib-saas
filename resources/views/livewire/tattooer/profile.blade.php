<div class="container mx-auto max-w-4xl">

    <!-- Header Profil Tattooer -->
    <div class="bg-gris-fonde rounded-xl p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center gap-6">

            <!-- Avatar + Infos -->
            <div class="flex items-center gap-4 flex-1">
                <!-- Avatar Spatie -->
                <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 bg-beige-peau/10">
                    <img src="{{ $tattooer->getFirstMediaUrl('avatar', 'thumb') ?: $user->avatar_url }}"
                        alt="{{ $user->displayName() }}" class="w-full h-full object-cover">
                </div>

                <div>
                    <!-- Pseudo affiché publiquement -->
                    <h1 class="text-3xl font-Satoshi font-bold text-ivoire-text mb-1">
                        {{ $user->displayName() }}
                    </h1>

                    <p class="text-ivoire-text/70 mb-2">
                        {{ $tattooer->city }}, {{ $tattooer->postal_code }}
                    </p>

                    <!-- Badges -->
                    <div class="flex gap-2 flex-wrap">
                        @if ($tattooer->has_compliance_badge)
                            <span
                                class="bg-vert-succes/20 text-vert-succes px-3 py-1 rounded-full text-xs font-semibold">
                                ✓ Conforme Ink&Pik
                            </span>
                        @endif

                        <span class="bg-beige-peau/20 text-beige-peau px-3 py-1 rounded-full text-xs font-semibold">
                            {{ $tattooer->current_plan === 'pro' ? '⭐ Plan PRO' : '🆓 Plan FREE' }}
                        </span>

                        @if ($user->status === 'pending_verification')
                            <span
                                class="bg-ambre-warning/20 text-ambre-warning px-3 py-1 rounded-full text-xs font-semibold">
                                ⏳ En attente validation
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <a href="{{ route('tattooer.settings') }}"
                    class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                    Modifier
                </a>
                @if ($tattooer->slug)
                    <a href="{{ route('marketplace.show', $tattooer->slug) }}" target="_blank"
                        class="px-4 py-2 border border-beige-peau text-beige-peau hover:bg-beige-peau/10 font-semibold rounded-lg transition-colors">
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
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-Satoshi font-bold text-ivoire-text">Bio</h2>
                    <button wire:click="toggleBioEdit" class="text-beige-peau text-sm font-semibold hover:underline">
                        {{ $editingBio ? 'Annuler' : 'Modifier' }}
                    </button>
                </div>

                @if (!$editingBio)
                    <!-- Affichage bio -->
                    <p class="text-ivoire-text/80 leading-relaxed">
                        {{ $tattooer->bio ?: 'Aucune bio renseignée.' }}
                    </p>
                @else
                    <!-- Édition bio -->
                    <form wire:submit.prevent="updateBio" class="space-y-4">
                        <textarea wire:model="bio" rows="4" placeholder="Parlez-nous de vous, de votre style, de votre expérience..."
                            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau transition-colors resize-none"></textarea>

                        <div class="flex gap-3">
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 disabled:bg-titane disabled:cursor-not-allowed text-noir-profond font-semibold rounded-lg transition-colors">
                                <span wire:loading.remove wire:target="updateBio">Enregistrer</span>
                                <span wire:loading wire:target="updateBio">...</span>
                            </button>
                            <button type="button" wire:click="toggleBioEdit"
                                class="px-4 py-2 border border-titane text-ivoire-text hover:bg-titane/10 font-semibold rounded-lg transition-colors">
                                Annuler
                            </button>
                        </div>
                    </form>
                @endif

                @if (session()->has('bio_success'))
                    <div class="mt-4 bg-vert-succes/10 border border-vert-succes rounded-lg p-3">
                        <p class="text-vert-succes text-sm">{{ session('bio_success') }}</p>
                    </div>
                @endif
            </div>

            <!-- Portfolio (Spatie Media) -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-Satoshi font-bold text-ivoire-text">Portfolio</h2>
                    <a href="{{ route('tattooer.settings') }}"
                        class="text-beige-peau text-sm font-semibold hover:underline">
                        Gérer →
                    </a>
                </div>

                @if ($portfolioImages->isNotEmpty())
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach ($portfolioImages as $media)
                            <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond">
                                <img src="{{ $media->getUrl() }}" alt="Portfolio"
                                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-ivoire-text/50 mb-4">Aucune image dans votre portfolio</p>
                        <a href="{{ route('tattooer.settings') }}"
                            class="inline-block px-4 py-2 bg-beige-peau text-noir-profond font-semibold rounded-lg">
                            Ajouter des images
                        </a>
                    </div>
                @endif
            </div>

            <!-- Infos Professionnelles (privées pour tattooer uniquement) -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <h2 class="text-xl font-Satoshi font-bold text-ivoire-text mb-4">
                    Informations professionnelles
                    <span class="text-xs text-ivoire-text/50 font-normal">(privées)</span>
                </h2>
                <div class="space-y-3">
                    <div class="flex justify-between py-2 border-b border-titane/20">
                        <span class="text-ivoire-text/50">Nom réel (ARS)</span>
                        <span class="text-ivoire-text">{{ $tattooer->name }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-titane/20">
                        <span class="text-ivoire-text/50">SIRET</span>
                        <span class="text-ivoire-text font-mono">{{ $tattooer->siret }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-titane/20">
                        <span class="text-ivoire-text/50">Email</span>
                        <span class="text-ivoire-text">{{ $tattooer->email }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-ivoire-text/50">Téléphone</span>
                        <span class="text-ivoire-text">{{ $tattooer->phone ?? 'Non renseigné' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Colonne Latérale (1/3) -->
        <div class="space-y-6">

            <!-- Statistiques -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-4">Mes stats</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-ivoire-text/50 text-xs mb-1">RDV ce mois</p>
                        <p class="text-2xl font-bold text-beige-peau">{{ $stats->appointments_this_month }}</p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/50 text-xs mb-1">Clients totaux</p>
                        <p class="text-2xl font-bold text-beige-peau">{{ $stats->total_clients }}</p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/50 text-xs mb-1">Revenus mois</p>
                        <p class="text-2xl font-bold text-beige-peau">
                            {{ number_format($stats->monthly_revenue, 2) }}€</p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/50 text-xs mb-1">Demandes en attente</p>
                        <p class="text-2xl font-bold text-beige-peau">{{ $stats->pending_requests }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-4">Actions rapides</h3>
                <div class="space-y-3">
                    <a href="{{ route('tattooer.dashboard') }}"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                        <span class="text-xl">📊</span>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="{{ route('tattooer.demandes') }}"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                        <span class="text-xl">📅</span>
                        <span class="font-semibold">Demandes RDV</span>
                        @if ($stats->pending_requests > 0)
                            <span
                                class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                                {{ $stats->pending_requests }}
                            </span>
                        @endif
                    </a>
                    <a href="/tattooer/messages"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                        <span class="text-xl">💬</span>
                        <span class="font-semibold">Messages</span>
                    </a>
                    <a href="/tattooer/calendar"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                        <span class="text-xl">🗓️</span>
                        <span class="font-semibold">Calendrier</span>
                    </a>
                    @if (!$tattooer->has_compliance_badge)
                        <a href="/tattooer/compliance"
                            class="w-full flex items-center gap-3 px-4 py-3 bg-vert-succes/20 hover:bg-vert-succes/30 text-vert-succes rounded-lg transition-colors">
                            <span class="text-xl">✓</span>
                            <span class="font-semibold">Obtenir badge conformité</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- Lien Profil Public -->
            <div class="bg-beige-peau/10 border border-beige-peau rounded-xl p-6">
                <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-2">Profil public</h3>
                <p class="text-ivoire-text/70 text-sm mb-4">
                    Partagez votre profil avec vos clients
                </p>
                <a href="{{ route('marketplace.show', $tattooer->slug) }}" target="_blank"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors">
                    <span>Voir mon profil</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Upgrade PRO (si FREE) -->
            @if ($tattooer->current_plan === 'free')
                <div
                    class="bg-gradient-to-br from-beige-peau/20 to-beige-peau/5 border border-beige-peau/30 rounded-xl p-6">
                    <div class="flex items-start gap-3 mb-4">
                        <span class="text-2xl">⭐</span>
                        <div>
                            <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-1">Passez PRO</h3>
                            <p class="text-ivoire-text/70 text-sm">
                                0% commission + fonctionnalités avancées
                            </p>
                        </div>
                    </div>
                    <a href="/tattooer/upgrade"
                        class="w-full block text-center px-4 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors">
                        Découvrir PRO
                    </a>
                </div>
            @endif

        </div>
    </div>
</div>

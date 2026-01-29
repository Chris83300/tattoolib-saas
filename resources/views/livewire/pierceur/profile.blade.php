<div class="min-h-screen bg-noir-profond py-8">
    <div class="container mx-auto px-4 max-w-6xl">

        <!-- Header Profil -->
        <div class="bg-gris-fonde rounded-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row gap-6">
                <!-- Avatar -->
                <div class="flex-shrink-0">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->displayName() }}"
                        class="w-32 h-32 rounded-full object-cover border-4 border-beige-peau/20">
                </div>

                <!-- Infos -->
                <div class="flex-1">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                        <div>
                            <h1 class="text-3xl font-Satoshi font-bold text-ivoire-text mb-2">
                                {{ $user->displayName() }}
                            </h1>
                            <p class="text-beige-peau font-semibold mb-1">{{ $pierceur->specialization_label }}</p>
                            <p class="text-ivoire-text/70">
                                {{ $pierceur->city }}, {{ $pierceur->postal_code }}
                            </p>
                        </div>

                        <!-- Badges -->
                        <div class="flex gap-2 flex-wrap">
                            @if ($pierceur->has_compliance_badge)
                                <span
                                    class="bg-vert-succes/20 text-vert-succes px-3 py-1 rounded-full text-xs font-semibold">
                                    ✓ Conforme Ink&Pik
                                </span>
                            @endif
                            @if ($pierceur->isPro())
                                <span
                                    class="bg-beige-peau/20 text-beige-peau px-3 py-1 rounded-full text-xs font-semibold">
                                    ⭐ PRO
                                </span>
                            @endif
                            @if ($user->status === 'pending_verification')
                                <span
                                    class="bg-ambre-warning/20 text-ambre-warning px-3 py-1 rounded-full text-xs font-semibold">
                                    ⏳ En attente validation
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 mt-4">
                        <a href="{{ route('pierceur.settings') }}"
                            class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                            Modifier
                        </a>
                        @if ($pierceur->slug)
                            <a href="{{ route('marketplace.show', $pierceur->slug) }}" target="_blank"
                                class="px-4 py-2 border border-beige-peau text-beige-peau hover:bg-beige-peau/10 font-semibold rounded-lg transition-colors">
                                Voir profil public
                            </a>
                        @endif
                    </div>
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
                        <button wire:click="toggleBioEdit"
                            class="text-beige-peau text-sm font-semibold hover:underline">
                            {{ $editingBio ? 'Annuler' : 'Modifier' }}
                        </button>
                    </div>

                    @if (!$editingBio)
                        <!-- Affichage bio -->
                        <p class="text-ivoire-text/80 leading-relaxed">
                            {{ $pierceur->bio ?: 'Aucune bio renseignée.' }}
                        </p>
                    @else
                        <!-- Édition bio -->
                        <form wire:submit.prevent="updateBio" class="space-y-4">
                            <textarea wire:model="bio" rows="4"
                                placeholder="Parlez-nous de vous, de votre spécialisation, de votre expérience..."
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
                        <a href="{{ route('pierceur.settings') }}"
                            class="text-beige-peau text-sm font-semibold hover:underline">
                            Gérer →
                        </a>
                    </div>

                    @if ($portfolioImages->isNotEmpty())
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($portfolioImages as $media)
                                <div class="aspect-square rounded-lg overflow-hidden bg-titane/20">
                                    <img src="{{ $media->getUrl('thumb') }}" alt="Portfolio"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform cursor-pointer">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-ivoire-text/50 mb-4">Aucune image dans votre portfolio</p>
                            <a href="{{ route('pierceur.settings') }}"
                                class="inline-block px-4 py-2 bg-beige-peau text-noir-profond font-semibold rounded-lg">
                                Ajouter des images
                            </a>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Colonne Latérale (1/3) -->
            <div class="space-y-6">

                <!-- Stats -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-4">Statistiques</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-ivoire-text/70">RDV ce mois</span>
                            <span
                                class="text-2xl font-bold text-beige-peau">{{ $stats->appointments_this_month }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-ivoire-text/70">Clients totaux</span>
                            <span class="text-2xl font-bold text-beige-peau">{{ $stats->total_clients }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-ivoire-text/70">Revenus mensuels</span>
                            <span
                                class="text-2xl font-bold text-beige-peau">{{ number_format($stats->monthly_revenue, 2) }}€</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-ivoire-text/70">Demandes en attente</span>
                            <span class="text-2xl font-bold text-beige-peau">{{ $stats->pending_requests }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-gris-fonde rounded-xl p-6">
                    <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-4">Actions rapides</h3>
                    <div class="space-y-3">
                        <a href="{{ route('pierceur.dashboard') }}"
                            class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                            <span class="text-xl">📊</span>
                            <span class="font-semibold">Dashboard</span>
                        </a>
                        <a href="{{ route('pierceur.booking-requests') }}"
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
                        <a href="/pierceur/messages"
                            class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                            <span class="text-xl">💬</span>
                            <span class="font-semibold">Messages</span>
                        </a>
                        <a href="/pierceur/calendar"
                            class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                            <span class="text-xl">🗓️</span>
                            <span class="font-semibold">Calendrier</span>
                        </a>
                        @if (!$pierceur->has_compliance_badge)
                            <a href="/pierceur/compliance"
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
                    <a href="{{ route('marketplace.show', $pierceur->slug) }}" target="_blank"
                        class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors">
                        <span>Voir mon profil</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                <!-- Upgrade PRO (si FREE) -->
                @if ($pierceur->isFree())
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
                        <a href="/pierceur/upgrade"
                            class="w-full block text-center px-4 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors">
                            Découvrir PRO
                        </a>
                    </div>
                @endif

            </div>

        </div>

    </div>
</div>

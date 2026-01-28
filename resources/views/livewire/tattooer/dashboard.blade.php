<div class="min-h-screen bg-noir-profond">

    <div class="container mx-auto px-4 py-8 max-w-6xl">

        <!-- Header profil artiste -->
        <div class="bg-gris-fonde rounded-xl p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
                <div class="flex-1">
                    <h1 class="text-ivoire-text font-display font-bold text-3xl mb-2">
                        Bonjour {{ $user->name }} 👋
                    </h1>
                    <p class="text-ivoire-text/70">
                        Voici votre tableau de bord professionnel
                    </p>
                </div>

                <!-- Statut compte -->
                <div class="flex flex-col gap-2">
                    @if ($user->status === 'pending_verification')
                        <div
                            class="bg-ambre-warning/20 border border-ambre-warning text-ambre-warning px-4 py-2 rounded-lg text-sm font-semibold">
                            ⏳ En attente de validation
                        </div>
                        <p class="text-ivoire-text/70 text-xs">
                            Votre profil sera visible après vérification admin
                        </p>
                    @elseif($user->status === 'active')
                        <div
                            class="bg-vert-succes/20 border border-vert-succes text-vert-succes px-4 py-2 rounded-lg text-sm font-semibold">
                            ✓ Compte actif
                        </div>

                        @if ($tattooer?->has_compliance_badge)
                            <div
                                class="bg-vert-succes/20 border border-vert-succes text-vert-succes px-4 py-2 rounded-lg text-sm font-semibold">
                                ✓ Badge Conforme Ink&Pik
                            </div>
                        @else
                            <a href="{{ route('tattooer.compliance') }}"
                                class="bg-beige-peau/20 border border-beige-peau text-beige-peau px-4 py-2 rounded-lg text-sm font-semibold text-center hover:bg-beige-peau/30">
                                Obtenir le badge conformité
                            </a>
                        @endif
                    @endif

                    <!-- Plan abonnement -->
                    <div class="bg-noir-profond px-4 py-2 rounded-lg text-center">
                        <p class="text-ivoire-text/50 text-xs mb-1">Plan actuel</p>
                        <p class="text-beige-peau font-bold uppercase">
                            {{ $tattooer?->current_plan ?? 'free' }}
                        </p>
                        @if (($tattooer?->current_plan ?? 'free') === 'free')
                            <a href="{{ route('tattooer.upgrade') }}" class="text-beige-peau text-xs hover:underline">
                                Passer PRO (0% commission)
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lien profil public -->
            <div class="pt-4 border-t border-titane/20">
                <a href="/marketplace/{{ $tattooer->slug ?? '#' }}" target="_blank"
                    class="inline-flex items-center gap-2 text-beige-peau font-semibold hover:underline">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    Voir mon profil public
                </a>
            </div>
        </div>

        <!-- Stats rapides (grid 3 colonnes) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

            <!-- Demandes en attente -->
            <a href="{{ route('tattooer.booking-requests') }}"
                class="bg-gris-fonde hover:bg-beige-peau/5 rounded-xl p-6 transition-colors group">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.586z">
                        </path>
                    </svg>
                    <span class="text-3xl font-bold text-ivoire-text group-hover:text-beige-peau">
                        {{ $stats['pending_requests'] }}
                    </span>
                </div>
                <p class="text-ivoire-text/70 text-sm">Demandes en attente</p>
            </a>

            <!-- RDV à venir -->
            <a href="{{ route('tattooer.calendar') }}"
                class="bg-gris-fonde hover:bg-beige-peau/5 rounded-xl p-6 transition-colors group">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-3xl font-bold text-ivoire-text group-hover:text-beige-peau">
                        {{ $stats['upcoming_appointments'] }}
                    </span>
                </div>
                <p class="text-ivoire-text/70 text-sm">RDV à venir</p>
            </a>

            <!-- Messages non lus -->
            <a href="{{ route('tattooer.messages') }}"
                class="bg-gris-fonde hover:bg-beige-peau/5 rounded-xl p-6 transition-colors group">
                <div class="flex items-center justify-between mb-2">
                    <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                        </path>
                    </svg>
                    <span class="text-3xl font-bold text-ivoire-text group-hover:text-beige-peau">
                        {{ $stats['unread_messages'] }}
                    </span>
                </div>
                <p class="text-ivoire-text/70 text-sm">Messages non lus</p>
            </a>

        </div>

        <!-- Navigation rapide -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">

            <!-- Gestion profil -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Mon profil
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('tattooer.profile.edit') }}"
                        class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                        → Modifier mes infos
                    </a>
                    <a href="{{ route('tattooer.portfolio') }}"
                        class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                        → Gérer mon portfolio
                    </a>
                    <a href="{{ route('tattooer.availability') }}"
                        class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                        → Disponibilités & horaires
                    </a>
                    <a href="{{ route('tattooer.compliance') }}"
                        class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                        → Badge conformité
                    </a>
                </div>
            </div>

            <!-- Activité -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Activité
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('tattooer.bookings') }}"
                        class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                        → Toutes mes réservations
                    </a>
                    <a href="{{ route('tattooer.calendar') }}"
                        class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                        → Calendrier & planning
                    </a>
                    @if ($tattooer->subscription_plan === 'pro')
                        <a href="{{ route('tattooer.clients') }}"
                            class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                            → Historique clients
                        </a>
                        <a href="{{ route('tattooer.analytics') }}"
                            class="block text-ivoire-text/80 hover:text-beige-peau text-sm">
                            → Statistiques
                        </a>
                    @endif
                </div>
            </div>

        </div>

        <!-- Dernières demandes (preview) -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-ivoire-text font-display font-bold text-lg">
                    Dernières demandes
                </h3>
                <a href="{{ route('tattooer.booking-requests') }}"
                    class="text-beige-peau text-sm font-semibold hover:underline">
                    Tout voir →
                </a>
            </div>

            <div class="text-center py-8">
                <svg class="w-12 h-12 text-titane mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-2.586z">
                    </path>
                </svg>
                <p class="text-ivoire-text/50">
                    Aucune demande pour le moment
                </p>
            </div>
        </div>

    </div>

</div>

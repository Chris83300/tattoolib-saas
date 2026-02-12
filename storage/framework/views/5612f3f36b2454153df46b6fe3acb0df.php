<div class="container mx-auto max-w-6xl">
    <!-- DEBUG: Afficher les données -->
    <div class="bg-yellow-500/20 border border-yellow-500 rounded-lg p-4 mb-8">
        <h3 class="text-yellow-400 font-bold mb-2">DEBUG INFO:</h3>
        <p class="text-yellow-300">User: <?php echo e($user?->name ?? 'NULL'); ?></p>
        <p class="text-yellow-300">Role: <?php echo e($user?->role ?? 'NULL'); ?></p>
        <p class="text-yellow-300">Tattooer: <?php echo e($tattooer?->name ?? 'NULL'); ?></p>
        <p class="text-yellow-300">Pending Requests: <?php echo e($pendingRequests ?? 'NULL'); ?></p>
        <p class="text-yellow-300">Upcoming: <?php echo e($upcomingAppointments ?? 'NULL'); ?></p>
    </div>

    <!-- Header profil artiste -->
    <div class="bg-gris-fonde rounded-xl p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center gap-4 mb-6">
            <div class="flex-1">
                <h1 class="text-ivoire-text font-display font-bold text-3xl mb-2">
                    Bonjour <?php echo e($user?->name ?? 'Utilisateur'); ?> 👋
                </h1>
                <p class="text-ivoire-text/70">
                    Voici votre tableau de bord professionnel
                </p>
            </div>

            <div class="flex flex-col gap-2">
                <!-- Lien vers profil public -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer): ?>
                    <a href="/tattooer/<?php echo e($tattooer->slug); ?>" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-beige-peau/20 text-beige-peau rounded-lg text-sm font-semibold hover:bg-beige-peau/30 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Voir mon profil public
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Statut compte -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user?->status === 'pending_verification'): ?>
                    <div class="px-4 py-2 bg-yellow-500/20 text-yellow-400 rounded-lg text-sm font-semibold">
                        ⏳ Vérification en cours
                    </div>
                <?php elseif($user?->status === 'verified'): ?>
                    <div class="px-4 py-2 bg-green-500/20 text-green-400 rounded-lg text-sm font-semibold">
                        ✅ Compte vérifié
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-ivoire-text mb-1"><?php echo e($pendingRequests ?? 0); ?></h3>
            <p class="text-ivoire-text/70 text-sm">Demandes en attente</p>
        </div>

        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-ivoire-text mb-1"><?php echo e($upcomingAppointments ?? 0); ?></h3>
            <p class="text-ivoire-text/70 text-sm">Rendez-vous à venir</p>
        </div>

        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2a3 3 0 015.356 1.857M12 12h.01M12 8h.01">
                        </path>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-ivoire-text mb-1"><?php echo e($totalClients ?? 0); ?></h3>
            <p class="text-ivoire-text/70 text-sm">Clients totaux</p>
        </div>

        <div class="bg-gris-fonde rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-beige-peau/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                        </path>
                    </svg>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-ivoire-text mb-1"><?php echo e($monthlyRevenue ?? 0); ?>€</h3>
            <p class="text-ivoire-text/70 text-sm">Revenus ce mois</p>
        </div>
    </div>

    <!-- Demandes récentes -->
    <div class="bg-gris-fonde rounded-xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-ivoire-text">Demandes récentes</h2>
            <a href="/tattooer/demandes" class="text-beige-peau hover:text-beige-peau/80 transition-colors">
                Voir tout →
            </a>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $recentRequests ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="border-b border-ivoire-text/10 pb-4 mb-4 last:border-0 last:pb-0 last:mb-0">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-semibold text-ivoire-text">
                            <?php echo e($request->client->pseudo ?? ($request->client->first_name . ' ' . $request->client->last_name ?? 'Client')); ?>

                        </h3>
                        <p class="text-ivoire-text/70 text-sm"><?php echo e($request->tattoo_description ?? 'Non spécifié'); ?></p>
                        <p class="text-ivoire-text/50 text-xs mt-1"><?php echo e($request->created_at->format('d/m/Y H:i')); ?></p>
                    </div>
                    <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs font-semibold">
                        En attente
                    </span>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-beige-peau" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                    Aucune demande pour le moment
                </h3>
                <p class="text-ivoire-text/50">
                    Vous n'avez pas de nouvelles demandes de réservation.
                </p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\tattooer\dashboard.blade.php ENDPATH**/ ?>
<div class="container mx-auto max-w-4xl">

    <!-- Header Profil Tattooer -->
    <div class="bg-gris-fonde rounded-xl p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center gap-6">

            <!-- Avatar + Infos -->
            <div class="flex items-center gap-4 flex-1">
                <!-- Avatar Spatie -->
                <div class="w-20 h-20 rounded-full overflow-hidden flex-shrink-0 bg-beige-peau/10">
                    <img src="<?php echo e(auth()->user()->getFirstMediaUrl('avatar', 'thumb') ?: $user->avatar_url); ?>"
                        alt="<?php echo e($user->displayName()); ?>" class="w-full h-full object-cover border border-cuivre">
                </div>

                <div>
                    <!-- Pseudo affiché publiquement -->
                    <h1 class="text-3xl font-Satoshi font-bold text-ivoire-text mb-1">
                        <?php echo e($user->displayName()); ?>

                    </h1>

                    <p class="text-ivoire-text/70 mb-2">
                        <?php echo e($tattooer->city); ?>, <?php echo e($tattooer->postal_code); ?>

                    </p>

                    <!-- Badges -->
                    <div class="flex gap-2 flex-wrap">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer->has_compliance_badge): ?>
                            <span
                                class="bg-vert-succes/20 text-vert-succes px-3 py-1 rounded-full text-xs font-semibold">
                                ✓ Conforme Ink&Pik
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <span class="bg-beige-peau/20 text-beige-peau px-3 py-1 rounded-full text-xs font-semibold">
                            <?php echo e($tattooer->current_plan === 'pro' ? '⭐ Plan PRO' : '🆓 Plan FREE'); ?>

                        </span>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status === 'pending_verification'): ?>
                            <span
                                class="bg-ambre-warning/20 text-ambre-warning px-3 py-1 rounded-full text-xs font-semibold">
                                ⏳ En attente validation
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3">
                <a href="<?php echo e(route('tattooer.settings')); ?>"
                    class="px-4 py-2 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-semibold rounded-lg transition-colors">
                    Modifier
                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer->slug): ?>
                    <a href="<?php echo e(route('marketplace.tattooer.show', $tattooer->slug)); ?>" target="_blank"
                        class="px-4 py-2 border border-beige-peau text-beige-peau hover:bg-beige-peau/10 font-semibold rounded-lg transition-colors">
                        Voir profil public
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Grid Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Colonne Principale (2/3) -->
        <div class="lg:col-span-2 space-y-6">



            <!-- Portfolio (Spatie Media) -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-Satoshi font-bold text-ivoire-text">Portfolio</h2>
                    <a href="<?php echo e(route('tattooer.settings')); ?>"
                        class="text-beige-peau text-sm font-semibold hover:underline">
                        Gérer →
                    </a>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($portfolioImages->isNotEmpty()): ?>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $portfolioImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond">
                                <img src="<?php echo e($media->getUrl()); ?>" alt="Portfolio"
                                    class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-8">
                        <p class="text-ivoire-text/50 mb-4">Aucune image dans votre portfolio</p>
                        <a href="<?php echo e(route('tattooer.settings')); ?>"
                            class="inline-block px-4 py-2 bg-beige-peau text-noir-profond font-semibold rounded-lg">
                            Ajouter des images
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                        <span class="text-ivoire-text"><?php echo e($tattooer->name); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-titane/20">
                        <span class="text-ivoire-text/50">SIRET</span>
                        <span class="text-ivoire-text font-mono"><?php echo e($tattooer->siret); ?></span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-titane/20">
                        <span class="text-ivoire-text/50">Email</span>
                        <span class="text-ivoire-text"><?php echo e($tattooer->email); ?></span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-ivoire-text/50">Téléphone</span>
                        <span class="text-ivoire-text"><?php echo e($tattooer->phone ?? 'Non renseigné'); ?></span>
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
                        <p class="text-2xl font-bold text-beige-peau"><?php echo e($stats->appointments_this_month); ?></p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/50 text-xs mb-1">Clients totaux</p>
                        <p class="text-2xl font-bold text-beige-peau"><?php echo e($stats->total_clients); ?></p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/50 text-xs mb-1">Revenus mois</p>
                        <p class="text-2xl font-bold text-beige-peau">
                            <?php echo e(number_format($stats->monthly_revenue, 2)); ?>€</p>
                    </div>
                    <div>
                        <p class="text-ivoire-text/50 text-xs mb-1">Demandes en attente</p>
                        <p class="text-2xl font-bold text-beige-peau"><?php echo e($stats->pending_requests); ?></p>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="bg-gris-fonde rounded-xl p-6">
                <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-4">Actions rapides</h3>
                <div class="space-y-3">
                    <a href="<?php echo e(route('tattooer.dashboard')); ?>"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                        <span class="text-xl">📊</span>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="<?php echo e(route('tattooer.demandes')); ?>"
                        class="w-full flex items-center gap-3 px-4 py-3 bg-beige-peau/10 hover:bg-beige-peau/20 text-beige-peau rounded-lg transition-colors">
                        <span class="text-xl">📅</span>
                        <span class="font-semibold">Demandes RDV</span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats->pending_requests > 0): ?>
                            <span
                                class="ml-auto bg-rouge-alerte text-noir-profond px-2 py-0.5 rounded-full text-xs font-bold">
                                <?php echo e($stats->pending_requests); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$tattooer->has_compliance_badge): ?>
                        <a href="/tattooer/compliance"
                            class="w-full flex items-center gap-3 px-4 py-3 bg-vert-succes/20 hover:bg-vert-succes/30 text-vert-succes rounded-lg transition-colors">
                            <span class="text-xl">✓</span>
                            <span class="font-semibold">Obtenir badge conformité</span>
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Lien Profil Public -->
            <div class="bg-beige-peau/10 border border-beige-peau rounded-xl p-6">
                <h3 class="text-lg font-Satoshi font-bold text-ivoire-text mb-2">Profil public</h3>
                <p class="text-ivoire-text/70 text-sm mb-4">
                    Partagez votre profil avec vos clients
                </p>
                <a href="<?php echo e(route('marketplace.tattooer.show', $tattooer->slug)); ?>" target="_blank"
                    class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold rounded-lg transition-colors">
                    <span>Voir mon profil</span>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            <!-- Upgrade PRO (si FREE) -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer->current_plan === 'free'): ?>
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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\tattooer\profile.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
    <div x-data="{ activeTab: '<?php echo e(request()->get('tab', 'info')); ?>', editMode: false }" class="space-y-4 pb-20">
        
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                <a href="<?php echo e(route('studio.clients.index')); ?>"
                    class="mt-1 p-2 rounded-lg hover:bg-noir-profond transition-colors flex-shrink-0">
                    <svg class="w-5 h-5 text-titane" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <?php
                    $avatarUrl = $client->user?->getFirstMediaUrl('avatar') ?: $client->getFirstMediaUrl('avatar');
                    $pseudo = $client->pseudo ?? ($client->user?->pseudo ?? null);
                    $fullName = trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''));
                    if (!$fullName) {
                        $fullName = $client->user?->name ?? 'Client';
                    }
                ?>

                <div
                    class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden bg-titane/30 flex-shrink-0 flex items-center justify-center">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($avatarUrl): ?>
                        <img src="<?php echo e($avatarUrl); ?>" alt="<?php echo e($pseudo ?? $fullName); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span class="text-2xl font-bold text-beige-peau">
                            <?php echo e(strtoupper(substr($client->first_name ?? '?', 0, 1))); ?><?php echo e(strtoupper(substr($client->last_name ?? '', 0, 1))); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="flex-1 min-w-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pseudo): ?>
                        <h1 class="text-xl md:text-2xl font-bold text-ivoire-text truncate"><?php echo e($pseudo); ?></h1>
                        <p class="text-sm text-ivoire-text/60"><?php echo e($fullName); ?></p>
                    <?php else: ?>
                        <h1 class="text-xl md:text-2xl font-bold text-ivoire-text truncate"><?php echo e($fullName); ?></h1>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex flex-wrap gap-2 mt-2">
                        <span class="px-2 py-0.5 bg-beige-peau/20 text-beige-peau rounded text-xs font-medium">
                            <?php echo e($stats->total_requests); ?> demande<?php echo e($stats->total_requests > 1 ? 's' : ''); ?>

                        </span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats->completed > 0): ?>
                            <span class="px-2 py-0.5 bg-vert-succes/20 text-vert-succes rounded text-xs font-medium">
                                <?php echo e($stats->completed); ?> terminée<?php echo e($stats->completed > 1 ? 's' : ''); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($stats->total_paid > 0): ?>
                            <span class="px-2 py-0.5 bg-titane/20 text-titane rounded text-xs font-medium">
                                <?php echo e(number_format($stats->total_paid, 0)); ?>€
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->is_blacklisted): ?>
                            <span class="px-2 py-0.5 bg-rouge-alerte/20 text-rouge-alerte rounded text-xs font-semibold">⛔
                                Blacklisté</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->no_show_count > 0): ?>
                            <span class="px-2 py-0.5 bg-ambre-warning/20 text-ambre-warning rounded text-xs font-medium">
                                <?php echo e($client->no_show_count); ?> no-show<?php echo e($client->no_show_count > 1 ? 's' : ''); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <?php echo $__env->make('partials.pdf-download-button', [
                            'url' => route('pdf.client-summary', $client),
                            'label' => 'Fiche client PDF',
                            'size' => 'xs',
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-1.5 sticky top-0 z-10 min-w-0">
            <div class="flex gap-1 overflow-x-auto pb-1 min-w-0" style="-webkit-overflow-scrolling: touch;">
                <?php
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
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeTab = '<?php echo e($key); ?>'"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg font-semibold whitespace-nowrap transition-all text-sm flex-shrink-0"
                        :class="activeTab === '<?php echo e($key); ?>' ? 'bg-beige-peau text-noir-profond' :
                            'text-titane hover:text-ivoire-text hover:bg-noir-profond'">
                        <span><?php echo e($tab['icon']); ?></span>
                        <span><?php echo e($tab['label']); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($tab['count']) && $tab['count'] > 0): ?>
                            <span class="px-1.5 py-0.5 rounded-full text-xs"
                                :class="activeTab === '<?php echo e($key); ?>' ? 'bg-noir-profond/20 text-noir-profond' :
                                    'bg-titane/20 text-titane'">
                                <?php echo e($tab['count']); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
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

                
                <div x-show="editMode" x-cloak>
                    <form action="<?php echo e(route('studio.clients.update', $client)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Prénom</label>
                                <input type="text" name="first_name" value="<?php echo e($client->first_name ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Nom</label>
                                <input type="text" name="last_name" value="<?php echo e($client->last_name ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Pseudo</label>
                                <input type="text" name="pseudo" value="<?php echo e($client->pseudo ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Email</label>
                                <input type="email" name="email" value="<?php echo e($client->user?->email ?? $client->email); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Téléphone</label>
                                <input type="tel" name="phone" value="<?php echo e($client->phone ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Date de
                                    naissance</label>
                                <input type="date" name="birth_date"
                                    value="<?php echo e($client->birth_date?->format('Y-m-d') ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Adresse</label>
                                <input type="text" name="address" value="<?php echo e($client->address ?? ''); ?>"
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

                
                <div x-show="!editMode" class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->user?->email ?? $client->email): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-titane flex-shrink-0">📧</span>
                                <span
                                    class="text-ivoire-text text-sm truncate"><?php echo e($client->user?->email ?? $client->email); ?></span>
                            </div>
                            <a href="mailto:<?php echo e($client->user?->email ?? $client->email); ?>"
                                class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->phone): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-titane flex-shrink-0">📱</span>
                                <span class="text-ivoire-text text-sm"><?php echo e($client->phone); ?></span>
                            </div>
                            <a href="tel:<?php echo e($client->phone); ?>"
                                class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->birth_date): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-titane flex-shrink-0">🎂</span>
                            <span class="text-ivoire-text text-sm">
                                <?php echo e($client->birth_date->format('d/m/Y')); ?> (<?php echo e($client->birth_date->age); ?> ans)
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->birth_date->age < 18): ?>
                                    <span
                                        class="ml-1 px-1.5 py-0.5 bg-ambre-warning/20 text-ambre-warning rounded text-xs font-semibold">MINEUR</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->address): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-titane flex-shrink-0">📍</span>
                            <span class="text-ivoire-text text-sm"><?php echo e($client->address); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!($client->user?->email ?? $client->email) && !$client->phone && !$client->birth_date && !$client->address): ?>
                        <p class="text-sm text-titane italic">Aucune information de contact. <button
                                @click="editMode = true" class="text-beige-peau hover:underline">Ajouter</button></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-beige-peau"><?php echo e($stats->total_requests); ?></p>
                    <p class="text-xs text-titane mt-1">Demandes</p>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-vert-succes"><?php echo e($stats->completed); ?></p>
                    <p class="text-xs text-titane mt-1">Réalisés</p>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-ivoire-text"><?php echo e(number_format($stats->total_paid, 0)); ?>€</p>
                    <p class="text-xs text-titane mt-1">Total versé</p>
                </div>
                <div class="bg-gris-fonde rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-ivoire-text"><?php echo e($stats->total_appointments); ?></p>
                    <p class="text-xs text-titane mt-1">RDV</p>
                </div>
            </div>

        </div>

        
        <div x-show="activeTab === 'history'" x-cloak class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-gris-fonde rounded-xl p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-semibold text-ivoire-text text-sm"><?php echo e($br->tattoo_style ?? 'Tattoo'); ?> —
                                    <?php echo e($br->body_zone ?? 'Non précisé'); ?></h4>
                                <?php
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
                                ?>
                                <span
                                    class="px-2 py-0.5 <?php echo e($statusConfig['bg']); ?> <?php echo e($statusConfig['text']); ?> rounded text-xs font-semibold"><?php echo e($statusConfig['label']); ?></span>
                            </div>
                            <p class="text-xs text-titane mt-1"><?php echo e($br->created_at->translatedFormat('d F Y')); ?></p>
                            <p class="text-xs text-titane/60 mt-1">
                                Artiste: <?php echo e($br->bookable?->user?->name ?? 'Non assigné'); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->bookable instanceof \App\Models\Piercer): ?>
                                    💎
                                <?php else: ?>
                                    🎨
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                        <a href="<?php echo e(route('studio.demandes.show', $br)); ?>"
                            class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-titane">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->total_deposit_amount): ?>
                            <span>💰 Acompte : <?php echo e(number_format($br->total_deposit_amount, 0)); ?>€ <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->deposit_paid_at): ?>
                                    <span class="text-vert-succes">(payé)</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->price_estimate_min && $br->price_estimate_max): ?>
                            <span>🏷️
                                <?php echo e(number_format($br->price_estimate_min, 0)); ?>-<?php echo e(number_format($br->price_estimate_max, 0)); ?>€</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->tattoo_size): ?>
                            <span>📐 <?php echo e($br->tattoo_size); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-gris-fonde rounded-xl p-8 text-center">
                    <p class="text-titane">Aucune demande enregistrée</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'consent'" x-cloak class="space-y-4">
            <?php if (isset($component)) { $__componentOriginal0482ca2f8c9f05860018c80a5d052c03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0482ca2f8c9f05860018c80a5d052c03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pro-gate','data' => ['feature' => 'la gestion des consentements SNAT']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pro-gate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['feature' => 'la gestion des consentements SNAT']); ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentDocuments->count() > 0): ?>
                    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📄 Consentements
                            scannés</h3>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $consentDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-noir-profond/50 rounded-lg">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="text-2xl flex-shrink-0">📄</span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-ivoire-text truncate">
                                                <?php echo e($document->file_name); ?></p>
                                            <p class="text-xs text-titane">
                                                Uploadé le <?php echo e($document->created_at->format('d/m/Y H:i')); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($document->getCustomProperty('consent_date')): ?>
                                                    · Signé le
                                                    <?php echo e(\Carbon\Carbon::parse($document->getCustomProperty('consent_date'))->format('d/m/Y')); ?>

                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="<?php echo e($document->getUrl()); ?>" target="_blank"
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $consent = $consents[$br->id] ?? null; ?>
                    <div class="bg-gris-fonde rounded-xl p-4" x-data="{ expanded: <?php echo e($loop->first ? 'true' : 'false'); ?> }">
                        <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                            <div class="flex items-center gap-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->isValid()): ?>
                                    <span
                                        class="w-8 h-8 bg-vert-succes/20 text-vert-succes rounded-full flex items-center justify-center text-sm">✅</span>
                                <?php else: ?>
                                    <span
                                        class="w-8 h-8 bg-ambre-warning/20 text-ambre-warning rounded-full flex items-center justify-center text-sm">⚠️</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div>
                                    <p class="text-sm font-semibold text-ivoire-text">
                                        <?php echo e($br->tattoo_style ?? 'Tattoo'); ?> — <?php echo e($br->body_zone ?? 'Non précisé'); ?>

                                        · <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->isValid()): ?>
                                            <span class="text-vert-succes">Signé</span>
                                        <?php else: ?>
                                            <span class="text-ambre-warning">En attente</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                    <p class="text-xs text-titane"><?php echo e($br->created_at->format('d/m/Y')); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->signed_at): ?>
                                            · Signé le <?php echo e($consent->signed_at->format('d/m/Y')); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->isValid()): ?>
                                <div class="space-y-3 text-ivoire-text">
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">📋 Identité</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                            <p><span class="text-ivoire-text/60">Nom:</span>
                                                <?php echo e($consent->client_full_name ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Né(e) le:</span>
                                                <?php echo e($consent->client_birth_date?->format('d/m/Y') ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Tél:</span>
                                                <?php echo e($consent->client_phone ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Email:</span>
                                                <?php echo e($consent->client_email ?? 'N/R'); ?></p>
                                            <p class="md:col-span-2"><span class="text-ivoire-text/60">Adresse:</span>
                                                <?php echo e($consent->client_address ?? 'N/R'); ?></p>
                                        </div>
                                    </div>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">✍️ Signature</p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->signature_data): ?>
                                            <img src="<?php echo e($consent->signature_data); ?>" alt="Signature"
                                                class="h-16 bg-white rounded mb-2">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-ivoire-text/60">Date:</span>
                                                <?php echo e($consent->signed_at?->format('d/m/Y à H:i')); ?></p>
                                            <p><span class="text-ivoire-text/60">IP:</span>
                                                <?php echo e($consent->signed_ip ?? 'N/R'); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-titane text-center">Consentement verrouillé après signature.</p>
                                    <div class="flex justify-center mt-3">
                                        <?php echo $__env->make('partials.pdf-download-button', [
                                            'url' => route('pdf.consent-form', $consent),
                                            'label' => 'Télécharger le consentement (PDF)',
                                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-6">
                                    <span class="text-3xl mb-2 block">⏳</span>
                                    <p class="text-sm text-ivoire-text/70">En attente de signature du client</p>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php if($consentDocuments->count() === 0): ?>
                        <div class="bg-gris-fonde rounded-xl p-8 text-center">
                            <span class="text-3xl mb-2 block">📝</span>
                            <p class="text-titane">Aucun consentement</p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0482ca2f8c9f05860018c80a5d052c03)): ?>
<?php $attributes = $__attributesOriginal0482ca2f8c9f05860018c80a5d052c03; ?>
<?php unset($__attributesOriginal0482ca2f8c9f05860018c80a5d052c03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0482ca2f8c9f05860018c80a5d052c03)): ?>
<?php $component = $__componentOriginal0482ca2f8c9f05860018c80a5d052c03; ?>
<?php unset($__componentOriginal0482ca2f8c9f05860018c80a5d052c03); ?>
<?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'trace'" x-cloak class="space-y-4">
            <?php if (isset($component)) { $__componentOriginal0482ca2f8c9f05860018c80a5d052c03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0482ca2f8c9f05860018c80a5d052c03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pro-gate','data' => ['feature' => 'la traçabilité réglementaire']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pro-gate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['feature' => 'la traçabilité réglementaire']); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $traceabilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trace): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-gris-fonde rounded-xl p-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <h4 class="font-semibold text-ivoire-text text-sm">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace->procedure_notes): ?>
                                            <?php echo e(\Illuminate\Support\Str::limit($trace->procedure_notes, 50)); ?>

                                        <?php else: ?>
                                            Séance du <?php echo e($trace->procedure_date->format('d/m/Y')); ?>

                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </h4>
                                    <span
                                        class="px-2 py-0.5 bg-beige-peau/20 text-beige-peau rounded text-xs font-semibold">
                                        <?php echo e($trace->procedure_date->format('d/m/Y')); ?>

                                    </span>
                                </div>
                                <p class="text-xs text-titane mt-1">
                                    Artiste: <?php echo e($trace->tattooer?->user?->name ?? 'Non spécifié'); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace->procedure_start_time && $trace->procedure_end_time): ?>
                                        · <?php echo e($trace->procedure_start_time); ?> - <?php echo e($trace->procedure_end_time); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace->room_number): ?>
                                    <p class="text-xs text-titane/60 mt-1">Salle: <?php echo e($trace->room_number); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace->needles && $trace->needles->count() > 0): ?>
                            <div class="mt-3">
                                <p class="text-xs font-semibold text-ivoire-text/60 uppercase mb-2">
                                    <?php echo e($trace->bookable instanceof \App\Models\Piercer ? 'Canules' : 'Aiguilles'); ?>

                                    utilisées
                                </p>
                                <div class="space-y-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $trace->needles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $needle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="text-xs text-titane">
                                            #<?php echo e($loop->iteration); ?>: <?php echo e($needle->brand ?? 'N/R'); ?> -
                                            Lot: <?php echo e($needle->lot_number ?? 'N/R'); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($needle->type): ?>
                                                (<?php echo e($needle->type); ?>)
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace->inks_used && is_array($trace->inks_used) && count($trace->inks_used) > 0): ?>
                            <div class="mt-3">
                                <p class="text-xs font-semibold text-ivoire-text/60 uppercase mb-2">Encres utilisées</p>
                                <div class="space-y-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $trace->inks_used; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ink): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p class="text-xs text-titane">
                                            #<?php echo e($loop->iteration); ?>:
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ink['brand'] ?? null): ?>
                                                <?php echo e($ink['brand']); ?> -
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ink['color'] ?? null): ?>
                                                <?php echo e($ink['color']); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ink['lot_number'] ?? null): ?>
                                                · Lot: <?php echo e($ink['lot_number']); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($ink['quantity_ml'] ?? null): ?>
                                                · <?php echo e($ink['quantity_ml']); ?>ml
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="bg-gris-fonde rounded-xl p-8 text-center">
                        <span class="text-3xl mb-2 block">🔬</span>
                        <p class="text-titane">Aucune traçabilité enregistrée</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0482ca2f8c9f05860018c80a5d052c03)): ?>
<?php $attributes = $__attributesOriginal0482ca2f8c9f05860018c80a5d052c03; ?>
<?php unset($__attributesOriginal0482ca2f8c9f05860018c80a5d052c03); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0482ca2f8c9f05860018c80a5d052c03)): ?>
<?php $component = $__componentOriginal0482ca2f8c9f05860018c80a5d052c03; ?>
<?php unset($__componentOriginal0482ca2f8c9f05860018c80a5d052c03); ?>
<?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'notes'" x-cloak class="space-y-4">
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider">Notes internes</h3>
                </div>
                <form action="<?php echo e(route('studio.clients.update', $client)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <textarea name="notes" rows="6" placeholder="Ajouter des notes sur ce client..."
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-none"><?php echo e($client->notes ?? ''); ?></textarea>
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">💾
                        Enregistrer les notes</button>
                </form>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\client-show.blade.php ENDPATH**/ ?>
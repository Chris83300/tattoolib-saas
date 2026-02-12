<?php $__env->startSection('content'); ?>
    <div x-data="{ activeTab: '<?php echo e(request()->get('tab', 'info')); ?>' }" class="space-y-4 pb-20">

        
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                
                <a href="<?php echo e(route('tattooer.clients')); ?>"
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

                <div class="w-16 h-16 md:w-20 md:h-20 rounded-full overflow-hidden bg-titane/30 flex-shrink-0 flex items-center justify-center">
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
                            <span class="px-2 py-0.5 bg-rouge-alerte/20 text-rouge-alerte rounded text-xs font-semibold">
                                ⛔ Blacklisté
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->no_show_count > 0): ?>
                            <span class="px-2 py-0.5 bg-ambre-warning/20 text-ambre-warning rounded text-xs font-medium">
                                <?php echo e($client->no_show_count); ?> no-show<?php echo e($client->no_show_count > 1 ? 's' : ''); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-1.5 sticky top-0 z-10">
            <div class="flex gap-1 overflow-x-auto pb-1" style="-webkit-overflow-scrolling: touch;">
                <?php
                    $tabs = [
                        'info' => ['label' => 'Infos', 'icon' => '👤'],
                        'history' => ['label' => 'Historique', 'icon' => '📜', 'count' => $bookingRequests->count()],
                        'consent' => ['label' => 'Consentement', 'icon' => '📝', 'count' => $consents->count()],
                        'trace' => ['label' => 'Traçabilité', 'icon' => '🔬', 'count' => $traceabilities->count()],
                        'media' => ['label' => 'Médias', 'icon' => '📸', 'count' => $chatMedia->count()],
                        'notes' => ['label' => 'Notes', 'icon' => '📋'],
                    ];
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeTab = '<?php echo e($key); ?>'"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg font-semibold whitespace-nowrap transition-all text-sm flex-shrink-0"
                        :class="activeTab === '<?php echo e($key); ?>'
                            ? 'bg-beige-peau text-noir-profond'
                            : 'text-titane hover:text-ivoire-text hover:bg-noir-profond'">
                        <span><?php echo e($tab['icon']); ?></span>
                        <span><?php echo e($tab['label']); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($tab['count']) && $tab['count'] > 0): ?>
                            <span class="px-1.5 py-0.5 rounded-full text-xs"
                                :class="activeTab === '<?php echo e($key); ?>'
                                    ? 'bg-noir-profond/20 text-noir-profond'
                                    : 'bg-titane/20 text-titane'">
                                <?php echo e($tab['count']); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div x-show="activeTab === 'info'" x-cloak class="space-y-4">

            
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">Contact</h3>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->user?->email ?? $client->email): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-titane flex-shrink-0">📧</span>
                                <span class="text-ivoire-text text-sm truncate"><?php echo e($client->user?->email ?? $client->email); ?></span>
                            </div>
                            <a href="mailto:<?php echo e($client->user?->email ?? $client->email); ?>"
                                class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <?php echo e($client->birth_date->format('d/m/Y')); ?>

                                (<?php echo e($client->birth_date->age); ?> ans)
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->birth_date->age < 18): ?>
                                    <span class="ml-1 px-1.5 py-0.5 bg-ambre-warning/20 text-ambre-warning rounded text-xs font-semibold">MINEUR</span>
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

            
            <div class="bg-gris-fonde rounded-xl p-4">
                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo e(route('tattooer.messages')); ?>"
                        class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold text-sm hover:bg-beige-peau/90 transition-colors">
                        💬 Envoyer un message
                    </a>
                </div>
            </div>
        </div>

        
        <div x-show="activeTab === 'history'" x-cloak class="space-y-3">

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-gris-fonde rounded-xl p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-semibold text-ivoire-text text-sm">
                                    <?php echo e($br->tattoo_style ?? 'Tattoo'); ?> — <?php echo e($br->body_zone ?? 'Non précisé'); ?>

                                </h4>
                                <?php
                                    $statusConfig = match ($br->status->value ?? $br->status) {
                                        'pending' => ['bg' => 'bg-ambre-warning/20', 'text' => 'text-ambre-warning', 'label' => '⏳ En attente'],
                                        'accepted' => ['bg' => 'bg-vert-succes/20', 'text' => 'text-vert-succes', 'label' => '✅ Acceptée'],
                                        'deposit_paid' => ['bg' => 'bg-vert-succes/20', 'text' => 'text-vert-succes', 'label' => '💰 Acompte payé'],
                                        'date_confirmed' => ['bg' => 'bg-beige-peau/20', 'text' => 'text-beige-peau', 'label' => '📅 Date confirmée'],
                                        'in_progress' => ['bg' => 'bg-beige-peau/20', 'text' => 'text-beige-peau', 'label' => '🎨 En cours'],
                                        'completed' => ['bg' => 'bg-vert-succes/20', 'text' => 'text-vert-succes', 'label' => '✅ Terminé'],
                                        'cancelled' => ['bg' => 'bg-rouge-alerte/20', 'text' => 'text-rouge-alerte', 'label' => '❌ Annulée'],
                                        'rejected' => ['bg' => 'bg-rouge-alerte/20', 'text' => 'text-rouge-alerte', 'label' => '❌ Refusée'],
                                        default => ['bg' => 'bg-titane/20', 'text' => 'text-titane', 'label' => ucfirst($br->status->value ?? $br->status)],
                                    };
                                ?>
                                <span class="px-2 py-0.5 <?php echo e($statusConfig['bg']); ?> <?php echo e($statusConfig['text']); ?> rounded text-xs font-semibold">
                                    <?php echo e($statusConfig['label']); ?>

                                </span>
                            </div>
                            <p class="text-xs text-titane mt-1"><?php echo e($br->created_at->translatedFormat('d F Y')); ?></p>
                        </div>

                        <a href="<?php echo e(route('tattooer.request.show', $br)); ?>"
                            class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                        </a>
                    </div>

                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-titane">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->total_deposit_amount): ?>
                            <span>💰 Acompte : <?php echo e(number_format($br->total_deposit_amount, 0)); ?>€
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->deposit_paid_at): ?>
                                    <span class="text-vert-succes">(payé)</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->price_estimate_min && $br->price_estimate_max): ?>
                            <span>🏷️ <?php echo e(number_format($br->price_estimate_min, 0)); ?>-<?php echo e(number_format($br->price_estimate_max, 0)); ?>€</span>
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

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $consent = $consents[$br->id] ?? null; ?>

                <div class="bg-gris-fonde rounded-xl p-4" x-data="{ expanded: <?php echo e($loop->first ? 'true' : 'false'); ?> }">
                    
                    <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                        <div class="flex items-center gap-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->isValid()): ?>
                                <span class="w-8 h-8 bg-vert-succes/20 text-vert-succes rounded-full flex items-center justify-center text-sm">✅</span>
                            <?php else: ?>
                                <span class="w-8 h-8 bg-ambre-warning/20 text-ambre-warning rounded-full flex items-center justify-center text-sm">⚠️</span>
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
                                <p class="text-xs text-titane">
                                    <?php echo e($br->created_at->format('d/m/Y')); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->signed_at): ?>
                                        · Signé le <?php echo e($consent->signed_at->format('d/m/Y')); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-titane transition-transform" :class="expanded ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    
                    <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t border-titane/20">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->isValid()): ?>
                            
                            <div class="space-y-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->allergies || $consent->has_skin_conditions || $consent->medications || $consent->is_pregnant): ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">Infos médicales déclarées</p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->allergies): ?>
                                            <p class="text-sm text-ivoire-text mb-1">🤧 <span class="text-ivoire-text/60">Allergies :</span>
                                                <?php echo e(is_array($consent->allergies) ? implode(', ', $consent->allergies) : $consent->allergies); ?>

                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->has_skin_conditions): ?>
                                            <p class="text-sm text-ivoire-text mb-1">🩹 <span class="text-ivoire-text/60">Peau :</span> Oui</p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medications): ?>
                                            <p class="text-sm text-ivoire-text mb-1">💊 <span class="text-ivoire-text/60">Médicaments :</span>
                                                <?php echo e(is_array($consent->medications) ? implode(', ', $consent->medications) : $consent->medications); ?>

                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->is_pregnant): ?>
                                            <p class="text-sm text-ivoire-text mb-1">🤰 <span class="text-ivoire-text/60">Grossesse :</span> Oui</p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->is_minor): ?>
                                    <div class="bg-ambre-warning/10 border border-ambre-warning/30 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ambre-warning uppercase mb-2">Consentement parental</p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->parent_name): ?>
                                            <p class="text-sm text-ivoire-text mb-1">👤 <?php echo e($consent->parent_name); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->parent_relation): ?>
                                            <p class="text-sm text-ivoire-text mb-1">🔗 <?php echo e($consent->parent_relation); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->parent_signature_data): ?>
                                            <p class="text-sm text-vert-succes mb-1">✍️ Signature parent présente</p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <div class="bg-noir-profond/50 rounded-lg p-3">
                                    <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">Signature du client</p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->signature_data): ?>
                                        <img src="<?php echo e($consent->signature_data); ?>" alt="Signature" class="h-12 bg-white rounded">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <p class="text-xs text-titane mt-1">Signé le <?php echo e($consent->signed_at->format('d/m/Y à H:i')); ?></p>
                                </div>

                                <p class="text-xs text-titane text-center mt-2">Ce consentement est verrouillé après signature.</p>
                            </div>
                        <?php else: ?>
                            
                            <div class="text-center py-6">
                                <span class="text-3xl mb-2 block">⏳</span>
                                <p class="text-sm text-ivoire-text/70">En attente de signature du client</p>
                                <p class="text-xs text-titane mt-1">Le formulaire sera envoyé dans le chat</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-gris-fonde rounded-xl p-8 text-center">
                    <p class="text-titane">Aucune demande nécessitant un consentement</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'trace'" x-cloak class="space-y-4">

            <?php
                $relevantAppointments = $appointments->filter(fn($apt) => $apt->bookingRequest);
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $relevantAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $trace = $traceabilities[$apt->id] ?? null; ?>

                <div class="bg-gris-fonde rounded-xl p-4"
                    x-data="{
                        expanded: <?php echo e($loop->first && !$trace ? 'true' : 'false'); ?>,
                        inks: <?php echo e(json_encode($trace?->sterile_equipment['inks'] ?? [['brand' => '', 'color' => '', 'lot_number' => '']])); ?>

                    }">

                    
                    <div class="flex items-center justify-between cursor-pointer" @click="expanded = !expanded">
                        <div class="flex items-center gap-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace && $trace->isComplete()): ?>
                                <span class="w-8 h-8 bg-vert-succes/20 text-vert-succes rounded-full flex items-center justify-center text-sm">✅</span>
                            <?php elseif($trace): ?>
                                <span class="w-8 h-8 bg-ambre-warning/20 text-ambre-warning rounded-full flex items-center justify-center text-sm">📝</span>
                            <?php else: ?>
                                <span class="w-8 h-8 bg-rouge-alerte/20 text-rouge-alerte rounded-full flex items-center justify-center text-sm">⚠️</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div>
                                <p class="text-sm font-semibold text-ivoire-text">
                                    <?php echo e($apt->bookingRequest?->tattoo_style ?? 'Tatouage'); ?> — <?php echo e($apt->bookingRequest?->body_zone ?? ''); ?>

                                    · <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace && $trace->isComplete()): ?>
                                        <span class="text-vert-succes">Complète</span>
                                    <?php elseif($trace): ?>
                                        <span class="text-ambre-warning">En cours</span>
                                    <?php else: ?>
                                        <span class="text-rouge-alerte">À remplir</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                <p class="text-xs text-titane">
                                    RDV <?php echo e($apt->start_datetime?->translatedFormat('l d F Y à H:i') ?? 'Date à définir'); ?>

                                </p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-titane transition-transform" :class="expanded ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    
                    <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t border-titane/20">
                        <form action="<?php echo e(route('tattooer.traceability.store', $apt)); ?>" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            <?php echo csrf_field(); ?>

                            
                            <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Aiguilles & Cartouches</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs text-titane block mb-1">Marque aiguilles</label>
                                    <input type="text" name="needle_brand"
                                        value="<?php echo e($trace?->sterile_equipment['needles'][0]['brand'] ?? ''); ?>"
                                        placeholder="Ex : Cheyenne, FK Irons..."
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">N° de lot aiguilles</label>
                                    <input type="text" name="needle_lot_number"
                                        value="<?php echo e($trace?->sterile_equipment['needles'][0]['lot_number'] ?? ''); ?>"
                                        placeholder="Numéro de lot"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">Marque cartouches</label>
                                    <input type="text" name="cartridge_brand"
                                        value="<?php echo e($trace?->sterile_equipment['needles'][1]['brand'] ?? ''); ?>"
                                        placeholder="Ex : Peak, Kwadron..."
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">N° de lot cartouches</label>
                                    <input type="text" name="cartridge_lot_number"
                                        value="<?php echo e($trace?->sterile_equipment['needles'][1]['lot_number'] ?? ''); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                            </div>

                            
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Encres utilisées</p>
                                <button type="button" @click="inks.push({brand: '', color: '', lot_number: ''})"
                                    class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">
                                    + Ajouter une encre
                                </button>
                            </div>

                            <template x-for="(ink, index) in inks" :key="index">
                                <div class="bg-noir-profond/30 rounded-lg p-3 mb-2 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-titane font-semibold" x-text="'Encre ' + (index + 1)"></span>
                                        <button type="button" @click="if(inks.length > 1) inks.splice(index, 1)"
                                            x-show="inks.length > 1" class="text-rouge-alerte/60 hover:text-rouge-alerte">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                    <input type="text" :name="'inks[' + index + '][brand]'" x-model="ink.brand"
                                        placeholder="Marque (ex : World Famous)"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" :name="'inks[' + index + '][color]'" x-model="ink.color"
                                            placeholder="Couleur"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        <input type="text" :name="'inks[' + index + '][lot_number]'" x-model="ink.lot_number"
                                            placeholder="N° lot"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </template>

                            
                            <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Stérilisation</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-titane block mb-1">Date stérilisation</label>
                                    <input type="date" name="sterilization_date"
                                        value="<?php echo e($trace?->sterile_equipment['sterilization_date'] ?? ''); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">N° lot stérilisation</label>
                                    <input type="text" name="sterilization_lot_number"
                                        value="<?php echo e($trace?->sterile_equipment['sterilization_lot_number'] ?? ''); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">N° cycle autoclave</label>
                                    <input type="text" name="autoclave_cycle_number"
                                        value="<?php echo e($trace?->sterile_equipment['autoclave_cycle_number'] ?? ''); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                            </div>

                            
                            <textarea name="other_supplies" rows="2" placeholder="Autres fournitures (film, crème, gants, vaseline...)"
                                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"><?php echo e($trace?->procedure_notes ?? ''); ?></textarea>

                            <textarea name="notes" rows="2" placeholder="Notes complémentaires..."
                                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"><?php echo e($trace?->equipment_notes ?? ''); ?></textarea>

                            
                            <div>
                                <label class="text-xs text-titane block mb-1">📸 Photos des numéros de lot (optionnel)</label>
                                <input type="file" name="lot_photos[]" multiple accept="image/*"
                                    onchange="previewFiles(this)"
                                    class="w-full text-sm text-ivoire-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau/20 file:text-beige-peau file:font-semibold file:text-xs">
                                <div class="upload-preview flex gap-2 mt-2 flex-wrap"></div>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace): ?>
                                    <?php $lotPhotos = $trace->getMedia('lot_photos'); ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lotPhotos->count() > 0): ?>
                                        <div class="flex gap-2 mt-2 flex-wrap">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $lotPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="w-16 h-16 rounded-lg overflow-hidden bg-noir-profond cursor-pointer border border-titane/20 hover:border-beige-peau transition-colors"
                                                    data-lb="<?php echo e($photo->getUrl()); ?>"
                                                    onclick="window.openLightbox('<?php echo e($photo->getUrl()); ?>')">
                                                    <img src="<?php echo e($photo->getUrl()); ?>" alt="<?php echo e($photo->file_name); ?>"
                                                        class="w-full h-full object-cover"
                                                        onerror="this.style.display='none'">
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <button type="submit"
                                class="w-full px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                                <?php echo e($trace ? '💾 Mettre à jour la traçabilité' : '✅ Enregistrer la traçabilité'); ?>

                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-gris-fonde rounded-xl p-8 text-center">
                    <p class="text-titane">Aucun rendez-vous nécessitant une traçabilité</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'media'" x-cloak class="space-y-4">

            
            <div class="bg-gris-fonde rounded-xl p-4">
                <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">
                    💬 Photos des conversations (<?php echo e($chatMedia->count()); ?>)
                </h3>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chatMedia->count() > 0): ?>
                    <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chatMedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond relative group">
                                <img src="<?php echo e($media->getUrl()); ?>" alt=""
                                    class="w-full h-full object-cover cursor-pointer" loading="lazy"
                                    data-lb="<?php echo e($media->getUrl()); ?>"
                                    onclick="window.openLightbox('<?php echo e($media->getUrl()); ?>')"
                                    onerror="this.style.display='none'">
                                <form action="<?php echo e(route('tattooer.client.media.delete', [$client, $media->id])); ?>"
                                    method="POST"
                                    class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" onclick="return confirm('Supprimer cette photo ?')"
                                        class="w-6 h-6 bg-rouge-alerte rounded-full flex items-center justify-center shadow-lg hover:bg-rouge-alerte/80">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <p class="text-titane text-sm text-center py-4">Aucune photo échangée</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequests->filter(fn($br) => $br->deposit_paid_at); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-gris-fonde rounded-xl p-4">
                    <h4 class="text-sm font-bold text-ivoire-text mb-3">
                        📸 <?php echo e($br->tattoo_style ?? 'Tattoo'); ?> — <?php echo e($br->body_zone ?? ''); ?>

                        <span class="text-xs text-titane font-normal ml-1">(<?php echo e($br->created_at->format('d/m/Y')); ?>)</span>
                    </h4>

                    <?php $tattooPhotos = $br->getMedia('tattoo_results'); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooPhotos->count() > 0): ?>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tattooPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond relative group">
                                    <img src="<?php echo e($photo->getUrl()); ?>" alt=""
                                        class="w-full h-full object-cover cursor-pointer"
                                        data-lb="<?php echo e($photo->getUrl()); ?>"
                                        onclick="window.openLightbox('<?php echo e($photo->getUrl()); ?>')">
                                    <form action="<?php echo e(route('tattooer.client.media.delete', [$client, $photo->id])); ?>"
                                        method="POST"
                                        class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" onclick="return confirm('Supprimer cette photo ?')"
                                            class="w-6 h-6 bg-rouge-alerte rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <form action="<?php echo e(route('tattooer.client.photos.upload', [$client, $br])); ?>" method="POST"
                        enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp"
                            onchange="previewFiles(this)"
                            class="flex-1 text-sm text-ivoire-text file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau/20 file:text-beige-peau file:font-semibold file:text-xs">
                        <button type="submit"
                            class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold text-xs hover:bg-beige-peau/90 transition-colors flex-shrink-0">
                            Upload
                        </button>
                    </form>
                    <div class="upload-preview flex gap-2 mt-2 flex-wrap"></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div x-show="activeTab === 'notes'" x-cloak>
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">Notes privées</h3>
                <p class="text-xs text-titane mb-3">Visibles uniquement par vous. Allergies, préférences, comportement...</p>

                <form action="<?php echo e(route('tattooer.client.update-notes', $client)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <textarea name="notes" rows="8"
                        placeholder="Allergies connues, préférences, comportement au salon, informations utiles..."
                        class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-y"><?php echo e($client->notes ?? ''); ?></textarea>
                    <button type="submit"
                        class="mt-3 w-full sm:w-auto px-6 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold text-sm hover:bg-beige-peau/90 transition-colors">
                        Enregistrer les notes
                    </button>
                </form>
            </div>
        </div>

    </div>

    
    <div id="lightbox" class="hidden fixed inset-0 bg-black/95 z-[60] flex items-center justify-center"
        onclick="if(event.target===this)window.closeLightbox()">
        <button onclick="window.closeLightbox()"
            class="absolute top-4 right-4 p-2 text-white/70 hover:text-white z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <button onclick="window.lightboxNav(-1)"
            class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 p-3 text-white/70 hover:text-white z-10 bg-black/40 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button onclick="window.lightboxNav(1)"
            class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 p-3 text-white/70 hover:text-white z-10 bg-black/40 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
        <img id="lightbox-img" src="" alt=""
            class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg"
            onclick="event.stopPropagation()">
        <div id="lightbox-counter"
            class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm bg-black/50 px-3 py-1 rounded-full">
        </div>
    </div>

    
    <script>
        // ═══ LIGHTBOX ═══
        (function() {
            var images = [];
            var index = 0;

            window.openLightbox = function(url) {
                images = [];
                document.querySelectorAll('[data-lb]').forEach(function(el) {
                    images.push(el.getAttribute('data-lb'));
                });
                index = images.indexOf(url);
                if (index === -1) {
                    images = [url];
                    index = 0;
                }
                showLb();
                document.getElementById('lightbox').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            window.closeLightbox = function() {
                document.getElementById('lightbox').classList.add('hidden');
                document.body.style.overflow = '';
            };

            window.lightboxNav = function(dir) {
                index = (index + dir + images.length) % images.length;
                showLb();
            };

            function showLb() {
                document.getElementById('lightbox-img').src = images[index];
                document.getElementById('lightbox-counter').textContent = (index + 1) + ' / ' + images.length;
            }

            document.addEventListener('keydown', function(e) {
                if (document.getElementById('lightbox').classList.contains('hidden')) return;
                if (e.key === 'Escape') window.closeLightbox();
                if (e.key === 'ArrowLeft') window.lightboxNav(-1);
                if (e.key === 'ArrowRight') window.lightboxNav(1);
            });
        })();

        // ═══ PREVIEW UPLOAD ═══
        function previewFiles(input) {
            var preview = input.closest('div').querySelector('.upload-preview')
                || input.parentElement.querySelector('.upload-preview');
            if (!preview) return;
            preview.innerHTML = '';
            Array.from(input.files).forEach(function(file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var div = document.createElement('div');
                    div.className = 'w-14 h-14 rounded-lg overflow-hidden border-2 border-beige-peau/40';
                    div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // ═══ RESTAURATION TAB DEPUIS HASH ═══
        document.addEventListener('DOMContentLoaded', function() {
            var hash = window.location.hash.replace('#', '');
            if (hash) {
                var check = setInterval(function() {
                    var el = document.querySelector('[x-data]');
                    if (el && el._x_dataStack) {
                        clearInterval(check);
                        el._x_dataStack[0].activeTab = hash;
                    }
                }, 50);
                setTimeout(function() { clearInterval(check); }, 1000);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\tattooer\client-show.blade.php ENDPATH**/ ?>
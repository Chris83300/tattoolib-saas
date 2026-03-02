<?php $__env->startSection('content'); ?>
    <div x-data="{ activeTab: '<?php echo e(request()->get('tab', 'info')); ?>', editMode: false }" class="space-y-4 pb-20">

        
        <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4">
                <a href="<?php echo e(route($tattooer->routePrefix() . '.clients')); ?>"
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
                            <span class="px-2 py-0.5 bg-rouge-alerte/20 text-rouge-alerte rounded text-xs font-semibold">⛔ Blacklisté</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->no_show_count > 0): ?>
                            <span class="px-2 py-0.5 bg-ambre-warning/20 text-ambre-warning rounded text-xs font-medium">
                                <?php echo e($client->no_show_count); ?> no-show<?php echo e($client->no_show_count > 1 ? 's' : ''); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="mt-3">
                        <?php echo $__env->make('partials.pdf-download-button', [
                            'url'   => route('pdf.client-summary', $client),
                            'label' => 'Fiche client PDF',
                            'size'  => 'xs',
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-gris-fonde rounded-xl p-1.5 sticky top-0 z-10 min-w-0">
            <div class="flex gap-1 overflow-x-auto pb-1 min-w-0" style="-webkit-overflow-scrolling: touch;">
                <?php
                    $consentDocuments = isset($consentDocuments) ? $consentDocuments : collect();
                    $standaloneTraces = isset($standaloneTraces) ? $standaloneTraces : collect();
                    $clientPhotos = isset($clientPhotos) ? $clientPhotos : collect();

                    $tabs = [
                        'info' => ['label' => 'Infos', 'icon' => '👤'],
                        'history' => ['label' => 'Historique', 'icon' => '📜', 'count' => $bookingRequests->count()],
                        'consent' => ['label' => 'Consentement', 'icon' => '📝', 'count' => $consents->count() + $consentDocuments->count()],
                        'trace' => ['label' => 'Traçabilité', 'icon' => '🔬', 'count' => $traceabilities->count() + $standaloneTraces->count()],
                        'media' => ['label' => 'Médias', 'icon' => '📸', 'count' => $chatMedia->count() + $clientPhotos->count()],
                        'notes' => ['label' => 'Notes', 'icon' => '📋'],
                    ];
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tabs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $tab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button @click="activeTab = '<?php echo e($key); ?>'"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-lg font-semibold whitespace-nowrap transition-all text-sm flex-shrink-0"
                        :class="activeTab === '<?php echo e($key); ?>' ? 'bg-beige-peau text-noir-profond' : 'text-titane hover:text-ivoire-text hover:bg-noir-profond'">
                        <span><?php echo e($tab['icon']); ?></span>
                        <span><?php echo e($tab['label']); ?></span>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($tab['count']) && $tab['count'] > 0): ?>
                            <span class="px-1.5 py-0.5 rounded-full text-xs"
                                :class="activeTab === '<?php echo e($key); ?>' ? 'bg-noir-profond/20 text-noir-profond' : 'bg-titane/20 text-titane'">
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
                    <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.update', $client)); ?>" method="POST">
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
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Date de naissance</label>
                                <input type="date" name="birth_date" value="<?php echo e($client->birth_date?->format('Y-m-d') ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Adresse</label>
                                <input type="text" name="address" value="<?php echo e($client->address ?? ''); ?>"
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">✅ Enregistrer</button>
                            <button type="button" @click="editMode = false" class="px-4 py-2 border border-titane/30 text-titane rounded-lg text-sm hover:bg-noir-profond transition-colors">Annuler</button>
                        </div>
                    </form>
                </div>

                
                <div x-show="!editMode" class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->user?->email ?? $client->email): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-titane flex-shrink-0">📧</span>
                                <span class="text-ivoire-text text-sm truncate"><?php echo e($client->user?->email ?? $client->email); ?></span>
                            </div>
                            <a href="mailto:<?php echo e($client->user?->email ?? $client->email); ?>" class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            </a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->phone): ?>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="text-titane flex-shrink-0">📱</span>
                                <span class="text-ivoire-text text-sm"><?php echo e($client->phone); ?></span>
                            </div>
                            <a href="tel:<?php echo e($client->phone); ?>" class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                                <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
                            </a>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->birth_date): ?>
                        <div class="flex items-center gap-2">
                            <span class="text-titane flex-shrink-0">🎂</span>
                            <span class="text-ivoire-text text-sm">
                                <?php echo e($client->birth_date->format('d/m/Y')); ?> (<?php echo e($client->birth_date->age); ?> ans)
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
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!($client->user?->email ?? $client->email) && !$client->phone && !$client->birth_date && !$client->address): ?>
                        <p class="text-sm text-titane italic">Aucune information de contact. <button @click="editMode = true" class="text-beige-peau hover:underline">Ajouter</button></p>
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
                    <a href="<?php echo e(route($tattooer->routePrefix() . '.messages')); ?>" class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold text-sm hover:bg-beige-peau/90 transition-colors">💬 Envoyer un message</a>
                </div>
            </div>

        </div>

        
        <div x-show="activeTab === 'history'" x-cloak class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $bookingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $br): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-gris-fonde rounded-xl p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h4 class="font-semibold text-ivoire-text text-sm"><?php echo e($br->tattoo_style ?? 'Tattoo'); ?> — <?php echo e($br->body_zone ?? 'Non précisé'); ?></h4>
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
                                <span class="px-2 py-0.5 <?php echo e($statusConfig['bg']); ?> <?php echo e($statusConfig['text']); ?> rounded text-xs font-semibold"><?php echo e($statusConfig['label']); ?></span>
                            </div>
                            <p class="text-xs text-titane mt-1"><?php echo e($br->created_at->translatedFormat('d F Y')); ?></p>
                        </div>
                        <a href="<?php echo e(route($tattooer->routePrefix() . '.request.show', $br)); ?>" class="p-2 bg-noir-profond rounded-lg hover:bg-beige-peau/20 transition-colors flex-shrink-0">
                            <svg class="w-4 h-4 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                        </a>
                    </div>
                    <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-titane">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->total_deposit_amount): ?>
                            <span>💰 Acompte : <?php echo e(number_format($br->total_deposit_amount, 0)); ?>€ <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->deposit_paid_at): ?> <span class="text-vert-succes">(payé)</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->price_estimate_min && $br->price_estimate_max): ?>
                            <span>🏷️ <?php echo e(number_format($br->price_estimate_min, 0)); ?>-<?php echo e(number_format($br->price_estimate_max, 0)); ?>€</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->tattoo_size): ?> <span>📐 <?php echo e($br->tattoo_size); ?></span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-gris-fonde rounded-xl p-8 text-center"><p class="text-titane">Aucune demande enregistrée</p></div>
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

                
                <div class="bg-gris-fonde rounded-xl p-4 md:p-6" x-data="{ consentMode: 'none' }">
                    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📤 Ajouter un consentement</h3>

                    <div class="flex flex-col sm:flex-row gap-2 mb-4" x-show="consentMode === 'none'">
                        <button @click="consentMode = 'upload'" type="button"
                            class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-sm text-ivoire-text hover:border-beige-peau transition-colors text-center">
                            📄 Uploader un scan papier
                        </button>
                        <button @click="consentMode = 'digital'" type="button"
                            class="flex-1 px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-sm text-ivoire-text hover:border-beige-peau transition-colors text-center">
                            ✍️ Formulaire numérique
                        </button>
                    </div>

                    <div x-show="consentMode !== 'none'" class="mb-4">
                        <button @click="consentMode = 'none'" type="button" class="text-xs text-titane hover:text-beige-peau transition-colors">← Retour au choix</button>
                    </div>

                    
                    <div x-show="consentMode === 'upload'" x-cloak x-collapse>
                        <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.consent.upload', $client)); ?>" method="POST" enctype="multipart/form-data" class="space-y-3">
                            <?php echo csrf_field(); ?>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Fichier (PDF/JPG/PNG)</label>
                                    <input type="file" name="consent_file" required accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-beige-peau file:text-noir-profond">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-ivoire-text/60 mb-1">Date du consentement</label>
                                    <input type="date" name="consent_date" required value="<?php echo e(now()->format('Y-m-d')); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                </div>
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">📤 Uploader</button>
                        </form>
                    </div>

                    
                    <div x-show="consentMode === 'digital'" x-cloak x-collapse>
                        <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.consent.store-digital', $client)); ?>" method="POST" class="space-y-4" id="digital-consent-form">
                            <?php echo csrf_field(); ?>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-3">📋 Identité client</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Nom complet *</label>
                                        <input type="text" name="client_full_name" required value="<?php echo e(trim(($client->first_name ?? '') . ' ' . ($client->last_name ?? ''))); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Date de naissance *</label>
                                        <input type="date" name="client_birth_date" required value="<?php echo e($client->birth_date?->format('Y-m-d')); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Téléphone</label>
                                        <input type="tel" name="client_phone" value="<?php echo e($client->phone); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Email</label>
                                        <input type="email" name="client_email" value="<?php echo e($client->user?->email ?? $client->email); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label class="text-xs text-titane block mb-1">Adresse</label>
                                        <input type="text" name="client_address" value="<?php echo e($client->address); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Type pièce d'identité</label>
                                        <select name="client_id_type" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                            <option value="">-- Choisir --</option>
                                            <option value="cni">Carte d'identité</option>
                                            <option value="passeport">Passeport</option>
                                            <option value="permis">Permis de conduire</option>
                                            <option value="titre_sejour">Titre de séjour</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">N° pièce d'identité</label>
                                        <input type="text" name="client_id_number"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3" x-data="{ isMinor: false }">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="is_minor" value="1" x-model="isMinor" class="w-4 h-4 rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                                    <span class="text-xs font-bold text-ambre-warning uppercase">👶 Client mineur</span>
                                </label>
                                <div x-show="isMinor" x-cloak x-collapse class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Nom parent/tuteur *</label>
                                        <input type="text" name="parent_name" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Relation</label>
                                        <select name="parent_relation" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                            <option value="pere">Père</option>
                                            <option value="mere">Mère</option>
                                            <option value="tuteur">Tuteur légal</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">N° pièce identité parent</label>
                                        <input type="text" name="parent_id_number" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-3">🏥 Questionnaire médical</p>
                                <div class="space-y-2">
                                    <?php
                                        $medicalFields = [
                                            'medical_allergies' => ['label' => '🤧 Allergies (latex, encres, pansements...)', 'detail' => true],
                                            'medical_anticoagulant' => ['label' => '💉 Traitement anticoagulant', 'detail' => false],
                                            'medical_diabetes' => ['label' => '🩸 Diabète', 'detail' => false],
                                            'medical_cicatrisation' => ['label' => '🩹 Problèmes de cicatrisation', 'detail' => false],
                                            'medical_skin_disease' => ['label' => '🩹 Maladie de peau (eczéma, psoriasis...)', 'detail' => true],
                                            'medical_vih_hepatite' => ['label' => '🔬 VIH / Hépatite', 'detail' => false],
                                            'medical_pregnant' => ['label' => '🤰 Grossesse ou allaitement', 'detail' => false],
                                            'medical_roaccutane' => ['label' => '💊 Traitement Roaccutane (< 1 an)', 'detail' => false],
                                            'medical_cheloide' => ['label' => '⚕️ Tendance aux chéloïdes', 'detail' => false],
                                        ];
                                    ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $medicalFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $config): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div x-data="{ checked: false }">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="<?php echo e($field); ?>" value="1" x-model="checked" class="w-4 h-4 rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                                                <span class="text-sm text-ivoire-text"><?php echo e($config['label']); ?></span>
                                            </label>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($config['detail']): ?>
                                                <div x-show="checked" x-cloak x-collapse class="mt-1 ml-6">
                                                    <input type="text" name="<?php echo e($field); ?>_detail" placeholder="Précisez..."
                                                        class="w-full px-3 py-1.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-xs focus:border-beige-peau">
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="mt-2">
                                        <label class="text-xs text-titane block mb-1">📝 Autres pathologies ou traitements</label>
                                        <textarea name="medical_other" rows="2" placeholder="Précisez..."
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"></textarea>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-3">🎨 Informations tatouage</p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Zone du corps</label>
                                        <input type="text" name="body_zone" placeholder="Ex : Avant-bras gauche"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Description / Style</label>
                                        <input type="text" name="tattoo_description" placeholder="Ex : Mandala géométrique"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-3">💰 Clause financière</p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Prix total (€)</label>
                                        <input type="number" name="total_price" step="0.01" min="0"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Acompte versé (€)</label>
                                        <input type="number" name="deposit_amount" step="0.01" min="0"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div class="flex items-end">
                                        <label class="flex items-center gap-2 cursor-pointer pb-2">
                                            <input type="checkbox" name="retouche_included" value="1" class="w-4 h-4 rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                                            <span class="text-sm text-ivoire-text">Retouche incluse</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-3">📷 Autorisation image</p>
                                <div class="flex flex-wrap gap-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="image_authorization" value="1" class="w-4 h-4 border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                                        <span class="text-sm text-ivoire-text">✅ Autorisée</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="image_authorization" value="0" class="w-4 h-4 border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau">
                                        <span class="text-sm text-ivoire-text">❌ Refusée</span>
                                    </label>
                                </div>
                            </div>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-3">✅ Confirmations obligatoires</p>
                                <div class="space-y-2">
                                    <?php
                                        $confirmFields = [
                                            'confirm_medical_sincere' => 'Je déclare que les informations médicales sont sincères et complètes',
                                            'confirm_risks_informed' => 'J\'ai été informé(e) des risques liés au tatouage',
                                            'confirm_info_sheet_read' => 'J\'ai lu et compris la fiche d\'information pré-acte',
                                            'confirm_aftercare_received' => 'J\'ai reçu les consignes de soins post-tatouage',
                                            'confirm_not_intoxicated' => 'Je certifie ne pas être sous l\'emprise d\'alcool ou de stupéfiants',
                                            'confirm_over_18_or_authorized' => 'Je certifie être majeur(e) ou avoir l\'autorisation parentale',
                                            'confirm_rgpd' => 'J\'accepte le traitement de mes données (RGPD)',
                                        ];
                                    ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $confirmFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-start gap-2 cursor-pointer">
                                            <input type="checkbox" name="<?php echo e($field); ?>" value="1" required class="w-4 h-4 rounded border-titane/30 bg-noir-profond text-beige-peau focus:ring-beige-peau mt-0.5">
                                            <span class="text-sm text-ivoire-text leading-tight"><?php echo e($label); ?></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            
                            <div class="bg-noir-profond/50 rounded-lg p-3">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-3">✍️ Signature du client</p>
                                <div class="mb-3">
                                    <label class="text-xs text-titane block mb-1">Mention manuscrite : « Lu et approuvé » *</label>
                                    <input type="text" name="handwritten_mention" required placeholder="Écrire : Lu et approuvé"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                </div>
                                <div class="mb-2">
                                    <label class="text-xs text-titane block mb-1">Signature (dessiner ci-dessous) *</label>
                                    <div class="relative">
                                        <canvas id="consent-signature-pad" width="600" height="200"
                                            class="w-full h-32 sm:h-40 bg-white rounded-lg border-2 border-titane/30 cursor-crosshair touch-none"></canvas>
                                        <button type="button" onclick="clearSignaturePad()"
                                            class="absolute top-2 right-2 px-2 py-1 bg-noir-profond/70 text-white text-xs rounded hover:bg-noir-profond">Effacer</button>
                                    </div>
                                    <input type="hidden" name="signature_data" id="consent-signature-data" required>
                                </div>
                            </div>

                            <button type="submit" onclick="return submitConsentForm()"
                                class="w-full px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                                ✅ Enregistrer le consentement SNAT
                            </button>
                        </form>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consentDocuments->count() > 0): ?>
                    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📄 Consentements scannés</h3>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $consentDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-noir-profond/50 rounded-lg">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <span class="text-2xl flex-shrink-0">📄</span>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-ivoire-text truncate"><?php echo e($document->file_name); ?></p>
                                            <p class="text-xs text-titane">
                                                Uploadé le <?php echo e($document->created_at->format('d/m/Y H:i')); ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($document->getCustomProperty('consent_date')): ?>
                                                    · Signé le <?php echo e(\Carbon\Carbon::parse($document->getCustomProperty('consent_date'))->format('d/m/Y')); ?>

                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <a href="<?php echo e($document->getUrl()); ?>" target="_blank" class="p-2 bg-beige-peau/20 text-beige-peau rounded-lg hover:bg-beige-peau/30 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        </a>
                                        <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.consent.delete', [$client, $document->id])); ?>" method="POST" onsubmit="return confirm('Supprimer ce consentement ?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 bg-rouge-alerte/20 text-rouge-alerte rounded-lg hover:bg-rouge-alerte/30 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
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
                                    <span class="w-8 h-8 bg-vert-succes/20 text-vert-succes rounded-full flex items-center justify-center text-sm">✅</span>
                                <?php else: ?>
                                    <span class="w-8 h-8 bg-ambre-warning/20 text-ambre-warning rounded-full flex items-center justify-center text-sm">⚠️</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div>
                                    <p class="text-sm font-semibold text-ivoire-text">
                                        <?php echo e($br->tattoo_style ?? 'Tattoo'); ?> — <?php echo e($br->body_zone ?? 'Non précisé'); ?>

                                        · <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->isValid()): ?> <span class="text-vert-succes">Signé</span> <?php else: ?> <span class="text-ambre-warning">En attente</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                    <p class="text-xs text-titane"><?php echo e($br->created_at->format('d/m/Y')); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->signed_at): ?> · Signé le <?php echo e($consent->signed_at->format('d/m/Y')); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-titane transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>
                        <div x-show="expanded" x-cloak x-collapse class="mt-4 pt-4 border-t border-titane/20">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent && $consent->isValid()): ?>
                                <div class="space-y-3 text-ivoire-text">
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">📋 Identité</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                            <p><span class="text-ivoire-text/60">Nom:</span> <?php echo e($consent->client_full_name ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Né(e) le:</span> <?php echo e($consent->client_birth_date?->format('d/m/Y') ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Tél:</span> <?php echo e($consent->client_phone ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Email:</span> <?php echo e($consent->client_email ?? 'N/R'); ?></p>
                                            <p class="md:col-span-2"><span class="text-ivoire-text/60">Adresse:</span> <?php echo e($consent->client_address ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Pièce:</span> <?php echo e($consent->client_id_type ? ucfirst($consent->client_id_type) : 'N/R'); ?> - <?php echo e($consent->client_id_number ?? 'N/R'); ?></p>
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->is_minor): ?>
                                        <div class="bg-ambre-warning/10 border border-ambre-warning/30 rounded-lg p-3">
                                            <p class="text-xs font-bold text-ambre-warning uppercase mb-2">👶 Autorisation parentale</p>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                                                <p><span class="text-ivoire-text/60">Parent:</span> <?php echo e($consent->parent_name ?? 'N/R'); ?></p>
                                                <p><span class="text-ivoire-text/60">Relation:</span> <?php echo e($consent->parent_relation ? ucfirst($consent->parent_relation) : 'N/R'); ?></p>
                                                <p><span class="text-ivoire-text/60">N° pièce:</span> <?php echo e($consent->parent_id_number ?? 'N/R'); ?></p>
                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">🏥 Médical</p>
                                        <div class="space-y-1 text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_allergies): ?> <p>🤧 Allergies: <?php echo e($consent->medical_allergies_detail ?: 'Oui'); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_anticoagulant): ?> <p>💉 Anticoagulant: Oui</p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_diabetes): ?> <p>🩸 Diabète: Oui</p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_cicatrisation): ?> <p>🩹 Cicatrisation difficile: Oui</p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_skin_disease): ?> <p>🩹 Maladie peau: <?php echo e($consent->medical_skin_disease_detail ?: 'Oui'); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_vih_hepatite): ?> <p>🔬 VIH/Hépatite: Oui</p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_pregnant): ?> <p>🤰 Grossesse: Oui</p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_roaccutane): ?> <p>💊 Roaccutane: Oui</p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_cheloide): ?> <p>⚕️ Chéloïdes: Oui</p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->medical_other): ?> <p>📝 Autres: <?php echo e($consent->medical_other); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">💰 Financier</p>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 text-sm">
                                            <p><span class="text-ivoire-text/60">Prix:</span> <?php echo e($consent->total_price ? number_format($consent->total_price, 2, ',', ' ') . ' €' : 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Acompte:</span> <?php echo e($consent->deposit_amount ? number_format($consent->deposit_amount, 2, ',', ' ') . ' €' : 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Retouche:</span> <?php echo e($consent->retouche_included ? 'Incluse' : 'Non incluse'); ?></p>
                                        </div>
                                    </div>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">📷 Image</p>
                                        <p class="text-sm">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->image_authorization === true): ?> <span class="text-vert-succes">✅ Accordée</span>
                                            <?php elseif($consent->image_authorization === false): ?> <span class="text-rouge-alerte">❌ Refusée</span>
                                            <?php else: ?> <span class="text-ambre-warning">⚠️ N/R</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </p>
                                    </div>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">✅ Confirmations</p>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-1 text-sm">
                                            <p><?php echo $consent->confirm_medical_sincere ? '✅' : '❌'; ?> Déclarations sincères</p>
                                            <p><?php echo $consent->confirm_risks_informed ? '✅' : '❌'; ?> Risques informés</p>
                                            <p><?php echo $consent->confirm_info_sheet_read ? '✅' : '❌'; ?> Fiche info lue</p>
                                            <p><?php echo $consent->confirm_aftercare_received ? '✅' : '❌'; ?> Soins reçus</p>
                                            <p><?php echo $consent->confirm_not_intoxicated ? '✅' : '❌'; ?> Non intoxiqué</p>
                                            <p><?php echo $consent->confirm_over_18_or_authorized ? '✅' : '❌'; ?> +18 ou autorisé</p>
                                            <p><?php echo $consent->confirm_rgpd ? '✅' : '❌'; ?> RGPD</p>
                                        </div>
                                    </div>
                                    <div class="bg-noir-profond/50 rounded-lg p-3">
                                        <p class="text-xs font-bold text-ivoire-text/60 uppercase mb-2">✍️ Signature</p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($consent->signature_data): ?> <img src="<?php echo e($consent->signature_data); ?>" alt="Signature" class="h-16 bg-white rounded mb-2"> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="space-y-1 text-sm">
                                            <p><span class="text-ivoire-text/60">Mention:</span> <?php echo e($consent->handwritten_mention ?? 'N/R'); ?></p>
                                            <p><span class="text-ivoire-text/60">Date:</span> <?php echo e($consent->signed_at?->format('d/m/Y à H:i')); ?></p>
                                            <p><span class="text-ivoire-text/60">IP:</span> <?php echo e($consent->signed_ip ?? 'N/R'); ?></p>
                                        </div>
                                    </div>
                                    <p class="text-xs text-titane text-center">Consentement verrouillé après signature.</p>
                                    <div class="flex justify-center mt-3">
                                        <?php echo $__env->make('partials.pdf-download-button', [
                                            'url'   => route('pdf.consent-form', $consent),
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

                
                <div class="bg-gris-fonde text-ivoire-text rounded-xl p-4" x-data="{
                    showForm: false,
                    needles: [{ brand: '', lot_number: '', type: '<?php echo e($tattooer->isPiercer() ? 'canule' : 'aiguille'); ?>' }],
                    inks: [{ brand: '', color: '', lot_number: '' }]
                }">
                    <button @click="showForm = !showForm" type="button"
                        class="w-full flex items-center justify-between py-1 text-sm font-semibold"
                        :class="showForm ? 'text-beige-peau' : 'text-ivoire-text hover:text-beige-peau'">
                        <span>➕ Ajouter une traçabilité (séance hors plateforme)</span>
                        <svg class="w-5 h-5 transition-transform" :class="showForm ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="showForm" x-cloak x-collapse class="mt-4 pt-4 border-t border-titane/20">
                        <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.traceability.store', $client)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <?php echo csrf_field(); ?>

                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-titane block mb-1">Date séance *</label>
                                    <input type="date" name="session_date" required value="<?php echo e(now()->format('Y-m-d')); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer->isPiercer()): ?> Description piercing <?php else: ?> Description tattoo <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </label>
                                    <input type="text" name="tattoo_description"
                                        placeholder="<?php echo e($tattooer->isPiercer() ? 'Ex : Septum titane' : 'Ex : Mandala bras gauche'); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">Zone du corps</label>
                                    <input type="text" name="body_zone"
                                        placeholder="<?php echo e($tattooer->isPiercer() ? 'Ex : Nez, Hélix gauche' : 'Ex : Avant-bras gauche'); ?>"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                            </div>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooer->isPiercer()): ?>
                            
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Canules utilisées</p>
                                <button type="button" @click="needles.push({ brand: '', lot_number: '', type: 'canule' })" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
                            </div>
                            <template x-for="(needle, ni) in needles" :key="'sn'+ni">
                                <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-titane font-semibold" x-text="'#' + (ni + 1)"></span>
                                            <select :name="'needles[' + ni + '][type]'" x-model="needle.type" class="px-2 py-1 bg-noir-profond border border-titane/30 rounded text-ivoire-text text-xs focus:border-beige-peau">
                                                <option value="canule">Canule</option>
                                                <option value="aiguille_piercing">Aiguille piercing</option>
                                            </select>
                                        </div>
                                        <button type="button" @click="if(needles.length > 1) needles.splice(ni, 1)" x-show="needles.length > 1" class="text-rouge-alerte/60 hover:text-rouge-alerte">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" :name="'needles[' + ni + '][brand]'" x-model="needle.brand" placeholder="Marque (Caflon, Inverness...)"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        <input type="text" :name="'needles[' + ni + '][lot_number]'" x-model="needle.lot_number" placeholder="N° de lot"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </template>

                            
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Bijoux utilisés</p>
                                <button type="button" @click="inks.push({ brand: '', color: '', lot_number: '' })" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
                            </div>
                            <template x-for="(ink, ii) in inks" :key="'si'+ii">
                                <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-titane font-semibold" x-text="'Bijou ' + (ii + 1)"></span>
                                        <button type="button" @click="if(inks.length > 1) inks.splice(ii, 1)" x-show="inks.length > 1" class="text-rouge-alerte/60 hover:text-rouge-alerte">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                    <input type="text" :name="'inks[' + ii + '][brand]'" x-model="ink.brand" placeholder="Marque / Type (Implant Grade Titane, Acier 316L...)"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" :name="'inks[' + ii + '][color]'" x-model="ink.color" placeholder="Taille / Calibre (ex: 1.2mm, 8mm)"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        <input type="text" :name="'inks[' + ii + '][lot_number]'" x-model="ink.lot_number" placeholder="N° lot"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </template>
                            <?php else: ?>
                            
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Aiguilles & Cartouches</p>
                                <button type="button" @click="needles.push({ brand: '', lot_number: '', type: 'aiguille' })" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
                            </div>
                            <template x-for="(needle, ni) in needles" :key="'sn'+ni">
                                <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-titane font-semibold" x-text="'#' + (ni + 1)"></span>
                                            <select :name="'needles[' + ni + '][type]'" x-model="needle.type" class="px-2 py-1 bg-noir-profond border border-titane/30 rounded text-ivoire-text text-xs focus:border-beige-peau">
                                                <option value="aiguille">Aiguille</option>
                                                <option value="cartouche">Cartouche</option>
                                            </select>
                                        </div>
                                        <button type="button" @click="if(needles.length > 1) needles.splice(ni, 1)" x-show="needles.length > 1" class="text-rouge-alerte/60 hover:text-rouge-alerte">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" :name="'needles[' + ni + '][brand]'" x-model="needle.brand" placeholder="Marque (Cheyenne, FK Irons, Kwadron...)"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        <input type="text" :name="'needles[' + ni + '][lot_number]'" x-model="needle.lot_number" placeholder="N° de lot"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </template>

                            
                            <div class="flex items-center justify-between">
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Encres utilisées</p>
                                <button type="button" @click="inks.push({ brand: '', color: '', lot_number: '' })" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
                            </div>
                            <template x-for="(ink, ii) in inks" :key="'si'+ii">
                                <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-titane font-semibold" x-text="'Encre ' + (ii + 1)"></span>
                                        <button type="button" @click="if(inks.length > 1) inks.splice(ii, 1)" x-show="inks.length > 1" class="text-rouge-alerte/60 hover:text-rouge-alerte">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </div>
                                    <input type="text" :name="'inks[' + ii + '][brand]'" x-model="ink.brand" placeholder="Marque (World Famous, Eternal...)"
                                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    <div class="flex flex-col sm:flex-row gap-2">
                                        <input type="text" :name="'inks[' + ii + '][color]'" x-model="ink.color" placeholder="Couleur"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        <input type="text" :name="'inks[' + ii + '][lot_number]'" x-model="ink.lot_number" placeholder="N° lot"
                                            class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                </div>
                            </template>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            
                            <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Stérilisation</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label class="text-xs text-titane block mb-1">Date stérilisation</label>
                                    <input type="date" name="sterilization_date" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">N° lot stérilisation</label>
                                    <input type="text" name="sterilization_lot_number" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                                <div>
                                    <label class="text-xs text-titane block mb-1">N° cycle autoclave</label>
                                    <input type="text" name="autoclave_cycle_number" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                </div>
                            </div>

                            <textarea name="other_supplies" rows="2" placeholder="Autres fournitures (film, crème, gants, vaseline...)"
                                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"></textarea>
                            <textarea name="notes" rows="2" placeholder="Notes complémentaires..."
                                class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"></textarea>

                            <div>
                                <label class="text-xs text-titane block mb-1">📸 Photos numéros de lot (optionnel)</label>
                                <input type="file" name="lot_photos[]" multiple accept="image/*" onchange="previewFiles(this)"
                                    class="w-full text-sm text-ivoire-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau/20 file:text-beige-peau file:font-semibold file:text-xs">
                                <div class="upload-preview flex gap-2 mt-2 flex-wrap"></div>
                            </div>

                            <button type="submit" class="w-full px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                                ✅ Enregistrer la traçabilité
                            </button>
                        </form>
                    </div>
                </div>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($standaloneTraces->count() > 0): ?>
                    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📋 Traçabilités manuelles</h3>
                        <div class="space-y-3">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $standaloneTraces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trace): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="p-4 bg-noir-profond/50 rounded-lg">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <p class="font-semibold text-ivoire-text"><?php echo e($trace->tattoo_description ?? 'Tatouage'); ?></p>
                                            <p class="text-sm text-titane"><?php echo e($trace->body_zone ?? ''); ?> · <?php echo e($trace->session_date?->format('d/m/Y') ?? ''); ?></p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-vert-succes/20 text-vert-succes rounded text-xs">✅ Validée</span>
                                            <?php echo $__env->make('partials.pdf-download-button', [
                                                'url'   => route('pdf.traceability', $trace),
                                                'label' => 'PDF',
                                                'size'  => 'xs',
                                            ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace->procedure_notes): ?> <p class="text-sm text-ivoire-text/80 mt-2"><?php echo e($trace->procedure_notes); ?></p> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <?php $relevantAppointments = $appointments->filter(fn($apt) => $apt->bookingRequest); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $relevantAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $apt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $trace = $traceabilities[$apt->id] ?? null; ?>
                    <div class="bg-gris-fonde rounded-xl p-4" x-data="{
                        expanded: <?php echo e($loop->first && !$trace ? 'true' : 'false'); ?>,
                        needles: <?php echo e(json_encode($trace?->sterile_equipment['needles'] ?? [['brand' => '', 'lot_number' => '', 'type' => 'aiguille']])); ?>,
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

                                        · <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace && $trace->isComplete()): ?> <span class="text-vert-succes">Complète</span>
                                        <?php elseif($trace): ?> <span class="text-ambre-warning">En cours</span>
                                        <?php else: ?> <span class="text-rouge-alerte">À remplir</span> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </p>
                                    <p class="text-xs text-titane">RDV <?php echo e($apt->start_datetime?->translatedFormat('l d F Y à H:i') ?? 'Date à définir'); ?></p>
                                </div>
                            </div>
                            <svg class="w-5 h-5 text-titane transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </div>

                        <div x-show="expanded" x-cloak x-collapse class="mt-4 pt-4 border-t border-titane/20">
                            <form action="<?php echo e(route($tattooer->routePrefix() . '.traceability.store', $apt)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                                <?php echo csrf_field(); ?>

                                
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Aiguilles & Cartouches</p>
                                    <button type="button" @click="needles.push({ brand: '', lot_number: '', type: 'aiguille' })" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
                                </div>
                                <template x-for="(needle, ni) in needles" :key="'an'+ni">
                                    <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-xs text-titane font-semibold" x-text="'#' + (ni + 1)"></span>
                                                <select :name="'needles[' + ni + '][type]'" x-model="needle.type" class="px-2 py-1 bg-noir-profond border border-titane/30 rounded text-ivoire-text text-xs focus:border-beige-peau">
                                                    <option value="aiguille">Aiguille</option>
                                                    <option value="cartouche">Cartouche</option>
                                                </select>
                                            </div>
                                            <button type="button" @click="if(needles.length > 1) needles.splice(ni, 1)" x-show="needles.length > 1" class="text-rouge-alerte/60 hover:text-rouge-alerte">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="text" :name="'needles[' + ni + '][brand]'" x-model="needle.brand" placeholder="Marque (Cheyenne, FK Irons...)"
                                                class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                            <input type="text" :name="'needles[' + ni + '][lot_number]'" x-model="needle.lot_number" placeholder="N° de lot"
                                                class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        </div>
                                    </div>
                                </template>

                                
                                <div class="flex items-center justify-between">
                                    <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Encres utilisées</p>
                                    <button type="button" @click="inks.push({ brand: '', color: '', lot_number: '' })" class="text-xs text-beige-peau hover:text-beige-peau/80 font-semibold">+ Ajouter</button>
                                </div>
                                <template x-for="(ink, ii) in inks" :key="'ai'+ii">
                                    <div class="bg-noir-profond/30 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-titane font-semibold" x-text="'Encre ' + (ii + 1)"></span>
                                            <button type="button" @click="if(inks.length > 1) inks.splice(ii, 1)" x-show="inks.length > 1" class="text-rouge-alerte/60 hover:text-rouge-alerte">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </div>
                                        <input type="text" :name="'inks[' + ii + '][brand]'" x-model="ink.brand" placeholder="Marque (World Famous, Eternal...)"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        <div class="flex flex-col sm:flex-row gap-2">
                                            <input type="text" :name="'inks[' + ii + '][color]'" x-model="ink.color" placeholder="Couleur"
                                                class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                            <input type="text" :name="'inks[' + ii + '][lot_number]'" x-model="ink.lot_number" placeholder="N° lot"
                                                class="flex-1 px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                        </div>
                                    </div>
                                </template>

                                
                                <p class="text-xs font-bold text-ivoire-text/60 uppercase tracking-wider">Stérilisation</p>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="text-xs text-titane block mb-1">Date stérilisation</label>
                                        <input type="date" name="sterilization_date" value="<?php echo e($trace?->sterile_equipment['sterilization_date'] ?? ''); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">N° lot stérilisation</label>
                                        <input type="text" name="sterilization_lot_number" value="<?php echo e($trace?->sterile_equipment['sterilization_lot_number'] ?? ''); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="text-xs text-titane block mb-1">N° cycle autoclave</label>
                                        <input type="text" name="autoclave_cycle_number" value="<?php echo e($trace?->sterile_equipment['autoclave_cycle_number'] ?? ''); ?>"
                                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau">
                                    </div>
                                </div>

                                <textarea name="other_supplies" rows="2" placeholder="Autres fournitures..."
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"><?php echo e($trace?->procedure_notes ?? ''); ?></textarea>
                                <textarea name="notes" rows="2" placeholder="Notes complémentaires..."
                                    class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau resize-none"><?php echo e($trace?->equipment_notes ?? ''); ?></textarea>

                                <div>
                                    <label class="text-xs text-titane block mb-1">📸 Photos numéros de lot</label>
                                    <input type="file" name="lot_photos[]" multiple accept="image/*" onchange="previewFiles(this)"
                                        class="w-full text-sm text-ivoire-text file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau/20 file:text-beige-peau file:font-semibold file:text-xs">
                                    <div class="upload-preview flex gap-2 mt-2 flex-wrap"></div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($trace): ?>
                                        <?php $lotPhotos = $trace->getMedia('lot_photos'); ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lotPhotos->count() > 0): ?>
                                            <div class="flex gap-2 mt-2 flex-wrap">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $lotPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-noir-profond cursor-pointer border border-titane/20 hover:border-beige-peau transition-colors"
                                                        data-lb="<?php echo e($photo->getUrl()); ?>" onclick="window.openLightbox('<?php echo e($photo->getUrl()); ?>')">
                                                        <img src="<?php echo e($photo->getUrl()); ?>" alt="<?php echo e($photo->file_name); ?>" class="w-full h-full object-cover" onerror="this.style.display='none'">
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <button type="submit" class="w-full px-4 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                                    <?php echo e($trace ? '💾 Mettre à jour' : '✅ Enregistrer la traçabilité'); ?>

                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php if($standaloneTraces->count() === 0): ?>
                        <div class="bg-gris-fonde rounded-xl p-8 text-center"><p class="text-titane">Aucun rendez-vous nécessitant une traçabilité</p></div>
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

        
        <div x-show="activeTab === 'media'" x-cloak class="space-y-4">
            <?php if (isset($component)) { $__componentOriginal0482ca2f8c9f05860018c80a5d052c03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0482ca2f8c9f05860018c80a5d052c03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pro-gate','data' => ['feature' => 'la galerie médias client']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pro-gate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['feature' => 'la galerie médias client']); ?>

                <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📤 Upload photos</h3>
                    <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.photos.upload', $client)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="file" name="photos[]" multiple accept=".jpg,.jpeg,.png,.webp" onchange="previewFiles(this)"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-beige-peau file:text-noir-profond mb-1">
                        <p class="text-xs text-titane mb-2">JPG, PNG, WEBP | Max 5MB | Max 10 photos</p>
                        <div class="upload-preview flex gap-2 mt-1 mb-3 flex-wrap"></div>
                        <button type="submit" class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">📤 Uploader</button>
                    </form>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($clientPhotos->count() > 0): ?>
                    <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                        <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">📸 Photos client (<?php echo e($clientPhotos->count()); ?>)</h3>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $clientPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond relative group">
                                    <img src="<?php echo e($photo->getUrl()); ?>" alt="" class="w-full h-full object-cover cursor-pointer" loading="lazy"
                                        data-lb="<?php echo e($photo->getUrl()); ?>" onclick="window.openLightbox('<?php echo e($photo->getUrl()); ?>')" onerror="this.style.display='none'">
                                    <form action="<?php echo e(route($tattooer->routePrefix() . '.clients.photos.delete', [$client, $photo->id])); ?>" method="POST"
                                        class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" onclick="return confirm('Supprimer ?')" class="w-6 h-6 bg-rouge-alerte rounded-full flex items-center justify-center shadow-lg hover:bg-rouge-alerte/80">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="bg-gris-fonde rounded-xl p-4">
                    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">💬 Photos conversations (<?php echo e($chatMedia->count()); ?>)</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chatMedia->count() > 0): ?>
                        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chatMedia; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond relative group">
                                    <img src="<?php echo e($media->getUrl()); ?>" alt="" class="w-full h-full object-cover cursor-pointer" loading="lazy"
                                        data-lb="<?php echo e($media->getUrl()); ?>" onclick="window.openLightbox('<?php echo e($media->getUrl()); ?>')" onerror="this.style.display='none'">
                                    <form action="<?php echo e(route($tattooer->routePrefix() . '.client.media.delete', [$client, $media->id])); ?>" method="POST"
                                        class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" onclick="return confirm('Supprimer ?')" class="w-6 h-6 bg-rouge-alerte rounded-full flex items-center justify-center shadow-lg hover:bg-rouge-alerte/80">
                                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
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
                        <h4 class="text-sm font-bold text-ivoire-text mb-3">📸 <?php echo e($br->tattoo_style ?? 'Tattoo'); ?> — <?php echo e($br->body_zone ?? ''); ?> <span class="text-xs text-titane font-normal ml-1">(<?php echo e($br->created_at->format('d/m/Y')); ?>)</span></h4>
                        <?php $tattooPhotos = $br->getMedia('tattoo_results'); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tattooPhotos->count() > 0): ?>
                            <div class="grid grid-cols-3 sm:grid-cols-4 gap-2 mb-3">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $tattooPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="aspect-square rounded-lg overflow-hidden bg-noir-profond relative group">
                                        <img src="<?php echo e($photo->getUrl()); ?>" alt="" class="w-full h-full object-cover cursor-pointer" data-lb="<?php echo e($photo->getUrl()); ?>" onclick="window.openLightbox('<?php echo e($photo->getUrl()); ?>')">
                                        <form action="<?php echo e(route($tattooer->routePrefix() . '.client.media.delete', [$client, $photo->id])); ?>" method="POST" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" onclick="return confirm('Supprimer ?')" class="w-6 h-6 bg-rouge-alerte rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <form action="<?php echo e(route($tattooer->routePrefix() . '.client.photos.upload', [$client, $br])); ?>" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                            <?php echo csrf_field(); ?>
                            <input type="file" name="photos[]" multiple accept="image/jpeg,image/png,image/webp" onchange="previewFiles(this)"
                                class="flex-1 text-sm text-ivoire-text file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-beige-peau/20 file:text-beige-peau file:font-semibold file:text-xs">
                            <button type="submit" class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold text-xs hover:bg-beige-peau/90 transition-colors flex-shrink-0">Upload</button>
                        </form>
                        <div class="upload-preview flex gap-2 mt-2 flex-wrap"></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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

        
        <div x-show="activeTab === 'notes'" x-cloak>
            <?php if (isset($component)) { $__componentOriginal0482ca2f8c9f05860018c80a5d052c03 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0482ca2f8c9f05860018c80a5d052c03 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pro-gate','data' => ['feature' => 'les notes privées client']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pro-gate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['feature' => 'les notes privées client']); ?>
                <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                    <h3 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">Notes privées</h3>
                    <p class="text-xs text-titane mb-3">Visibles uniquement par vous.</p>
                    <form action="<?php echo e(route($tattooer->routePrefix() . '.client.update-notes', $client)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <textarea name="notes" rows="8" placeholder="Allergies, préférences, comportement au salon..."
                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane text-sm focus:border-beige-peau focus:ring-1 focus:ring-beige-peau resize-y"><?php echo e($client->notes ?? ''); ?></textarea>
                        <button type="submit" class="mt-3 w-full sm:w-auto px-6 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold text-sm hover:bg-beige-peau/90 transition-colors">Enregistrer</button>
                    </form>
                </div>
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

    </div>

    
    <div id="lightbox" class="hidden fixed inset-0 bg-black/95 z-[60] flex items-center justify-center" onclick="if(event.target===this)window.closeLightbox()">
        <button onclick="window.closeLightbox()" class="absolute top-4 right-4 p-2 text-white/70 hover:text-white z-10">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
        <button onclick="window.lightboxNav(-1)" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 p-3 text-white/70 hover:text-white z-10 bg-black/40 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
        </button>
        <button onclick="window.lightboxNav(1)" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 p-3 text-white/70 hover:text-white z-10 bg-black/40 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
        </button>
        <img id="lightbox-img" src="" alt="" class="max-w-[90vw] max-h-[85vh] object-contain rounded-lg" onclick="event.stopPropagation()">
        <div id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/60 text-sm bg-black/50 px-3 py-1 rounded-full"></div>
    </div>

    
    <script>
        // LIGHTBOX
        (function(){var images=[],index=0;
        window.openLightbox=function(url){images=[];document.querySelectorAll('[data-lb]').forEach(function(el){images.push(el.getAttribute('data-lb'))});index=images.indexOf(url);if(index===-1){images=[url];index=0}showLb();document.getElementById('lightbox').classList.remove('hidden');document.body.style.overflow='hidden'};
        window.closeLightbox=function(){document.getElementById('lightbox').classList.add('hidden');document.body.style.overflow=''};
        window.lightboxNav=function(dir){index=(index+dir+images.length)%images.length;showLb()};
        function showLb(){document.getElementById('lightbox-img').src=images[index];document.getElementById('lightbox-counter').textContent=(index+1)+' / '+images.length}
        document.addEventListener('keydown',function(e){if(document.getElementById('lightbox').classList.contains('hidden'))return;if(e.key==='Escape')window.closeLightbox();if(e.key==='ArrowLeft')window.lightboxNav(-1);if(e.key==='ArrowRight')window.lightboxNav(1)})})();

        // PREVIEW UPLOAD
        function previewFiles(input){var preview=input.closest('div').querySelector('.upload-preview')||input.parentElement.querySelector('.upload-preview');if(!preview)return;preview.innerHTML='';Array.from(input.files).forEach(function(file){var reader=new FileReader();reader.onload=function(e){var div=document.createElement('div');div.className='w-14 h-14 rounded-lg overflow-hidden border-2 border-beige-peau/40';div.innerHTML='<img src="'+e.target.result+'" class="w-full h-full object-cover">';preview.appendChild(div)};reader.readAsDataURL(file)})}

        // SIGNATURE PAD
        (function(){var canvas,ctx,drawing=false,hasSignature=false;
        function initPad(){canvas=document.getElementById('consent-signature-pad');if(!canvas||canvas.dataset.init)return;canvas.dataset.init='1';ctx=canvas.getContext('2d');var rect=canvas.getBoundingClientRect(),dpr=window.devicePixelRatio||1;canvas.width=rect.width*dpr;canvas.height=rect.height*dpr;ctx.scale(dpr,dpr);ctx.strokeStyle='#1a1a1a';ctx.lineWidth=2;ctx.lineCap='round';ctx.lineJoin='round';
        function pos(e){var r=canvas.getBoundingClientRect();return{x:e.clientX-r.left,y:e.clientY-r.top}}
        canvas.addEventListener('mousedown',function(e){drawing=true;hasSignature=true;var p=pos(e);ctx.beginPath();ctx.moveTo(p.x,p.y)});
        canvas.addEventListener('mousemove',function(e){if(!drawing)return;var p=pos(e);ctx.lineTo(p.x,p.y);ctx.stroke()});
        canvas.addEventListener('mouseup',function(){drawing=false});
        canvas.addEventListener('mouseleave',function(){drawing=false});
        canvas.addEventListener('touchstart',function(e){e.preventDefault();drawing=true;hasSignature=true;var p=pos(e.touches[0]);ctx.beginPath();ctx.moveTo(p.x,p.y)},{passive:false});
        canvas.addEventListener('touchmove',function(e){e.preventDefault();if(!drawing)return;var p=pos(e.touches[0]);ctx.lineTo(p.x,p.y);ctx.stroke()},{passive:false});
        canvas.addEventListener('touchend',function(){drawing=false})}
        window.clearSignaturePad=function(){if(!canvas||!ctx)return;ctx.clearRect(0,0,canvas.width,canvas.height);hasSignature=false};
        window.submitConsentForm=function(){if(!hasSignature){alert('Veuillez signer le consentement.');return false}document.getElementById('consent-signature-data').value=canvas.toDataURL('image/png');return true};
        // Re-init quand Alpine montre le canvas
        var obs=new MutationObserver(function(){var el=document.getElementById('consent-signature-pad');if(el&&!el.dataset.init)setTimeout(initPad,150)});
        document.addEventListener('DOMContentLoaded',function(){obs.observe(document.body,{childList:true,subtree:true,attributes:true});initPad()})})();

        // TAB HASH
        document.addEventListener('DOMContentLoaded',function(){var hash=window.location.hash.replace('#','');if(hash){var check=setInterval(function(){var el=document.querySelector('[x-data]');if(el&&el._x_dataStack){clearInterval(check);el._x_dataStack[0].activeTab=hash}},50);setTimeout(function(){clearInterval(check)},1000)}});
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/client-show.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-ivoire-text">Artistes</h1>
                <p class="text-sm text-titane mt-1">
                    <?php echo e($activeArtists->count()); ?> artiste<?php echo e($activeArtists->count() > 1 ? 's' : ''); ?>

                    actif<?php echo e($activeArtists->count() > 1 ? 's' : ''); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($paidArtistCount > 0): ?>
                        <span class="text-beige-peau">(dont <?php echo e($paidArtistCount); ?>

                            supplémentaire<?php echo e($paidArtistCount > 1 ? 's' : ''); ?> à 39,99€/mois)</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canAddArtist): ?>
                <a href="<?php echo e(route('studio.artists.create')); ?>"
                    class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                    + Ajouter
                </a>
            <?php elseif($needsSubscriptionForNewArtist): ?>
                <a href="<?php echo e(route('studio.subscribe')); ?>"
                    class="px-4 py-2.5 bg-beige-peau text-noir-profond rounded-xl font-semibold text-sm hover:bg-beige-peau/90 transition-colors active:scale-95">
                    🔓 Souscrire pour ajouter
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $activeArtists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex items-center gap-3 p-4">
                    <img src="<?php echo e($sa->user?->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>"
                        alt="<?php echo e($sa->user?->name); ?>" class="w-12 h-12 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-md font-semibold text-beige-peau truncate"><?php echo e($sa->user?->name); ?></p>
                        <p class="text-xs text-titane">
                            <?php echo e($sa->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur'); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sa->joined_at && $sa->joined_at->isToday()): ?>
                                • Rejoint aujourd'hui
                            <?php else: ?>
                                • Rejoint <?php echo e($sa->joined_at?->diffForHumans() ?? '—'); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        
                        <form action="<?php echo e(route('studio.artists.toggle', $sa)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <button type="submit"
                                class="text-xs px-3 text-vert-succes bg-vert-succes/20 border border-vert-succes rounded-full py-1.5 font-semibold transition-colors <?php echo e($sa->is_active ? 'bg-vert-validation/20 text-vert-validation' : 'bg-rouge-alerte/20 text-rouge-alerte'); ?>">
                                <?php echo e($sa->is_active ? 'Actif' : 'Inactif'); ?>

                            </button>
                        </form>
                        
                        <form action="<?php echo e(route('studio.artists.remove', $sa)); ?>" method="POST"
                            onsubmit="return confirm('Retirer cet artiste du studio ?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="text-rouge-alerte/60 hover:text-rouge-alerte p-1 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-sm text-titane text-center py-8">Aucun artiste actif</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($pendingInvitations->count() > 0): ?>
            <div class="bg-gris-fonde rounded-xl p-4 md:p-6">
                <h2 class="text-sm font-bold text-ivoire-text/60 uppercase tracking-wider mb-3">⏳ Invitations en attente
                </h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pendingInvitations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-3 py-2">
                        <div class="w-10 h-10 rounded-full bg-titane/20 flex items-center justify-center text-titane">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm text-ivoire-text"><?php echo e($inv->invitation_email); ?></p>
                            <p class="text-xs text-titane">
                                <?php echo e($inv->artisan_type === 'piercer' ? '💎 Pierceur' : '🎨 Tatoueur'); ?>

                                • Invité <?php echo e($inv->invited_at?->diffForHumans() ?? '—'); ?>

                            </p>
                        </div>
                        <form action="<?php echo e(route('studio.artists.remove', $inv)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="text-xs text-rouge-alerte/60 hover:text-rouge-alerte transition-colors">Annuler</button>
                        </form>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <div class="bg-gris-fonde/50 rounded-xl p-4 border border-titane/10">
            <p class="text-xs text-titane">
                💡 Votre abonnement Studio inclut <strong class="text-ivoire-text">1 artiste</strong>.
                Chaque artiste supplémentaire coûte <strong class="text-beige-peau">39,99€/mois</strong>.
                Facturation actuelle : <strong
                    class="text-ivoire-text"><?php echo e(number_format($monthlyPrice, 2)); ?>€/mois</strong>
            </p>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/studio/artists.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Messages</h1>
            <p class="text-sm text-titane mt-1">Conversations des artistes de votre studio</p>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($recentConversations->count() > 0): ?>
            <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $recentConversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $lastMessage = $booking->messages->first();
                        $status = is_object($booking->status) ? $booking->status->value : $booking->status;
                    ?>
                    <a href="<?php echo e(route('studio.demandes.show', $booking)); ?>#messages"
                        class="flex items-center gap-4 p-4 hover:bg-noir-profond/40 transition-colors cursor-default">

                        <!-- Avatar de l'artiste -->
                        <div class="w-10 h-10 rounded-full overflow-hidden shrink-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->bookable && method_exists($booking->bookable, 'hasMedia') && $booking->bookable->hasMedia('avatar')): ?>
                                <img src="<?php echo e($booking->bookable->getFirstMediaUrl('avatar')); ?>"
                                    alt="<?php echo e($booking->bookable->user?->name); ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-beige-peau/20 flex items-center justify-center text-lg">
                                    <?php echo e($booking->bookable instanceof \App\Models\Piercer ? '💎' : '🎨'); ?>

                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Contenu -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($booking->client?->pseudo || $booking->client?->pseudo): ?>
                                    <p class="text-sm font-semibold text-ivoire-text truncate">
                                        <?php echo e($booking->client?->pseudo); ?>

                                    </p>
                                <?php else: ?>
                                    <p class="text-sm font-semibold text-ivoire-text truncate">
                                        <?php echo e($booking->client?->first_name); ?> <?php echo e($booking->client?->last_name); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <span class="text-xs text-titane">→
                                    <?php echo e($booking->bookable?->user?->name ?? 'Artiste'); ?></span>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMessage): ?>
                                <p class="text-xs text-titane mt-0.5 truncate">
                                    <?php echo e(Str::limit($lastMessage->content ?? '', 80)); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <!-- Date + statut -->
                        <div class="text-right shrink-0">
                            <p class="text-xs text-titane"><?php echo e($booking->updated_at?->diffForHumans()); ?></p>
                            <?php
                                $status = is_object($booking->status) ? $booking->status->value : $booking->status;
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/20 text-yellow-400',
                                    'accepted' => 'bg-blue-500/20 text-blue-400',
                                    'deposit_requested' => 'bg-purple-500/20 text-purple-400',
                                    'deposit_paid' => 'bg-vert-succes/20 text-vert-succes',
                                    'date_confirmed' => 'bg-vert-succes/20 text-vert-succes',
                                    'completed' => 'bg-vert-succes/20 text-vert-succes',
                                    'balance_paid' => 'bg-vert-succes/20 text-vert-succes',
                                    'balance_paid_offline' => 'bg-vert-succes/20 text-vert-succes',
                                    'fully_completed' => 'bg-vert-succes/20 text-vert-succes',
                                    'cancelled' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                    'rejected' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                    'expired' => 'bg-titane/20 text-titane',
                                    'no_show' => 'bg-rouge-alerte/20 text-rouge-alerte',
                                ];
                                $colorClass = $statusColors[$status] ?? 'bg-titane/20 text-titane';
                            ?>
                            <span
                                class="text-xs px-1.5 py-0.5 rounded-full font-semibold mt-1 inline-block <?php echo e($colorClass); ?>">
                                <?php echo e(str_replace('_', ' ', $status)); ?>

                            </span>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <p class="text-xs text-titane/60 text-center">
                Les messages sont gérés individuellement par chaque artiste depuis leur espace.
            </p>
        <?php else: ?>
            <div class="bg-gris-fonde rounded-xl p-8 text-center">
                <svg class="w-12 h-12 mx-auto mb-4 text-titane/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h2 class="text-base font-semibold text-ivoire-text mb-2">Aucun message pour l'instant</h2>
                <p class="text-sm text-titane">
                    Les échanges avec les clients apparaîtront ici une fois que vos artistes auront des demandes en cours.
                </p>
                <a href="<?php echo e(route('studio.requests')); ?>" class="inline-block mt-4 text-sm text-beige-peau hover:underline">
                    Voir les demandes →
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.livewire-studio-simple', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\studio\messages.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        <!-- Header -->
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-ivoire-text mb-2">
                Messages
            </h1>
            <p class="text-ivoire-text/70">
                Conversations avec vos clients
            </p>
        </div>

        <!-- Liste des conversations -->
        <div class="bg-gris-fonde rounded-xl p-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversations->count() > 0): ?>
                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route($tattooer->routePrefix() . '.message.show', $conversation)); ?>"
                            class="block p-4 bg-noir-profond rounded-lg hover:bg-noir-profond/80 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <!-- Avatar -->
                                        <div
                                            class="w-10 h-10 rounded-full overflow-hidden bg-titane/30 flex items-center justify-center">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation->client->user->getFirstMedia('avatar')): ?>
                                                <img src="<?php echo e($conversation->client->user->getFirstMedia('avatar')->getUrl()); ?>"
                                                    alt="<?php echo e($conversation->client->user->name); ?>"
                                                    class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <svg class="w-5 h-5 text-ivoire-text/40" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <div>
                                            <!-- PSEUDO (pas nom/prénom) -->
                                            <h3 class="font-semibold text-ivoire-text">
                                                <?php echo e($conversation->client->user->pseudo ?? $conversation->client->user->first_name . ' ' . $conversation->client->user->last_name); ?>

                                            </h3>
                                            <p class="text-sm text-ivoire-text/60">
                                                <?php echo e($conversation->description ? Str::limit($conversation->description, 50) : 'Nouvelle demande de projet'); ?>

                                            </p>
                                        </div>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation->messages->count() > 0): ?>
                                        <?php
                                            $lastMessage = $conversation->messages->first();
                                        ?>
                                        <div class="mt-2">
                                            <p class="text-sm text-ivoire-text/70">
                                                <?php echo e($lastMessage->content ? Str::limit($lastMessage->content, 80) : 'Message sans texte'); ?>

                                            </p>
                                            <p class="text-xs text-ivoire-text/50 mt-1">
                                                <?php echo e($lastMessage->created_at->format('d/m/Y à H:i')); ?>

                                            </p>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <!-- Badge statut acompte -->
                                    <?php
                                        $br = $conversation->bookingRequest;
                                    ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br): ?>
                                        <div class="flex flex-col gap-1">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($br->deposit_paid_at): ?>
                                                <span
                                                    class="px-2.5 py-0.5 bg-vert-succes/20 text-vert-succes rounded-full text-xs font-bold">
                                                    💰 Acompte payé
                                                </span>
                                            <?php elseif(in_array($br->status, ['accepted', 'awaiting_deposit']) && $br->deposit_amount): ?>
                                                <span
                                                    class="px-2.5 py-0.5 bg-jaune-alerte/20 text-jaune-alerte rounded-full text-xs font-bold">
                                                    ⏳ Acompte en attente
                                                </span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <!-- Badge statut demande -->
                                            <span
                                                class="px-2.5 py-0.5 bg-titane/30 text-ivoire-text/80 rounded-full text-xs font-semibold">
                                                <?php echo e(match ($br->status->value) {
                                                    'pending' => 'En attente',
                                                    'accepted' => 'Acceptée',
                                                    'deposit_requested' => 'Acompte attendu',
                                                    'deposit_paid' => 'Acompte payé',
                                                    'design_sent' => 'Dessin envoyé',
                                                    'date_confirmed' => 'Confirmé',
                                                    'completed' => 'Terminé',
                                                    'cancelled' => 'Annulé',
                                                    default => ucfirst($br->status->value),
                                                }); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversation->unread_count > 0): ?>
                                        <span
                                            class="bg-rouge-alerte text-noir-profond px-2 py-1 rounded-full text-xs font-bold">
                                            <?php echo e($conversation->unread_count); ?>

                                        </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-noir-profond rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">
                        Aucune conversation
                    </h3>
                    <p class="text-ivoire-text/60">
                        Vous n'avez pas encore de messages avec vos clients.
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

    </div>

    <?php $__env->startPush('scripts'); ?>
        <script>
            // Auto-rafraîchissement toutes les 30 secondes pour les nouveaux messages
            setInterval(() => {
                // Optionnel : recharger la page pour voir les nouveaux messages
                // window.location.reload();
            }, 30000);
        </script>
    <?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/tattooer/messages.blade.php ENDPATH**/ ?>
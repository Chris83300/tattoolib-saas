<?php $__env->startSection('title', 'Demandes de ' . ($client->user->pseudo ?? $client->user->first_name . ' ' .
    $client->user->last_name)); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('tattooer.clients')); ?>" class="text-ivoire-text/70 hover:text-beige-peau transition-colors">
                    ← Retour aux clients
                </a>
                <div class="w-px h-6 bg-titane/30"></div>
                <div>
                    <h1 class="text-2xl font-bold text-ivoire-text">
                        Demandes de
                        <?php echo e($client->user->pseudo ?? $client->user->first_name . ' ' . $client->user->last_name); ?>

                    </h1>
                    <p class="text-ivoire-text/70">
                        <?php echo e($requests->count()); ?> demande<?php echo e($requests->count() > 1 ? 's' : ''); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="bg-gris-fonde rounded-xl p-6 border border-titane/30 hover:border-beige-peau/40 transition-all">
                    <div class="flex items-start justify-between">
                        <!-- Infos principales -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-lg font-semibold text-ivoire-text">
                                    <?php echo e($request->tattoo_size); ?> - <?php echo e($request->body_zone); ?>

                                </h3>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-semibold
                                <?php switch($request->status):
                                    case ('pending'): ?>
                                        bg-jaune-alerte/20 text-jaune-alerte
                                    <?php break; ?>
                                    <?php case ('accepted'): ?>
                                        bg-beige-peau/20 text-beige-peau
                                    <?php break; ?>
                                    <?php case ('awaiting_deposit'): ?>
                                        bg-vert-succes/20 text-vert-succes
                                    <?php break; ?>
                                    <?php case ('deposit_paid'): ?>
                                        bg-vert-succes/20 text-vert-succes
                                    <?php break; ?>
                                    <?php case ('confirmed'): ?>
                                        bg-vert-succes/20 text-vert-succes
                                    <?php break; ?>
                                    <?php case ('completed'): ?>
                                        bg-titane/30 text-ivoire-text/80
                                    <?php break; ?>
                                    <?php case ('cancelled'): ?>
                                        bg-rouge-alerte/20 text-rouge-alerte
                                    <?php break; ?>
                                    <?php default: ?>
                                        bg-titane/30 text-ivoire-text/80
                                <?php endswitch; ?>">
                                    <?php echo e(match ($request->status->value) {
                                        'pending' => 'En attente',
                                        'accepted' => 'Acceptée',
                                        'deposit_requested' => 'Acompte attendu',
                                        'deposit_paid' => 'Acompte payé',
                                        'date_confirmed' => 'Confirmée',
                                        'completed' => 'Terminée',
                                        'cancelled' => 'Annulée',
                                        default => ucfirst($request->status->value),
                                    }); ?>

                                </span>
                            </div>

                            <!-- Description -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->description): ?>
                                <p class="text-ivoire-text/80 mb-3"><?php echo e(Str::limit($request->description, 150)); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Dates et prix -->
                            <div class="flex flex-wrap gap-4 text-sm text-ivoire-text/60">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->created_at): ?>
                                    <span>Créée le <?php echo e($request->created_at->format('d/m/Y')); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->estimated_total_price): ?>
                                    <span>Estimation : <?php echo e(number_format($request->estimated_total_price, 2, ',', ' ')); ?>

                                        €</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->deposit_amount): ?>
                                    <span>Acompte : <?php echo e(number_format($request->deposit_amount, 2, ',', ' ')); ?> €</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2 ml-4">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->conversation): ?>
                                <a href="<?php echo e(route('tattooer.message.show', $request)); ?>"
                                    class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg font-semibold hover:bg-beige-peau/90 transition-colors">
                                    💬 Chat
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <a href="<?php echo e(route('tattooer.request-show', $request)); ?>"
                                class="px-4 py-2 bg-noir-profond text-ivoire-text border border-titane/30 rounded-lg font-semibold hover:bg-noir-profond/80 transition-colors">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="bg-gris-fonde rounded-xl p-12 text-center">
                    <div class="w-16 h-16 bg-titane/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-ivoire-text/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-ivoire-text mb-2">Aucune demande</h3>
                    <p class="text-ivoire-text/60">Ce client n'a pas encore de demandes avec vous.</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tattooer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\tattooer\client-requests.blade.php ENDPATH**/ ?>
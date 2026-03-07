<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-ivoire-text">Demandes</h1>
        <p class="text-sm text-titane mt-1">Toutes les demandes adressées aux artistes de votre studio</p>
    </div>

    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $requests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="p-4 flex items-center gap-3">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <p class="text-sm font-semibold text-ivoire-text truncate">
                            <?php echo e($request->client?->first_name); ?> <?php echo e($request->client?->last_name); ?>

                        </p>
                        <?php
                            $status = is_object($request->status) ? $request->status->value : $request->status;
                        ?>
                        <span class="text-xs px-2 py-0.5 rounded-full font-semibold
                            <?php echo e(in_array($status, ['pending']) ? 'bg-yellow-500/20 text-yellow-400' : ''); ?>

                            <?php echo e(in_array($status, ['accepted', 'deposit_paid', 'date_confirmed']) ? 'bg-vert-succes/10 text-vert-succes' : ''); ?>

                            <?php echo e(in_array($status, ['completed', 'fully_completed', 'balance_paid', 'balance_paid_offline']) ? 'bg-vert-succes/20 text-vert-succes' : ''); ?>

                            <?php echo e(in_array($status, ['cancelled', 'rejected', 'no_show']) ? 'bg-rouge-alerte/10 text-rouge-alerte' : ''); ?>

                            <?php echo e(!in_array($status, ['pending','accepted','deposit_paid','date_confirmed','completed','fully_completed','balance_paid','balance_paid_offline','cancelled','rejected','no_show']) ? 'bg-titane/20 text-titane' : ''); ?>">
                            <?php echo e(str_replace('_', ' ', ucfirst($status))); ?>

                        </span>
                    </div>
                    <p class="text-xs text-titane mt-0.5">
                        → <?php echo e($request->bookable?->user?->name ?? 'Artiste'); ?>

                        <span class="text-beige-peau">(<?php echo e($request->bookable instanceof \App\Models\Piercer ? 'Pierçeur' : 'Tatoueur'); ?>)</span>
                        • <?php echo e($request->created_at?->diffForHumans()); ?>

                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($request->deposit_amount): ?>
                        <span class="text-sm font-semibold text-beige-peau">
                            <?php echo e(number_format($request->deposit_amount / 100, 2)); ?>€
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <a href="<?php echo e(route('studio.demandes.show', $request)); ?>"
                        class="text-xs text-beige-peau hover:underline">
                        Détails →
                    </a>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p class="text-sm text-titane text-center py-8">Aucune demande</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php echo e($requests->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\requests.blade.php ENDPATH**/ ?>
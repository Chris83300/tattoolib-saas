
<div class="px-4 py-3 border-t border-gray-700/30">
    <div class="flex items-center justify-between">
        <span class="text-xs text-gray-500">
            Ink&amp;Pik Admin
        </span>
        <span class="text-xs text-gray-600">
            v1.0.0
        </span>
    </div>
    <?php $user = auth()->user(); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user): ?>
    <p class="text-xs text-gray-600 truncate mt-0.5">
        <?php echo e($user->name ?? $user->email); ?>

    </p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/filament/hooks/sidebar-footer.blade.php ENDPATH**/ ?>
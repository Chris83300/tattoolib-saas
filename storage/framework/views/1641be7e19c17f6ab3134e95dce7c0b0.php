
<div class="space-y-2 text-sm">
    <div class="flex justify-between">
        <span class="text-ivoire-text/70">Nom:</span>
        <span class="text-ivoire-text"><?php echo e($bookingRequest->bookable->user->name); ?></span>
    </div>
    <div class="flex justify-between">
        <span class="text-ivoire-text/70">Type:</span>
        <span class="text-ivoire-text">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->bookable_type === 'App\Models\Tattooer'): ?>
                Tatoueur indépendant
            <?php elseif($bookingRequest->bookable_type === 'App\Models\StudioArtist'): ?>
                Artiste de studio
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </span>
    </div>
    <div class="flex justify-between">
        <span class="text-ivoire-text/70">Email:</span>
        <span class="text-ivoire-text"><?php echo e($bookingRequest->bookable->user->email); ?></span>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\client\_artist-info.blade.php ENDPATH**/ ?>
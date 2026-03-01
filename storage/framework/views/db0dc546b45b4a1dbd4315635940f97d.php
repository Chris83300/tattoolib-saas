<div class="bg-gris-fonde rounded-xl border border-beige-peau/20 shadow-lg p-6">
    <h2 class="text-xl font-bold text-ivoire-text mb-6">Historique complet</h2>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->client->bookingRequests->where('status', 'completed')->count() > 0): ?>
        <div class="space-y-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->client->bookingRequests->where('status', 'completed'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="p-6 bg-noir-profond rounded-xl border border-beige-peau/10">
                <div class="flex items-start gap-4">
                    <img src="<?php echo e($booking->bookable->user->getFirstMediaUrl('avatar') ?: asset('images/default-avatar.png')); ?>" 
                         alt="Artiste" 
                         class="w-16 h-16 rounded-full">
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-bold text-ivoire-text"><?php echo e($booking->bookable->user->name); ?></h3>
                            <span class="text-sm text-vert-succes font-semibold">✅ Terminé</span>
                        </div>
                        <p class="text-ivoire-text/80 mb-2"><?php echo e($booking->tattoo_description); ?></p>
                        <div class="flex items-center gap-4 text-sm text-ivoire-text/60">
                            <span>📅 <?php echo e($booking->appointment_datetime?->format('d/m/Y') ?? 'Date non définie'); ?></span>
                            <span>💰 <?php echo e($booking->estimated_price); ?>€</span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-ivoire-text/20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-ivoire-text/60">Aucun projet terminé</p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/client/profile-tabs/historique.blade.php ENDPATH**/ ?>
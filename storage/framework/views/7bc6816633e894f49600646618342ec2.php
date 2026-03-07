<?php $__env->startSection('content'); ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Fiches clients</h1>
            <p class="text-sm text-titane mt-1">Clients ayant adressé une demande aux artistes de votre studio</p>
        </div>
        <span class="text-sm text-titane"><?php echo e($clients->total()); ?> client<?php echo e($clients->total() > 1 ? 's' : ''); ?></span>
    </div>

    <!-- Recherche -->
    <form method="GET" action="<?php echo e(route('studio.clients.index')); ?>" class="flex gap-2">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
            placeholder="Rechercher par nom ou email..."
            class="flex-1 bg-gris-fonde border border-titane/30 rounded-lg px-4 py-2 text-sm text-ivoire-text placeholder-titane focus:outline-none focus:border-beige-peau/50">
        <button type="submit"
            class="px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-semibold hover:bg-beige-peau/90 transition-colors">
            Rechercher
        </button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
            <a href="<?php echo e(route('studio.clients.index')); ?>"
                class="px-4 py-2 bg-gris-fonde border border-titane/30 rounded-lg text-sm text-titane hover:text-ivoire-text transition-colors">
                Réinitialiser
            </a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </form>

    <!-- Liste clients -->
    <div class="bg-gris-fonde rounded-xl divide-y divide-titane/10">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <a href="<?php echo e(route('studio.clients.show', $client)); ?>"
                class="flex items-center gap-4 p-4 hover:bg-noir-profond/40 transition-colors">

                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full bg-beige-peau/20 flex items-center justify-center shrink-0 overflow-hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->getFirstMediaUrl('avatar')): ?>
                        <img src="<?php echo e($client->getFirstMediaUrl('avatar')); ?>" alt="<?php echo e($client->display_name); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <span class="text-beige-peau font-bold text-sm"><?php echo e(mb_strtoupper(mb_substr($client->first_name ?? $client->pseudo ?? 'C', 0, 1))); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Infos -->
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-ivoire-text truncate">
                        <?php echo e($client->first_name); ?> <?php echo e($client->last_name); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->pseudo && $client->pseudo !== trim($client->first_name . ' ' . $client->last_name)): ?>
                            <span class="text-titane font-normal">(<?php echo e($client->pseudo); ?>)</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                    <p class="text-xs text-titane truncate mt-0.5">
                        <?php echo e($client->email); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->phone): ?> • <?php echo e($client->phone); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>

                <!-- Stats demandes -->
                <div class="text-right shrink-0">
                    <p class="text-sm font-semibold text-beige-peau mb-1">
                        <?php echo e($client->booking_requests_count ?? $client->bookingRequests->count()); ?>

                        demande<?php echo e(($client->booking_requests_count ?? $client->bookingRequests->count()) > 1 ? 's' : ''); ?>

                    </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->is_blacklisted): ?>
                        <span class="text-xs bg-rouge-alerte/10 p-1 rounded-full text-rouge-alerte font-semibold mt-2 ">Liste noire</span>
                    <?php elseif($client->no_show_count > 0): ?>
                        <span class="text-xs bg-rouge-alerte/10 p-1 rounded-full text-rouge-alerte mt-2"><?php echo e($client->no_show_count); ?> no-show</span>
                    <?php else: ?>
                        <span class="text-xs text-titane mt-2"><?php echo e($client->created_at?->diffForHumans()); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Chevron -->
                <svg class="w-4 h-4 text-titane shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="text-center py-12">
                <p class="text-titane text-sm">Aucun client trouvé</p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
                    <a href="<?php echo e(route('studio.clients.index')); ?>" class="text-beige-peau text-sm hover:underline mt-2 inline-block">Effacer la recherche</a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php echo e($clients->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\clients.blade.php ENDPATH**/ ?>
<?php $__env->startSection('title', 'Conversations artistes'); ?>

<?php $__env->startSection('content'); ?>
<div class="space-y-6">

    
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-ivoire-text">Conversations artistes</h1>
            <p class="text-sm text-titane mt-1"><?php echo e($conversations->count()); ?> conversation(s) — lecture seule</p>
        </div>
    </div>

    
    <div class="bg-gris-fonde rounded-xl p-4 border border-titane/20 flex items-start gap-3">
        <svg class="w-5 h-5 text-beige-peau flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-sm text-ivoire-text/80">
            En tant que gérant de studio, vous consultez les conversations de vos artistes en <strong class="text-beige-peau">lecture seule</strong>.
            Vous ne pouvez pas répondre directement dans ces conversations.
        </p>
    </div>

    
    <?php
        $artistUserIds = $studio->artists()->with('user')->get()->pluck('user', 'user_id')->filter();
    ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($artistUserIds->count() > 1): ?>
    <div x-data="{ filter: 'all' }" class="space-y-4">
        <div class="flex flex-wrap gap-2">
            <button @click="filter = 'all'"
                :class="filter === 'all' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane hover:text-ivoire-text'"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border border-titane/20">
                Tous
            </button>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $artistUserIds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userId => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button @click="filter = '<?php echo e($userId); ?>'"
                :class="filter === '<?php echo e($userId); ?>' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane hover:text-ivoire-text'"
                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors border border-titane/20">
                <?php echo e($user?->name ?? 'Artiste'); ?>

            </button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversations->count() > 0): ?>
        <div class="bg-gris-fonde rounded-xl border border-titane/10 divide-y divide-titane/5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $artistParticipant = $conv->participants->first(fn($u) => $artistUserIds->has($u->id));
                $clientParticipant = $conv->participants->first(fn($u) => !$artistUserIds->has($u->id));
                $lastMsg = $conv->messages->first();
                $convType = $conv->type === 'support' ? 'Support' : ($conv->type === 'booking' ? 'Réservation' : 'Privé');
                $typeColor = $conv->type === 'support' ? 'rgba(168,85,247,0.15);color:#c084fc' : ($conv->type === 'booking' ? 'rgba(59,130,246,0.15);color:#60a5fa' : 'rgba(233,198,160,0.1);color:#e9c6a0');
            ?>
            <div x-show="filter === 'all' || filter === '<?php echo e($artistParticipant?->id ?? ''); ?>'">
                <a href="<?php echo e(route('studio.conversations.show', $conv)); ?>"
                   class="flex items-start gap-4 px-4 py-4 hover:bg-noir-profond/30 transition-colors">

                    <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm text-white"
                         style="background:rgba(233,198,160,0.3)">
                        <?php echo e(mb_strtoupper(mb_substr($clientParticipant?->name ?? $clientParticipant?->pseudo ?? '?', 0, 1))); ?>

                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-0.5">
                            <p class="text-sm font-semibold text-ivoire-text truncate">
                                <?php echo e($clientParticipant?->name ?? $clientParticipant?->pseudo ?? 'Utilisateur'); ?>

                            </p>
                            <span class="text-[10px] px-1.5 py-0.5 rounded flex-shrink-0"
                                  style="background: <?php echo e($typeColor); ?>">
                                <?php echo e($convType); ?>

                            </span>
                        </div>
                        <p class="text-xs text-titane">
                            → <?php echo e($artistParticipant?->name ?? 'Artiste'); ?>

                        </p>
                        <p class="text-xs text-titane/60 mt-1 truncate">
                            <?php echo e(\Str::limit($lastMsg?->content ?? '—', 60)); ?>

                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <span class="text-[10px] text-titane whitespace-nowrap">
                            <?php echo e($conv->updated_at->diffForHumans()); ?>

                        </span>
                        <span class="text-[10px] text-titane/50"><?php echo e($conv->messages_count); ?> msg</span>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php else: ?>
        <div class="bg-gris-fonde rounded-xl py-12 text-center border border-titane/10">
            <p class="text-titane text-sm">Aucune conversation trouvée pour vos artistes</p>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php else: ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($conversations->count() > 0): ?>
    <div class="bg-gris-fonde rounded-xl border border-titane/10 divide-y divide-titane/5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $conversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $clientParticipant = $conv->participants->first();
            $lastMsg = $conv->messages->first();
            $convType = $conv->type === 'support' ? 'Support' : ($conv->type === 'booking' ? 'Réservation' : 'Privé');
            $typeColor = $conv->type === 'support' ? 'rgba(168,85,247,0.15);color:#c084fc' : ($conv->type === 'booking' ? 'rgba(59,130,246,0.15);color:#60a5fa' : 'rgba(233,198,160,0.1);color:#e9c6a0');
        ?>
        <a href="<?php echo e(route('studio.conversations.show', $conv)); ?>"
           class="flex items-start gap-4 px-4 py-4 hover:bg-noir-profond/30 transition-colors">
            <div class="w-10 h-10 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-sm text-white"
                 style="background:rgba(233,198,160,0.3)">
                <?php echo e(mb_strtoupper(mb_substr($clientParticipant?->name ?? '?', 0, 1))); ?>

            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                    <p class="text-sm font-semibold text-ivoire-text truncate">
                        <?php echo e($clientParticipant?->name ?? 'Utilisateur'); ?>

                    </p>
                    <span class="text-[10px] px-1.5 py-0.5 rounded flex-shrink-0"
                          style="background: <?php echo e($typeColor); ?>">
                        <?php echo e($convType); ?>

                    </span>
                </div>
                <p class="text-xs text-titane/60 mt-1 truncate">
                    <?php echo e(\Str::limit($lastMsg?->content ?? '—', 60)); ?>

                </p>
            </div>
            <div class="flex flex-col items-end gap-1 flex-shrink-0">
                <span class="text-[10px] text-titane"><?php echo e($conv->updated_at->diffForHumans()); ?></span>
                <span class="text-[10px] text-titane/50"><?php echo e($conv->messages_count); ?> msg</span>
            </div>
        </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php else: ?>
    <div class="bg-gris-fonde rounded-xl py-12 text-center border border-titane/10">
        <p class="text-titane text-sm">Aucune conversation trouvée pour vos artistes</p>
        <p class="text-xs text-titane/60 mt-2">Les conversations apparaîtront ici une fois que vos artistes auront des échanges avec des clients</p>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/studio/conversations.blade.php ENDPATH**/ ?>
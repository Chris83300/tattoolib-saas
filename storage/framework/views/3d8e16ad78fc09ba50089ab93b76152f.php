<div class="w-full">

    
    <div class="flex items-center justify-between mb-4">
        <button type="button" wire:click="previousMonth"
            class="p-2 text-titane hover:text-ivoire-text transition rounded-lg hover:bg-gris-fonde">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h3 class="text-lg font-semibold text-ivoire-text capitalize">
            <?php echo e($monthName); ?> <?php echo e($currentYear); ?>

        </h3>
        <button type="button" wire:click="nextMonth"
            class="p-2 text-titane hover:text-ivoire-text transition rounded-lg hover:bg-gris-fonde">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    
    <div class="grid grid-cols-7 gap-1 mb-2 text-center text-xs text-titane font-medium">
        <span>Lu</span><span>Ma</span><span>Me</span><span>Je</span><span>Ve</span><span>Sa</span><span>Di</span>
    </div>

    
    <div class="grid grid-cols-7 gap-1">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $calendarDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($readOnly): ?>
                
                <div
                    class="
                        relative h-12 rounded-lg flex flex-col items-center justify-center text-sm
                        <?php echo e($day['is_today'] ? 'border-2 border-titane' : ''); ?>

                        <?php echo e($day['outside_month'] ? 'opacity-20' : ''); ?>

                        <?php echo e($day['status'] === 'unavailable' ? 'opacity-30' : ''); ?>

                        <?php echo e($day['status'] === 'past' ? 'opacity-20' : ''); ?>

                    ">
                    <span class="text-ivoire-text font-medium z-10"><?php echo e($day['day_number']); ?></span>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$day['outside_month'] && $day['status'] !== 'past' && $day['status'] !== 'unavailable'): ?>
                        <div class="flex gap-0.5 mt-0.5">
                            <span
                                class="w-1.5 h-1.5 rounded-full <?php echo e($day['morning_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50'); ?>"></span>
                            <span
                                class="w-1.5 h-1.5 rounded-full <?php echo e($day['afternoon_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50'); ?>"></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php else: ?>
                
                <button type="button" wire:click="selectDate('<?php echo e($day['date']); ?>')" <?php if($day['status'] === 'unavailable' || $day['status'] === 'past' || $day['outside_month']): echo 'disabled'; endif; ?>
                    class="
                        relative h-12 rounded-lg flex flex-col items-center justify-center text-sm transition-all
                        <?php echo e($day['selected'] ? 'ring-2 ring-beige-peau bg-beige-peau/10' : ''); ?>

                        <?php echo e($day['is_today'] ? 'border-2 border-titane' : ''); ?>

                        <?php echo e($day['outside_month'] ? 'opacity-20 cursor-default' : ''); ?>

                        <?php echo e($day['status'] === 'unavailable' ? 'opacity-30 cursor-not-allowed' : ''); ?>

                        <?php echo e($day['status'] === 'past' ? 'opacity-20 cursor-not-allowed' : ''); ?>

                        <?php echo e(in_array($day['status'], ['fully_available', 'morning_only', 'afternoon_only']) ? 'hover:bg-gris-fonde cursor-pointer' : ''); ?>

                    ">
                    <span class="text-ivoire-text font-medium z-10"><?php echo e($day['day_number']); ?></span>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$day['outside_month'] && $day['status'] !== 'past' && $day['status'] !== 'unavailable'): ?>
                        <div class="flex gap-0.5 mt-0.5">
                            <span
                                class="w-1.5 h-1.5 rounded-full <?php echo e($day['morning_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50'); ?>"></span>
                            <span
                                class="w-1.5 h-1.5 rounded-full <?php echo e($day['afternoon_available'] ? 'bg-vert-succes' : 'bg-rouge-alerte/50'); ?>"></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="flex items-center gap-4 mt-3 text-xs text-titane">
        <div class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-vert-succes"></span> Disponible
        </div>
        <div class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-rouge-alerte/50"></span> Occupé
        </div>
        <div class="flex items-center gap-1">
            <span class="w-2 h-2 rounded-full bg-titane/30"></span> Indisponible
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPeriodSelector && count($selectedDates) > 0): ?>
        <div class="mt-4 space-y-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $selectedDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 bg-gris-fonde rounded-lg p-3">
                    <span class="text-sm text-ivoire-text font-medium">
                        <?php echo e(\Carbon\Carbon::parse($sel['date'])->translatedFormat('D d M Y')); ?>

                    </span>
                    <select wire:model.live="selectedDates.<?php echo e($index); ?>.period"
                        class="bg-noir-profond border border-titane/30 rounded-lg px-3 py-1.5 text-sm text-ivoire-text">
                        <option value="">Flexible</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dayAvailability[$sel['date']]['morning'] ?? false): ?>
                            <option value="morning">Matin</option>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($dayAvailability[$sel['date']]['afternoon'] ?? false): ?>
                            <option value="afternoon">Après-midi</option>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <button type="button" wire:click="removeDate(<?php echo e($index); ?>)"
                        class="text-rouge-alerte hover:text-rouge-alerte/80 ml-auto text-lg">
                        &times;
                    </button>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\components\availability-calendar.blade.php ENDPATH**/ ?>
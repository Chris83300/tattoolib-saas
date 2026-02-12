
<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
        $bookingRequest->status === \App\Enums\BookingRequestStatus::DEPOSIT_PAID &&
            $bookingRequest->proposed_dates &&
            !$bookingRequest->client_selected_dates): ?>
        <div class="bg-gris-fonde rounded-xl p-4 border border-titane/20">
            <h4 class="font-semibold text-ivoire-text mb-2">📅 Choisissez vos dates préférées</h4>
            <p class="text-sm text-titane mb-3">
                Sélectionnez les dates qui vous conviennent (le tatoueur fixera la date finale).
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->date_selection_deadline): ?>
                    <span class="text-ambre-warning font-medium">
                        ⏰ Avant le <?php echo e($bookingRequest->date_selection_deadline->translatedFormat('d/m/Y à H:i')); ?>

                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </p>

            
            <div class="space-y-2 mb-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->proposed_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $proposal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button wire:click="toggleDateSelection(<?php echo e($index); ?>)"
                        class="w-full flex items-center justify-between p-3 rounded-lg border transition-all
                        <?php echo e(in_array($index, $selectedDateIndexes)
                            ? 'border-vert-succes bg-vert-succes/10 text-ivoire-text'
                            : 'border-titane/30 bg-noir-profond text-titane hover:border-beige-peau'); ?>">
                        <span class="flex items-center gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($index, $selectedDateIndexes)): ?>
                                <span class="text-vert-succes">✓</span>
                            <?php else: ?>
                                <span class="text-titane/50">○</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php echo e(\Carbon\Carbon::parse($proposal['date'])->translatedFormat('l d F Y')); ?>

                        </span>
                        <span
                            class="text-sm px-2 py-0.5 rounded-full <?php echo e(match ($proposal['period'] ?? '') {
                                'morning' => 'bg-blue-500/20 text-blue-300',
                                'afternoon' => 'bg-amber-500/20 text-amber-300',
                                'evening' => 'bg-purple-500/20 text-purple-300',
                                default => 'bg-titane/20 text-titane',
                            }); ?>">
                            <?php echo e(match ($proposal['period'] ?? '') {
                                'morning' => '☀️ Matin',
                                'afternoon' => '🌤️ Après-midi',
                                'evening' => '🌙 Soirée',
                                default => '🔄 Flexible',
                            }); ?>

                        </span>
                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <button wire:click="requestAlternativeDate"
                class="text-xs text-titane underline hover:text-ivoire-text mb-4 block">
                Aucune date ne me convient → demander d'autres propositions
            </button>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($selectedDateIndexes) > 0): ?>
                <button wire:click="confirmDateSelection"
                    wire:confirm="Confirmer votre sélection de <?php echo e(count($selectedDateIndexes)); ?> date(s) ?"
                    class="w-full py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>✅ Valider ma sélection (<?php echo e(count($selectedDateIndexes)); ?> date(s))</span>
                    <span wire:loading>⏳ Traitement...</span>
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest->client_selected_dates && $bookingRequest->client_dates_selected_at): ?>
        <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-xl p-4">
            <h4 class="font-semibold text-vert-succes mb-2">✅ Vos dates sélectionnées</h4>
            <p class="text-sm text-ivoire-text mb-3">
                Le tatoueur a reçu vos préférences et vous contactera pour finaliser le rendez-vous.
            </p>
            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookingRequest->client_selected_dates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selected): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center gap-2 text-sm text-ivoire-text">
                        <span class="text-vert-succes">✓</span>
                        <?php echo e(\Carbon\Carbon::parse($selected['date'])->translatedFormat('l d F Y')); ?>

                        <span class="text-xs px-2 py-0.5 rounded-full bg-vert-succes/20 text-vert-succes">
                            <?php echo e(match ($selected['period'] ?? '') {
                                'morning' => 'Matin',
                                'afternoon' => 'Après-midi',
                                'evening' => 'Soirée',
                                default => 'Flexible',
                            }); ?>

                        </span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <p class="text-xs text-titane mt-3">
                Sélectionné le <?php echo e($bookingRequest->client_dates_selected_at->translatedFormat('d/m/Y à H:i')); ?>

            </p>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\client\date-selection.blade.php ENDPATH**/ ?>
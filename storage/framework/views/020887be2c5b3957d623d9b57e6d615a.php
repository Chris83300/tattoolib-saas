<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointment): ?>
    <div 
        x-data="{ show: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?>, editMode: <?php if ((object) ('editMode') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('editMode'->value()); ?>')<?php echo e('editMode'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('editMode'); ?>')<?php endif; ?> }"
        x-show="show" 
        x-cloak
        class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4"
    >
        <div class="bg-gris-fonde rounded-xl max-w-lg w-full p-6" @click.away="show = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-ivoire-text"><?php echo e($appointment->title); ?></h3>
                <div class="flex items-center gap-2">
                    
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?php echo e($appointment->status->color()); ?>">
                        <?php echo e($appointment->status->label()); ?>

                    </span>
                    <button @click="show = false" class="text-titane hover:text-ivoire-text text-xl">&times;</button>
                </div>
            </div>

            <!-- Infos RDV -->
            <div x-show="!editMode" class="space-y-3 mb-6">
                <div class="flex items-center gap-3 text-ivoire-text">
                    <span class="text-titane">📅</span>
                    <span><?php echo e($appointment->start_datetime->translatedFormat('l d F Y')); ?></span>
                </div>
                <div class="flex items-center gap-3 text-ivoire-text">
                    <span class="text-titane">🕐</span>
                    <span><?php echo e($appointment->start_datetime->format('H:i')); ?> → <?php echo e($appointment->end_datetime->format('H:i')); ?></span>
                    <span class="text-titane text-sm">(<?php echo e($appointment->start_datetime->diffInMinutes($appointment->end_datetime)); ?> min)</span>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointment->bookingRequest?->client): ?>
                    <div class="flex items-center gap-3 text-ivoire-text">
                        <span class="text-titane">👤</span>
                        <span><?php echo e($appointment->bookingRequest->client->user->pseudo ?? $appointment->bookingRequest->client->user->name); ?></span>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Formulaire modification (toggle) -->
            <div x-show="editMode" class="space-y-3 mb-6">
                <div>
                    <label class="text-sm text-titane">Nouvelle date</label>
                    <input type="date" wire:model="editDate" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-rouge-alerte text-xs"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm text-titane">Début</label>
                        <input type="time" wire:model="editStartTime" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editStartTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-rouge-alerte text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="text-sm text-titane">Fin</label>
                        <input type="time" wire:model="editEndTime" class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editEndTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-rouge-alerte text-xs"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button wire:click="updateAppointment" class="flex-1 py-2 bg-beige-peau text-noir-profond font-bold rounded-lg">
                        ✅ Sauvegarder
                    </button>
                    <button @click="editMode = false" class="flex-1 py-2 border border-titane/30 text-titane rounded-lg">
                        Annuler
                    </button>
                </div>
            </div>

            <!-- 3 Boutons d'action -->
            <div x-show="!editMode" class="grid grid-cols-3 gap-3">
                
                <a href="<?php echo e(route('tattooer.booking-requests.show', $appointment->booking_request_id)); ?>"
                   class="flex flex-col items-center gap-1 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-beige-peau transition text-center">
                    <span class="text-lg">📋</span>
                    <span class="text-xs text-ivoire-text font-medium">Voir détails</span>
                </a>
                
                
                <button @click="editMode = true"
                        class="flex flex-col items-center gap-1 py-3 bg-noir-profond border border-titane/30 rounded-lg hover:border-ambre-warning transition text-center">
                    <span class="text-lg">✏️</span>
                    <span class="text-xs text-ivoire-text font-medium">Modifier</span>
                </button>
                
                
                <button wire:click="openCancelConfirm"
                        class="flex flex-col items-center gap-1 py-3 bg-noir-profond border border-rouge-alerte/30 rounded-lg hover:border-rouge-alerte transition text-center">
                    <span class="text-lg">❌</span>
                    <span class="text-xs text-ivoire-text font-medium">Annuler</span>
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Sous-modale confirmation annulation -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCancelConfirm): ?>
    <div class="fixed inset-0 bg-black/90 z-[60] flex items-center justify-center p-4">
        <div class="bg-gris-fonde rounded-xl max-w-sm w-full p-6">
            <h4 class="text-lg font-bold text-rouge-alerte mb-3">Annuler le rendez-vous ?</h4>
            <p class="text-sm text-titane mb-4">Le client sera notifié. Un remboursement peut s'appliquer selon vos conditions.</p>
            <textarea wire:model="cancelReason" placeholder="Motif (optionnel)" rows="2"
                      class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text mb-4"></textarea>
            <div class="flex gap-3">
                <button wire:click="$set('showCancelConfirm', false)" class="flex-1 py-2 border border-titane/30 text-titane rounded-lg">
                    Retour
                </button>
                <button wire:click="cancelAppointment" class="flex-1 py-2 bg-rouge-alerte text-ivoire-text font-bold rounded-lg">
                    Confirmer l'annulation
                </button>
            </div>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\tattooer\appointment-detail-modal.blade.php ENDPATH**/ ?>
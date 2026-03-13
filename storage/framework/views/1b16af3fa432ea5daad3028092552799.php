<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showQuickBookingModal): ?>
        <div class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4" x-data="{
            calculateDuration() {
                const start = document.querySelector('[wire\\:model=startTime]')?.value || '';
                const end = document.querySelector('[wire\\:model=endTime]')?.value || '';
                if (!start || !end) return '0';
                const [sh, sm] = start.split(':').map(Number);
                const [eh, em] = end.split(':').map(Number);
                const diff = (eh * 60 + em) - (sh * 60 + sm);
                return diff > 0 ? diff : '0';
            }
        }"
            x-trap.noscroll="true" @click.self="$wire.set('showQuickBookingModal', false)">

            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-ivoire-text">📅 Fixer le rendez-vous</h3>
                <button type="button" wire:click="$set('showQuickBookingModal', false)"
                    class="text-titane hover:text-ivoire-text transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Client info -->
            <div class="bg-noir-profond/50 rounded-lg p-3 mb-4">
                <p class="text-sm text-ivoire-text/80 mb-1">Client</p>
                <p class="text-ivoire-text font-medium"><?php echo e($bookingRequest?->client?->user?->pseudo ?? 'Client'); ?>

                </p>
                <p class="text-xs text-titane mt-1"><?php echo e($bookingRequest?->tattoo_description); ?></p>
            </div>

            <!-- Date verrouillée -->
            <div class="bg-vert-succes/10 border border-vert-succes/30 rounded-lg p-3 mb-4">
                <p class="text-sm text-vert-succes font-medium mb-1">📅 Date choisie par le client</p>
                <p class="text-ivoire-text font-bold"><?php echo e($appointmentDateDisplay); ?></p>
            </div>

            <!-- Formulaire -->
            <form wire:submit.prevent="createAppointment" class="space-y-4">

                <!-- Titre -->
                <div>
                    <label class="block text-sm font-medium text-ivoire-text/80 mb-1">
                        Titre du rendez-vous
                    </label>
                    <input type="text" wire:model="appointmentTitle" required
                        class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text placeholder-titane focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                </div>

                <!-- Heures -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-ivoire-text/80 mb-1">
                            Heure de début
                        </label>
                        <input type="time" wire:model="startTime" required
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-ivoire-text/80 mb-1">
                            Heure de fin
                        </label>
                        <input type="time" wire:model="endTime" required
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                    </div>
                </div>

                <!-- Durée calculée -->
                <div class="bg-noir-profond/30 rounded-lg p-3">
                    <p class="text-xs text-titane">Durée estimée</p>
                    <p class="text-ivoire-text font-medium">
                        <span x-text="calculateDuration()"></span> minutes
                    </p>
                </div>

                <!-- Erreurs -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['startTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rouge-alerte text-sm"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['endTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rouge-alerte text-sm"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="$set('showQuickBookingModal', false)"
                        class="flex-1 px-4 py-2 border border-titane/30 text-titane rounded-lg hover:bg-noir-profond transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                        ✅ Confirmer le RDV
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/livewire/tattooer/quick-booking-modal.blade.php ENDPATH**/ ?>
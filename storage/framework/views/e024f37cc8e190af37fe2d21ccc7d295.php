<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showModal): ?>
        <div x-data="{ open: true }" x-show="open" x-cloak
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
            @click.self="open = false; $wire.closeModal()" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">

            <div class="bg-gris-fonde rounded-2xl border border-beige-peau/20 shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto"
                @click.stop>

                
                <div
                    class="sticky top-0 bg-gris-fonde border-b border-beige-peau/20 p-6 flex items-center justify-between z-10">
                    <h2 class="text-2xl font-bold text-ivoire-text">Accepter la demande</h2>
                    <button type="button" wire:click="closeModal" class="text-ivoire-text/60 hover:text-rouge-alerte">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                
                <form wire:submit="submitAcceptance" class="p-6 space-y-8">

                    
                    
                    
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">1</span>
                            💰 Estimation du projet
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Prix minimum (€) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="priceEstimateMin" step="0.01" required
                                        placeholder="300"
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['priceEstimateMin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Prix maximum (€) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="priceEstimateMax" step="0.01" required
                                        placeholder="500"
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['priceEstimateMax'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Montant acompte (€) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="totalDepositAmount" step="0.01" required
                                        placeholder="100"
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['totalDepositAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Délai paiement (jours) <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <select wire:model="clientPaymentDeadlineDays" required
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau">
                                        <option value="3">3 jours</option>
                                        <option value="5">5 jours</option>
                                        <option value="7">7 jours (recommandé)</option>
                                        <option value="14">14 jours</option>
                                        <option value="30">30 jours</option>
                                    </select>
                                    <p class="text-xs text-ivoire-text/60 mt-1">Le chat se fermera automatiquement si le
                                        client ne paie pas dans ce délai.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    
                    
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">2</span>
                            📅 Proposition de rendez-vous
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4">
                            <p class="text-ivoire-text/60 text-sm mb-4">
                                Cliquez sur 1 à 3 dates disponibles pour les proposer au client.
                                Le calendrier affiche vos disponibilités réelles.
                            </p>

                            
                            
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bookingRequest): ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('components.availability-calendar', ['tattooerId' => $bookingRequest->bookable_id,'mode' => 'multi-max-3','showPeriodSelector' => true]);

$key = 'calendar-accept-' . $bookingRequest->id;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-807747036-0', 'calendar-accept-' . $bookingRequest->id);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['proposedDates'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-rouge-alerte text-sm mt-2 block"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>
                    </div>

                    
                    
                    
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">3</span>
                            🎨 Options de design
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Versions de design incluses <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="includedDesignVersions" min="1"
                                        max="10" required
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                </div>
                                <div>
                                    <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                        Modifications par version <span class="text-rouge-alerte">*</span>
                                    </label>
                                    <input type="number" wire:model="modificationsPerDesign" min="0"
                                        max="10" required
                                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    
                    
                    <div class="space-y-4">
                        <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                            <span
                                class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">4</span>
                            📝 Notes pour le client
                        </h3>

                        <div class="bg-noir-profond/50 rounded-xl p-4">
                            <textarea wire:model="tattooerNotes" rows="4" placeholder="Informations complémentaires pour le client..."
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20"></textarea>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['tattooerNotes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="flex gap-4 pt-6 border-t border-beige-peau/20">
                        <button type="button" wire:click="closeModal"
                            class="flex-1 px-6 py-4 bg-noir-profond border-2 border-beige-peau/20 text-ivoire-text rounded-xl font-semibold hover:bg-beige-peau/10 transition-all">
                            Annuler
                        </button>
                        <button type="submit"
                            class="flex-1 px-6 py-4 bg-vert-succes text-noir-profond rounded-xl font-bold text-lg hover:bg-vert-succes/90 transition-all shadow-lg"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove>✓ Valider et envoyer au client</span>
                            <span wire:loading>⏳ Traitement...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\tattooer\accept-booking-modal.blade.php ENDPATH**/ ?>
<div class="bg-titane/20 rounded-xl p-6 border border-titane/30">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-ivoire-text">Demander un acompte</h2>
        <span class="text-sm text-ivoire-text/70">
            Client: <?php echo e($bookingRequest->client->full_name); ?>

        </span>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
        <div class="bg-vert-succes/20 border border-vert-succes text-vert-succes px-4 py-3 rounded-lg mb-4">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div class="bg-rouge-alerte/20 border border-rouge-alerte text-rouge-alerte px-4 py-3 rounded-lg mb-4">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Résumé du projet -->
    <div class="bg-noir-profond rounded-lg p-4 mb-6 border border-titane/30">
        <h3 class="font-bold text-ivoire-text mb-2">Résumé du projet</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-ivoire-text/70">Description:</span>
                <p class="text-ivoire-text font-medium"><?php echo e($bookingRequest->tattoo_description); ?></p>
            </div>
            <div>
                <span class="text-ivoire-text/70">Emplacement:</span>
                <p class="text-ivoire-text font-medium"><?php echo e($bookingRequest->tattoo_location); ?></p>
            </div>
            <div>
                <span class="text-ivoire-text/70">Style:</span>
                <p class="text-ivoire-text font-medium"><?php echo e($bookingRequest->tattoo_style); ?></p>
            </div>
            <div>
                <span class="text-ivoire-text/70">Statut:</span>
                <p class="text-ivoire-text font-medium"><?php echo e(ucfirst($bookingRequest->status)); ?></p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <form wire:submit="requestDeposit" class="space-y-6">
        <!-- Prix et acompte -->
        <div class="border-b border-titane/30 pb-6">
            <h3 class="text-lg font-bold text-ivoire-text mb-4">Tarification</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-ivoire-text mb-2">
                        Prix total estimé (€) *
                    </label>
                    <div class="relative">
                        <input type="number" wire:model="estimatedPrice" min="10" step="5"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg focus:ring-beige-peau focus:border-beige-peau text-ivoire-text pr-12">
                        <span class="absolute right-3 top-2 text-ivoire-text/70">€</span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estimatedPrice'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-ivoire-text mb-2">
                        Acompte demandé (€) *
                    </label>
                    <div class="relative">
                        <input type="number" wire:model="depositAmount" min="10" step="5"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg focus:ring-beige-peau focus:border-beige-peau text-ivoire-text pr-12">
                        <span class="absolute right-3 top-2 text-ivoire-text/70">€</span>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['depositAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Bouton calcul automatique -->
                        <button type="button" wire:click="calculateDeposit"
                            class="mt-2 text-sm text-beige-peau hover:text-beige-peau/80">
                            Calculer 30% automatiquement
                        </button>
                    </div>
                </div>

                <!-- Résumé financier -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($estimatedPrice && $depositAmount): ?>
                    <div class="mt-4 p-4 bg-beige-peau/10 rounded-lg border border-beige-peau/30">
                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-sm text-ivoire-text/70">Acompte</p>
                                <p class="font-semibold text-ivoire-text"><?php echo e(number_format($depositAmount, 2)); ?>€</p>
                            </div>
                            <div>
                                <p class="text-sm text-ivoire-text/70">Reste dû</p>
                                <p class="font-semibold"><?php echo e(number_format($remainingAmount, 2)); ?>€</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total</p>
                                <p class="font-semibold"><?php echo e(number_format($estimatedPrice, 2)); ?>€</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Durée et rendez-vous -->
            <div class="border-b border-titane/30 pb-6">
                <h3 class="text-lg font-bold text-ivoire-text mb-4">Planning</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-ivoire-text mb-2">
                            Durée estimée (minutes) *
                        </label>
                        <input type="number" wire:model="estimatedDuration" min="30" step="15"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg focus:ring-beige-peau focus:border-beige-peau text-ivoire-text">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['estimatedDuration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <p class="text-xs text-ivoire-text/70 mt-1"><?php echo e(floor($estimatedDuration / 60)); ?>h
                            <?php echo e($estimatedDuration % 60); ?>min</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ivoire-text mb-2">
                            Date du rendez-vous *
                        </label>
                        <input type="date" wire:model="appointmentDate"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg focus:ring-beige-peau focus:border-beige-peau text-ivoire-text">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['appointmentDate'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-ivoire-text mb-2">
                            Heure de début *
                        </label>
                        <select wire:model="appointmentTime"
                            class="w-full px-3 py-2 bg-noir-profond border border-titane/30 rounded-lg focus:ring-beige-peau focus:border-beige-peau text-ivoire-text">
                            <option value="">Sélectionnez une heure</option>
                            <option value="09:00">09:00</option>
                            <option value="09:30">09:30</option>
                            <option value="10:00">10:00</option>
                            <option value="10:30">10:30</option>
                            <option value="11:00">11:00</option>
                            <option value="11:30">11:30</option>
                            <option value="14:00">14:00</option>
                            <option value="14:30">14:30</option>
                            <option value="15:00">15:00</option>
                            <option value="15:30">15:30</option>
                            <option value="16:00">16:00</option>
                            <option value="16:30">16:30</option>
                            <option value="17:00">17:00</option>
                            <option value="17:30">17:30</option>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['appointmentTime'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-rouge-alerte text-sm"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($appointmentEndTime): ?>
                            <p class="text-xs text-ivoire-text/70 mt-1">
                                Fin: <?php echo e($appointmentEndTime); ?>

                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-4 mt-8">
                <a href="<?php echo e(route('tattooer.requests')); ?>"
                    class="px-6 py-2 border border-titane/30 text-ivoire-text rounded-lg hover:bg-titane/30 transition-colors">
                    Annuler
                </a>

                <button type="submit" wire:loading.attr="disabled"
                    class="px-6 py-2 bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/90 transition-colors disabled:opacity-50 font-semibold">
                    <span wire:loading.remove>Envoyer la demande d'acompte</span>
                    <span wire:loading>Envoi en cours...</span>
                </button>
            </div>
        </form>

        <!-- Informations -->
        <div class="mt-6 p-4 bg-beige-peau/10 rounded-lg border border-beige-peau/30">
            <h4 class="font-bold text-ivoire-text mb-2">
                <svg class="w-5 h-5 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Important
            </h4>
            <ul class="text-sm text-ivoire-text/80 space-y-1">
                <li>• Le client recevra un email avec un lien de paiement sécurisé</li>
                <li>• Le rendez-vous sera confirmé uniquement après paiement de l'acompte</li>
                <li>• Le client aura 48h pour payer l'acompte</li>
                <li>• En cas de non-paiement, la demande sera automatiquement annulée</li>
            </ul>
        </div>
    </div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\request-deposit.blade.php ENDPATH**/ ?>
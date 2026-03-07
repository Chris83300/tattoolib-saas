

<div class="space-y-3 pt-2 border-t border-titane/10">
    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" wire:model="acceptCgu"
            class="mt-0.5 w-4 h-4 rounded border-titane/50 bg-noir-profond text-beige-peau focus:ring-beige-peau flex-shrink-0">
        <span class="text-xs text-titane leading-relaxed">
            J'ai lu et j'accepte les
            <a href="<?php echo e(route('legal.cgu')); ?>" target="_blank" class="text-beige-peau hover:underline">Conditions Générales d'Utilisation</a>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isPro ?? false): ?>
                et les <a href="<?php echo e(route('legal.cgv-artistes')); ?>" target="_blank" class="text-beige-peau hover:underline">Conditions Générales de Vente</a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <span class="text-rouge-alerte">*</span>
        </span>
    </label>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['acceptCgu'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-rouge-alerte text-xs pl-7"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <label class="flex items-start gap-3 cursor-pointer">
        <input type="checkbox" wire:model="acceptPrivacy"
            class="mt-0.5 w-4 h-4 rounded border-titane/50 bg-noir-profond text-beige-peau focus:ring-beige-peau flex-shrink-0">
        <span class="text-xs text-titane leading-relaxed">
            J'ai lu et j'accepte la
            <a href="<?php echo e(route('legal.politique-confidentialite')); ?>" target="_blank" class="text-beige-peau hover:underline">Politique de confidentialité</a>
            <span class="text-rouge-alerte">*</span>
        </span>
    </label>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['acceptPrivacy'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <p class="text-rouge-alerte text-xs pl-7"><?php echo e($message); ?></p>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\partials\legal-checkboxes.blade.php ENDPATH**/ ?>
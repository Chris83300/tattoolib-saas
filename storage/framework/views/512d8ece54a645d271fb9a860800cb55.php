<?php
    $user = auth()->user();
    $enabled = $user->hasEnabledTwoFactorAuthentication();
    $confirmed = $user->two_factor_confirmed_at !== null;
?>

<div class="bg-gris-fonde rounded-xl border border-titane/10 p-4 md:p-6 mt-6" x-data="{
    showRecoveryCodes: false,
    enabling: false,
    confirming: false,
    disabling: false,
    confirmationCode: '',
}">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h3 class="text-lg font-semibold text-ivoire-text">Authentification à deux facteurs (2FA)</h3>
            <p class="text-sm text-titane mt-1">
                Ajoutez une couche de sécurité supplémentaire avec une application comme Google Authenticator, Authy ou 1Password.
            </p>
        </div>
        <div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enabled && $confirmed): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium bg-green-500/10 text-green-400 rounded-full">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Activée
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-medium bg-rouge-alerte/10 text-rouge-alerte rounded-full">
                    Désactivée
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $enabled): ?>
        <form method="POST" action="<?php echo e(url('/user/two-factor-authentication')); ?>"
            @submit.prevent="enabling = true; $el.submit()">
            <?php echo csrf_field(); ?>
            <div class="flex items-center gap-4 flex-wrap">
                <button type="submit" :disabled="enabling"
                    class="min-h-11 px-5 py-2.5 text-sm font-semibold bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors disabled:opacity-50">
                    <span x-show="!enabling">🔐 Activer la 2FA</span>
                    <span x-show="enabling">Activation...</span>
                </button>
            </div>
        </form>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enabled && ! $confirmed): ?>
        <div class="space-y-4">
            <div class="p-4 bg-noir-profond/50 rounded-lg">
                <p class="text-sm text-ivoire-text mb-3">Scannez ce QR code avec votre application d'authentification :</p>
                <div class="flex justify-center bg-white p-4 rounded-lg w-fit mx-auto">
                    <?php echo $user->twoFactorQrCodeSvg(); ?>

                </div>
                <div class="mt-3">
                    <p class="text-xs text-titane">Ou entrez ce code manuellement :</p>
                    <code class="block mt-1 text-sm text-beige-peau bg-noir-profond px-3 py-2 rounded font-mono break-all">
                        <?php echo e(decrypt($user->two_factor_secret)); ?>

                    </code>
                </div>
            </div>

            <form method="POST" action="<?php echo e(url('/user/confirmed-two-factor-authentication')); ?>"
                @submit.prevent="confirming = true; $el.submit()">
                <?php echo csrf_field(); ?>
                <label class="block text-sm text-titane mb-2">
                    Entrez le code à 6 chiffres de votre application pour confirmer :
                </label>
                <div class="flex items-center gap-3 flex-wrap">
                    <input type="text" name="code" x-model="confirmationCode" required autofocus
                        maxlength="6" inputmode="numeric" pattern="[0-9]*"
                        class="w-40 px-4 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-center text-lg tracking-widest focus:border-beige-peau focus:ring-1 focus:ring-beige-peau">
                    <button type="submit" :disabled="confirming || confirmationCode.length < 6"
                        class="min-h-11 px-5 py-2.5 text-sm font-semibold bg-beige-peau text-noir-profond rounded-lg hover:bg-beige-peau/80 transition-colors disabled:opacity-50">
                        Confirmer
                    </button>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-rouge-alerte mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </form>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($enabled && $confirmed): ?>
        <div class="space-y-4">
            <button @click="showRecoveryCodes = !showRecoveryCodes" type="button"
                class="text-sm text-beige-peau hover:underline">
                <span x-show="!showRecoveryCodes">Afficher les codes de récupération</span>
                <span x-show="showRecoveryCodes">Masquer les codes de récupération</span>
            </button>

            <div x-show="showRecoveryCodes" x-transition class="p-4 bg-noir-profond/50 rounded-lg">
                <p class="text-xs text-titane mb-2">
                    Conservez ces codes dans un endroit sûr — ils permettent de vous connecter si vous perdez l'accès à votre application.
                </p>
                <div class="grid grid-cols-2 gap-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = json_decode(decrypt($user->two_factor_recovery_codes), true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <code class="text-sm text-ivoire-text font-mono bg-noir-profond px-2 py-1 rounded"><?php echo e($code); ?></code>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <form method="POST" action="<?php echo e(url('/user/two-factor-recovery-codes')); ?>" class="mt-3">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="text-xs text-beige-peau hover:underline">
                        Regénérer les codes de récupération
                    </button>
                </form>
            </div>

            <div class="pt-4 border-t border-titane/10">
                <form method="POST" action="<?php echo e(url('/user/two-factor-authentication')); ?>"
                    @submit.prevent="if(confirm('Êtes-vous sûr de vouloir désactiver la 2FA ?')) { disabling = true; $el.submit(); }">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" :disabled="disabling"
                        class="min-h-11 px-5 py-2.5 text-sm font-semibold text-rouge-alerte border border-rouge-alerte/30 rounded-lg hover:bg-rouge-alerte/10 transition-colors disabled:opacity-50">
                        Désactiver la 2FA
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/partials/two-factor-settings.blade.php ENDPATH**/ ?>
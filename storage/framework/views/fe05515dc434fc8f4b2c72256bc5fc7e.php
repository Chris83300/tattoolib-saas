<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="text-beige-peau font-Satoshi text-3xl font-bold">
                Ink&Pik
            </a>
        </div>

        <!-- Titre -->
        <h1 class="text-2xl md:text-3xl font-display font-bold text-ivoire-text mb-2 text-center">
            Connexion
        </h1>
        <p class="text-ivoire-text/70 text-center mb-8">
            Connectez-vous à votre compte Ink&Pik
        </p>

        <!-- Formulaire -->
        <form wire:submit="login" class="bg-gris-fonde rounded-xl p-6 space-y-4">
            <?php echo csrf_field(); ?>

            <!-- Email -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Email
                </label>
                <input
                    type="email"
                    wire:model="email"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    required
                    placeholder="votre@email.com"
                >
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rouge-alerte text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Mot de passe
                </label>
                <input
                    type="password"
                    wire:model="password"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    required
                    placeholder="••••••••••••"
                >
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-rouge-alerte text-xs mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Remember me -->
            <div class="flex items-center">
                <input
                    type="checkbox"
                    wire:model="remember"
                    id="remember"
                    class="w-4 h-4 text-beige-peau bg-noir-profond border-titane/30 rounded focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50">
                >
                <label for="remember" class="text-ivoire-text/70 text-sm">
                    Se souvenir de moi
                </label>
            </div>

            <!-- Submit -->
            <button
                type="submit"
                class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors"
            >
                Se connecter
            </button>
        </form>

        <!-- Lien inscription -->
        <div class="text-center mt-6">
            <p class="text-ivoire-text/70 text-sm">
                Pas encore de compte ?
                <a href="<?php echo e(route('register')); ?>" class="text-beige-peau font-semibold hover:underline">
                    Créer un compte
                </a>
            </p>
        </div>

        <!-- Lien mot de passe oublié -->
        <div class="text-center mt-4">
            <a href="#" class="text-ivoire-text/50 text-sm hover:text-beige-peau hover:underline">
                Mot de passe oublié ?
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\auth\login-new.blade.php ENDPATH**/ ?>
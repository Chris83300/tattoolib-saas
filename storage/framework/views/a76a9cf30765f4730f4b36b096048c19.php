<?php $__env->startSection('title', 'Connexion - Ink&Pik'); ?>

<div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="<?php echo e(route('home')); ?>" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
                ← Retour à l'accueil
            </a>
            <h1 class="text-beige-peau font-display text-2xl font-bold">
                Connexion
            </h1>
            <p class="text-ivoire-text/70 text-sm mt-2">
                Accédez à votre compte Ink&Pik
            </p>
        </div>

        <!-- Formulaire -->
        <form action="<?php echo e(route('login.authenticate')); ?>" method="POST" class="bg-gris-fonde rounded-xl p-6 space-y-4">
            <?php echo csrf_field(); ?>

            <!-- Email -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Email *
                </label>
                <input type="email" name="email" required value="<?php echo e(old('email')); ?>"
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    placeholder="votre@email.com">
            </div>

            <!-- Password -->
            <div>
                <label class="block text-ivoire-text text-sm font-semibold mb-2">
                    Mot de passe *
                </label>
                <input type="password" name="password" required
                    class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                    placeholder="•••••••••">
            </div>

            <!-- Erreurs -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-lg p-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p class="text-rouge-alerte text-sm"><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-beige-peau hover:bg-beige-peau/90 text-noir-profond font-bold py-3 rounded-lg transition-colors">
                Se connecter
            </button>

        </form>

        <!-- Lien inscription -->
        <div class="text-center mt-6">
            <p class="text-ivoire-text/70 text-sm">
                Pas encore de compte ?
                <a href="<?php echo e(route('register')); ?>" class="text-beige-peau font-semibold hover:underline">
                    S'inscrire
                </a>
            </p>
        </div>
    </div>
</div>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/auth/login.blade.php ENDPATH**/ ?>
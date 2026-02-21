<?php $__env->startSection('content'); ?>
<div class="flex items-center justify-center px-4 py-12 min-h-screen">
    <div class="max-w-2xl w-full">
        <!-- Logo + Titre -->
        <div class="text-center mb-10">
            <a href="/" class="text-beige-peau font-display text-3xl font-bold">
                Ink&Pik
            </a>
            <h1 class="text-ivoire-text text-2xl font-display font-bold mt-6 mb-2">
                Créer un compte
            </h1>
            <p class="text-ivoire-text/70">
                Vous êtes...
            </p>
        </div>

        <!-- Grid choix rôles (2x2) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">

            <!-- CLIENT -->
            <button
                wire:click="selectRole('client')"
                class="bg-gris-fonde hover:bg-beige-peau/10 hover:border-beige-peau border-2 border-transparent rounded-xl p-6 text-center transition-all group">
                <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-2 group-hover:text-beige-peau transition-colors">
                    Client
                </h3>
                <p class="text-ivoire-text/70 text-sm">
                    Je cherche un artiste pour mon projet
                </p>
            </button>

            <!-- TATTOOER -->
            <button
                wire:click="selectRole('tattooer')"
                class="bg-gris-fonde hover:bg-beige-peau/10 hover:border-beige-peau border-2 border-transparent rounded-xl p-6 text-center transition-all group">
                <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-2 group-hover:text-beige-peau transition-colors">
                    Tatoueur
                </h3>
                <p class="text-ivoire-text/70 text-sm">
                    Je suis tatoueur professionnel
                </p>
            </button>

            <!-- Piercer -->
            <button
                wire:click="selectRole('Piercer')"
                class="bg-gris-fonde hover:bg-beige-peau/10 hover:border-beige-peau border-2 border-transparent rounded-xl p-6 text-center transition-all group">
                <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-2 group-hover:text-beige-peau transition-colors">
                    Piercer
                </h3>
                <p class="text-ivoire-text/70 text-sm">
                    Je suis Piercer professionnel
                </p>
            </button>

            <!-- STUDIO -->
            <button
                wire:click="selectRole('studio')"
                class="bg-gris-fonde hover:bg-beige-peau/10 hover:border-beige-peau border-2 border-transparent rounded-xl p-6 text-center transition-all group">
                <div class="w-16 h-16 mx-auto mb-4 bg-beige-peau/10 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-ivoire-text font-display font-bold text-lg mb-2 group-hover:text-beige-peau transition-colors">
                    Salon / Studio
                </h3>
                <p class="text-ivoire-text/70 text-sm">
                    Je gère un salon avec plusieurs artistes
                </p>
            </button>

        </div>

        <!-- Lien connexion -->
        <div class="text-center">
            <p class="text-ivoire-text/70 text-sm">
                Vous avez déjà un compte ?
                <a href="<?php echo e(route('login')); ?>" class="text-beige-peau font-semibold hover:underline">
                    Se connecter
                </a>
            </p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\auth\register-wrapper.blade.php ENDPATH**/ ?>
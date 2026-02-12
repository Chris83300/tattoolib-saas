<div class="flex items-center justify-center px-4 py-12 min-h-screen bg-noir-profond">
    <div class="max-w-2xl w-full">
        <h1 class="text-beige-peau font-Satoshi text-3xl font-bold text-center mb-8">
            Ink&Pik
        </h1>
        <h2 class="text-ivoire-text text-xl font-display font-bold text-center mb-8">
            Créer un compte
        </h2>
        <p class="text-ivoire-text/70 text-center mb-8">
            Vous êtes...
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
            <button wire:click="selectRole('client')" class="bg-gris-fonde hover:bg-beige-peau/10 border-2 border-transparent rounded-xl p-6 text-center">
                <h3 class="text-ivoire-text font-bold">Client</h3>
                <p class="text-ivoire-text/70 text-sm">Je cherche un artiste</p>
            </button>

            <button wire:click="selectRole('tattooer')" class="bg-gris-fonde hover:bg-beige-peau/10 border-2 border-transparent rounded-xl p-6 text-center">
                <h3 class="text-ivoire-text font-bold">Tatoueur</h3>
                <p class="text-ivoire-text/70 text-sm">Je suis tatoueur</p>
            </button>

            <button wire:click="selectRole('pierceur')" class="bg-gris-fonde hover:bg-beige-peau/10 border-2 border-transparent rounded-xl p-6 text-center">
                <h3 class="text-ivoire-text font-bold">Pierceur</h3>
                <p class="text-ivoire-text/70 text-sm">Je suis pierceur</p>
            </button>

            <button wire:click="selectRole('studio')" class="bg-gris-fonde hover:bg-beige-peau/10 border-2 border-transparent rounded-xl p-6 text-center">
                <h3 class="text-ivoire-text font-bold">Studio</h3>
                <p class="text-ivoire-text/70 text-sm">Je gère un salon</p>
            </button>
        </div>

        <div class="text-center">
            <p class="text-ivoire-text/70 text-sm">
                Vous avez déjà un compte ?
                <a href="<?php echo e(route('login')); ?>" class="text-beige-peau font-semibold hover:underline">Se connecter</a>
            </p>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\livewire\auth\register-simple.blade.php ENDPATH**/ ?>
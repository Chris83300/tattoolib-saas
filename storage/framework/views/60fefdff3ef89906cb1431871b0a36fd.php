<?php $__env->startSection('content'); ?>
    <div class="max-w-xl mx-auto space-y-6" x-data="{ mode: 'create' }">
        <div>
            <a href="<?php echo e(route('studio.artists')); ?>" class="text-xs text-titane hover:text-ivoire-text transition-colors">←
                Retour</a>
            <h1 class="text-2xl font-bold text-ivoire-text mt-2">Ajouter un artiste</h1>
        </div>

        
        <div class="flex gap-2">
            <button @click="mode = 'create'"
                :class="mode === 'create' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
                class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                ✏️ Créer un compte
            </button>
            <button @click="mode = 'invite'"
                :class="mode === 'invite' ? 'bg-beige-peau text-noir-profond' : 'bg-gris-fonde text-titane'"
                class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                📧 Envoyer une invitation
            </button>
        </div>

        
        <form x-show="mode === 'create'" action="<?php echo e(route('studio.artists.store')); ?>" method="POST"
            class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <p class="text-xs text-titane">Créez un compte pour votre artiste. Définissez un mot de passe qu'il utilisera
                pour se connecter.</p>

            <div>
                <label class="text-xs text-titane block mb-1">Nom complet *</label>
                <input type="text" name="name" required placeholder="Prénom Nom" value="<?php echo e(old('name')); ?>"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-rouge-alerte mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Email *</label>
                <input type="email" name="email" required placeholder="artiste@email.com" value="<?php echo e(old('email')); ?>"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-rouge-alerte mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Mot de passe *</label>
                <input type="password" name="password" required placeholder="Définir un mot de passe"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-rouge-alerte mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="text-xs text-titane mt-1">Le mot de passe sera communiqué à l'artiste</p>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Type de profil *</label>
                <div class="flex gap-3">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
                        <div
                            class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                            <span class="text-lg">🎨</span>
                            <p class="text-xs font-semibold text-ivoire-text mt-1">Tatoueur</p>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
                        <div
                            class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                            <span class="text-lg">💎</span>
                            <p class="text-xs font-semibold text-ivoire-text mt-1">Pierceur</p>
                        </div>
                    </label>
                </div>
            </div>
            <button type="submit"
                class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
                Créer l'artiste
            </button>
        </form>

        
        <form x-show="mode === 'invite'" action="<?php echo e(route('studio.artists.invite')); ?>" method="POST"
            class="bg-gris-fonde rounded-xl p-4 md:p-6 space-y-4">
            <?php echo csrf_field(); ?>
            <p class="text-xs text-titane">Envoyez une invitation par email. L'artiste créera son propre compte et sera
                automatiquement rattaché à votre studio.</p>

            <div>
                <label class="text-xs text-titane block mb-1">Email de l'artiste *</label>
                <input type="email" name="email" required placeholder="artiste@email.com" value="<?php echo e(old('email')); ?>"
                    class="w-full px-3 py-2.5 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text text-sm placeholder-titane focus:border-beige-peau focus:outline-none">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-xs text-rouge-alerte mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <div>
                <label class="text-xs text-titane block mb-1">Type de profil *</label>
                <div class="flex gap-3">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="artisan_type" value="tattooer" class="peer hidden" checked>
                        <div
                            class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                            <span class="text-lg">🎨</span>
                            <p class="text-xs font-semibold text-ivoire-text mt-1">Tatoueur</p>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="artisan_type" value="piercer" class="peer hidden">
                        <div
                            class="peer-checked:border-beige-peau peer-checked:bg-beige-peau/10 border-2 border-titane/30 rounded-xl p-3 text-center transition-colors">
                            <span class="text-lg">💎</span>
                            <p class="text-xs font-semibold text-ivoire-text mt-1">Pierceur</p>
                        </div>
                    </label>
                </div>
            </div>
            <button type="submit"
                class="w-full py-3 bg-beige-peau text-noir-profond rounded-xl font-semibold hover:bg-beige-peau/90 transition-colors active:scale-95">
                Envoyer l'invitation
            </button>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.studio', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\studio\artists-create.blade.php ENDPATH**/ ?>
<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond flex items-center justify-center px-4 py-12">
        <div class="max-w-2xl w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="<?php echo e(route('register')); ?>" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
                    ← Retour au choix du rôle
                </a>
                <h1 class="text-beige-peau font-display text-2xl font-bold">
                    Inscription Tatoueur Professionnel
                </h1>
                <p class="text-ivoire-text/70 text-sm mt-2">
                    SIRET obligatoire pour vous inscrire
                </p>
            </div>

            <!-- Formulaire -->
            <form action="<?php echo e(route('register.tattooer.submit')); ?>" method="POST"
                class="bg-gris-fonde rounded-xl border border-cuivre/40 shadow-md shadow-cuivre/20 p-6 md:p-8 space-y-6">
                <?php echo csrf_field(); ?>

                <!-- Champ caché pour le plan -->
                <input type="hidden" name="plan" value="<?php echo e(request('plan', 'starter')); ?>" />

                <!-- Affichage des erreurs de validation -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                    <div class="bg-rouge-alerte/10 border border-rouge-alerte/30 rounded-lg p-4 mb-4">
                        <div class="text-rouge-alerte text-sm">
                            <ul class="list-disc list-inside space-y-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <!-- SECTION 1 : SIRET (PRIORITÉ) -->
                <div class="border-b border-titane/20 pb-6">
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        1. Informations professionnelles
                    </h2>

                    <!-- SIRET -->
                    <div>
                        <label class="block text-ivoire-text text-sm font-semibold mb-2">
                            Numéro SIRET <span class="text-rouge-alerte">*</span> <span
                                class="text-ambre-warning/80 font-normal">(14 chiffres)</span>
                        </label>
                        <input type="text" name="siret" required maxlength="14" placeholder="12345678901234"
                            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors font-mono">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['siret'];
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
                </div>

                <!-- SECTION 2 : COMPTE -->
                <div class="border-b border-titane/20 pb-6">
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        2. Informations de compte
                    </h2>

                    <div class="space-y-4">
                        <!-- Prénom -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Prénom <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="text" name="first_name" required placeholder="Jean"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Nom -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Nom <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="text" name="last_name" required placeholder="Dupont"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Pseudo -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Pseudo <span class="text-ivoire-text/50 font-normal">(affiché publiquement)</span>
                            </label>
                            <input type="text" name="pseudo" placeholder="Ex: JohnDoeTattoo"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                            <p class="text-ivoire-text/50 text-xs mt-1">
                                Ce pseudo sera affiché sur votre profil public et dans les messages
                            </p>
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Email professionnel <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="email" name="email" required
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Mot de passe <span class="text-rouge-alerte">*</span> <span
                                    class="text-ivoire-text/50 font-normal">(8 caractères minimum, 1
                                    majuscule, 1 chiffre, 1 caractère spécial)</span>
                            </label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                                placeholder="•••••••••">
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

                        <!-- Password confirmation -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Confirmer mot de passe <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                                placeholder="•••••••••">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
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
                    </div>
                </div>

                <!-- SECTION 3 : LOCALISATION -->
                <div>
                    <h2 class="text-ivoire-text font-display font-bold text-lg mb-4">
                        3. Localisation
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Ville -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Ville <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="text" name="city" required
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>

                        <!-- Code postal -->
                        <div>
                            <label class="block text-ivoire-text text-sm font-semibold mb-2">
                                Code postal <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="text" name="postal_code" required
                                class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div class="mt-4">
                        <label class="block text-ivoire-text text-sm font-semibold mb-2">
                            Téléphone <span class="text-ivoire-text/60 font-normal">(optionnel)</span>
                        </label>
                        <input type="tel" name="phone"
                            class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                    </div>
                </div>

                <!-- Submit -->
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['type' => 'submit','variant' => 'primary','size' => 'md','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','variant' => 'primary','size' => 'md','class' => 'w-full']); ?>
                    Créer mon compte
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $attributes = $__attributesOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__attributesOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala8bb031a483a05f647cb99ed3a469847)): ?>
<?php $component = $__componentOriginala8bb031a483a05f647cb99ed3a469847; ?>
<?php unset($__componentOriginala8bb031a483a05f647cb99ed3a469847); ?>
<?php endif; ?>

            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\auth\register-tattooer.blade.php ENDPATH**/ ?>
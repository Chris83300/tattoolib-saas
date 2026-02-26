<?php $__env->startSection('content'); ?>
    <div class="min-h-screen bg-noir-profond flex items-center px-4 py-12">
        <div class="max-w-md w-full">
            <!-- Header -->
            <div class="text-center mb-8">
                <a href="<?php echo e(route('register')); ?>" class="text-ivoire-text/70 text-sm hover:text-beige-peau mb-4 inline-block">
                    ← Retour au choix du rôle
                </a>
                <h1 class="text-beige-peau font-display text-2xl font-bold">
                    Inscription Client
                </h1>
                <p class="text-ivoire-text/70 text-sm mt-2">
                    Créez votre compte pour accéder à la plateforme
                </p>
            </div>

            <!-- Formulaire -->
            <form action="<?php echo e(route('register.client.submit')); ?>" method="POST" class="bg-gris-fonde justify-center border border-cuivre/40 shadow-md shadow-cuivre/20 rounded-xl p-6 space-y-4">
                <?php echo csrf_field(); ?>

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

                <!-- Nom complet -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Nom <span class="text-rouge-alerte">*</span>
                    </label>
                    <input type="text" name="last_name" placeholder="Ex: Wick"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                </div>
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Prénom <span class="text-rouge-alerte">*</span>
                    </label>
                    <input type="text" name="first_name" placeholder="Ex: Jhon"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                </div>

                <!-- Pseudo -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Pseudo <span class="text-ivoire-text/50 font-normal">(affiché publiquement)</span>
                    </label>
                    <input type="text" name="pseudo" required placeholder="Ex: Client123"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                    <p class="text-ivoire-text/50 text-xs mt-1">
                        Ce pseudo sera affiché sur votre profil public et dans les messages
                    </p>
                </div>

                
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Date de naissance <span class="text-rouge-alerte">*</span> <span class="text-ambre-warning/80 font-normal">(Minimum 16 ans)</span>
                    </label>
                    <input type="date" name="birth_date" required placeholder="Ex: 10/08/2004"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors">
                    <p class="text-ivoire-text/50 text-xs mt-1">
                        Votre date de naissance ne sera pas affichée sur votre profil public mais sera utilisée pour
                        vérifier votre âge.
                    </p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Email <span class="text-rouge-alerte">*</span>
                    </label>
                    <input type="email" name="email" required
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="jean.dupont@email.com">
                </div>

                <!-- Téléphone (optionnel) -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Téléphone <span class="text-ivoire-text/60">(optionnel)</span>
                    </label>
                    <input type="tel" name="phone"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="06 12 34 56 78">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-ivoire-text text-sm font-semibold mb-2">
                        Mot de passe <span class="text-rouge-alerte">*</span> <span class="text-ivoire-text/50 font-normal">(8 caractères minimum, 1 majuscule, 1
                            chiffre, 1 caractère spécial)</span>
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
                        Confirmer mot de passe <span class="text-rouge-alerte">*</span> <span class="text-ivoire-text/50 font-normal">(8 caractères minimum)</span>
                    </label>
                    <input type="password" name="password_confirmation" required minlength="8"
                        class="w-full bg-noir-profond text-ivoire-text px-4 py-3 rounded-lg border border-titane/30 focus:border-beige-peau focus:ring-2 focus:ring-beige-peau focus:ring-opacity-50 transition-colors"
                        placeholder="••••••••">
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

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/auth/register-client.blade.php ENDPATH**/ ?>
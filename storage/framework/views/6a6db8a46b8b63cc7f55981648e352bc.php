<div x-data="{ showPrompt: false, deferredPrompt: null }"
     x-init="
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;

            // Ne montrer que si pas déjà installé et après 30 secondes
            setTimeout(() => {
                if (!localStorage.getItem('pwa-installed') && !localStorage.getItem('pwa-dismissed')) {
                    showPrompt = true;
                }
            }, 30000);
        });

        window.addEventListener('appinstalled', () => {
            localStorage.setItem('pwa-installed', 'true');
            showPrompt = false;
        });
     "
     x-show="showPrompt && !localStorage.getItem('pwa-installed')"
     x-cloak
     class="fixed bottom-20 left-4 right-4 md:left-auto md:right-4 md:w-80 bg-gris-fonde border border-cuivre/20 rounded-xl shadow-lg shadow-cuivre/20 z-40 p-4"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4">

    <div class="flex items-start gap-3">
        <!-- Icon -->
        <div class="flex items-center justify-center flex-shrink-0">
            <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Ink&Pik" class="w-10 h-10">
        </div>

        <!-- Content -->
        <div class="flex-1">
            <h3 class="text-beige-peau font-semibold mb-1">Installez Ink&Pik</h3>
            <p class="text-ivoire-text/90 text-sm mb-3">
                Accédez à l'application en un clic, même hors ligne
            </p>

            <!-- Actions -->
            <div class="flex gap-2">
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'primary','size' => 'md','class' => 'w-1/2','@click' => 'deferredPrompt.prompt(); deferredPrompt.userChoice.then((choiceResult) => { showPrompt = false; });']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','size' => 'md','class' => 'w-1/2','@click' => 'deferredPrompt.prompt(); deferredPrompt.userChoice.then((choiceResult) => { showPrompt = false; });']); ?>
                    Installer
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
                <?php if (isset($component)) { $__componentOriginala8bb031a483a05f647cb99ed3a469847 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala8bb031a483a05f647cb99ed3a469847 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.ui.button','data' => ['variant' => 'secondary','size' => 'md','@click' => 'showPrompt = false; localStorage.setItem(\'pwa-dismissed\', \'true\');']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('ui.button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'secondary','size' => 'md','@click' => 'showPrompt = false; localStorage.setItem(\'pwa-dismissed\', \'true\');']); ?>
                    Plus tard
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
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/partials/pwa-install-prompt.blade.php ENDPATH**/ ?>
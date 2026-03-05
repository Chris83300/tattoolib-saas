<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'feature' => 'cette fonctionnalité',
    'blur' => true,
    'compact' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'feature' => 'cette fonctionnalité',
    'blur' => true,
    'compact' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $user = auth()->user();
    $isAllowed = false;
    $subscribeRoute = 'studio.subscribe'; // Route par défaut pour les studios

    // Studio owner — TOUJOURS PRO par définition (vérifier en premier)
    if ($user && $user->isStudioOwner()) {
        $isAllowed = true;
        $subscribeRoute = 'studio.subscribe';
    }
    // Artiste rattaché à un studio — hérite du PRO du studio
    elseif ($user && $user->hasRole('studio_artist')) {
        $isAllowed = true;
    }
    // Tattooer indépendant
    elseif ($user && $user->tattooer) {
        $isAllowed = $user->tattooer->isPro();
        $subscribeRoute = 'tattooer.subscription.plans';
    }
    // Pierceur indépendant
    elseif ($user && $user->piercer) {
        $isAllowed = $user->piercer->isPro();
        $subscribeRoute = 'tattooer.subscription.plans';
    }
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAllowed): ?>
    
    <?php echo e($slot); ?>

<?php else: ?>
    
    <div class="relative">
        
        <div
            class="<?php echo e($blur ? 'blur-sm pointer-events-none select-none' : 'opacity-40 pointer-events-none select-none'); ?>">
            <?php echo e($slot); ?>

        </div>

        
        <div class="absolute inset-0 flex items-center justify-center z-10">
            <div
                class="bg-gris-fonde/95 backdrop-blur-sm border border-beige-peau/30 rounded-2xl p-6 text-center max-w-sm mx-4 shadow-xl">
                
                <div class="w-12 h-12 bg-beige-peau/20 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-beige-peau" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($compact): ?>
                    
                    <p class="text-sm text-ivoire-text/80 mb-3">
                        <strong class="text-beige-peau">PRO</strong> requis pour <?php echo e($feature); ?>

                    </p>
                    <a href="<?php echo e(route($subscribeRoute)); ?>"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-beige-peau text-noir-profond rounded-lg text-sm font-bold hover:bg-beige-peau/90 transition-colors">
                        🚀 Passer PRO
                    </a>
                <?php else: ?>
                    
                    <h4 class="text-lg font-bold text-ivoire-text mb-1">Fonctionnalité PRO</h4>
                    <p class="text-sm text-ivoire-text/70 mb-4">
                        Passez au plan PRO pour accéder à <?php echo e($feature); ?>.
                        <br>
                        <span class="text-beige-peau font-semibold">Commission 0%</span> + outils professionnels.
                    </p>
                    <a href="<?php echo e(route($subscribeRoute)); ?>"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-beige-peau text-noir-profond rounded-xl text-sm font-bold hover:bg-beige-peau/90 transition-colors active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Passer PRO —
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user && $user->isStudioOwner()): ?>
                            59.99€/mois
                        <?php else: ?>
                            29.99€/mois
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </a>
                    <p class="text-xs text-titane mt-2">Annulable à tout moment</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/components/pro-gate.blade.php ENDPATH**/ ?>
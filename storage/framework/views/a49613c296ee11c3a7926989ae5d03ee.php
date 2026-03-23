<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'artist', 'year' => now()->year]));

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

foreach (array_filter((['type' => 'artist', 'year' => now()->year]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div class="flex flex-wrap items-center gap-3">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($type === 'artist'): ?>
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gris-fonde text-titane hover:text-beige-peau border border-titane/20 hover:border-beige-peau/30 rounded-lg transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter
            </button>
            <div
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-60 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-50 py-1"
            >
                <a href="<?php echo e(route('export.artist.transactions', ['format' => 'xlsx'])); ?>"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (Excel)</span>
                </a>
                <a href="<?php echo e(route('export.artist.transactions', ['format' => 'csv'])); ?>"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (CSV)</span>
                </a>
                <div class="border-t border-titane/10 my-1"></div>
                <a href="<?php echo e(route('export.artist.full', ['year' => $year])); ?>"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Comptabilité <?php echo e($year); ?> (Excel)</span>
                </a>
                <a href="<?php echo e(route('export.artist.monthly', ['year' => $year, 'format' => 'csv'])); ?>"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Récap mensuel <?php echo e($year); ?> (CSV)</span>
                </a>
                <a href="<?php echo e(route('export.artist.monthly', ['year' => $year - 1, 'format' => 'csv'])); ?>"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Récap mensuel <?php echo e($year - 1); ?> (CSV)</span>
                </a>
            </div>
        </div>
    <?php elseif($type === 'studio'): ?>
        <div x-data="{ open: false }" class="relative">
            <button
                @click="open = !open"
                class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gris-fonde text-titane hover:text-beige-peau border border-titane/20 hover:border-beige-peau/30 rounded-lg transition-colors"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Exporter Studio
            </button>
            <div
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-60 bg-gris-fonde border border-titane/20 rounded-lg shadow-lg z-50 py-1"
            >
                <a href="<?php echo e(route('export.studio.transactions', ['format' => 'xlsx'])); ?>"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (Excel)</span>
                </a>
                <a href="<?php echo e(route('export.studio.transactions', ['format' => 'csv'])); ?>"
                   class="flex items-center gap-2 px-4 py-2 text-sm text-titane hover:bg-noir-profond hover:text-beige-peau">
                    <span>Transactions (CSV)</span>
                </a>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/partials/export-buttons.blade.php ENDPATH**/ ?>
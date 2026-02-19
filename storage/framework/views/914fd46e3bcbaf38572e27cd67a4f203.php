<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'conformite',
    'size' => 'md'
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
    'variant' => 'conformite',
    'size' => 'md'
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $variantClasses = [
        'conformite' => 'bg-vert-succes text-noir-profond',
        'warning' => 'bg-ambre-warning text-noir-profond',
        'danger' => 'bg-rouge-alerte text-ivoire-text',
        'info' => 'bg-titane text-ivoire-text',
        'premium' => 'bg-beige-peau text-noir-profond',
    ];

    $sizeClasses = [
        'sm' => 'px-2 py-1 text-xs',
        'md' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-2 text-base',
    ];

    $classes = implode(' ', [
        'inline-flex items-center font-semibold rounded-full',
        $variantClasses[$variant] ?? $variantClasses['info'],
        $sizeClasses[$size] ?? $sizeClasses['md'],
    ]);
?>

<span <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</span>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/components/ui/badge.blade.php ENDPATH**/ ?>
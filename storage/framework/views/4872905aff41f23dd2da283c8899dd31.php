<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'variant' => 'dark',
    'padding' => 'normal',
    'hover' => false
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
    'variant' => 'dark',
    'padding' => 'normal',
    'hover' => false
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
        'dark' => 'bg-gris-fonde text-ivoire-text border border-titane/20',
        'light' => 'bg-ivoire-text text-noir-text border border-titane/30',
        'bordered' => 'bg-transparent text-ivoire-text border border-titane/30',
    ];

    $paddingClasses = [
        'none' => 'p-0',
        'sm' => 'p-3',
        'normal' => 'p-4',
        'lg' => 'p-6',
    ];

    $classes = implode(' ', [
        'rounded-lg shadow-card',
        $variantClasses[$variant] ?? $variantClasses['dark'],
        $paddingClasses[$padding] ?? $paddingClasses['normal'],
        $hover ? 'hover:shadow-card-hover transition-shadow duration-200' : '',
    ]);
?>

<div <?php echo e($attributes->merge(['class' => $classes])); ?>>
    <?php echo e($slot); ?>

</div>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views\components\ui\card.blade.php ENDPATH**/ ?>
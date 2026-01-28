@props([
    'variant' => 'dark',
    'padding' => 'normal',
    'hover' => false
])

@php
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
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

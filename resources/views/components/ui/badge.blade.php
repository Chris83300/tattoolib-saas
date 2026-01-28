@props([
    'variant' => 'conformite',
    'size' => 'md'
])

@php
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
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>

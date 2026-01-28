@props([
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'loading' => false,
    'fullWidth' => false,
    'type' => 'button'
])

@php
    $variantClasses = [
        'primary' => 'bg-beige-peau text-noir-profond hover:bg-beige-peau/90 focus:ring-beige-peau',
        'secondary' => 'bg-gris-fonde text-ivoire-text border border-titane hover:bg-titane/20 focus:ring-titane',
        'ghost' => 'bg-transparent text-ivoire-text hover:bg-beige-peau/10 focus:ring-beige-peau',
        'danger' => 'bg-rouge-alerte text-ivoire-text hover:bg-rouge-alerte/90 focus:ring-rouge-alerte',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];

    $classes = implode(' ', [
        'inline-flex items-center justify-center font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-noir-profond',
        $variantClasses[$variant] ?? $variantClasses['primary'],
        $sizeClasses[$size] ?? $sizeClasses['md'],
        $fullWidth ? 'w-full' : '',
        $disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
        $loading ? 'opacity-75 cursor-wait' : '',
    ]);
@endphp

<button {{ $attributes->merge(['type' => $type, 'disabled' => $disabled || $loading, 'class' => $classes]) }}>
    
    @if($loading)
        <!-- Loading Spinner -->
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @endif
    
    {{ $slot }}
    
</button>

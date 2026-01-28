@props([
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'required' => false,
    'error' => null,
    'help' => null,
    'disabled' => false
])

@php
    $inputClasses = implode(' ', [
        'w-full bg-gris-fonde text-ivoire-text px-4 py-3 rounded-lg',
        'border border-titane/30 focus:border-beige-peau',
        'focus:outline-none focus:ring-2 focus:ring-beige-peau focus:ring-offset-2 focus:ring-offset-noir-profond',
        'transition-all duration-200',
        $disabled ? 'opacity-50 cursor-not-allowed' : '',
        $error ? 'border-rouge-alerte focus:ring-rouge-alerte' : '',
    ]);
@endphp

<div class="space-y-2">
    @if($label)
        <label {{ $attributes->merge(['class' => 'block text-ivoire-text font-medium text-sm']) }}>
            {{ $label }}
            @if($required) <span class="text-rouge-alerte">*</span> @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => $inputClasses]) }}
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
    >
    
    @if($error)
        <p class="text-rouge-alerte text-sm">{{ $error }}</p>
    @endif
    
    @if($help)
        <p class="text-ivoire-text/50 text-sm">{{ $help }}</p>
    @endif
</div>

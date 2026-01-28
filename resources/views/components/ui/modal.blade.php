@props([
    'id' => null,
    'title' => null,
    'size' => 'md',
    'show' => false
])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];

    $modalClasses = implode(' ', [
        'fixed inset-0 z-50 overflow-y-auto',
        'bg-noir-profond/95 backdrop-blur-sm',
        'flex items-center justify-center p-4',
        'min-h-screen'
    ]);
@endphp

<div x-data="{ open: {{ $show ? 'true' : 'false' }} }" 
     x-show="open" 
     x-cloak
     @keydown.escape.window="open = false"
     class="{{ $modalClasses }}"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0">
    
    <!-- Modal Content -->
    <div class="bg-gris-fonde rounded-lg shadow-lg w-full {{ $sizeClasses[$size] ?? $sizeClasses['md'] }}"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">
        
        <!-- Header -->
        @if($title)
        <div class="flex items-center justify-between p-6 border-b border-titane/20">
            <h3 class="text-ivoire-text font-display font-bold text-xl">{{ $title }}</h3>
            <button @click="open = false" class="text-ivoire-text/50 hover:text-ivoire-text transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        @endif
        
        <!-- Body -->
        <div class="{{ $title ? 'p-6 pt-0' : 'p-6' }}">
            {{ $slot }}
        </div>
        
        <!-- Footer (optional) -->
        @if(isset($footer))
        <div class="p-6 border-t border-titane/20 bg-noir-profond/50">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>

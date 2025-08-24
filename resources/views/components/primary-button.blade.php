@props(['type' => 'submit', 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-150';
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ][$size];
    $classes = "{$baseClasses} {$sizeClasses} bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 active:bg-primary-800";
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>

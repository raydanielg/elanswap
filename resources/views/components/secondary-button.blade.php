@props(['type' => 'button', 'size' => 'md'])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-150 border';
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ][$size];
    $classes = "{$baseClasses} {$sizeClasses} border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-primary-500 active:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed";
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>

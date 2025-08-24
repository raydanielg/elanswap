@props(['disabled' => false, 'hasError' => false])

@php
    $baseClasses = 'block w-full rounded-md shadow-sm focus:ring-2 focus:ring-offset-1 transition duration-150 ease-in-out sm:text-sm';
    $stateClasses = $hasError 
        ? 'border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500' 
        : 'border-gray-300 placeholder-gray-400 focus:border-primary-500 focus:ring-primary-500';
    $disabledClasses = $disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white';
    $classes = "{$baseClasses} {$stateClasses} {$disabledClasses}";
@endphp

<input 
    @disabled($disabled) 
    {{ $attributes->merge(['class' => $classes]) }}
>

@props(['value', 'required' => false])

@php
    $classes = 'block text-sm font-medium text-gray-700' . ($required ? ' after:content-["*"] after:ml-0.5 after:text-red-500' : '');
@endphp

<label {{ $attributes->merge(['class' => $classes]) }}>
    {{ $value ?? $slot }}
</label>

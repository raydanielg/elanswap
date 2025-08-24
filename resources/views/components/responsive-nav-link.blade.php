@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2 text-base font-medium text-white bg-primary-700 rounded-md transition duration-150 ease-in-out hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500'
            : 'block w-full px-4 py-2 text-base font-medium text-blue-100 rounded-md transition duration-150 ease-in-out hover:bg-primary-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 border-indigo-400 text-sm font-medium leading-5 text-gray-900 focus:outline-none transition'
            : 'inline-flex items-center px-1 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 py-4 border-l-4 border-indigo-400 text-sm font-medium leading-5 text-indigo-700 bg-indigo-50 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 py-4 border-l-4 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>

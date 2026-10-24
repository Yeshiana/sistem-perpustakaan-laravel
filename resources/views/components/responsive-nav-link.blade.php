@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 rounded-lg text-start text-sm font-semibold text-white bg-gradient-to-r from-sky-400 to-indigo-600 transition-all duration-300'
            : 'block w-full ps-3 pe-4 py-2 rounded-lg text-start text-sm font-medium text-slate-600 hover:text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-600 transition-all duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
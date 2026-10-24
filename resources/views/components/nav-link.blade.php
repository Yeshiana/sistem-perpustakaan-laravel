@props(['active'])

@php
$classes = ($active ?? false)
            ? 'relative inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white bg-gradient-to-r from-sky-400 to-indigo-600 shadow-sm transition-all duration-300'
            : 'relative inline-flex items-center px-4 py-2 rounded-full text-sm font-medium text-slate-500 hover:text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-600 hover:shadow-sm transition-all duration-300 ease-out active:brightness-95';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
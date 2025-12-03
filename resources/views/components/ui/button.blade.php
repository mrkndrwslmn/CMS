@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
    'disabled' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium transition-all duration-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $variants = [
        'primary' => 'bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-500 shadow-sm hover:shadow-md',
        'secondary' => 'bg-neutral-100 text-neutral-700 hover:bg-neutral-200 focus:ring-neutral-400',
        'outline' => 'border border-neutral-200 bg-white text-neutral-700 hover:bg-neutral-50 hover:border-neutral-300 focus:ring-primary-500',
        'ghost' => 'text-neutral-600 hover:bg-neutral-100 hover:text-neutral-900 focus:ring-neutral-400',
        'danger' => 'bg-error-500 text-white hover:bg-error-600 focus:ring-error-500 shadow-sm hover:shadow-md',
        'success' => 'bg-success-500 text-white hover:bg-success-600 focus:ring-success-500 shadow-sm hover:shadow-md',
    ];
    
    $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-base',
        'xl' => 'px-6 py-3.5 text-base',
    ];
    
    $classes = $baseClasses . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif

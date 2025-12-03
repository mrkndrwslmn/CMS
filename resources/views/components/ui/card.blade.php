@props([
    'padding' => 'md',
    'shadow' => 'sm',
    'hover' => false,
])

@php
    $baseClasses = 'bg-white rounded-2xl border border-neutral-100';
    
    $paddings = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
    
    $shadows = [
        'none' => '',
        'sm' => 'shadow-sm',
        'md' => 'shadow-md',
        'lg' => 'shadow-lg',
    ];
    
    $hoverClass = $hover ? 'transition-all duration-300 hover:shadow-md hover:-translate-y-1' : '';
    
    $classes = $baseClasses . ' ' . ($paddings[$padding] ?? $paddings['md']) . ' ' . ($shadows[$shadow] ?? $shadows['sm']) . ' ' . $hoverClass;
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>

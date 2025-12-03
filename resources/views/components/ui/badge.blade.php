@props([
    'type' => 'default',
    'size' => 'md',
    'dot' => false,
])

@php
    $baseClasses = 'inline-flex items-center font-medium rounded-full';
    
    $types = [
        'default' => 'bg-neutral-100 text-neutral-700',
        'primary' => 'bg-primary-50 text-primary-700',
        'success' => 'bg-success-50 text-success-700',
        'warning' => 'bg-warning-50 text-warning-700',
        'error' => 'bg-error-50 text-error-700',
    ];
    
    $sizes = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];
    
    $dotColors = [
        'default' => 'bg-neutral-400',
        'primary' => 'bg-primary-400',
        'success' => 'bg-success-400',
        'warning' => 'bg-warning-400',
        'error' => 'bg-error-400',
    ];
    
    $classes = $baseClasses . ' ' . ($types[$type] ?? $types['default']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    @if($dot)
        <span class="mr-1.5 h-1.5 w-1.5 rounded-full {{ $dotColors[$type] ?? $dotColors['default'] }}"></span>
    @endif
    {{ $slot }}
</span>

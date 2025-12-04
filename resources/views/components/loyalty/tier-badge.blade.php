@props([
    'tier' => 'bronze',
    'size' => 'md',
    'showIcon' => true,
])

@php
    $tierConfig = [
        'bronze' => [
            'bg' => 'bg-orange-50',
            'text' => 'text-orange-700',
            'border' => 'border-orange-200',
            'icon' => 'text-orange-500',
        ],
        'silver' => [
            'bg' => 'bg-neutral-100',
            'text' => 'text-neutral-700',
            'border' => 'border-neutral-200',
            'icon' => 'text-neutral-500',
        ],
        'gold' => [
            'bg' => 'bg-warning-50',
            'text' => 'text-warning-700',
            'border' => 'border-warning-200',
            'icon' => 'text-warning-500',
        ],
        'platinum' => [
            'bg' => 'bg-primary-50',
            'text' => 'text-primary-700',
            'border' => 'border-primary-200',
            'icon' => 'text-primary-500',
        ],
    ];

    $sizes = [
        'sm' => 'px-2.5 py-1 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-5 py-2.5 text-lg',
        'xl' => 'px-5 py-2.5 text-xl',
    ];

    $iconSizes = [
        'sm' => 'w-3 h-3',
        'md' => 'w-4 h-4',
        'lg' => 'w-5 h-5',
        'xl' => 'w-5 h-5',
    ];

    $config = $tierConfig[$tier] ?? $tierConfig['bronze'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $iconSize = $iconSizes[$size] ?? $iconSizes['md'];
@endphp

<div {{ $attributes->merge(['class' => "inline-flex items-center justify-center gap-2 rounded-xl font-semibold border {$config['bg']} {$config['text']} {$config['border']} {$sizeClass}"]) }}>
    @if($showIcon)
        <x-lucide-award class="{{ $iconSize }} {{ $config['icon'] }}" />
    @endif
    {{ ucfirst($tier) }}
</div>

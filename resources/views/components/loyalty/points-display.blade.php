@props([
    'points',
    'label' => null,
    'type' => 'default', // default, earned, redeemed, available
    'size' => 'md',
    'showCurrency' => false,
    'conversionRate' => 1, // Points to currency conversion
])

@php
    $typeConfig = [
        'default' => 'text-neutral-800',
        'earned' => 'text-success-600',
        'redeemed' => 'text-error-600',
        'available' => 'text-neutral-800',
        'expiring' => 'text-warning-600',
    ];

    $sizes = [
        'sm' => 'text-lg',
        'md' => 'text-2xl',
        'lg' => 'text-4xl',
        'xl' => 'text-5xl',
    ];

    $labelSizes = [
        'sm' => 'text-xs',
        'md' => 'text-sm',
        'lg' => 'text-sm',
        'xl' => 'text-base',
    ];

    $colorClass = $typeConfig[$type] ?? $typeConfig['default'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $labelSizeClass = $labelSizes[$size] ?? $labelSizes['md'];
    
    $formattedPoints = number_format($points);
    $currencyValue = $points * $conversionRate;
@endphp

<div {{ $attributes->merge(['class' => 'text-center']) }}>
    @if($label)
        <p class="{{ $labelSizeClass }} font-medium text-neutral-500 mb-1">{{ $label }}</p>
    @endif
    
    <p class="{{ $sizeClass }} font-semibold {{ $colorClass }}">
        {{ $formattedPoints }}
    </p>
    
    @if($showCurrency)
        <p class="{{ $labelSizeClass }} text-neutral-400 mt-1">
            ≈ ₱{{ number_format($currencyValue, 2) }}
        </p>
    @endif
    
    @if($slot->isNotEmpty())
        <div class="{{ $labelSizeClass }} text-neutral-400 mt-1">
            {{ $slot }}
        </div>
    @endif
</div>

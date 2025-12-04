@props([
    'current' => 0,
    'target' => 100,
    'currentTier' => 'bronze',
    'nextTier' => null,
    'showLabels' => true,
    'height' => 'md', // sm, md, lg
])

@php
    $percentage = $target > 0 ? min(100, ($current / $target) * 100) : 0;
    
    $heights = [
        'sm' => 'h-1.5',
        'md' => 'h-2.5',
        'lg' => 'h-4',
    ];
    
    $heightClass = $heights[$height] ?? $heights['md'];
    
    $tierColors = [
        'bronze' => 'from-orange-400 to-orange-500',
        'silver' => 'from-neutral-400 to-neutral-500',
        'gold' => 'from-warning-400 to-warning-500',
        'platinum' => 'from-primary-400 to-primary-600',
    ];
    
    $barColor = $tierColors[$currentTier] ?? $tierColors['bronze'];
@endphp

<div {{ $attributes }}>
    @if($showLabels)
        <div class="flex justify-between text-sm font-medium text-neutral-500 mb-2">
            <span>{{ number_format($current) }} points</span>
            @if($nextTier)
                <span>{{ number_format($target) }} needed</span>
            @endif
        </div>
    @endif
    
    <div class="w-full bg-neutral-100 rounded-full {{ $heightClass }} overflow-hidden">
        <div class="bg-gradient-to-r {{ $barColor }} {{ $heightClass }} rounded-full transition-all duration-500 ease-out" 
             style="width: {{ $percentage }}%">
        </div>
    </div>
    
    @if($nextTier && $showLabels)
        <div class="flex justify-between items-center mt-2">
            <span class="text-xs text-neutral-400">{{ ucfirst($currentTier) }}</span>
            <span class="text-xs font-medium text-primary-600">{{ ucfirst($nextTier) }}</span>
        </div>
    @endif
</div>

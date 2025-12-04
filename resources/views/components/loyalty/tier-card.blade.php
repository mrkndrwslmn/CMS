@props([
    'tier' => 'bronze',
    'benefits' => [],
    'isCurrent' => false,
    'isLocked' => false,
    'pointsRequired' => 0,
])

@php
    $tierConfig = [
        'bronze' => [
            'gradient' => 'from-orange-400 to-orange-600',
            'bg' => 'bg-orange-50',
            'border' => 'border-orange-200',
            'text' => 'text-orange-700',
        ],
        'silver' => [
            'gradient' => 'from-neutral-400 to-neutral-600',
            'bg' => 'bg-neutral-100',
            'border' => 'border-neutral-300',
            'text' => 'text-neutral-700',
        ],
        'gold' => [
            'gradient' => 'from-warning-400 to-warning-600',
            'bg' => 'bg-warning-50',
            'border' => 'border-warning-200',
            'text' => 'text-warning-700',
        ],
        'platinum' => [
            'gradient' => 'from-primary-400 to-primary-600',
            'bg' => 'bg-primary-50',
            'border' => 'border-primary-200',
            'text' => 'text-primary-700',
        ],
    ];

    $config = $tierConfig[$tier] ?? $tierConfig['bronze'];
@endphp

<div {{ $attributes->merge(['class' => "rounded-2xl border shadow-sm overflow-hidden " . ($isCurrent ? "ring-2 ring-primary-500 {$config['border']}" : ($isLocked ? 'border-neutral-200 opacity-75' : $config['border']))]) }}>
    {{-- Header --}}
    <div class="bg-gradient-to-r {{ $config['gradient'] }} p-4 text-white">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <x-lucide-award class="w-6 h-6" />
                <h4 class="text-lg font-semibold">{{ ucfirst($tier) }}</h4>
            </div>
            @if($isCurrent)
                <span class="text-xs font-medium bg-white/20 px-2 py-1 rounded-full">Current</span>
            @elseif($isLocked)
                <x-lucide-lock class="w-4 h-4" />
            @endif
        </div>
        @if($pointsRequired > 0)
            <p class="text-sm text-white/80 mt-1">{{ number_format($pointsRequired) }}+ lifetime points</p>
        @endif
    </div>

    {{-- Benefits List --}}
    <div class="p-4 {{ $config['bg'] }}">
        @if(count($benefits) > 0)
            <ul class="space-y-2">
                @foreach($benefits as $benefit)
                    <li class="flex items-start gap-2 text-sm">
                        @if($isLocked)
                            <x-lucide-lock class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                            <span class="text-neutral-500">{{ $benefit }}</span>
                        @else
                            <x-lucide-check class="w-4 h-4 text-success-500 mt-0.5 flex-shrink-0" />
                            <span class="{{ $config['text'] }}">{{ $benefit }}</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-neutral-500 text-center py-2">No benefits listed</p>
        @endif
    </div>
</div>

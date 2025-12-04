@props([
    'transaction',
    'showDate' => true,
    'compact' => false,
])

@php
    $typeConfig = [
        'earned' => [
            'bg' => 'bg-success-50',
            'border' => 'border-success-100',
            'icon' => 'plus',
            'iconColor' => 'text-success-500',
            'pointsColor' => 'text-success-600',
        ],
        'redeemed' => [
            'bg' => 'bg-warning-50',
            'border' => 'border-warning-100',
            'icon' => 'minus',
            'iconColor' => 'text-warning-500',
            'pointsColor' => 'text-error-600',
        ],
        'expired' => [
            'bg' => 'bg-error-50',
            'border' => 'border-error-100',
            'icon' => 'clock',
            'iconColor' => 'text-error-500',
            'pointsColor' => 'text-error-600',
        ],
        'adjusted' => [
            'bg' => 'bg-primary-50',
            'border' => 'border-primary-100',
            'icon' => 'settings',
            'iconColor' => 'text-primary-500',
            'pointsColor' => $transaction->points > 0 ? 'text-success-600' : 'text-error-600',
        ],
        'refunded' => [
            'bg' => 'bg-success-50',
            'border' => 'border-success-100',
            'icon' => 'refresh-cw',
            'iconColor' => 'text-success-500',
            'pointsColor' => 'text-success-600',
        ],
    ];

    $config = $typeConfig[$transaction->transaction_type] ?? $typeConfig['adjusted'];
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center justify-between p-4 bg-neutral-50 rounded-xl border border-neutral-100 hover:border-primary-200 transition-all']) }}>
    <div class="flex items-center gap-3 flex-1 min-w-0">
        {{-- Icon --}}
        <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 {{ $config['bg'] }} {{ $config['border'] }} border">
            @switch($config['icon'])
                @case('plus')
                    <x-lucide-plus class="w-4 h-4 {{ $config['iconColor'] }}" />
                    @break
                @case('minus')
                    <x-lucide-minus class="w-4 h-4 {{ $config['iconColor'] }}" />
                    @break
                @case('clock')
                    <x-lucide-clock class="w-4 h-4 {{ $config['iconColor'] }}" />
                    @break
                @case('settings')
                    <x-lucide-settings class="w-4 h-4 {{ $config['iconColor'] }}" />
                    @break
                @case('refresh-cw')
                    <x-lucide-refresh-cw class="w-4 h-4 {{ $config['iconColor'] }}" />
                    @break
            @endswitch
        </div>

        {{-- Details --}}
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-neutral-700 truncate">{{ $transaction->description }}</p>
            @if($showDate)
                <p class="text-xs text-neutral-400">{{ $transaction->created_at->format('M d, Y g:i A') }}</p>
            @endif
            @if(!$compact && $transaction->source)
                <p class="text-xs text-neutral-400 mt-0.5">{{ ucwords(str_replace('_', ' ', $transaction->source)) }}</p>
            @endif
        </div>
    </div>

    {{-- Points --}}
    <div class="text-right ml-4">
        <p class="text-base font-semibold {{ $config['pointsColor'] }}">
            {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
        </p>
        @if(!$compact && isset($transaction->balance_after))
            <p class="text-xs text-neutral-400">Balance: {{ number_format($transaction->balance_after) }}</p>
        @endif
    </div>
</div>

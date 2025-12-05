@props([
    'label' => null,
    'value' => null,
    'icon' => null,
    'iconColor' => 'neutral', // neutral, primary, success, warning, error
    'trend' => null,
    'trendDirection' => null, // up, down, neutral
    'subtitle' => null,
    'href' => null,
    'linkText' => 'View all',
])

{{--
    Stat Card Component
    
    Usage:
    <x-ui.stat-card 
        label="Total Revenue"
        value="₱125,430"
        icon="philippine-peso"
        icon-color="primary"
        trend="+12%"
        trend-direction="up"
    />
    
    With subtitle:
    <x-ui.stat-card 
        label="Pending Requests"
        value="8"
        icon="clock"
        icon-color="warning"
        subtitle="3 require immediate attention"
    />
    
    With link:
    <x-ui.stat-card 
        label="Unread Messages"
        value="12"
        icon="message-square"
        icon-color="primary"
        href="/messages"
        link-text="View all"
    />
--}}

@php
    $iconBgColors = [
        'neutral' => 'bg-neutral-50',
        'primary' => 'bg-primary-50',
        'success' => 'bg-success-50',
        'warning' => 'bg-warning-50',
        'error' => 'bg-error-50',
    ];
    
    $iconTextColors = [
        'neutral' => 'text-neutral-400',
        'primary' => 'text-primary-500',
        'success' => 'text-success-500',
        'warning' => 'text-warning-500',
        'error' => 'text-error-500',
    ];
    
    $iconBg = $iconBgColors[$iconColor] ?? $iconBgColors['neutral'];
    $iconText = $iconTextColors[$iconColor] ?? $iconTextColors['neutral'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-neutral-100 shadow-sm p-6']) }}>
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-neutral-500">{{ $label }}</p>
            <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $value }}</p>
            
            {{-- Trend indicator --}}
            @if($trend && $trendDirection)
                <p class="flex items-center gap-1 text-sm mt-2 
                    @if($trendDirection === 'up') text-success-600
                    @elseif($trendDirection === 'down') text-error-600
                    @else text-neutral-500
                    @endif">
                    @if($trendDirection === 'up')
                        <x-lucide-trending-up class="w-4 h-4" />
                    @elseif($trendDirection === 'down')
                        <x-lucide-trending-down class="w-4 h-4" />
                    @else
                        <x-lucide-minus class="w-4 h-4" />
                    @endif
                    <span>{{ $trend }}</span>
                </p>
            @endif
            
            {{-- Subtitle --}}
            @if($subtitle)
                <p class="text-sm text-neutral-400 mt-2">{{ $subtitle }}</p>
            @endif
            
            {{-- Action link --}}
            @if($href)
                <a href="{{ $href }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 mt-2 transition-colors">
                    {{ $linkText }}
                    <x-lucide-arrow-right class="w-3 h-3" />
                </a>
            @endif
        </div>
        
        {{-- Icon --}}
        @if($icon)
            <div class="p-3 {{ $iconBg }} rounded-xl flex-shrink-0 ml-4">
                <x-dynamic-component :component="'lucide-' . $icon" class="w-5 h-5 {{ $iconText }}" />
            </div>
        @endif
    </div>
</div>

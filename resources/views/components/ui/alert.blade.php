@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => false,
])

{{--
    Alert Component (Inline Flash Messages)
    
    Usage:
    <x-ui.alert type="success">Your changes have been saved.</x-ui.alert>
    <x-ui.alert type="error" title="Error" dismissible>Something went wrong.</x-ui.alert>
    
    Types: info, success, warning, error
    
    Note: For popup dialogs/confirmations, use <x-ui.modal> or window.showModal() instead.
--}}

@php
    $types = [
        'info' => [
            'bg' => 'bg-primary-50',
            'border' => 'border-primary-200',
            'text' => 'text-primary-800',
            'icon' => 'text-primary-500',
        ],
        'success' => [
            'bg' => 'bg-success-50',
            'border' => 'border-success-200',
            'text' => 'text-success-800',
            'icon' => 'text-success-500',
        ],
        'warning' => [
            'bg' => 'bg-warning-50',
            'border' => 'border-warning-200',
            'text' => 'text-warning-800',
            'icon' => 'text-warning-500',
        ],
        'error' => [
            'bg' => 'bg-error-50',
            'border' => 'border-error-200',
            'text' => 'text-error-800',
            'icon' => 'text-error-500',
        ],
    ];
    
    $style = $types[$type] ?? $types['info'];
@endphp

<div 
    x-data="{ show: true }" 
    x-show="show"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    {{ $attributes->merge(['class' => "rounded-lg border p-4 {$style['bg']} {$style['border']}"]) }}
    role="alert"
>
    <div class="flex">
        <div class="flex-shrink-0">
            @if($type === 'info')
                <x-lucide-info class="h-5 w-5 {{ $style['icon'] }}" />
            @elseif($type === 'success')
                <x-lucide-check-circle class="h-5 w-5 {{ $style['icon'] }}" />
            @elseif($type === 'warning')
                <x-lucide-alert-triangle class="h-5 w-5 {{ $style['icon'] }}" />
            @elseif($type === 'error')
                <x-lucide-x-circle class="h-5 w-5 {{ $style['icon'] }}" />
            @endif
        </div>
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $style['text'] }}">{{ $title }}</h3>
            @endif
            <div class="text-sm {{ $style['text'] }} {{ $title ? 'mt-1' : '' }}">
                {{ $slot }}
            </div>
        </div>
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button 
                    type="button" 
                    @click="show = false"
                    class="-mx-1.5 -my-1.5 inline-flex rounded-lg p-1.5 {{ $style['text'] }} hover:bg-white/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-transparent focus:ring-current"
                >
                    <span class="sr-only">Dismiss</span>
                    <x-lucide-x class="h-5 w-5" />
                </button>
            </div>
        @endif
    </div>
</div>

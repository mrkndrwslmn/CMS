@props([
    'label' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Select an option',
    'options' => [],
])

@php
    $selectClasses = 'block w-full px-4 py-2.5 text-neutral-900 bg-white border rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-neutral-50 disabled:text-neutral-500 disabled:cursor-not-allowed appearance-none bg-no-repeat';
    
    $borderClasses = $error 
        ? 'border-error-300 focus:border-error-500 focus:ring-error-500/20' 
        : 'border-neutral-200 focus:border-primary-500 focus:ring-primary-500/20';
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'space-y-1.5']) }}>
    @if($label)
        <label class="block text-sm font-medium text-neutral-700">
            {{ $label }}
            @if($required)
                <span class="text-error-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <select 
            {{ $disabled ? 'disabled' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->except('class')->merge(['class' => $selectClasses . ' ' . $borderClasses . ' pr-10']) }}
        >
            @if($placeholder)
                <option value="" disabled selected>{{ $placeholder }}</option>
            @endif
            
            @if(count($options) > 0)
                @foreach($options as $value => $optionLabel)
                    <option value="{{ $value }}">{{ $optionLabel }}</option>
                @endforeach
            @else
                {{ $slot }}
            @endif
        </select>
        
        {{-- Dropdown arrow --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <x-lucide-chevron-down class="h-4 w-4 text-neutral-400" />
        </div>
    </div>
    
    @if($hint && !$error)
        <p class="text-sm text-neutral-500">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-error-600">{{ $error }}</p>
    @endif
</div>

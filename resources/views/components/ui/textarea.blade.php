@props([
    'label' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'rows' => 4,
])

@php
    $textareaClasses = 'block w-full px-4 py-2.5 text-neutral-900 bg-white border rounded-lg shadow-sm transition-all duration-200 placeholder:text-neutral-400 focus:outline-none focus:ring-2 focus:ring-offset-0 disabled:bg-neutral-50 disabled:text-neutral-500 disabled:cursor-not-allowed resize-y';
    
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
    
    <textarea 
        rows="{{ $rows }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $required ? 'required' : '' }}
        {{ $attributes->except('class')->merge(['class' => $textareaClasses . ' ' . $borderClasses]) }}
    >{{ $slot }}</textarea>
    
    @if($hint && !$error)
        <p class="text-sm text-neutral-500">{{ $hint }}</p>
    @endif
    
    @if($error)
        <p class="text-sm text-error-600">{{ $error }}</p>
    @endif
</div>

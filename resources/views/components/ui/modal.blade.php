@props([
    'name' => 'modal',
    'type' => 'info', // info, success, warning, error, confirm
    'title' => '',
    'message' => '',
    'confirmText' => 'OK',
    'cancelText' => null,
    'confirmVariant' => 'primary', // primary, danger
    'show' => false,
])

{{--
    Modal Dialog Component
    
    Usage:
    
    Basic alert:
    <x-ui.modal 
        name="success-modal"
        type="success"
        title="Payment Successful"
        message="Your payment has been processed."
        confirm-text="Done"
    />
    
    Confirmation with cancel:
    <x-ui.modal 
        name="delete-modal"
        type="confirm"
        title="Delete Project?"
        message="This action cannot be undone."
        confirm-text="Delete"
        cancel-text="Cancel"
        confirm-variant="danger"
    />
    
    Trigger with Alpine.js:
    <button @click="$dispatch('open-modal', 'success-modal')">Open</button>
--}}

@php
    $icons = [
        'info' => 'info',
        'success' => 'check-circle',
        'warning' => 'alert-triangle',
        'error' => 'x-circle',
        'confirm' => 'help-circle',
    ];
    
    $iconBgColors = [
        'info' => 'bg-primary-50',
        'success' => 'bg-success-50',
        'warning' => 'bg-warning-50',
        'error' => 'bg-error-50',
        'confirm' => 'bg-primary-50',
    ];
    
    $iconTextColors = [
        'info' => 'text-primary-500',
        'success' => 'text-success-500',
        'warning' => 'text-warning-500',
        'error' => 'text-error-500',
        'confirm' => 'text-primary-500',
    ];
    
    $icon = $icons[$type] ?? $icons['info'];
    $iconBg = $iconBgColors[$type] ?? $iconBgColors['info'];
    $iconText = $iconTextColors[$type] ?? $iconTextColors['info'];
@endphp

<div
    x-data="{ open: @js($show) }"
    x-show="open"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    aria-labelledby="modal-title-{{ $name }}"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm"
        @click="open = false"
    ></div>

    <!-- Modal -->
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div
            x-show="open"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="relative bg-white rounded-2xl shadow-lg w-full max-w-md p-6"
        >
            <!-- Icon -->
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 rounded-full {{ $iconBg }} flex items-center justify-center">
                    <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6 {{ $iconText }}" />
                </div>
            </div>

            <!-- Title -->
            @if($title)
                <h3 id="modal-title-{{ $name }}" class="text-lg font-semibold text-neutral-800 text-center mb-2">
                    {{ $title }}
                </h3>
            @endif

            <!-- Message -->
            @if($message)
                <p class="text-sm text-neutral-500 text-center mb-6">
                    {{ $message }}
                </p>
            @endif

            <!-- Custom Content Slot -->
            @if($slot->isNotEmpty())
                <div class="mb-6">
                    {{ $slot }}
                </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center justify-center gap-3">
                @if($cancelText)
                    <button
                        type="button"
                        @click="open = false; $dispatch('modal-cancelled', '{{ $name }}')"
                        class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-neutral-700 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors"
                    >
                        {{ $cancelText }}
                    </button>
                @endif

                <button
                    type="button"
                    @click="open = false; $dispatch('modal-confirmed', '{{ $name }}')"
                    @class([
                        'inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors',
                        'bg-primary-600 text-white hover:bg-primary-700' => $confirmVariant === 'primary',
                        'bg-error-600 text-white hover:bg-error-700' => $confirmVariant === 'danger',
                    ])
                >
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>

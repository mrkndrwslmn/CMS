@props(['variant' => 'primary', 'label' => 'Submit Request', 'icon' => 'zap'])

<div class="flex items-center justify-center pt-4">
    <x-ui.button type="submit" :variant="$variant" size="lg">
        @if($icon === 'zap')
            <x-lucide-zap class="w-5 h-5" />
        @elseif($icon === 'send')
            <x-lucide-send class="w-5 h-5" />
        @endif
        {{ $label }}
    </x-ui.button>
</div>
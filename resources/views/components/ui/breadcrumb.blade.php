@props([
    'items' => [],
    'separator' => 'chevron',
])

{{-- 
    Breadcrumb Component
    
    IMPORTANT: Every breadcrumb item MUST have an icon for visual consistency.
    
    Usage:
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Users', 'route' => 'admin.users.index', 'icon' => 'users'],
        ['label' => 'John Doe', 'icon' => 'user'],
    ]" />

    With href instead of route:
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'href' => '/', 'icon' => 'home'],
        ['label' => 'Settings', 'icon' => 'settings'],
    ]" />

    With slash separator:
    <x-ui.breadcrumb separator="slash" :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Settings', 'icon' => 'settings'],
    ]" />
--}}

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'mb-6']) }}>
    <ol class="flex items-center gap-2 text-sm">
        @foreach($items as $index => $item)
            @if($index > 0)
                {{-- Separator --}}
                <li class="text-neutral-300" aria-hidden="true">
                    @if($separator === 'chevron')
                        <x-lucide-chevron-right class="w-4 h-4" />
                    @elseif($separator === 'slash')
                        <span>/</span>
                    @endif
                </li>
            @endif
            
            @php
                $href = $item['href'] ?? (isset($item['route']) ? route($item['route']) : null);
                $isLast = $index === count($items) - 1;
            @endphp
            
            <li>
                @if($href && !$isLast)
                    <a href="{{ $href }}" 
                       class="flex items-center gap-1.5 text-neutral-500 hover:text-primary-600 transition-colors duration-200">
                        @if(isset($item['icon']))
                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-4 h-4" />
                        @endif
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="flex items-center gap-1.5 text-neutral-900 font-medium">
                        @if(isset($item['icon']))
                            <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-4 h-4" />
                        @endif
                        {{ $item['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>

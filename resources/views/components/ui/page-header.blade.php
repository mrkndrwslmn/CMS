@props([
    'title' => null,
    'description' => null,
    'subtitle' => null,
    'actions' => null,
])

{{--
    Page Header Component
    
    Usage:
    <x-ui.page-header 
        title="Users" 
        description="Manage all users in the system"
    >
        <x-slot:actions>
            <x-ui.button variant="primary">Add User</x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>
--}}

@php
    // Support both 'description' and 'subtitle' props
    $desc = $description ?? $subtitle;
@endphp

<div {{ $attributes->merge(['class' => 'mb-8']) }}>
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            @if($title)
                <h1 class="text-2xl font-semibold text-neutral-900">{{ $title }}</h1>
            @endif
            @if($desc)
                <p class="mt-1 text-sm text-neutral-500">{{ $desc }}</p>
            @endif
        </div>
        
        @if($actions)
            <div class="flex items-center gap-3">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>

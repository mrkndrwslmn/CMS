@props([
    'align' => 'left',
])

@php
    $alignments = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
@endphp

<td {{ $attributes->merge(['class' => 'px-6 py-4 text-sm text-neutral-700 whitespace-nowrap ' . ($alignments[$align] ?? $alignments['left'])]) }}>
    {{ $slot }}
</td>

@props([
    'transactions',
    'totalExpiring' => 0,
    'daysThreshold' => 30,
])

@if($transactions->count() > 0)
<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-warning-200 shadow-sm p-6']) }}>
    <h3 class="text-lg font-medium text-warning-700 mb-4 flex items-center gap-2">
        <x-lucide-alert-triangle class="w-5 h-5" />
        Points Expiring Soon
    </h3>

    <div class="space-y-3">
        @foreach($transactions->take(3) as $expiring)
            <div class="bg-warning-50 rounded-xl p-3 border border-warning-100">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-base font-semibold text-warning-700">{{ number_format($expiring->points) }}</span>
                    <span class="text-xs font-medium text-warning-600 bg-warning-100 px-2 py-0.5 rounded-full">
                        {{ $expiring->expires_at->format('M d, Y') }}
                    </span>
                </div>
                <p class="text-xs text-neutral-500">{{ $expiring->description }}</p>
            </div>
        @endforeach
    </div>

    @if($totalExpiring > 0)
        <div class="flex items-start gap-2 mt-4 p-3 bg-warning-50 rounded-xl border border-warning-100">
            <x-lucide-info class="w-4 h-4 text-warning-500 mt-0.5 flex-shrink-0" />
            <p class="text-xs text-warning-700">
                Total <span class="font-semibold">{{ number_format($totalExpiring) }}</span> points expiring in {{ $daysThreshold }} days
            </p>
        </div>
    @endif
</div>
@endif

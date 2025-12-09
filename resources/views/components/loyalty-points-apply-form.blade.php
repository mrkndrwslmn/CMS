@props(['serviceRequest', 'formId' => 'loyalty-points-form'])

@php
    $user = auth()->user();
    $loyaltyPoint = $user->loyaltyPoints;
    $availablePoints = $loyaltyPoint?->available_points ?? 0;
    $pointsAlreadyUsed = $serviceRequest->loyalty_points_used ?? 0;
    $loyaltyDiscountApplied = $serviceRequest->loyalty_discount_amount ?? 0;
    
    // Calculate conversion rate (from config) - 1 point = ₱1
    $conversionRate = config('loyalty.points.conversion_rate', 1);
    $minRedemption = config('loyalty.points.minimum_redemption', 100);
    $maxPercentage = config('loyalty.points.maximum_redemption_percentage', 50);
    
    // Calculate max discount based on approved budget
    $approvedBudget = $serviceRequest->approved_budget ?? 0;
    $maxDiscountAmount = ($approvedBudget * $maxPercentage) / 100;
    // Since 1 point = ₱1 (conversion_rate = 1), max points = max discount amount
    $maxPointsUsable = $maxDiscountAmount * $conversionRate;
    
    // Effective max is the lesser of available points and max usable
    $effectiveMaxPoints = min($availablePoints, $maxPointsUsable);
    $effectiveMaxDiscount = $effectiveMaxPoints / $conversionRate;
@endphp

@if($availablePoints >= $minRedemption && $pointsAlreadyUsed == 0)
<div class="bg-gradient-to-r from-warning-50 to-amber-50 rounded-xl p-4 mb-4 border border-warning-200" 
     x-data="{
        pointsToRedeem: 0,
        maxPoints: {{ $effectiveMaxPoints }},
        minPoints: {{ $minRedemption }},
        conversionRate: {{ $conversionRate }},
        isLoading: false,
        errorMessage: '',
        get discount() {
            return this.pointsToRedeem / this.conversionRate;
        },
        setMax() {
            this.pointsToRedeem = Math.min(this.maxPoints, {{ $availablePoints }});
        },
        async redeemPoints() {
            if (this.pointsToRedeem < this.minPoints) {
                this.errorMessage = 'Minimum {{ number_format($minRedemption) }} points required';
                return;
            }
            
            this.isLoading = true;
            this.errorMessage = '';
            
            try {
                const response = await fetch('{{ route('client.loyalty.redeem', $serviceRequest) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ points: parseInt(this.pointsToRedeem) })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success !== false) {
                    window.toast.success(data.message || 'Points redeemed successfully!');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || data.errors?.points?.[0] || 'Failed to redeem points';
                    window.toast.error(this.errorMessage);
                }
            } catch (error) {
                console.error('Loyalty error:', error);
                this.errorMessage = 'An error occurred. Please try again.';
                window.toast.error(this.errorMessage);
            } finally {
                this.isLoading = false;
            }
        }
    }">
    <div class="flex items-center gap-2 mb-3">
        <x-lucide-award class="w-5 h-5 text-warning-600" />
        <p class="text-sm font-semibold text-warning-800">Use Loyalty Points</p>
    </div>
    
    <div class="bg-white/60 rounded-lg p-3 mb-3">
        <div class="flex items-center justify-between text-sm">
            <span class="text-neutral-600">Available Points:</span>
            <span class="font-bold text-warning-700">{{ number_format($availablePoints) }} pts</span>
        </div>
        <div class="flex items-center justify-between text-xs text-neutral-500 mt-1">
            <span>Worth up to:</span>
            <span>₱{{ number_format($availablePoints / $conversionRate, 2) }}</span>
        </div>
    </div>
    
    <form @submit.prevent="redeemPoints" class="space-y-3" id="{{ $formId }}">
        <div>
            <label class="text-xs font-medium text-neutral-600 mb-1 block">Points to Redeem</label>
            <div class="flex items-center gap-2">
                <input type="number" 
                       x-model.number="pointsToRedeem"
                       :max="maxPoints"
                       :min="minPoints"
                       step="1"
                       placeholder="Enter points" 
                       class="flex-1 px-3 py-2 border border-neutral-300 rounded-lg focus:border-warning-500 focus:ring-1 focus:ring-warning-200 transition-all text-sm"
                       :disabled="isLoading">
                <button type="button" 
                        @click="setMax()"
                        class="px-3 py-2 bg-warning-100 text-warning-700 text-xs font-medium rounded-lg hover:bg-warning-200 transition-colors whitespace-nowrap">
                    Use Max
                </button>
            </div>
            <p x-show="errorMessage" x-text="errorMessage" class="text-xs text-error-600 mt-1" x-cloak></p>
        </div>
        
        <div x-show="pointsToRedeem >= minPoints" class="bg-success-50 rounded-lg p-2 border border-success-200" x-cloak>
            <div class="flex items-center justify-between text-sm">
                <span class="text-success-700">You'll save:</span>
                <span class="font-bold text-success-700">₱<span x-text="discount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})"></span></span>
            </div>
        </div>
        
        <button type="submit" 
                :disabled="isLoading || pointsToRedeem < minPoints"
                class="w-full px-4 py-2 bg-warning-500 text-white font-medium rounded-lg hover:bg-warning-600 transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
            <template x-if="isLoading">
                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </template>
            <span x-text="isLoading ? 'Redeeming...' : 'Redeem Points'"></span>
        </button>
        
        <p class="text-xs text-neutral-500">
            1 point = ₱{{ $conversionRate }} • Min: {{ number_format($minRedemption) }} pts • Max discount: {{ $maxPercentage }}%
        </p>
    </form>
</div>
@elseif($pointsAlreadyUsed > 0)
<!-- Already redeemed - show redemption info with remove option -->
<div class="bg-success-50 rounded-xl p-4 mb-4 border border-success-200">
    <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2">
            <x-lucide-award class="w-5 h-5 text-success-600" />
            <p class="text-sm font-semibold text-success-800">Points Redeemed</p>
        </div>
        <span class="text-xs bg-success-100 text-success-700 px-2 py-1 rounded-full font-medium">Applied</span>
    </div>
    
    <div class="flex items-center justify-between mb-3">
        <div>
            <p class="text-lg font-bold text-success-700">{{ number_format($pointsAlreadyUsed) }} pts</p>
            <p class="text-xs text-success-600">used for this request</p>
        </div>
        <div class="text-right">
            <p class="text-lg font-bold text-success-700">-₱{{ number_format($loyaltyDiscountApplied, 2) }}</p>
            <p class="text-xs text-success-600">discount applied</p>
        </div>
    </div>
    
    @if($serviceRequest->status !== 'paid' && !$serviceRequest->payments()->where('status', 'confirmed')->exists())
        <form action="{{ route('client.loyalty.remove-redemption', $serviceRequest) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    onclick="return window.Alerts.confirmForm(event, 'Remove Points', 'Remove redeemed points and refund them to your balance?')" 
                    class="w-full px-3 py-1.5 bg-white border border-success-300 text-success-700 text-xs font-medium rounded-lg hover:bg-success-50 transition-colors">
                Remove & Refund Points
            </button>
        </form>
    @endif
</div>
@elseif($availablePoints > 0 && $availablePoints < $minRedemption)
<!-- Has points but below minimum -->
<div class="bg-neutral-50 rounded-xl p-4 mb-4 border border-neutral-200">
    <div class="flex items-center gap-2 mb-2">
        <x-lucide-award class="w-5 h-5 text-neutral-400" />
        <p class="text-sm font-medium text-neutral-600">Loyalty Points</p>
    </div>
    <p class="text-xs text-neutral-500">
        You have <span class="font-semibold text-neutral-700">{{ number_format($availablePoints) }} points</span>. 
        Earn {{ number_format($minRedemption - $availablePoints) }} more to redeem!
    </p>
</div>
@endif

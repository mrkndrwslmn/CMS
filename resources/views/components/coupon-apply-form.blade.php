@props(['serviceRequest', 'formId' => 'coupon-form'])

<div class="bg-neutral-50 rounded-lg p-4 mb-4 border border-neutral-200" 
     x-data="{
        couponCode: '',
        isLoading: false,
        errorMessage: '',
        async applyCoupon() {
            if (!this.couponCode.trim()) {
                this.errorMessage = 'Please enter a coupon code';
                return;
            }
            
            this.isLoading = true;
            this.errorMessage = '';
            
            try {
                const response = await fetch('{{ route('client.coupons.apply', $serviceRequest) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ coupon_code: this.couponCode.toUpperCase() })
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    window.toast.success(data.message || 'Coupon applied successfully!');
                    // Reload to show updated prices
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    this.errorMessage = data.message || data.errors?.coupon_code?.[0] || 'Failed to apply coupon';
                    window.toast.error(this.errorMessage);
                }
            } catch (error) {
                console.error('Coupon error:', error);
                this.errorMessage = 'An error occurred. Please try again.';
                window.toast.error(this.errorMessage);
            } finally {
                this.isLoading = false;
            }
        }
    }">
    <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide mb-3">Have a Coupon?</p>
    <form @submit.prevent="applyCoupon" class="space-y-3" id="{{ $formId }}">
        <div>
            <input type="text" 
                   x-model="couponCode"
                   placeholder="Enter code" 
                   class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-all font-mono uppercase text-sm"
                   :disabled="isLoading"
                   required>
            <p x-show="errorMessage" x-text="errorMessage" class="text-xs text-error-600 mt-1" x-cloak></p>
        </div>
        <button type="submit" 
                :disabled="isLoading"
                class="w-full px-4 py-2 bg-primary-500 text-white font-medium rounded-lg hover:bg-primary-600 transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
            <template x-if="isLoading">
                <svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </template>
            <span x-text="isLoading ? 'Applying...' : 'Apply Coupon'"></span>
        </button>
        <p class="text-xs text-neutral-500">
            <a href="{{ route('client.coupons.index') }}" class="text-primary-600 hover:text-primary-700 font-medium">View available coupons</a>
        </p>
    </form>
</div>

@extends('adiutor.layouts.app')

@section('title', 'Earnings & Payout Settings')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Earnings & Payout Settings</h1>
                <p class="text-sm text-gray-500 mt-1">Configure your hourly rate and payout preferences</p>
            </div>
            <a href="{{ route('adiutor.profile.show') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Profile
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <form action="{{ route('adiutor.profile.earnings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Standard Hourly Rate Card -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Standard Hourly Rate</h2>
                        <p class="text-sm text-gray-600">Your default billing rate per hour</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-4">
                        <span class="inline-flex items-center gap-1.5 text-blue-600 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Note:
                        </span>
                        This is your default hourly rate. Admins can override this for specific projects or tasks.
                    </p>
                </div>
                
                <div class="max-w-md">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Hourly Rate <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">
                            ₱
                        </span>
                        <input type="number" 
                               name="standard_hourly_rate" 
                               class="block w-full pl-8 pr-20 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow @error('standard_hourly_rate') border-red-300 @enderror" 
                               value="{{ old('standard_hourly_rate', $profile->standard_hourly_rate ?? '') }}"
                               step="0.01"
                               min="0"
                               placeholder="500.00"
                               required>
                        <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-500 text-sm">
                            /hour
                        </span>
                    </div>
                    @error('standard_hourly_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">
                        Example: ₱500.00 = Five hundred pesos per hour
                    </p>
                </div>
            </div>
        </div>

        <!-- Payout Settings Card -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Payout Settings</h2>
                        <p class="text-sm text-gray-600">Configure how you receive payments</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Minimum Payout Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Minimum Payout Amount
                    </label>
                    <div class="max-w-md">
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">
                                ₱
                            </span>
                            <input type="number" 
                                   name="minimum_payout_amount" 
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow" 
                                   value="{{ old('minimum_payout_amount', $profile->minimum_payout_amount ?? 500) }}"
                                   step="0.01"
                                   min="100"
                                   placeholder="500.00">
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            You can only request payout when earnings reach this amount (minimum: ₱100)
                        </p>
                    </div>
                </div>

                <!-- Preferred Payout Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Preferred Payout Method <span class="text-red-500">*</span>
                    </label>
                    <div class="max-w-md">
                        <select name="preferred_payout_method" 
                                id="payout_method_select"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow @error('preferred_payout_method') border-red-300 @enderror" 
                                required>
                            <option value="">-- Select Payment Method --</option>
                            <option value="bank_transfer" {{ old('preferred_payout_method', $profile->preferred_payout_method ?? '') == 'bank_transfer' ? 'selected' : '' }}>🏦 Bank Transfer</option>
                            <option value="gcash" {{ old('preferred_payout_method', $profile->preferred_payout_method ?? '') == 'gcash' ? 'selected' : '' }}>📱 GCash</option>
                            <option value="paymaya" {{ old('preferred_payout_method', $profile->preferred_payout_method ?? '') == 'paymaya' ? 'selected' : '' }}>💳 PayMaya</option>
                            <option value="paypal" {{ old('preferred_payout_method', $profile->preferred_payout_method ?? '') == 'paypal' ? 'selected' : '' }}>🌐 PayPal</option>
                            <option value="other" {{ old('preferred_payout_method', $profile->preferred_payout_method ?? '') == 'other' ? 'selected' : '' }}>📝 Other</option>
                        </select>
                        @error('preferred_payout_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bank Transfer Fields -->
                <div id="bank-fields" class="space-y-4" style="display:none;">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Bank Account Details</h3>
                        <div class="space-y-3">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Bank Name</label>
                                    <input type="text" 
                                           name="payout_details[bank_name]" 
                                           class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                           value="{{ old('payout_details.bank_name', $payoutDetails['bank_name'] ?? '') }}"
                                           placeholder="e.g., BDO, BPI, Metrobank">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Account Number</label>
                                    <input type="text" 
                                           name="payout_details[account_number]" 
                                           class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                           value="{{ old('payout_details.account_number', $payoutDetails['account_number'] ?? '') }}"
                                           placeholder="1234567890">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Account Name</label>
                                <input type="text" 
                                       name="payout_details[account_name]" 
                                       class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                       value="{{ old('payout_details.account_name', $payoutDetails['account_name'] ?? '') }}"
                                       placeholder="Juan Dela Cruz">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GCash/PayMaya Fields -->
                <div id="mobile-fields" class="space-y-4" style="display:none;">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Mobile Wallet Details</h3>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input type="text" 
                                   name="payout_details[mobile_number]" 
                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                   value="{{ old('payout_details.mobile_number', $payoutDetails['mobile_number'] ?? '') }}"
                                   placeholder="09171234567">
                            <p class="mt-1 text-xs text-gray-500">Format: 09XXXXXXXXX</p>
                        </div>
                    </div>
                </div>

                <!-- PayPal Fields -->
                <div id="paypal-fields" class="space-y-4" style="display:none;">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">PayPal Details</h3>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">PayPal Email</label>
                            <input type="email" 
                                   name="payout_details[paypal_email]" 
                                   class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                   value="{{ old('payout_details.paypal_email', $payoutDetails['paypal_email'] ?? '') }}"
                                   placeholder="your.email@example.com">
                        </div>
                    </div>
                </div>

                <!-- Other Fields -->
                <div id="other-fields" class="space-y-4" style="display:none;">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-900 mb-4">Payment Details</h3>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Specify your payment details</label>
                            <textarea name="payout_details[other_details]" 
                                      class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" 
                                      rows="4"
                                      placeholder="Please provide complete payment instructions...">{{ old('payout_details.other_details', $payoutDetails['other_details'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('adiutor.profile.show') }}" 
               class="px-6 py-3 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Settings
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function togglePayoutFields() {
    const method = document.getElementById('payout_method_select').value;
    
    // Hide all fields
    document.getElementById('bank-fields').style.display = 'none';
    document.getElementById('mobile-fields').style.display = 'none';
    document.getElementById('paypal-fields').style.display = 'none';
    document.getElementById('other-fields').style.display = 'none';
    
    // Show relevant field
    if (method === 'bank_transfer') {
        document.getElementById('bank-fields').style.display = 'block';
    } else if (method === 'gcash' || method === 'paymaya') {
        document.getElementById('mobile-fields').style.display = 'block';
    } else if (method === 'paypal') {
        document.getElementById('paypal-fields').style.display = 'block';
    } else if (method === 'other') {
        document.getElementById('other-fields').style.display = 'block';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    togglePayoutFields();
    document.getElementById('payout_method_select').addEventListener('change', togglePayoutFields);
});
</script>
@endpush
@endsection

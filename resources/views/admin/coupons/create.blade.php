@extends('layouts.admin')

@section('title', 'Create Coupon')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-800 mb-2">Create New Coupon</h1>
            <p class="text-neutral-600">Generate discount coupons for clients</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Coupons
        </a>
    </div>

    <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm" class="max-w-4xl">
        @csrf

        <div class="space-y-8">
            <!-- Basic Information -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-info-circle text-primary-600 mr-2"></i>Basic Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Coupon Code -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Coupon Code <span class="text-error-600">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="code" id="code" 
                                   value="{{ old('code') }}"
                                   class="form-input flex-1 @error('code') border-error-500 @enderror"
                                   placeholder="e.g., WELCOME2024, FIRST50"
                                   maxlength="50"
                                   required>
                            <button type="button" onclick="generateCode()" class="btn-secondary whitespace-nowrap">
                                <i class="fas fa-random mr-2"></i>Generate
                            </button>
                        </div>
                        @error('code')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-1">Uppercase letters, numbers, and hyphens only</p>
                    </div>

                    <!-- Discount Type -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Discount Type <span class="text-error-600">*</span>
                        </label>
                        <select name="discount_type" id="discount_type" 
                                class="form-select @error('discount_type') border-error-500 @enderror"
                                onchange="updateDiscountInput()"
                                required>
                            <option value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>
                                Percentage (%)
                            </option>
                            <option value="fixed" {{ old('discount_type') === 'fixed' ? 'selected' : '' }}>
                                Fixed Amount (₱)
                            </option>
                        </select>
                        @error('discount_type')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Value -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Discount Value <span class="text-error-600">*</span>
                        </label>
                        <div class="relative">
                            <span id="discount_prefix" class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">
                                {{ old('discount_type', 'percentage') === 'percentage' ? '%' : '₱' }}
                            </span>
                            <input type="number" name="discount_value" id="discount_value" 
                                   value="{{ old('discount_value') }}"
                                   class="form-input pl-8 @error('discount_value') border-error-500 @enderror"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   required>
                        </div>
                        @error('discount_value')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-1" id="discount_hint">
                            Max: 100%
                        </p>
                    </div>

                    <!-- Max Discount Cap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Maximum Discount Cap (₱)
                        </label>
                        <input type="number" name="max_discount_cap" 
                               value="{{ old('max_discount_cap') }}"
                               class="form-input @error('max_discount_cap') border-error-500 @enderror"
                               step="0.01"
                               min="0"
                               placeholder="Leave empty for unlimited">
                        @error('max_discount_cap')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-1">Limit the maximum discount amount (useful for percentage discounts)</p>
                    </div>

                    <!-- Minimum Order Amount -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Minimum Order Amount (₱)
                        </label>
                        <input type="number" name="min_order_amount" 
                               value="{{ old('min_order_amount', 0) }}"
                               class="form-input @error('min_order_amount') border-error-500 @enderror"
                               step="0.01"
                               min="0">
                        @error('min_order_amount')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="3" 
                                  class="form-input @error('description') border-error-500 @enderror"
                                  placeholder="e.g., Welcome discount for new customers">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-calendar text-success-600 mr-2"></i>Validity Period
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Valid From -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Valid From <span class="text-error-600">*</span>
                        </label>
                        <input type="datetime-local" name="valid_from" 
                               value="{{ old('valid_from', now()->format('Y-m-d\TH:i')) }}"
                               class="form-input @error('valid_from') border-error-500 @enderror"
                               required>
                        @error('valid_from')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Valid Until -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Valid Until <span class="text-error-600">*</span>
                        </label>
                        <input type="datetime-local" name="valid_until" 
                               value="{{ old('valid_until', now()->addDays(30)->format('Y-m-d\TH:i')) }}"
                               class="form-input @error('valid_until') border-error-500 @enderror"
                               required>
                        @error('valid_until')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-hashtag text-warning-600 mr-2"></i>Usage Limits
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Uses -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Total Uses Allowed
                        </label>
                        <input type="number" name="total_uses_allowed" 
                               value="{{ old('total_uses_allowed') }}"
                               class="form-input @error('total_uses_allowed') border-error-500 @enderror"
                               min="1"
                               placeholder="Unlimited">
                        @error('total_uses_allowed')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-1">Total times this coupon can be used</p>
                    </div>

                    <!-- Uses Per User -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Uses Per User
                        </label>
                        <input type="number" name="uses_per_user" 
                               value="{{ old('uses_per_user', 1) }}"
                               class="form-input @error('uses_per_user') border-error-500 @enderror"
                               min="1"
                               placeholder="1">
                        @error('uses_per_user')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-1">How many times each user can use this</p>
                    </div>
                </div>
            </div>

            <!-- Coupon Type & Assignment -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-users text-info-600 mr-2"></i>Type & Assignment
                </h3>

                <div class="space-y-6">
                    <!-- Coupon Type -->
                    <div>
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Coupon Type <span class="text-error-600">*</span>
                        </label>
                        <select name="coupon_type" id="coupon_type" 
                                class="form-select @error('coupon_type') border-error-500 @enderror"
                                onchange="toggleAssignmentFields()"
                                required>
                            <option value="public" {{ old('coupon_type', 'public') === 'public' ? 'selected' : '' }}>
                                Public - Available to all users
                            </option>
                            <option value="specific_user" {{ old('coupon_type') === 'specific_user' ? 'selected' : '' }}>
                                Specific User - Assigned to one user
                            </option>
                            <option value="specific_request" {{ old('coupon_type') === 'specific_request' ? 'selected' : '' }}>
                                Specific Request - For a service request
                            </option>
                        </select>
                        @error('coupon_type')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User Assignment (shown when specific_user is selected) -->
                    <div id="user_assignment_field" class="hidden">
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Assign to User <span class="text-error-600">*</span>
                        </label>
                        <select name="user_id" 
                                class="form-select @error('user_id') border-error-500 @enderror">
                            <option value="">Select a user...</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Request Assignment (shown when specific_request is selected) -->
                    <div id="request_assignment_field" class="hidden">
                        <label class="block text-sm font-semibold text-neutral-700 mb-2">
                            Assign to Service Request <span class="text-error-600">*</span>
                        </label>
                        <input type="text" name="service_request_id" 
                               value="{{ old('service_request_id') }}"
                               class="form-input @error('service_request_id') border-error-500 @enderror"
                               placeholder="Enter service request ID">
                        @error('service_request_id')
                            <p class="text-error-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Settings -->
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold text-neutral-800 mb-6">
                    <i class="fas fa-cog text-neutral-600 mr-2"></i>Additional Settings
                </h3>

                <div class="space-y-4">
                    <!-- Can Combine With Others -->
                    <div class="flex items-start">
                        <input type="checkbox" name="can_combine_with_others" id="can_combine_with_others" 
                               value="1" {{ old('can_combine_with_others') ? 'checked' : '' }}
                               class="mt-1 mr-3">
                        <div>
                            <label for="can_combine_with_others" class="text-sm font-semibold text-neutral-700 cursor-pointer">
                                Allow Combining with Other Coupons
                            </label>
                            <p class="text-xs text-neutral-500 mt-1">Enable this to allow stacking with other coupons</p>
                        </div>
                    </div>

                    <!-- Can Combine With Loyalty -->
                    <div class="flex items-start">
                        <input type="checkbox" name="can_combine_with_loyalty" id="can_combine_with_loyalty" 
                               value="1" {{ old('can_combine_with_loyalty', true) ? 'checked' : '' }}
                               class="mt-1 mr-3">
                        <div>
                            <label for="can_combine_with_loyalty" class="text-sm font-semibold text-neutral-700 cursor-pointer">
                                Allow Combining with Loyalty Points
                            </label>
                            <p class="text-xs text-neutral-500 mt-1">Users can use both coupon and loyalty points</p>
                        </div>
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-start">
                        <input type="checkbox" name="is_active" id="is_active" 
                               value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="mt-1 mr-3">
                        <div>
                            <label for="is_active" class="text-sm font-semibold text-neutral-700 cursor-pointer">
                                Activate Immediately
                            </label>
                            <p class="text-xs text-neutral-500 mt-1">Make this coupon available for use right away</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('admin.coupons.index') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Create Coupon
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Generate random coupon code
    function generateCode() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 12; i++) {
            if (i > 0 && i % 4 === 0) code += '-';
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('code').value = code;
    }

    // Update discount input based on type
    function updateDiscountInput() {
        const type = document.getElementById('discount_type').value;
        const prefix = document.getElementById('discount_prefix');
        const input = document.getElementById('discount_value');
        const hint = document.getElementById('discount_hint');

        if (type === 'percentage') {
            prefix.textContent = '%';
            input.max = '100';
            hint.textContent = 'Max: 100%';
        } else {
            prefix.textContent = '₱';
            input.removeAttribute('max');
            hint.textContent = 'Enter fixed discount amount';
        }
    }

    // Toggle assignment fields based on coupon type
    function toggleAssignmentFields() {
        const type = document.getElementById('coupon_type').value;
        const userField = document.getElementById('user_assignment_field');
        const requestField = document.getElementById('request_assignment_field');

        userField.classList.add('hidden');
        requestField.classList.add('hidden');

        if (type === 'specific_user') {
            userField.classList.remove('hidden');
            document.querySelector('[name="user_id"]').required = true;
            document.querySelector('[name="service_request_id"]').required = false;
        } else if (type === 'specific_request') {
            requestField.classList.remove('hidden');
            document.querySelector('[name="service_request_id"]').required = true;
            document.querySelector('[name="user_id"]').required = false;
        } else {
            document.querySelector('[name="user_id"]').required = false;
            document.querySelector('[name="service_request_id"]').required = false;
        }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateDiscountInput();
        toggleAssignmentFields();
    });

    // Form validation
    document.getElementById('couponForm').addEventListener('submit', function(e) {
        const validFrom = new Date(document.querySelector('[name="valid_from"]').value);
        const validUntil = new Date(document.querySelector('[name="valid_until"]').value);

        if (validUntil <= validFrom) {
            e.preventDefault();
            alert('Valid Until date must be after Valid From date');
            return false;
        }
    });
</script>
@endpush
@endsection

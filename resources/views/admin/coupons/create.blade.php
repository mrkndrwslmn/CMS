@extends('admin.layouts.app')

@section('title', 'Create Coupon')
@section('page-title', 'Create Coupon')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-6" aria-label="Breadcrumb">
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-neutral-600 hover:text-primary-600 transition-colors">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <svg class="w-4 h-4 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li>
                <a href="{{ route('admin.coupons.index') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">
                    Coupons
                </a>
            </li>
            <li>
                <svg class="w-4 h-4 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </li>
            <li class="text-neutral-900 font-medium">Create Coupon</li>
        </ol>
    </nav>

    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">
            Create New Coupon
        </h1>
        <p class="text-neutral-500 mt-2">Generate discount coupons for clients and track their usage</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-4 rounded-lg shadow-sm mb-6">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-success-500 text-xl mr-3"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-4 rounded-lg shadow-sm mb-6">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-error-500 text-xl mr-3"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm" class="space-y-6">
        @csrf
        <!-- Basic Information Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-gradient-to-r from-primary-50 to-primary-100 border-b border-primary-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Basic Information</h2>
                        <p class="text-sm text-neutral-600">Define the coupon code, name, and discount details</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Coupon Code -->
                    <div class="md:col-span-2">
                        <label for="code" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Coupon Code <span class="text-error-500">*</span>
                        </label>
                        <div class="flex gap-2">
                            <input type="text" name="code" id="code" 
                                   value="{{ old('code') }}"
                                   class="flex-1 rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('code') border-error-500 @enderror"
                                   placeholder="e.g., WELCOME2024, SAVE50"
                                   maxlength="50"
                                   required>
                            <button type="button" onclick="generateCode()" 
                                    class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-lg transition-all shadow-sm hover:shadow whitespace-nowrap">
                                <i class="fas fa-magic mr-2"></i>Generate
                            </button>
                        </div>
                        @error('code')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2">
                            <i class="fas fa-info-circle text-primary-500"></i> 
                            Uppercase letters, numbers, and hyphens only
                        </p>
                    </div>

                    <!-- Coupon Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Coupon Name <span class="text-error-500">*</span>
                        </label>
                        <input type="text" name="name" id="name"
                               value="{{ old('name') }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('name') border-error-500 @enderror"
                               placeholder="e.g., Welcome Discount, First Order Special"
                               maxlength="255"
                               required>
                        @error('name')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Discount Type -->
                    <div>
                        <label for="discount_type" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Discount Type <span class="text-error-500">*</span>
                        </label>
                        <select name="discount_type" id="discount_type" 
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('discount_type') border-error-500 @enderror"
                                onchange="updateDiscountInput()"
                                required>
                            <option value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>
                                Percentage (%)
                            </option>
                            <option value="fixed_amount" {{ old('discount_type') === 'fixed_amount' ? 'selected' : '' }}>
                                Fixed Amount (₱)
                            </option>
                        </select>
                        @error('discount_type')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Discount Value -->
                    <div>
                        <label for="discount_value" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Discount Value <span class="text-error-500">*</span>
                        </label>
                        <div class="relative">
                            <span id="discount_prefix" class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-600 font-semibold">
                                {{ old('discount_type', 'percentage') === 'percentage' ? '%' : '₱' }}
                            </span>
                            <input type="number" name="discount_value" id="discount_value" 
                                   value="{{ old('discount_value') }}"
                                   class="w-full rounded-lg border-2 border-neutral-300 pl-10 pr-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('discount_value') border-error-500 @enderror"
                                   step="0.01"
                                   min="0"
                                   max="100"
                                   required>
                        </div>
                        @error('discount_value')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2" id="discount_hint">
                            <i class="fas fa-lightbulb text-warning-500"></i> Max: 100%
                        </p>
                    </div>

                    <!-- Max Discount Cap -->
                    <div class="md:col-span-2">
                        <label for="max_discount_amount" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Maximum Discount Cap (₱)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-secondary-100 text-secondary-700 ml-2">
                                <i class="fas fa-shield-alt mr-1"></i> Optional
                            </span>
                        </label>
                        <input type="number" name="max_discount_amount" id="max_discount_amount"
                               value="{{ old('max_discount_amount') }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('max_discount_amount') border-error-500 @enderror"
                               step="0.01"
                               min="0"
                               placeholder="Leave empty for unlimited">
                        @error('max_discount_amount')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2">
                            <i class="fas fa-info-circle text-secondary-500"></i> 
                            Limit the maximum discount amount (useful for percentage discounts)
                        </p>
                    </div>

                    <!-- Minimum Order Amount -->
                    <div>
                        <label for="min_purchase_amount" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Minimum Purchase Amount (₱)
                        </label>
                        <input type="number" name="min_purchase_amount" id="min_purchase_amount"
                               value="{{ old('min_purchase_amount', 0) }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('min_purchase_amount') border-error-500 @enderror"
                               step="0.01"
                               min="0">
                        @error('min_purchase_amount')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3" 
                                  class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('description') border-error-500 @enderror"
                                  placeholder="e.g., Welcome discount for new customers, Limited time offer...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Validity Period Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-gradient-to-r from-success-50 to-success-100 border-b border-success-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-success-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Validity Period</h2>
                        <p class="text-sm text-neutral-600">Set when this coupon can be used</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Valid From -->
                    <div>
                        <label for="valid_from" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Valid From <span class="text-error-500">*</span>
                        </label>
                        <input type="datetime-local" name="valid_from" id="valid_from"
                               value="{{ old('valid_from', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('valid_from') border-error-500 @enderror"
                               required>
                        @error('valid_from')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Valid Until -->
                    <div>
                        <label for="valid_until" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Valid Until <span class="text-error-500">*</span>
                        </label>
                        <input type="datetime-local" name="valid_until" id="valid_until"
                               value="{{ old('valid_until', now()->addDays(30)->format('Y-m-d\TH:i')) }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('valid_until') border-error-500 @enderror"
                               required>
                        @error('valid_until')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Limits Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-gradient-to-r from-warning-50 to-warning-100 border-b border-warning-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-warning-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-hashtag text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Usage Limits</h2>
                        <p class="text-sm text-neutral-600">Control how many times this coupon can be used</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Total Uses -->
                    <div>
                        <label for="max_total_uses" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Total Uses Allowed
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-secondary-100 text-secondary-700 ml-2">
                                <i class="fas fa-infinity mr-1"></i> Optional
                            </span>
                        </label>
                        <input type="number" name="max_total_uses" id="max_total_uses"
                               value="{{ old('max_total_uses') }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('max_total_uses') border-error-500 @enderror"
                               min="1"
                               placeholder="Unlimited">
                        @error('max_total_uses')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2">
                            <i class="fas fa-info-circle text-warning-500"></i> 
                            Total times this coupon can be used by all users
                        </p>
                    </div>

                    <!-- Uses Per User -->
                    <div>
                        <label for="max_uses_per_user" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Uses Per User <span class="text-error-500">*</span>
                        </label>
                        <input type="number" name="max_uses_per_user" id="max_uses_per_user"
                               value="{{ old('max_uses_per_user', 1) }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('max_uses_per_user') border-error-500 @enderror"
                               min="1"
                               required>
                        @error('max_uses_per_user')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-neutral-500 mt-2">
                            <i class="fas fa-info-circle text-warning-500"></i> 
                            How many times each user can use this coupon
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Coupon Type & Assignment Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-gradient-to-r from-info-50 to-info-100 border-b border-info-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-info-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Type & Assignment</h2>
                        <p class="text-sm text-neutral-600">Choose who can use this coupon</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Coupon Type -->
                    <div>
                        <label for="coupon_type" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Coupon Type <span class="text-error-500">*</span>
                        </label>
                        <select name="coupon_type" id="coupon_type" 
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('coupon_type') border-error-500 @enderror"
                                onchange="toggleAssignmentFields()"
                                required>
                            <option value="public" {{ old('coupon_type', 'public') === 'public' ? 'selected' : '' }}>
                                🌐 Public - Available to all users
                            </option>
                            <option value="user_specific" {{ old('coupon_type') === 'user_specific' ? 'selected' : '' }}>
                                👤 Specific User - Assigned to one user
                            </option>
                            <option value="request_specific" {{ old('coupon_type') === 'request_specific' ? 'selected' : '' }}>
                                📋 Specific Request - For a service request
                            </option>
                        </select>
                        @error('coupon_type')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- User Assignment (shown when user_specific is selected) -->
                    <div id="user_assignment_field" class="hidden">
                        <label for="specific_user_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Assign to User <span class="text-error-500">*</span>
                        </label>
                        <select name="specific_user_id" id="specific_user_id"
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('specific_user_id') border-error-500 @enderror">
                            <option value="">Select a client...</option>
                            @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('specific_user_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->fullName }} ({{ $client->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('specific_user_id')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Request Assignment (shown when request_specific is selected) -->
                    <div id="request_assignment_field" class="hidden">
                        <label for="specific_request_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Assign to Service Request <span class="text-error-500">*</span>
                        </label>
                        <input type="text" name="specific_request_id" id="specific_request_id"
                               value="{{ old('specific_request_id') }}"
                               class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('specific_request_id') border-error-500 @enderror"
                               placeholder="Enter service request ID">
                        @error('specific_request_id')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Settings Section -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 overflow-hidden">
            <div class="bg-gradient-to-r from-neutral-50 to-neutral-100 border-b border-neutral-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-neutral-600 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-sliders-h text-white"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900">Additional Settings</h2>
                        <p class="text-sm text-neutral-600">Configure stacking rules and coupon status</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Stackable with Loyalty Tier -->
                    <div class="flex items-start p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                        <input type="checkbox" name="stackable_with_loyalty_tier" id="stackable_with_loyalty_tier" 
                               value="1" {{ old('stackable_with_loyalty_tier', true) ? 'checked' : '' }}
                               class="mt-1 mr-3 w-5 h-5 text-primary-600 border-2 border-neutral-300 rounded focus:ring-2 focus:ring-primary-500">
                        <div class="flex-1">
                            <label for="stackable_with_loyalty_tier" class="text-sm font-semibold text-neutral-800 cursor-pointer flex items-center">
                                <i class="fas fa-award text-warning-500 mr-2"></i>
                                Allow Combining with Loyalty Tier Discounts
                            </label>
                            <p class="text-xs text-neutral-600 mt-1">Users can use both coupon and their loyalty tier discount together</p>
                        </div>
                    </div>

                    <!-- Stackable with Points -->
                    <div class="flex items-start p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                        <input type="checkbox" name="stackable_with_points" id="stackable_with_points" 
                               value="1" {{ old('stackable_with_points', true) ? 'checked' : '' }}
                               class="mt-1 mr-3 w-5 h-5 text-primary-600 border-2 border-neutral-300 rounded focus:ring-2 focus:ring-primary-200">
                        <div class="flex-1">
                            <label for="stackable_with_points" class="text-sm font-semibold text-neutral-800 cursor-pointer flex items-center">
                                <i class="fas fa-coins text-warning-500 mr-2"></i>
                                Allow Combining with Loyalty Points
                            </label>
                            <p class="text-xs text-neutral-600 mt-1">Users can use both coupon and redeem loyalty points together</p>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Status <span class="text-error-500">*</span>
                        </label>
                        <select name="status" id="status" 
                                class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('status') border-error-500 @enderror" 
                                required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                ✅ Active - Ready to use
                            </option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                ⏸️ Inactive - Not available for use
                            </option>
                        </select>
                        @error('status')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Admin Notes -->
                    <div>
                        <label for="admin_notes" class="block text-sm font-semibold text-neutral-700 mb-2">
                            Admin Notes
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-secondary-100 text-secondary-700 ml-2">
                                <i class="fas fa-lock mr-1"></i> Internal Only
                            </span>
                        </label>
                        <textarea name="admin_notes" id="admin_notes" rows="3" 
                                  class="w-full rounded-lg border-2 border-neutral-300 px-4 py-3 text-neutral-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all @error('admin_notes') border-error-500 @enderror"
                                  placeholder="Internal notes about this coupon (not visible to clients)...">{{ old('admin_notes') }}</textarea>
                        @error('admin_notes')
                            <p class="text-error-500 text-sm mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4 mt-6">
            <a href="{{ route('admin.coupons.index') }}" 
               class="inline-flex items-center px-6 py-3 bg-white border-2 border-neutral-300 hover:border-neutral-400 text-neutral-700 rounded-lg transition-all shadow-sm hover:shadow font-medium">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white rounded-lg transition-all shadow-lg hover:shadow-xl font-semibold">
                <i class="fas fa-save mr-2"></i>
                Create Coupon
            </button>
        </div>
    </form>
</div>
@endsection

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
            hint.innerHTML = '<i class="fas fa-lightbulb text-warning-500"></i> Max: 100%';
        } else {
            prefix.textContent = '₱';
            input.removeAttribute('max');
            hint.innerHTML = '<i class="fas fa-lightbulb text-warning-500"></i> Enter fixed discount amount';
        }
    }

    // Toggle assignment fields based on coupon type
    function toggleAssignmentFields() {
        const type = document.getElementById('coupon_type').value;
        const userField = document.getElementById('user_assignment_field');
        const requestField = document.getElementById('request_assignment_field');

        userField.classList.add('hidden');
        requestField.classList.add('hidden');

        if (type === 'user_specific') {
            userField.classList.remove('hidden');
            document.querySelector('[name="specific_user_id"]').required = true;
            document.querySelector('[name="specific_request_id"]').required = false;
        } else if (type === 'request_specific') {
            requestField.classList.remove('hidden');
            document.querySelector('[name="specific_request_id"]').required = true;
            document.querySelector('[name="specific_user_id"]').required = false;
        } else {
            document.querySelector('[name="specific_user_id"]').required = false;
            document.querySelector('[name="specific_request_id"]').required = false;
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

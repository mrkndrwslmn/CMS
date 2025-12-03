@extends('admin.layouts.app')

@section('title', 'Create Coupon')
@section('page-title', 'Create Coupon')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Coupons', 'route' => 'admin.coupons.index', 'icon' => 'ticket'],
        ['label' => 'Create Coupon', 'icon' => 'plus'],
    ]" class="mb-6" />

    <!-- Header Section -->
    <x-ui.page-header 
        title="Create New Coupon" 
        description="Generate discount coupons for clients and track their usage"
        class="mb-6"
    />

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-4 rounded-lg shadow-sm mb-6">
            <div class="flex items-center">
                <x-lucide-check-circle class="w-5 h-5 text-success-500 mr-3" />
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-4 rounded-lg shadow-sm mb-6">
            <div class="flex items-center">
                <x-lucide-alert-circle class="w-5 h-5 text-error-500 mr-3" />
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Column -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.coupons.store') }}" method="POST" id="couponForm" class="space-y-6">
                @csrf
                <!-- Basic Information Section -->
                <x-ui.card>
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center mr-3">
                    <x-lucide-info class="w-5 h-5 text-primary-600" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Basic Information</h2>
                    <p class="text-sm text-neutral-600">Define the coupon code, name, and discount details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Coupon Code -->
                <div class="md:col-span-2">
                    <label for="code" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Coupon Code <span class="text-error-500">*</span>
                    </label>
                    <div class="flex gap-2">
                        <x-ui.input type="text" name="code" id="code" 
                               :value="old('code')"
                               class="flex-1"
                               placeholder="e.g., WELCOME2024, SAVE50"
                               maxlength="50"
                               required />
                        <x-ui.button type="button" onclick="generateCode()" icon="sparkles">
                            Generate
                        </x-ui.button>
                    </div>
                    @error('code')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <x-lucide-info class="w-3 h-3 text-primary-500 mr-1" /> 
                        Uppercase letters, numbers, and hyphens only
                    </p>
                </div>

                <!-- Coupon Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Coupon Name <span class="text-error-500">*</span>
                    </label>
                    <x-ui.input type="text" name="name" id="name"
                           :value="old('name')"
                           placeholder="e.g., Welcome Discount, First Order Special"
                           maxlength="255"
                           required />
                    @error('name')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Discount Type -->
                <div>
                    <label for="discount_type" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Discount Type <span class="text-error-500">*</span>
                    </label>
                    <x-ui.select name="discount_type" id="discount_type" 
                            onchange="updateDiscountInput()"
                            required>
                        <option value="percentage" {{ old('discount_type', 'percentage') === 'percentage' ? 'selected' : '' }}>
                            Percentage (%)
                        </option>
                        <option value="fixed_amount" {{ old('discount_type') === 'fixed_amount' ? 'selected' : '' }}>
                            Fixed Amount (₱)
                        </option>
                    </x-ui.select>
                    @error('discount_type')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
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
                        <x-ui.input type="number" name="discount_value" id="discount_value" 
                               :value="old('discount_value')"
                               class="pl-10"
                               step="0.01"
                               min="0"
                               max="100"
                               required />
                    </div>
                    @error('discount_value')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-neutral-500 mt-2 flex items-center" id="discount_hint">
                        <x-lucide-lightbulb class="w-3 h-3 text-warning-500 mr-1" /> Max: 100%
                    </p>
                </div>

                <!-- Max Discount Cap -->
                <div class="md:col-span-2">
                    <label for="max_discount_amount" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Maximum Discount Cap (₱)
                        <x-ui.badge variant="neutral" size="sm" class="ml-2">Optional</x-ui.badge>
                    </label>
                    <x-ui.input type="number" name="max_discount_amount" id="max_discount_amount"
                           :value="old('max_discount_amount')"
                           step="0.01"
                           min="0"
                           placeholder="Leave empty for unlimited" />
                    @error('max_discount_amount')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <x-lucide-info class="w-3 h-3 text-neutral-500 mr-1" /> 
                        Limit the maximum discount amount (useful for percentage discounts)
                    </p>
                </div>

                <!-- Minimum Order Amount -->
                <div>
                    <label for="min_purchase_amount" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Minimum Purchase Amount (₱)
                    </label>
                    <x-ui.input type="number" name="min_purchase_amount" id="min_purchase_amount"
                           :value="old('min_purchase_amount', 0)"
                           step="0.01"
                           min="0" />
                    @error('min_purchase_amount')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full px-4 py-2.5 bg-white border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                              placeholder="e.g., Welcome discount for new customers, Limited time offer...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </x-ui.card>

        <!-- Validity Period Section -->
        <x-ui.card>
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-success-100 rounded-xl flex items-center justify-center mr-3">
                    <x-lucide-calendar class="w-5 h-5 text-success-600" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Validity Period</h2>
                    <p class="text-sm text-neutral-600">Set when this coupon can be used</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Valid From -->
                <div>
                    <label for="valid_from" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Valid From <span class="text-error-500">*</span>
                    </label>
                    <x-ui.input type="datetime-local" name="valid_from" id="valid_from"
                           :value="old('valid_from', now()->format('Y-m-d\TH:i'))"
                           required />
                    @error('valid_from')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Valid Until -->
                <div>
                    <label for="valid_until" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Valid Until <span class="text-error-500">*</span>
                    </label>
                    <x-ui.input type="datetime-local" name="valid_until" id="valid_until"
                           :value="old('valid_until', now()->addDays(30)->format('Y-m-d\TH:i'))"
                           required />
                    @error('valid_until')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </x-ui.card>

        <!-- Usage Limits Section -->
        <x-ui.card>
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-warning-100 rounded-xl flex items-center justify-center mr-3">
                    <x-lucide-hash class="w-5 h-5 text-warning-600" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Usage Limits</h2>
                    <p class="text-sm text-neutral-600">Control how many times this coupon can be used</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Uses -->
                <div>
                    <label for="max_total_uses" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Total Uses Allowed
                        <x-ui.badge variant="neutral" size="sm" class="ml-2">Optional</x-ui.badge>
                    </label>
                    <x-ui.input type="number" name="max_total_uses" id="max_total_uses"
                           :value="old('max_total_uses')"
                           min="1"
                           placeholder="Unlimited" />
                    @error('max_total_uses')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <x-lucide-info class="w-3 h-3 text-warning-500 mr-1" /> 
                        Total times this coupon can be used by all users
                    </p>
                </div>

                <!-- Uses Per User -->
                <div>
                    <label for="max_uses_per_user" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Uses Per User <span class="text-error-500">*</span>
                    </label>
                    <x-ui.input type="number" name="max_uses_per_user" id="max_uses_per_user"
                           :value="old('max_uses_per_user', 1)"
                           min="1"
                           required />
                    @error('max_uses_per_user')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-neutral-500 mt-2 flex items-center">
                        <x-lucide-info class="w-3 h-3 text-warning-500 mr-1" /> 
                        How many times each user can use this coupon
                    </p>
                </div>
            </div>
        </x-ui.card>

        <!-- Coupon Type & Assignment Section -->
        <x-ui.card>
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-info-100 rounded-xl flex items-center justify-center mr-3">
                    <x-lucide-users class="w-5 h-5 text-info-600" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Type & Assignment</h2>
                    <p class="text-sm text-neutral-600">Choose who can use this coupon</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Coupon Type -->
                <div>
                    <label for="coupon_type" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Coupon Type <span class="text-error-500">*</span>
                    </label>
                    <x-ui.select name="coupon_type" id="coupon_type" 
                            onchange="toggleAssignmentFields()"
                            required>
                        <option value="public" {{ old('coupon_type', 'public') === 'public' ? 'selected' : '' }}>
                            Public - Available to all users
                        </option>
                        <option value="user_specific" {{ old('coupon_type') === 'user_specific' ? 'selected' : '' }}>
                            Specific User - Assigned to one user
                        </option>
                        <option value="request_specific" {{ old('coupon_type') === 'request_specific' ? 'selected' : '' }}>
                            Specific Request - For a service request
                        </option>
                    </x-ui.select>
                    @error('coupon_type')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- User Assignment (shown when user_specific is selected) -->
                <div id="user_assignment_field" class="hidden">
                    <label for="specific_user_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Assign to User <span class="text-error-500">*</span>
                    </label>
                    <x-ui.select name="specific_user_id" id="specific_user_id">
                        <option value="">Select a client...</option>
                        @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('specific_user_id') == $client->id ? 'selected' : '' }}>
                            {{ $client->fullName }} ({{ $client->email }})
                        </option>
                        @endforeach
                    </x-ui.select>
                    @error('specific_user_id')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Request Assignment (shown when request_specific is selected) -->
                <div id="request_assignment_field" class="hidden">
                    <label for="specific_request_id" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Assign to Service Request <span class="text-error-500">*</span>
                    </label>
                    <x-ui.input type="text" name="specific_request_id" id="specific_request_id"
                           :value="old('specific_request_id')"
                           placeholder="Enter service request ID" />
                    @error('specific_request_id')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </x-ui.card>

        <!-- Additional Settings Section -->
        <x-ui.card>
            <div class="flex items-center mb-6">
                <div class="w-10 h-10 bg-neutral-100 rounded-xl flex items-center justify-center mr-3">
                    <x-lucide-sliders-horizontal class="w-5 h-5 text-neutral-600" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-neutral-900">Additional Settings</h2>
                    <p class="text-sm text-neutral-600">Configure stacking rules and coupon status</p>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Stackable with Loyalty Tier -->
                <div class="flex items-start p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                    <input type="checkbox" name="stackable_with_loyalty_tier" id="stackable_with_loyalty_tier" 
                           value="1" {{ old('stackable_with_loyalty_tier', true) ? 'checked' : '' }}
                           class="mt-1 mr-3 w-5 h-5 text-primary-600 border-2 border-neutral-300 rounded focus:ring-2 focus:ring-primary-500">
                    <div class="flex-1">
                        <label for="stackable_with_loyalty_tier" class="text-sm font-semibold text-neutral-800 cursor-pointer flex items-center">
                            <x-lucide-award class="w-4 h-4 text-warning-500 mr-2" />
                            Allow Combining with Loyalty Tier Discounts
                        </label>
                        <p class="text-xs text-neutral-600 mt-1">Users can use both coupon and their loyalty tier discount together</p>
                    </div>
                </div>

                <!-- Stackable with Points -->
                <div class="flex items-start p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                    <input type="checkbox" name="stackable_with_points" id="stackable_with_points" 
                           value="1" {{ old('stackable_with_points', true) ? 'checked' : '' }}
                           class="mt-1 mr-3 w-5 h-5 text-primary-600 border-2 border-neutral-300 rounded focus:ring-2 focus:ring-primary-200">
                    <div class="flex-1">
                        <label for="stackable_with_points" class="text-sm font-semibold text-neutral-800 cursor-pointer flex items-center">
                            <x-lucide-coins class="w-4 h-4 text-warning-500 mr-2" />
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
                    <x-ui.select name="status" id="status" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                            Active - Ready to use
                        </option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                            Inactive - Not available for use
                        </option>
                    </x-ui.select>
                    @error('status')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Admin Notes -->
                <div>
                    <label for="admin_notes" class="block text-sm font-semibold text-neutral-700 mb-2">
                        Admin Notes
                        <x-ui.badge variant="neutral" size="sm" class="ml-2">
                            <x-lucide-lock class="w-3 h-3 mr-1" /> Internal Only
                        </x-ui.badge>
                    </label>
                    <textarea name="admin_notes" id="admin_notes" rows="3" 
                              class="w-full px-4 py-2.5 bg-white border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all"
                              placeholder="Internal notes about this coupon (not visible to clients)...">{{ old('admin_notes') }}</textarea>
                    @error('admin_notes')
                        <p class="text-error-500 text-sm mt-2 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" /> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </x-ui.card>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 mt-6">
                    <a href="{{ route('admin.coupons.index') }}">
                        <x-ui.button type="button" variant="ghost" icon="x">
                            Cancel
                        </x-ui.button>
                    </a>
                    <x-ui.button type="submit" variant="primary" icon="save">
                        Create Coupon
                    </x-ui.button>
                </div>
            </form>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 space-y-6">
                <!-- Quick Tips Card -->
                <x-ui.card class="border-primary-100 bg-primary-50/30">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <x-lucide-lightbulb class="w-4 h-4 text-primary-600" />
                        </div>
                        <h3 class="font-semibold text-neutral-800">Quick Tips</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-neutral-600">
                        <li class="flex items-start gap-2">
                            <x-lucide-check class="w-4 h-4 text-success-500 mt-0.5 shrink-0" />
                            <span>Use memorable codes like <strong>WELCOME20</strong> or <strong>SUMMER2024</strong></span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-check class="w-4 h-4 text-success-500 mt-0.5 shrink-0" />
                            <span>Set a maximum discount cap to limit losses on high-value orders</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-check class="w-4 h-4 text-success-500 mt-0.5 shrink-0" />
                            <span>Limit uses per user to prevent coupon abuse</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-check class="w-4 h-4 text-success-500 mt-0.5 shrink-0" />
                            <span>Use admin notes to track the campaign purpose</span>
                        </li>
                    </ul>
                </x-ui.card>

                <!-- Coupon Types Info -->
                <x-ui.card>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-info-100 rounded-lg flex items-center justify-center">
                            <x-lucide-info class="w-4 h-4 text-info-600" />
                        </div>
                        <h3 class="font-semibold text-neutral-800">Coupon Types</h3>
                    </div>
                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-neutral-100 rounded flex items-center justify-center shrink-0">
                                <x-lucide-globe class="w-3.5 h-3.5 text-neutral-600" />
                            </div>
                            <div>
                                <p class="font-medium text-neutral-700">Public</p>
                                <p class="text-neutral-500">Anyone can use this code</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-neutral-100 rounded flex items-center justify-center shrink-0">
                                <x-lucide-user class="w-3.5 h-3.5 text-neutral-600" />
                            </div>
                            <div>
                                <p class="font-medium text-neutral-700">User Specific</p>
                                <p class="text-neutral-500">Only assigned user can use</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 bg-neutral-100 rounded flex items-center justify-center shrink-0">
                                <x-lucide-file-text class="w-3.5 h-3.5 text-neutral-600" />
                            </div>
                            <div>
                                <p class="font-medium text-neutral-700">Request Specific</p>
                                <p class="text-neutral-500">Tied to a service request</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card>

                <!-- Stacking Info -->
                <x-ui.card class="border-warning-100 bg-warning-50/30">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-warning-100 rounded-lg flex items-center justify-center">
                            <x-lucide-layers class="w-4 h-4 text-warning-600" />
                        </div>
                        <h3 class="font-semibold text-neutral-800">About Stacking</h3>
                    </div>
                    <p class="text-sm text-neutral-600 mb-3">
                        Stacking allows coupons to be combined with other discounts:
                    </p>
                    <ul class="space-y-2 text-sm text-neutral-600">
                        <li class="flex items-start gap-2">
                            <x-lucide-award class="w-4 h-4 text-warning-500 mt-0.5 shrink-0" />
                            <span><strong>Loyalty Tier:</strong> Member discount benefits</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <x-lucide-coins class="w-4 h-4 text-warning-500 mt-0.5 shrink-0" />
                            <span><strong>Points:</strong> Redeemable loyalty points</span>
                        </li>
                    </ul>
                </x-ui.card>
            </div>
        </div>
    </div>
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
            hint.innerHTML = '<svg class="w-3 h-3 text-warning-500 inline mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg> Max: 100%';
        } else {
            prefix.textContent = '₱';
            input.removeAttribute('max');
            hint.innerHTML = '<svg class="w-3 h-3 text-warning-500 inline mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A6 6 0 0 0 6 8c0 1 .2 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5"/><path d="M9 18h6"/><path d="M10 22h4"/></svg> Enter fixed discount amount';
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

@extends('admin.layouts.app')

@section('title', 'Coupon Management')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-neutral-800 mb-2">Coupon Management</h1>
            <p class="text-neutral-600">Create and manage discount coupons</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Create Coupon
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card p-6 bg-gradient-to-br from-primary-50 to-primary-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-primary-600 mb-1">Total Coupons</p>
                    <p class="text-3xl font-bold text-primary-700">{{ $stats['total_coupons'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ticket-alt text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-success-50 to-success-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-success-600 mb-1">Active Coupons</p>
                    <p class="text-3xl font-bold text-success-700">{{ $stats['active_coupons'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-success-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-warning-50 to-warning-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-warning-600 mb-1">Redemptions</p>
                    <p class="text-3xl font-bold text-warning-700">{{ $stats['total_redemptions'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-warning-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-white text-xl"></i>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 bg-gradient-to-br from-info-50 to-info-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-info-600 mb-1">Total Discounts</p>
                    <p class="text-3xl font-bold text-info-700">₱{{ number_format($stats['total_discount_given'] ?? 0, 0) }}</p>
                </div>
                <div class="w-12 h-12 bg-info-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tag text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card p-6 mb-6">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Code or name..." class="form-input w-full">
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                <select name="status" class="form-select w-full">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Type</label>
                <select name="type" class="form-select w-full">
                    <option value="">All Types</option>
                    <option value="public" {{ request('type') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="user_specific" {{ request('type') === 'user_specific' ? 'selected' : '' }}>User Specific</option>
                    <option value="request_specific" {{ request('type') === 'request_specific' ? 'selected' : '' }}>Request Specific</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Sort By</label>
                <select name="sort_by" class="form-select w-full">
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="current_uses" {{ request('sort_by') === 'current_uses' ? 'selected' : '' }}>Usage Count</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Coupons Table -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Discount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Usage</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Valid Until</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-neutral-200">
                    @forelse($coupons as $coupon)
                    <tr class="hover:bg-neutral-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="font-mono font-bold text-primary-600">{{ $coupon->code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-neutral-900">{{ $coupon->name }}</div>
                            @if($coupon->description)
                            <div class="text-xs text-neutral-500 truncate max-w-xs">{{ $coupon->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-success-100 text-success-800">
                                {{ $coupon->getDiscountLabel() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm text-neutral-600">{{ $coupon->getCouponTypeLabel() }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-neutral-900">{{ $coupon->current_uses }} / {{ $coupon->max_total_uses ?? '∞' }}</div>
                            @if($coupon->max_total_uses)
                            <div class="w-full bg-neutral-200 rounded-full h-1.5 mt-1">
                                <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ $coupon->getUsagePercentage() }}%"></div>
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                            {{ $coupon->valid_until ? $coupon->valid_until->format('M d, Y') : 'No expiry' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $coupon->status === 'active' ? 'bg-success-100 text-success-800' : '' }}
                                {{ $coupon->status === 'inactive' ? 'bg-warning-100 text-warning-800' : '' }}
                                {{ $coupon->status === 'expired' ? 'bg-error-100 text-error-800' : '' }}">
                                {{ ucfirst($coupon->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.coupons.show', $coupon) }}" class="text-info-600 hover:text-info-900" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-warning-600 hover:text-warning-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-{{ $coupon->status === 'active' ? 'warning' : 'success' }}-600 hover:text-{{ $coupon->status === 'active' ? 'warning' : 'success' }}-900" 
                                            title="{{ $coupon->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $coupon->status === 'active' ? 'pause' : 'play' }}-circle"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error-600 hover:text-error-900" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-ticket-alt text-neutral-300 text-6xl mb-4"></i>
                                <p class="text-neutral-500 text-lg mb-2">No coupons found</p>
                                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary mt-4">
                                    <i class="fas fa-plus mr-2"></i>Create Your First Coupon
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($coupons->hasPages())
        <div class="px-6 py-4 border-t border-neutral-200">
            {{ $coupons->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

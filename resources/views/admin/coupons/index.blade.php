@extends('admin.layouts.app')

@section('title', 'Coupon Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Coupons', 'icon' => 'ticket'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Coupon Management" 
            description="Create and manage discount coupons"
        />
        <a href="{{ route('admin.coupons.create') }}">
            <x-ui.button icon="plus">
                Create Coupon
            </x-ui.button>
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card 
            label="Total Coupons" 
            :value="$stats['total_coupons'] ?? 0" 
            icon="ticket"
        />
        <x-ui.stat-card 
            label="Active Coupons" 
            :value="$stats['active_coupons'] ?? 0" 
            icon="check-circle"
            iconBg="success"
        />
        <x-ui.stat-card 
            label="Redemptions" 
            :value="$stats['total_redemptions'] ?? 0" 
            icon="shopping-cart"
            iconBg="warning"
        />
        <x-ui.stat-card 
            label="Total Discounts" 
            :value="'₱' . number_format($stats['total_discount_given'] ?? 0, 0)" 
            icon="tag"
            iconBg="primary"
        />
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Search</label>
                <x-ui.input 
                    type="text" 
                    name="search" 
                    :value="request('search')" 
                    placeholder="Code or name..."
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Status</label>
                <x-ui.select name="status">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </x-ui.select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Type</label>
                <x-ui.select name="type">
                    <option value="">All Types</option>
                    <option value="public" {{ request('type') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="user_specific" {{ request('type') === 'user_specific' ? 'selected' : '' }}>User Specific</option>
                    <option value="request_specific" {{ request('type') === 'request_specific' ? 'selected' : '' }}>Request Specific</option>
                </x-ui.select>
            </div>
            <div>
                <label class="block text-sm font-medium text-neutral-700 mb-2">Sort By</label>
                <x-ui.select name="sort_by">
                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Created Date</option>
                    <option value="name" {{ request('sort_by') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="current_uses" {{ request('sort_by') === 'current_uses' ? 'selected' : '' }}>Usage Count</option>
                </x-ui.select>
            </div>
            <div class="flex items-end gap-2">
                <x-ui.button type="submit" variant="primary" class="flex-1">
                    <x-lucide-search class="w-4 h-4 mr-2" />
                    Filter
                </x-ui.button>
                <a href="{{ route('admin.coupons.index') }}">
                    <x-ui.button type="button" variant="ghost">
                        <x-lucide-refresh-cw class="w-4 h-4" />
                    </x-ui.button>
                </a>
            </div>
        </form>
    </x-ui.card>

    <!-- Coupons Table -->
    <x-ui.card noPadding>
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
                            <x-ui.badge variant="success">
                                {{ $coupon->getDiscountLabel() }}
                            </x-ui.badge>
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
                            @php
                                $statusVariant = match($coupon->status) {
                                    'active' => 'success',
                                    'inactive' => 'warning',
                                    'expired' => 'error',
                                    default => 'neutral'
                                };
                            @endphp
                            <x-ui.badge :variant="$statusVariant">
                                {{ ucfirst($coupon->status) }}
                            </x-ui.badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.coupons.show', $coupon) }}" class="text-info-600 hover:text-info-900" title="View">
                                    <x-lucide-eye class="w-4 h-4" />
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-warning-600 hover:text-warning-900" title="Edit">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-{{ $coupon->status === 'active' ? 'warning' : 'success' }}-600 hover:text-{{ $coupon->status === 'active' ? 'warning' : 'success' }}-900" 
                                            title="{{ $coupon->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                        @if($coupon->status === 'active')
                                            <x-lucide-pause class="w-4 h-4" />
                                        @else
                                            <x-lucide-play class="w-4 h-4" />
                                        @endif
                                    </button>
                                </form>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error-600 hover:text-error-900" title="Delete">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <x-ui.empty-state
                                icon="ticket"
                                title="No coupons found"
                                description="Get started by creating your first discount coupon"
                            >
                                <a href="{{ route('admin.coupons.create') }}">
                                    <x-ui.button icon="plus">
                                        Create Your First Coupon
                                    </x-ui.button>
                                </a>
                            </x-ui.empty-state>
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
    </x-ui.card>
</div>
@endsection

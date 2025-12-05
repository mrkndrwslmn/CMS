@extends('admin.layouts.app')

@section('title', 'Payout Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Payouts', 'icon' => 'wallet'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-header 
            title="Payout Management" 
            description="Review and process adiutor payout requests"
        />
        
        <a href="{{ route('admin.payouts.export', request()->query()) }}">
            <x-ui.button variant="secondary">
                <x-lucide-download class="w-4 h-4" />
                Export CSV
            </x-ui.button>
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-success-50 border border-success-200 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <x-lucide-check-circle class="w-5 h-5 text-success-500 flex-shrink-0" />
                <p class="text-sm text-success-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-error-50 border border-error-200 rounded-xl p-4">
            <div class="flex gap-3">
                <x-lucide-x-circle class="w-5 h-5 text-error-500 flex-shrink-0" />
                <ul class="text-sm text-error-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['pending'] }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Processing</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['processing'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-loader-2 class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Completed (Month)</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">{{ $stats['completed'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Total (Month)</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">₱{{ number_format($stats['total_this_month'], 2) }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-banknote class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filters -->
    <x-ui.card class="overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
            <h3 class="text-base font-medium text-neutral-700">Filters</h3>
        </div>
        <form method="GET" action="{{ route('admin.payouts.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-1.5">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>

                <div>
                    <label for="adiutor" class="block text-sm font-medium text-neutral-700 mb-1.5">Adiutor</label>
                    <select name="adiutor" id="adiutor" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Adiutors</option>
                        @foreach($adiutors as $adiutor)
                            <option value="{{ $adiutor->id }}" {{ request('adiutor') == $adiutor->id ? 'selected' : '' }}>
                                {{ $adiutor->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-neutral-700 mb-1.5">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-neutral-700 mb-1.5">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-search class="w-4 h-4" />
                    Apply Filters
                </x-ui.button>
                <a href="{{ route('admin.payouts.index') }}">
                    <x-ui.button type="button" variant="ghost">
                        Clear
                    </x-ui.button>
                </a>
            </div>
        </form>
    </x-ui.card>

    <!-- Payouts Table -->
    <x-ui.card class="overflow-hidden">
        @if($payouts->isEmpty())
            <div class="px-6 py-16 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-neutral-100 mb-4">
                    <x-lucide-wallet class="w-8 h-8 text-neutral-400" />
                </div>
                <p class="text-neutral-600 text-base font-medium">No payout requests found</p>
                <p class="text-neutral-400 text-sm mt-1">Try adjusting your filters</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Payout #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Adiutor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-100">
                        @foreach($payouts as $payout)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-neutral-800">{{ $payout->payout_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $payout->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($payout->adiutor->fullName) }}" 
                                         alt="{{ $payout->adiutor->fullName }}" 
                                         class="w-8 h-8 rounded-full">
                                    <div>
                                        <div class="text-sm font-medium text-neutral-800">{{ $payout->adiutor->fullName }}</div>
                                        <div class="text-xs text-neutral-400">{{ $payout->adiutor->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-neutral-800">₱{{ number_format($payout->amount, 2) }}</div>
                                <div class="text-xs text-neutral-400">{{ $payout->items->count() }} items</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                {{ ucwords(str_replace('_', ' ', $payout->payout_method ?? 'N/A')) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payout->status === 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">Paid</span>
                                @elseif($payout->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-700">Approved</span>
                                @elseif($payout->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700">Rejected</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">Pending</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-neutral-700">{{ $payout->requested_at->format('M d, Y') }}</div>
                                <div class="text-xs text-neutral-400">{{ $payout->requested_at->format('g:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.payouts.show', $payout->id) }}" 
                                   class="inline-flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium transition-colors">
                                    View
                                    <x-lucide-chevron-right class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payouts->hasPages())
            <div class="px-6 py-4 border-t border-neutral-100">
                <x-ui.pagination :paginator="$payouts" />
            </div>
            @endif
        @endif
    </x-ui.card>
</div>
@endsection

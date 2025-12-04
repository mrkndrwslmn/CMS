@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.4s ease forwards;
    }
    
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
</style>
@endpush

@section('content')
<div class="px-6 py-8" 
     data-monthly-users="{{ json_encode($monthlyUsers ?? []) }}" 
     data-task-stats="{{ json_encode($taskStats ?? []) }}">
    
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-semibold text-neutral-800">Dashboard</h1>
            <p class="text-sm text-neutral-500 mt-1">Welcome back, <span class="font-medium text-neutral-700">{{ Auth::user()->fullName }}</span></p>
        </div>
        <div class="flex items-center gap-3">
            <span class="text-sm text-neutral-400 hidden sm:block" data-timestamp>{{ now()->format('M d, Y') }}</span>
            <button x-data="{ spinning: false }"
                    @click="spinning = true; setTimeout(() => { spinning = false; refreshDashboard(); }, 1500)"
                    class="p-2 rounded-lg bg-white border border-neutral-200 hover:bg-neutral-50 hover:shadow-sm transition-all"
                    title="Refresh">
                <x-lucide-refresh-cw class="w-4 h-4 text-neutral-500" ::class="{ 'animate-spin': spinning }" />
            </button>
            <a href="{{ route('admin.profile') }}" class="p-2 rounded-lg bg-white border border-neutral-200 hover:bg-neutral-50 hover:shadow-sm transition-all" title="Settings">
                <x-lucide-settings class="w-4 h-4 text-neutral-500" />
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Users Card -->
        <div class="animate-fade-in delay-100 opacity-0">
            <x-ui.card class="p-5" padding="none">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Total Users</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1" data-stat="total-users">{{ number_format($stats['total_users']) }}</p>
                        <p class="text-xs text-success-600 mt-2 flex items-center gap-1">
                            <x-lucide-trending-up class="w-3.5 h-3.5" />
                            12% from last month
                        </p>
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-users class="w-5 h-5 text-primary-500" />
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Active Tasks Card -->
        <div class="animate-fade-in delay-200 opacity-0">
            <x-ui.card class="p-5" padding="none">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Active Tasks</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1" data-stat="active-tasks">{{ number_format($stats['active_tasks']) }}</p>
                        <p class="text-xs text-primary-600 mt-2 flex items-center gap-1">
                            <x-lucide-clock class="w-3.5 h-3.5" />
                            In progress
                        </p>
                    </div>
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-list-checks class="w-5 h-5 text-primary-500" />
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Pending Requests Card -->
        <div class="animate-fade-in delay-300 opacity-0">
            <x-ui.card class="p-5" padding="none">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Pending</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1" data-stat="pending-requests">{{ number_format($stats['pending_requests']) }}</p>
                        <p class="text-xs text-warning-600 mt-2 flex items-center gap-1">
                            <x-lucide-alert-circle class="w-3.5 h-3.5" />
                            Needs review
                        </p>
                    </div>
                    <div class="p-3 bg-warning-50 rounded-xl">
                        <x-lucide-clipboard-list class="w-5 h-5 text-warning-500" />
                    </div>
                </div>
            </x-ui.card>
        </div>

        <!-- Completed Tasks Card -->
        <div class="animate-fade-in delay-400 opacity-0">
            <x-ui.card class="p-5" padding="none">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-500">Completed</p>
                        <p class="text-2xl font-semibold text-neutral-800 mt-1" data-stat="completed-tasks">{{ number_format($stats['completed_tasks']) }}</p>
                        <p class="text-xs text-success-600 mt-2 flex items-center gap-1">
                            <x-lucide-check-circle class="w-3.5 h-3.5" />
                            All time
                        </p>
                    </div>
                    <div class="p-3 bg-success-50 rounded-xl">
                        <x-lucide-trophy class="w-5 h-5 text-success-500" />
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>

        <!-- Earnings Analytics Widget -->
        @if(isset($earningsStats))
        <div class="animate-fade-in opacity-0 mb-8">
            <x-ui.card padding="none">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 rounded-xl bg-primary-50">
                            <x-lucide-trending-up class="w-5 h-5 text-primary-500" />
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-neutral-800">Financial Overview</h2>
                            <p class="text-xs text-neutral-500">Revenue & earnings analytics</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.earnings-analytics.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium flex items-center gap-1.5">
                        <span>View Details</span>
                        <x-lucide-arrow-right class="w-4 h-4" />
                    </a>
                </div>
                
                <!-- Main Stats Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-neutral-100">
                    <!-- Total Revenue -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-medium text-neutral-500">Total Revenue</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-success-50 text-success-600 font-medium">
                                {{ $earningsStats['completed_projects'] }} projects
                            </span>
                        </div>
                        <p class="text-3xl font-semibold text-neutral-800 tracking-tight">₱{{ number_format($earningsStats['total_earnings'], 2) }}</p>
                        <p class="text-xs text-neutral-500 mt-2">Lifetime earnings from completed projects</p>
                    </div>
                    
                    <!-- This Month -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-medium text-neutral-500">This Month</p>
                            @if($earningsStats['monthly_growth'] >= 0)
                                <span class="text-xs px-2 py-0.5 rounded-full bg-success-50 text-success-600 font-medium flex items-center gap-1">
                                    <x-lucide-trending-up class="w-3 h-3" />
                                    {{ $earningsStats['monthly_growth'] }}%
                                </span>
                            @else
                                <span class="text-xs px-2 py-0.5 rounded-full bg-error-50 text-error-600 font-medium flex items-center gap-1">
                                    <x-lucide-trending-down class="w-3 h-3" />
                                    {{ abs($earningsStats['monthly_growth']) }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-3xl font-semibold text-neutral-800 tracking-tight">₱{{ number_format($earningsStats['this_month_earnings'], 2) }}</p>
                        <p class="text-xs text-neutral-500 mt-2">{{ now()->format('F Y') }} revenue</p>
                    </div>
                    
                    <!-- Adiutor Payouts -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-medium text-neutral-500">Adiutor Earnings</p>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-primary-50 text-primary-600 font-medium">
                                This month
                            </span>
                        </div>
                        <p class="text-3xl font-semibold text-neutral-800 tracking-tight">₱{{ number_format($earningsStats['adiutor_earnings_this_month'], 2) }}</p>
                        <p class="text-xs text-neutral-500 mt-2">Approved time entries</p>
                    </div>
                </div>
                
                <!-- Action Items Row -->
                <div class="bg-neutral-50 px-6 py-4 border-t border-neutral-100">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-neutral-600">Action Items</p>
                        <div class="flex items-center gap-6">
                            <!-- Pending Approvals -->
                            <a href="{{ route('admin.payouts.index') }}" class="flex items-center gap-2 group">
                                <div class="flex items-center gap-1.5">
                                    @if($earningsStats['pending_approvals'] > 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-warning-500 animate-pulse"></span>
                                    @endif
                                    <span class="text-sm text-neutral-600 group-hover:text-primary-500 transition-colors">{{ $earningsStats['pending_approvals'] }} pending approvals</span>
                                </div>
                                <x-lucide-chevron-right class="w-3 h-3 text-neutral-400 group-hover:text-primary-500 transition-colors" />
                            </a>
                            
                            <!-- Pending Payouts -->
                            <a href="{{ route('admin.payouts.index', ['status' => 'pending']) }}" class="flex items-center gap-2 group">
                                <div class="flex items-center gap-1.5">
                                    @if($earningsStats['pending_payouts'] > 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-secondary-500 animate-pulse"></span>
                                    @endif
                                    <span class="text-sm text-neutral-600 group-hover:text-primary-500 transition-colors">₱{{ number_format($earningsStats['pending_payouts'], 2) }} pending payouts</span>
                                </div>
                                <x-lucide-chevron-right class="w-3 h-3 text-neutral-400 group-hover:text-primary-500 transition-colors" />
                            </a>
                            
                            <!-- Hour Requests -->
                            <a href="{{ route('admin.hour-requests.index') }}" class="flex items-center gap-2 group">
                                <div class="flex items-center gap-1.5">
                                    @if($earningsStats['pending_hour_requests'] > 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                                    @endif
                                    <span class="text-sm text-neutral-600 group-hover:text-primary-500 transition-colors">{{ $earningsStats['pending_hour_requests'] }} hour requests</span>
                                </div>
                                <x-lucide-chevron-right class="w-3 h-3 text-neutral-400 group-hover:text-primary-500 transition-colors" />
                            </a>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
        @endif

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Monthly User Growth Chart -->
            <div class="lg:col-span-2 animate-fade-in opacity-0">
                <x-ui.card padding="none" class="h-full">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <div>
                            <h2 class="text-sm font-semibold text-neutral-800">User Growth</h2>
                            <p class="text-xs text-neutral-500 mt-0.5">Monthly registration trends</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-sm px-3 py-1.5 rounded-lg bg-neutral-100 hover:bg-neutral-200 text-neutral-600 transition-colors flex items-center gap-1.5">
                                    <span x-text="$store.timeFilter ? $store.timeFilter : 'This Year'">This Year</span>
                                    <x-lucide-chevron-down class="w-3.5 h-3.5" x-bind:class="{'rotate-180': open}" />
                                </button>
                                <ul x-show="open" 
                                    @click.outside="open = false"
                                    x-transition
                                    class="absolute right-0 mt-1 w-32 bg-white rounded-lg shadow-lg border border-neutral-200 py-1 z-10"
                                    style="display: none;">
                                    <li><a @click="$store.timeFilter = 'This Year'; open = false" class="block px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-50 cursor-pointer">This Year</a></li>
                                    <li><a @click="$store.timeFilter = 'Last Year'; open = false" class="block px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-50 cursor-pointer">Last Year</a></li>
                                    <li><a @click="$store.timeFilter = 'All Time'; open = false" class="block px-3 py-1.5 text-sm text-neutral-600 hover:bg-neutral-50 cursor-pointer">All Time</a></li>
                                </ul>
                            </div>
                            <button @click="downloadReport()" class="p-2 rounded-lg hover:bg-neutral-100 transition-colors" title="Download">
                                <x-lucide-download class="w-4 h-4 text-neutral-500" />
                            </button>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="h-64">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Task Status Pie Chart -->
            <div class="animate-fade-in opacity-0">
                <x-ui.card padding="none" class="h-full">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <div>
                            <h2 class="text-sm font-semibold text-neutral-800">Task Distribution</h2>
                            <p class="text-xs text-neutral-500 mt-0.5">Current status overview</p>
                        </div>
                        <button x-data="{ spinning: false }"
                                @click="spinning = true; setTimeout(() => { spinning = false }, 1500)"
                                class="p-2 rounded-lg hover:bg-neutral-100 transition-colors" 
                                title="Refresh">
                            <x-lucide-refresh-cw class="w-4 h-4 text-neutral-500" ::class="{ 'animate-spin': spinning }" />
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="h-44">
                            <canvas id="taskStatusChart"></canvas>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <div class="text-center p-2 rounded-lg bg-primary-50">
                                <p class="text-xs text-neutral-500">Active</p>
                                <p class="text-sm font-semibold text-primary-600">{{ $stats['active_tasks'] }}</p>
                            </div>
                            <div class="text-center p-2 rounded-lg bg-warning-50">
                                <p class="text-xs text-neutral-500">Pending</p>
                                <p class="text-sm font-semibold text-warning-600">{{ $stats['pending_requests'] }}</p>
                            </div>
                            <div class="text-center p-2 rounded-lg bg-success-50">
                                <p class="text-xs text-neutral-500">Done</p>
                                <p class="text-sm font-semibold text-success-600">{{ $stats['completed_tasks'] }}</p>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        </div>

        <!-- Content Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Users -->
            <div class="animate-fade-in opacity-0">
                <x-ui.card padding="none">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <h2 class="text-sm font-semibold text-neutral-800">Recent Users</h2>
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium flex items-center gap-1">
                            View all <x-lucide-arrow-right class="w-4 h-4" />
                        </a>
                    </div>
                    <div class="p-5">
                        @if($stats['recent_users']->count() > 0)
                            <div class="space-y-3">
                                @foreach($stats['recent_users'] as $user)
                                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-neutral-50' : '' }}">
                                    <div class="flex items-center gap-3">
                                        @if($user->profilePic)
                                            <img src="{{ $user->getProfilePictureUrl() }}" class="h-9 w-9 rounded-full object-cover" alt="{{ $user->fullName }}">
                                        @else
                                            <div class="h-9 w-9 rounded-full bg-primary-100 flex items-center justify-center">
                                                <span class="text-primary-600 text-xs font-medium">{{ substr($user->fullName, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-neutral-800">{{ $user->fullName }}</p>
                                            <p class="text-xs text-neutral-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @php
                                            $roleClasses = [
                                                'admin' => 'bg-secondary-50 text-secondary-600',
                                                'client' => 'bg-primary-50 text-primary-600',
                                                'adiutor' => 'bg-success-50 text-success-600'
                                            ];
                                            $roleClass = $roleClasses[$user->role] ?? 'bg-neutral-100 text-neutral-600';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                        <span class="text-xs text-neutral-500">{{ $user->created_at->format('M d') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                                    <x-lucide-users class="w-5 h-5 text-neutral-400" />
                                </div>
                                <p class="text-sm text-neutral-500">No recent users</p>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            </div>

            <!-- Recent Requests -->
            <div class="animate-fade-in opacity-0">
                <x-ui.card padding="none">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <h2 class="text-sm font-semibold text-neutral-800">Service Requests</h2>
                        <a href="{{ route('admin.requests.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium flex items-center gap-1">
                            View all <x-lucide-arrow-right class="w-4 h-4" />
                        </a>
                    </div>
                    <div class="p-5">
                        @if($stats['recent_requests']->count() > 0)
                            <div class="space-y-3">
                                @foreach($stats['recent_requests'] as $request)
                                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-neutral-50' : '' }}">
                                    <div>
                                        <p class="text-sm font-medium text-neutral-800">{{ $request->user->fullName }}</p>
                                        <p class="text-xs text-neutral-500">{{ $request->company_name ?? 'Individual' }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning-50 text-warning-600',
                                                'approved' => 'bg-success-50 text-success-600',
                                                'rejected' => 'bg-error-50 text-error-600',
                                                'processing' => 'bg-primary-50 text-primary-600'
                                            ];
                                            $statusClass = $statusClasses[$request->status] ?? $statusClasses['pending'];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-xs font-medium {{ $statusClass }}">{{ ucfirst($request->status) }}</span>
                                        <span class="text-xs text-neutral-500">{{ $request->submission_date ? $request->submission_date->format('M d') : 'N/A' }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                                    <x-lucide-clipboard-list class="w-5 h-5 text-neutral-400" />
                                </div>
                                <p class="text-sm text-neutral-500">No recent requests</p>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="animate-fade-in opacity-0">
            <x-ui.card padding="none">
                <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                    <h2 class="text-sm font-semibold text-neutral-800">Quick Actions</h2>
                    <button class="p-2 rounded-lg text-neutral-400 hover:text-neutral-600 hover:bg-neutral-50 transition-colors" id="customizeActions" title="Customize">
                        <x-lucide-sliders-horizontal class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-3 sm:grid-cols-7 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-primary-50 group-hover:bg-primary-100 flex items-center justify-center transition-colors mb-2">
                                <x-lucide-users class="w-5 h-5 text-primary-500" />
                            </div>
                            <p class="text-xs font-medium text-neutral-700">Users</p>
                            <p class="text-xs text-neutral-500">{{ $stats['total_users'] }}</p>
                        </a>
                        <a href="{{ route('admin.requests.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-warning-50 group-hover:bg-warning-100 flex items-center justify-center transition-colors mb-2">
                                <x-lucide-clipboard-list class="w-5 h-5 text-warning-500" />
                            </div>
                            <p class="text-xs font-medium text-neutral-700">Requests</p>
                            <p class="text-xs text-neutral-500">{{ $stats['pending_requests'] }} pending</p>
                        </a>
                        <a href="{{ route('admin.tasks.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-primary-50 group-hover:bg-primary-100 flex items-center justify-center transition-colors mb-2">
                                <x-lucide-list-checks class="w-5 h-5 text-primary-500" />
                            </div>
                            <p class="text-xs font-medium text-neutral-700">Tasks</p>
                            <p class="text-xs text-neutral-500">{{ $stats['active_tasks'] }} active</p>
                        </a>
                        <a href="{{ route('admin.earnings-analytics.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-success-50 group-hover:bg-success-100 flex items-center justify-center transition-colors mb-2">
                                <x-lucide-wallet class="w-5 h-5 text-success-500" />
                            </div>
                            <p class="text-xs font-medium text-neutral-700">Earnings</p>
                            <p class="text-xs text-neutral-500">Analytics</p>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-secondary-50 group-hover:bg-secondary-100 flex items-center justify-center transition-colors mb-2">
                                <x-lucide-bar-chart-3 class="w-5 h-5 text-secondary-500" />
                            </div>
                            <p class="text-xs font-medium text-neutral-700">Reports</p>
                            <p class="text-xs text-neutral-500">Analytics</p>
                        </a>
                        <a href="{{ route('admin.documents.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-neutral-100 group-hover:bg-neutral-200 flex items-center justify-center transition-colors mb-2">
                                <x-lucide-folder-open class="w-5 h-5 text-neutral-600" />
                            </div>
                            <p class="text-xs font-medium text-neutral-700">Documents</p>
                            <p class="text-xs text-neutral-500">Files</p>
                        </a>
                        <a href="{{ route('admin.profile') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-accent-50 group-hover:bg-accent-100 flex items-center justify-center transition-colors mb-2">
                                <x-lucide-user class="w-5 h-5 text-accent-500" />
                            </div>
                            <p class="text-xs font-medium text-neutral-700">Profile</p>
                            <p class="text-xs text-neutral-500">Settings</p>
                        </a>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize Alpine.js store for state management
    document.addEventListener('alpine:init', () => {
        Alpine.store('timeFilter', 'This Year');
    });
    
    // Function to download report
    function downloadReport() {
        const timeFilter = Alpine.store('timeFilter');
        
        // Show loading state
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4 animate-spin inline mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...';
        button.disabled = true;
        
        // Create download URL with filter parameter
        const downloadUrl = `{{ route('admin.dashboard.download') }}?filter=${encodeURIComponent(timeFilter)}`;
        
        // Create temporary link for download
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = `dashboard_report_${timeFilter.toLowerCase().replace(/\s+/g, '_')}_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Reset button state
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
            
            // Show success notification
            showToast('success', 'Report downloaded successfully');
        }, 1500);
    }
    
    // Function to refresh dashboard data
    function refreshDashboard() {
        
        // Show loading toast
        showToast('info', 'Refreshing dashboard data...', 2000);
        
        // Fetch fresh data from server
        fetch('{{ route('admin.dashboard.refresh') }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                // Update stats cards
                updateStatsCards(data.data.stats);
                
                // Update charts
                updateCharts(data.data.monthlyUsers, data.data.taskStats);
                
                // Update timestamp
                updateLastRefreshed(data.data.timestamp);
                
                // Show success notification
                showToast('success', 'Dashboard data refreshed successfully');
            } else {
                throw new Error(data.message || 'Failed to refresh data');
            }
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            showToast('error', 'Failed to refresh dashboard data');
        });
    }
    
    // Helper function to show toast notifications
    function showToast(type, message, duration = 3000) {
        const toastEl = document.createElement('div');
        
        // Define toast styles based on type
        const typeStyles = {
            success: 'bg-success-50 text-success-600 border-success-200',
            error: 'bg-error-50 text-error-600 border-error-200',
            info: 'bg-info-50 text-info-600 border-info-200',
            warning: 'bg-warning-50 text-warning-600 border-warning-200'
        };
        
        // SVG icons for each type (Lucide-style)
        const typeIcons = {
            success: '<svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline stroke-linecap="round" stroke-linejoin="round" points="22 4 12 14.01 9 11.01"></polyline></svg>',
            error: '<svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
            info: '<svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
            warning: '<svg class="w-4 h-4 mr-2 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>'
        };
        
        toastEl.className = `fixed bottom-4 right-4 ${typeStyles[type]} border px-4 py-3 rounded-xl shadow-lg z-50 flex items-center transition-all transform translate-x-full opacity-0`;
        toastEl.innerHTML = `${typeIcons[type]} ${message}`;
        document.body.appendChild(toastEl);
        
        // Animate in
        setTimeout(() => {
            toastEl.classList.remove('translate-x-full', 'opacity-0');
        }, 100);
        
        // Remove toast after duration
        setTimeout(() => {
            toastEl.classList.add('opacity-0', 'translate-x-full');
            setTimeout(() => {
                if (document.body.contains(toastEl)) {
                    document.body.removeChild(toastEl);
                }
            }, 300);
        }, duration);
    }
    
    // Function to update stats cards
    function updateStatsCards(stats) {
        // Update total users
        const totalUsersElement = document.querySelector('[data-stat="total-users"]');
        if (totalUsersElement) {
            totalUsersElement.textContent = stats.total_users.toLocaleString();
        }
        
        // Update active tasks
        const activeTasksElement = document.querySelector('[data-stat="active-tasks"]');
        if (activeTasksElement) {
            activeTasksElement.textContent = stats.active_tasks.toLocaleString();
        }
        
        // Update pending requests
        const pendingRequestsElement = document.querySelector('[data-stat="pending-requests"]');
        if (pendingRequestsElement) {
            pendingRequestsElement.textContent = stats.pending_requests.toLocaleString();
        }
        
        // Update completed tasks
        const completedTasksElement = document.querySelector('[data-stat="completed-tasks"]');
        if (completedTasksElement) {
            completedTasksElement.textContent = stats.completed_tasks.toLocaleString();
        }
    }
    
    // Function to update charts with new data
    function updateCharts(monthlyUsers, taskStats) {
        // Update user growth chart
        if (window.userGrowthChart && monthlyUsers) {
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            window.userGrowthChart.data.labels = monthlyUsers.map(item => months[item.month - 1]);
            window.userGrowthChart.data.datasets[0].data = monthlyUsers.map(item => item.count);
            window.userGrowthChart.update('active');
        }
        
        // Update task status chart
        if (window.taskStatusChart && taskStats) {
            window.taskStatusChart.data.labels = taskStats.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1));
            window.taskStatusChart.data.datasets[0].data = taskStats.map(item => item.count);
            window.taskStatusChart.update('active');
        }
    }
    
    // Function to update last refreshed timestamp
    function updateLastRefreshed(timestamp) {
        const timestampElement = document.querySelector('[data-timestamp]');
        if (timestampElement) {
            timestampElement.textContent = `Last updated: ${timestamp}`;
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // TREIS ADIUTOR Brand Color System
        const brandColors = {
            primary: '#1E293B',    // Deep Tech Navy
            accent: '#00D4FF',     // Electric Cyan
            secondary: '#A855F7',  // Modern Purple
            tertiary: '#FF6B35',   // Vibrant Orange
            success: '#10B981',    // Emerald Green
            neutral: '#64748B',    // Slate
            
            // Color variations with opacity
            primaryTransparent: 'rgba(30, 41, 59, 0.1)',
            accentTransparent: 'rgba(0, 212, 255, 0.1)',
            secondaryTransparent: 'rgba(168, 85, 247, 0.1)',
            tertiaryTransparent: 'rgba(255, 107, 53, 0.1)',
            successTransparent: 'rgba(16, 185, 129, 0.1)'
        };
        
        // Font styling for charts
        Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#64748B';
        
        // Get data from data attributes - with proper null check
        const container = document.querySelector('[data-monthly-users]');
        
        if (!container) {
            console.warn('Dashboard data container not found');
            return;
        }
        
        const monthlyUsersData = JSON.parse(container.dataset.monthlyUsers || '[]');
        const taskStatsData = JSON.parse(container.dataset.taskStats || '[]');
        
        // Ensure we have at least some data for demo purposes if empty
        const demoMonthlyUsers = monthlyUsersData.length > 0 ? monthlyUsersData : [
            {month: 1, count: 42}, {month: 2, count: 56}, {month: 3, count: 78}, 
            {month: 4, count: 85}, {month: 5, count: 110}, {month: 6, count: 98},
            {month: 7, count: 120}, {month: 8, count: 145}, {month: 9, count: 180},
            {month: 10, count: 210}, {month: 11, count: 245}, {month: 12, count: 280}
        ];
        
        const demoTaskStats = taskStatsData.length > 0 ? taskStatsData : [
            {status: 'active', count: 24}, 
            {status: 'pending', count: 13}, 
            {status: 'completed', count: 86}
        ];
        
        // User Growth Chart
        if (document.getElementById('userGrowthChart')) {
            const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
            
            // Create gradient for area under line
            const areaGradient = userGrowthCtx.createLinearGradient(0, 0, 0, 350);
            areaGradient.addColorStop(0, 'rgba(0, 212, 255, 0.2)');
            areaGradient.addColorStop(0.5, 'rgba(0, 212, 255, 0.05)');
            areaGradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
            
            // Create gradient for the line itself
            const lineGradient = userGrowthCtx.createLinearGradient(0, 0, 700, 0);
            lineGradient.addColorStop(0, brandColors.accent);
            lineGradient.addColorStop(0.5, brandColors.secondary);
            lineGradient.addColorStop(1, brandColors.accent);
            
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            
            // Store chart reference globally for updates
            window.userGrowthChart = new Chart(userGrowthCtx, {
                type: 'line',
                data: {
                    labels: demoMonthlyUsers.map(item => months[item.month - 1]),
                    datasets: [{
                        label: 'User Registrations',
                        data: demoMonthlyUsers.map(item => item.count),
                        borderColor: lineGradient,
                        backgroundColor: areaGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#FFFFFF',
                        pointBorderColor: brandColors.accent,
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: brandColors.accent,
                        pointHoverBorderColor: '#FFFFFF',
                        pointHoverBorderWidth: 2,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.98)',
                            titleColor: brandColors.primary,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyColor: brandColors.neutral,
                            bodyFont: {
                                size: 13
                            },
                            borderColor: 'rgba(0, 212, 255, 0.3)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                title: function(tooltipItems) {
                                    const monthIndex = tooltipItems[0].dataIndex;
                                    const month = months[demoMonthlyUsers[monthIndex].month - 1];
                                    return month + ' ' + new Date().getFullYear();
                                },
                                label: function(context) {
                                    return 'New Users: ' + context.raw;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            border: {
                                display: false
                            },
                            grid: {
                                display: false
                            },
                            ticks: {
                                padding: 10,
                                color: '#94A3B8',
                                font: {
                                    size: 11
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            border: {
                                display: false
                            },
                            grid: {
                                color: 'rgba(226, 232, 240, 0.6)'
                            },
                            ticks: {
                                padding: 10,
                                precision: 0,
                                color: '#94A3B8',
                                font: {
                                    size: 11
                                },
                                callback: function(value) {
                                    return value;
                                }
                            }
                        }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
            
            // Add animation to the chart on load
            const originalDraw = window.userGrowthChart.draw;
            window.userGrowthChart.draw = function() {
                originalDraw.apply(this, arguments);
                
                if (this.animating !== true) {
                    this.animating = true;
                    
                    const meta = this.getDatasetMeta(0);
                    const ctx = this.ctx;
                    
                    meta.data.forEach((point, index) => {
                        setTimeout(() => {
                            const newX = point.x + 3;
                            const newY = point.y - 3;
                            
                            // Add subtle pulse effect
                            ctx.save();
                            ctx.beginPath();
                            ctx.arc(point.x, point.y, 10, 0, Math.PI * 2);
                            ctx.fillStyle = 'rgba(0, 212, 255, 0.2)';
                            ctx.fill();
                            ctx.closePath();
                            ctx.restore();
                        }, index * 150);
                    });
                }
            };
        }
        
        // Task Status Doughnut Chart
        if (document.getElementById('taskStatusChart')) {
            const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
            
            const statusColors = [
                brandColors.accent,     // Active tasks
                brandColors.tertiary,   // Pending tasks
                brandColors.success,    // Completed tasks
                brandColors.secondary   // Additional status if needed
            ];
            
            // Store chart reference globally for updates
            window.taskStatusChart = new Chart(taskStatusCtx, {
                type: 'doughnut',
                data: {
                    labels: demoTaskStats.map(item => item.status.charAt(0).toUpperCase() + item.status.slice(1)),
                    datasets: [{
                        data: demoTaskStats.map(item => item.count),
                        backgroundColor: statusColors,
                        borderWidth: 0,
                        hoverOffset: 10,
                        hoverBorderWidth: 2,
                        hoverBorderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.98)',
                            titleColor: brandColors.primary,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyColor: brandColors.neutral,
                            bodyFont: {
                                size: 13
                            },
                            borderColor: 'rgba(0, 212, 255, 0.3)',
                            borderWidth: 1,
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: true,
                            boxPadding: 5,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((acc, data) => acc + data, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateRotate: true,
                        animateScale: true,
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
            
            // Add animation to the chart on load
            const originalDraw = window.taskStatusChart.draw;
            window.taskStatusChart.draw = function() {
                originalDraw.apply(this, arguments);
                
                if (this.animating !== true) {
                    this.animating = true;
                    
                    const centerX = this.chartArea.left + (this.chartArea.right - this.chartArea.left) / 2;
                    const centerY = this.chartArea.top + (this.chartArea.bottom - this.chartArea.top) / 2;
                    
                    // Add subtle glow effect in the center
                    const ctx = this.ctx;
                    const gradient = ctx.createRadialGradient(centerX, centerY, 0, centerX, centerY, 60);
                    gradient.addColorStop(0, 'rgba(255, 255, 255, 0.8)');
                    gradient.addColorStop(0.5, 'rgba(255, 255, 255, 0.3)');
                    gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');
                    
                    ctx.save();
                    ctx.globalCompositeOperation = 'source-over';
                    ctx.fillStyle = gradient;
                    ctx.beginPath();
                    ctx.arc(centerX, centerY, 60, 0, Math.PI * 2);
                    ctx.fill();
                    ctx.restore();
                }
            };
        }
        
        // We're now using Alpine.js for managing refresh button states
        // The functionality is defined in the button attributes directly
    });
</script>
@endpush
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
<div class="min-h-screen bg-neutral-50">
    <div class="max-w-8xl mx-auto px-6 py-8" 
         data-monthly-users="{{ json_encode($monthlyUsers ?? []) }}" 
         data-task-stats="{{ json_encode($taskStats ?? []) }}">
        
        <!-- Streamlined Header/Taskbar -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-xl font-semibold text-primary-500">Dashboard</h1>
                <p class="text-sm text-neutral-400 mt-0.5">Welcome back, <span class="text-accent-500">{{ Auth::user()->fullName }}</span></p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs text-neutral-400 hidden sm:block" data-timestamp>{{ now()->format('M d, Y') }}</span>
                <button x-data="{ spinning: false }"
                        @click="spinning = true; setTimeout(() => { spinning = false; refreshDashboard(); }, 1500)"
                        class="p-2 rounded-lg bg-white border border-neutral-200 hover:bg-neutral-50 transition-colors"
                        title="Refresh">
                    <i class="fas fa-sync-alt text-neutral-500 text-sm" :class="{ 'animate-spin': spinning }"></i>
                </button>
                <a href="{{ route('admin.profile') }}" class="p-2 rounded-lg bg-white border border-neutral-200 hover:bg-neutral-50 transition-colors" title="Settings">
                    <i class="fas fa-cog text-neutral-500 text-sm"></i>
                </a>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Total Users Card -->
            <div class="animate-fade-in delay-100 opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide">Total Users</p>
                            <p class="text-2xl font-bold text-primary-500 mt-1" data-stat="total-users">{{ number_format($stats['total_users']) }}</p>
                            <p class="text-xs text-success-500 mt-2 flex items-center">
                                <i class="fas fa-arrow-up mr-1"></i>12% from last month
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-primary-50 flex items-center justify-center">
                            <i class="fas fa-users text-primary-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Tasks Card -->
            <div class="animate-fade-in delay-200 opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide">Active Tasks</p>
                            <p class="text-2xl font-bold text-primary-500 mt-1" data-stat="active-tasks">{{ number_format($stats['active_tasks']) }}</p>
                            <p class="text-xs text-accent-500 mt-2 flex items-center">
                                <i class="fas fa-clock mr-1"></i>In progress
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-accent-50 flex items-center justify-center">
                            <i class="fas fa-tasks text-accent-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card -->
            <div class="animate-fade-in delay-300 opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide">Pending</p>
                            <p class="text-2xl font-bold text-primary-500 mt-1" data-stat="pending-requests">{{ number_format($stats['pending_requests']) }}</p>
                            <p class="text-xs text-warning-500 mt-2 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>Needs review
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-tertiary-50 flex items-center justify-center">
                            <i class="fas fa-clipboard-list text-tertiary-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks Card -->
            <div class="animate-fade-in delay-400 opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide">Completed</p>
                            <p class="text-2xl font-bold text-primary-500 mt-1" data-stat="completed-tasks">{{ number_format($stats['completed_tasks']) }}</p>
                            <p class="text-xs text-success-500 mt-2 flex items-center">
                                <i class="fas fa-check-circle mr-1"></i>All time
                            </p>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-success-50 flex items-center justify-center">
                            <i class="fas fa-trophy text-success-500"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings Analytics Widget -->
        @if(isset($earningsStats))
        <div class="animate-fade-in opacity-0 mb-8">
            <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
                <!-- Header -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-neutral-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center">
                            <i class="fas fa-chart-line text-primary-500 text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-primary-500">Financial Overview</h2>
                            <p class="text-xs text-neutral-400">Revenue & earnings analytics</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.earnings-analytics.index') }}" class="text-xs text-accent-500 hover:text-accent-600 font-medium flex items-center gap-1">
                        <span>View Details</span>
                        <i class="fas fa-arrow-right text-[10px]"></i>
                    </a>
                </div>
                
                <!-- Main Stats Row -->
                <div class="grid grid-cols-1 lg:grid-cols-3 divide-y lg:divide-y-0 lg:divide-x divide-neutral-100">
                    <!-- Total Revenue -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide">Total Revenue</p>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-success-50 text-success-600 font-medium">
                                {{ $earningsStats['completed_projects'] }} projects
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-primary-500 tracking-tight">₱{{ number_format($earningsStats['total_earnings'], 2) }}</p>
                        <p class="text-xs text-neutral-400 mt-2">Lifetime earnings from completed projects</p>
                    </div>
                    
                    <!-- This Month -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide">This Month</p>
                            @if($earningsStats['monthly_growth'] >= 0)
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-success-50 text-success-600 font-medium flex items-center gap-1">
                                    <i class="fas fa-arrow-up text-[8px]"></i>
                                    {{ $earningsStats['monthly_growth'] }}%
                                </span>
                            @else
                                <span class="text-[10px] px-2 py-0.5 rounded-full bg-error-50 text-error-600 font-medium flex items-center gap-1">
                                    <i class="fas fa-arrow-down text-[8px]"></i>
                                    {{ abs($earningsStats['monthly_growth']) }}%
                                </span>
                            @endif
                        </div>
                        <p class="text-3xl font-bold text-primary-500 tracking-tight">₱{{ number_format($earningsStats['this_month_earnings'], 2) }}</p>
                        <p class="text-xs text-neutral-400 mt-2">{{ now()->format('F Y') }} revenue</p>
                    </div>
                    
                    <!-- Adiutor Payouts -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-xs font-medium text-neutral-400 uppercase tracking-wide">Adiutor Earnings</p>
                            <span class="text-[10px] px-2 py-0.5 rounded-full bg-accent-50 text-accent-600 font-medium">
                                This month
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-primary-500 tracking-tight">₱{{ number_format($earningsStats['adiutor_earnings_this_month'], 2) }}</p>
                        <p class="text-xs text-neutral-400 mt-2">Approved time entries</p>
                    </div>
                </div>
                
                <!-- Action Items Row -->
                <div class="bg-neutral-50 px-6 py-4 border-t border-neutral-100">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-medium text-neutral-500">Action Items</p>
                        <div class="flex items-center gap-6">
                            <!-- Pending Approvals -->
                            <a href="{{ route('admin.payouts.index') }}" class="flex items-center gap-2 group">
                                <div class="flex items-center gap-1.5">
                                    @if($earningsStats['pending_approvals'] > 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-warning-500 animate-pulse"></span>
                                    @endif
                                    <span class="text-xs text-neutral-600 group-hover:text-primary-500 transition-colors">{{ $earningsStats['pending_approvals'] }} pending approvals</span>
                                </div>
                                <i class="fas fa-chevron-right text-[8px] text-neutral-400 group-hover:text-primary-500 transition-colors"></i>
                            </a>
                            
                            <!-- Pending Payouts -->
                            <a href="{{ route('admin.payouts.index', ['status' => 'pending']) }}" class="flex items-center gap-2 group">
                                <div class="flex items-center gap-1.5">
                                    @if($earningsStats['pending_payouts'] > 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-tertiary-500 animate-pulse"></span>
                                    @endif
                                    <span class="text-xs text-neutral-600 group-hover:text-primary-500 transition-colors">₱{{ number_format($earningsStats['pending_payouts'], 2) }} pending payouts</span>
                                </div>
                                <i class="fas fa-chevron-right text-[8px] text-neutral-400 group-hover:text-primary-500 transition-colors"></i>
                            </a>
                            
                            <!-- Hour Requests -->
                            <a href="{{ route('admin.hour-requests.index') }}" class="flex items-center gap-2 group">
                                <div class="flex items-center gap-1.5">
                                    @if($earningsStats['pending_hour_requests'] > 0)
                                        <span class="w-1.5 h-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                                    @endif
                                    <span class="text-xs text-neutral-600 group-hover:text-primary-500 transition-colors">{{ $earningsStats['pending_hour_requests'] }} hour requests</span>
                                </div>
                                <i class="fas fa-chevron-right text-[8px] text-neutral-400 group-hover:text-primary-500 transition-colors"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Monthly User Growth Chart -->
            <div class="lg:col-span-2 animate-fade-in opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200 h-full">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <div>
                            <h2 class="text-sm font-semibold text-primary-500">User Growth</h2>
                            <p class="text-xs text-neutral-400 mt-0.5">Monthly registration trends</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="text-xs px-3 py-1.5 rounded-md bg-neutral-100 hover:bg-neutral-200 text-neutral-600 transition-colors flex items-center gap-1.5">
                                    <span x-text="$store.timeFilter ? $store.timeFilter : 'This Year'">This Year</span>
                                    <i class="fas fa-chevron-down text-[10px]" :class="{'rotate-180': open}"></i>
                                </button>
                                <ul x-show="open" 
                                    @click.outside="open = false"
                                    x-transition
                                    class="absolute right-0 mt-1 w-32 bg-white rounded-md shadow-lg border border-neutral-200 py-1 z-10"
                                    style="display: none;">
                                    <li><a @click="$store.timeFilter = 'This Year'; open = false" class="block px-3 py-1.5 text-xs text-neutral-600 hover:bg-neutral-50 cursor-pointer">This Year</a></li>
                                    <li><a @click="$store.timeFilter = 'Last Year'; open = false" class="block px-3 py-1.5 text-xs text-neutral-600 hover:bg-neutral-50 cursor-pointer">Last Year</a></li>
                                    <li><a @click="$store.timeFilter = 'All Time'; open = false" class="block px-3 py-1.5 text-xs text-neutral-600 hover:bg-neutral-50 cursor-pointer">All Time</a></li>
                                </ul>
                            </div>
                            <button @click="downloadReport()" class="p-1.5 rounded-md hover:bg-neutral-100 transition-colors" title="Download">
                                <i class="fas fa-download text-neutral-400 text-xs"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-5">
                        <div class="h-64">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Status Pie Chart -->
            <div class="animate-fade-in opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200 h-full">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <div>
                            <h2 class="text-sm font-semibold text-primary-500">Task Distribution</h2>
                            <p class="text-xs text-neutral-400 mt-0.5">Current status overview</p>
                        </div>
                        <button x-data="{ spinning: false }"
                                @click="spinning = true; setTimeout(() => { spinning = false }, 1500)"
                                class="p-1.5 rounded-md hover:bg-neutral-100 transition-colors" 
                                title="Refresh">
                            <i class="fas fa-sync-alt text-neutral-400 text-xs" :class="{ 'animate-spin': spinning }"></i>
                        </button>
                    </div>
                    <div class="p-5">
                        <div class="h-44">
                            <canvas id="taskStatusChart"></canvas>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-4">
                            <div class="text-center p-2 rounded-md bg-accent-50">
                                <p class="text-xs text-neutral-500">Active</p>
                                <p class="text-sm font-semibold text-accent-500">{{ $stats['active_tasks'] }}</p>
                            </div>
                            <div class="text-center p-2 rounded-md bg-tertiary-50">
                                <p class="text-xs text-neutral-500">Pending</p>
                                <p class="text-sm font-semibold text-tertiary-500">{{ $stats['pending_requests'] }}</p>
                            </div>
                            <div class="text-center p-2 rounded-md bg-success-50">
                                <p class="text-xs text-neutral-500">Done</p>
                                <p class="text-sm font-semibold text-success-500">{{ $stats['completed_tasks'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Users -->
            <div class="animate-fade-in opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <h2 class="text-sm font-semibold text-primary-500">Recent Users</h2>
                        <a href="{{ route('admin.users.index') }}" class="text-xs text-accent-500 hover:text-accent-600 font-medium">View all →</a>
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
                                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-accent-400 to-secondary-500 flex items-center justify-center">
                                                <span class="text-white text-xs font-medium">{{ substr($user->fullName, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-primary-500">{{ $user->fullName }}</p>
                                            <p class="text-xs text-neutral-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @php
                                            $roleClasses = [
                                                'admin' => 'bg-secondary-50 text-secondary-500',
                                                'client' => 'bg-accent-50 text-accent-500',
                                                'adiutor' => 'bg-success-50 text-success-500'
                                            ];
                                            $roleClass = $roleClasses[$user->role] ?? 'bg-neutral-100 text-neutral-500';
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $roleClass }}">{{ ucfirst($user->role) }}</span>
                                        <span class="text-xs text-neutral-400">{{ $user->created_at->format('M d') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-users text-neutral-300"></i>
                                </div>
                                <p class="text-sm text-neutral-400">No recent users</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="animate-fade-in opacity-0">
                <div class="bg-white rounded-lg border border-neutral-200">
                    <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                        <h2 class="text-sm font-semibold text-primary-500">Service Requests</h2>
                        <a href="{{ route('admin.requests.index') }}" class="text-xs text-accent-500 hover:text-accent-600 font-medium">View all →</a>
                    </div>
                    <div class="p-5">
                        @if($stats['recent_requests']->count() > 0)
                            <div class="space-y-3">
                                @foreach($stats['recent_requests'] as $request)
                                <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-neutral-50' : '' }}">
                                    <div>
                                        <p class="text-sm font-medium text-primary-500">{{ $request->user->fullName }}</p>
                                        <p class="text-xs text-neutral-400">{{ $request->company_name ?? 'Individual' }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning-50 text-warning-500',
                                                'approved' => 'bg-success-50 text-success-500',
                                                'rejected' => 'bg-error-50 text-error-500',
                                                'processing' => 'bg-accent-50 text-accent-500'
                                            ];
                                            $statusClass = $statusClasses[$request->status] ?? $statusClasses['pending'];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded text-[10px] font-medium {{ $statusClass }}">{{ ucfirst($request->status) }}</span>
                                        <span class="text-xs text-neutral-400">{{ $request->submission_date ? $request->submission_date->format('M d') : 'N/A' }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="w-12 h-12 rounded-full bg-neutral-100 flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-clipboard-list text-neutral-300"></i>
                                </div>
                                <p class="text-sm text-neutral-400">No recent requests</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="animate-fade-in opacity-0">
            <div class="bg-white rounded-lg border border-neutral-200">
                <div class="flex items-center justify-between p-5 border-b border-neutral-100">
                    <h2 class="text-sm font-semibold text-primary-500">Quick Actions</h2>
                    <button class="text-xs text-neutral-400 hover:text-neutral-600" id="customizeActions" title="Customize">
                        <i class="fas fa-sliders-h"></i>
                    </button>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-3 sm:grid-cols-7 gap-4">
                        <a href="{{ route('admin.users.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-lg bg-primary-50 group-hover:bg-primary-100 flex items-center justify-center transition-colors mb-2">
                                <i class="fas fa-users text-primary-500"></i>
                            </div>
                            <p class="text-xs font-medium text-neutral-600">Users</p>
                            <p class="text-[10px] text-neutral-400">{{ $stats['total_users'] }}</p>
                        </a>
                        <a href="{{ route('admin.requests.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-lg bg-tertiary-50 group-hover:bg-tertiary-100 flex items-center justify-center transition-colors mb-2">
                                <i class="fas fa-clipboard-list text-tertiary-500"></i>
                            </div>
                            <p class="text-xs font-medium text-neutral-600">Requests</p>
                            <p class="text-[10px] text-neutral-400">{{ $stats['pending_requests'] }} pending</p>
                        </a>
                        <a href="{{ route('admin.tasks.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-lg bg-accent-50 group-hover:bg-accent-100 flex items-center justify-center transition-colors mb-2">
                                <i class="fas fa-tasks text-accent-500"></i>
                            </div>
                            <p class="text-xs font-medium text-neutral-600">Tasks</p>
                            <p class="text-[10px] text-neutral-400">{{ $stats['active_tasks'] }} active</p>
                        </a>
                        <a href="{{ route('admin.earnings-analytics.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-lg bg-gradient-to-br from-success-50 to-accent-50 group-hover:from-success-100 group-hover:to-accent-100 flex items-center justify-center transition-colors mb-2">
                                <i class="fas fa-money-bill-wave text-success-500"></i>
                            </div>
                            <p class="text-xs font-medium text-neutral-600">Earnings</p>
                            <p class="text-[10px] text-neutral-400">Analytics</p>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-lg bg-tertiary-50 group-hover:bg-tertiary-100 flex items-center justify-center transition-colors mb-2">
                                <i class="fas fa-chart-bar text-tertiary-500"></i>
                            </div>
                            <p class="text-xs font-medium text-neutral-600">Reports</p>
                            <p class="text-[10px] text-neutral-400">Analytics</p>
                        </a>
                        <a href="{{ route('admin.documents.index') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-lg bg-neutral-100 group-hover:bg-neutral-200 flex items-center justify-center transition-colors mb-2">
                                <i class="fas fa-folder-open text-neutral-500"></i>
                            </div>
                            <p class="text-xs font-medium text-neutral-600">Documents</p>
                            <p class="text-[10px] text-neutral-400">Files</p>
                        </a>
                        <a href="{{ route('admin.profile') }}" class="group text-center">
                            <div class="w-12 h-12 mx-auto rounded-lg bg-secondary-50 group-hover:bg-secondary-100 flex items-center justify-center transition-colors mb-2">
                                <i class="fas fa-user text-secondary-500"></i>
                            </div>
                            <p class="text-xs font-medium text-neutral-600">Profile</p>
                            <p class="text-[10px] text-neutral-400">Settings</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
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
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Generating...';
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
            success: 'bg-success-50 text-success-500 border-success-200',
            error: 'bg-red-50 text-red-500 border-red-200',
            info: 'bg-blue-50 text-blue-500 border-blue-200',
            warning: 'bg-yellow-50 text-yellow-500 border-yellow-200'
        };
        
        const typeIcons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            info: 'fas fa-info-circle',
            warning: 'fas fa-exclamation-triangle'
        };
        
        toastEl.className = `fixed bottom-4 right-4 ${typeStyles[type]} border px-4 py-3 rounded-lg shadow-lg z-50 flex items-center transition-all transform translate-x-full opacity-0`;
        toastEl.innerHTML = `<i class="${typeIcons[type]} mr-2"></i> ${message}`;
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
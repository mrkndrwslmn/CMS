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
<div class="w-full">
    <div class="w-full px-4 py-5" 
         data-monthly-users="{{ json_encode($monthlyUsers ?? []) }}" 
         data-task-stats="{{ json_encode($taskStats ?? []) }}">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-primary-500 mb-2 relative">
                        Dashboard Overview
                    </h1>
                    <p class="text-neutral-500">Welcome back, <span class="font-medium text-accent-500">{{ Auth::user()->fullName }}</span>! Here's what's happening today.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center bg-accent-50 px-4 py-2 rounded-lg">
                            <i class="fas fa-calendar-alt mr-2 text-accent-500"></i>
                            <span class="font-medium text-primary-500">{{ now()->format('F d, Y') }}</span>
                        </div>
                        <button x-data="{ spinning: false }"
                                @click="spinning = true; setTimeout(() => { spinning = false; refreshDashboard(); }, 1500)"
                                class="glass-button-accent rounded-lg px-4 py-2 flex items-center">
                            <i class="fas fa-sync-alt mr-2" :class="{ 'animate-spin': spinning }"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
            <!-- Total Users Card -->
            <div class="animate-fade-in delay-100 opacity-0">
                <div class="bg-white rounded-xl shadow-sm h-full transition-all hover:-translate-y-1 duration-300 overflow-hidden">
                    <div class="p-5 border-l-4 border-primary-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Total Users</div>
                                <div class="flex items-baseline">
                                    <div class="text-2xl font-bold text-primary-500">{{ number_format($stats['total_users']) }}</div>
                                    <div class="ml-2 px-2 py-0.5 bg-success-50 text-success-500 text-xs font-medium rounded-full flex items-center">
                                        <i class="fas fa-arrow-up mr-1"></i>12%
                                    </div>
                                </div>
                                <div class="text-neutral-500 text-xs mt-2 flex items-center">
                                    <div class="flex items-center">
                                        <i class="fas fa-user-plus mr-1 text-success-500"></i>
                                        <span>24 new this month</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-primary-50 p-3 rounded-xl">
                                <i class="fas fa-users text-primary-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Tasks Card -->
            <div class="animate-fade-in delay-200 opacity-0">
                <div class="bg-white rounded-xl shadow-sm h-full transition-all hover:-translate-y-1 duration-300 overflow-hidden">
                    <div class="p-5 border-l-4 border-accent-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Active Tasks</div>
                                <div class="flex items-baseline">
                                    <div class="text-2xl font-bold text-primary-500">{{ number_format($stats['active_tasks']) }}</div>
                                    <div class="ml-2 px-2 py-0.5 bg-accent-50 text-accent-500 text-xs font-medium rounded-full flex items-center">
                                        <i class="fas fa-clock mr-1"></i>In progress
                                    </div>
                                </div>
                                <div class="text-neutral-500 text-xs mt-2 flex items-center">
                                    <div class="flex items-center">
                                        <i class="fas fa-hourglass-half mr-1 text-accent-500"></i>
                                        <span>Updated 2 hours ago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-accent-50 p-3 rounded-xl">
                                <i class="fas fa-tasks text-accent-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Card -->
            <div class="animate-fade-in delay-300 opacity-0">
                <div class="bg-white rounded-xl shadow-sm h-full transition-all hover:-translate-y-1 duration-300 overflow-hidden">
                    <div class="p-5 border-l-4 border-tertiary-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Pending Requests</div>
                                <div class="flex items-baseline">
                                    <div class="text-2xl font-bold text-primary-500">{{ number_format($stats['pending_requests']) }}</div>
                                    <div class="ml-2 px-2 py-0.5 bg-warning-50 text-warning-500 text-xs font-medium rounded-full flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Attention
                                    </div>
                                </div>
                                <div class="text-neutral-500 text-xs mt-2 flex items-center">
                                    <div class="flex items-center">
                                        <i class="fas fa-clock mr-1 text-tertiary-500"></i>
                                        <span>5 require review</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-tertiary-50 p-3 rounded-xl">
                                <i class="fas fa-clipboard-list text-tertiary-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Tasks Card -->
            <div class="animate-fade-in delay-400 opacity-0">
                <div class="bg-white rounded-xl shadow-sm h-full transition-all hover:-translate-y-1 duration-300 overflow-hidden">
                    <div class="p-5 border-l-4 border-success-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs uppercase font-bold text-neutral-500 mb-1">Completed Tasks</div>
                                <div class="flex items-baseline">
                                    <div class="text-2xl font-bold text-primary-500">{{ number_format($stats['completed_tasks']) }}</div>
                                    <div class="ml-2 px-2 py-0.5 bg-success-50 text-success-500 text-xs font-medium rounded-full flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>All time
                                    </div>
                                </div>
                                <div class="text-neutral-500 text-xs mt-2 flex items-center">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-check mr-1 text-success-500"></i>
                                        <span>18 this week</span>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-success-50 p-3 rounded-xl">
                                <i class="fas fa-trophy text-success-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">
            <!-- Monthly User Growth Chart -->
            <div class="lg:col-span-8 animate-fade-in opacity-0">
                <div class="bg-white rounded-xl shadow-sm h-full">
                    <div class="p-5 border-b border-neutral-100">
                        <div class="flex flex-wrap justify-between items-center">
                            <div>
                                <h5 class="flex items-center text-lg font-bold text-primary-500 mb-1">
                                    <i class="fas fa-chart-line text-accent-500 mr-2"></i>
                                    User Growth Analytics
                                </h5>
                                <p class="text-neutral-500 text-sm">Monthly registration trends for {{ date('Y') }}</p>
                            </div>
                            <div class="flex space-x-3 mt-2 md:mt-0">
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" class="glass-button-accent text-sm rounded-lg px-4 py-2 flex items-center" type="button">
                                        <i class="fas fa-calendar mr-2"></i> 
                                        <span x-text="$store.timeFilter ? $store.timeFilter : 'This Year'">This Year</span> 
                                        <i class="fas fa-chevron-down ml-2 text-xs transition-transform" :class="{'rotate-180': open}"></i>
                                    </button>
                                    <ul x-show="open" 
                                        @click.outside="open = false"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform scale-95"
                                        x-transition:enter-end="opacity-100 transform scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 transform scale-100"
                                        x-transition:leave-end="opacity-0 transform scale-95"
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-10 py-1 border border-neutral-100"
                                        style="display: none;">
                                        <li><a @click="$store.timeFilter = 'This Year'; open = false" class="flex items-center px-4 py-2 text-sm text-primary-500 hover:bg-accent-50 cursor-pointer"><i class="fas fa-calendar-day mr-2 text-neutral-500"></i>This Year</a></li>
                                        <li><a @click="$store.timeFilter = 'Last Year'; open = false" class="flex items-center px-4 py-2 text-sm text-primary-500 hover:bg-accent-50 cursor-pointer"><i class="fas fa-calendar-week mr-2 text-neutral-500"></i>Last Year</a></li>
                                        <li><a @click="$store.timeFilter = 'All Time'; open = false" class="flex items-center px-4 py-2 text-sm text-primary-500 hover:bg-accent-50 cursor-pointer"><i class="fas fa-calendar mr-2 text-neutral-500"></i>All Time</a></li>
                                    </ul>
                                </div>
                                <button @click="downloadReport()" class="glass-button rounded-lg px-3 py-2 text-sm" title="Download Report">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 pt-4">
                        <div class="chart-area h-72">
                            <canvas id="userGrowthChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Task Status Pie Chart -->
            <div class="lg:col-span-4 animate-fade-in opacity-0">
                <div class="tech-card h-full rounded-xl">
                    <div class="p-5 border-b border-neutral-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="flex items-center text-lg font-bold text-primary-500 mb-1">
                                    <i class="fas fa-chart-pie text-secondary-500 mr-2"></i>
                                    Task Distribution
                                </h5>
                                <p class="text-neutral-500 text-sm">Current task status overview</p>
                            </div>
                            <button x-data="{ spinning: false }"
                                    @click="spinning = true; setTimeout(() => { spinning = false }, 1500)"
                                    class="glass-button-purple rounded-lg px-3 py-2 text-sm" 
                                    title="Refresh Data">
                                <i class="fas fa-sync-alt" :class="{ 'animate-spin': spinning }"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-5 pt-4">
                        <div class="chart-pie mb-5 h-56">
                            <canvas id="taskStatusChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div class="bg-accent-50 p-3 rounded-lg">
                                    <div class="text-neutral-500 text-xs mb-1">Active</div>
                                    <div class="font-bold text-accent-500">{{ $stats['active_tasks'] }}</div>
                                </div>
                                <div class="bg-tertiary-50 p-3 rounded-lg">
                                    <div class="text-neutral-500 text-xs mb-1">Pending</div>
                                    <div class="font-bold text-tertiary-500">{{ $stats['pending_requests'] }}</div>
                                </div>
                                <div class="bg-success-50 p-3 rounded-lg">
                                    <div class="text-neutral-500 text-xs mb-1">Complete</div>
                                    <div class="font-bold text-success-500">{{ $stats['completed_tasks'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Recent Users -->
            <div class="animate-fade-in opacity-0">
                <div class="bg-white rounded-xl shadow-sm h-full">
                    <div class="p-5 border-b border-neutral-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="flex items-center text-lg font-bold text-primary-500 mb-1">
                                    <i class="fas fa-users text-primary-500 mr-2"></i>
                                    Recent Users
                                </h5>
                                <p class="text-neutral-500 text-sm">Latest user registrations</p>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 bg-primary-50 hover:bg-primary-100 text-primary-500 text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i> View All
                            </a>
                        </div>
                    </div>
                    <div class="p-5 pt-3">
                        @if($stats['recent_users']->count() > 0)
                            <div class="overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-neutral-100">
                                                <th class="text-neutral-500 font-semibold py-3 pl-3 text-left">User</th>
                                                <th class="text-neutral-500 font-semibold py-3 text-left">Role</th>
                                                <th class="text-neutral-500 font-semibold py-3 text-left">Status</th>
                                                <th class="text-neutral-500 font-semibold py-3 pr-3 text-right">Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['recent_users'] as $user)
                                            <tr class="hover:bg-neutral-50 transition-all duration-200 border-b border-neutral-50">
                                                <td class="py-3 pl-3">
                                                    <div class="flex items-center">
                                                        <div class="mr-3">
                                                            @if($user->profilePic)
                                                                <img src="{{ asset('storage/' . $user->profilePic) }}" class="h-10 w-10 rounded-full object-cover" alt="{{ $user->fullName }}">
                                                            @else
                                                                <div class="h-10 w-10 rounded-full flex items-center justify-center bg-gradient-to-r from-accent-400 to-secondary-500">
                                                                    <span class="text-white font-bold">{{ substr($user->fullName, 0, 1) }}</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-medium text-primary-500">{{ $user->fullName }}</div>
                                                            <div class="text-neutral-500 text-xs">{{ $user->email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    @php
                                                        $roleClasses = [
                                                            'admin' => 'bg-secondary-50 text-secondary-500',
                                                            'client' => 'bg-accent-50 text-accent-500',
                                                            'adiutor' => 'bg-success-50 text-success-500'
                                                        ];
                                                        $roleClass = $roleClasses[$user->role] ?? 'bg-neutral-100 text-neutral-500';
                                                    @endphp
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center {{ $roleClass }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </td>
                                                <td class="py-3">
                                                    <div class="flex items-center">
                                                        <span class="inline-block w-2 h-2 rounded-full mr-2 {{ $user->status === 'active' ? 'bg-success-500' : 'bg-neutral-400' }}"></span>
                                                        <span class="text-sm {{ $user->status === 'active' ? 'text-success-500' : 'text-neutral-500' }}">
                                                            {{ ucfirst($user->status) }}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="py-3 pr-3 text-right">
                                                    <div class="flex items-center justify-end text-neutral-500">
                                                        <i class="far fa-calendar-alt mr-1 opacity-70"></i>
                                                        <span>{{ $user->created_at->format('M d, Y') }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="bg-primary-50 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-primary-300 text-3xl"></i>
                                </div>
                                <p class="text-neutral-500 mb-4">No recent users found</p>
                                <a href="{{ route('admin.users.create') }}" class="glass-button-secondary inline-flex items-center px-4 py-2 rounded-lg">
                                    <i class="fas fa-plus mr-2"></i> Add New User
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Requests -->
            <div class="animate-fade-in opacity-0">
                <div class="bg-white rounded-xl shadow-sm h-full">
                    <div class="p-5 border-b border-neutral-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="flex items-center text-lg font-bold text-primary-500 mb-1">
                                    <i class="fas fa-clipboard-list text-tertiary-500 mr-2"></i>
                                    Service Requests
                                </h5>
                                <p class="text-neutral-500 text-sm">Recent client submissions</p>
                            </div>
                            <a href="{{ route('admin.requests.index') }}" class="flex items-center px-4 py-2 bg-tertiary-50 hover:bg-tertiary-100 text-tertiary-500 text-sm font-medium rounded-lg transition-colors">
                                <i class="fas fa-external-link-alt mr-2"></i> View All
                            </a>
                        </div>
                    </div>
                    <div class="p-5 pt-3">
                        @if($stats['recent_requests']->count() > 0)
                            <div class="overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="border-b border-neutral-100">
                                                <th class="text-neutral-500 font-semibold py-3 pl-3 text-left">Client</th>
                                                <th class="text-neutral-500 font-semibold py-3 text-left">Company</th>
                                                <th class="text-neutral-500 font-semibold py-3 text-left">Status</th>
                                                <th class="text-neutral-500 font-semibold py-3 pr-3 text-right">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats['recent_requests'] as $request)
                                            <tr class="hover:bg-neutral-50 transition-all duration-200 border-b border-neutral-50">
                                                <td class="py-3 pl-3">
                                                    <div>
                                                        <div class="font-medium text-primary-500">{{ $request->user->fullName }}</div>
                                                        <div class="text-neutral-500 text-xs">{{ $request->user->email }}</div>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    <div class="flex items-center text-neutral-600">
                                                        <i class="fas fa-building mr-2 text-neutral-400"></i>
                                                        <span>{{ $request->company_name ?? 'Individual' }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-3">
                                                    @php
                                                        $statusClasses = [
                                                            'pending' => 'bg-warning-50 text-warning-500',
                                                            'approved' => 'bg-success-50 text-success-500',
                                                            'rejected' => 'bg-error-50 text-error-500',
                                                            'processing' => 'bg-accent-50 text-accent-500'
                                                        ];
                                                        $statusClass = $statusClasses[$request->status] ?? $statusClasses['pending'];
                                                    @endphp
                                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium inline-flex items-center {{ $statusClass }}">
                                                        {{ ucfirst($request->status) }}
                                                    </span>
                                                </td>
                                                <td class="py-3 pr-3 text-right">
                                                    <div class="flex items-center justify-end text-neutral-500">
                                                        <i class="far fa-calendar-alt mr-1 opacity-70"></i>
                                                        <span>{{ $request->submission_date ? $request->submission_date->format('M d, Y') : 'N/A' }}</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <div class="bg-tertiary-50 h-20 w-20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clipboard-list text-tertiary-300 text-3xl"></i>
                                </div>
                                <p class="text-neutral-500 mb-4">No recent requests found</p>
                                <a href="{{ route('admin.requests.index') }}" class="glass-button-orange inline-flex items-center px-4 py-2 rounded-lg">
                                    <i class="fas fa-search mr-2"></i> View All Requests
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 gap-6">
            <div class="animate-fade-in opacity-0">
                <div class="bg-white rounded-xl shadow-sm">
                    <div class="p-5 border-b border-neutral-100">
                        <div class="flex justify-between items-center">
                            <div>
                                <h5 class="flex items-center text-lg font-bold text-primary-500 mb-1">
                                    <i class="fas fa-bolt text-secondary-500 mr-2"></i>
                                    Quick Actions
                                </h5>
                                <p class="text-neutral-500 text-sm">Access key management features</p>
                            </div>
                            <div>
                                <button class="flex items-center px-4 py-2 bg-secondary-50 hover:bg-secondary-100 text-secondary-500 text-sm font-medium rounded-lg transition-colors" id="customizeActions" title="Customize Quick Actions">
                                    <i class="fas fa-sliders-h mr-2"></i> Customize
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-5 pt-4">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">
                            <div>
                                <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-4 h-full rounded-xl hover:bg-neutral-50 transition-all duration-300 group">
                                    <div class="mb-3 rounded-xl flex items-center justify-center w-14 h-14 bg-primary-50 group-hover:bg-primary-100 transition-colors">
                                        <i class="fas fa-users text-primary-500 text-xl"></i>
                                    </div>
                                    <span class="text-sm font-medium text-primary-500 mb-1">Manage Users</span>
                                    <span class="px-2.5 py-1 bg-primary-50 text-primary-500 text-xs font-medium rounded-full">{{ $stats['total_users'] }} Users</span>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.requests.index') }}" class="flex flex-col items-center justify-center p-4 h-full rounded-xl hover:bg-neutral-50 transition-all duration-300 group">
                                    <div class="mb-3 rounded-xl flex items-center justify-center w-14 h-14 bg-tertiary-50 group-hover:bg-tertiary-100 transition-colors">
                                        <i class="fas fa-clipboard-list text-tertiary-500 text-xl"></i>
                                    </div>
                                    <span class="text-sm font-medium text-primary-500 mb-1">View Requests</span>
                                    <span class="px-2.5 py-1 bg-tertiary-50 text-tertiary-500 text-xs font-medium rounded-full">{{ $stats['pending_requests'] }} Pending</span>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.tasks.index') }}" class="flex flex-col items-center justify-center p-4 h-full rounded-xl hover:bg-neutral-50 transition-all duration-300 group">
                                    <div class="mb-3 rounded-xl flex items-center justify-center w-14 h-14 bg-accent-50 group-hover:bg-accent-100 transition-colors">
                                        <i class="fas fa-tasks text-accent-500 text-xl"></i>
                                    </div>
                                    <span class="text-sm font-medium text-primary-500 mb-1">Manage Tasks</span>
                                    <span class="px-2.5 py-1 bg-accent-50 text-accent-500 text-xs font-medium rounded-full">{{ $stats['active_tasks'] }} Active</span>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.reports.index') }}" class="flex flex-col items-center justify-center p-4 h-full rounded-xl hover:bg-neutral-50 transition-all duration-300 group">
                                    <div class="mb-3 rounded-xl flex items-center justify-center w-14 h-14 bg-tertiary-50 group-hover:bg-tertiary-100 transition-colors">
                                        <i class="fas fa-chart-bar text-tertiary-500 text-xl"></i>
                                    </div>
                                    <span class="text-sm font-medium text-primary-500 mb-1">View Reports</span>
                                    <span class="px-2.5 py-1 bg-neutral-50 text-neutral-500 text-xs font-medium rounded-full">Analytics</span>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.documents.index') }}" class="flex flex-col items-center justify-center p-4 h-full rounded-xl hover:bg-neutral-50 transition-all duration-300 group">
                                    <div class="mb-3 rounded-xl flex items-center justify-center w-14 h-14 bg-neutral-50 group-hover:bg-neutral-100 transition-colors">
                                        <i class="fas fa-folder-open text-neutral-500 text-xl"></i>
                                    </div>
                                    <span class="text-sm font-medium text-primary-500 mb-1">Documents</span>
                                    <span class="px-2.5 py-1 bg-neutral-50 text-neutral-500 text-xs font-medium rounded-full">Files</span>
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('admin.profile') }}" class="flex flex-col items-center justify-center p-4 h-full rounded-xl hover:bg-neutral-50 transition-all duration-300 group">
                                    <div class="mb-3 rounded-xl flex items-center justify-center w-14 h-14 bg-secondary-50 group-hover:bg-secondary-100 transition-colors">
                                        <i class="fas fa-user text-secondary-500 text-xl"></i>
                                    </div>
                                    <span class="text-sm font-medium text-primary-500 mb-1">Your Profile</span>
                                    <span class="px-2.5 py-1 bg-secondary-50 text-secondary-500 text-xs font-medium rounded-full">Settings</span>
                                </a>
                            </div>
                        </div>
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
        // Placeholder function for download report functionality
        console.log('Downloading report for: ' + Alpine.store('timeFilter'));
        // Implementation would go here
        alert('Report download started for ' + Alpine.store('timeFilter'));
    }
    
    // Function to refresh dashboard data
    function refreshDashboard() {
        console.log('Refreshing dashboard data...');
        // In a real implementation, this would fetch fresh data from the server
        // For now, we'll just display a message
        
        // You could implement a fetch call here to refresh the data
        // fetch('/admin/dashboard/refresh')
        //     .then(response => response.json())
        //     .then(data => {
        //         // Update the charts and stats
        //         updateCharts(data);
        //     });
        
        // Show toast notification
        const toastEl = document.createElement('div');
        toastEl.className = 'fixed bottom-4 right-4 bg-success-50 text-success-500 px-4 py-2 rounded-lg shadow-lg z-50 flex items-center';
        toastEl.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Dashboard data refreshed';
        document.body.appendChild(toastEl);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toastEl.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                document.body.removeChild(toastEl);
            }, 500);
        }, 3000);
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
        
        // Get data from data attributes
        const container = document.querySelector('.container-fluid');
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
            
            const userGrowthChart = new Chart(userGrowthCtx, {
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
            const originalDraw = userGrowthChart.draw;
            userGrowthChart.draw = function() {
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
            
            const taskStatusChart = new Chart(taskStatusCtx, {
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
            const originalDraw = taskStatusChart.draw;
            taskStatusChart.draw = function() {
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
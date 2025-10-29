@extends('admin.layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
<div class="px-6 py-8" x-data="auditLogs()">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Audit Logs</h1>
            <p class="text-neutral-500 text-sm">Monitor and track all system changes and user activities</p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center space-x-3">
            <button @click="autoRefresh = !autoRefresh" 
                    :class="autoRefresh ? 'bg-green-500 hover:bg-green-600' : 'bg-neutral-500 hover:bg-neutral-600'"
                    class="flex items-center px-3 py-2 text-white text-sm rounded-lg transition-colors">
                <i :class="autoRefresh ? 'fas fa-pause' : 'fas fa-play'" class="mr-2"></i>
                <span x-text="autoRefresh ? 'Auto-refresh ON' : 'Auto-refresh OFF'"></span>
            </button>
            <button @click="exportLogs()" 
                    class="flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>Export CSV
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-6">
        <!-- Total Logs -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Total Logs</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ number_format($stats['total_logs']) }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg">
                    <i class="fas fa-list text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Today's Logs -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Today's Logs</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['today_logs']) }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg">
                    <i class="fas fa-calendar-day text-green-600"></i>
                </div>
            </div>
        </div>
        
        <!-- This Week -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">This Week</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['week_logs']) }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg">
                    <i class="fas fa-calendar-week text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Active Users -->
        <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-neutral-500 text-sm font-medium">Active Users (7d)</p>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($stats['active_users']) }}</p>
                </div>
                <div class="flex items-center justify-center w-10 h-10 bg-orange-100 rounded-lg">
                    <i class="fas fa-users text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <!-- Search -->
            <div>
                <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                       placeholder="Search logs..." 
                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <!-- User Filter -->
            <div>
                <label for="user_id" class="block text-sm font-medium text-neutral-700 mb-1">User</label>
                <select id="user_id" name="user_id" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->fullName }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Action Filter -->
            <div>
                <label for="action" class="block text-sm font-medium text-neutral-700 mb-1">Action</label>
                <select id="action" name="action" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Model Type Filter -->
            <div>
                <label for="auditable_type" class="block text-sm font-medium text-neutral-700 mb-1">Model</label>
                <select id="auditable_type" name="auditable_type" class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $type)
                        <option value="{{ $type['value'] }}" {{ request('auditable_type') === $type['value'] ? 'selected' : '' }}>
                            {{ $type['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Date From -->
            <div>
                <label for="date_from" class="block text-sm font-medium text-neutral-700 mb-1">From Date</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
            </div>
            
            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.audit.index') }}" class="px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-lg shadow-sm border border-neutral-200">
        @if($auditLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Model</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Changes</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($auditLogs as $log)
                            <tr class="hover:bg-neutral-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900">{{ $log->created_at->format('M j, Y') }}</div>
                                    <div class="text-sm text-neutral-500">{{ $log->created_at->format('g:i A') }}</div>
                                    <div class="text-xs text-neutral-400">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center">
                                                <i class="fas fa-user text-primary-600 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-neutral-900">
                                                {{ $log->user ? $log->user->fullName : 'System' }}
                                            </div>
                                            @if($log->user)
                                                <div class="text-sm text-neutral-500">{{ $log->user->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $log->action === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $log->action === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $log->action === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ !in_array($log->action, ['created', 'updated', 'deleted']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900">{{ class_basename($log->auditable_type) }}</div>
                                    <div class="text-sm text-neutral-500">ID: {{ $log->auditable_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-900">{{ $log->ip_address ?: 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->old_values || $log->new_values)
                                        @php
                                            $oldValues = json_decode($log->old_values, true) ?: [];
                                            $newValues = json_decode($log->new_values, true) ?: [];
                                            $changeCount = count(array_merge(array_keys($oldValues), array_keys($newValues)));
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $changeCount }} {{ Str::plural('field', $changeCount) }}
                                        </span>
                                    @else
                                        <span class="text-neutral-400 text-sm">No changes</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('admin.audit.show', $log) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 transition-colors" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $auditLogs->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 mx-auto mb-4 bg-neutral-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-search text-neutral-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-neutral-900 mb-2">No audit logs found</h3>
                <p class="text-neutral-500 mb-6">
                    @if(request()->hasAny(['search', 'user_id', 'action', 'auditable_type', 'date_from']))
                        No logs match your current filters. Try adjusting your search criteria.
                    @else
                        No audit logs have been recorded yet.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'user_id', 'action', 'auditable_type', 'date_from']))
                    <a href="{{ route('admin.audit.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Cleanup Modal -->
    <div x-show="showCleanupModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" @click="showCleanupModal = false">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.audit.cleanup') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Cleanup Old Audit Logs</h3>
                        <div class="mb-4">
                            <label for="days" class="block text-sm font-medium text-gray-700 mb-2">Delete logs older than (days):</label>
                            <input type="number" id="days" name="days" min="30" max="365" value="90" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            <p class="mt-1 text-sm text-gray-500">Minimum: 30 days, Maximum: 365 days</p>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete Logs
                        </button>
                        <button type="button" @click="showCleanupModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function auditLogs() {
    return {
        autoRefresh: false,
        showCleanupModal: false,
        refreshInterval: null,
        
        init() {
            // Set up auto-refresh
            this.$watch('autoRefresh', (value) => {
                if (value) {
                    this.refreshInterval = setInterval(() => {
                        this.refreshLogs();
                    }, 30000); // Refresh every 30 seconds
                } else {
                    if (this.refreshInterval) {
                        clearInterval(this.refreshInterval);
                        this.refreshInterval = null;
                    }
                }
            });
        },
        
        async refreshLogs() {
            try {
                const response = await fetch('/admin/audit/api/logs?' + new URLSearchParams(window.location.search));
                const data = await response.json();
                
                if (data.success) {
                    // You could update the table dynamically here
                    // For now, we'll just show a subtle indication that data was refreshed
                    this.showToast('Logs refreshed', 'success');
                }
            } catch (error) {
                console.error('Failed to refresh logs:', error);
            }
        },
        
        exportLogs() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '/admin/audit/export/csv?' + params.toString();
        },
        
        showToast(message, type = 'success') {
            // Simple toast notification
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    }
}
</script>

@if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" 
         class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
            <button @click="show = false" class="ml-4 text-green-200 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
@endif
@endsection
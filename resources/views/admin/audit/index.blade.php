@extends('admin.layouts.app')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
<div class="p-6 lg:p-8" x-data="auditLogs()">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Audit Logs', 'icon' => 'scroll-text'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Audit Logs" 
        description="Monitor and track all system changes and user activities"
        class="mb-6"
    >
        <x-slot:actions>
            <x-ui.button 
                variant="secondary"
                @click="autoRefresh = !autoRefresh"
                ::class="autoRefresh ? 'bg-success-50 border-success-200 text-success-700' : ''"
            >
                <x-lucide-refresh-cw class="w-4 h-4" ::class="autoRefresh ? 'animate-spin' : ''" />
                <span x-text="autoRefresh ? 'Auto-refresh ON' : 'Auto-refresh OFF'"></span>
            </x-ui.button>
            <x-ui.button variant="secondary" @click="exportLogs()" icon="download">
                Export CSV
            </x-ui.button>
        </x-slot:actions>
    </x-ui.page-header>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.stat-card 
            label="Total Logs" 
            :value="number_format($stats['total_logs'])" 
            icon="scroll-text"
            icon-color="primary"
        />
        <x-ui.stat-card 
            label="Today's Logs" 
            :value="number_format($stats['today_logs'])" 
            icon="calendar-days"
            icon-color="success"
        />
        <x-ui.stat-card 
            label="This Week" 
            :value="number_format($stats['week_logs'])" 
            icon="calendar-range"
            icon-color="warning"
        />
        <x-ui.stat-card 
            label="Active Users (7d)" 
            :value="number_format($stats['active_users'])" 
            icon="users"
            icon-color="error"
        />
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.audit.index') }}" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Search -->
                <x-ui.input 
                    label="Search"
                    name="search"
                    :value="request('search')"
                    placeholder="Search logs..."
                />
                
                <!-- User Filter -->
                <x-ui.select label="User" name="user_id">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->fullName }}
                        </option>
                    @endforeach
                </x-ui.select>
                
                <!-- Action Filter -->
                <x-ui.select label="Action" name="action">
                    <option value="">All Actions</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>
                            {{ ucfirst($action) }}
                        </option>
                    @endforeach
                </x-ui.select>
                
                <!-- Model Type Filter -->
                <x-ui.select label="Model" name="auditable_type">
                    <option value="">All Models</option>
                    @foreach($modelTypes as $type)
                        <option value="{{ $type['value'] }}" {{ request('auditable_type') === $type['value'] ? 'selected' : '' }}>
                            {{ $type['label'] }}
                        </option>
                    @endforeach
                </x-ui.select>
                
                <!-- Date From -->
                <x-ui.input 
                    type="date"
                    label="From Date"
                    name="date_from"
                    :value="request('date_from')"
                />
                
                <!-- Actions -->
                <div class="flex items-end gap-2">
                    <x-ui.button type="submit" icon="search">
                        Filter
                    </x-ui.button>
                    <a href="{{ route('admin.audit.index') }}">
                        <x-ui.button type="button" variant="ghost" icon="x">
                            Clear
                        </x-ui.button>
                    </a>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Audit Logs Table -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        @if($auditLogs->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-100">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Timestamp</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">User</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Action</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Model</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">IP Address</th>
                            <th scope="col" class="px-6 py-3.5 text-left text-xs font-medium uppercase tracking-wider text-neutral-500">Changes</th>
                            <th scope="col" class="px-6 py-3.5 text-right text-xs font-medium uppercase tracking-wider text-neutral-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        @foreach($auditLogs as $log)
                            <tr class="hover:bg-neutral-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-neutral-800">{{ $log->created_at->format('M j, Y') }}</div>
                                    <div class="text-sm text-neutral-500">{{ $log->created_at->format('g:i A') }}</div>
                                    <div class="text-xs text-neutral-400">{{ $log->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-primary-50 flex items-center justify-center">
                                                <x-lucide-user class="w-4 h-4 text-primary-500" />
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-neutral-800">
                                                {{ $log->user ? $log->user->fullName : 'System' }}
                                            </div>
                                            @if($log->user)
                                                <div class="text-sm text-neutral-500">{{ $log->user->email }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-ui.badge 
                                        :type="$log->action === 'created' ? 'success' : ($log->action === 'updated' ? 'info' : ($log->action === 'deleted' ? 'error' : 'neutral'))"
                                    >
                                        {{ ucfirst($log->action) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-neutral-800">{{ class_basename($log->auditable_type) }}</div>
                                    <div class="text-sm text-neutral-500">ID: {{ $log->auditable_id }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-neutral-700">{{ $log->ip_address ?: 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log->old_values || $log->new_values)
                                        @php
                                            $oldValues = $log->old_values ?: [];
                                            $newValues = $log->new_values ?: [];
                                            $changeCount = count(array_merge(array_keys($oldValues), array_keys($newValues)));
                                        @endphp
                                        <x-ui.badge type="info">
                                            {{ $changeCount }} {{ Str::plural('field', $changeCount) }}
                                        </x-ui.badge>
                                    @else
                                        <span class="text-neutral-400 text-sm">No changes</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <a href="{{ route('admin.audit.show', $log) }}" 
                                       class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors inline-flex"
                                       title="View Details">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-neutral-100">
                <x-ui.pagination :paginator="$auditLogs->appends(request()->query())" />
            </div>
        @else
            <x-ui.empty-state 
                icon="scroll-text"
                title="No audit logs found"
                :description="request()->hasAny(['search', 'user_id', 'action', 'auditable_type', 'date_from']) 
                    ? 'No logs match your current filters. Try adjusting your search criteria.' 
                    : 'No audit logs have been recorded yet.'"
                class="py-12"
            >
                @if(request()->hasAny(['search', 'user_id', 'action', 'auditable_type', 'date_from']))
                    <a href="{{ route('admin.audit.index') }}" class="mt-4 inline-block">
                        <x-ui.button variant="secondary" icon="x">
                            Clear Filters
                        </x-ui.button>
                    </a>
                @endif
            </x-ui.empty-state>
        @endif
    </div>

    <!-- Cleanup Modal -->
    <div x-show="showCleanupModal" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="fixed inset-0 bg-neutral-900/50 backdrop-blur-sm" @click="showCleanupModal = false"></div>
            
            <div class="relative bg-white rounded-2xl shadow-lg max-w-md w-full p-6">
                <form action="{{ route('admin.audit.cleanup') }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-800">Cleanup Old Audit Logs</h3>
                            <p class="text-sm text-neutral-500 mt-1">Remove old logs to free up database space</p>
                        </div>
                        <button type="button" @click="showCleanupModal = false" class="p-2 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors">
                            <x-lucide-x class="w-5 h-5" />
                        </button>
                    </div>
                    
                    <!-- Form Content -->
                    <div class="mb-6">
                        <x-ui.input 
                            type="number"
                            label="Delete logs older than (days)"
                            name="days"
                            min="30"
                            max="365"
                            value="90"
                            required
                            hint="Minimum: 30 days, Maximum: 365 days"
                        />
                    </div>
                    
                    <!-- Modal Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-neutral-200">
                        <x-ui.button type="button" variant="ghost" @click="showCleanupModal = false">
                            Cancel
                        </x-ui.button>
                        <x-ui.button type="submit" variant="danger" icon="trash-2">
                            Delete Logs
                        </x-ui.button>
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
                    window.showSuccess('Refreshed', 'Logs have been refreshed');
                }
            } catch (error) {
                console.error('Failed to refresh logs:', error);
            }
        },
        
        exportLogs() {
            const params = new URLSearchParams(window.location.search);
            window.location.href = '/admin/audit/export/csv?' + params.toString();
        }
    }
}
</script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.showSuccess) {
                window.showSuccess('Success', '{{ session('success') }}');
            }
        });
    </script>
@endif
@endsection
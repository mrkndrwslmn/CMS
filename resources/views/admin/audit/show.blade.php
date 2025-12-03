@extends('admin.layouts.app')

@section('title', 'Audit Log Details')
@section('page-title', 'Audit Log Details')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Audit Logs', 'route' => 'admin.audit.index', 'icon' => 'scroll-text'],
        ['label' => 'Log #' . $auditLog->id, 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Audit Log Details" 
        description="Detailed information about this audit log entry"
        class="mb-6"
    >
        <x-slot:actions>
            <a href="{{ route('admin.audit.index') }}">
                <x-ui.button variant="secondary" icon="arrow-left">
                    Back to Audit Logs
                </x-ui.button>
            </a>
        </x-slot:actions>
    </x-ui.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <x-ui.card>
                <div class="p-6 border-b border-neutral-100">
                    <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                        <x-lucide-info class="w-5 h-5 text-neutral-400" />
                        Basic Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Action</label>
                            <x-ui.badge 
                                :type="$auditLog->action === 'created' ? 'success' : ($auditLog->action === 'updated' ? 'info' : ($auditLog->action === 'deleted' ? 'error' : 'neutral'))"
                            >
                                {{ ucfirst($auditLog->action) }}
                            </x-ui.badge>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Model Type</label>
                            <p class="text-sm font-medium text-neutral-800">{{ class_basename($auditLog->auditable_type) }}</p>
                            <p class="text-xs text-neutral-500">ID: {{ $auditLog->auditable_id }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Timestamp</label>
                            <p class="text-sm font-medium text-neutral-800">{{ $auditLog->created_at->format('M j, Y g:i:s A') }}</p>
                            <p class="text-xs text-neutral-500">{{ $auditLog->created_at->diffForHumans() }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">User</label>
                            @if($auditLog->user)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center mr-3">
                                        <x-lucide-user class="w-4 h-4 text-primary-500" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-800">{{ $auditLog->user->fullName }}</p>
                                        <p class="text-xs text-neutral-500">{{ $auditLog->user->email }}</p>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-neutral-500">System</p>
                            @endif
                        </div>
                    </div>
                </div>
            </x-ui.card>

            <!-- Changes Details -->
            @if($auditLog->old_values || $auditLog->new_values)
                <x-ui.card>
                    <div class="p-6 border-b border-neutral-100">
                        <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                            <x-lucide-git-compare class="w-5 h-5 text-neutral-400" />
                            Changes
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $oldValues = $auditLog->old_values ?: [];
                            $newValues = $auditLog->new_values ?: [];
                            $allFields = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                        @endphp
                        
                        <div class="space-y-4">
                            @foreach($allFields as $field)
                                <div class="border border-neutral-200 rounded-xl p-4">
                                    <h4 class="font-medium text-neutral-800 mb-3">{{ ucfirst(str_replace('_', ' ', $field)) }}</h4>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Old Value -->
                                        <div>
                                            <label class="block text-sm font-medium text-neutral-500 mb-2">Old Value</label>
                                            <div class="bg-error-50 border border-error-200 rounded-lg p-3">
                                                @if(isset($oldValues[$field]))
                                                    @if(is_array($oldValues[$field]) || is_object($oldValues[$field]))
                                                        <pre class="text-xs text-error-700 whitespace-pre-wrap">{{ json_encode($oldValues[$field], JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        <p class="text-sm text-error-700">{{ $oldValues[$field] ?: '(empty)' }}</p>
                                                    @endif
                                                @else
                                                    <p class="text-sm text-error-500 italic">(not set)</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- New Value -->
                                        <div>
                                            <label class="block text-sm font-medium text-neutral-500 mb-2">New Value</label>
                                            <div class="bg-success-50 border border-success-200 rounded-lg p-3">
                                                @if(isset($newValues[$field]))
                                                    @if(is_array($newValues[$field]) || is_object($newValues[$field]))
                                                        <pre class="text-xs text-success-700 whitespace-pre-wrap">{{ json_encode($newValues[$field], JSON_PRETTY_PRINT) }}</pre>
                                                    @else
                                                        <p class="text-sm text-success-700">{{ $newValues[$field] ?: '(empty)' }}</p>
                                                    @endif
                                                @else
                                                    <p class="text-sm text-success-500 italic">(not set)</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card>
            @endif

            <!-- Related Logs -->
            @if($relatedLogs->count() > 0)
                <x-ui.card>
                    <div class="p-6 border-b border-neutral-100">
                        <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                            <x-lucide-link class="w-5 h-5 text-neutral-400" />
                            Related Audit Logs
                        </h3>
                        <p class="text-sm text-neutral-500 mt-1">Other changes made to the same {{ class_basename($auditLog->auditable_type) }} record</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($relatedLogs as $relatedLog)
                                <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-xl">
                                    <div class="flex items-center">
                                        <x-ui.badge 
                                            :type="$relatedLog->action === 'created' ? 'success' : ($relatedLog->action === 'updated' ? 'info' : ($relatedLog->action === 'deleted' ? 'error' : 'neutral'))"
                                            class="mr-3"
                                        >
                                            {{ ucfirst($relatedLog->action) }}
                                        </x-ui.badge>
                                        <div>
                                            <p class="text-sm font-medium text-neutral-800">
                                                {{ $relatedLog->user ? $relatedLog->user->fullName : 'System' }}
                                            </p>
                                            <p class="text-xs text-neutral-500">{{ $relatedLog->created_at->format('M j, Y g:i A') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('admin.audit.show', $relatedLog) }}" 
                                       class="p-2 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                                        <x-lucide-external-link class="w-4 h-4" />
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Technical Details -->
            <x-ui.card>
                <div class="p-6 border-b border-neutral-100">
                    <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                        <x-lucide-code class="w-5 h-5 text-neutral-400" />
                        Technical Details
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-500 mb-1">Log ID</label>
                        <p class="text-sm font-mono text-neutral-800">{{ $auditLog->id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-500 mb-1">IP Address</label>
                        <p class="text-sm font-mono text-neutral-800">{{ $auditLog->ip_address ?: 'N/A' }}</p>
                    </div>
                    
                    @if($auditLog->user_agent)
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">User Agent</label>
                            <p class="text-xs text-neutral-600 break-all">{{ $auditLog->user_agent }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-500 mb-1">Full Model Path</label>
                        <p class="text-xs font-mono text-neutral-600 break-all">{{ $auditLog->auditable_type }}</p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Model Information -->
            @if($auditLog->auditable)
                <x-ui.card>
                    <div class="p-6 border-b border-neutral-100">
                        <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                            <x-lucide-database class="w-5 h-5 text-neutral-400" />
                            Current Model State
                        </h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Status</span>
                            <x-ui.badge type="success">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-success-400 rounded-full"></span>
                                Exists
                            </x-ui.badge>
                        </div>
                        
                        @if(method_exists($auditLog->auditable, 'getUpdatedAtAttribute'))
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-600">Last Updated</span>
                                <span class="text-sm text-neutral-800">{{ $auditLog->auditable->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                        
                        @if(method_exists($auditLog->auditable, 'getCreatedAtAttribute'))
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-600">Created</span>
                                <span class="text-sm text-neutral-800">{{ $auditLog->auditable->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </x-ui.card>
            @else
                <x-ui.card>
                    <div class="p-6 border-b border-neutral-100">
                        <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                            <x-lucide-database class="w-5 h-5 text-neutral-400" />
                            Model State
                        </h3>
                    </div>
                    <div class="p-6 text-center">
                        <div class="w-12 h-12 mx-auto mb-3 bg-error-50 rounded-full flex items-center justify-center">
                            <x-lucide-alert-triangle class="w-6 h-6 text-error-500" />
                        </div>
                        <p class="text-sm text-error-600 font-medium">Model No Longer Exists</p>
                        <p class="text-xs text-neutral-500 mt-1">This record has been deleted or is no longer available</p>
                    </div>
                </x-ui.card>
            @endif

            <!-- Actions -->
            <x-ui.card>
                <div class="p-6 border-b border-neutral-100">
                    <h3 class="text-lg font-medium text-neutral-700 flex items-center gap-3">
                        <x-lucide-mouse-pointer-click class="w-5 h-5 text-neutral-400" />
                        Quick Actions
                    </h3>
                </div>
                <div class="p-6 space-y-3">
                    @if($auditLog->auditable)
                        <button onclick="navigator.clipboard.writeText('{{ $auditLog->auditable_type }}:{{ $auditLog->auditable_id }}'); window.showSuccess('Copied', 'Model reference copied to clipboard');" 
                                class="w-full">
                            <x-ui.button variant="secondary" icon="copy" class="w-full justify-center">
                                Copy Model Reference
                            </x-ui.button>
                        </button>
                    @endif
                    
                    <button onclick="navigator.clipboard.writeText('{{ $auditLog->id }}'); window.showSuccess('Copied', 'Log ID copied to clipboard');" 
                            class="w-full">
                        <x-ui.button variant="ghost" icon="copy" class="w-full justify-center">
                            Copy Log ID
                        </x-ui.button>
                    </button>
                    
                    @if($auditLog->ip_address)
                        <button onclick="navigator.clipboard.writeText('{{ $auditLog->ip_address }}'); window.showSuccess('Copied', 'IP address copied to clipboard');" 
                                class="w-full">
                            <x-ui.button variant="ghost" icon="copy" class="w-full justify-center">
                                Copy IP Address
                            </x-ui.button>
                        </button>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection
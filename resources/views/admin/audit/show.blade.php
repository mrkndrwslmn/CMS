@extends('admin.layouts.app')

@section('title', 'Audit Log Details')
@section('page-title', 'Audit Log Details')

@section('content')
<div class="px-6 py-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-500 mb-1">Audit Log Details</h1>
            <p class="text-neutral-500 text-sm">Detailed information about this audit log entry</p>
        </div>
        <a href="{{ route('admin.audit.index') }}" 
           class="flex items-center px-4 py-2 bg-neutral-200 hover:bg-neutral-300 text-neutral-700 rounded-lg transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Back to Audit Logs
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4">Basic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Action</label>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium 
                            {{ $auditLog->action === 'created' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $auditLog->action === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $auditLog->action === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                            {{ !in_array($auditLog->action, ['created', 'updated', 'deleted']) ? 'bg-gray-100 text-gray-800' : '' }}">
                            {{ ucfirst($auditLog->action) }}
                        </span>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Model Type</label>
                        <p class="text-sm text-neutral-900">{{ class_basename($auditLog->auditable_type) }}</p>
                        <p class="text-xs text-neutral-500">ID: {{ $auditLog->auditable_id }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Timestamp</label>
                        <p class="text-sm text-neutral-900">{{ $auditLog->created_at->format('M j, Y g:i:s A') }}</p>
                        <p class="text-xs text-neutral-500">{{ $auditLog->created_at->diffForHumans() }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">User</label>
                        @if($auditLog->user)
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-primary-600 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-900">{{ $auditLog->user->fullName }}</p>
                                    <p class="text-xs text-neutral-500">{{ $auditLog->user->email }}</p>
                                </div>
                            </div>
                        @else
                            <p class="text-sm text-neutral-500">System</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Changes Details -->
            @if($auditLog->old_values || $auditLog->new_values)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Changes</h3>
                    
                    @php
                        $oldValues = json_decode($auditLog->old_values, true) ?: [];
                        $newValues = json_decode($auditLog->new_values, true) ?: [];
                        $allFields = array_unique(array_merge(array_keys($oldValues), array_keys($newValues)));
                    @endphp
                    
                    <div class="space-y-4">
                        @foreach($allFields as $field)
                            <div class="border border-neutral-200 rounded-lg p-4">
                                <h4 class="font-medium text-neutral-900 mb-3">{{ ucfirst(str_replace('_', ' ', $field)) }}</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Old Value -->
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-2">Old Value</label>
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                            @if(isset($oldValues[$field]))
                                                @if(is_array($oldValues[$field]) || is_object($oldValues[$field]))
                                                    <pre class="text-xs text-red-800 whitespace-pre-wrap">{{ json_encode($oldValues[$field], JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    <p class="text-sm text-red-800">{{ $oldValues[$field] ?: '(empty)' }}</p>
                                                @endif
                                            @else
                                                <p class="text-sm text-red-500 italic">(not set)</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- New Value -->
                                    <div>
                                        <label class="block text-sm font-medium text-neutral-700 mb-2">New Value</label>
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                            @if(isset($newValues[$field]))
                                                @if(is_array($newValues[$field]) || is_object($newValues[$field]))
                                                    <pre class="text-xs text-green-800 whitespace-pre-wrap">{{ json_encode($newValues[$field], JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    <p class="text-sm text-green-800">{{ $newValues[$field] ?: '(empty)' }}</p>
                                                @endif
                                            @else
                                                <p class="text-sm text-green-500 italic">(not set)</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Related Logs -->
            @if($relatedLogs->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Related Audit Logs</h3>
                    <p class="text-sm text-neutral-600 mb-4">Other changes made to the same {{ class_basename($auditLog->auditable_type) }} record</p>
                    
                    <div class="space-y-3">
                        @foreach($relatedLogs as $relatedLog)
                            <div class="flex items-center justify-between p-3 bg-neutral-50 rounded-lg">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium mr-3
                                        {{ $relatedLog->action === 'created' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $relatedLog->action === 'updated' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $relatedLog->action === 'deleted' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ !in_array($relatedLog->action, ['created', 'updated', 'deleted']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                        {{ ucfirst($relatedLog->action) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-neutral-900">
                                            {{ $relatedLog->user ? $relatedLog->user->fullName : 'System' }}
                                        </p>
                                        <p class="text-xs text-neutral-500">{{ $relatedLog->created_at->format('M j, Y g:i A') }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.audit.show', $relatedLog) }}" 
                                   class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Technical Details -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4">Technical Details</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Log ID</label>
                        <p class="text-sm font-mono text-neutral-900">{{ $auditLog->id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">IP Address</label>
                        <p class="text-sm font-mono text-neutral-900">{{ $auditLog->ip_address ?: 'N/A' }}</p>
                    </div>
                    
                    @if($auditLog->user_agent)
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-1">User Agent</label>
                            <p class="text-xs text-neutral-600 break-all">{{ $auditLog->user_agent }}</p>
                        </div>
                    @endif
                    
                    <div>
                        <label class="block text-sm font-medium text-neutral-700 mb-1">Full Model Path</label>
                        <p class="text-xs font-mono text-neutral-600 break-all">{{ $auditLog->auditable_type }}</p>
                    </div>
                </div>
            </div>

            <!-- Model Information -->
            @if($auditLog->auditable)
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Current Model State</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-neutral-600">Status</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-1.5 h-1.5 mr-1.5 bg-green-400 rounded-full"></span>
                                Exists
                            </span>
                        </div>
                        
                        @if(method_exists($auditLog->auditable, 'getUpdatedAtAttribute'))
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-600">Last Updated</span>
                                <span class="text-sm text-neutral-900">{{ $auditLog->auditable->updated_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                        
                        @if(method_exists($auditLog->auditable, 'getCreatedAtAttribute'))
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-neutral-600">Created</span>
                                <span class="text-sm text-neutral-900">{{ $auditLog->auditable->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                    <h3 class="text-lg font-medium text-neutral-900 mb-4">Model State</h3>
                    
                    <div class="text-center py-4">
                        <div class="w-12 h-12 mx-auto mb-3 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                        </div>
                        <p class="text-sm text-red-600 font-medium">Model No Longer Exists</p>
                        <p class="text-xs text-neutral-500 mt-1">This record has been deleted or is no longer available</p>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-neutral-200 p-6">
                <h3 class="text-lg font-medium text-neutral-900 mb-4">Actions</h3>
                
                <div class="space-y-3">
                    @if($auditLog->auditable)
                        <button onclick="navigator.clipboard.writeText('{{ $auditLog->auditable_type }}:{{ $auditLog->auditable_id }}')" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-copy mr-2"></i>Copy Model Reference
                        </button>
                    @endif
                    
                    <button onclick="navigator.clipboard.writeText('{{ $auditLog->id }}')" 
                            class="w-full flex items-center justify-center px-4 py-2 bg-neutral-500 hover:bg-neutral-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-copy mr-2"></i>Copy Log ID
                    </button>
                    
                    @if($auditLog->ip_address)
                        <button onclick="navigator.clipboard.writeText('{{ $auditLog->ip_address }}')" 
                                class="w-full flex items-center justify-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                            <i class="fas fa-copy mr-2"></i>Copy IP Address
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Copy to clipboard functionality
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg';
        toast.textContent = 'Copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 2000);
    });
}
</script>
@endsection
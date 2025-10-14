@extends('admin.layouts.app')

@section('title', 'Client Requests')

@section('content')
<div class="px-6 py-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-primary-600 mb-1">Client Requests</h1>
            <p class="text-neutral-500">Manage and process client service requests</p>
        </div>
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <button type="button" id="exportBtn" 
                    class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                <i class="fas fa-download mr-2"></i>Export
            </button>
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-primary-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Total Requests
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-neutral-900">
                            {{ $stats['total_requests'] }}
                        </div>
                    </div>
                    <div class="rounded-full p-3 bg-primary-50 text-primary-500">
                        <i class="fas fa-file-alt fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-warning-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Pending
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-neutral-900">
                            {{ $stats['pending_requests'] }}
                        </div>
                    </div>
                    <div class="rounded-full p-3 bg-warning-50 text-warning-500">
                        <i class="fas fa-clock fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-success-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Approved
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-neutral-900">
                            {{ $stats['approved_requests'] }}
                        </div>
                    </div>
                    <div class="rounded-full p-3 bg-success-50 text-success-500">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border-l-4 border-error-500">
            <div class="p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <div class="text-xs font-medium text-neutral-500 uppercase tracking-wider">
                            Rejected
                        </div>
                        <div class="mt-1 text-2xl font-semibold text-neutral-900">
                            {{ $stats['rejected_requests'] }}
                        </div>
                    </div>
                    <div class="rounded-full p-3 bg-error-50 text-error-500">
                        <i class="fas fa-times-circle fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-primary-500">Search & Filters</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.requests.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}" 
                           class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                           placeholder="Search requests...">
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <select id="status" name="status" 
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority</label>
                    <select id="priority" name="priority" 
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-neutral-700 mb-1">Type</label>
                    <select id="type" name="type" 
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <option value="">All Types</option>
                        <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service Request</option>
                        <option value="support" {{ request('type') == 'support' ? 'selected' : '' }}>Support Request</option>
                        <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document Request</option>
                    </select>
                </div>

                <div>
                    <label for="client" class="block text-sm font-medium text-neutral-700 mb-1">Client</label>
                    <select id="client" name="client" 
                            class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                {{ $client->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.requests.index') }}" class="ml-2 px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Request List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-200">
            <h2 class="text-lg font-semibold text-primary-500">Client Requests</h2>
        </div>
        <div class="p-6">
            @if(count($requests) > 0)
                <div class="overflow-x-auto">
                    <form id="bulkActionForm" action="{{ route('admin.requests.bulk-action') }}" method="POST">
                        @csrf
                        <table class="w-full whitespace-nowrap">
                            <thead>
                                <tr class="bg-neutral-50 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    <th class="px-4 py-3 rounded-l-lg">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="selectAll" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                        </div>
                                    </th>
                                    <th class="px-4 py-3">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'title', 'direction' => request('direction') == 'asc' && request('sort') == 'title' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center group">
                                            <span>Request</span>
                                            <i class="fas fa-sort ml-1 text-neutral-300 group-hover:text-primary-500"></i>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'client_id', 'direction' => request('direction') == 'asc' && request('sort') == 'client_id' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center group">
                                            <span>Client</span>
                                            <i class="fas fa-sort ml-1 text-neutral-300 group-hover:text-primary-500"></i>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3">Type</th>
                                    <th class="px-4 py-3">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'deadline', 'direction' => request('direction') == 'asc' && request('sort') == 'deadline' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center group">
                                            <span>Deadline</span>
                                            <i class="fas fa-sort ml-1 text-neutral-300 group-hover:text-primary-500"></i>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Priority</th>
                                    <th class="px-4 py-3">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') == 'asc' && request('sort') == 'created_at' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center group">
                                            <span>Date</span>
                                            <i class="fas fa-sort ml-1 text-neutral-300 group-hover:text-primary-500"></i>
                                        </a>
                                    </th>
                                    <th class="px-4 py-3 rounded-r-lg text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                @foreach($requests as $request)
                                <tr class="hover:bg-neutral-50">
                                    <td class="px-4 py-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="request_ids[]" value="{{ $request->display_id }}" class="request-checkbox rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-medium text-neutral-900">
                                            <a href="{{ route('admin.requests.show', $request->display_id) }}" class="hover:text-primary-600">
                                                {{ $request->display_title }}
                                            </a>
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $request->type === 'service_request' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $request->type === 'service_request' ? 'Service Request' : 'Form' }}
                                            </span>
                                        </div>
                                        @if(!empty($request->display_description))
                                            <div class="text-neutral-500 text-sm truncate max-w-xs">
                                                {{ Str::limit($request->display_description, 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($request->client)
                                            <div class="font-medium text-neutral-900">{{ $request->client->fullName }}</div>
                                            <div class="text-neutral-500 text-sm">{{ $request->client->email }}</div>
                                        @else
                                            <span class="text-neutral-400">No client assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @php
                                            $typeClasses = [
                                                'web_development' => 'bg-blue-100 text-blue-800',
                                                'mobile_development' => 'bg-purple-100 text-purple-800',
                                                'design' => 'bg-cyan-100 text-cyan-800',
                                                'consulting' => 'bg-green-100 text-green-800',
                                                'seo' => 'bg-orange-100 text-orange-800',
                                                'other' => 'bg-gray-100 text-gray-800',
                                                // Legacy form business types
                                                'E-commerce' => 'bg-purple-100 text-purple-800',
                                                'Technology' => 'bg-blue-100 text-blue-800',
                                                'Education' => 'bg-green-100 text-green-800',
                                                'Healthcare' => 'bg-cyan-100 text-cyan-800',
                                            ];
                                            $class = $typeClasses[$request->display_type] ?? 'bg-neutral-100 text-neutral-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->display_type ?? 'Unknown')) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($request->deadline)
                                            @php 
                                                $deadline = \Carbon\Carbon::parse($request->deadline);
                                                $isPast = $deadline->isPast();
                                                $isClose = $deadline->diffInDays(now()) <= 3 && !$isPast;
                                            @endphp
                                            
                                            <span class="{{ $isPast ? 'text-error-600' : ($isClose ? 'text-warning-600' : 'text-neutral-600') }}">
                                                {{ $deadline->format('M d, Y') }}
                                                @if($isPast)
                                                    <span class="block text-xs mt-1">
                                                        <i class="fas fa-exclamation-circle"></i> 
                                                        {{ $deadline->diffForHumans() }}
                                                    </span>
                                                @elseif($isClose)
                                                    <span class="block text-xs mt-1">
                                                        <i class="fas fa-clock"></i> 
                                                        {{ $deadline->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-neutral-400">Not set</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($request->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-warning-500 mr-1.5"></span>
                                                Pending
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-success-500 mr-1.5"></span>
                                                Approved
                                            </span>
                                        @elseif($request->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-error-500 mr-1.5"></span>
                                                Rejected
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                                <span class="h-1.5 w-1.5 rounded-full bg-neutral-500 mr-1.5"></span>
                                                {{ ucfirst($request->status ?? 'Unknown') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @php
                                            $priorityClasses = [
                                                'low' => 'bg-info-100 text-info-800',
                                                'medium' => 'bg-warning-100 text-warning-800',
                                                'high' => 'bg-orange-100 text-orange-800',
                                                'urgent' => 'bg-error-100 text-error-800',
                                            ];
                                            $priorityClass = $priorityClasses[$request->priority ?? 'low'] ?? 'bg-neutral-100 text-neutral-800';
                                            
                                            $priorityIcons = [
                                                'low' => '<i class="fas fa-arrow-down mr-1 text-xs"></i>',
                                                'medium' => '<i class="fas fa-minus mr-1 text-xs"></i>',
                                                'high' => '<i class="fas fa-arrow-up mr-1 text-xs"></i>',
                                                'urgent' => '<i class="fas fa-exclamation mr-1 text-xs"></i>',
                                            ];
                                            $priorityIcon = $priorityIcons[$request->priority ?? 'low'] ?? '';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityClass }}">
                                            {!! $priorityIcon !!}
                                            {{ ucfirst($request->priority ?? 'Low') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-neutral-500 text-sm">
                                        {{ date('M d, Y', strtotime($request->created_at)) }}
                                    </td>
                                    <td class="px-4 py-4 text-right">
                                        <div class="flex justify-end items-center space-x-2">
                                            <a href="{{ route('admin.requests.show', $request->display_id) }}" 
                                               class="p-2 text-neutral-600 hover:text-primary-600 hover:bg-primary-50 rounded-full" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($request->status === 'pending')
                                                <button type="button"
                                                        class="approve-request p-2 text-success-600 hover:bg-success-50 rounded-full"
                                                        data-id="{{ $request->display_id }}" 
                                                        data-type="{{ $request->type }}"
                                                        title="Approve Request">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button"
                                                        class="reject-request p-2 text-error-600 hover:bg-error-50 rounded-full"
                                                        data-id="{{ $request->display_id }}" 
                                                        data-type="{{ $request->type }}"
                                                        title="Reject Request">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Bulk Actions -->
                        <div class="mt-4 flex flex-col sm:flex-row items-start sm:items-center justify-between">
                            <div class="flex items-center mb-4 sm:mb-0">
                                <select name="action" class="mr-2 rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Bulk Actions</option>
                                    <option value="approve">Approve Selected</option>
                                    <option value="reject">Reject Selected</option>
                                    <option value="update_priority">Update Priority</option>
                                </select>
                                <select name="priority" class="mr-2 rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="">Select Priority</option>
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                                <textarea name="bulk_notes" placeholder="Add notes (optional)" class="mr-2 rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50 hidden" id="bulkNotesField"></textarea>
                                <button type="submit" id="applyBulkAction" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                    Apply
                                </button>
                            </div>

                            <!-- Pagination -->
                            <div class="pagination">
                                {{ $requests->links() }}
                            </div>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-alt text-neutral-400 text-xl"></i>
                    </div>
                    <h3 class="text-neutral-500 text-base">No requests found</h3>
                    <p class="text-neutral-400 text-sm mt-1">There are no client requests matching your filters.</p>
                    <a href="{{ route('admin.requests.index') }}" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Export Form -->
<form id="exportForm" action="{{ route('admin.requests.export') }}" method="GET" class="hidden">
    @if(request()->filled('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
    @endif
    @if(request()->filled('status'))
        <input type="hidden" name="status" value="{{ request('status') }}">
    @endif
    @if(request()->filled('priority'))
        <input type="hidden" name="priority" value="{{ request('priority') }}">
    @endif
    @if(request()->filled('type'))
        <input type="hidden" name="type" value="{{ request('type') }}">
    @endif
    @if(request()->filled('client'))
        <input type="hidden" name="client" value="{{ request('client') }}">
    @endif
    @if(request()->filled('date_from'))
        <input type="hidden" name="date_from" value="{{ request('date_from') }}">
    @endif
    @if(request()->filled('date_to'))
        <input type="hidden" name="date_to" value="{{ request('date_to') }}">
    @endif
</form>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all checkboxes
    $('#selectAll').on('change', function() {
        $('.request-checkbox').prop('checked', $(this).is(':checked'));
    });
    
    // Deselect "select all" if any individual checkbox is unchecked
    $('.request-checkbox').on('change', function() {
        if(!$(this).is(':checked')) {
            $('#selectAll').prop('checked', false);
        } else {
            // Check if all individual checkboxes are checked
            if($('.request-checkbox:checked').length === $('.request-checkbox').length) {
                $('#selectAll').prop('checked', true);
            }
        }
    });
    
    // Show/hide bulk notes field based on action selection
    $('select[name="action"]').on('change', function() {
        const action = $(this).val();
        if(action === 'approve' || action === 'reject') {
            $('#bulkNotesField').removeClass('hidden');
        } else {
            $('#bulkNotesField').addClass('hidden');
        }
        
        if(action === 'update_priority') {
            $('select[name="priority"]').removeClass('hidden');
        } else {
            $('select[name="priority"]').addClass('hidden');
        }
    });
    
    // Validate before submitting bulk action form
    $('#applyBulkAction').on('click', function(e) {
        e.preventDefault();
        
        const checkedRequests = $('.request-checkbox:checked').length;
        if(checkedRequests === 0) {
            alert('Please select at least one request to perform action.');
            return;
        }
        
        const action = $('select[name="action"]').val();
        if(!action) {
            alert('Please select an action to perform.');
            return;
        }
        
        if(action === 'update_priority' && !$('select[name="priority"]').val()) {
            alert('Please select a priority.');
            return;
        }
        
        // Confirm before bulk actions
        if(action === 'approve') {
            if(confirm('Are you sure you want to approve ' + checkedRequests + ' selected requests?')) {
                $('#bulkActionForm').submit();
            }
        } else if(action === 'reject') {
            if($('#bulkNotesField').val().trim() === '') {
                alert('Please provide rejection notes.');
                return;
            }
            if(confirm('Are you sure you want to reject ' + checkedRequests + ' selected requests?')) {
                $('#bulkActionForm').submit();
            }
        } else {
            $('#bulkActionForm').submit();
        }
    });
    
    // Export functionality
    $('#exportBtn').on('click', function() {
        $('#exportForm').submit();
    });
});
</script>
@endsection
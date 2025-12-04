@extends('admin.layouts.app')

@section('title', 'Client Requests')
@section('page-title', 'Client Requests')

@section('content')
<div class="px-6 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Home', 'url' => route('admin.dashboard'), 'icon' => 'home'],
        ['label' => 'Requests', 'icon' => 'file-text']
    ]" class="mb-4" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <x-ui.page-header
            title="Client Requests"
            subtitle="Manage and process client service requests"
        />
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <x-ui.button 
                variant="primary"
                onclick="document.getElementById('exportForm').submit()"
                class="inline-flex items-center gap-2"
            >
                <x-lucide-download class="w-4 h-4" />
                Export
            </x-ui.button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Requests</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total_requests'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-file-text class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>
        
        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['pending_requests'] }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>
        
        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Approved</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['approved_requests'] }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>
        
        <x-ui.card class="p-5">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Rejected</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['rejected_requests'] }}</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-x-circle class="w-5 h-5 text-error-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <div class="px-6 py-4 border-b border-neutral-200">
            <div class="flex items-center gap-2">
                <x-lucide-filter class="w-5 h-5 text-primary-500" />
                <h2 class="text-lg font-semibold text-neutral-800">Search & Filters</h2>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.requests.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-neutral-700 mb-1">Search</label>
                    <x-ui.input 
                        type="text" 
                        id="search" 
                        name="search" 
                        :value="request('search')"
                        placeholder="Search requests..."
                    />
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-1">Status</label>
                    <x-ui.select id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </x-ui.select>
                </div>

                <div>
                    <label for="priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority</label>
                    <x-ui.select id="priority" name="priority">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    </x-ui.select>
                </div>
                
                <div>
                    <label for="type" class="block text-sm font-medium text-neutral-700 mb-1">Type</label>
                    <x-ui.select id="type" name="type">
                        <option value="">All Types</option>
                        <option value="service" {{ request('type') == 'service' ? 'selected' : '' }}>Service Request</option>
                        <option value="support" {{ request('type') == 'support' ? 'selected' : '' }}>Support Request</option>
                        <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Document Request</option>
                    </x-ui.select>
                </div>

                <div>
                    <label for="client" class="block text-sm font-medium text-neutral-700 mb-1">Client</label>
                    <x-ui.select id="client" name="client">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                {{ $client->fullName }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>
                
                <div class="flex items-end gap-2">
                    <x-ui.button type="submit" variant="primary" class="inline-flex items-center gap-2">
                        <x-lucide-search class="w-4 h-4" />
                        Filter
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.requests.index') }}" variant="secondary" class="inline-flex items-center gap-2">
                        <x-lucide-x class="w-4 h-4" />
                        Clear
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Request List -->
    <x-ui.card class="overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-200">
            <div class="flex items-center gap-2">
                <x-lucide-list class="w-5 h-5 text-primary-500" />
                <h2 class="text-lg font-semibold text-neutral-800">Client Requests</h2>
            </div>
        </div>
        <div class="p-6">
            @if(count($requests) > 0)
                <div class="overflow-x-auto -mx-6 px-6">
                    <form id="bulkActionForm" action="{{ route('admin.requests.bulk-action') }}" method="POST">
                        @csrf
                        <table class="w-full min-w-max">
                            <thead>
                                <tr class="bg-neutral-50 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                    <th class="px-3 py-3 rounded-l-lg w-10">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="selectAll" class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                        </div>
                                    </th>
                                    <th class="px-3 py-3 min-w-[200px]">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'title', 'direction' => request('direction') == 'asc' && request('sort') == 'title' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center gap-1 group">
                                            <span>Request</span>
                                            <x-lucide-arrow-up-down class="w-3 h-3 text-neutral-300 group-hover:text-primary-500" />
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 min-w-[150px]">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'client_id', 'direction' => request('direction') == 'asc' && request('sort') == 'client_id' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center gap-1 group">
                                            <span>Client</span>
                                            <x-lucide-arrow-up-down class="w-3 h-3 text-neutral-300 group-hover:text-primary-500" />
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 min-w-[120px]">Type</th>
                                    <th class="px-3 py-3 min-w-[110px]">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'deadline', 'direction' => request('direction') == 'asc' && request('sort') == 'deadline' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center gap-1 group">
                                            <span>Deadline</span>
                                            <x-lucide-arrow-up-down class="w-3 h-3 text-neutral-300 group-hover:text-primary-500" />
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 min-w-[100px]">Status</th>
                                    <th class="px-3 py-3 min-w-[90px]">Priority</th>
                                    <th class="px-3 py-3 min-w-[100px]">
                                        <a href="{{ route('admin.requests.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') == 'asc' && request('sort') == 'created_at' ? 'desc' : 'asc'])) }}" 
                                           class="flex items-center gap-1 group">
                                            <span>Date</span>
                                            <x-lucide-arrow-up-down class="w-3 h-3 text-neutral-300 group-hover:text-primary-500" />
                                        </a>
                                    </th>
                                    <th class="px-3 py-3 rounded-r-lg text-right min-w-[120px]">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-100">
                                @foreach($requests as $request)
                                <tr class="hover:bg-neutral-50">
                                    <td class="px-3 py-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="request_ids[]" value="{{ $request->id }}" class="request-checkbox rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                                        </div>
                                    </td>
                                    <td class="px-3 py-4">
                                        <div class="font-medium text-neutral-900 whitespace-normal">
                                            <a href="{{ route('admin.requests.show', $request->id) }}" class="hover:text-primary-600" title="{{ $request->project_name }}">
                                                {{ Str::limit($request->project_name, 30) }}
                                            </a>
                                            <span class="ml-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 whitespace-nowrap">
                                                Service Request
                                            </span>
                                        </div>
                                        @if(!empty($request->request_description))
                                            <div class="text-neutral-500 text-sm mt-1 line-clamp-2">
                                                {{ Str::limit($request->request_description, limit: 50) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4">
                                        @if($request->client)
                                            <div class="font-medium text-neutral-900 whitespace-nowrap">{{ $request->client->fullName }}</div>
                                            <div class="text-neutral-500 text-sm truncate max-w-[150px]">{{ $request->client->email }}</div>
                                        @else
                                            <span class="text-neutral-400 whitespace-nowrap">No client assigned</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4">
                                        @php
                                            $typeClasses = [
                                                'web_development' => 'bg-blue-100 text-blue-800',
                                                'mobile_development' => 'bg-purple-100 text-purple-800',
                                                'design' => 'bg-cyan-100 text-cyan-800',
                                                'consulting' => 'bg-green-100 text-green-800',
                                                'seo' => 'bg-orange-100 text-orange-800',
                                                'other' => 'bg-gray-100 text-gray-800',
                                            ];
                                            $class = $typeClasses[$request->service_type] ?? 'bg-neutral-100 text-neutral-800';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium whitespace-nowrap {{ $class }}">
                                            {{ ucfirst(str_replace('_', ' ', $request->service_type ?? 'Unknown')) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        @if($request->deadline)
                                            @php 
                                                $deadline = \Carbon\Carbon::parse($request->deadline);
                                                $isPast = $deadline->isPast();
                                                $isClose = $deadline->diffInDays(now()) <= 3 && !$isPast;
                                            @endphp
                                            
                                            <span class="{{ $isPast ? 'text-error-600' : ($isClose ? 'text-warning-600' : 'text-neutral-600') }}">
                                                {{ $deadline->format('M d, Y') }}
                                                @if($isPast)
                                                    <span class="flex items-center gap-1 text-xs mt-1">
                                                        <x-lucide-alert-circle class="w-3 h-3" />
                                                        Overdue
                                                    </span>
                                                @elseif($isClose)
                                                    <span class="flex items-center gap-1 text-xs mt-1">
                                                        <x-lucide-clock class="w-3 h-3" />
                                                        Soon
                                                    </span>
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-neutral-400">Not set</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        @if($request->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-700">
                                                <x-lucide-clock class="w-3 h-3 mr-1" />
                                                Pending
                                            </span>
                                        @elseif($request->status === 'approved')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">
                                                <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                                Approved
                                            </span>
                                        @elseif($request->status === 'rejected')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-error-100 text-error-700">
                                                <x-lucide-x-circle class="w-3 h-3 mr-1" />
                                                Rejected
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-700">
                                                {{ ucfirst($request->status ?? 'Unknown') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        @php
                                            $priorityClasses = [
                                                'low' => 'bg-info-100 text-info-700',
                                                'medium' => 'bg-warning-100 text-warning-700',
                                                'high' => 'bg-orange-100 text-orange-700',
                                                'urgent' => 'bg-error-100 text-error-700',
                                            ];
                                            $priorityClass = $priorityClasses[$request->priority ?? 'low'] ?? 'bg-neutral-100 text-neutral-700';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityClass }}">
                                            @if($request->priority === 'low')
                                                <x-lucide-arrow-down class="w-3 h-3 mr-1" />
                                            @elseif($request->priority === 'medium')
                                                <x-lucide-minus class="w-3 h-3 mr-1" />
                                            @elseif($request->priority === 'high')
                                                <x-lucide-arrow-up class="w-3 h-3 mr-1" />
                                            @elseif($request->priority === 'urgent')
                                                <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                            @endif
                                            {{ ucfirst($request->priority ?? 'Low') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-4 text-neutral-500 text-sm whitespace-nowrap">
                                        {{ date('M d, Y', strtotime($request->created_at)) }}
                                    </td>
                                    <td class="px-3 py-4 text-right">
                                        <div class="flex justify-end items-center space-x-1">
                                            <a href="{{ route('admin.requests.show', $request->id) }}" 
                                               class="p-2 text-neutral-600 hover:text-primary-600 hover:bg-primary-50 rounded-full" 
                                               title="View Details">
                                                <x-lucide-eye class="w-4 h-4" />
                                            </a>
                                            @if($request->status === 'pending')
                                                <button type="button"
                                                        class="approve-request p-2 text-success-600 hover:bg-success-50 rounded-full"
                                                        data-id="{{ $request->id }}" 
                                                        title="Approve Request">
                                                    <x-lucide-check class="w-4 h-4" />
                                                </button>
                                                <button type="button"
                                                        class="reject-request p-2 text-error-600 hover:bg-error-50 rounded-full"
                                                        data-id="{{ $request->id }}" 
                                                        title="Reject Request">
                                                    <x-lucide-x class="w-4 h-4" />
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
                    <div class="w-16 h-16 bg-neutral-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <x-lucide-inbox class="w-8 h-8 text-neutral-400" />
                    </div>
                    <h3 class="text-neutral-600 text-lg">No requests found</h3>
                    <p class="text-neutral-500 text-sm mt-2">There are no client requests matching your filters.</p>
                    <x-ui.button 
                        href="{{ route('admin.requests.index') }}" 
                        variant="primary"
                        class="mt-4 inline-flex items-center gap-2"
                    >
                        <x-lucide-refresh-cw class="w-4 h-4" />
                        Clear Filters
                    </x-ui.button>
                </div>
            @endif
        </div>
    </x-ui.card>
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
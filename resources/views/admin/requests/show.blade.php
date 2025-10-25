@extends('admin.layouts.app')

@section('title', 'Request Details')
@section('page-title', 'Request Details')

@section('content')
<div class="px-6 py-8" x-data="{
    showApproveModal: false,
    showRejectModal: false,
    showCreateTaskModal: false,
    
    openApproveModal() {
        this.showApproveModal = true;
        console.log('Opening Approve Modal', this.showApproveModal);
    },
    
    openRejectModal() {
        this.showRejectModal = true;
        console.log('Opening Reject Modal', this.showRejectModal);
    },
    
    openCreateTaskModal() {
        this.showCreateTaskModal = true;
        console.log('Opening Create Task Modal', this.showCreateTaskModal);
    }
}">
    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-success-50 border-l-4 border-success-500 text-success-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-success-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-success-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-error-50 border-l-4 border-error-500 text-error-700 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-error-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-error-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8">
        <div>
            <div class="flex items-center mb-2">
                @php
                    $requestStatus = $request['status'] ?? 'pending';
                    $statusBadgeClasses = [
                        'pending' => 'bg-warning-100 text-warning-800',
                        'approved' => 'bg-success-100 text-success-800',
                        'rejected' => 'bg-error-100 text-error-800',
                        'pending_payment' => 'bg-warning-100 text-warning-800',
                        'paid' => 'bg-success-100 text-success-800',
                    ];
                    $statusBadgeClass = $statusBadgeClasses[$requestStatus] ?? 'bg-neutral-100 text-neutral-800';
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $statusBadgeClass }} mr-3">
                    @if($requestStatus === 'pending')
                        <span class="h-2 w-2 rounded-full bg-warning-500 mr-1.5"></span>Pending
                    @elseif($requestStatus === 'approved')
                        <span class="h-2 w-2 rounded-full bg-success-500 mr-1.5"></span>Approved
                    @elseif($requestStatus === 'rejected')
                        <span class="h-2 w-2 rounded-full bg-error-500 mr-1.5"></span>Rejected
                    @elseif($requestStatus === 'pending_payment')
                        <span class="h-2 w-2 rounded-full bg-warning-500 mr-1.5"></span>Pending Payment
                    @elseif($requestStatus === 'paid')
                        <span class="h-2 w-2 rounded-full bg-success-500 mr-1.5"></span>Paid
                    @else
                        <span class="h-2 w-2 rounded-full bg-neutral-500 mr-1.5"></span>{{ ucfirst($requestStatus) }}
                    @endif
                </span>
                
                @php
                    $requestPriority = $request['priority'] ?? 'low';
                    $priorityBadgeClasses = [
                        'low' => 'bg-info-100 text-info-800',
                        'medium' => 'bg-warning-100 text-warning-800',
                        'high' => 'bg-orange-100 text-orange-800',
                        'urgent' => 'bg-error-100 text-error-800'
                    ];
                    $priorityBadgeClass = $priorityBadgeClasses[$requestPriority] ?? 'bg-neutral-100 text-neutral-800';
                    
                    $priorityIcons = [
                        'low' => 'fa-arrow-down',
                        'medium' => 'fa-minus',
                        'high' => 'fa-arrow-up',
                        'urgent' => 'fa-exclamation'
                    ];
                    $priorityIcon = $priorityIcons[$requestPriority] ?? 'fa-circle';
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium {{ $priorityBadgeClass }}">
                    <i class="fas {{ $priorityIcon }} mr-1.5 text-xs"></i>
                    {{ ucfirst($requestPriority) }} Priority
                </span>
            </div>
            
            <h1 class="text-2xl font-semibold text-primary-600 mb-1">
                {{ $request['title'] ?? 'Request #' . $request['id'] }}
            </h1>
            
            <div class="text-neutral-500 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                <span>Submitted {{ isset($request['submissionDate']) ? date('F d, Y', strtotime($request['submissionDate'])) : 
                (isset($request['submission_date']) ? date('F d, Y', strtotime($request['submission_date'])) : 
                date('F d, Y', strtotime($request['created_at']))) }}</span>
            </div>
        </div>
        
        <div class="mt-4 sm:mt-0 flex flex-wrap gap-3">
            <a href="{{ route('admin.requests.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Requests
            </a>
            
            @if($requestStatus === 'pending')
                <button type="button" 
                        @click="openApproveModal()"
                        class="inline-flex items-center px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
                <button type="button" 
                        @click="openRejectModal()"
                        class="inline-flex items-center px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-times mr-2"></i>Reject
                </button>
            @elseif($requestStatus === 'approved' || $requestStatus === 'pending_payment')
                <a href="{{ route('client.maya.checkout', $request['id']) }}" 
                   target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                    <i class="fas fa-credit-card mr-2"></i>View Payment Link
                </a>
            @elseif($requestStatus === 'rejected')
                <form action="{{ route('admin.requests.reopen', $request['id']) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-warning-500 hover:bg-warning-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-redo-alt mr-2"></i>Reopen
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Request Details -->
        <div class="lg:col-span-2">
            <!-- Request Details Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Request Details</h2>
                </div>
                <div class="p-6">
                    @if(!empty($request['projectDescription']) || !empty($request['project_description']))
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Project Description</h3>
                            <div class="prose max-w-none text-neutral-800">
                                {!! nl2br(e($request['projectDescription'] ?? $request['project_description'])) !!}
                            </div>
                        </div>
                    @endif

                    @if(!empty($request['specialRequests']) || !empty($request['special_requests']))
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Special Requests</h3>
                            <div class="prose max-w-none text-neutral-800">
                                {!! nl2br(e($request['specialRequests'] ?? $request['special_requests'])) !!}
                            </div>
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Request Information</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Request ID:</div>
                                    <div class="flex-1 text-neutral-800 font-medium">
                                        {{ $request['id'] ?? 'N/A' }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Type:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ ucfirst($request['businessType'] ?? $request['business_type'] ?? 'N/A') }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Status:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ ucfirst($requestStatus) }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Priority:</div>
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <form action="{{ route('admin.requests.update-priority', $request['id']) }}" method="POST" class="flex items-center" id="priorityForm">
                                                @csrf
                                                @method('PATCH')
                                                <select name="priority" id="priority" onchange="document.getElementById('priorityForm').submit()"
                                                        class="rounded-lg border border-neutral-300 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                                    <option value="low" {{ $requestPriority === 'low' ? 'selected' : '' }}>Low</option>
                                                    <option value="medium" {{ $requestPriority === 'medium' ? 'selected' : '' }}>Medium</option>
                                                    <option value="high" {{ $requestPriority === 'high' ? 'selected' : '' }}>High</option>
                                                    <option value="urgent" {{ $requestPriority === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                                </select>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Company:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ $request['companyName'] ?? $request['company_name'] ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Timeline</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Submitted:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ isset($request['submission_date']) ? date('M d, Y', strtotime($request['submission_date'])) : date('M d, Y', strtotime($request['created_at'])) }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Deadline:</div>
                                    <div class="flex-1 text-neutral-800">
                                        @if(isset($request['deadline']))
                                            @php 
                                                $deadline = \Carbon\Carbon::parse($request['deadline']);
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
                                    </div>
                                </div>
                                
                                @if(!empty($request['reviewed_at']))
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Reviewed:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ date('M d, Y', strtotime($request['reviewed_at'])) }}
                                    </div>
                                </div>
                                @endif
                                
                                @if(!empty($request['reviewed_by']))
                                <div class="flex">
                                    <div class="w-32 text-neutral-500">Reviewed by:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ \App\Models\User::find($request['reviewed_by'])?->fullName ?? 'Admin #' . $request['reviewed_by'] }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Payment Information Card (Maya automatic payment info) -->
            @if($requestStatus === 'approved' || $requestStatus === 'pending_payment')
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border-2 border-primary-200">
                <div class="px-6 py-4 border-b border-primary-200 bg-primary-50">
                    <h2 class="text-lg font-semibold text-primary-700 flex items-center">
                        <i class="fas fa-credit-card mr-2"></i>
                        Payment Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-4">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-info-circle text-primary-600 mr-2"></i>
                            <p class="text-primary-800 font-medium">This request uses Maya automatic payment gateway</p>
                        </div>
                        <p class="text-primary-700 text-sm">Client will pay via Maya, and the payment will be automatically verified. Once confirmed, the project will be created automatically.</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-3">Payment Details</h3>
                            <div class="space-y-3">
                                <div class="flex">
                                    <div class="w-36 text-neutral-500">Amount:</div>
                                    <div class="flex-1 text-neutral-800 font-semibold text-lg">
                                        ₱{{ number_format($request['approved_budget'] ?? 0, 2) }}
                                    </div>
                                </div>
                                
                                <div class="flex">
                                    <div class="w-36 text-neutral-500">Payment Method:</div>
                                    <div class="flex-1 text-neutral-800">
                                        Maya Payment Gateway
                                    </div>
                                </div>
                                
                                @if(!empty($request['payment_due_date']))
                                <div class="flex">
                                    <div class="w-36 text-neutral-500">Due Date:</div>
                                    <div class="flex-1 text-neutral-800">
                                        {{ \Carbon\Carbon::parse($request['payment_due_date'])->format('M d, Y') }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-3">Payment Status</h3>
                            <div class="space-y-3">
                                <div class="bg-warning-50 border border-warning-200 rounded-lg p-4">
                                    <div class="flex items-center text-warning-800">
                                        <i class="fas fa-clock mr-2"></i>
                                        <span class="font-medium">Waiting for Client Payment</span>
                                    </div>
                                    <p class="text-warning-700 text-sm mt-2">Client needs to complete payment via Maya</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Attached Files -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Attached Files</h2>
                </div>
                <div class="p-6">
                    @if(isset($request['files']) && count($request['files']) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($request['files'] as $file)
                                <div class="bg-neutral-50 rounded-lg p-4 flex items-center">
                                    @php
                                        $extension = strtolower(pathinfo($file['filename'], PATHINFO_EXTENSION));
                                        
                                        $iconClass = match($extension) {
                                            'pdf' => 'fas fa-file-pdf text-error-600',
                                            'doc', 'docx' => 'fas fa-file-word text-blue-600',
                                            'xls', 'xlsx' => 'fas fa-file-excel text-success-600',
                                            'ppt', 'pptx' => 'fas fa-file-powerpoint text-orange-600',
                                            'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-purple-600',
                                            'zip', 'rar' => 'fas fa-file-archive text-warning-600',
                                            default => 'fas fa-file text-neutral-600'
                                        };
                                    @endphp
                                    
                                    <div class="h-10 w-10 flex-shrink-0 flex items-center justify-center">
                                        <i class="{{ $iconClass }} text-xl"></i>
                                    </div>
                                    
                                    <div class="ml-4 flex-1 min-w-0">
                                        <div class="text-sm font-medium text-neutral-900 truncate">
                                            {{ $file['filename'] }}
                                        </div>
                                        <div class="text-xs text-neutral-500">
                                            {{ isset($file['uploaded_at']) ? date('M d, Y', strtotime($file['uploaded_at'])) : 'Unknown date' }}
                                            @if(isset($file['file_size']))
                                                · {{ $file['file_size'] }}
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <a href="{{ route('admin.requests.download-file', ['request' => $request['id'], 'file' => $file['id']]) }}" 
                                       class="ml-4 p-2 text-neutral-500 hover:text-primary-600 rounded-full hover:bg-primary-50">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-file-alt text-neutral-400 text-xl"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No files attached</h3>
                            <p class="text-neutral-400 text-sm mt-1">This request doesn't have any attached files</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Associated Tasks -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Associated Tasks</h2>
                </div>
                <div class="p-6">
                    @if(isset($request['tasks']) && count($request['tasks']) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full whitespace-nowrap">
                                <thead>
                                    <tr class="bg-neutral-50 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                        <th class="px-4 py-3 rounded-l-lg">Task</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Priority</th>
                                        <th class="px-4 py-3">Assigned To</th>
                                        <th class="px-4 py-3 rounded-r-lg">Due Date</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-100">
                                    @foreach($request['tasks'] as $task)
                                        <tr class="hover:bg-neutral-50">
                                            <td class="px-4 py-3">
                                                <div class="font-medium text-neutral-900">
                                                    {{ $task['title'] ?? 'Task #' . $task['id'] }}
                                                </div>
                                                @if(!empty($task['description']))
                                                    <div class="text-neutral-500 text-sm truncate max-w-xs">
                                                        {{ Str::limit($task['description'], 50) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($task['status'] === 'completed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-success-500 mr-1.5"></span>
                                                        Completed
                                                    </span>
                                                @elseif($task['status'] === 'in_progress')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-warning-500 mr-1.5"></span>
                                                        In Progress
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-neutral-100 text-neutral-800">
                                                        <span class="h-1.5 w-1.5 rounded-full bg-neutral-500 mr-1.5"></span>
                                                        Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                @php
                                                    $taskPriority = $task['priority'] ?? 'low';
                                                    $priorityClasses = [
                                                        'low' => 'bg-info-100 text-info-800',
                                                        'medium' => 'bg-warning-100 text-warning-800',
                                                        'high' => 'bg-orange-100 text-orange-800',
                                                        'urgent' => 'bg-error-100 text-error-800',
                                                    ];
                                                    $priorityClass = $priorityClasses[$taskPriority] ?? 'bg-neutral-100 text-neutral-800';
                                                    
                                                    $priorityIcons = [
                                                        'low' => '<i class="fas fa-arrow-down mr-1 text-xs"></i>',
                                                        'medium' => '<i class="fas fa-minus mr-1 text-xs"></i>',
                                                        'high' => '<i class="fas fa-arrow-up mr-1 text-xs"></i>',
                                                        'urgent' => '<i class="fas fa-exclamation mr-1 text-xs"></i>',
                                                    ];
                                                    $priorityIcon = $priorityIcons[$taskPriority] ?? '';
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityClass }}">
                                                    {!! $priorityIcon !!}
                                                    {{ ucfirst($taskPriority) }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if(!empty($task['adiutor_id']))
                                                    <div class="flex items-center">
                                                        <div class="h-6 w-6 rounded-full bg-neutral-200 text-neutral-600 flex items-center justify-center mr-2">
                                                            <i class="fas fa-user text-xs"></i>
                                                        </div>
                                                        <span>{{ \App\Models\User::find($task['adiutor_id'])?->fullName ?? 'Adiutor #' . $task['adiutor_id'] }}</span>
                                                    </div>
                                                @else
                                                    <span class="text-neutral-400">Unassigned</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-neutral-600">
                                                {{ isset($task['due_date']) ? date('M d, Y', strtotime($task['due_date'])) : 'Not set' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="bg-neutral-50 rounded-full h-16 w-16 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tasks text-neutral-400 text-xl"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No tasks created</h3>
                            <p class="text-neutral-400 text-sm mt-1">There are no tasks associated with this request yet</p>
                            
                            @if($requestStatus === 'approved')
                                <button type="button" 
                                        @click="openCreateTaskModal()"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Create Task
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Right Column: Client Info, Admin Notes -->
        <div class="lg:col-span-1">
            <!-- Client Info Card -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Client Information</h2>
                </div>
                <div class="p-6">
                    @if(isset($request['client']))
                        <div class="flex items-center mb-6">
                            <div class="bg-primary-100 h-12 w-12 rounded-full flex items-center justify-center text-primary-600 mr-4">
                                <i class="fas fa-user text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-neutral-900">{{ $request['client']['full_name'] ?? $request['client']['name'] ?? 'Client #' . $request['client']['id'] }}</h3>
                                <div class="text-neutral-500">
                                    <i class="fas fa-envelope mr-2"></i>{{ $request['client']['email'] ?? 'No email' }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            @if(!empty($request['client']['phone_number']))
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Phone</div>
                                        <div class="text-neutral-900">{{ $request['client']['phone_number'] }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            @if(!empty($request['company_name']))
                                <div class="flex">
                                    <div class="w-8 flex-shrink-0 text-neutral-400">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-neutral-500">Company</div>
                                        <div class="text-neutral-900">{{ $request['company_name'] }}</div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="flex">
                                <div class="w-8 flex-shrink-0 text-neutral-400">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-neutral-500">Client Since</div>
                                    <div class="text-neutral-900">{{ isset($request['client']['created_at']) ? date('M d, Y', strtotime($request['client']['created_at'])) : 'Unknown' }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-4 border-t border-neutral-100">
                            <a href="{{ route('admin.clients.show', $request['client']['id']) }}" 
                               class="inline-flex items-center text-primary-600 hover:text-primary-700">
                                <span>View Client Profile</span>
                                <i class="fas fa-chevron-right ml-1 text-xs"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="bg-neutral-50 rounded-full h-12 w-12 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-neutral-400"></i>
                            </div>
                            <h3 class="text-neutral-500 text-base">No client information</h3>
                            <p class="text-neutral-400 text-sm mt-1">This request is not associated with a client</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Admin Notes -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-neutral-200">
                    <h2 class="text-lg font-semibold text-primary-500">Admin Notes</h2>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.requests.add-note', $request['id']) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="mb-3">
                            <textarea name="note" rows="3" required
                                      class="w-full rounded-lg border border-neutral-300 px-3 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="Add a note about this request..."></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                <i class="fas fa-plus mr-2"></i>Add Note
                            </button>
                        </div>
                    </form>
                    
                    @if(!empty($request['admin_notes']))
                        <div class="bg-neutral-50 rounded-lg p-4 text-neutral-800 whitespace-pre-line text-sm">
                            {!! nl2br(e($request['admin_notes'])) !!}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="text-neutral-500">No admin notes yet</div>
                        </div>
                    @endif
                    
                    @if($requestStatus === 'rejected' && !empty($request['rejection_reason']))
                        <div class="mt-6">
                            <h3 class="text-sm font-medium text-neutral-500 uppercase tracking-wider mb-2">Rejection Reason</h3>
                            <div class="bg-error-50 text-error-700 rounded-lg p-4 text-sm">
                                {{ $request['rejection_reason'] }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Request Modal -->
    <div x-show="showApproveModal" 
     x-cloak 
     @keydown.escape.window="showApproveModal = false"
     class="fixed inset-0 z-50 overflow-y-auto" 
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showApproveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showApproveModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" aria-hidden="true"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showApproveModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
            <form action="{{ route('admin.requests.approve', $request['id']) }}" method="POST">
                @csrf
                <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-neutral-800">Approve Request</h5>
                    <button type="button" @click="showApproveModal = false" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body p-6" x-data="{
                    paymentType: 'full_payment',
                    totalMilestones: 3,
                    milestonePhases: [
                        { name: 'Phase 1', percentage: 30, description: '' },
                        { name: 'Phase 2', percentage: 40, description: '' },
                        { name: 'Phase 3', percentage: 30, description: '' }
                    ],
                    downpaymentPercentage: 30,
                    budgetDisplay: '',
                    budgetValue: '',
                    
                    init() {
                        // Set default payment due date to +7 days from today
                        const today = new Date();
                        today.setDate(today.getDate() + 7);
                        const year = today.getFullYear();
                        const month = String(today.getMonth() + 1).padStart(2, '0');
                        const day = String(today.getDate()).padStart(2, '0');
                        document.getElementById('payment_due_date').value = `${year}-${month}-${day}`;
                    },
                    
                    formatBudget(event) {
                        // Remove all non-digit characters
                        let value = event.target.value.replace(/[^\d.]/g, '');
                        
                        // Store raw value
                        this.budgetValue = value;
                        
                        // Format with commas
                        if (value) {
                            const parts = value.split('.');
                            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                            this.budgetDisplay = parts.join('.');
                        } else {
                            this.budgetDisplay = '';
                        }
                        
                        // Update display
                        event.target.value = this.budgetDisplay;
                    },
                    
                    addPhase() {
                        this.milestonePhases.push({ 
                            name: 'Phase ' + (this.milestonePhases.length + 1), 
                            percentage: 0, 
                            description: '' 
                        });
                        this.totalMilestones = this.milestonePhases.length;
                    },
                    
                    removePhase(index) {
                        if (this.milestonePhases.length > 1) {
                            this.milestonePhases.splice(index, 1);
                            this.totalMilestones = this.milestonePhases.length;
                            // Renumber phases
                            this.milestonePhases.forEach((phase, idx) => {
                                if (!phase.name || phase.name.startsWith('Phase ')) {
                                    phase.name = 'Phase ' + (idx + 1);
                                }
                            });
                        }
                    },
                    
                    getTotalPercentage() {
                        return this.milestonePhases.reduce((sum, phase) => sum + parseFloat(phase.percentage || 0), 0);
                    },
                    
                    isPercentageValid() {
                        return this.getTotalPercentage() === 100;
                    }
                }" x-init="init()">
                    <!-- Budget and Payment Section -->
                    <div class="bg-primary-50 rounded-lg p-4 mb-5">
                        <h3 class="text-neutral-800 font-medium mb-3 flex items-center">
                            <i class="fas fa-dollar-sign mr-2 text-primary-600"></i>
                            Budget & Payment
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="approved_budget" class="block text-sm font-medium text-neutral-700 mb-1">Approved Budget *</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-500">₱</span>
                                    <input type="text" id="approved_budget_display" 
                                           @input="formatBudget($event)"
                                           class="w-full pl-8 pr-4 py-2 rounded-lg border border-neutral-300 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                           placeholder="0.00"
                                           required>
                                    <input type="hidden" id="approved_budget" name="approved_budget" :value="budgetValue" required>
                                </div>
                                <p class="text-xs text-neutral-500 mt-1">Amount will be formatted with commas (e.g., 100,000.00)</p>
                            </div>
                            
                            <div>
                                <label for="payment_due_date" class="block text-sm font-medium text-neutral-700 mb-1">Payment Due Date *</label>
                                <input type="date" id="payment_due_date" name="payment_due_date" required
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <p class="text-xs text-neutral-500 mt-1">Default: 7 days from today</p>
                            </div>
                        </div>
                        
                        <!-- Payment Type Selection -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Payment Type *</label>
                            <div class="space-y-3">
                                <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-primary-50 transition-colors" 
                                       :class="paymentType === 'full_payment' ? 'border-primary-500 bg-primary-50' : 'border-neutral-300'">
                                    <input type="radio" name="payment_type" value="full_payment" 
                                           x-model="paymentType" required
                                           class="mt-1 text-primary-600 focus:ring-primary-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-neutral-800">Full Payment</div>
                                        <div class="text-sm text-neutral-600">Client pays entire amount upfront. All tasks and documents are immediately accessible.</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-primary-50 transition-colors"
                                       :class="paymentType === 'milestone_payment' ? 'border-primary-500 bg-primary-50' : 'border-neutral-300'">
                                    <input type="radio" name="payment_type" value="milestone_payment" 
                                           x-model="paymentType"
                                           class="mt-1 text-primary-600 focus:ring-primary-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-neutral-800">Milestone Payment</div>
                                        <div class="text-sm text-neutral-600">Client pays per phase. Tasks and documents are locked per phase until paid.</div>
                                    </div>
                                </label>
                                
                                <label class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-primary-50 transition-colors"
                                       :class="paymentType === 'downpayment' ? 'border-primary-500 bg-primary-50' : 'border-neutral-300'">
                                    <input type="radio" name="payment_type" value="downpayment" 
                                           x-model="paymentType"
                                           class="mt-1 text-primary-600 focus:ring-primary-500">
                                    <div class="ml-3">
                                        <div class="font-medium text-neutral-800">Downpayment</div>
                                        <div class="text-sm text-neutral-600">Client pays initial downpayment to start. All content locked until remaining balance is paid.</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Milestone Payment Configuration -->
                        <div x-show="paymentType === 'milestone_payment'" 
                             x-transition
                             class="mt-4 p-4 bg-white rounded-lg border border-primary-200">
                            <div class="flex items-center justify-between mb-3">
                                <h4 class="font-medium text-neutral-800">Configure Phases</h4>
                                <button type="button" @click="addPhase()" 
                                        class="text-sm px-3 py-1 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-1"></i>Add Phase
                                </button>
                            </div>
                            
                            <input type="hidden" name="total_milestones" :value="milestonePhases.length">
                            
                            <div class="space-y-3 mb-3">
                                <template x-for="(phase, index) in milestonePhases" :key="index">
                                    <div class="p-3 bg-neutral-50 rounded-lg border border-neutral-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-neutral-700" x-text="'Phase ' + (index + 1)"></span>
                                            <button type="button" @click="removePhase(index)" 
                                                    x-show="milestonePhases.length > 1"
                                                    class="text-error-600 hover:text-error-700 text-sm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <input type="text" 
                                                       :name="'milestone_phases[' + index + '][name]'"
                                                       x-model="phase.name"
                                                       placeholder="Phase name"
                                                       class="w-full text-sm rounded border border-neutral-300 px-2 py-1.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                            </div>
                                            <div class="relative">
                                                <input type="number" 
                                                       :name="'milestone_phases[' + index + '][percentage]'"
                                                       x-model="phase.percentage"
                                                       min="0" max="100" step="0.01"
                                                       placeholder="Percentage"
                                                       class="w-full text-sm rounded border border-neutral-300 pl-2 pr-7 py-1.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                                <span class="absolute right-2 top-1/2 -translate-y-1/2 text-neutral-500 text-sm">%</span>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <input type="text" 
                                                   :name="'milestone_phases[' + index + '][description]'"
                                                   x-model="phase.description"
                                                   placeholder="Description (optional)"
                                                   class="w-full text-sm rounded border border-neutral-300 px-2 py-1.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                        </div>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 rounded-lg"
                                 :class="isPercentageValid() ? 'bg-success-50 border border-success-200' : 'bg-warning-50 border border-warning-200'">
                                <span class="text-sm font-medium" 
                                      :class="isPercentageValid() ? 'text-success-700' : 'text-warning-700'">
                                    Total Percentage:
                                </span>
                                <span class="text-lg font-bold"
                                      :class="isPercentageValid() ? 'text-success-700' : 'text-warning-700'"
                                      x-text="getTotalPercentage() + '%'"></span>
                            </div>
                            <p class="text-xs text-neutral-600 mt-2" x-show="!isPercentageValid()">
                                <i class="fas fa-exclamation-triangle text-warning-600 mr-1"></i>
                                Percentages must total exactly 100%
                            </p>
                        </div>
                        
                        <!-- Downpayment Configuration -->
                        <div x-show="paymentType === 'downpayment'" 
                             x-transition
                             class="mt-4 p-4 bg-white rounded-lg border border-primary-200">
                            <h4 class="font-medium text-neutral-800 mb-3">Downpayment Configuration</h4>
                            <div>
                                <label for="downpayment_percentage" class="block text-sm font-medium text-neutral-700 mb-1">
                                    Downpayment Percentage *
                                </label>
                                <div class="relative">
                                    <input type="number" id="downpayment_percentage" name="downpayment_percentage" 
                                           x-model="downpaymentPercentage"
                                           min="1" max="99" step="0.01"
                                           class="w-full rounded border border-neutral-300 pl-3 pr-10 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                           placeholder="30">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-500">%</span>
                                </div>
                                <p class="text-xs text-neutral-600 mt-1">
                                    Client will pay <span class="font-medium" x-text="downpaymentPercentage + '%'"></span> upfront, 
                                    then <span class="font-medium" x-text="(100 - downpaymentPercentage) + '%'"></span> as remaining balance.
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="payment_instructions" class="block text-sm font-medium text-neutral-700 mb-1">Payment Instructions (Optional)</label>
                            <textarea id="payment_instructions" name="payment_instructions" rows="2" 
                                      class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="e.g., Bank transfer details, payment link, etc."></textarea>
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <label for="admin_notes" class="block text-sm font-medium text-neutral-700 mb-1">Admin Notes (Optional)</label>
                        <textarea id="admin_notes" name="admin_notes" rows="3" 
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Add any notes about this approval..."></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" id="create_task" name="create_task" value="1" 
                                   class="rounded border-neutral-300 text-primary-600 focus:ring-primary-500">
                            <label for="create_task" class="ml-2 text-neutral-800 font-medium">
                                Create Task from this Request
                            </label>
                        </div>
                        <p class="text-neutral-500 text-sm">If checked, a task will be created automatically.</p>
                    </div>
                    
                    <div id="task_fields" class="bg-neutral-50 rounded-lg p-4 mb-2 hidden">
                        <h3 class="text-neutral-800 font-medium mb-3">Task Details</h3>
                        
                        <div class="mb-4">
                            <label for="task_title" class="block text-sm font-medium text-neutral-700 mb-1">Task Title *</label>
                            <input type="text" id="task_title" name="task_title" 
                                   class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                   placeholder="Enter task title">
                        </div>
                        
                        <div class="mb-4">
                            <label for="task_description" class="block text-sm font-medium text-neutral-700 mb-1">Description *</label>
                            <textarea id="task_description" name="task_description" rows="3" 
                                      class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                      placeholder="Enter task description"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="task_priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority *</label>
                                <select id="task_priority" name="task_priority" 
                                        class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                    <option value="urgent">Urgent</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="task_due_date" class="block text-sm font-medium text-neutral-700 mb-1">Due Date</label>
                                <input type="date" id="task_due_date" name="task_due_date" 
                                       class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="adiutor_id" class="block text-sm font-medium text-neutral-700 mb-1">Assign To (Optional)</label>
                            <select id="adiutor_id" name="adiutor_id" 
                                    class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="">Select an Adiutor</option>
                                @foreach(\App\Models\User::where('role', 'adiutor')->orderBy('fullName')->get() as $adiutor)
                                    <option value="{{ $adiutor->id }}">{{ $adiutor->fullName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" @click="showApproveModal = false" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-success-500 hover:bg-success-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>Approve Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Request Modal -->
<div x-show="showRejectModal" 
     x-cloak 
     @keydown.escape.window="showRejectModal = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showRejectModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" aria-hidden="true"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
            <form action="{{ route('admin.requests.reject', $request['id']) }}" method="POST">
                @csrf
                <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-neutral-800">Reject Request</h5>
                    <button type="button" @click="showRejectModal = false" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="mb-5">
                        <label for="rejection_reason" class="block text-sm font-medium text-neutral-700 mb-1">Rejection Reason *</label>
                        <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Please provide a reason for rejecting this request..."></textarea>
                    </div>
                    
                    <div class="mb-5">
                        <label for="reject_admin_notes" class="block text-sm font-medium text-neutral-700 mb-1">Additional Notes</label>
                        <textarea id="reject_admin_notes" name="admin_notes" rows="3" 
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2.5 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Add any additional notes..."></textarea>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" @click="showRejectModal = false" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-error-500 hover:bg-error-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-times mr-2"></i>Reject Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Task Modal -->
<div x-show="showCreateTaskModal"
     x-cloak 
     @keydown.escape.window="showCreateTaskModal = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div x-show="showCreateTaskModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="showCreateTaskModal = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40" aria-hidden="true"></div>
        
        <!-- Center modal -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div x-show="showCreateTaskModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full relative z-50">
            <form action="{{ route('admin.requests.approve', $request['id']) }}" method="POST">
                @csrf
                <input type="hidden" name="create_task" value="1">
                
                <div class="bg-neutral-50 border-b border-neutral-200 px-6 py-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-neutral-800">Create Task</h5>
                    <button type="button" @click="showCreateTaskModal = false" class="text-neutral-500 hover:text-neutral-700 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="mb-5">
                        <label for="new_task_title" class="block text-sm font-medium text-neutral-700 mb-1">Task Title *</label>
                        <input type="text" id="new_task_title" name="task_title" required
                               class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                               placeholder="Enter task title" 
                               value="{{ isset($request['title']) ? 'Task for: ' . $request['title'] : 'New task for request #' . $request['id'] }}">
                    </div>
                    
                    <div class="mb-5">
                        <label for="new_task_description" class="block text-sm font-medium text-neutral-700 mb-1">Description *</label>
                        <textarea id="new_task_description" name="task_description" rows="3" required
                                  class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                  placeholder="Enter task description">{{ 'Based on client request #' . $request['id'] }}</textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="new_task_priority" class="block text-sm font-medium text-neutral-700 mb-1">Priority *</label>
                            <select id="new_task_priority" name="task_priority" required
                                    class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                                <option value="low">Low</option>
                                <option value="medium" selected>Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="new_task_due_date" class="block text-sm font-medium text-neutral-700 mb-1">Due Date</label>
                            <input type="date" id="new_task_due_date" name="task_due_date" 
                                   class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="new_adiutor_id" class="block text-sm font-medium text-neutral-700 mb-1">Assign To (Optional)</label>
                        <select id="new_adiutor_id" name="adiutor_id" 
                                class="w-full rounded-lg border border-neutral-300 px-4 py-2 text-neutral-800 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                            <option value="">Select an Adiutor</option>
                            @foreach(\App\Models\User::where('role', 'adiutor')->orderBy('fullName')->get() as $adiutor)
                                <option value="{{ $adiutor->id }}">{{ $adiutor->fullName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="bg-neutral-50 border-t border-neutral-200 px-6 py-4 flex justify-end">
                    <button type="button" @click="showCreateTaskModal = false" class="px-4 py-2 border border-neutral-300 bg-white text-neutral-700 rounded-lg hover:bg-neutral-100 mr-3">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>
<!-- End of Alpine.js scope -->

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Toggle task fields based on checkbox
    $('#create_task').on('change', function() {
        if($(this).is(':checked')) {
            $('#task_fields').removeClass('hidden').slideDown();
            
            // Pre-populate fields with default values
            var requestId = '{{ $request["id"] ?? "" }}';
            var requestTitle = '{{ $request["project_name"] ?? $request["title"] ?? "" }}';
            
            if (requestTitle) {
                $('#task_title').val('Task for: ' + requestTitle);
            } else {
                $('#task_title').val('New task for request #' + requestId);
            }
            
            $('#task_description').val('Based on client request #' + requestId);
            
            // Don't set HTML5 required - we handle this on server side
        } else {
            $('#task_fields').slideUp().addClass('hidden');
            
            // Clear fields when unchecked
            $('#task_title, #task_description').val('');
        }
    });
    
    // No need for form validation since server handles it
});
</script>
@endsection
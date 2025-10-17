@extends('layouts.admin')

@section('title', 'Budget Change Request Details')
@section('page-title', 'Budget Change Request Details')

@section('content')
<div class="pt-20 pb-8">
    <div class="container mx-auto px-4">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('admin.budget-requests.index') }}" 
               class="text-primary hover:text-secondary inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to Budget Requests
            </a>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Main Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Request Overview -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Budget Change Request</h2>
                        @if($budgetRequest->status === 'pending')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending Review
                            </span>
                        @elseif($budgetRequest->status === 'approved')
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Approved
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Rejected
                            </span>
                        @endif
                    </div>

                    <!-- Budget Comparison -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Current Budget</p>
                            <p class="text-2xl font-bold text-gray-800">₱{{ number_format($budgetRequest->current_budget, 2) }}</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Requested Budget</p>
                            <p class="text-2xl font-bold text-primary">₱{{ number_format($budgetRequest->requested_budget, 2) }}</p>
                        </div>
                    </div>

                    <!-- Change Analysis -->
                    @php
                        $difference = $budgetRequest->requested_budget - $budgetRequest->current_budget;
                        $percentage = $budgetRequest->current_budget > 0 ? (($difference / $budgetRequest->current_budget) * 100) : 100;
                    @endphp
                    <div class="bg-{{ $difference > 0 ? 'red' : 'green' }}-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Budget Change</p>
                                <p class="text-xl font-bold text-{{ $difference > 0 ? 'red' : 'green' }}-700">
                                    {{ $difference > 0 ? '+' : '' }}₱{{ number_format(abs($difference), 2) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-1">Percentage</p>
                                <p class="text-xl font-bold text-{{ $difference > 0 ? 'red' : 'green' }}-700">
                                    {{ $difference > 0 ? '+' : '' }}{{ number_format($percentage, 1) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="border-t pt-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Request Reason</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">{{ $budgetRequest->reason }}</p>
                        </div>
                    </div>
                </div>

                <!-- Review Details (if reviewed) -->
                @if($budgetRequest->status !== 'pending')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Review Details</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Reviewed By</p>
                            <p class="text-gray-900 font-medium">{{ $budgetRequest->reviewer->fullName ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Reviewed At</p>
                            <p class="text-gray-900">{{ $budgetRequest->reviewed_at ? $budgetRequest->reviewed_at->format('F d, Y - h:i A') : 'N/A' }}</p>
                        </div>
                        
                        @if($budgetRequest->review_notes)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Review Notes</p>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">{{ $budgetRequest->review_notes }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Action Buttons (if pending) -->
                @if($budgetRequest->status === 'pending')
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Review Actions</h3>
                    
                    <form method="POST" action="{{ route('admin.budget-requests.approve', $budgetRequest->id) }}" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Approval Notes (Optional)</label>
                            <textarea name="review_notes" 
                                      rows="3" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                      placeholder="Add any notes about this approval..."></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700 transition-colors font-medium">
                            <i class="fas fa-check-circle mr-2"></i> Approve Budget Change
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.budget-requests.reject', $budgetRequest->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                            <textarea name="review_notes" 
                                      rows="3" 
                                      required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-primary focus:border-primary"
                                      placeholder="Explain why this request is being rejected..."></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full bg-red-600 text-white px-6 py-3 rounded-md hover:bg-red-700 transition-colors font-medium">
                            <i class="fas fa-times-circle mr-2"></i> Reject Budget Change
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Right Column - Task & Adiutor Info -->
            <div class="space-y-6">
                <!-- Task Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Task Information</h3>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600">Task Title</p>
                            <p class="text-gray-900 font-medium">{{ $budgetRequest->task->taskTitle ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Project</p>
                            <p class="text-gray-900">{{ $budgetRequest->task->project->title ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-600">Task Status</p>
                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($budgetRequest->task->status ?? 'unknown') }}
                            </span>
                        </div>
                        
                        @if($budgetRequest->task)
                        <div class="pt-3 border-t">
                            <a href="{{ route('admin.tasks.show', $budgetRequest->task->taskID) }}" 
                               class="text-primary hover:text-secondary text-sm font-medium">
                                View Full Task Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Adiutor Information -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Adiutor Information</h3>
                    
                    <div class="text-center mb-4">
                        <img src="{{ $budgetRequest->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($budgetRequest->adiutor->fullName) }}" 
                             alt="{{ $budgetRequest->adiutor->fullName }}"
                             class="w-20 h-20 rounded-full mx-auto mb-3">
                        <p class="font-semibold text-gray-900">{{ $budgetRequest->adiutor->fullName }}</p>
                        <p class="text-sm text-gray-600">{{ $budgetRequest->adiutor->email }}</p>
                    </div>
                    
                    <div class="space-y-2 text-sm">
                        @if($budgetRequest->adiutor->phoneNumber)
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-phone w-5"></i>
                            <span>{{ $budgetRequest->adiutor->phoneNumber }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-user-tag w-5"></i>
                            <span class="capitalize">{{ $budgetRequest->adiutor->role }}</span>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Timeline</h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-full mr-3">
                                <i class="fas fa-plus text-blue-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Request Created</p>
                                <p class="text-xs text-gray-600">{{ $budgetRequest->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($budgetRequest->reviewed_at)
                        <div class="flex items-start">
                            <div class="bg-{{ $budgetRequest->status === 'approved' ? 'green' : 'red' }}-100 p-2 rounded-full mr-3">
                                <i class="fas fa-{{ $budgetRequest->status === 'approved' ? 'check' : 'times' }} text-{{ $budgetRequest->status === 'approved' ? 'green' : 'red' }}-600 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($budgetRequest->status) }}</p>
                                <p class="text-xs text-gray-600">{{ $budgetRequest->reviewed_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Payout Details - ' . $payout->payout_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('admin.payouts.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Payouts
            </a>
        </div>
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Payout {{ $payout->payout_number }}</h1>
                <p class="text-sm text-gray-500 mt-1">Requested on {{ $payout->requested_at->format('F d, Y \a\t g:i A') }}</p>
            </div>
            <div class="mt-4 sm:mt-0">
                @if($payout->status === 'paid')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Paid
                    </span>
                @elseif($payout->status === 'approved')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Approved
                    </span>
                @elseif($payout->status === 'rejected')
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Rejected
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        Pending Review
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <ul class="text-sm text-red-700 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Action Buttons -->
            @if($payout->status === 'pending')
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                <div class="flex flex-wrap gap-3">
                    <button onclick="showApproveModal()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Approve & Mark as Paid
                    </button>
                    <button onclick="showRejectModal()" 
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Reject Request
                    </button>
                </div>
            </div>
            @endif

            <!-- Adiutor Information -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Adiutor Information</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <img src="{{ $payout->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($payout->adiutor->fullName) }}" 
                             alt="{{ $payout->adiutor->fullName }}" 
                             class="w-16 h-16 rounded-full border-2 border-gray-200">
                        <div class="flex-1">
                            <h4 class="text-lg font-semibold text-gray-900">{{ $payout->adiutor->fullName }}</h4>
                            <p class="text-sm text-gray-600">{{ $payout->adiutor->email }}</p>
                            @if($payout->adiutor->adiutorProfile)
                                <div class="mt-3 text-sm text-gray-600">
                                    <p><span class="font-medium">Standard Rate:</span> ₱{{ number_format($payout->adiutor->adiutorProfile->standard_hourly_rate ?? 0, 2) }}/hr</p>
                                    <p><span class="font-medium">Preferred Method:</span> {{ ucwords(str_replace('_', ' ', $payout->adiutor->adiutorProfile->preferred_payout_method ?? 'Not set')) }}</p>
                                </div>
                            @endif
                            <a href="{{ route('admin.payouts.adiutor-earnings', $payout->adiutor_id) }}" 
                               class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-700 text-sm font-medium mt-3">
                                View All Earnings
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout Items -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Time Entries Included</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $payout->items->count() }} entries</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Task / Project</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($payout->items as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($item->timeEntry)
                                        {{ $item->timeEntry->start_time->format('M d, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->description }}</div>
                                    @if($item->task)
                                        <div class="text-xs text-gray-500">{{ $item->task->project->title ?? 'N/A' }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ number_format($item->hours ?? 0, 2) }}h
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    ₱{{ number_format($item->rate ?? 0, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    ₱{{ number_format($item->amount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-sm font-semibold text-gray-900">Total</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    {{ number_format($payout->items->sum('hours'), 2) }}h
                                </td>
                                <td class="px-6 py-4"></td>
                                <td class="px-6 py-4 text-lg font-bold text-primary-600">
                                    ₱{{ number_format($payout->amount, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Adiutor Notes -->
            @if($payout->adiutor_notes)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Adiutor's Notes</h4>
                        <p class="text-sm text-blue-800">{{ $payout->adiutor_notes }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Summary -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Summary</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Amount</p>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($payout->amount, 2) }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Period</p>
                        <p class="text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($payout->period_start)->format('M d, Y') }} -<br>
                            {{ \Carbon\Carbon::parse($payout->period_end)->format('M d, Y') }}
                        </p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Hours</p>
                        <p class="text-sm text-gray-900">{{ number_format($payout->items->sum('hours'), 2) }} hours</p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Payment Method</p>
                        <p class="text-sm text-gray-900">{{ ucwords(str_replace('_', ' ', $payout->payout_method ?? 'Not specified')) }}</p>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Currency</p>
                        <p class="text-sm text-gray-900">{{ $payout->currency ?? 'PHP' }}</p>
                    </div>

                    @if($payout->reference_number)
                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Reference Number</p>
                        <p class="text-sm text-gray-900 font-mono">{{ $payout->reference_number }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Requested -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Requested</p>
                                <p class="text-xs text-gray-500">{{ $payout->requested_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>

                        @if($payout->reviewed_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full {{ $payout->status === 'rejected' ? 'bg-red-100' : 'bg-green-100' }} flex items-center justify-center">
                                    @if($payout->status === 'rejected')
                                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">{{ $payout->status === 'rejected' ? 'Rejected' : 'Approved' }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($payout->reviewed_at)->format('M d, Y g:i A') }}</p>
                                @if($payout->processedBy)
                                    <p class="text-xs text-gray-500">by {{ $payout->processedBy->fullName }}</p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if($payout->paid_at)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900">Paid</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($payout->paid_at)->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            @if($payout->payout_details)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-2 text-sm">
                        @foreach(json_decode($payout->payout_details, true) ?? [] as $key => $value)
                            <div>
                                <span class="text-gray-500">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                <span class="text-gray-900 font-medium ml-2">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Admin Notes -->
            @if($payout->admin_notes)
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Admin Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-700">{{ $payout->admin_notes }}</p>
                    @if($payout->processedBy)
                        <p class="text-xs text-gray-500 mt-2">— {{ $payout->processedBy->fullName }}</p>
                    @endif
                </div>
            </div>
            @endif

            <!-- Rejection Reason -->
            @if($payout->status === 'rejected' && $payout->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <h4 class="text-sm font-medium text-red-900 mb-2">Rejection Reason</h4>
                <p class="text-sm text-red-800">{{ $payout->rejection_reason }}</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <form action="{{ route('admin.payouts.complete', $payout->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Approve & Process Payout</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Reference Number <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="reference_number" name="reference_number" required
                           placeholder="e.g., TXN-2024-001234"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div>
                    <label for="proof_of_payment" class="block text-sm font-medium text-gray-700 mb-2">
                        Proof of Payment (Optional)
                    </label>
                    <input type="file" id="proof_of_payment" name="proof_of_payment" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Accepted: JPG, PNG, PDF (Max 5MB)</p>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Admin Notes (Optional)
                    </label>
                    <textarea id="notes" name="notes" rows="3"
                              placeholder="Add any notes about this payout..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-800">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        This will mark all time entries as paid and notify the adiutor.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="hideApproveModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    Approve & Mark as Paid
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl max-w-2xl w-full">
        <form action="{{ route('admin.payouts.cancel', $payout->id) }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Reject Payout Request</h3>
            </div>
            <div class="p-6">
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                        Reason for Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea id="reason" name="reason" rows="4" required
                              placeholder="Explain why this payout request is being rejected..."
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"></textarea>
                </div>

                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
                    <p class="text-sm text-red-800">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        This will cancel the payout request and notify the adiutor.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button type="button" onclick="hideRejectModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">
                    Reject Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal() {
    document.getElementById('approveModal').classList.remove('hidden');
}

function hideApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function showRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}

function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modals on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideApproveModal();
        hideRejectModal();
    }
});

// Close modals on backdrop click
document.getElementById('approveModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        hideApproveModal();
    }
});

document.getElementById('rejectModal')?.addEventListener('click', function(event) {
    if (event.target === this) {
        hideRejectModal();
    }
});
</script>
@endsection

@extends('client.layout')

@section('title', 'Complete Payment - ' . $request->project_name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header with Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.requests.show', $request->id) }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Request Details
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full shadow-lg mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-neutral-900 mb-2">Complete Your Payment</h1>
        <p class="text-lg text-neutral-600">{{ $request->project_name }}</p>
        @php
            $statusLabel = match($request->status) {
                'pending' => 'Pending Review',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                'pending_payment' => 'Pending Payment',
                'payment_submitted' => 'Payment Submitted',
                'paid' => 'Payment Confirmed',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                default => ucfirst(str_replace('_', ' ', $request->status))
            };
            $statusColor = match($request->status) {
                'pending' => 'warning',
                'approved' => 'success',
                'rejected' => 'error',
                'pending_payment' => 'info',
                'payment_submitted' => 'warning',
                'paid' => 'success',
                'in_progress' => 'primary',
                'completed' => 'success',
                default => 'neutral'
            };
        @endphp
        <span class="inline-block badge badge-{{ $statusColor }} mt-2">{{ $statusLabel }}</span>
    </div>

    @if($request->status === 'pending_payment' || $request->status === 'approved')
        <!-- Payment Steps Guide -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-6 mb-8">
            <h2 class="text-xl font-semibold text-neutral-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                How to Complete Your Payment
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-100 text-primary-600 font-bold">1</div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-neutral-900 mb-1">Scan QR Code</h3>
                        <p class="text-sm text-neutral-600">Use your GCash, Maya, or banking app to scan the QR code below</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-100 text-primary-600 font-bold">2</div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-neutral-900 mb-1">Complete Payment</h3>
                        <p class="text-sm text-neutral-600">Send the exact amount displayed to our account</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-primary-100 text-primary-600 font-bold">3</div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-semibold text-neutral-900 mb-1">Upload Receipt</h3>
                        <p class="text-sm text-neutral-600">Take a screenshot and upload your payment proof below</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information Card -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Amount & Details -->
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl shadow-lg p-8 border-2 border-primary-200">
                <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Payment Amount
                </h3>
                
                <div class="text-center bg-white rounded-xl p-8 shadow-md mb-6">
                    <p class="text-sm font-medium text-neutral-600 mb-2">Total Amount to Pay</p>
                    <p class="text-5xl font-bold text-primary-600 mb-6">${{ number_format($request->approved_budget, 2) }}</p>
                    
                    <div class="space-y-3 text-left">
                        <div class="flex justify-between items-center py-3 border-b border-neutral-200">
                            <span class="text-sm text-neutral-600 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Payment Method
                            </span>
                            <span class="font-medium text-neutral-900">{{ ucfirst($request->payment_method) }}</span>
                        </div>
                        @if($request->payment_due_date)
                            <div class="flex justify-between items-center py-3 border-b border-neutral-200">
                                <span class="text-sm text-neutral-600 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Due Date
                                </span>
                                <span class="font-medium {{ $request->payment_due_date->isPast() ? 'text-error-600' : 'text-neutral-900' }}">
                                    {{ $request->payment_due_date->format('M d, Y') }}
                                    @if($request->payment_due_date->isPast())
                                        <span class="text-xs">(Overdue)</span>
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($request->payment_due_date && $request->payment_due_date->isPast())
                    <div class="bg-error-50 border-l-4 border-error-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-error-600 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-semibold text-error-900">Payment Overdue</p>
                                <p class="text-sm text-error-800 mt-1">This payment is past due. Please complete it as soon as possible or contact us if you need assistance.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- QR Code & Instructions -->
            <div class="bg-white rounded-xl shadow-lg p-8 border border-neutral-200">
                <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    Scan to Pay
                </h3>
                
                <div class="bg-gradient-to-br from-neutral-50 to-neutral-100 rounded-xl p-6 text-center mb-6">
                    <div class="bg-white inline-block p-6 rounded-xl shadow-lg mb-4">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=GCash:09614736286&margin=10" 
                             alt="Payment QR Code" 
                             class="w-56 h-56 mx-auto">
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-medium text-neutral-600 uppercase tracking-wide">Scan with your preferred app</p>
                        <div class="flex items-center justify-center space-x-2">
                            <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-neutral-700 shadow-sm">GCash</span>
                            <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-neutral-700 shadow-sm">Maya</span>
                            <span class="px-3 py-1 bg-white rounded-full text-xs font-medium text-neutral-700 shadow-sm">InstaPay</span>
                        </div>
                    </div>
                </div>

                <div class="bg-primary-50 rounded-lg p-4 border border-primary-200">
                    <p class="text-xs font-semibold text-primary-900 mb-2 uppercase tracking-wide">Account Details</p>
                    <div class="space-y-1">
                        <p class="text-sm"><span class="font-semibold text-primary-900">GCash:</span> <span class="font-mono text-neutral-900">09614736286</span></p>
                        <p class="text-xs text-primary-800">Account Name: M.A.S.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Instructions -->
        <div class="bg-white rounded-xl shadow-sm border border-neutral-200 p-8 mb-8">
            <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Payment Instructions
            </h3>
            
            <div class="prose prose-sm max-w-none">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-neutral-900 mb-3 flex items-center">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-success-100 text-success-600 text-xs font-bold mr-2">✓</span>
                            Accepted Payment Methods
                        </h4>
                        <ul class="space-y-2 text-sm text-neutral-700">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>GCash</strong> - Scan QR code or send to 09614736286</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Maya</strong> - Contact us for account details</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>GoTyme</strong> - Contact us for account details</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Bank Transfer</strong> - InstaPay & PESONet available</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-semibold text-neutral-900 mb-3 flex items-center">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-warning-100 text-warning-600 text-xs font-bold mr-2">!</span>
                            Important Reminders
                        </h4>
                        <ul class="space-y-2 text-sm text-neutral-700">
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>Send the <strong>exact amount</strong> shown above</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>Take a <strong>clear screenshot</strong> of your receipt</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>Upload your payment proof below to confirm</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span>We'll verify and confirm within <strong>24 hours</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>

                @if($request->payment_instructions)
                    <div class="mt-6 p-5 bg-neutral-50 rounded-lg border border-neutral-200">
                        <p class="text-sm font-semibold text-neutral-900 mb-2 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            Additional Instructions from Admin
                        </p>
                        <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $request->payment_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upload Payment Proof -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8 border border-neutral-200">
            <h3 class="text-2xl font-bold text-neutral-900 mb-2 flex items-center">
                <svg class="w-7 h-7 mr-3 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Upload Your Payment Receipt
            </h3>
            <p class="text-neutral-600 mb-8">After completing your payment, upload a screenshot or photo of your receipt below for verification.</p>
            
            <form action="{{ route('client.requests.submit-payment', $request->id) }}" method="POST" enctype="multipart/form-data" id="paymentForm">
                @csrf
                
                <div class="mb-8">
                    <label for="payment_proof" class="block text-base font-semibold text-neutral-900 mb-3">
                        Payment Receipt / Screenshot *
                    </label>
                    <div class="border-3 border-dashed border-neutral-300 rounded-xl p-8 text-center hover:border-primary-400 hover:bg-primary-50/50 transition-all cursor-pointer" id="dropZone">
                        <input type="file" 
                               id="payment_proof" 
                               name="payment_proof" 
                               required
                               accept="image/*,.pdf"
                               class="hidden"
                               onchange="updateFileName(this)">
                        <label for="payment_proof" class="cursor-pointer">
                            <div id="uploadPlaceholder">
                                <svg class="w-16 h-16 mx-auto text-neutral-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <p class="text-base text-neutral-700 mb-2 font-medium">
                                    <span class="text-primary-600 font-semibold">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-sm text-neutral-500">PNG, JPG, or PDF (Maximum 5MB)</p>
                            </div>
                            <div id="filePreview" class="hidden">
                                <svg class="w-16 h-16 mx-auto text-success-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p id="file-name" class="text-base text-neutral-900 font-semibold"></p>
                                <button type="button" onclick="clearFile()" class="mt-3 text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    Change file
                                </button>
                            </div>
                        </label>
                    </div>
                    <p class="text-xs text-neutral-500 mt-2 flex items-start">
                        <svg class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Make sure your receipt shows the transaction amount, date, and reference number
                    </p>
                </div>

                <div class="mb-8">
                    <label for="payment_notes" class="block text-base font-semibold text-neutral-900 mb-3">
                        Additional Notes <span class="text-neutral-500 font-normal text-sm">(Optional)</span>
                    </label>
                    <textarea id="payment_notes" 
                              name="payment_notes" 
                              rows="4"
                              class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                              placeholder="Any additional information about your payment (e.g., sender name, transaction reference, etc.)"></textarea>
                </div>

                <div class="bg-neutral-50 rounded-xl p-6 mb-6 border border-neutral-200">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <input type="checkbox" 
                                   id="confirm_payment" 
                                   name="confirm_payment" 
                                   required
                                   class="mt-1 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 w-5 h-5 flex-shrink-0">
                            <label for="confirm_payment" class="ml-3 text-sm text-neutral-700">
                                I confirm that I have completed the payment of <strong class="text-neutral-900">${{ number_format($request->approved_budget, 2) }}</strong> and the information provided is accurate and truthful.
                            </label>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" 
                                   id="agree_terms" 
                                   name="agree_terms" 
                                   required
                                   class="mt-1 rounded border-neutral-300 text-primary-600 focus:ring-primary-500 w-5 h-5 flex-shrink-0">
                            <label for="agree_terms" class="ml-3 text-sm text-neutral-700">
                                I have read and agree to the 
                                <a href="{{ route('terms-and-conditions') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium underline">Terms and Conditions</a> 
                                and 
                                <a href="{{ route('privacy-policy') }}" target="_blank" class="text-primary-600 hover:text-primary-700 font-medium underline">Privacy Policy</a>.
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white py-4 px-6 rounded-xl text-lg font-semibold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                    <svg class="w-6 h-6 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Submit Payment Confirmation
                </button>

                <p class="text-center text-sm text-neutral-500 mt-4">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Your payment information is secure and will be verified within 24 hours
                </p>
            </form>
        </div>
    @endif

    @if($request->status === 'paid' || $request->status === 'payment_submitted')
        <!-- Payment Confirmed Card -->
        <div class="card mb-8 border-l-4 border-success-500">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7 12a5 5 0 1110 0 5 5 0 01-10 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-lg font-semibold text-success-900 mb-2">Payment Confirmed</h3>
                    <p class="text-success-700 mb-4">Thank you! Your payment has been confirmed and your project is now in progress.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Amount Paid:</span>
                                    <span class="font-semibold text-lg text-success-600">${{ number_format($request->approved_budget, 2) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Payment Reference:</span>
                                    <span class="font-medium">{{ $request->payment_reference }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-neutral-600">Confirmed Date:</span>
                                    <span class="font-medium">{{ $request->payment_confirmed_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


@push('scripts')
<script>
function updateFileName(input) {
    const fileNameDisplay = document.getElementById('file-name');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const filePreview = document.getElementById('filePreview');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const fileName = file.name;
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
        
        fileNameDisplay.textContent = `${fileName} (${fileSize} MB)`;
        uploadPlaceholder.classList.add('hidden');
        filePreview.classList.remove('hidden');
    } else {
        fileNameDisplay.textContent = '';
        uploadPlaceholder.classList.remove('hidden');
        filePreview.classList.add('hidden');
    }
}

function clearFile() {
    const input = document.getElementById('payment_proof');
    const uploadPlaceholder = document.getElementById('uploadPlaceholder');
    const filePreview = document.getElementById('filePreview');
    
    input.value = '';
    uploadPlaceholder.classList.remove('hidden');
    filePreview.classList.add('hidden');
}

// Drag and drop functionality
const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('payment_proof');

['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.add('border-primary-500', 'bg-primary-50');
    }, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropZone.addEventListener(eventName, () => {
        dropZone.classList.remove('border-primary-500', 'bg-primary-50');
    }, false);
});

dropZone.addEventListener('drop', (e) => {
    const dt = e.dataTransfer;
    const files = dt.files;
    
    if (files.length > 0) {
        fileInput.files = files;
        updateFileName(fileInput);
    }
}, false);
</script>
@endpush
@extends('client.layouts.app')

@section('title', 'Complete Payment - ' . $request->project_name)

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Service Requests', 'url' => route('client.requests'), 'icon' => 'file-text'],
        ['label' => $request->project_name, 'url' => route('client.requests.show', $request->id)],
        ['label' => 'Payment']
    ]" />

    <!-- Header with Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.requests.show', $request->id) }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 transition-colors">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" />
            Back to Request Details
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-600 rounded-full shadow-lg mb-4">
            <x-lucide-wallet class="w-8 h-8 text-white" />
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
                <x-lucide-clipboard-list class="w-6 h-6 mr-2 text-primary-600" />
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
            <div class="bg-primary-50 rounded-xl shadow-sm p-8 border border-primary-200">
                <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                    <x-lucide-circle-dollar-sign class="w-6 h-6 mr-2 text-primary-600" />
                    Payment Amount
                </h3>
                
                <div class="text-center bg-white rounded-xl p-8 shadow-md mb-6">
                    <p class="text-sm font-medium text-neutral-600 mb-2">Total Amount to Pay</p>
                    <p class="text-5xl font-bold text-primary-600 mb-6">${{ number_format($request->approved_budget, 2) }}</p>
                    
                    <div class="space-y-3 text-left">
                        <div class="flex justify-between items-center py-3 border-b border-neutral-200">
                            <span class="text-sm text-neutral-600 flex items-center">
                                <x-lucide-credit-card class="w-4 h-4 mr-2" />
                                Payment Method
                            </span>
                            <span class="font-medium text-neutral-900">{{ ucfirst($request->payment_method) }}</span>
                        </div>
                        @if($request->payment_due_date)
                            <div class="flex justify-between items-center py-3 border-b border-neutral-200">
                                <span class="text-sm text-neutral-600 flex items-center">
                                    <x-lucide-calendar class="w-4 h-4 mr-2" />
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
                            <x-lucide-alert-triangle class="w-5 h-5 text-error-600 mt-0.5 mr-3 flex-shrink-0" />
                            <div>
                                <p class="text-sm font-semibold text-error-900">Payment Overdue</p>
                                <p class="text-sm text-error-800 mt-1">This payment is past due. Please complete it as soon as possible or contact us if you need assistance.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- QR Code & Instructions -->
            <div class="bg-white rounded-xl shadow-sm p-8 border border-neutral-200">
                <h3 class="text-xl font-bold text-neutral-900 mb-6 flex items-center">
                    <x-lucide-qr-code class="w-6 h-6 mr-2 text-success-600" />
                    Scan to Pay
                </h3>
                
                <div class="bg-neutral-50 rounded-xl p-6 text-center mb-6 border border-neutral-200">
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
                <x-lucide-info class="w-6 h-6 mr-2 text-primary-600" />
                Payment Instructions
            </h3>
            
            <div class="prose prose-sm max-w-none">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-neutral-900 mb-3 flex items-center">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-success-100 text-success-600 text-xs font-bold mr-2">
                                <x-lucide-check class="w-3 h-3" />
                            </span>
                            Accepted Payment Methods
                        </h4>
                        <ul class="space-y-2 text-sm text-neutral-700">
                            <li class="flex items-start">
                                <x-lucide-check-circle class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span><strong>GCash</strong> - Scan QR code or send to 09614736286</span>
                            </li>
                            <li class="flex items-start">
                                <x-lucide-check-circle class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span><strong>Maya</strong> - Contact us for account details</span>
                            </li>
                            <li class="flex items-start">
                                <x-lucide-check-circle class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span><strong>GoTyme</strong> - Contact us for account details</span>
                            </li>
                            <li class="flex items-start">
                                <x-lucide-check-circle class="w-4 h-4 text-success-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span><strong>Bank Transfer</strong> - InstaPay & PESONet available</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-semibold text-neutral-900 mb-3 flex items-center">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-warning-100 text-warning-600 text-xs font-bold mr-2">
                                <x-lucide-alert-circle class="w-3 h-3" />
                            </span>
                            Important Reminders
                        </h4>
                        <ul class="space-y-2 text-sm text-neutral-700">
                            <li class="flex items-start">
                                <x-lucide-info class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span>Send the <strong>exact amount</strong> shown above</span>
                            </li>
                            <li class="flex items-start">
                                <x-lucide-info class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span>Take a <strong>clear screenshot</strong> of your receipt</span>
                            </li>
                            <li class="flex items-start">
                                <x-lucide-info class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span>Upload your payment proof below to confirm</span>
                            </li>
                            <li class="flex items-start">
                                <x-lucide-info class="w-4 h-4 text-warning-500 mt-0.5 mr-2 flex-shrink-0" />
                                <span>We'll verify and confirm within <strong>24 hours</strong></span>
                            </li>
                        </ul>
                    </div>
                </div>

                @if($request->payment_instructions)
                    <div class="mt-6 p-5 bg-neutral-50 rounded-lg border border-neutral-200">
                        <p class="text-sm font-semibold text-neutral-900 mb-2 flex items-center">
                            <x-lucide-message-square class="w-4 h-4 mr-2" />
                            Additional Instructions from Admin
                        </p>
                        <p class="text-sm text-neutral-700 whitespace-pre-line">{{ $request->payment_instructions }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Upload Payment Proof -->
        <div class="bg-white rounded-xl shadow-sm p-8 mb-8 border border-neutral-200">
            <h3 class="text-2xl font-bold text-neutral-900 mb-2 flex items-center">
                <x-lucide-upload class="w-7 h-7 mr-3 text-success-600" />
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
                                <x-lucide-cloud-upload class="w-16 h-16 mx-auto text-neutral-400 mb-4" />
                                <p class="text-base text-neutral-700 mb-2 font-medium">
                                    <span class="text-primary-600 font-semibold">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-sm text-neutral-500">PNG, JPG, or PDF (Maximum 5MB)</p>
                            </div>
                            <div id="filePreview" class="hidden">
                                <x-lucide-check-circle class="w-16 h-16 mx-auto text-success-500 mb-4" />
                                <p id="file-name" class="text-base text-neutral-900 font-semibold"></p>
                                <button type="button" onclick="clearFile()" class="mt-3 text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    Change file
                                </button>
                            </div>
                        </label>
                    </div>
                    <p class="text-xs text-neutral-500 mt-2 flex items-start">
                        <x-lucide-info class="w-4 h-4 mr-1 mt-0.5 flex-shrink-0" />
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

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white py-4 px-6 rounded-xl text-lg font-semibold shadow-sm hover:shadow-md transition-all">
                    <x-lucide-check-circle class="w-6 h-6 inline mr-2" />
                    Submit Payment Confirmation
                </button>

                <p class="text-center text-sm text-neutral-500 mt-4">
                    <x-lucide-lock class="w-4 h-4 inline mr-1" />
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
                        <x-lucide-check-circle class="w-6 h-6 text-success-600" />
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
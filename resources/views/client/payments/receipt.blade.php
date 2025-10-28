<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->payment_reference }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
        .receipt-pattern {
            background-image: radial-gradient(circle at 25px 25px, lightblue 2%, transparent 3%), radial-gradient(circle at 75px 75px, lightblue 1%, transparent 3%);
            background-size: 100px 100px;
        }
    </style>
</head>
<body class="bg-neutral-50">
    <div class="max-w-4xl mx-auto p-6">
        <!-- Print Actions -->
        <div class="no-print mb-6 flex justify-between items-center">
            <a href="{{ route('client.payments.show', $payment->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-neutral-600 hover:text-neutral-800 hover:bg-neutral-100 rounded-lg transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Payment Details
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-accent-600 hover:from-primary-700 hover:to-accent-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H9.5a2 2 0 01-2-2V5a2 2 0 00-2-2H3a2 2 0 00-2 2v4a2 2 0 002 2h2.5a2 2 0 012 2v4a2 2 0 002 2H17z"></path>
                </svg>
                Print Receipt
            </button>
        </div>

        <!-- Receipt -->
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden border">
            <!-- Header -->
            <div class="bg-gradient-to-r from-primary-600 to-accent-600 p-8 text-white receipt-pattern">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">TREIS ADIUTOR</h1>
                        <p class="text-primary-100 text-lg">Digital Innovation Solutions</p>
                        <div class="mt-4 space-y-1 text-sm text-primary-100">
                            <p>📧 contact@treisadiutor.com</p>
                            <p>🌐 www.treisadiutor.com</p>
                            <p>📱 +63 (XXX) XXX-XXXX</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4">
                            <h2 class="text-xl font-bold mb-2">PAYMENT RECEIPT</h2>
                            <p class="text-sm">Receipt #{{ $payment->payment_reference }}</p>
                            <p class="text-sm">{{ $payment->confirmed_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Receipt Body -->
            <div class="p-8">
                <!-- Client Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4 border-b border-neutral-200 pb-2">Bill To:</h3>
                        <div class="space-y-2 text-neutral-700">
                            <p class="font-medium text-neutral-900">{{ $payment->serviceRequest->client->fullName ?? 'N/A' }}</p>
                            <p>{{ $payment->serviceRequest->client->email ?? 'N/A' }}</p>
                            @if($payment->serviceRequest->client->phone ?? false)
                                <p>{{ $payment->serviceRequest->client->phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4 border-b border-neutral-200 pb-2">Payment Details:</h3>
                        <div class="space-y-2 text-neutral-700">
                            <div class="flex justify-between">
                                <span>Payment Method:</span>
                                <span class="font-medium">{{ $payment->payment_method ?? 'Online Payment' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Transaction Date:</span>
                                <span class="font-medium">{{ $payment->created_at->format('M d, Y g:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Confirmed Date:</span>
                                <span class="font-medium">{{ $payment->confirmed_at->format('M d, Y g:i A') }}</span>
                            </div>
                            @if($payment->confirmedBy)
                                <div class="flex justify-between">
                                    <span>Confirmed By:</span>
                                    <span class="font-medium">{{ $payment->confirmedBy->fullName }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Project Information -->
                @if($payment->serviceRequest)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4 border-b border-neutral-200 pb-2">Project Information:</h3>
                        <div class="bg-gradient-to-r from-primary-50/50 to-accent-50/30 rounded-xl p-6 border border-primary-100/50">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-neutral-600 mb-1">Project Name</p>
                                    <p class="font-semibold text-neutral-900">{{ $payment->serviceRequest->project_name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-neutral-600 mb-1">Project ID</p>
                                    <p class="font-semibold text-neutral-900">#{{ $payment->service_request_id }}</p>
                                </div>
                                @if($payment->serviceRequest->description)
                                    <div class="md:col-span-2">
                                        <p class="text-sm text-neutral-600 mb-1">Description</p>
                                        <p class="text-neutral-700">{{ $payment->serviceRequest->description }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Payment Summary -->
                <div class="bg-neutral-50 rounded-xl p-6 mb-8">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4">Payment Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between py-2 border-b border-neutral-200">
                            <span class="text-neutral-700">Payment Reference</span>
                            <span class="font-medium text-neutral-900">{{ $payment->payment_reference }}</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-neutral-200">
                            <span class="text-neutral-700">Project Fee</span>
                            <span class="font-medium text-neutral-900">₱{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between py-3 pt-4 border-t-2 border-neutral-300">
                            <span class="text-lg font-semibold text-neutral-900">Total Amount Paid</span>
                            <span class="text-2xl font-bold text-primary-600">₱{{ number_format($payment->amount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center gap-3 px-6 py-3 bg-success-100 text-success-800 rounded-full border border-success-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-semibold">PAYMENT CONFIRMED</span>
                    </div>
                </div>

                <!-- Additional Notes -->
                @if($payment->notes)
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-neutral-900 mb-4 border-b border-neutral-200 pb-2">Additional Notes:</h3>
                        <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                            <p class="text-neutral-700">{{ $payment->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Terms & Conditions -->
                <div class="border-t border-neutral-200 pt-6">
                    <h3 class="text-sm font-semibold text-neutral-900 mb-3">Terms & Conditions:</h3>
                    <div class="text-xs text-neutral-600 space-y-1">
                        <p>• This receipt serves as proof of payment for the specified project services.</p>
                        <p>• All payments are non-refundable unless otherwise specified in the service agreement.</p>
                        <p>• For any payment-related inquiries, please contact our support team.</p>
                        <p>• This is a computer-generated receipt and is valid without signature.</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-neutral-200 text-center">
                    <p class="text-sm text-neutral-600">Thank you for choosing TREIS ADIUTOR!</p>
                    <p class="text-xs text-neutral-500 mt-2">Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
                </div>
            </div>

            <!-- QR Code Section (Optional) -->
            <div class="bg-neutral-50 p-6 text-center border-t">
                <p class="text-xs text-neutral-500 mb-2">Scan QR code to verify this receipt online</p>
                <div class="inline-block bg-white p-4 rounded-lg border">
                    <!-- You can implement QR code generation here -->
                    <div class="w-16 h-16 bg-neutral-200 rounded flex items-center justify-center">
                        <svg class="w-8 h-8 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-focus print dialog when accessing receipt directly
        if (document.referrer.includes('/payments/')) {
            // Only auto-print if coming from payment details
        } else {
            // Auto-print when accessing receipt directly
            window.onload = function() {
                setTimeout(function() {
                    window.print();
                }, 1000);
            };
        }
    </script>
</body>
</html>
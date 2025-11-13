@extends('adiutor.layouts.app')

@section('title', 'Request Payout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('adiutor.earnings.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Earnings
            </a>
        </div>
        <h1 class="text-2xl font-semibold text-gray-900">Request Payout</h1>
        <p class="text-sm text-gray-500 mt-1">Submit a request to receive your approved earnings</p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Available Earnings Summary -->
    <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-6 md:mb-0">
                <h2 class="text-lg font-medium opacity-90 mb-2">Available for Payout</h2>
                <p class="text-4xl font-bold">₱{{ number_format($totalUnpaid, 2) }}</p>
                <p class="text-sm opacity-75 mt-2">{{ number_format($totalHours, 1) }} hours tracked</p>
            </div>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ $unpaidEarnings->count() }} approved time entries</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Minimum: ₱{{ number_format($adiutor->adiutorProfile->minimum_payout_amount ?? 500, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($totalUnpaid < ($adiutor->adiutorProfile->minimum_payout_amount ?? 500))
        <!-- Insufficient Amount Warning -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
            <div class="flex">
                <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-yellow-800 mb-2">Insufficient Amount</h3>
                    <p class="text-sm text-yellow-700">
                        Your available earnings (₱{{ number_format($totalUnpaid, 2) }}) are below the minimum payout amount 
                        (₱{{ number_format($adiutor->adiutorProfile->minimum_payout_amount ?? 500, 2) }}). 
                        Continue tracking time to reach the minimum threshold.
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Payout Request Form -->
        <form action="{{ route('adiutor.earnings.request') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Period Selection -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Payout Period</h3>
                    <p class="text-sm text-gray-500 mt-1">Select the time period for this payout request</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="period_start" class="block text-sm font-medium text-gray-700 mb-2">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="period_start" 
                                   name="period_start" 
                                   value="{{ old('period_start', $suggestedStart->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="period_end" class="block text-sm font-medium text-gray-700 mb-2">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   id="period_end" 
                                   name="period_end" 
                                   value="{{ old('period_end', $suggestedEnd->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Only approved and unpaid time entries within this period will be included
                    </p>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Payment Method</h3>
                    <p class="text-sm text-gray-500 mt-1">Confirm or change your preferred payment method</p>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="payout_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Preferred Method
                        </label>
                        <select id="payout_method" 
                                name="payout_method"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Use default ({{ ucwords(str_replace('_', ' ', $adiutor->adiutorProfile->preferred_payout_method ?? 'Not set')) }})</option>
                            <option value="bank_transfer" {{ old('payout_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="gcash" {{ old('payout_method') == 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="paymaya" {{ old('payout_method') == 'paymaya' ? 'selected' : '' }}>PayMaya</option>
                            <option value="paypal" {{ old('payout_method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                            <option value="cash" {{ old('payout_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="check" {{ old('payout_method') == 'check' ? 'selected' : '' }}>Check</option>
                            <option value="other" {{ old('payout_method') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    @if($adiutor->adiutorProfile->payout_details)
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Saved Payment Details:</p>
                            <div class="text-sm text-gray-600 space-y-1">
                                @foreach(json_decode($adiutor->adiutorProfile->payout_details, true) ?? [] as $key => $value)
                                    <p><span class="font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}:</span> {{ $value }}</p>
                                @endforeach
                            </div>
                            <p class="text-xs text-gray-500 mt-3">
                                <a href="{{ route('adiutor.profile.earnings') }}" class="text-primary-600 hover:text-primary-700">
                                    Update payment details
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800">
                                ⚠️ You haven't set up your payment details yet. 
                                <a href="{{ route('adiutor.profile.earnings') }}" class="font-medium underline hover:text-yellow-900">
                                    Set up now
                                </a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Additional Notes</h3>
                    <p class="text-sm text-gray-500 mt-1">Optional notes for the admin</p>
                </div>
                <div class="p-6">
                    <textarea id="notes" 
                              name="notes" 
                              rows="4"
                              placeholder="Add any special instructions or notes for this payout request..."
                              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('notes') }}</textarea>
                    <p class="text-xs text-gray-500 mt-2">Maximum 1000 characters</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('adiutor.earnings.index') }}" 
                   class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Submit Payout Request
                </button>
            </div>
        </form>
    @endif

    <!-- Time Entries Preview -->
    @if($unpaidEarnings->isNotEmpty())
    <div class="mt-8 bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Time Entries to be Included</h3>
            <p class="text-sm text-gray-500 mt-1">Preview of approved unpaid entries</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Project / Task</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($unpaidEarnings as $entry)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $entry->start_time->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $entry->task->taskTitle ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $entry->task->project->title ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ number_format($entry->duration_minutes / 60, 2) }}h
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₱{{ number_format($entry->hourly_rate ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            ₱{{ number_format($entry->calculated_amount ?? 0, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                    <tr>
                        <td colspan="2" class="px-6 py-4 text-sm font-semibold text-gray-900">Total</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ number_format($totalHours, 2) }}h</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4 text-lg font-bold text-primary-600">₱{{ number_format($totalUnpaid, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection

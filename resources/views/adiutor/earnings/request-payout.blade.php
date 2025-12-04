@extends('adiutor.layouts.app')

@section('title', 'Request Payout')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Earnings', 'url' => route('adiutor.earnings.index')],
        ['label' => 'Request Payout'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <a href="{{ route('adiutor.earnings.index') }}" 
               class="inline-flex items-center text-neutral-600 hover:text-neutral-800 transition-colors">
                <x-lucide-chevron-left class="w-5 h-5 mr-1" />
                Back to Earnings
            </a>
        </div>
        <h1 class="text-2xl font-semibold text-neutral-800">Request Payout</h1>
        <p class="text-sm text-neutral-500 mt-1">Submit a request to receive your approved earnings</p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-6 bg-error-50 border border-error-100 rounded-2xl p-4">
            <div class="flex gap-3">
                <div class="p-2 bg-error-100 rounded-lg h-fit">
                    <x-lucide-alert-circle class="w-5 h-5 text-error-600" />
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-error-800">Error</h3>
                    <ul class="mt-2 text-sm text-error-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Available Earnings Summary -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-8 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-6 md:mb-0">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-primary-50 rounded-xl">
                        <x-lucide-wallet class="w-6 h-6 text-primary-600" />
                    </div>
                    <h2 class="text-lg font-medium text-neutral-600">Available for Payout</h2>
                </div>
                <p class="text-4xl font-bold text-neutral-800">₱{{ number_format($totalUnpaid, 2) }}</p>
                <p class="text-sm text-neutral-500 mt-2">{{ number_format($totalHours, 1) }} hours tracked</p>
            </div>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex items-center gap-2 text-neutral-600">
                    <x-lucide-check-circle-2 class="w-5 h-5 text-success-500" />
                    <span>{{ $unpaidEarnings->count() }} approved time entries</span>
                </div>
                <div class="flex items-center gap-2 text-neutral-600">
                    <x-lucide-banknote class="w-5 h-5 text-primary-500" />
                    <span>Minimum: ₱{{ number_format($adiutor->adiutorProfile->minimum_payout_amount ?? 500, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($totalUnpaid < ($adiutor->adiutorProfile->minimum_payout_amount ?? 500))
        <!-- Insufficient Amount Warning -->
        <div class="bg-warning-50 border border-warning-100 rounded-2xl p-6 mb-8">
            <div class="flex gap-3">
                <div class="p-2 bg-warning-100 rounded-lg h-fit">
                    <x-lucide-alert-triangle class="w-5 h-5 text-warning-600" />
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-warning-800 mb-2">Insufficient Amount</h3>
                    <p class="text-sm text-warning-700">
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
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-neutral-800">Payout Period</h3>
                    <p class="text-sm text-neutral-500 mt-1">Select the time period for this payout request</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="period_start" class="block text-sm font-medium text-neutral-700 mb-2">
                                Start Date <span class="text-error-500">*</span>
                            </label>
                            <input type="date" 
                                   id="period_start" 
                                   name="period_start" 
                                   value="{{ old('period_start', $suggestedStart->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        </div>
                        <div>
                            <label for="period_end" class="block text-sm font-medium text-neutral-700 mb-2">
                                End Date <span class="text-error-500">*</span>
                            </label>
                            <input type="date" 
                                   id="period_end" 
                                   name="period_end" 
                                   value="{{ old('period_end', $suggestedEnd->format('Y-m-d')) }}"
                                   required
                                   class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        </div>
                    </div>
                    <p class="text-xs text-neutral-500 mt-3 flex items-center gap-1">
                        <x-lucide-info class="w-4 h-4" />
                        Only approved and unpaid time entries within this period will be included
                    </p>
                </div>
            </x-ui.card>

            <!-- Payment Method -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-neutral-800">Payment Method</h3>
                    <p class="text-sm text-neutral-500 mt-1">Confirm or change your preferred payment method</p>
                </div>
                <div class="p-6">
                    <div class="mb-4">
                        <label for="payout_method" class="block text-sm font-medium text-neutral-700 mb-2">
                            Preferred Method
                        </label>
                        <select id="payout_method" 
                                name="payout_method"
                                class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
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
                        <div class="bg-neutral-50 rounded-xl p-4">
                            <p class="text-sm font-medium text-neutral-700 mb-2">Saved Payment Details:</p>
                            <div class="text-sm text-neutral-600 space-y-1">
                                @foreach(json_decode($adiutor->adiutorProfile->payout_details, true) ?? [] as $key => $value)
                                    <p><span class="font-medium">{{ ucwords(str_replace('_', ' ', $key)) }}:</span> {{ $value }}</p>
                                @endforeach
                            </div>
                            <p class="text-xs text-neutral-500 mt-3">
                                <a href="{{ route('adiutor.profile.earnings') }}" class="text-primary-600 hover:text-primary-700">
                                    Update payment details
                                </a>
                            </p>
                        </div>
                    @else
                        <div class="bg-warning-50 border border-warning-100 rounded-xl p-4">
                            <div class="flex items-center gap-2">
                                <x-lucide-alert-triangle class="w-4 h-4 text-warning-600" />
                                <p class="text-sm text-warning-800">
                                    You haven't set up your payment details yet. 
                                    <a href="{{ route('adiutor.profile.earnings') }}" class="font-medium underline hover:text-warning-900">
                                        Set up now
                                    </a>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </x-ui.card>

            <!-- Additional Notes -->
            <x-ui.card>
                <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                    <h3 class="text-lg font-semibold text-neutral-800">Additional Notes</h3>
                    <p class="text-sm text-neutral-500 mt-1">Optional notes for the admin</p>
                </div>
                <div class="p-6">
                    <textarea id="notes" 
                              name="notes" 
                              rows="4"
                              placeholder="Add any special instructions or notes for this payout request..."
                              class="w-full px-4 py-2.5 border border-neutral-200 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">{{ old('notes') }}</textarea>
                    <p class="text-xs text-neutral-500 mt-2">Maximum 1000 characters</p>
                </div>
            </x-ui.card>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between gap-4">
                <x-ui.button href="{{ route('adiutor.earnings.index') }}" variant="secondary">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-check class="w-5 h-5" />
                    Submit Payout Request
                </x-ui.button>
            </div>
        </form>
    @endif

    <!-- Time Entries Preview -->
    @if($unpaidEarnings->isNotEmpty())
    <div class="mt-8">
        <x-ui.card>
            <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50 rounded-t-2xl">
                <h3 class="text-lg font-semibold text-neutral-800">Time Entries to be Included</h3>
                <p class="text-sm text-neutral-500 mt-1">Preview of approved unpaid entries</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Project / Task</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Hours</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Rate</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        @foreach($unpaidEarnings as $entry)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800">
                                {{ $entry->start_time->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-neutral-800">{{ $entry->task->taskTitle ?? 'N/A' }}</div>
                                <div class="text-xs text-neutral-500">{{ $entry->task->project->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                                {{ number_format($entry->duration_minutes / 60, 2) }}h
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-700">
                                ₱{{ number_format($entry->hourly_rate ?? 0, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-neutral-800">
                                ₱{{ number_format($entry->calculated_amount ?? 0, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-neutral-50 border-t-2 border-neutral-200">
                        <tr>
                            <td colspan="2" class="px-6 py-4 text-sm font-semibold text-neutral-800">Total</td>
                            <td class="px-6 py-4 text-sm font-semibold text-neutral-800">{{ number_format($totalHours, 2) }}h</td>
                            <td class="px-6 py-4"></td>
                            <td class="px-6 py-4 text-lg font-bold text-primary-600">₱{{ number_format($totalUnpaid, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </x-ui.card>
    </div>
    @endif
</div>
@endsection

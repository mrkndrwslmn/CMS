@extends('admin.layouts.app')

@section('title', 'Time Entry Approvals')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Payouts', 'route' => 'admin.payouts.index', 'icon' => 'wallet'],
        ['label' => 'Time Entry Approvals', 'icon' => 'clock'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <x-ui.page-header 
            title="Time Entry Approvals" 
            description="Review and approve adiutor time entries"
        />
    </div>

    @if(session('success'))
        <div class="mb-6 bg-success-50 border border-success-200 rounded-xl p-4">
            <div class="flex gap-3">
                <x-lucide-check-circle class="w-5 h-5 text-success-500 flex-shrink-0" />
                <p class="text-sm text-success-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-error-50 border border-error-200 rounded-xl p-4">
            <div class="flex gap-3">
                <x-lucide-x-circle class="w-5 h-5 text-error-500 flex-shrink-0" />
                <p class="text-sm text-error-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Pending Approval</p>
                    <p class="text-2xl font-semibold text-warning-600 mt-1">{{ $stats['pending'] }}</p>
                    <p class="text-xs text-neutral-400 mt-1">{{ number_format($stats['total_hours_pending'], 1) }} hours</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Pending Amount</p>
                    <p class="text-2xl font-semibold text-warning-600 mt-1">₱{{ number_format($stats['pending_amount'], 2) }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-banknote class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Approved (Unpaid)</p>
                    <p class="text-2xl font-semibold text-primary-600 mt-1">{{ $stats['approved'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-neutral-500">Ready for Payout</p>
                    <p class="text-2xl font-semibold text-success-600 mt-1">₱{{ number_format($stats['approved_amount'], 2) }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-wallet class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </x-ui.card>
    </div>

    <!-- Filters -->
    <x-ui.card class="overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-neutral-100 bg-neutral-50">
            <h3 class="text-base font-medium text-neutral-700">Filters</h3>
        </div>
        <form method="GET" action="{{ route('admin.payouts.time-entry-approvals') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-neutral-700 mb-1.5">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending Approval</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved (Unpaid)</option>
                        <option value="paid" {{ $status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </div>

                <div>
                    <label for="adiutor" class="block text-sm font-medium text-neutral-700 mb-1.5">Adiutor</label>
                    <select name="adiutor" id="adiutor" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Adiutors</option>
                        @foreach($adiutors as $adiutor)
                            <option value="{{ $adiutor->id }}" {{ request('adiutor') == $adiutor->id ? 'selected' : '' }}>
                                {{ $adiutor->fullName }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="project" class="block text-sm font-medium text-neutral-700 mb-1.5">Project</label>
                    <select name="project" id="project" class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                {{ $project->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="date_from" class="block text-sm font-medium text-neutral-700 mb-1.5">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-neutral-700 mb-1.5">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                           class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-colors">
                </div>
            </div>

            <div class="mt-4 flex items-center gap-3">
                <x-ui.button type="submit" variant="primary">
                    <x-lucide-search class="w-4 h-4" />
                    Apply Filters
                </x-ui.button>
                <a href="{{ route('admin.payouts.time-entry-approvals') }}">
                    <x-ui.button type="button" variant="ghost">
                        Clear
                    </x-ui.button>
                </a>
            </div>
        </form>
    </x-ui.card>

    <!-- Time Entries Table -->
    <x-ui.card class="overflow-hidden">
        <div class="px-6 py-4 border-b border-neutral-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-neutral-800">Time Entries</h3>
                <p class="text-sm text-neutral-500">{{ $timeEntries->total() }} entries found</p>
            </div>
        </div>

        @if($timeEntries->isEmpty())
            <div class="p-12 text-center">
                <div class="w-16 h-16 bg-neutral-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <x-lucide-clock class="w-8 h-8 text-neutral-400" />
                </div>
                <h4 class="text-lg font-medium text-neutral-800 mb-2">No Time Entries Found</h4>
                <p class="text-neutral-500">No time entries match your current filters.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-neutral-50 border-b border-neutral-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Adiutor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Date / Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Task / Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Duration</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-100">
                        @foreach($timeEntries as $entry)
                        <tr class="hover:bg-neutral-50 transition-colors" id="time-entry-row-{{ $entry->id }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $entry->adiutor->getProfilePictureUrl() }}" 
                                         alt="{{ $entry->adiutor->fullName ?? 'Unknown' }}" 
                                         class="w-8 h-8 rounded-full object-cover">
                                    <div>
                                        <a href="{{ route('admin.payouts.adiutor-earnings', $entry->adiutor_id) }}" 
                                           class="text-sm font-medium text-neutral-800 hover:text-primary-600">
                                            {{ $entry->adiutor->fullName ?? 'Unknown' }}
                                        </a>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800">
                                {{ $entry->start_time->format('M d, Y') }}
                                <div class="text-xs text-neutral-500">
                                    {{ $entry->start_time->format('g:i A') }} - {{ $entry->end_time ? $entry->end_time->format('g:i A') : 'Ongoing' }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-neutral-800">{{ $entry->task?->taskTitle ?? 'N/A' }}</div>
                                <div class="text-xs text-neutral-500">{{ $entry->task?->project?->title ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-800">
                                {{ number_format($entry->duration_minutes / 60, 2) }}h
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-neutral-800">₱{{ number_format($entry->calculated_amount ?? 0, 2) }}</div>
                                <div class="text-xs text-neutral-500">@ ₱{{ number_format($entry->hourly_rate ?? 0, 2) }}/hr</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" id="status-cell-{{ $entry->id }}">
                                @if($entry->is_paid)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-success-50 text-success-700">
                                        <x-lucide-check-circle class="w-3 h-3" />
                                        Paid
                                    </span>
                                @elseif($entry->is_approved)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-50 text-primary-700">
                                        <x-lucide-check class="w-3 h-3" />
                                        Approved
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-warning-50 text-warning-700">
                                        <x-lucide-clock class="w-3 h-3" />
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap" id="actions-cell-{{ $entry->id }}">
                                @if(!$entry->is_approved && !$entry->is_paid)
                                    <div class="flex items-center gap-2">
                                        <button type="button" 
                                                onclick="approveTimeEntry({{ $entry->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-white bg-success-500 hover:bg-success-600 rounded-lg transition-colors">
                                            <x-lucide-check class="w-3 h-3" />
                                            Approve
                                        </button>
                                        <button type="button"
                                                onclick="openRejectModal({{ $entry->id }})"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-white bg-error-500 hover:bg-error-600 rounded-lg transition-colors">
                                            <x-lucide-x class="w-3 h-3" />
                                            Reject
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-neutral-400">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($timeEntries->hasPages())
                <div class="px-6 py-4 border-t border-neutral-100">
                    {{ $timeEntries->withQueryString()->links() }}
                </div>
            @endif
        @endif
    </x-ui.card>
</div>

<!-- Reject Time Entry Modal -->
<div id="rejectModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeRejectModal()"></div>
        
        <div class="relative bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="bg-white px-6 pt-6 pb-4">
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-error-100 rounded-full flex items-center justify-center">
                            <x-lucide-x-circle class="w-6 h-6 text-error-600" />
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-800">Reject Time Entry</h3>
                            <p class="text-sm text-neutral-500">Please provide a reason for rejection.</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="rejection_reason" class="block text-sm font-medium text-neutral-700 mb-1.5">Rejection Reason</label>
                        <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                                  class="w-full px-3 py-2 border border-neutral-200 rounded-lg text-sm focus:ring-2 focus:ring-error-500/20 focus:border-error-500 transition-colors"
                                  placeholder="Explain why this time entry is being rejected..."></textarea>
                    </div>
                </div>
                
                <div class="bg-neutral-50 px-6 py-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-error-500 rounded-lg hover:bg-error-600 transition-colors">
                        Reject Entry
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentRejectEntryId = null;

    function approveTimeEntry(entryId) {
        if (!confirm('Are you sure you want to approve this time entry?')) {
            return;
        }

        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin w-3 h-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        fetch(`{{ url('admin/payouts/time-entries') }}/${entryId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (response.ok) {
                // Update the status cell
                const statusCell = document.getElementById(`status-cell-${entryId}`);
                statusCell.innerHTML = `
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-medium bg-primary-50 text-primary-700">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                        Approved
                    </span>
                `;
                
                // Update the actions cell
                const actionsCell = document.getElementById(`actions-cell-${entryId}`);
                actionsCell.innerHTML = '<span class="text-xs text-neutral-400">—</span>';

                // Show success message
                if (window.toast) {
                    window.toast.success('Time entry approved successfully');
                } else {
                    alert('Time entry approved successfully');
                }
            } else {
                throw new Error('Failed to approve');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            if (window.toast) {
                window.toast.error('Failed to approve time entry. Please try again.');
            } else {
                alert('Failed to approve time entry. Please try again.');
            }
        });
    }

    function openRejectModal(entryId) {
        currentRejectEntryId = entryId;
        document.getElementById('rejectForm').action = `{{ url('admin/payouts/time-entries') }}/${entryId}/reject`;
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejection_reason').value = '';
        currentRejectEntryId = null;
    }
</script>
@endpush

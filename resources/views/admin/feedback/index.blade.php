@extends('admin.layouts.app')

@section('title', 'Feedback Management')
@section('page-title', 'Feedback Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Feedback', 'icon' => 'message-square-text'],
    ]" class="mb-6" />

    <!-- Page Header with Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <x-ui.page-header 
            title="Feedback Management" 
            description="View and manage all customer feedback"
        />
        
        <div class="flex items-center gap-3 mt-4 md:mt-0">
            <a href="{{ route('admin.feedback.analytics') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-xl border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                <x-lucide-bar-chart-3 class="w-4 h-4" />
                Analytics
            </a>
            <a href="{{ route('admin.feedback.export') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-xl border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                <x-lucide-download class="w-4 h-4" />
                Export
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-6">
        <!-- Total Feedback -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Feedback</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_feedback']) }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-message-square-text class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Average Rating</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['average_rating'] ?? 'N/A' }}/5</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-star class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Pending</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['pending_feedback']) }}</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-clock class="w-5 h-5 text-warning-500" />
                </div>
            </div>
        </div>

        <!-- Resolved -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Resolved</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['resolved_feedback']) }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <!-- Positive -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Positive</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['positive_feedback']) }}</p>
                </div>
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-smile class="w-5 h-5 text-success-500" />
                </div>
            </div>
        </div>

        <!-- Negative -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Negative</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['negative_feedback']) }}</p>
                </div>
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-frown class="w-5 h-5 text-error-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <x-ui.card>
        <x-slot:header>
            <div class="flex items-center gap-2">
                <x-lucide-message-square-text class="w-5 h-5 text-neutral-400" />
                <h2 class="text-lg font-medium text-neutral-700">All Feedback</h2>
            </div>
        </x-slot:header>

        <!-- Search and Filter Form -->
        <form method="GET" action="{{ route('admin.feedback.index') }}" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <x-ui.input 
                        type="text" 
                        name="search" 
                        :value="request('search')" 
                        placeholder="Search feedback..."
                        icon="search"
                    />
                </div>

                <!-- Rating Filter -->
                <div>
                    <x-ui.select name="rating">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    </x-ui.select>
                </div>

                <!-- Status Filter -->
                <div>
                    <x-ui.select name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    </x-ui.select>
                </div>

                <!-- Client Filter -->
                <div>
                    <x-ui.select name="client">
                        <option value="">All Clients</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                {{ $client->fullName }}
                            </option>
                        @endforeach
                    </x-ui.select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <x-ui.button type="submit" variant="primary" class="flex-1">
                        <x-lucide-filter class="w-4 h-4" />
                        Filter
                    </x-ui.button>
                    <x-ui.button href="{{ route('admin.feedback.index') }}" variant="secondary">
                        <x-lucide-x class="w-4 h-4" />
                    </x-ui.button>
                </div>
            </div>
        </form>

        <!-- Feedback Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-neutral-50 border-b border-neutral-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Feedback</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-100">
                    @forelse($feedbacks as $feedback)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($feedback->client)
                                        @if($feedback->client->profilePic)
                                            <img src="{{ $feedback->client->getProfilePictureUrl() }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold mr-3">
                                                {{ substr($feedback->client->fullName, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-neutral-700">{{ $feedback->client->fullName }}</p>
                                            <p class="text-xs text-neutral-400">{{ $feedback->client->email }}</p>
                                        </div>
                                    @else
                                        <span class="text-neutral-400 italic text-sm">N/A</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    @if($feedback->title)
                                        <p class="text-sm font-medium text-neutral-700 mb-1">{{ Str::limit($feedback->title, 40) }}</p>
                                    @endif
                                    <p class="text-sm text-neutral-500">{{ Str::limit($feedback->message, 60) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($feedback->rating)
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <x-lucide-star class="w-4 h-4 {{ $i <= $feedback->rating ? 'text-warning-400 fill-warning-400' : 'text-neutral-200' }}" />
                                        @endfor
                                        <span class="ml-1 text-sm font-medium text-neutral-600">{{ $feedback->rating }}/5</span>
                                    </div>
                                @else
                                    <span class="text-neutral-400 text-sm">No rating</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $typeVariants = [
                                        'general' => 'primary',
                                        'service' => 'info',
                                        'technical' => 'info',
                                        'complaint' => 'error',
                                        'suggestion' => 'success',
                                    ];
                                    $variant = $typeVariants[$feedback->type] ?? 'neutral';
                                @endphp
                                <x-ui.badge :variant="$variant">
                                    {{ ucfirst($feedback->type ?? 'general') }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusVariants = [
                                        'pending' => 'warning',
                                        'reviewed' => 'info',
                                        'resolved' => 'success',
                                    ];
                                    $statusVariant = $statusVariants[$feedback->status] ?? 'neutral';
                                @endphp
                                <x-ui.badge :variant="$statusVariant">
                                    {{ ucfirst($feedback->status ?? 'pending') }}
                                </x-ui.badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-500">
                                {{ $feedback->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.feedback.show', $feedback->id) }}" 
                                       class="inline-flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors"
                                       title="View Details">
                                        <x-lucide-eye class="w-4 h-4" />
                                    </a>
                                    @if($feedback->status !== 'resolved')
                                        <button type="button" 
                                                class="inline-flex items-center justify-center w-8 h-8 text-neutral-400 hover:text-success-600 hover:bg-success-50 rounded-lg transition-colors"
                                                title="Mark as Resolved"
                                                onclick="updateStatus({{ $feedback->id }}, 'resolved')">
                                            <x-lucide-check class="w-4 h-4" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="p-4 bg-neutral-50 rounded-full mb-4">
                                        <x-lucide-message-square-text class="w-12 h-12 text-neutral-300" />
                                    </div>
                                    <p class="text-lg font-medium text-neutral-600">No feedback found</p>
                                    <p class="text-sm text-neutral-400 mt-1">No feedback has been submitted yet</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($feedbacks->hasPages())
            <div class="mt-6 flex justify-center border-t border-neutral-100 pt-6">
                <x-ui.pagination :paginator="$feedbacks" />
            </div>
        @endif
    </x-ui.card>
</div>
@endsection

@section('scripts')
<script>
    function updateStatus(feedbackId, status) {
        window.Alerts.confirm(
            'Update Status',
            'Are you sure you want to mark this feedback as ' + status + '?',
            {
                confirmText: 'Update',
                confirmVariant: 'primary',
                onConfirm: function() {
                    fetch(`/admin/feedback/${feedbackId}/status`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ status: status })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            window.toast.error('Failed to update status');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        window.toast.error('An error occurred');
                    });
                }
            }
        );
    }
</script>
@endsection

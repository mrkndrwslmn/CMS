@extends('admin.layouts.app')

@section('title', 'Feedback Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Feedback Management</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Feedback</span>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
        <!-- Total Feedback -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-comments text-xl"></i>
                </div>
            </div>
            <p class="text-blue-100 text-xs font-medium mb-1">Total Feedback</p>
            <p class="text-2xl font-bold">{{ number_format($stats['total_feedback']) }}</p>
        </div>

        <!-- Average Rating -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-star text-xl"></i>
                </div>
            </div>
            <p class="text-amber-100 text-xs font-medium mb-1">Average Rating</p>
            <p class="text-2xl font-bold">{{ $stats['average_rating'] ?? 'N/A' }}/5</p>
        </div>

        <!-- Pending -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-clock text-xl"></i>
                </div>
            </div>
            <p class="text-orange-100 text-xs font-medium mb-1">Pending</p>
            <p class="text-2xl font-bold">{{ number_format($stats['pending_feedback']) }}</p>
        </div>

        <!-- Resolved -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
            </div>
            <p class="text-green-100 text-xs font-medium mb-1">Resolved</p>
            <p class="text-2xl font-bold">{{ number_format($stats['resolved_feedback']) }}</p>
        </div>

        <!-- Positive -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-smile text-xl"></i>
                </div>
            </div>
            <p class="text-emerald-100 text-xs font-medium mb-1">Positive</p>
            <p class="text-2xl font-bold">{{ number_format($stats['positive_feedback']) }}</p>
        </div>

        <!-- Negative -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-5 text-white transform hover:scale-105 transition-transform duration-200">
            <div class="flex items-center justify-between mb-2">
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-frown text-xl"></i>
                </div>
            </div>
            <p class="text-red-100 text-xs font-medium mb-1">Negative</p>
            <p class="text-2xl font-bold">{{ number_format($stats['negative_feedback']) }}</p>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center">
                <i class="fas fa-comments text-gray-600 mr-2"></i>
                <h2 class="text-lg font-semibold text-gray-800">All Feedback</h2>
            </div>
        </div>

        <div class="p-6">
            <!-- Search and Filter Form -->
            <form method="GET" action="{{ route('admin.feedback.index') }}" class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-6 lg:grid-cols-12 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-3 lg:col-span-3">
                        <div class="relative">
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search feedback..." 
                                   class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Rating Filter -->
                    <div class="md:col-span-2 lg:col-span-2">
                        <select name="rating" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            <option value="">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="md:col-span-2 lg:col-span-2">
                        <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>

                    <!-- Client Filter -->
                    <div class="md:col-span-2 lg:col-span-2">
                        <select name="client" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                            <option value="">All Clients</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ request('client') == $client->id ? 'selected' : '' }}>
                                    {{ $client->fullName }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="md:col-span-2 lg:col-span-2">
                        <button type="submit" class="w-full px-4 py-2.5 bg-accent-500 text-white font-medium rounded-lg hover:bg-accent-600 transition-colors">
                            Filter
                        </button>
                    </div>
                    <div class="md:col-span-1 lg:col-span-1">
                        <a href="{{ route('admin.feedback.index') }}" class="block w-full px-4 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors text-center">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Feedback Table -->
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Feedback</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($feedbacks as $feedback)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if($feedback->client)
                                            @if($feedback->client->profilePic)
                                                <img src="{{ asset('storage/' . $feedback->client->profilePic) }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                            @else
                                                <div class="w-10 h-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-semibold mr-3">
                                                    {{ substr($feedback->client->fullName, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $feedback->client->fullName }}</p>
                                                <p class="text-xs text-gray-500">{{ $feedback->client->email }}</p>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        @if($feedback->title)
                                            <p class="text-sm font-medium text-gray-900 mb-1">{{ Str::limit($feedback->title, 40) }}</p>
                                        @endif
                                        <p class="text-sm text-gray-600">{{ Str::limit($feedback->message, 60) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($feedback->rating)
                                        <div class="flex items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $feedback->rating)
                                                    <i class="fas fa-star text-amber-400 text-sm"></i>
                                                @else
                                                    <i class="far fa-star text-gray-300 text-sm"></i>
                                                @endif
                                            @endfor
                                            <span class="ml-2 text-sm font-medium text-gray-700">{{ $feedback->rating }}/5</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">No rating</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $typeColors = [
                                            'general' => 'bg-blue-100 text-blue-800',
                                            'service' => 'bg-purple-100 text-purple-800',
                                            'technical' => 'bg-indigo-100 text-indigo-800',
                                            'complaint' => 'bg-red-100 text-red-800',
                                            'suggestion' => 'bg-green-100 text-green-800',
                                        ];
                                        $color = $typeColors[$feedback->type] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
                                        {{ ucfirst($feedback->type ?? 'general') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-orange-100 text-orange-800',
                                            'reviewed' => 'bg-blue-100 text-blue-800',
                                            'resolved' => 'bg-green-100 text-green-800',
                                        ];
                                        $statusColor = $statusColors[$feedback->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst($feedback->status ?? 'pending') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $feedback->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.feedback.show', $feedback->id) }}" 
                                           class="inline-flex items-center justify-center w-8 h-8 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($feedback->status !== 'resolved')
                                            <button type="button" 
                                                    class="inline-flex items-center justify-center w-8 h-8 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                                    title="Mark as Resolved"
                                                    onclick="updateStatus({{ $feedback->id }}, 'resolved')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-comments text-6xl mb-4"></i>
                                        <p class="text-lg font-medium text-gray-500">No feedback found</p>
                                        <p class="text-sm mt-1">No feedback has been submitted yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-center">
                {{ $feedbacks->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateStatus(feedbackId, status) {
        if (confirm('Are you sure you want to mark this feedback as ' + status + '?')) {
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
                    alert('Failed to update status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    }
</script>
@endsection

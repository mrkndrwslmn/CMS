@extends('admin.layouts.app')

@section('title', 'Feedback Details')
@section('page-title', 'Feedback Details')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Feedback Details</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('admin.feedback.index') }}" class="hover:text-accent-500 transition-colors">Feedback</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Details</span>
                </nav>
            </div>
            <a href="{{ route('admin.feedback.index') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Feedback
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Feedback Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-accent-500 to-accent-600 px-6 py-4">
                    <div class="flex items-center justify-between text-white">
                        <div>
                            <h2 class="text-xl font-semibold">{{ $feedback->title ?? 'Feedback' }}</h2>
                            <p class="text-accent-100 text-sm mt-1">Submitted on {{ $feedback->created_at->format('F d, Y') }}</p>
                        </div>
                        @php
                            $statusColors = [
                                'pending' => 'bg-orange-100 text-orange-800',
                                'reviewed' => 'bg-blue-100 text-blue-800',
                                'resolved' => 'bg-green-100 text-green-800',
                            ];
                            $statusColor = $statusColors[$feedback->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">
                            {{ ucfirst($feedback->status ?? 'pending') }}
                        </span>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <!-- Rating -->
                    @if($feedback->rating)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Rating</h3>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $feedback->rating)
                                        <i class="fas fa-star text-amber-400 text-2xl"></i>
                                    @else
                                        <i class="far fa-star text-gray-300 text-2xl"></i>
                                    @endif
                                @endfor
                                <span class="ml-3 text-xl font-bold text-gray-700">{{ $feedback->rating }}/5</span>
                            </div>
                        </div>
                    @endif

                    <!-- Message -->
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Feedback Message</h3>
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                            <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->message }}</p>
                        </div>
                    </div>

                    <!-- Task Reference -->
                    @if($feedback->task)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Related Task</h3>
                            <a href="{{ route('admin.tasks.show', $feedback->task->taskID) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-tasks mr-2"></i>
                                {{ $feedback->task->taskTitle }}
                            </a>
                        </div>
                    @endif

                    <!-- Admin Response -->
                    @if($feedback->admin_response)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2">Admin Response</h3>
                            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4">
                                <p class="text-gray-700 whitespace-pre-wrap">{{ $feedback->admin_response }}</p>
                                @if($feedback->responded_at)
                                    <p class="text-xs text-gray-500 mt-2">Responded on {{ $feedback->responded_at->format('F d, Y g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Response Form -->
                    @if(!$feedback->admin_response || $feedback->status !== 'resolved')
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-4">
                                {{ $feedback->admin_response ? 'Update Response' : 'Add Response' }}
                            </h3>
                            <form action="{{ route('admin.feedback.respond', $feedback->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <textarea 
                                        name="response" 
                                        rows="4" 
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all resize-none"
                                        placeholder="Write your response here...">{{ old('response', $feedback->admin_response) }}</textarea>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button type="submit" 
                                            class="px-6 py-2.5 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-lg hover:from-accent-600 hover:to-accent-700 transition-all duration-200 shadow-lg shadow-accent-500/30">
                                        <i class="fas fa-paper-plane mr-2"></i>
                                        Send Response
                                    </button>
                                    @if($feedback->status !== 'resolved')
                                        <button type="submit" 
                                                name="mark_resolved" 
                                                value="1"
                                                class="px-6 py-2.5 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Send & Mark Resolved
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Client Info Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-800">Client Information</h3>
                </div>
                <div class="p-4">
                    @if($feedback->client)
                        <div class="flex items-center mb-4">
                            @if($feedback->client->profilePic)
                                <img src="{{ $feedback->client->getProfilePictureUrl() }}" class="w-16 h-16 rounded-full object-cover mr-3">
                            @else
                                <div class="w-16 h-16 rounded-full bg-primary-500 flex items-center justify-center text-white text-xl font-bold mr-3">
                                    {{ substr($feedback->client->fullName, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $feedback->client->fullName }}</p>
                                <p class="text-sm text-gray-500">{{ ucfirst($feedback->client->role) }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-envelope w-5 mr-2 text-gray-400"></i>
                                <a href="mailto:{{ $feedback->client->email }}" class="hover:text-accent-500">{{ $feedback->client->email }}</a>
                            </div>
                            @if($feedback->client->phone)
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-phone w-5 mr-2 text-gray-400"></i>
                                    <a href="tel:{{ $feedback->client->phone }}" class="hover:text-accent-500">{{ $feedback->client->phone }}</a>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500 italic">No client information available</p>
                    @endif
                </div>
            </div>

            <!-- Adiutor Info Card -->
            @if($feedback->adiutor)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800">Assigned Adiutor</h3>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-4">
                            @if($feedback->adiutor->profilePic)
                                <img src="{{ $feedback->adiutor->getProfilePictureUrl() }}" class="w-12 h-12 rounded-full object-cover mr-3">
                            @else
                                <div class="w-12 h-12 rounded-full bg-accent-500 flex items-center justify-center text-white font-bold mr-3">
                                    {{ substr($feedback->adiutor->fullName, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-semibold text-gray-900">{{ $feedback->adiutor->fullName }}</p>
                                <p class="text-sm text-gray-500">Adiutor</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-envelope w-5 mr-2 text-gray-400"></i>
                                <a href="mailto:{{ $feedback->adiutor->email }}" class="hover:text-accent-500">{{ $feedback->adiutor->email }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Metadata Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-800">Metadata</h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($feedback->type ?? 'general') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium text-gray-900">{{ ucfirst($feedback->status ?? 'pending') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Submitted:</span>
                        <span class="font-medium text-gray-900">{{ $feedback->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($feedback->updated_at != $feedback->created_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Last Updated:</span>
                            <span class="font-medium text-gray-900">{{ $feedback->updated_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

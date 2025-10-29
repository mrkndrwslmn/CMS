@extends('adiutor.layouts.app')

@section('title', 'Feedback')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-neutral-900">Client Feedback</h1>
        <p class="text-neutral-600 mt-2">View feedback and reviews from your clients.</p>
    </div>

    <!-- Feedback Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Average Rating</p>
                    <p class="text-2xl font-bold text-neutral-900">
                        @if($feedbackStats['total_feedback'] > 0)
                            {{ number_format($feedbackStats['average_rating'], 1) }}
                        @else
                            --
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Total Reviews</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $feedbackStats['total_feedback'] }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Positive</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $feedbackStats['five_star'] + $feedbackStats['four_star'] }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Needs Work</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ $feedbackStats['three_star'] + $feedbackStats['two_star'] + $feedbackStats['one_star'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($feedback->count() > 0)
        <!-- Feedback List -->
        <div class="space-y-6">
            @foreach($feedback as $review)
                <div class="glass-card p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                    <span class="text-primary-700 font-medium text-lg">
                                        {{ substr($review->client_name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <h3 class="font-medium text-neutral-900">{{ $review->client_name }}</h3>
                                <p class="text-sm text-neutral-600">
                                    @if($review->project_title)
                                        Project: {{ $review->project_title }}
                                    @elseif($review->task_title)
                                        Task: {{ $review->task_title }}
                                    @else
                                        General Feedback
                                    @endif
                                </p>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm text-neutral-600">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm text-neutral-500">
                            {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                        </div>
                    </div>

                    <div class="mb-4">
                        @if($review->title)
                            <h4 class="font-medium text-neutral-900 mb-2">{{ $review->title }}</h4>
                        @endif
                        <p class="text-neutral-700">{{ $review->message }}</p>
                    </div>

                    @if($review->admin_response)
                        <div class="bg-neutral-50 rounded-lg p-4 border-l-4 border-primary-500">
                            <h4 class="font-medium text-neutral-900 mb-2">Admin Response</h4>
                            <p class="text-neutral-700">{{ $review->admin_response }}</p>
                            @if($review->responded_at)
                                <p class="text-xs text-neutral-500 mt-2">
                                    Responded {{ \Carbon\Carbon::parse($review->responded_at)->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    @endif
                    
                    @if($review->status)
                        <div class="mt-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($review->status === 'resolved') bg-green-100 text-green-800
                                @elseif($review->status === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($review->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                            </span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="glass-card p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-neutral-900">No Feedback Yet</h3>
            <p class="mt-2 text-neutral-600">Client feedback and reviews will appear here once you complete projects.</p>
            <div class="mt-6">
                <a href="{{ route('adiutor.projects.index') }}" class="btn btn-primary">
                    View Your Projects
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
@extends('adiutor.layouts.app')

@section('title', 'Feedback')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'Feedback'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Client Feedback</h1>
        <p class="text-neutral-500 mt-1">View feedback and reviews from your clients.</p>
    </div>

    <!-- Feedback Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-warning-50 rounded-xl">
                    <x-lucide-star class="w-6 h-6 text-warning-600" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500">Average Rating</p>
                    <p class="text-2xl font-bold text-neutral-800">
                        @if($feedbackStats['total_feedback'] > 0)
                            {{ number_format($feedbackStats['average_rating'], 1) }}
                        @else
                            --
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-message-square class="w-6 h-6 text-primary-600" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Reviews</p>
                    <p class="text-2xl font-bold text-neutral-800">{{ $feedbackStats['total_feedback'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-success-50 rounded-xl">
                    <x-lucide-thumbs-up class="w-6 h-6 text-success-600" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500">Positive</p>
                    <p class="text-2xl font-bold text-neutral-800">{{ $feedbackStats['five_star'] + $feedbackStats['four_star'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-error-50 rounded-xl">
                    <x-lucide-thumbs-down class="w-6 h-6 text-error-600" />
                </div>
                <div>
                    <p class="text-sm font-medium text-neutral-500">Needs Work</p>
                    <p class="text-2xl font-bold text-neutral-800">{{ $feedbackStats['three_star'] + $feedbackStats['two_star'] + $feedbackStats['one_star'] }}</p>
                </div>
            </div>
        </div>
    </div>

    @if($feedback->count() > 0)
        <!-- Feedback List -->
        <div class="space-y-6">
            @foreach($feedback as $review)
                <x-ui.card class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary-50 rounded-full flex items-center justify-center">
                                <span class="text-primary-600 font-semibold text-lg">
                                    {{ substr($review->client_name, 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-neutral-800">{{ $review->client_name }}</h3>
                                <p class="text-sm text-neutral-500">
                                    @if($review->project_title)
                                        Project: {{ $review->project_title }}
                                    @else
                                        General Feedback
                                    @endif
                                </p>
                                <div class="flex items-center mt-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <x-lucide-star class="w-4 h-4 text-warning-400 fill-warning-400" />
                                        @else
                                            <x-lucide-star class="w-4 h-4 text-neutral-200" />
                                        @endif
                                    @endfor
                                    <span class="ml-2 text-sm text-neutral-500">{{ $review->rating }}/5</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-sm text-neutral-500">
                            {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                        </div>
                    </div>

                    <div class="mb-4">
                        @if($review->title)
                            <h4 class="font-medium text-neutral-800 mb-2">{{ $review->title }}</h4>
                        @endif
                        <p class="text-neutral-600">{{ $review->message }}</p>
                    </div>

                    @if($review->admin_response)
                        <div class="bg-neutral-50 rounded-xl p-4 border-l-4 border-primary-500">
                            <h4 class="font-medium text-neutral-800 mb-2">Admin Response</h4>
                            <p class="text-neutral-600">{{ $review->admin_response }}</p>
                            @if($review->responded_at)
                                <p class="text-xs text-neutral-500 mt-2">
                                    Responded {{ \Carbon\Carbon::parse($review->responded_at)->diffForHumans() }}
                                </p>
                            @endif
                        </div>
                    @endif
                    
                    @if($review->status)
                        <div class="mt-3">
                            <x-ui.badge 
                                :variant="match($review->status) {
                                    'resolved' => 'success',
                                    'in_progress' => 'info',
                                    'pending' => 'warning',
                                    default => 'default'
                                }">
                                {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                            </x-ui.badge>
                        </div>
                    @endif
                </x-ui.card>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <x-ui.card class="p-12 text-center">
            <div class="flex justify-center mb-4">
                <div class="p-4 bg-neutral-100 rounded-full">
                    <x-lucide-message-square class="w-12 h-12 text-neutral-400" />
                </div>
            </div>
            <h3 class="text-lg font-semibold text-neutral-800">No Feedback Yet</h3>
            <p class="mt-2 text-neutral-500">Client feedback and reviews will appear here once you complete projects.</p>
            <div class="mt-6">
                <x-ui.button href="{{ route('adiutor.projects.index') }}" variant="primary">
                    <x-lucide-folder class="w-4 h-4" />
                    View Your Projects
                </x-ui.button>
            </div>
        </x-ui.card>
    @endif
</div>
@endsection
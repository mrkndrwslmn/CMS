@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-neutral-50 dark:bg-neutral-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-neutral-900 dark:text-white mb-2">
                Select Team Member to Rate
            </h1>
            <p class="text-neutral-600 dark:text-neutral-400">
                Project: <span class="font-semibold">{{ $project->title }}</span>
            </p>
        </div>

        <!-- Adiutors List -->
        <div class="space-y-4">
            @foreach($assignments as $assignment)
            <div class="bg-white dark:bg-neutral-800 rounded-lg shadow-sm border border-neutral-200 dark:border-neutral-700 hover:shadow-md transition-shadow">
                <a href="{{ route('client.feedback.create', ['project' => $project->id, 'adiutor_id' => $assignment->adiutor_id]) }}" 
                   class="block p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Avatar -->
                            <div class="w-16 h-16 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                @if($assignment->adiutor->profile_photo_path)
                                    <img src="{{ asset('storage/' . $assignment->adiutor->profile_photo_path) }}" 
                                         alt="{{ $assignment->adiutor->fullName }}" 
                                         class="w-16 h-16 rounded-full object-cover">
                                @else
                                    <span class="text-2xl font-semibold text-primary-600 dark:text-primary-400">
                                        {{ substr($assignment->adiutor->first_name, 0, 1) }}{{ substr($assignment->adiutor->last_name, 0, 1) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Adiutor Info -->
                            <div>
                                <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                    {{ $assignment->adiutor->fullName }}
                                </h3>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400">
                                    {{ $assignment->adiutor->email }}
                                </p>
                                @if($assignment->adiutor->expertise)
                                    <p class="text-sm text-neutral-500 dark:text-neutral-500 mt-1">
                                        {{ $assignment->adiutor->expertise }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Arrow Icon -->
                        <div>
                            <svg class="w-6 h-6 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('client.feedback') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 dark:text-neutral-300 hover:text-neutral-900 dark:hover:text-white">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Feedback
            </a>
        </div>
    </div>
</div>
@endsection

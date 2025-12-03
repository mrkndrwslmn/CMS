@extends('admin.layouts.app')

@section('title', 'Feedback Details')
@section('page-title', 'Feedback Details')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Feedback', 'route' => 'admin.feedback.index', 'icon' => 'message-square-text'],
        ['label' => 'Details', 'icon' => 'file-text'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <x-ui.page-header 
        title="Feedback Details" 
        :description="'Submitted on ' . $feedback->created_at->format('F d, Y')"
        class="mb-6"
    />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Feedback Card -->
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-primary-50 rounded-lg">
                                <x-lucide-message-square-text class="w-5 h-5 text-primary-500" />
                            </div>
                            <div>
                                <h2 class="text-lg font-medium text-neutral-700">{{ $feedback->title ?? 'Feedback' }}</h2>
                                <p class="text-sm text-neutral-400">Submitted on {{ $feedback->created_at->format('F d, Y') }}</p>
                            </div>
                        </div>
                        @php
                            $statusVariants = [
                                'pending' => 'warning',
                                'reviewed' => 'info',
                                'resolved' => 'success',
                            ];
                            $statusVariant = $statusVariants[$feedback->status] ?? 'neutral';
                        @endphp
                        <x-ui.badge :variant="$statusVariant" size="lg">
                            {{ ucfirst($feedback->status ?? 'pending') }}
                        </x-ui.badge>
                    </div>
                </x-slot:header>

                <!-- Rating -->
                @if($feedback->rating)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-neutral-500 mb-2">Rating</h3>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <x-lucide-star class="w-6 h-6 {{ $i <= $feedback->rating ? 'text-warning-400 fill-warning-400' : 'text-neutral-200' }}" />
                            @endfor
                            <span class="ml-2 text-xl font-semibold text-neutral-700">{{ $feedback->rating }}/5</span>
                        </div>
                    </div>
                @endif

                <!-- Message -->
                <div class="mb-6">
                    <h3 class="text-sm font-medium text-neutral-500 mb-2">Feedback Message</h3>
                    <div class="bg-neutral-50 rounded-xl p-4 border border-neutral-100">
                        <p class="text-neutral-700 whitespace-pre-wrap">{{ $feedback->message }}</p>
                    </div>
                </div>

                <!-- Task Reference -->
                @if($feedback->task)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-neutral-500 mb-2">Related Task</h3>
                        <a href="{{ route('admin.tasks.show', $feedback->task->taskID) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-50 text-primary-700 rounded-lg hover:bg-primary-100 transition-colors">
                            <x-lucide-clipboard-list class="w-4 h-4" />
                            {{ $feedback->task->taskTitle }}
                        </a>
                    </div>
                @endif

                <!-- Admin Response -->
                @if($feedback->admin_response)
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-neutral-500 mb-2">Admin Response</h3>
                        <div class="bg-success-50 border-l-4 border-success-500 rounded-r-xl p-4">
                            <p class="text-neutral-700 whitespace-pre-wrap">{{ $feedback->admin_response }}</p>
                            @if($feedback->responded_at)
                                <p class="text-xs text-neutral-400 mt-3">
                                    Responded on {{ $feedback->responded_at->format('F d, Y g:i A') }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Response Form -->
                @if(!$feedback->admin_response || $feedback->status !== 'resolved')
                    <div class="border-t border-neutral-100 pt-6">
                        <h3 class="text-sm font-medium text-neutral-700 mb-4">
                            {{ $feedback->admin_response ? 'Update Response' : 'Add Response' }}
                        </h3>
                        <form action="{{ route('admin.feedback.respond', $feedback->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <x-ui.textarea 
                                    name="response" 
                                    rows="4" 
                                    required
                                    placeholder="Write your response here..."
                                    :value="old('response', $feedback->admin_response)"
                                />
                            </div>
                            <div class="flex items-center gap-3">
                                <x-ui.button type="submit" variant="primary">
                                    <x-lucide-send class="w-4 h-4" />
                                    Send Response
                                </x-ui.button>
                                @if($feedback->status !== 'resolved')
                                    <x-ui.button type="submit" name="mark_resolved" value="1" variant="success">
                                        <x-lucide-check-circle class="w-4 h-4" />
                                        Send & Mark Resolved
                                    </x-ui.button>
                                @endif
                            </div>
                        </form>
                    </div>
                @endif
            </x-ui.card>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Client Info Card -->
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-user class="w-4 h-4 text-neutral-400" />
                        <h3 class="text-sm font-medium text-neutral-700">Client Information</h3>
                    </div>
                </x-slot:header>

                @if($feedback->client)
                    <div class="flex items-center mb-4">
                        @if($feedback->client->profilePic)
                            <img src="{{ $feedback->client->getProfilePictureUrl() }}" class="w-14 h-14 rounded-full object-cover mr-3">
                        @else
                            <div class="w-14 h-14 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 text-xl font-semibold mr-3">
                                {{ substr($feedback->client->fullName, 0, 1) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-neutral-700">{{ $feedback->client->fullName }}</p>
                            <p class="text-sm text-neutral-400">{{ ucfirst($feedback->client->role) }}</p>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm">
                            <x-lucide-mail class="w-4 h-4 text-neutral-400" />
                            <a href="mailto:{{ $feedback->client->email }}" class="text-neutral-600 hover:text-primary-600 transition-colors">
                                {{ $feedback->client->email }}
                            </a>
                        </div>
                        @if($feedback->client->phone)
                            <div class="flex items-center gap-3 text-sm">
                                <x-lucide-phone class="w-4 h-4 text-neutral-400" />
                                <a href="tel:{{ $feedback->client->phone }}" class="text-neutral-600 hover:text-primary-600 transition-colors">
                                    {{ $feedback->client->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <p class="text-neutral-400 text-sm italic">No client information available</p>
                @endif
            </x-ui.card>

            <!-- Project Team Info Card -->
            @if($feedback->project)
                <x-ui.card>
                    <x-slot:header>
                        <div class="flex items-center gap-2">
                            <x-lucide-users class="w-4 h-4 text-neutral-400" />
                            <h3 class="text-sm font-medium text-neutral-700">Project Team</h3>
                        </div>
                    </x-slot:header>

                    @if($feedback->project->assignments && $feedback->project->assignments->count() > 0)
                        <div class="space-y-3">
                            @foreach($feedback->project->assignments as $assignment)
                                <div class="flex items-center">
                                    @if($assignment->adiutor->profilePic)
                                        <img src="{{ $assignment->adiutor->getProfilePictureUrl() }}" class="w-10 h-10 rounded-full object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold mr-3">
                                            {{ substr($assignment->adiutor->fullName, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-neutral-700 text-sm truncate">{{ $assignment->adiutor->fullName }}</p>
                                        <p class="text-xs text-neutral-400 truncate">{{ $assignment->adiutor->email }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-neutral-400 text-sm italic">No team members assigned</p>
                    @endif
                </x-ui.card>
            @endif

            <!-- Metadata Card -->
            <x-ui.card>
                <x-slot:header>
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-4 h-4 text-neutral-400" />
                        <h3 class="text-sm font-medium text-neutral-700">Metadata</h3>
                    </div>
                </x-slot:header>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500">Type</span>
                        <span class="text-sm font-medium text-neutral-700">{{ ucfirst($feedback->type ?? 'general') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500">Status</span>
                        <span class="text-sm font-medium text-neutral-700">{{ ucfirst($feedback->status ?? 'pending') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-neutral-500">Submitted</span>
                        <span class="text-sm font-medium text-neutral-700">{{ $feedback->created_at->format('M d, Y') }}</span>
                    </div>
                    @if($feedback->updated_at != $feedback->created_at)
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-neutral-500">Last Updated</span>
                            <span class="text-sm font-medium text-neutral-700">{{ $feedback->updated_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
            </x-ui.card>
        </div>
    </div>
</div>
@endsection

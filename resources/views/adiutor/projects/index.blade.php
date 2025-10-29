@extends('adiutor.layouts.app')

@section('title', 'Projects')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb Navigation -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('adiutor.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-900 font-medium">Projects</span>
    </nav>

    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Projects</h1>
                <p class="text-gray-600 mt-1">Manage and track your assigned projects</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-500">Active Projects</p>
                <p class="text-2xl font-bold text-primary-600">{{ $projects->where('assignment_status', 'active')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Projects</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $projects->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Active Projects -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $projects->where('assignment_status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Projects -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $projects->where('assignment_status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-50 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $projects->where('assignment_status', 'completed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-xl border border-gray-200 mb-6">
        <div class="border-b border-gray-200 px-6">
            <nav class="-mb-px flex space-x-8">
                <button class="filter-tab active border-primary-500 text-primary-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="all">
                    All Projects
                </button>
                <button class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="active">
                    Active
                </button>
                <button class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="pending">
                    Pending
                </button>
                <button class="filter-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                        data-filter="completed">
                    Completed
                </button>
            </nav>
        </div>
    </div>

    @if($projects->count() > 0)
        <!-- Projects Grid -->
        <div class="grid gap-6">
            @foreach($projects as $project)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden project-item hover:shadow-lg hover:border-primary-200 transition-all duration-200 cursor-pointer" 
                     data-status="{{ $project->assignment_status }}"
                     onclick="window.location.href='{{ route('adiutor.projects.show', $project->id) }}'">
                    <div class="p-6">
                        <!-- Project Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $project->title }}</h3>
                                <p class="text-sm text-gray-600 flex items-center">
                                    <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Client: {{ $project->client_name }}
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    {{ $project->assignment_status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $project->assignment_status === 'pending' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $project->assignment_status === 'completed' ? 'bg-primary-100 text-primary-800' : '' }}
                                    {{ !in_array($project->assignment_status, ['active', 'pending', 'completed']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                    <span class="w-2 h-2 rounded-full mr-2
                                        {{ $project->assignment_status === 'active' ? 'bg-green-600' : '' }}
                                        {{ $project->assignment_status === 'pending' ? 'bg-amber-600' : '' }}
                                        {{ $project->assignment_status === 'completed' ? 'bg-primary-600' : '' }}
                                        {{ !in_array($project->assignment_status, ['active', 'pending', 'completed']) ? 'bg-gray-600' : '' }}">
                                    </span>
                                    {{ ucfirst($project->assignment_status) }}
                                </span>
                                @if($project->priority)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $project->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $project->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                        {{ !in_array($project->priority, ['urgent', 'high']) ? 'bg-gray-100 text-gray-800' : '' }}">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ ucfirst($project->priority) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Project Description -->
                        @if($project->description)
                            <p class="text-gray-700 mb-4 line-clamp-2">{{ Str::limit($project->description, 150) }}</p>
                        @endif

                        <!-- Project Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
                                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    Budget
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($project->agreed_rate)
                                        ${{ number_format($project->agreed_rate, 2) }}
                                    @else
                                        ${{ number_format($project->budget, 2) }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500">{{ ucfirst($project->budget_type) }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
                                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    Deadline
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($project->deadline)
                                        {{ \Carbon\Carbon::parse($project->deadline)->format('M d, Y') }}
                                    @else
                                        <span class="text-gray-400">Not set</span>
                                    @endif
                                </p>
                                @if($project->deadline)
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($project->deadline)->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 mb-1 flex items-center">
                                    <svg class="w-4 h-4 text-primary-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M15 14h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Status
                                </p>
                                <p class="text-lg font-semibold text-gray-900">
                                    @if($project->start_date)
                                        Started
                                    @else
                                        <span class="text-gray-400">Not started</span>
                                    @endif
                                </p>
                                @if($project->start_date)
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($project->start_date)->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        @if($project->assignment_status === 'active')
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-sm font-medium text-gray-700 flex items-center">
                                        <svg class="w-4 h-4 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                        Progress
                                    </p>
                                    <p class="text-sm font-semibold text-primary-600">{{ $project->progress_percentage }}%</p>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                                    <div class="bg-linear-to-r from-primary-500 to-primary-600 h-2 rounded-full transition-all duration-300" 
                                         style="width: {{ $project->progress_percentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Project Notes -->
                        @if($project->notes)
                            <div class="mb-4 p-4 bg-primary-50 border-l-4 border-primary-500 rounded">
                                <p class="text-sm font-medium text-primary-900 mb-1 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    Notes
                                </p>
                                <p class="text-sm text-primary-800">{{ $project->notes }}</p>
                            </div>
                        @endif

                        <!-- View Details Button -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Created {{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}</span>
                            </div>
                            
                            <div class="flex items-center text-primary-600 font-medium">
                                <span class="text-sm">View Details</span>
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl border border-gray-200 p-12">
            <div class="text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No projects assigned</h3>
                <p class="text-gray-600 mb-6">You'll see your assigned projects here once they're available.</p>
                <a href="{{ route('adiutor.profile.edit') }}" class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Complete Your Profile
                </a>
            </div>
        </div>
    @endif
</div>

<script>
// Filter functionality
document.querySelectorAll('.filter-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        const filter = this.dataset.filter;
        
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(t => {
            t.classList.remove('active', 'border-primary-500', 'text-primary-600');
            t.classList.add('border-transparent', 'text-gray-500');
        });
        
        this.classList.add('active', 'border-primary-500', 'text-primary-600');
        this.classList.remove('border-transparent', 'text-gray-500');
        
        // Filter projects
        document.querySelectorAll('.project-item').forEach(project => {
            const status = project.dataset.status;
            
            if (filter === 'all' || status === filter) {
                project.style.display = 'block';
            } else {
                project.style.display = 'none';
            }
        });
    });
});
</script>
@endsection
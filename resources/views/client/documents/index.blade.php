@extends('client.layouts.app')

@section('title', 'My Documents')

@section('content')
<div class="max-w-7xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Documents', 'icon' => 'file-text'],
    ]" />

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">My Documents</h1>
        <p class="text-sm text-neutral-500 mt-1">View and download all documents from your projects</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Documents</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ $stats['total'] }}</p>
                </div>
                <div class="p-3 bg-primary-50 rounded-xl">
                    <x-lucide-files class="w-5 h-5 text-primary-500" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm font-medium text-neutral-500">Total Size</p>
                    <p class="text-2xl font-semibold text-neutral-800 mt-1">{{ number_format($stats['total_size'] / (1024 * 1024), 2) }} MB</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl">
                    <x-lucide-hard-drive class="w-5 h-5 text-blue-500" />
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm overflow-hidden">
        <!-- Card Header with Search -->
        <div class="bg-neutral-50 border-b border-neutral-100 p-6">
            <form method="GET" action="{{ route('client.documents') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Search</label>
                        <div class="relative">
                            <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400" />
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}" 
                                placeholder="Search by file name, description, or project..." 
                                class="w-full pl-10 pr-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                        </div>
                    </div>

                    <!-- Project Filter -->
                    <div>
                        <label class="block text-xs font-medium text-neutral-600 mb-1">Project</label>
                        <select name="project" class="w-full px-4 py-2.5 border border-neutral-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-sm">
                            <option value="">All Projects</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ request('project') == $project->id ? 'selected' : '' }}>
                                    {{ $project->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <x-lucide-search class="w-4 h-4" />
                            Filter
                        </button>
                        @if(request()->anyFilled(['search', 'project', 'type']))
                            <a href="{{ route('client.documents') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-neutral-200 text-neutral-700 text-sm font-medium rounded-lg hover:bg-neutral-100 transition-colors">
                                <x-lucide-x class="w-4 h-4" />
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Documents List -->
        <div class="p-6">
            @if($documents->count() > 0)
                <div class="space-y-4">
                    @foreach($documents as $document)
                        <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl border border-neutral-100 hover:bg-neutral-100/50 hover:border-primary-200 transition-colors {{ $document->is_locked ? 'opacity-70' : '' }}">
                            <div class="flex items-start space-x-4 flex-1 min-w-0">
                                <!-- File Icon -->
                                <div class="shrink-0">
                                    @if($document->is_locked)
                                        <div class="w-12 h-12 rounded-xl bg-warning-100 flex items-center justify-center">
                                            <x-lucide-lock class="w-6 h-6 text-warning-600" />
                                        </div>
                                    @else
                                        @php
                                            $extension = strtolower(pathinfo($document->fileName, PATHINFO_EXTENSION));
                                            $iconConfig = match($extension) {
                                                'pdf' => ['color' => 'text-error-600', 'bg' => 'bg-error-100', 'icon' => 'file-text'],
                                                'doc', 'docx' => ['color' => 'text-primary-600', 'bg' => 'bg-primary-100', 'icon' => 'file-text'],
                                                'xls', 'xlsx' => ['color' => 'text-success-600', 'bg' => 'bg-success-100', 'icon' => 'file-spreadsheet'],
                                                'ppt', 'pptx' => ['color' => 'text-orange-600', 'bg' => 'bg-orange-100', 'icon' => 'presentation'],
                                                'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => ['color' => 'text-purple-600', 'bg' => 'bg-purple-100', 'icon' => 'image'],
                                                'zip', 'rar', '7z' => ['color' => 'text-warning-600', 'bg' => 'bg-warning-100', 'icon' => 'archive'],
                                                'mp4', 'mov', 'avi' => ['color' => 'text-pink-600', 'bg' => 'bg-pink-100', 'icon' => 'video'],
                                                default => ['color' => 'text-neutral-600', 'bg' => 'bg-neutral-100', 'icon' => 'file']
                                            };
                                        @endphp
                                        <div class="w-12 h-12 rounded-xl {{ $iconConfig['bg'] }} flex items-center justify-center">
                                            <x-dynamic-component :component="'lucide-' . $iconConfig['icon']" class="w-6 h-6 {{ $iconConfig['color'] }}" />
                                        </div>
                                    @endif
                                </div>

                                <!-- File Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <p class="text-sm font-medium text-neutral-800 truncate">{{ $document->fileName }}</p>
                                        @if($document->is_locked)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-700">
                                                <x-lucide-lock class="w-3 h-3" />
                                                Payment Required
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if($document->description)
                                        <p class="text-xs text-neutral-600 mb-2 line-clamp-1">{{ $document->description }}</p>
                                    @endif
                                    
                                    <div class="flex flex-wrap items-center gap-3 text-xs text-neutral-500">
                                        @if($document->fileSize)
                                            <span class="flex items-center gap-1">
                                                <x-lucide-hard-drive class="w-3 h-3" />
                                                {{ number_format($document->fileSize / 1024, 2) }} KB
                                            </span>
                                        @endif
                                        
                                        @if($document->project_title)
                                            <a href="{{ route('client.projects.show', $document->project_id) }}" class="flex items-center gap-1 text-primary-600 hover:text-primary-700">
                                                <x-lucide-folder class="w-3 h-3" />
                                                {{ Str::limit($document->project_title, 25) }}
                                            </a>
                                        @endif
                                        
                                        @if($document->task_name)
                                            <span class="flex items-center gap-1">
                                                <x-lucide-clipboard-list class="w-3 h-3" />
                                                {{ Str::limit($document->task_name, 20) }}
                                            </span>
                                        @endif
                                        
                                        @if($document->uploaded_by_name)
                                            <span class="flex items-center gap-1">
                                                <x-lucide-user class="w-3 h-3" />
                                                {{ $document->uploaded_by_name }}
                                            </span>
                                        @endif
                                        
                                        <span class="flex items-center gap-1">
                                            <x-lucide-calendar class="w-3 h-3" />
                                            {{ $document->created_at->format('M j, Y') }}
                                        </span>
                                    </div>

                                    @if($document->is_locked)
                                        <p class="text-xs text-warning-700 mt-2 font-medium flex items-center">
                                            <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                                            Complete milestone payment to access this document
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Download Button -->
                            <div class="shrink-0 ml-4">
                                @if($document->is_locked)
                                    <button disabled class="inline-flex items-center gap-2 px-4 py-2.5 bg-neutral-200 text-neutral-400 font-medium text-sm rounded-lg cursor-not-allowed">
                                        <x-lucide-lock class="w-4 h-4" />
                                        Locked
                                    </button>
                                @else
                                    <a href="{{ route('client.documents.download', ['documentId' => $document->documentID]) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white font-medium text-sm rounded-lg hover:bg-primary-700 transition-colors">
                                        <x-lucide-download class="w-4 h-4" />
                                        Download
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <x-ui.pagination :paginator="$documents" />
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-neutral-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <x-lucide-files class="w-10 h-10 text-neutral-400" />
                    </div>
                    <h3 class="text-lg font-medium text-neutral-700 mb-2">No documents found</h3>
                    <p class="text-neutral-500 text-sm mb-6">
                        @if(request()->anyFilled(['search', 'project', 'type']))
                            Try adjusting your search or filters
                        @else
                            Documents from your projects will appear here once uploaded
                        @endif
                    </p>
                    @if(request()->anyFilled(['search', 'project', 'type']))
                        <a href="{{ route('client.documents') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-neutral-100 text-neutral-700 font-medium text-sm rounded-lg hover:bg-neutral-200 transition-colors">
                            <x-lucide-x class="w-4 h-4" />
                            Clear Filters
                        </a>
                    @else
                        <a href="{{ route('client.requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white font-medium text-sm rounded-lg hover:bg-primary-700 transition-colors">
                            <x-lucide-plus class="w-4 h-4" />
                            Start a New Project
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

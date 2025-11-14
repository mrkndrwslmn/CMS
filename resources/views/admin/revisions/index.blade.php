@extends('admin.layouts.app')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-900">Revision Requests</h1>
        <p class="mt-1 text-sm text-neutral-500">Manage and review client revision requests</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg border border-neutral-200 p-5">
            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">All Requests</p>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">{{ $statusCounts['all'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-neutral-200 p-5">
            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Pending</p>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">{{ $statusCounts['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-neutral-200 p-5">
            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Approved</p>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">{{ $statusCounts['approved'] }}</p>
        </div>
        <div class="bg-white rounded-lg border border-neutral-200 p-5">
            <p class="text-xs font-medium text-neutral-500 uppercase tracking-wide">Completed</p>
            <p class="mt-2 text-3xl font-semibold text-neutral-900">{{ $statusCounts['completed'] }}</p>
        </div>
    </div>

    <!-- Requests Table -->
    <div class="bg-white rounded-lg border border-neutral-200 overflow-hidden">
        @if($revisions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-neutral-200">
                    <thead class="bg-neutral-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Client</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Requested</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-neutral-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-neutral-200">
                        @foreach($revisions as $revision)
                        <tr class="hover:bg-neutral-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                #{{ $revision->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                @if($revision->requestedBy)
                                    {{ $revision->requestedBy->fullName }}
                                @else
                                    <span class="text-neutral-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-neutral-600">
                                @if($revision->project)
                                    <a href="{{ route('admin.projects.show', $revision->project->id) }}" class="text-primary-600 hover:text-primary-700 font-medium">
                                        {{ Str::limit($revision->project->projectName, 30) }}
                                    </a>
                                @else
                                    <span class="text-neutral-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($revision->source_type === 'project')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-800">
                                        Project
                                    </span>
                                @elseif($revision->source_type === 'task')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-800">
                                        Task
                                    </span>
                                @elseif($revision->source_type === 'document')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-800">
                                        Document
                                    </span>
                                @endif
                                @if($revision->priority === 'urgent' || $revision->priority === 'high')
                                    <span class="ml-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-neutral-900 text-white">
                                        {{ ucfirst($revision->priority) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($revision->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-800">
                                        Pending
                                    </span>
                                @elseif($revision->status === 'approved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-900 text-white">
                                        Approved
                                    </span>
                                @elseif($revision->status === 'rejected')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-800">
                                        Rejected
                                    </span>
                                @elseif($revision->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-900 text-white">
                                        Completed
                                    </span>
                                @elseif($revision->status === 'cancelled')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-neutral-100 text-neutral-800">
                                        Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-600">
                                {{ $revision->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.revisions.show', $revision->id) }}" 
                                   class="inline-flex items-center text-neutral-700 hover:text-neutral-900 font-medium">
                                    View
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-neutral-200">
                {{ $revisions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                          d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <h3 class="mt-4 text-sm font-medium text-neutral-900">No revision requests</h3>
                <p class="mt-1 text-sm text-neutral-500">Revision requests from clients will appear here.</p>
            </div>
        @endif
    </div>
</div>
@endsection

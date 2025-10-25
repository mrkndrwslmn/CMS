@extends('adiutor.layouts.app')

@section('title', 'My Clients')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Clients</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('adiutor.dashboard') }}" class="hover:text-primary-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Clients</span>
                </nav>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Total Clients</p>
                <p class="text-3xl font-bold text-primary-500">{{ count($clients) }}</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Clients -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Total Clients</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ count($clients) }}</p>
                </div>
            </div>
        </div>

        <!-- Total Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-success-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Total Projects</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ collect($clients)->sum('total_projects') }}</p>
                </div>
            </div>
        </div>

        <!-- Completed Projects -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-warning-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-warning-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Completed</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ collect($clients)->sum('completed_projects') }}</p>
                </div>
            </div>
        </div>

        <!-- Active Clients -->
        <div class="glass-card p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-neutral-600">Active Clients</p>
                    <p class="text-2xl font-bold text-neutral-900">{{ collect($clients)->where('total_projects', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Card Header -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-briefcase text-gray-600 mr-2"></i>
                    Client List
                </h2>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search clients..." 
                               class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Clients Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="clientsGrid">
                @forelse($clients as $client)
                    <div class="client-card bg-gradient-to-br from-white to-gray-50 rounded-xl border border-gray-200 p-6 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-1">
                        <!-- Client Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($client->fullName, 0, 1)) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 client-name">{{ $client->fullName }}</h3>
                                    <p class="text-xs text-gray-500">Client ID: #{{ $client->id }}</p>
                                </div>
                            </div>
                            @if(isset($client->total_projects) && $client->total_projects > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-circle text-green-500 text-[6px] mr-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-envelope text-primary-500 w-5"></i>
                                <span class="ml-2 truncate">{{ $client->email }}</span>
                            </div>
                            @if($client->phoneNumber)
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-phone text-primary-500 w-5"></i>
                                    <span class="ml-2">{{ $client->phoneNumber }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Project Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-4 pt-4 border-t border-gray-200">
                            <div class="text-center">
                                <p class="text-2xl font-bold text-blue-600">{{ $client->total_projects ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Total Projects</p>
                            </div>
                            <div class="text-center">
                                <p class="text-2xl font-bold text-green-600">{{ $client->completed_projects ?? 0 }}</p>
                                <p class="text-xs text-gray-500">Completed</p>
                            </div>
                        </div>

                        <!-- Last Project Date -->
                        @if($client->last_project_date)
                            <div class="text-xs text-gray-500 mb-4">
                                <i class="fas fa-calendar-alt mr-1"></i>
                                Last project: {{ \Carbon\Carbon::parse($client->last_project_date)->format('M d, Y') }}
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
<<<<<<< HEAD
                            <a href="{{ route('adiutor.tasks.index') }}?client={{ $client->id }}" 
=======
                            <a href="{{ route('adiutor.tasks') }}?client={{ $client->id }}" 
>>>>>>> 7c71488 (Initial commit from Princess)
                               class="flex-1 px-4 py-2 bg-primary-500 text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors text-center">
                                <i class="fas fa-tasks mr-1"></i>
                                View Tasks
                            </a>
                            <button class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors"
                                    onclick="showClientDetails({{ json_encode($client) }})">
                                <i class="fas fa-info-circle"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-16 text-gray-400">
                        <i class="fas fa-users text-6xl mb-4"></i>
                        <p class="text-lg font-medium text-gray-500">No clients found</p>
                        <p class="text-sm mt-1">You haven't been assigned to any clients yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Client Details Modal -->
<div id="clientDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 items-center justify-center p-4" style="display: none;">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full transform transition-all">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-900">Client Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeClientDetails()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>
        <div class="px-6 py-6" id="clientDetailsContent">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex justify-end">
            <button type="button" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors"
                    onclick="closeClientDetails()">
                Close
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/adiutor/clients.js') }}"></script>
@endsection

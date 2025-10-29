@extends('adiutor.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">My Profile</h1>
                <p class="text-sm text-gray-500 mt-1">Manage your professional information and settings</p>
            </div>
            <a href="{{ route('adiutor.profile.edit') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit Profile
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <!-- Profile Header -->
                <div class="relative bg-gradient-to-r from-primary-500 to-primary-600 h-24"></div>
                
                <!-- Profile Content -->
                <div class="relative px-6 pb-6">
                    <!-- Avatar -->
                    <div class="flex justify-center -mt-12 mb-4">
                        @if($user->profilePic)
                            <img src="{{ $user->getProfilePictureUrl() }}" 
                                 alt="{{ $user->fullName }}" 
                                 class="w-24 h-24 rounded-full border-4 border-white object-cover shadow-sm">
                        @else
                            <div class="w-24 h-24 rounded-full border-4 border-white bg-primary-600 flex items-center justify-center text-white text-2xl font-medium shadow-sm">
                                {{ strtoupper(substr($user->fullName, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- User Info -->
                    <div class="text-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-1">{{ $user->fullName }}</h2>
                        @if($profile && $profile->title)
                            <p class="text-primary-600 font-medium text-sm mb-2">{{ $profile->title }}</p>
                        @endif
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            <svg class="w-1.5 h-1.5 mr-1.5" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3"></circle>
                            </svg>
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <span class="truncate">{{ $user->email }}</span>
                        </div>
                        
                        @if($user->phoneNumber)
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <span>{{ $user->phoneNumber }}</span>
                        </div>
                        @endif
                        
                        @if($profile && $profile->location)
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $profile->location }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Member Since -->
                    <div class="border-t border-gray-200 pt-4 mt-6">
                        <p class="text-xs text-gray-500 mb-1">Member Since</p>
                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($user->created_at)->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            @if($profile && ($profile->hourly_rate || $profile->years_of_experience || $profile->availability_status))
            <div class="bg-white rounded-xl border border-gray-200 p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    @if($profile->hourly_rate)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Hourly Rate</p>
                        <p class="text-2xl font-bold text-primary-600">${{ number_format($profile->hourly_rate, 2) }}</p>
                    </div>
                    @endif
                    
                    @if($profile->years_of_experience)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Experience</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $profile->years_of_experience }} <span class="text-sm font-normal">years</span></p>
                    </div>
                    @endif
                    
                    @if($profile->availability_status)
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Availability</p>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $profile->availability_status === 'available' ? 'bg-green-100 text-green-800' : ($profile->availability_status === 'busy' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($profile->availability_status) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-6">
            <!-- About Section -->
            @if($profile && $profile->bio)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">About</h3>
                <p class="text-gray-700 leading-relaxed">{{ $profile->bio }}</p>
            </div>
            @endif

            <!-- Skills Section -->
            @if($skills->count() > 0)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Skills & Expertise</h3>
                <div class="grid gap-4">
                    @foreach($skills as $skill)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-gray-900">{{ $skill->name }}</h4>
                                <div class="flex items-center gap-3">
                                    @if($skill->years_experience)
                                    <span class="text-xs text-gray-500">{{ $skill->years_experience }} years</span>
                                    @endif
                                    @if($skill->proficiency)
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium
                                        {{ $skill->proficiency === 'expert' ? 'bg-primary-100 text-primary-800' : 
                                           ($skill->proficiency === 'advanced' ? 'bg-blue-100 text-blue-800' : 
                                           ($skill->proficiency === 'intermediate' ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-600')) }}">
                                        {{ ucfirst($skill->proficiency) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @if($skill->proficiency)
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-300 {{ $skill->proficiency === 'expert' ? 'bg-primary-600' : ($skill->proficiency === 'advanced' ? 'bg-blue-500' : ($skill->proficiency === 'intermediate' ? 'bg-gray-400' : 'bg-gray-300')) }}"
                                     style="width: {{ $skill->proficiency === 'expert' ? '100' : ($skill->proficiency === 'advanced' ? '80' : ($skill->proficiency === 'intermediate' ? '60' : '40')) }}%">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Professional Information -->
            @if($profile)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Professional Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if($profile->job_title)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Job Title</p>
                        <p class="text-gray-900">{{ $profile->job_title }}</p>
                    </div>
                    @endif
                    
                    @if($profile->company_name)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Company</p>
                        <p class="text-gray-900">{{ $profile->company_name }}</p>
                    </div>
                    @endif
                    
                    @if($profile->years_of_experience)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Experience</p>
                        <p class="text-gray-900">{{ $profile->years_of_experience }} years</p>
                    </div>
                    @endif
                    
                    @if($profile->hourly_rate)
                    <div>
                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Hourly Rate</p>
                        <p class="text-gray-900">${{ number_format($profile->hourly_rate, 2) }}/hr</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Social Links -->
            @if($profile && ($profile->linkedin_url || $profile->github_url || $profile->portfolio_url || $profile->website_url))
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Links & Portfolio</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($profile->linkedin_url)
                    <a href="{{ $profile->linkedin_url }}" target="_blank" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-primary-300 hover:bg-primary-50 transition-colors group">
                        <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-primary-200 transition-colors">
                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">LinkedIn</p>
                            <p class="text-xs text-gray-500">View profile</p>
                        </div>
                    </a>
                    @endif
                    
                    @if($profile->github_url)
                    <a href="{{ $profile->github_url }}" target="_blank" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-gray-400 hover:bg-gray-50 transition-colors group">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-gray-200 transition-colors">
                            <svg class="w-5 h-5 text-gray-700" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">GitHub</p>
                            <p class="text-xs text-gray-500">View repositories</p>
                        </div>
                    </a>
                    @endif
                    
                    @if($profile->portfolio_url)
                    <a href="{{ $profile->portfolio_url }}" target="_blank" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-orange-300 hover:bg-orange-50 transition-colors group">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-orange-200 transition-colors">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Portfolio</p>
                            <p class="text-xs text-gray-500">View work</p>
                        </div>
                    </a>
                    @endif
                    
                    @if($profile->website_url)
                    <a href="{{ $profile->website_url }}" target="_blank" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition-colors group">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:bg-blue-200 transition-colors">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Website</p>
                            <p class="text-xs text-gray-500">Visit site</p>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

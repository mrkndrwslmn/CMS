@extends('adiutor.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">My Profile</h1>
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-2">
                    <a href="{{ route('adiutor.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-neutral-900">Profile</span>
                </nav>
            </div>
            <a href="{{ route('adiutor.profile.edit') }}" class="px-5 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-md">
                <i class="fas fa-edit mr-2"></i>
                Edit Profile
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Profile Card -->
        <div class="lg:col-span-1">
            <!-- Profile Card -->
            <div class="glass-card p-6 mb-6">
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="mb-4">
                        @if($user->profilePic)
                            <img src="{{ $user->getProfilePictureUrl() }}" alt="{{ $user->fullName }}" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-primary-100">
                        @else
                            <div class="w-32 h-32 rounded-full mx-auto bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-primary-100">
                                {{ strtoupper(substr($user->fullName, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Name and Role -->
                    <h2 class="text-2xl font-bold text-neutral-900 mb-1">{{ $user->fullName }}</h2>
                    <p class="text-sm text-neutral-600 mb-2">{{ ucfirst($user->role) }}</p>
                    
                    @if($profile && $profile->title)
                        <p class="text-sm text-primary-600 font-medium mb-4">{{ $profile->title }}</p>
                    @endif

                    <!-- Account Status -->
                    <div class="flex items-center justify-center space-x-2 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($user->status === 'active') bg-success-100 text-success-800
                            @else bg-neutral-100 text-neutral-800 @endif">
                            <i class="fas fa-circle text-[6px] mr-2"></i>
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>

                    <!-- Contact Info -->
                    <div class="space-y-3 text-left border-t border-neutral-200 pt-4">
                        <div class="flex items-center text-sm text-neutral-700">
                            <i class="fas fa-envelope text-primary-600 w-5 mr-3"></i>
                            <span class="truncate">{{ $user->email }}</span>
                        </div>
                        @if($user->phoneNumber)
                            <div class="flex items-center text-sm text-neutral-700">
                                <i class="fas fa-phone text-primary-600 w-5 mr-3"></i>
                                <span>{{ $user->phoneNumber }}</span>
                            </div>
                        @endif
                        @if($profile && $profile->location)
                            <div class="flex items-center text-sm text-neutral-700">
                                <i class="fas fa-map-marker-alt text-primary-600 w-5 mr-3"></i>
                                <span>{{ $profile->location }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Member Since -->
                    <div class="border-t border-neutral-200 pt-4 mt-4">
                        <p class="text-xs text-neutral-500">Member Since</p>
                        <p class="text-sm font-medium text-neutral-700">{{ \Carbon\Carbon::parse($user->created_at)->format('F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            @if($profile)
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4">Quick Stats</h3>
                <div class="space-y-4">
                    @if(isset($profile->hourly_rate) && $profile->hourly_rate)
                        <div>
                            <p class="text-sm text-neutral-600 mb-1">Hourly Rate</p>
                            <p class="text-xl font-bold text-primary-600">${{ number_format($profile->hourly_rate, 2) }}/hr</p>
                        </div>
                    @endif
                    @if(isset($profile->years_of_experience) && $profile->years_of_experience)
                        <div>
                            <p class="text-sm text-neutral-600 mb-1">Experience</p>
                            <p class="text-xl font-bold text-neutral-900">{{ $profile->years_of_experience }} years</p>
                        </div>
                    @endif
                    @if(isset($profile->availability_status) && $profile->availability_status)
                        <div>
                            <p class="text-sm text-neutral-600 mb-1">Availability</p>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-success-100 text-success-800">
                                {{ ucfirst($profile->availability_status) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Details -->
        <div class="lg:col-span-2">
            <!-- About Section -->
            @if($profile && $profile->bio)
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-user text-primary-600 mr-2"></i>
                    About Me
                </h3>
                <p class="text-neutral-700 leading-relaxed">{{ $profile->bio }}</p>
            </div>
            @endif

            <!-- Skills Section -->
            @if($skills->count() > 0)
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-code text-primary-600 mr-2"></i>
                    Skills & Expertise
                </h3>
                <div class="space-y-4">
                    @foreach($skills as $skill)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-neutral-900">{{ $skill->name }}</span>
                                <div class="flex items-center space-x-3">
                                    @if($skill->years_experience)
                                        <span class="text-xs text-neutral-600">{{ $skill->years_experience }} years</span>
                                    @endif
                                    @if($skill->proficiency)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($skill->proficiency === 'expert') bg-success-100 text-success-800
                                            @elseif($skill->proficiency === 'advanced') bg-primary-100 text-primary-800
                                            @elseif($skill->proficiency === 'intermediate') bg-warning-100 text-warning-800
                                            @else bg-neutral-100 text-neutral-800 @endif">
                                            {{ ucfirst($skill->proficiency) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @if($skill->proficiency)
                                <div class="w-full bg-neutral-200 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-300
                                        @if($skill->proficiency === 'expert') bg-success-600
                                        @elseif($skill->proficiency === 'advanced') bg-primary-600
                                        @elseif($skill->proficiency === 'intermediate') bg-warning-600
                                        @else bg-neutral-600 @endif"
                                        style="width: {{ $skill->proficiency === 'expert' ? '100' : ($skill->proficiency === 'advanced' ? '80' : ($skill->proficiency === 'intermediate' ? '60' : '40')) }}%">
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Professional Information -->
            @if($profile)
            <div class="glass-card p-6 mb-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-briefcase text-primary-600 mr-2"></i>
                    Professional Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if(isset($profile->job_title) && $profile->job_title)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Job Title</p>
                            <p class="text-base text-neutral-900">{{ $profile->job_title }}</p>
                        </div>
                    @endif
                    @if(isset($profile->company_name) && $profile->company_name)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Company</p>
                            <p class="text-base text-neutral-900">{{ $profile->company_name }}</p>
                        </div>
                    @endif
                    @if(isset($profile->years_of_experience) && $profile->years_of_experience)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Years of Experience</p>
                            <p class="text-base text-neutral-900">{{ $profile->years_of_experience }} years</p>
                        </div>
                    @endif
                    @if(isset($profile->hourly_rate) && $profile->hourly_rate)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Hourly Rate</p>
                            <p class="text-base text-neutral-900">${{ number_format($profile->hourly_rate, 2) }}/hr</p>
                        </div>
                    @endif
                    @if(isset($profile->availability_status) && $profile->availability_status)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Availability</p>
                            <p class="text-base text-neutral-900">{{ ucfirst($profile->availability_status) }}</p>
                        </div>
                    @endif
                    @if(isset($profile->location) && $profile->location)
                        <div>
                            <p class="text-sm font-medium text-neutral-500 mb-1">Location</p>
                            <p class="text-base text-neutral-900">{{ $profile->location }}</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Social Links -->
            @if($profile && (
                (isset($profile->linkedin_url) && $profile->linkedin_url) || 
                (isset($profile->github_url) && $profile->github_url) || 
                (isset($profile->portfolio_url) && $profile->portfolio_url) || 
                (isset($profile->website_url) && $profile->website_url)
            ))
            <div class="glass-card p-6">
                <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                    <i class="fas fa-link text-primary-600 mr-2"></i>
                    Links & Portfolio
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if(isset($profile->linkedin_url) && $profile->linkedin_url)
                        <a href="{{ $profile->linkedin_url }}" target="_blank" class="flex items-center p-3 bg-neutral-50 rounded-lg hover:bg-neutral-100 transition-colors">
                            <i class="fab fa-linkedin text-primary-600 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">LinkedIn</p>
                                <p class="text-xs text-neutral-500">View profile</p>
                            </div>
                        </a>
                    @endif
                    @if(isset($profile->github_url) && $profile->github_url)
                        <a href="{{ $profile->github_url }}" target="_blank" class="flex items-center p-3 bg-neutral-50 rounded-lg hover:bg-neutral-100 transition-colors">
                            <i class="fab fa-github text-neutral-900 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">GitHub</p>
                                <p class="text-xs text-neutral-500">View repositories</p>
                            </div>
                        </a>
                    @endif
                    @if(isset($profile->portfolio_url) && $profile->portfolio_url)
                        <a href="{{ $profile->portfolio_url }}" target="_blank" class="flex items-center p-3 bg-neutral-50 rounded-lg hover:bg-neutral-100 transition-colors">
                            <i class="fas fa-folder text-warning-600 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">Portfolio</p>
                                <p class="text-xs text-neutral-500">View work</p>
                            </div>
                        </a>
                    @endif
                    @if(isset($profile->website_url) && $profile->website_url)
                        <a href="{{ $profile->website_url }}" target="_blank" class="flex items-center p-3 bg-neutral-50 rounded-lg hover:bg-neutral-100 transition-colors">
                            <i class="fas fa-globe text-accent-600 text-xl mr-3"></i>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">Website</p>
                                <p class="text-xs text-neutral-500">Visit site</p>
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

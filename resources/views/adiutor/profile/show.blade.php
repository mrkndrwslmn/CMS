@extends('adiutor.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'My Profile'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">My Profile</h1>
                <p class="text-sm text-neutral-500 mt-1">Manage your professional information and settings</p>
            </div>
            <a href="{{ route('adiutor.profile.edit') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">
                <x-lucide-pencil class="w-4 h-4" />
                Edit Profile
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Profile Card -->
        <div class="lg:col-span-4">
            <x-ui.card class="overflow-hidden">
                <!-- Profile Header -->
                <div class="relative bg-primary-600 h-24"></div>
                
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
                        <h2 class="text-xl font-semibold text-neutral-800 mb-1">{{ $user->fullName }}</h2>
                        @if($profile && $profile->title)
                            <p class="text-primary-600 font-medium text-sm mb-2">{{ $profile->title }}</p>
                        @endif
                        <x-ui.badge :variant="$user->status === 'active' ? 'success' : 'neutral'">
                            {{ ucfirst($user->status) }}
                        </x-ui.badge>
                    </div>

                    <!-- Contact Information -->
                    <div class="space-y-3">
                        <div class="flex items-center text-sm text-neutral-600">
                            <x-lucide-mail class="w-4 h-4 mr-3 text-neutral-400" />
                            <span class="truncate">{{ $user->email }}</span>
                        </div>
                        
                        @if($user->phoneNumber)
                        <div class="flex items-center text-sm text-neutral-600">
                            <x-lucide-phone class="w-4 h-4 mr-3 text-neutral-400" />
                            <span>{{ $user->phoneNumber }}</span>
                        </div>
                        @endif
                        
                        @if($profile && $profile->location)
                        <div class="flex items-center text-sm text-neutral-600">
                            <x-lucide-map-pin class="w-4 h-4 mr-3 text-neutral-400" />
                            <span>{{ $profile->location }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Member Since -->
                    <div class="border-t border-neutral-100 pt-4 mt-6">
                        <p class="text-xs text-neutral-500 mb-1">Member Since</p>
                        <p class="text-sm font-medium text-neutral-800">{{ \Carbon\Carbon::parse($user->created_at)->format('F Y') }}</p>
                    </div>
                </div>
            </x-ui.card>

            <!-- Quick Stats -->
            @if($profile && $profile->standard_hourly_rate)
            <x-ui.card class="p-6 mt-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4">Earnings Info</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Standard Hourly Rate</p>
                        <p class="text-2xl font-bold text-primary-600">₱{{ number_format($profile->standard_hourly_rate, 2) }}</p>
                    </div>
                    @if($profile->minimum_payout_amount)
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Minimum Payout Amount</p>
                        <p class="text-lg font-semibold text-neutral-800">₱{{ number_format($profile->minimum_payout_amount, 2) }}</p>
                    </div>
                    @endif
                    @if($profile->preferred_payout_method)
                    <div>
                        <p class="text-xs text-neutral-500 mb-1">Preferred Payout Method</p>
                        <x-ui.badge variant="primary">
                            {{ ucfirst(str_replace('_', ' ', $profile->preferred_payout_method)) }}
                        </x-ui.badge>
                    </div>
                    @endif
                </div>
            </x-ui.card>
            @endif
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-8 space-y-6">
            <!-- About Section -->
            @if($profile && $profile->bio)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4">About</h3>
                <p class="text-neutral-600 leading-relaxed">{{ $profile->bio }}</p>
            </x-ui.card>
            @endif

            <!-- Skills Section -->
            @if($skills->count() > 0)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-6">Skills & Expertise</h3>
                <div class="grid gap-4">
                    @foreach($skills as $skill)
                    <div class="flex items-center justify-between p-4 bg-neutral-50 rounded-xl">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-medium text-neutral-800">{{ $skill->name }}</h4>
                                <div class="flex items-center gap-3">
                                    @if($skill->years_experience)
                                    <span class="text-xs text-neutral-500">{{ $skill->years_experience }} years</span>
                                    @endif
                                    @if($skill->proficiency)
                                    <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium
                                        {{ $skill->proficiency === 'expert' ? 'bg-primary-100 text-primary-800' : 
                                           ($skill->proficiency === 'advanced' ? 'bg-primary-50 text-primary-700' : 
                                           ($skill->proficiency === 'intermediate' ? 'bg-neutral-100 text-neutral-700' : 'bg-neutral-100 text-neutral-500')) }}">
                                        {{ ucfirst($skill->proficiency) }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            @if($skill->proficiency)
                            <div class="w-full bg-neutral-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-300 {{ $skill->proficiency === 'expert' ? 'bg-primary-600' : ($skill->proficiency === 'advanced' ? 'bg-primary-500' : ($skill->proficiency === 'intermediate' ? 'bg-neutral-400' : 'bg-neutral-300')) }}"
                                     style="width: {{ $skill->proficiency === 'expert' ? '100' : ($skill->proficiency === 'advanced' ? '80' : ($skill->proficiency === 'intermediate' ? '60' : '40')) }}%">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-ui.card>
            @endif

            <!-- Experience Section -->
            @if($profile && $profile->experience)
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-4">Experience</h3>
                <p class="text-neutral-600 leading-relaxed whitespace-pre-line">{{ $profile->experience }}</p>
            </x-ui.card>
            @endif

            <!-- Social Links -->
            @if($profile && ($profile->linkedin_url || $profile->github_url || $profile->portfolio_url))
            <x-ui.card class="p-6">
                <h3 class="text-lg font-semibold text-neutral-800 mb-6">Links & Portfolio</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($profile->linkedin_url)
                    <a href="{{ $profile->linkedin_url }}" target="_blank" 
                       class="flex items-center p-4 border border-neutral-200 rounded-xl hover:border-primary-300 hover:bg-primary-50 transition-colors group">
                        <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center mr-3 group-hover:bg-primary-200 transition-colors">
                            <x-lucide-linkedin class="w-5 h-5 text-primary-600" />
                        </div>
                        <div>
                            <p class="font-medium text-neutral-800">LinkedIn</p>
                            <p class="text-xs text-neutral-500">View profile</p>
                        </div>
                    </a>
                    @endif
                    
                    @if($profile->github_url)
                    <a href="{{ $profile->github_url }}" target="_blank" 
                       class="flex items-center p-4 border border-neutral-200 rounded-xl hover:border-neutral-400 hover:bg-neutral-50 transition-colors group">
                        <div class="w-10 h-10 bg-neutral-100 rounded-xl flex items-center justify-center mr-3 group-hover:bg-neutral-200 transition-colors">
                            <x-lucide-github class="w-5 h-5 text-neutral-700" />
                        </div>
                        <div>
                            <p class="font-medium text-neutral-800">GitHub</p>
                            <p class="text-xs text-neutral-500">View repositories</p>
                        </div>
                    </a>
                    @endif
                    
                    @if($profile->portfolio_url)
                    <a href="{{ $profile->portfolio_url }}" target="_blank" 
                       class="flex items-center p-4 border border-neutral-200 rounded-xl hover:border-warning-300 hover:bg-warning-50 transition-colors group">
                        <div class="w-10 h-10 bg-warning-100 rounded-xl flex items-center justify-center mr-3 group-hover:bg-warning-200 transition-colors">
                            <x-lucide-briefcase class="w-5 h-5 text-warning-600" />
                        </div>
                        <div>
                            <p class="font-medium text-neutral-800">Portfolio</p>
                            <p class="text-xs text-neutral-500">View work</p>
                        </div>
                    </a>
                    @endif
                </div>
            </x-ui.card>
            @endif
        </div>
    </div>
</div>
@endsection

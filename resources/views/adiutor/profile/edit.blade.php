@extends('adiutor.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-neutral-900">Edit Profile</h1>
                <nav class="flex items-center space-x-2 text-sm text-neutral-500 mt-2">
                    <a href="{{ route('adiutor.dashboard') }}" class="hover:text-primary-600 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <a href="{{ route('adiutor.profile.show') }}" class="hover:text-primary-600 transition-colors">Profile</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-neutral-900">Edit</span>
                </nav>
            </div>
            <a href="{{ route('adiutor.profile.show') }}" class="px-5 py-2.5 bg-neutral-600 text-white font-medium rounded-lg hover:bg-neutral-700 transition-colors shadow-md">
                <i class="fas fa-times mr-2"></i>
                Cancel
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-success-50 border border-success-200 text-success-800 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
            <div class="flex items-center mb-2">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <strong>Please fix the following errors:</strong>
            </div>
            <ul class="list-disc list-inside ml-6 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('adiutor.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Profile Picture & Basic Info -->
            <div class="lg:col-span-1">
                <!-- Profile Picture -->
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-camera text-primary-600 mr-2"></i>
                        Profile Picture
                    </h3>
                    <div class="text-center">
                        <div class="mb-4">
                            @if($user->profilePicture)
                                <img src="{{ asset('storage/' . $user->profilePicture) }}" alt="{{ $user->fullName }}" class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-primary-100" id="profilePreview">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-primary-100" id="profilePreview">
                                    {{ strtoupper(substr($user->fullName, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <input type="file" name="profile_picture" id="profilePictureInput" class="hidden" accept="image/*">
                        <button type="button" onclick="document.getElementById('profilePictureInput').click()" class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <i class="fas fa-upload mr-2"></i>
                            Change Picture
                        </button>
                        <p class="text-xs text-neutral-500 mt-2">Max size: 2MB (JPG, PNG)</p>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="glass-card p-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-user text-primary-600 mr-2"></i>
                        Basic Information
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Full Name</label>
                            <input type="text" value="{{ $user->fullName }}" disabled class="w-full px-4 py-2 border border-neutral-300 rounded-lg bg-neutral-50 text-neutral-600 cursor-not-allowed">
                            <p class="text-xs text-neutral-500 mt-1">Contact admin to change your name</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-2 border border-neutral-300 rounded-lg bg-neutral-50 text-neutral-600 cursor-not-allowed">
                            <p class="text-xs text-neutral-500 mt-1">Contact admin to change your email</p>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-neutral-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phoneNumber) }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Profile Details -->
            <div class="lg:col-span-2">
                <!-- Professional Information -->
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-briefcase text-primary-600 mr-2"></i>
                        Professional Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="job_title" class="block text-sm font-medium text-neutral-700 mb-2">Job Title</label>
                            <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $profile->job_title ?? '') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g. Senior Developer">
                        </div>
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-neutral-700 mb-2">Company</label>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $profile->company_name ?? '') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g. Tech Corp">
                        </div>
                        <div>
                            <label for="years_of_experience" class="block text-sm font-medium text-neutral-700 mb-2">Years of Experience</label>
                            <input type="number" id="years_of_experience" name="years_of_experience" value="{{ old('years_of_experience', $profile->years_of_experience ?? '') }}" min="0" max="50" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0">
                        </div>
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-neutral-700 mb-2">Hourly Rate ($)</label>
                            <input type="number" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate ?? '') }}" min="0" step="0.01" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0.00">
                        </div>
                        <div>
                            <label for="availability_status" class="block text-sm font-medium text-neutral-700 mb-2">Availability</label>
                            <select id="availability_status" name="availability_status" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                <option value="">Select availability</option>
                                <option value="available" {{ old('availability_status', $profile->availability_status ?? '') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="busy" {{ old('availability_status', $profile->availability_status ?? '') == 'busy' ? 'selected' : '' }}>Busy</option>
                                <option value="unavailable" {{ old('availability_status', $profile->availability_status ?? '') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                            </select>
                        </div>
                        <div>
                            <label for="location" class="block text-sm font-medium text-neutral-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $profile->location ?? '') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g. New York, USA">
                        </div>
                    </div>
                </div>

                <!-- About Me -->
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-align-left text-primary-600 mr-2"></i>
                        About Me
                    </h3>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-neutral-700 mb-2">Biography</label>
                        <textarea id="bio" name="bio" rows="6" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="Tell us about yourself, your experience, and what you're passionate about...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        <p class="text-xs text-neutral-500 mt-1">Max 1000 characters</p>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-code text-primary-600 mr-2"></i>
                        Skills & Expertise
                    </h3>
                    <div id="skillsContainer">
                        @if($skills->count() > 0)
                            @foreach($skills as $index => $skill)
                                <div class="skill-item mb-4 p-4 bg-neutral-50 rounded-lg border border-neutral-200">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-neutral-700 mb-2">Skill Name</label>
                                                <input type="text" name="skills[{{ $index }}][name]" value="{{ $skill->name }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="e.g. JavaScript">
                                                <input type="hidden" name="skills[{{ $index }}][skill_id]" value="{{ $skill->id }}">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-neutral-700 mb-2">Proficiency</label>
                                                <select name="skills[{{ $index }}][proficiency]" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                                    <option value="beginner" {{ $skill->proficiency == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="intermediate" {{ $skill->proficiency == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="advanced" {{ $skill->proficiency == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                                    <option value="expert" {{ $skill->proficiency == 'expert' ? 'selected' : '' }}>Expert</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-neutral-700 mb-2">Years</label>
                                                <input type="number" name="skills[{{ $index }}][years_experience]" value="{{ $skill->years_experience }}" min="0" max="50" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="0">
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeSkill(this)" class="ml-4 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" id="addSkillBtn" class="px-4 py-2 bg-primary-100 text-primary-700 font-medium rounded-lg hover:bg-primary-200 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Add Skill
                    </button>
                </div>

                <!-- Social Links -->
                <div class="glass-card p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-900 mb-4 flex items-center">
                        <i class="fas fa-link text-primary-600 mr-2"></i>
                        Social Links & Portfolio
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-neutral-700 mb-2">
                                <i class="fab fa-linkedin text-primary-600 mr-2"></i>
                                LinkedIn URL
                            </label>
                            <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                        <div>
                            <label for="github_url" class="block text-sm font-medium text-neutral-700 mb-2">
                                <i class="fab fa-github text-neutral-900 mr-2"></i>
                                GitHub URL
                            </label>
                            <input type="url" id="github_url" name="github_url" value="{{ old('github_url', $profile->github_url ?? '') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="https://github.com/yourusername">
                        </div>
                        <div>
                            <label for="portfolio_url" class="block text-sm font-medium text-neutral-700 mb-2">
                                <i class="fas fa-folder text-warning-600 mr-2"></i>
                                Portfolio URL
                            </label>
                            <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url ?? '') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="https://yourportfolio.com">
                        </div>
                        <div>
                            <label for="website_url" class="block text-sm font-medium text-neutral-700 mb-2">
                                <i class="fas fa-globe text-accent-600 mr-2"></i>
                                Website URL
                            </label>
                            <input type="url" id="website_url" name="website_url" value="{{ old('website_url', $profile->website_url ?? '') }}" class="w-full px-4 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" placeholder="https://yourwebsite.com">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('adiutor.profile.show') }}" class="px-6 py-3 bg-neutral-200 text-neutral-700 font-medium rounded-lg hover:bg-neutral-300 transition-colors">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors shadow-md">
                        <i class="fas fa-save mr-2"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="{{ asset('js/adiutor/profile-edit.js') }}"></script>
@endsection

@extends('adiutor.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Profile</h1>
                <p class="text-sm text-gray-500 mt-1">Update your professional information and settings</p>
            </div>
            <a href="{{ route('adiutor.profile.show') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancel
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    <!-- Error Messages -->
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">
        <div class="flex items-center mb-2">
            <svg class="w-5 h-5 mr-3 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <strong>Please fix the following errors:</strong>
        </div>
        <ul class="list-disc list-inside ml-8 space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('adiutor.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column - Profile Picture & Basic Info -->
            <div class="lg:col-span-4">
                <!-- Profile Picture -->
                <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Picture</h3>
                    <div class="text-center">
                        <div class="mb-4">
                            @if($user->profilePicture)
                                <img src="{{ $user->getProfilePictureUrl() }}" 
                                     alt="{{ $user->fullName }}" 
                                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-100" 
                                     id="profilePreview">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-primary-600 flex items-center justify-center text-white text-3xl font-medium border-4 border-gray-100" 
                                     id="profilePreview">
                                    {{ strtoupper(substr($user->fullName, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <input type="file" name="profile_picture" id="profilePictureInput" class="hidden" accept="image/*">
                        <button type="button" onclick="document.getElementById('profilePictureInput').click()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Change Picture
                        </button>
                        <p class="text-xs text-gray-500 mt-2">JPG, PNG up to 2MB</p>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" value="{{ $user->fullName }}" disabled 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Contact admin to change your name</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="text-xs text-gray-500 mt-1">Contact admin to change your email</p>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phoneNumber) }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="+1 (555) 000-0000">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Profile Details -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Professional Information -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Professional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Professional Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $profile->title ?? '') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="e.g. Senior Developer">
                        </div>
                        
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $profile->location ?? '') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="e.g. Manila, Philippines">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            For earnings settings including hourly rate, please visit the 
                            <a href="{{ route('adiutor.profile.earnings') }}" class="text-primary-600 hover:text-primary-700 font-medium">Earnings Settings</a> page.
                        </p>
                    </div>
                </div>

                <!-- About Me -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">About Me</h3>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Biography</label>
                        <textarea id="bio" name="bio" rows="6" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                  placeholder="Tell us about yourself, your experience, and what you're passionate about...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Maximum 1000 characters</p>
                    </div>
                </div>

                <!-- Skills Section -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Skills & Expertise</h3>
                        <button type="button" id="addSkillBtn" 
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 text-primary-600 text-sm font-medium rounded-lg hover:bg-primary-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Skill
                        </button>
                    </div>
                    
                    <div id="skillsContainer" class="space-y-4">
                        @if($skills->count() > 0)
                            @foreach($skills as $index => $skill)
                                <div class="skill-item p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Skill Name</label>
                                                <input type="text" name="skills[{{ $index }}][name]" value="{{ $skill->name }}" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm" 
                                                       placeholder="e.g. JavaScript">
                                                <input type="hidden" name="skills[{{ $index }}][skill_id]" value="{{ $skill->id }}">
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Proficiency</label>
                                                <select name="skills[{{ $index }}][proficiency]" 
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm">
                                                    <option value="beginner" {{ $skill->proficiency == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="intermediate" {{ $skill->proficiency == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="advanced" {{ $skill->proficiency == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                                    <option value="expert" {{ $skill->proficiency == 'expert' ? 'selected' : '' }}>Expert</option>
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Years</label>
                                                <input type="number" name="skills[{{ $index }}][years_experience]" value="{{ $skill->years_experience }}" 
                                                       min="0" max="50" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm" 
                                                       placeholder="0">
                                            </div>
                                        </div>
                                        
                                        <button type="button" onclick="removeSkill(this)" 
                                                class="ml-4 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Social Links & Portfolio</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-2">
                                LinkedIn URL
                            </label>
                            <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                        
                        <div>
                            <label for="github_url" class="block text-sm font-medium text-gray-700 mb-2">
                                GitHub URL
                            </label>
                            <input type="url" id="github_url" name="github_url" value="{{ old('github_url', $profile->github_url ?? '') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="https://github.com/yourusername">
                        </div>
                        
                        <div>
                            <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-2">
                                Portfolio URL
                            </label>
                            <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url ?? '') }}" 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="https://yourportfolio.com">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('adiutor.profile.show') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile picture preview
    const profileInput = document.getElementById('profilePictureInput');
    const profilePreview = document.getElementById('profilePreview');
    
    if (profileInput) {
        profileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profilePreview.tagName === 'IMG') {
                        profilePreview.src = e.target.result;
                    } else {
                        // Replace div with img
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = "{{ $user->fullName }}";
                        img.className = "w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-100";
                        img.id = "profilePreview";
                        profilePreview.parentNode.replaceChild(img, profilePreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Add skill functionality
    let skillIndex = {{ $skills->count() }};
    const addSkillBtn = document.getElementById('addSkillBtn');
    const skillsContainer = document.getElementById('skillsContainer');
    
    if (addSkillBtn) {
        addSkillBtn.addEventListener('click', function() {
            const skillHtml = `
                <div class="skill-item p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Skill Name</label>
                                <input type="text" name="skills[${skillIndex}][name]" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm" 
                                       placeholder="e.g. JavaScript">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Proficiency</label>
                                <select name="skills[${skillIndex}][proficiency]" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm">
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Years</label>
                                <input type="number" name="skills[${skillIndex}][years_experience]" 
                                       min="0" max="50" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm" 
                                       placeholder="0">
                            </div>
                        </div>
                        <button type="button" onclick="removeSkill(this)" 
                                class="ml-4 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `;
            skillsContainer.insertAdjacentHTML('beforeend', skillHtml);
            skillIndex++;
        });
    }
});

function removeSkill(button) {
    const skillItem = button.closest('.skill-item');
    if (skillItem) {
        skillItem.remove();
    }
}
</script>
@endsection

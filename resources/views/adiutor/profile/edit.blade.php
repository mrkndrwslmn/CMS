@extends('adiutor.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('adiutor.dashboard')],
        ['label' => 'My Profile', 'url' => route('adiutor.profile.show')],
        ['label' => 'Edit Profile'],
    ]" class="mb-6" />

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-neutral-800">Edit Profile</h1>
                <p class="text-sm text-neutral-500 mt-1">Update your professional information and settings</p>
            </div>
            <a href="{{ route('adiutor.profile.show') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-neutral-700 bg-neutral-100 hover:bg-neutral-200 text-sm font-medium rounded-xl transition-colors">
                <x-lucide-x class="w-4 h-4" />
                Cancel
            </a>
        </div>
    </div>

    <form action="{{ route('adiutor.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column - Profile Picture & Basic Info -->
            <div class="lg:col-span-4">
                <!-- Profile Picture -->
                <x-ui.card class="p-6 mb-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Profile Picture</h3>
                    <div class="text-center">
                        <div class="mb-4">
                            @if($user->profilePic)
                                <img src="{{ $user->getProfilePictureUrl() }}" 
                                     alt="{{ $user->fullName }}" 
                                     class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-neutral-100" 
                                     id="profilePreview">
                            @else
                                <div class="w-32 h-32 rounded-full mx-auto bg-primary-600 flex items-center justify-center text-white text-3xl font-medium border-4 border-neutral-100" 
                                     id="profilePreview">
                                    {{ strtoupper(substr($user->fullName, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        
                        <input type="file" name="profile_picture" id="profilePictureInput" class="hidden" accept="image/*">
                        <button type="button" onclick="document.getElementById('profilePictureInput').click()" 
                                class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">
                            <x-lucide-upload class="w-4 h-4" />
                            Change Picture
                        </button>
                        <p class="text-xs text-neutral-500 mt-2">JPG, PNG up to 2MB</p>
                    </div>
                </x-ui.card>

                <!-- Basic Information -->
                <x-ui.card class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">Basic Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Full Name</label>
                            <input type="text" value="{{ $user->fullName }}" disabled 
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl bg-neutral-50 text-neutral-500 cursor-not-allowed">
                            <p class="text-xs text-neutral-500 mt-1">Contact admin to change your name</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-neutral-700 mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled 
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl bg-neutral-50 text-neutral-500 cursor-not-allowed">
                            <p class="text-xs text-neutral-500 mt-1">Contact admin to change your email</p>
                        </div>
                        
                        <div>
                            <label for="phone" class="block text-sm font-medium text-neutral-700 mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phoneNumber) }}" 
                                   data-format="ph"
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="+63 917 123 4567">
                        </div>
                    </div>
                </x-ui.card>
            </div>

            <!-- Right Column - Profile Details -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Professional Information -->
                <x-ui.card class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-6">Professional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="title" class="block text-sm font-medium text-neutral-700 mb-2">Professional Title</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $profile->title ?? '') }}" 
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="e.g. Senior Developer">
                        </div>
                        
                        <div>
                            <label for="location" class="block text-sm font-medium text-neutral-700 mb-2">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $profile->location ?? '') }}" 
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="e.g. Manila, Philippines">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-sm text-neutral-600 flex items-center gap-1.5">
                            <x-lucide-info class="w-4 h-4 text-neutral-400" />
                            For earnings settings including hourly rate, please visit the 
                            <a href="{{ route('adiutor.profile.earnings') }}" class="text-primary-600 hover:text-primary-700 font-medium">Earnings Settings</a> page.
                        </p>
                    </div>
                </x-ui.card>

                <!-- About Me -->
                <x-ui.card class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-4">About Me</h3>
                    <div>
                        <label for="bio" class="block text-sm font-medium text-neutral-700 mb-2">Biography</label>
                        <textarea id="bio" name="bio" rows="6" 
                                  class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                  placeholder="Tell us about yourself, your experience, and what you're passionate about...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                        <p class="text-xs text-neutral-500 mt-1">Maximum 1000 characters</p>
                    </div>
                </x-ui.card>

                <!-- Skills Section -->
                <x-ui.card class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-neutral-800">Skills & Expertise</h3>
                        <button type="button" id="addSkillBtn" 
                                class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-50 text-primary-600 text-sm font-medium rounded-xl hover:bg-primary-100 transition-colors">
                            <x-lucide-plus class="w-4 h-4" />
                            Add Skill
                        </button>
                    </div>
                    
                    <div id="skillsContainer" class="space-y-4">
                        @if($skills->count() > 0)
                            @foreach($skills as $index => $skill)
                                <div class="skill-item p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="relative">
                                                <label class="block text-sm font-medium text-neutral-700 mb-2">Skill</label>
                                                <select name="skills[{{ $index }}][skill_id]" 
                                                        class="w-full px-3 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm appearance-none bg-white" 
                                                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;" 
                                                        required>
                                                    <option value="">Select a skill...</option>
                                                    @foreach($availableSkills as $category => $categorySkills)
                                                        <optgroup label="{{ ucfirst($category) }}">
                                                            @foreach($categorySkills as $availableSkill)
                                                                <option value="{{ $availableSkill->id }}" {{ $skill->id == $availableSkill->id ? 'selected' : '' }}>
                                                                    {{ $availableSkill->name }}
                                                                </option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-neutral-700 mb-2">Proficiency</label>
                                                <select name="skills[{{ $index }}][proficiency]" 
                                                        class="w-full px-3 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm">
                                                    <option value="beginner" {{ $skill->proficiency == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="intermediate" {{ $skill->proficiency == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="advanced" {{ $skill->proficiency == 'advanced' ? 'selected' : '' }}>Advanced</option>
                                                    <option value="expert" {{ $skill->proficiency == 'expert' ? 'selected' : '' }}>Expert</option>
                                                </select>
                                            </div>
                                            
                                            <div>
                                                <label class="block text-sm font-medium text-neutral-700 mb-2">Years</label>
                                                <input type="number" name="skills[{{ $index }}][years_experience]" value="{{ $skill->years_experience }}" 
                                                       min="0" max="50" 
                                                       class="w-full px-3 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm" 
                                                       placeholder="0">
                                            </div>
                                        </div>
                                        
                                        <button type="button" onclick="removeSkill(this)" 
                                                class="ml-4 p-2 text-error-600 hover:bg-error-50 rounded-xl transition-colors">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </x-ui.card>

                <!-- Social Links -->
                <x-ui.card class="p-6">
                    <h3 class="text-lg font-semibold text-neutral-800 mb-6">Social Links & Portfolio</h3>
                    <div class="space-y-4">
                        <div>
                            <label for="linkedin_url" class="block text-sm font-medium text-neutral-700 mb-2">
                                LinkedIn URL
                            </label>
                            <input type="url" id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}" 
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="https://linkedin.com/in/yourprofile">
                        </div>
                        
                        <div>
                            <label for="github_url" class="block text-sm font-medium text-neutral-700 mb-2">
                                GitHub URL
                            </label>
                            <input type="url" id="github_url" name="github_url" value="{{ old('github_url', $profile->github_url ?? '') }}" 
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="https://github.com/yourusername">
                        </div>
                        
                        <div>
                            <label for="portfolio_url" class="block text-sm font-medium text-neutral-700 mb-2">
                                Portfolio URL
                            </label>
                            <input type="url" id="portfolio_url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url ?? '') }}" 
                                   class="w-full px-4 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" 
                                   placeholder="https://yourportfolio.com">
                        </div>
                    </div>
                </x-ui.card>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6">
                    <a href="{{ route('adiutor.profile.show') }}" 
                       class="inline-flex items-center gap-2 px-6 py-3 text-neutral-700 bg-neutral-100 hover:bg-neutral-200 font-medium rounded-xl transition-colors">
                        <x-lucide-x class="w-4 h-4" />
                        Cancel
                    </a>
                    
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors">
                        <x-lucide-save class="w-4 h-4" />
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
                        img.className = "w-32 h-32 rounded-full mx-auto object-cover border-4 border-neutral-100";
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
                <div class="skill-item p-4 bg-neutral-50 rounded-xl border border-neutral-200">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="relative">
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Skill</label>
                                <select name="skills[${skillIndex}][skill_id]" 
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm appearance-none bg-white" 
                                        style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27currentColor%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3e%3cpolyline points=%276 9 12 15 18 9%27%3e%3c/polyline%3e%3c/svg%3e'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 1.5em 1.5em; padding-right: 2.5rem;" 
                                        required>
                                    <option value="">Select a skill...</option>
                                    @foreach($availableSkills as $category => $categorySkills)
                                        <optgroup label="{{ ucfirst($category) }}">
                                            @foreach($categorySkills as $availableSkill)
                                                <option value="{{ $availableSkill->id }}">{{ $availableSkill->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Proficiency</label>
                                <select name="skills[${skillIndex}][proficiency]" 
                                        class="w-full px-3 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm">
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-neutral-700 mb-2">Years</label>
                                <input type="number" name="skills[${skillIndex}][years_experience]" 
                                       min="0" max="50" 
                                       class="w-full px-3 py-2 border border-neutral-300 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors text-sm" 
                                       placeholder="0">
                            </div>
                        </div>
                        <button type="button" onclick="removeSkill(this)" 
                                class="ml-4 p-2 text-error-600 hover:bg-error-50 rounded-xl transition-colors">
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

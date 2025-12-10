@extends('client.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="min-h-screen bg-neutral-50">
    <div class="max-w-4xl mx-auto px-6 py-12">
        <!-- Clean Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-500 mb-6 shadow-sm">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-neutral-900 mb-3">
                Profile Settings
            </h1>
            <p class="text-lg text-neutral-600 max-w-2xl mx-auto">
                Manage your account information and preferences
            </p>
        </div>

        <!-- Profile Form -->
        <form action="{{ route('client.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Profile Picture Card -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-neutral-900">Profile Picture</h2>
                            <p class="text-neutral-600">Upload a photo to personalize your account</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-6">
                        <div class="flex-shrink-0">
                            @if($user->profilePic)
                                <img src="{{ $user->getProfilePictureUrl() }}" 
                                     alt="{{ $user->fullName }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-neutral-100"
                                     id="clientProfilePreview">
                            @else
                                <div class="w-24 h-24 rounded-full bg-primary-500 flex items-center justify-center border-4 border-neutral-100" 
                                     id="clientProfilePreview">
                                    <span class="text-3xl font-bold text-white">{{ substr($user->fullName, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <input type="file" 
                                   name="profile_picture" 
                                   id="clientProfilePictureInput" 
                                   class="hidden" 
                                   accept="image/*">
                            <button type="button" 
                                    onclick="document.getElementById('clientProfilePictureInput').click()" 
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-500 text-white text-sm font-medium rounded-lg hover:bg-primary-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Change Picture
                            </button>
                            <p class="text-xs text-neutral-500 mt-2">JPG, PNG up to 2MB</p>
                            @error('profile_picture')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-8">
                    <!-- Card Header -->
                    <div class="flex items-center mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-neutral-900">Personal Information</h2>
                            <p class="text-neutral-600">Update your basic account details</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label for="fullName" class="block text-sm font-semibold text-neutral-800">
                                Full Name <span class="text-primary-500">*</span>
                            </label>
                            <input type="text" 
                                   id="fullName" 
                                   name="fullName" 
                                   value="{{ old('fullName', $user->fullName) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('fullName') border-red-300 ring-2 ring-red-100 @enderror" 
                                   required>
                            @error('fullName')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-2">
                            <label for="email" class="block text-sm font-semibold text-neutral-800">
                                Email Address <span class="text-primary-500">*</span>
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="w-full px-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('email') border-red-300 ring-2 ring-red-100 @enderror" 
                                   required>
                            @error('email')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-semibold text-neutral-800">
                                Phone Number
                            </label>
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   data-format="ph"
                                   value="{{ old('phone', $profile->contact_phone ?? '') }}"
                                   placeholder="+1 (555) 123-4567"
                                   class="w-full px-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('phone') border-red-300 ring-2 ring-red-100 @enderror">
                            @error('phone')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Industry -->
                        <div class="space-y-2">
                            <label for="industry" class="block text-sm font-semibold text-neutral-800">
                                Industry
                            </label>
                            <select id="industry" 
                                    name="industry" 
                                    class="w-full px-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('industry') border-red-300 ring-2 ring-red-100 @enderror">
                                <option value="">Select Industry</option>
                                <option value="Technology" {{ old('industry', $profile->industry ?? '') == 'Technology' ? 'selected' : '' }}>Technology</option>
                                <option value="Healthcare" {{ old('industry', $profile->industry ?? '') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                <option value="Finance" {{ old('industry', $profile->industry ?? '') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                <option value="Education" {{ old('industry', $profile->industry ?? '') == 'Education' ? 'selected' : '' }}>Education</option>
                                <option value="Retail" {{ old('industry', $profile->industry ?? '') == 'Retail' ? 'selected' : '' }}>Retail</option>
                                <option value="Manufacturing" {{ old('industry', $profile->industry ?? '') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                <option value="Marketing" {{ old('industry', $profile->industry ?? '') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                <option value="Real Estate" {{ old('industry', $profile->industry ?? '') == 'Real Estate' ? 'selected' : '' }}>Real Estate</option>
                                <option value="Non-profit" {{ old('industry', $profile->industry ?? '') == 'Non-profit' ? 'selected' : '' }}>Non-profit</option>
                                <option value="Other" {{ old('industry', $profile->industry ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('industry')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-8">
                    <!-- Card Header -->
                    <div class="flex items-center mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-neutral-900">Business Information</h2>
                            <p class="text-neutral-600">Company details and professional information</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Company Name -->
                        <div class="space-y-2">
                            <label for="company_name" class="block text-sm font-semibold text-neutral-800">
                                Company Name
                            </label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   value="{{ old('company_name', $profile->company_name ?? '') }}"
                                   placeholder="Your Company Inc."
                                   class="w-full px-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('company_name') border-red-300 ring-2 ring-red-100 @enderror">
                            @error('company_name')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="space-y-2">
                            <label for="address" class="block text-sm font-semibold text-neutral-800">
                                Business Address
                            </label>
                            <textarea id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Enter your complete business address"
                                      class="w-full px-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 resize-none @error('address') border-red-300 ring-2 ring-red-100 @enderror">{{ old('address', $profile->address ?? '') }}</textarea>
                            @error('address')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="space-y-2">
                            <label for="bio" class="block text-sm font-semibold text-neutral-800">
                                Professional Bio
                            </label>
                            <textarea id="bio" 
                                      name="bio" 
                                      rows="4"
                                      placeholder="Tell us about yourself, your business, and what makes you unique..."
                                      class="w-full px-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 resize-none @error('bio') border-red-300 ring-2 ring-red-100 @enderror">{{ old('bio', $profile->bio ?? '') }}</textarea>
                            @error('bio')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Links Card -->
            <div class="bg-white rounded-xl shadow-sm border border-neutral-200 hover:shadow-md transition-shadow duration-300">
                <div class="p-8">
                    <!-- Card Header -->
                    <div class="flex items-center mb-8">
                        <div>
                            <h2 class="text-2xl font-bold text-neutral-900">Social Presence</h2>
                            <p class="text-neutral-600">Connect your professional social media profiles</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Website -->
                        <div class="space-y-2">
                            <label for="website" class="block text-sm font-semibold text-neutral-800">
                                Website
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-5 0-9-4-9-9s4-9 9-9"/>
                                    </svg>
                                </div>
                                <input type="url" 
                                       id="website" 
                                       name="website" 
                                       value="{{ old('website', $profile->website ?? '') }}"
                                       placeholder="https://yourwebsite.com"
                                       class="w-full pl-12 pr-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('website') border-red-300 ring-2 ring-red-100 @enderror">
                            </div>
                            @error('website')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- LinkedIn -->
                        <div class="space-y-2">
                            <label for="linkedin" class="block text-sm font-semibold text-neutral-800">
                                LinkedIn Profile
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </div>
                                <input type="url" 
                                       id="linkedin" 
                                       name="linkedin" 
                                       value="{{ old('linkedin', $profile->linkedin ?? '') }}"
                                       placeholder="https://linkedin.com/in/yourprofile"
                                       class="w-full pl-12 pr-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('linkedin') border-red-300 ring-2 ring-red-100 @enderror">
                            </div>
                            @error('linkedin')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Twitter -->
                        <div class="space-y-2 lg:col-span-2">
                            <label for="twitter" class="block text-sm font-semibold text-neutral-800">
                                Twitter/X Profile
                            </label>
                            <div class="relative max-w-lg">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                    </svg>
                                </div>
                                <input type="url" 
                                       id="twitter" 
                                       name="twitter" 
                                       value="{{ old('twitter', $profile->twitter ?? '') }}"
                                       placeholder="https://twitter.com/yourusername"
                                       class="w-full pl-12 pr-4 py-3 rounded-lg border border-neutral-200 bg-white focus:border-primary-400 focus:ring-2 focus:ring-primary-100 transition-all duration-300 @error('twitter') border-red-300 ring-2 ring-red-100 @enderror">
                            </div>
                            @error('twitter')
                                <p class="text-red-600 text-sm mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center sm:justify-end max-w-md ml-auto">
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center justify-center px-8 py-3 border border-neutral-300 rounded-lg bg-white text-neutral-700 font-semibold hover:bg-neutral-50 hover:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-300 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center justify-center px-8 py-3 bg-primary-500 text-white font-semibold rounded-lg hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-300 transform hover:scale-[1.02] transition-all duration-300 shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Profile picture preview
    document.addEventListener('DOMContentLoaded', function() {
        const profileInput = document.getElementById('clientProfilePictureInput');
        const profilePreview = document.getElementById('clientProfilePreview');
        
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
                            img.alt = "Profile Preview";
                            img.className = "w-24 h-24 rounded-full object-cover border-4 border-neutral-100";
                            img.id = "clientProfilePreview";
                            profilePreview.parentNode.replaceChild(img, profilePreview);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endpush
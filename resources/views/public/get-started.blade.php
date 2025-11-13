@extends('layouts.public')

@section('title', 'Our Services - Academic and Programming Solutions')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    <script defer src="https://cdn.vercel-insights.com/v1/script.js?projectId=prj_8NsY544ll3Q74OVb6njoN8QFj0kl"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1864950796514595" crossorigin="anonymous"></script>
    
    @if(app(\App\Services\RecaptchaService::class)->isEnabled())
        <script src="{{ app(\App\Services\RecaptchaService::class)->getScriptUrl() }}" async defer></script>
    @endif
@endpush

@section('content')
    <div class="max-w-7xl mx-auto pt-32 pb-32">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-neutral-900 mb-4">Get Started with Your Project</h1>
            <p class="text-lg text-neutral-600">Tell us about your needs and we'll match you with the perfect Adiutor</p>
            @if(!$isLoggedIn)
                <p class="text-sm text-primary-600 mt-2">✨ No account? No problem! We'll create one for you automatically.</p>
            @endif
        </div>

        <!-- Service Pre-selected Notice -->
        <div id="service-preselected-notice" class="hidden mb-8 max-w-7xl mx-auto">
            <div class="bg-primary-50 border-l-4 border-primary-500 p-6 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-primary-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-primary-900 mb-2">Service Pre-selected</h3>
                        <p class="text-primary-700">We've pre-filled some details based on the service you selected. Feel free to modify any information to match your specific needs.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message for New Accounts -->
        @if(session('credentials'))
            <div class="bg-success-50 border-l-4 border-success-500 p-6 mb-8 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-success-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-success-900 mb-2">Request Submitted Successfully!</h3>
                        <p class="text-success-700 mb-4">Your account has been created. Here are your credentials:</p>
                        <div class="bg-white p-4 rounded border border-success-200">
                            <p class="text-sm"><strong>Email:</strong> {{ session('credentials.email') }}</p>
                            <p class="text-sm"><strong>Password:</strong> <code class="bg-neutral-100 px-2 py-1 rounded">{{ session('credentials.password') }}</code></p>
                        </div>
                        @if(session('email_failed'))
                            <p class="text-sm text-warning-600 mt-3 font-semibold">⚠️ {{ session('warning', 'We couldn\'t send the email. Please save these credentials now!') }}</p>
                        @else
                            <p class="text-sm text-success-600 mt-3">💌 These credentials have also been sent to your email.</p>
                        @endif
                        <a href="{{ route('login') }}" class="btn-primary mt-4 inline-block">Login Now</a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Message for Existing Users -->
        @if(session('success') && !session('credentials'))
            <div class="bg-success-50 border-l-4 border-success-500 p-6 mb-8 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-success-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-success-900">{{ session('success') }}</h3>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="bg-error-50 border-l-4 border-error-500 p-6 mb-8 rounded-lg">
                <div class="flex">
                    <svg class="w-6 h-6 text-error-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-error-900 mb-2">Please fix the following errors:</h3>
                        <ul class="list-disc list-inside text-error-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('get-started.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            @if(!$isLoggedIn)
            <!-- Contact Information -->
            <div class="bg-white/70 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-neutral-200">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Your Contact Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="md:col-span-2">
                        <label for="full_name" class="block text-sm font-medium text-neutral-700 mb-2">
                            Full Name <span class="text-error-500">*</span>
                        </label>
                        <input type="text" 
                               id="full_name" 
                               name="full_name" 
                               value="{{ old('full_name') }}"
                               class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('full_name') border-error-300 @enderror" 
                               placeholder="John Doe"
                               required>
                        @error('full_name')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">
                            Email Address <span class="text-error-500">*</span>
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('email') border-error-300 @enderror" 
                               placeholder="john@example.com"
                               required>
                        @error('email')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-neutral-700 mb-2">
                            Phone Number
                        </label>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('phone') border-error-300 @enderror" 
                               placeholder="+1 (555) 000-0000">
                        @error('phone')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Method -->
                    <div>
                        <label for="contact_method" class="block text-sm font-medium text-neutral-700 mb-2">
                            Preferred Contact Method <span class="text-error-500">*</span>
                        </label>
                        <select id="contact_method" 
                                name="contact_method" 
                                class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('contact_method') border-error-300 @enderror" 
                                required>
                            <option value="">Select a method</option>
                            <option value="email" {{ old('contact_method') === 'email' ? 'selected' : '' }}>📧 Email</option>
                            <option value="messenger" {{ old('contact_method') === 'messenger' ? 'selected' : '' }}>💬 Facebook Messenger</option>
                            <option value="phone" {{ old('contact_method') === 'phone' ? 'selected' : '' }}>📞 Phone</option>
                        </select>
                        @error('contact_method')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Details -->
                    <div>
                        <label for="contact_details" class="block text-sm font-medium text-neutral-700 mb-2">
                            Contact Details <span class="text-error-500">*</span>
                        </label>
                        <input type="text" 
                               id="contact_details" 
                               name="contact_details" 
                               value="{{ old('contact_details') }}"
                               class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('contact_details') border-error-300 @enderror" 
                               placeholder="Your email, phone, or messenger link"
                               required>
                        @error('contact_details')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500">Enter the details for your preferred contact method</p>
                    </div>

                    <!-- Hidden password field (auto-generated) -->
                    <input type="hidden" name="password" value="{{ Str::random(12) }}">
                </div>
            </div>
            @endif

            <!-- Service Request Details -->
            <div class="bg-white/70 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-neutral-200">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Project Details
                </h2>
                
                <div class="space-y-6">
                    <!-- Project Name -->
                    <div>
                        <label for="project_name" class="block text-sm font-medium text-neutral-700 mb-2">
                            Project Name <span class="text-error-500">*</span>
                        </label>
                        <input type="text" 
                               id="project_name" 
                               name="project_name" 
                               value="{{ old('project_name') }}"
                               class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('project_name') border-error-300 @enderror" 
                               placeholder="e.g., E-commerce Website Redesign"
                               required>
                        @error('project_name')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Category -->
                    <div>
                        <label for="service_type" class="block text-sm font-medium text-neutral-700 mb-2">
                            What category of service do you need? <span class="text-error-500">*</span>
                        </label>
                        <select id="service_type" 
                                name="service_type" 
                                class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('service_type') border-error-300 @enderror" 
                                required>
                            <option value="">Loading service categories...</option>
                            <!-- Service categories will be populated dynamically -->
                        </select>
                        @error('service_type')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-neutral-500">Choose the category that best matches your project needs</p>
                    </div>

                    <!-- Project Description -->
                    <div>
                        <label for="request_description" class="block text-sm font-medium text-neutral-700 mb-2">
                            Describe your project <span class="text-error-500">*</span>
                        </label>
                        <textarea id="request_description" 
                                  name="request_description" 
                                  rows="6" 
                                  class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('request_description') border-error-300 @enderror" 
                                  placeholder="Tell us about your project goals, requirements, timeline, and any specific needs..."
                                  required>{{ old('request_description') }}</textarea>
                        @error('request_description')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deadline (Optional) -->
                    <div>
                        <label for="deadline" class="block text-sm font-medium text-neutral-700 mb-2">
                            Deadline (Optional)
                        </label>
                        <input type="date" 
                               id="deadline" 
                               name="deadline" 
                               value="{{ old('deadline') }}"
                               class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('deadline') border-error-300 @enderror"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        @error('deadline')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expectations (Optional) -->
                    <div>
                        <label for="expectations" class="block text-sm font-medium text-neutral-700 mb-2">
                            Expectations (Optional)
                        </label>
                        <textarea id="expectations" 
                                  name="expectations" 
                                  rows="3" 
                                  class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('expectations') border-error-300 @enderror" 
                                  placeholder="What are your expectations for this project?">{{ old('expectations') }}</textarea>
                        @error('expectations')
                            <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Budget (Optional) - Removed as it's not in the controller validation -->
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="bg-white/70 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-neutral-200">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                    </svg>
                    Additional Notes
                </h2>
                
                <div>
                    <label for="additional_notes" class="block text-sm font-medium text-neutral-700 mb-2">
                        Any other information? (Optional)
                    </label>
                    <textarea id="additional_notes" 
                              name="additional_notes" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('additional_notes') border-error-300 @enderror" 
                              placeholder="Any additional comments, questions, or special requirements...">{{ old('additional_notes') }}</textarea>
                    @error('additional_notes')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- File Attachments -->
            <div class="bg-white/70 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-neutral-200">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                    </svg>
                    Attachments (Optional)
                </h2>
                
                <div>
                    <label for="file_upload" class="block text-sm font-medium text-neutral-700 mb-2">
                        Upload relevant files
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-8 pb-8 border-2 border-neutral-300 border-dashed rounded-lg hover:border-primary-400 transition-all duration-200 bg-neutral-50/50">
                        <div class="space-y-2 text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-neutral-600 justify-center">
                                <label for="file_upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 px-2 py-1">
                                    <span>Choose files</span>
                                    <input id="file_upload" name="file_upload[]" type="file" class="sr-only" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-neutral-500">
                                Documents, images, or any relevant files (max 10MB each)
                            </p>
                        </div>
                    </div>
                    @error('file_upload')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File List -->
                <div id="file-list" class="mt-6 hidden">
                    <h4 class="text-sm font-medium text-neutral-700 mb-3">Selected Files:</h4>
                    <div id="files" class="space-y-2"></div>
                </div>
            </div>

            <!-- reCAPTCHA -->
            @if(app(\App\Services\RecaptchaService::class)->isEnabled())
            <div class="bg-white/70 backdrop-blur-sm rounded-xl shadow-lg p-8 border border-neutral-200">
                <h2 class="text-xl font-semibold text-neutral-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.586-3.414A2 2 0 0118 4.586V2a1 1 0 011-1h2a1 1 0 011 1v2.586A2 2 0 0120.414 6L18 8.414a2 2 0 01-2.828 0L13.586 6A2 2 0 0112 4.586V2a1 1 0 011-1h2a1 1 0 011 1v2.586z"/>
                    </svg>
                    Security Verification
                </h2>
                
                <div class="space-y-4">
                    <p class="text-sm text-neutral-600">
                        Please complete the security verification below to protect against spam and automated submissions.
                    </p>
                    
                    <div class="flex justify-center">
                        {!! app(\App\Services\RecaptchaService::class)->getHtml() !!}
                    </div>
                    
                    @error('g-recaptcha-response')
                        <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            @endif

            <!-- Submit Button -->
            <div class="flex items-center justify-center pt-4">
                <button type="submit" class="bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold px-8 py-4 rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center text-lg">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Submit Request
                </button>
            </div>
        </form>
    </div>

    @endsection

    @push('scripts')

    <script>
    document.addEventListener('DOMContentLoaded', async function() {
        // Populate service categories from API
        await populateServiceCategories();
        
        // Auto-populate form from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        
        if (urlParams.has('service')) {
            const serviceName = urlParams.get('service');
            const serviceType = urlParams.get('type');
            const serviceDescription = urlParams.get('description');
            const servicePrice = urlParams.get('price');
            
            // Show pre-selected notice
            const notice = document.getElementById('service-preselected-notice');
            if (notice) {
                notice.classList.remove('hidden');
            }
            
            // Populate service type (wait for categories to load first)
            if (serviceType) {
                setTimeout(() => {
                    const serviceTypeSelect = document.getElementById('service_type');
                    if (serviceTypeSelect) {
                        // Find the option that matches the service type
                        const options = serviceTypeSelect.querySelectorAll('option');
                        let matched = false;
                        
                        options.forEach(option => {
                            if (option.value === serviceType) {
                                option.selected = true;
                                matched = true;
                            }
                        });
                        
                        // If matched, add visual feedback
                        if (matched) {
                            serviceTypeSelect.classList.add('ring-2', 'ring-primary-300', 'bg-primary-50/30');
                        }
                    }
                }, 500); // Wait for API call to complete
            }
            
            // Populate description with service details
            if (serviceDescription) {
                const descriptionTextarea = document.getElementById('request_description');
                if (descriptionTextarea && !descriptionTextarea.value) {
                    descriptionTextarea.value = `I'm interested in: ${serviceName}\n\n${serviceDescription}\n\nAdditional details: `;
                    // Add visual feedback
                    descriptionTextarea.classList.add('ring-2', 'ring-primary-300', 'bg-primary-50/30');
                    // Move cursor to end for user to add their details
                    descriptionTextarea.focus();
                    descriptionTextarea.setSelectionRange(descriptionTextarea.value.length, descriptionTextarea.value.length);
                }
            }
            
            // Populate project name if service name is available
            if (serviceName) {
                const projectNameInput = document.getElementById('project_name');
                if (projectNameInput && !projectNameInput.value) {
                    projectNameInput.value = serviceName;
                    // Add visual feedback
                    projectNameInput.classList.add('ring-2', 'ring-primary-300', 'bg-primary-50/30');
                }
            }
            
            // Remove budget field population as it's no longer in the form
            
            // Scroll to form smoothly
            setTimeout(() => {
                const form = document.querySelector('form');
                if (form) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        }
        
        // Auto-populate contact_details based on contact_method selection
        const contactMethodSelect = document.getElementById('contact_method');
        const contactDetailsInput = document.getElementById('contact_details');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        
        if (contactMethodSelect && contactDetailsInput) {
            contactMethodSelect.addEventListener('change', function() {
                const method = this.value;
                if (method === 'email' && emailInput) {
                    contactDetailsInput.value = emailInput.value;
                } else if (method === 'phone' && phoneInput) {
                    contactDetailsInput.value = phoneInput.value;
                }
            });
            
            // Auto-sync email and phone changes
            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    if (contactMethodSelect.value === 'email') {
                        contactDetailsInput.value = this.value;
                    }
                });
            }
            
            if (phoneInput) {
                phoneInput.addEventListener('input', function() {
                    if (contactMethodSelect.value === 'phone') {
                        contactDetailsInput.value = this.value;
                    }
                });
            }
        }
        
        // File upload handling
        const fileInput = document.getElementById('file_upload');
        const fileList = document.getElementById('file-list');
        const filesContainer = document.getElementById('files');

        fileInput.addEventListener('change', function() {
            const files = Array.from(this.files);
            
            if (files.length > 0) {
                fileList.classList.remove('hidden');
                filesContainer.innerHTML = '';
                
                files.forEach((file, index) => {
                    const fileDiv = document.createElement('div');
                    fileDiv.className = 'flex items-center justify-between p-4 bg-white rounded-lg border border-neutral-200 hover:border-primary-300 transition-colors';
                    
                    fileDiv.innerHTML = `
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">${file.name}</p>
                                <p class="text-xs text-neutral-500">${(file.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                        </div>
                        <button type="button" class="text-error-600 hover:text-error-700 p-1" onclick="removeFile(${index})">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    `;
                    
                    filesContainer.appendChild(fileDiv);
                });
            } else {
                fileList.classList.add('hidden');
            }
        });

        // Drag and drop functionality
        const dropZone = document.querySelector('[class*="border-dashed"]');
        
        if (dropZone) {
            dropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-primary-500', 'bg-primary-50');
            });
            
            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-primary-500', 'bg-primary-50');
            });
            
            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-primary-500', 'bg-primary-50');
                
                const files = e.dataTransfer.files;
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            });
        }
    });

    // Function to populate service categories from API
    async function populateServiceCategories() {
        try {
            const response = await fetch('/api/services');
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const services = await response.json();
            const categories = [...new Set(services.map(service => service.service_type))];
            
            const serviceTypeSelect = document.getElementById('service_type');
            if (serviceTypeSelect) {
                // Clear loading state and set proper placeholder
                serviceTypeSelect.innerHTML = '<option value="">Select a service category</option>';
                
                // Category icons mapping
                const categoryIcons = {
                    'Web Development': '💻',
                    'Mobile Development': '📱',
                    'Design': '🎨',
                    'Backend Development': '⚙️',
                    'Integration': '🔗',
                    'Consulting': '👥',
                    'Maintenance': '🔧',
                    'Marketing': '📢'
                };
                
                // Add category options with icons
                categories.sort().forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    const icon = categoryIcons[category] || '📋';
                    option.textContent = `${icon} ${category}`;
                    
                    // Check if this category should be selected from old input
                    const oldValue = '{{ old("service_type") }}';
                    if (oldValue === category) {
                        option.selected = true;
                    }
                    
                    serviceTypeSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error fetching service categories:', error);
            // Fallback to basic categories if API fails
            const serviceTypeSelect = document.getElementById('service_type');
            if (serviceTypeSelect) {
                serviceTypeSelect.innerHTML = `
                    <option value="">Select a service category</option>
                    <option value="Web Development">💻 Web Development</option>
                    <option value="Mobile Development">📱 Mobile Development</option>
                    <option value="Design">🎨 Design</option>
                    <option value="Backend Development">⚙️ Backend Development</option>
                    <option value="Integration">🔗 Integration</option>
                    <option value="Consulting">👥 Consulting</option>
                    <option value="Maintenance">🔧 Maintenance</option>
                    <option value="Marketing">📢 Marketing</option>
                `;
            }
        }
    }

    function removeFile(index) {
        const fileInput = document.getElementById('file_upload');
        const dt = new DataTransfer();
        const files = Array.from(fileInput.files);
        
        files.forEach((file, i) => {
            if (i !== index) {
                dt.items.add(file);
            }
        });
        
        fileInput.files = dt.files;
        fileInput.dispatchEvent(new Event('change'));
    }
    </script>
    
    @endpush
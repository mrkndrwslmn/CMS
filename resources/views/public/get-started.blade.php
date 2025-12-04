@extends('layouts.public')

@section('title', 'Our Services - Academic and Programming Solutions')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    <script defer src="https://cdn.vercel-insights.com/v1/script.js?projectId=prj_8NsY544ll3Q74OVb6njoN8QFj0kl"></script>
    
    @if(app(\App\Services\RecaptchaService::class)->isEnabled())
        <script src="{{ app(\App\Services\RecaptchaService::class)->getScriptUrl() }}" async defer></script>
    @endif
@endpush

@section('content')
    <div class="max-w-7xl mx-auto px-6 lg:px-8 pt-24 pb-24">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-semibold text-neutral-800 mb-2">Get Started with Your Project</h1>
            <p class="text-md text-neutral-600">Tell us about your needs and we'll match you with the perfect Adiutor</p>
            @if(!$isLoggedIn)
                <p class="flex items-center justify-center gap-2 text-sm text-primary-600 mt-3">
                    <x-lucide-sparkles class="w-4 h-4" />
                    <span>No account? No problem! We'll create one for you automatically.</span>
                </p>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form Column -->
            <div class="lg:col-span-2">
                <!-- Service Pre-selected Notice -->
        <div id="service-preselected-notice" class="hidden mb-8">
            <x-ui.alert type="info" title="Service Pre-selected">
                We've pre-filled some details based on the service you selected. Feel free to modify any information to match your specific needs.
            </x-ui.alert>
        </div>

        <!-- Success Message for New Accounts -->
        @if(session('credentials'))
            <div class="mb-8">
                <x-ui.card>
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-success-50 flex items-center justify-center">
                                <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-neutral-800 mb-2">Request Submitted Successfully!</h3>
                            <p class="text-sm text-neutral-600 mb-4">Your account has been created. Here are your credentials:</p>
                            <div class="bg-neutral-50 p-4 rounded-lg border border-neutral-200">
                                <p class="text-sm text-neutral-700"><span class="font-medium">Email:</span> {{ session('credentials.email') }}</p>
                                <p class="text-sm text-neutral-700 mt-1"><span class="font-medium">Password:</span> <code class="bg-neutral-100 px-2 py-0.5 rounded text-neutral-800">{{ session('credentials.password') }}</code></p>
                            </div>
                            @if(session('email_failed'))
                                <div class="flex items-center gap-2 mt-4 text-sm text-warning-600">
                                    <x-lucide-alert-triangle class="w-4 h-4" />
                                    <span>{{ session('warning', 'We couldn\'t send the email. Please save these credentials now!') }}</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2 mt-4 text-sm text-success-600">
                                    <x-lucide-mail class="w-4 h-4" />
                                    <span>These credentials have also been sent to your email.</span>
                                </div>
                            @endif
                            <div class="mt-4">
                                <x-ui.button href="{{ route('login') }}" variant="primary" size="sm">
                                    <x-lucide-log-in class="w-4 h-4" />
                                    Login Now
                                </x-ui.button>
                            </div>
                        </div>
                    </div>
                </x-ui.card>
            </div>
        @endif

        <!-- Success Message for Existing Users -->
        @if(session('success') && !session('credentials'))
            <div class="mb-8">
                <x-ui.alert type="success" title="{{ session('success') }}" />
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-8">
                <x-ui.alert type="error" title="Please fix the following errors:">
                    <ul class="list-disc list-inside mt-2 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-ui.alert>
            </div>
        @endif

        <!-- Form -->
        <form action="{{ route('get-started.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            @if(!$isLoggedIn)
            <!-- Contact Information -->
            <x-ui.card>
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-user class="w-5 h-5 text-neutral-500" />
                    </div>
                    <h2 class="text-lg font-medium text-neutral-800">Your Contact Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="md:col-span-2">
                        <x-ui.input 
                            type="text"
                            id="full_name"
                            name="full_name"
                            label="Full Name"
                            placeholder="John Doe"
                            :value="old('full_name')"
                            :error="$errors->first('full_name')"
                            required
                        />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-ui.input 
                            type="email"
                            id="email"
                            name="email"
                            label="Email Address"
                            placeholder="john@example.com"
                            :value="old('email')"
                            :error="$errors->first('email')"
                            required
                        />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-ui.input 
                            type="tel"
                            id="phone"
                            name="phone"
                            label="Phone Number"
                            placeholder="+1 (555) 000-0000"
                            :value="old('phone')"
                            :error="$errors->first('phone')"
                        />
                    </div>

                    <!-- Contact Method -->
                    <div>
                        <x-ui.select 
                            id="contact_method"
                            name="contact_method"
                            label="Preferred Contact Method"
                            placeholder="Select a method"
                            :error="$errors->first('contact_method')"
                            required
                        >
                            <option value="email" {{ old('contact_method') === 'email' ? 'selected' : '' }}>Email</option>
                            <option value="messenger" {{ old('contact_method') === 'messenger' ? 'selected' : '' }}>Facebook Messenger</option>
                            <option value="phone" {{ old('contact_method') === 'phone' ? 'selected' : '' }}>Phone</option>
                        </x-ui.select>
                    </div>

                    <!-- Contact Details -->
                    <div>
                        <x-ui.input 
                            type="text"
                            id="contact_details"
                            name="contact_details"
                            label="Contact Details"
                            placeholder="Your email, phone, or messenger link"
                            hint="Enter the details for your preferred contact method"
                            :value="old('contact_details')"
                            :error="$errors->first('contact_details')"
                            required
                        />
                    </div>

                    <!-- Hidden password field (auto-generated) -->
                    <input type="hidden" name="password" value="{{ Str::random(12) }}">
                </div>
            </x-ui.card>
            @endif

            <!-- Service Request Details -->
            <x-ui.card>
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-file-text class="w-5 h-5 text-neutral-500" />
                    </div>
                    <h2 class="text-lg font-medium text-neutral-800">Project Details</h2>
                </div>
                
                <div class="space-y-6">
                    <!-- Project Name -->
                    <x-ui.input 
                        type="text"
                        id="project_name"
                        name="project_name"
                        label="Project Name"
                        placeholder="e.g., E-commerce Website Redesign"
                        :value="old('project_name')"
                        :error="$errors->first('project_name')"
                        required
                    />

                    <!-- Service Category -->
                    <x-ui.select 
                        id="service_type"
                        name="service_type"
                        label="What category of service do you need?"
                        placeholder="Loading service categories..."
                        hint="Choose the category that best matches your project needs"
                        :error="$errors->first('service_type')"
                        required
                    />

                    <!-- Project Description -->
                    <x-ui.textarea 
                        id="request_description"
                        name="request_description"
                        label="Describe your project"
                        placeholder="Tell us about your project goals, requirements, timeline, and any specific needs..."
                        :rows="6"
                        :error="$errors->first('request_description')"
                        required
                    >{{ old('request_description') }}</x-ui.textarea>

                    <!-- Deadline (Optional) -->
                    <x-ui.input 
                        type="date"
                        id="deadline"
                        name="deadline"
                        label="Deadline (Optional)"
                        :value="old('deadline')"
                        :error="$errors->first('deadline')"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                    />

                    <!-- Expectations (Optional) -->
                    <x-ui.textarea 
                        id="expectations"
                        name="expectations"
                        label="Expectations (Optional)"
                        placeholder="What are your expectations for this project?"
                        :rows="3"
                        :error="$errors->first('expectations')"
                    >{{ old('expectations') }}</x-ui.textarea>
                </div>
            </x-ui.card>

            <!-- Additional Notes -->
            <x-ui.card>
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-message-square class="w-5 h-5 text-neutral-500" />
                    </div>
                    <h2 class="text-lg font-medium text-neutral-800">Additional Notes</h2>
                </div>
                
                <x-ui.textarea 
                    id="additional_notes"
                    name="additional_notes"
                    label="Any other information? (Optional)"
                    placeholder="Any additional comments, questions, or special requirements..."
                    :rows="4"
                    :error="$errors->first('additional_notes')"
                >{{ old('additional_notes') }}</x-ui.textarea>
            </x-ui.card>

            <!-- File Attachments -->
            <x-ui.card>
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-paperclip class="w-5 h-5 text-neutral-500" />
                    </div>
                    <h2 class="text-lg font-medium text-neutral-800">Attachments (Optional)</h2>
                </div>
                
                <div>
                    <label for="file_upload" class="block text-sm font-medium text-neutral-700 mb-2">
                        Upload relevant files
                    </label>
                    <div class="mt-2 flex justify-center px-6 pt-8 pb-8 border-2 border-neutral-200 border-dashed rounded-xl hover:border-primary-400 transition-all duration-200 bg-neutral-50/50">
                        <div class="space-y-3 text-center">
                            <div class="mx-auto w-12 h-12 bg-neutral-100 rounded-full flex items-center justify-center">
                                <x-lucide-upload-cloud class="w-6 h-6 text-neutral-400" />
                            </div>
                            <div class="flex text-sm text-neutral-600 justify-center">
                                <label for="file_upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 px-2 py-1 border border-neutral-200">
                                    <span>Choose files</span>
                                    <input id="file_upload" name="file_upload[]" type="file" class="sr-only" multiple>
                                </label>
                                <p class="pl-2 self-center">or drag and drop</p>
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
            </x-ui.card>

            <!-- reCAPTCHA -->
            @if(app(\App\Services\RecaptchaService::class)->isEnabled())
            <x-ui.card>
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-neutral-50 rounded-lg">
                        <x-lucide-shield-check class="w-5 h-5 text-neutral-500" />
                    </div>
                    <h2 class="text-lg font-medium text-neutral-800">Security Verification</h2>
                </div>
                
                <div class="space-y-4">
                    <p class="text-sm text-neutral-600">
                        Please complete the security verification below to protect against spam and automated submissions.
                    </p>
                    
                    <div class="flex justify-center">
                        {!! app(\App\Services\RecaptchaService::class)->getHtml() !!}
                    </div>
                    
                    @error('g-recaptcha-response')
                        <p class="mt-1 text-sm text-error-600 text-center">{{ $message }}</p>
                    @enderror
                </div>
            </x-ui.card>
            @endif

            <!-- Submit Button -->
            <div class="flex items-center justify-center pt-4">
                <x-ui.button type="submit" variant="primary" size="lg">
                    <x-lucide-zap class="w-5 h-5" />
                    Submit Request
                </x-ui.button>
            </div>
        </form>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    <!-- How It Works -->
                    <x-ui.card>
                        <h3 class="text-base font-medium text-neutral-800 mb-4">How It Works</h3>
                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-600">1</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-700">Submit Your Request</p>
                                    <p class="text-xs text-neutral-500 mt-0.5">Tell us about your project needs</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-600">2</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-700">Get Matched</p>
                                    <p class="text-xs text-neutral-500 mt-0.5">We'll find the perfect Adiutor for you</p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <div class="flex-shrink-0 w-8 h-8 bg-primary-50 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-medium text-primary-600">3</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-neutral-700">Start Collaborating</p>
                                    <p class="text-xs text-neutral-500 mt-0.5">Work together to bring your project to life</p>
                                </div>
                            </div>
                        </div>
                    </x-ui.card>

                    <!-- What to Expect -->
                    <x-ui.card>
                        <h3 class="text-base font-medium text-neutral-800 mb-4">What to Expect</h3>
                        <ul class="space-y-3">
                            <li class="flex items-start gap-2">
                                <x-lucide-clock class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                                <span class="text-sm text-neutral-600">Response within 24 hours</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <x-lucide-message-circle class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                                <span class="text-sm text-neutral-600">Free initial consultation</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <x-lucide-shield-check class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                                <span class="text-sm text-neutral-600">Secure & confidential</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <x-lucide-credit-card class="w-4 h-4 text-neutral-400 mt-0.5 flex-shrink-0" />
                                <span class="text-sm text-neutral-600">Flexible payment options</span>
                            </li>
                        </ul>
                    </x-ui.card>

                    <!-- Need Help? -->
                    <x-ui.card>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-neutral-50 rounded-lg flex-shrink-0">
                                <x-lucide-help-circle class="w-5 h-5 text-neutral-500" />
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-neutral-800">Need Help?</h3>
                                <p class="text-xs text-neutral-500 mt-1">Have questions before submitting? We're here to help.</p>
                                <a href="{{ route('contact') }}" class="inline-flex items-center gap-1 text-sm text-primary-600 hover:text-primary-700 mt-2 transition-colors">
                                    Contact Us
                                    <x-lucide-arrow-right class="w-3 h-3" />
                                </a>
                            </div>
                        </div>
                    </x-ui.card>
                </div>
            </div>
        </div>
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
                
                // Add category options (no emojis for clean UI)
                categories.sort().forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    
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
                    <option value="Web Development">Web Development</option>
                    <option value="Mobile Development">Mobile Development</option>
                    <option value="Design">Design</option>
                    <option value="Backend Development">Backend Development</option>
                    <option value="Integration">Integration</option>
                    <option value="Consulting">Consulting</option>
                    <option value="Maintenance">Maintenance</option>
                    <option value="Marketing">Marketing</option>
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
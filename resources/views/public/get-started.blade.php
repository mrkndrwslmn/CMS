@extends('layouts.public')

@section('title', 'Get Started - Academic and Programming Solutions')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    
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
                <!-- Success Message for New Accounts -->
                <x-service-request.credentials-success 
                    :credentials="session('credentials')" 
                    :loginRoute="route('login')" 
                />

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

                    <!-- Service Pre-selected Notice (MUST be inside form for hidden inputs) -->
                    <x-service-request.preselected-notice />

                    @if(!$isLoggedIn)
                        <!-- Contact Information (for new users) -->
                        <x-service-request.contact-info />
                    @else
                        <!-- Contact Preferences (for logged-in users) -->
                        <x-ui.card>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="p-2 bg-neutral-50 rounded-lg">
                                    <x-lucide-user class="w-5 h-5 text-neutral-500" />
                                </div>
                                <h2 class="text-lg font-medium text-neutral-800">Contact Preferences for This Request</h2>
                            </div>
                            <x-service-request.contact-preferences :userEmail="$user->email ?? null" />
                        </x-ui.card>
                    @endif

                    <!-- Service Request Details -->
                    <x-service-request.project-details />

                    <!-- Additional Notes -->
                    <x-service-request.additional-notes />

                    <!-- File Attachments -->
                    <x-service-request.file-attachments />

                    <!-- reCAPTCHA -->
                    <x-service-request.recaptcha />

                    <!-- Submit Button -->
                    <x-service-request.submit-button />
                </form>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <x-service-request.sidebar :showContactLink="true" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <x-service-request.form-scripts :isLoggedIn="$isLoggedIn" :userEmail="$user->email ?? null" />
@endpush
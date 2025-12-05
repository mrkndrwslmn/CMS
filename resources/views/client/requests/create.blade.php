@extends('client.layouts.app')

@section('title', 'Create Service Request')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    
@endpush

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <x-ui.breadcrumb :items="[
            ['label' => 'Service Requests', 'url' => route('client.requests'), 'icon' => 'file-text'],
            ['label' => 'Create Request', 'icon' => 'plus']
        ]" class="mb-6" />

        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-2xl font-semibold text-neutral-800 mb-4">Get Started with Your Project</h1>
            <p class="text-neutral-600">Tell us about your needs and we'll match you with the perfect Adiutor</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Form Column -->
            <div class="lg:col-span-2">
                <!-- Service Pre-selected Notice -->
                <x-service-request.preselected-notice />

                <!-- Success Message for Existing Users -->
                @if(session('success'))
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
                <form action="{{ route('client.requests.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Hidden user_id for authenticated users -->
                    <input type="hidden" name="user_id" value="{{ $user->id ?? $user->userID }}">

                    <!-- Service Request Details with Contact Preferences for authenticated users -->
                    <x-service-request.project-details>
                        <!-- Contact Preferences for authenticated users -->
                        <x-service-request.contact-preferences :userEmail="$user->email ?? null" />
                    </x-service-request.project-details>

                    <!-- Additional Notes -->
                    <x-service-request.additional-notes />

                    <!-- File Attachments -->
                    <x-service-request.file-attachments />

                    <!-- Submit Button -->
                    <x-service-request.submit-button label="Submit Request" icon="send" />
                </form>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <x-service-request.sidebar :showContactLink="false" />
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <x-service-request.form-scripts :isLoggedIn="true" :userEmail="$user->email ?? null" />
@endpush
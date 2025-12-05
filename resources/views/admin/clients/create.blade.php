@extends('admin.layouts.app')

@section('title', 'Add New Client')
@section('page-title', 'Client Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'route' => 'admin.clients.index', 'icon' => 'users'],
        ['label' => 'Add New Client', 'icon' => 'user-plus'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Add New Client" 
            description="Create a new client account"
        />
        <a href="{{ route('admin.clients.index') }}">
            <x-ui.button variant="secondary" icon="arrow-left">
                Back to List
            </x-ui.button>
        </a>
    </div>

    <!-- Create Form -->
    <x-ui.card>
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex items-center gap-2">
                <x-lucide-user-plus class="w-5 h-5 text-neutral-400" />
                <h2 class="text-lg font-medium text-neutral-700">Client Information</h2>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.clients.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div>
                        <label for="fullName" class="block text-sm font-medium text-neutral-700 mb-1.5">
                            Full Name <span class="text-error-500">*</span>
                        </label>
                        <x-ui.input 
                            type="text" 
                            name="fullName" 
                            id="fullName" 
                            icon="user"
                            placeholder="Enter client's full name" 
                            :value="old('fullName')"
                            required
                        />
                        @error('fullName')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-neutral-700 mb-1.5">
                            Email Address <span class="text-error-500">*</span>
                        </label>
                        <x-ui.input 
                            type="email" 
                            name="email" 
                            id="email" 
                            icon="mail"
                            placeholder="Enter email address" 
                            :value="old('email')"
                            required
                        />
                        @error('email')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phoneNumber" class="block text-sm font-medium text-neutral-700 mb-1.5">
                            Phone Number <span class="text-error-500">*</span>
                        </label>
                        <x-ui.input 
                            type="tel" 
                            name="phoneNumber" 
                            id="phoneNumber" 
                            icon="phone"
                            placeholder="Enter phone number" 
                            :value="old('phoneNumber')"
                            required
                        />
                        @error('phoneNumber')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-neutral-700 mb-1.5">
                            Status <span class="text-error-500">*</span>
                        </label>
                        <x-ui.select name="status" id="status" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </x-ui.select>
                        @error('status')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Notice -->
                <div class="mt-6 p-4 bg-info-50 border border-info-200 rounded-lg">
                    <div class="flex items-start gap-3">
                        <x-lucide-info class="w-5 h-5 text-info-500 mt-0.5 flex-shrink-0" />
                        <div>
                            <h4 class="text-sm font-medium text-info-800">Automatic Password Generation</h4>
                            <p class="mt-1 text-sm text-info-700">
                                For security and privacy purposes, a secure password will be automatically generated and sent to the client's email address along with login instructions.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-neutral-100">
                    <a href="{{ route('admin.clients.index') }}">
                        <x-ui.button type="button" variant="secondary">
                            Cancel
                        </x-ui.button>
                    </a>
                    <x-ui.button type="submit" icon="user-plus">
                        Create Client
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>
</div>
@endsection

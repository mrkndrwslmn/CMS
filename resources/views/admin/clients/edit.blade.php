@extends('admin.layouts.app')

@section('title', 'Edit Client - ' . $client->fullName)
@section('page-title', 'Client Management')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Clients', 'route' => 'admin.clients.index', 'icon' => 'users'],
        ['label' => $client->fullName, 'route' => 'admin.clients.show', 'routeParams' => $client->id, 'icon' => 'user'],
        ['label' => 'Edit', 'icon' => 'pencil'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Edit Client" 
            :description="'Editing: ' . $client->fullName"
        />
        <div class="flex gap-3">
            <a href="{{ route('admin.clients.show', $client->id) }}">
                <x-ui.button variant="secondary" icon="eye">
                    View Profile
                </x-ui.button>
            </a>
            <a href="{{ route('admin.clients.index') }}">
                <x-ui.button variant="secondary" icon="arrow-left">
                    Back to List
                </x-ui.button>
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <x-ui.card>
        <div class="px-6 py-4 border-b border-neutral-100">
            <div class="flex items-center gap-2">
                <x-lucide-pencil class="w-5 h-5 text-neutral-400" />
                <h2 class="text-lg font-medium text-neutral-700">Client Information</h2>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.clients.update', $client->id) }}" method="POST">
                @csrf
                @method('PUT')
                
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
                            :value="old('fullName', $client->fullName)"
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
                            :value="old('email', $client->email)"
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
                            :value="old('phoneNumber', $client->phoneNumber)"
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
                            <option value="active" {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ old('status', $client->status) == 'banned' ? 'selected' : '' }}>Banned</option>
                        </x-ui.select>
                        @error('status')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Client Profile Section (if exists) -->
                @if($client->clientProfile)
                <div class="mt-8 pt-6 border-t border-neutral-100">
                    <h3 class="text-md font-medium text-neutral-700 mb-4">Additional Profile Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Company Name</label>
                            <p class="text-neutral-800">{{ $client->clientProfile->company_name ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Industry</label>
                            <p class="text-neutral-800">{{ $client->clientProfile->industry ?? 'Not set' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Client Type</label>
                            <p class="text-neutral-800">{{ ucfirst($client->clientProfile->client_type ?? 'Not set') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Verified</label>
                            @if($client->clientProfile->is_verified)
                                <x-ui.badge type="success">Verified</x-ui.badge>
                            @else
                                <x-ui.badge type="neutral">Not Verified</x-ui.badge>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Account Info Section -->
                <div class="mt-8 pt-6 border-t border-neutral-100">
                    <h3 class="text-md font-medium text-neutral-700 mb-4">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Member Since</label>
                            <p class="text-neutral-800">{{ $client->created_at->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Last Updated</label>
                            <p class="text-neutral-800">{{ $client->updated_at->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Authentication Provider</label>
                            <p class="text-neutral-800">{{ ucfirst($client->auth_provider ?? 'local') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-neutral-500 mb-1">Email Verified</label>
                            @if($client->email_verified_at)
                                <x-ui.badge type="success">Verified</x-ui.badge>
                            @else
                                <x-ui.badge type="warning">Not Verified</x-ui.badge>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-neutral-100">
                    <a href="{{ route('admin.clients.show', $client->id) }}">
                        <x-ui.button type="button" variant="secondary">
                            Cancel
                        </x-ui.button>
                    </a>
                    <x-ui.button type="submit" icon="save">
                        Save Changes
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>
</div>
@endsection

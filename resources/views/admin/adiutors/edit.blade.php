@extends('admin.layouts.app')

@section('title', 'Edit Adiutor' . (isset($adiutor) && is_object($adiutor) ? ' - ' . $adiutor->fullName : ''))
@section('page-title', 'Adiutor Management')

@section('content')
<div class="p-6 lg:p-8">
    @if(!isset($adiutor) || !is_object($adiutor))
        <x-ui.card class="border-l-4 border-error-500">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-error-50 rounded-xl">
                        <x-lucide-alert-circle class="w-6 h-6 text-error-500" />
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-neutral-800">Adiutor Not Found</h4>
                        <p class="text-neutral-600 mt-1">The requested adiutor could not be found.</p>
                        <a href="{{ route('admin.adiutors.index') }}" class="mt-4 inline-block">
                            <x-ui.button variant="secondary" icon="arrow-left">
                                Back to Adiutors
                            </x-ui.button>
                        </a>
                    </div>
                </div>
            </div>
        </x-ui.card>
    @else
    
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'icon' => 'home'],
        ['label' => 'Adiutors', 'route' => 'admin.adiutors.index', 'icon' => 'hard-hat'],
        ['label' => $adiutor->fullName, 'route' => 'admin.adiutors.show', 'routeParams' => ['adiutor' => $adiutor->id], 'icon' => 'user'],
        ['label' => 'Edit', 'icon' => 'pencil'],
    ]" class="mb-6" />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <x-ui.page-header 
            title="Edit Adiutor" 
            :description="'Update information for ' . $adiutor->fullName"
        />
        <div class="flex gap-3">
            <a href="{{ route('admin.adiutors.show', $adiutor->id) }}">
                <x-ui.button variant="secondary" icon="eye">
                    View Details
                </x-ui.button>
            </a>
            <a href="{{ route('admin.adiutors.index') }}">
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
                <h2 class="text-lg font-medium text-neutral-700">Adiutor Information</h2>
            </div>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.adiutors.update', $adiutor->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
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
                            placeholder="Enter adiutor's full name" 
                            :value="old('fullName', $adiutor->fullName)"
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
                            :value="old('email', $adiutor->email)"
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
                            placeholder="+63 917 123 4567" 
                            data-format="ph"
                            :value="old('phoneNumber', $adiutor->phoneNumber)"
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
                            <option value="active" {{ old('status', $adiutor->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $adiutor->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="banned" {{ old('status', $adiutor->status) == 'banned' ? 'selected' : '' }}>Banned</option>
                        </x-ui.select>
                        @error('status')
                            <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Profile Section -->
                <div class="mt-8 pt-6 border-t border-neutral-100">
                    <h3 class="text-base font-medium text-neutral-800 mb-4 flex items-center gap-2">
                        <x-lucide-briefcase class="w-4 h-4 text-neutral-400" />
                        Profile Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Professional Title
                            </label>
                            <x-ui.input 
                                type="text" 
                                name="title" 
                                id="title" 
                                icon="badge"
                                placeholder="e.g., Senior Research Analyst" 
                                :value="old('title', $adiutor->adiutorProfile->title ?? '')"
                            />
                            @error('title')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hourly Rate -->
                        <div>
                            <label for="hourly_rate" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Standard Hourly Rate (₱)
                            </label>
                            <x-ui.input 
                                type="number" 
                                name="hourly_rate" 
                                id="hourly_rate" 
                                icon="peso-sign"
                                placeholder="e.g., 150.00" 
                                :value="old('hourly_rate', $adiutor->adiutorProfile->standard_hourly_rate ?? '')"
                                step="0.01"
                                min="0"
                            />
                            @error('hourly_rate')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bio -->
                        <div class="md:col-span-2">
                            <label for="bio" class="block text-sm font-medium text-neutral-700 mb-1.5">
                                Bio / About
                            </label>
                            <textarea 
                                name="bio" 
                                id="bio" 
                                rows="3"
                                class="w-full rounded-lg border-neutral-200 shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Brief description about the adiutor's expertise and background..."
                            >{{ old('bio', $adiutor->adiutorProfile->bio ?? '') }}</textarea>
                            @error('bio')
                                <p class="mt-1 text-sm text-error-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Info -->
                <div class="mt-8 p-4 bg-neutral-50 border border-neutral-200 rounded-lg">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-neutral-500">Account ID:</span>
                            <span class="ml-2 font-medium text-neutral-800">{{ str_pad($adiutor->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500">Created:</span>
                            <span class="ml-2 font-medium text-neutral-800">{{ $adiutor->created_at->format('M d, Y') }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-500">Last Updated:</span>
                            <span class="ml-2 font-medium text-neutral-800">{{ $adiutor->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-neutral-100">
                    <a href="{{ route('admin.adiutors.show', $adiutor->id) }}">
                        <x-ui.button type="button" variant="secondary">
                            Cancel
                        </x-ui.button>
                    </a>
                    <x-ui.button type="submit" icon="save">
                        Update Adiutor
                    </x-ui.button>
                </div>
            </form>
        </div>
    </x-ui.card>

    <!-- Danger Zone -->
    <x-ui.card class="mt-6 border-error-200">
        <div class="px-6 py-4 border-b border-error-200 bg-error-50">
            <div class="flex items-center gap-2">
                <x-lucide-alert-triangle class="w-5 h-5 text-error-500" />
                <h2 class="text-lg font-medium text-error-700">Danger Zone</h2>
            </div>
        </div>
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-sm font-medium text-neutral-800">Archive this adiutor</h4>
                    <p class="text-sm text-neutral-500 mt-1">Once archived, this adiutor will no longer be able to access their account.</p>
                </div>
                <form action="{{ route('admin.adiutors.destroy', $adiutor->id) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to archive this adiutor? They will no longer be able to access their account.')">
                    @csrf
                    @method('DELETE')
                    <x-ui.button type="submit" variant="danger" icon="archive">
                        Archive Adiutor
                    </x-ui.button>
                </form>
            </div>
        </div>
    </x-ui.card>
    @endif
</div>
@endsection

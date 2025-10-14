@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mt-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-accent-500 transition-colors">Dashboard</a>
                    <i class="fas fa-chevron-right text-xs"></i>
                    <span class="text-gray-900">Profile</span>
                </nav>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-accent-500 to-accent-600 px-6 py-8 text-center">
                    <div class="flex justify-center mb-4">
                        @if($user->profilePic)
                            <img src="{{ asset('storage/' . $user->profilePic) }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg"
                                 alt="{{ $user->fullName }}">
                        @else
                            <div class="w-32 h-32 rounded-full bg-white/20 backdrop-blur-sm border-4 border-white flex items-center justify-center shadow-lg">
                                <span class="text-5xl font-bold text-white">{{ substr($user->fullName, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ $user->fullName }}</h2>
                    <p class="text-accent-100 text-sm font-medium">{{ ucfirst($user->role) }}</p>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-4">
                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-envelope w-5 mr-3 text-gray-400"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs text-gray-500 mb-0.5">Email</p>
                            <p class="text-sm font-medium truncate">{{ $user->email }}</p>
                        </div>
                    </div>

                    @if($user->phoneNumber)
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-phone w-5 mr-3 text-gray-400"></i>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-0.5">Phone</p>
                                <p class="text-sm font-medium">{{ $user->phoneNumber }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-calendar-alt w-5 mr-3 text-gray-400"></i>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 mb-0.5">Member Since</p>
                            <p class="text-sm font-medium">{{ $user->created_at->format('F Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-center text-gray-700">
                        <i class="fas fa-clock w-5 mr-3 text-gray-400"></i>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 mb-0.5">Last Updated</p>
                            <p class="text-sm font-medium">{{ $user->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-6">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-800">Account Status</h3>
                </div>
                <div class="p-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Account Status</span>
                        <span class="px-2.5 py-1 bg-green-100 text-green-800 text-xs font-medium rounded-full">Active</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Role</span>
                        <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">User ID</span>
                        <span class="text-sm font-medium text-gray-900">#{{ $user->id }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-user-edit text-gray-600 mr-2"></i>
                        Edit Profile Information
                    </h3>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <form action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Full Name -->
                        <div class="mb-6">
                            <label for="fullName" class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="fullName" 
                                name="fullName" 
                                value="{{ old('fullName', $user->fullName) }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all @error('fullName') border-red-500 @enderror">
                            @error('fullName')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone Number -->
                        <div class="mb-6">
                            <label for="phoneNumber" class="block text-sm font-semibold text-gray-700 mb-2">
                                Phone Number
                            </label>
                            <input 
                                type="tel" 
                                id="phoneNumber" 
                                name="phoneNumber" 
                                value="{{ old('phoneNumber', $user->phoneNumber) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all @error('phoneNumber') border-red-500 @enderror">
                            @error('phoneNumber')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Divider -->
                        <div class="border-t border-gray-200 my-8"></div>

                        <!-- Change Password Section -->
                        <h4 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-lock text-gray-600 mr-2"></i>
                            Change Password
                        </h4>
                        <p class="text-sm text-gray-600 mb-4">Leave blank if you don't want to change your password</p>

                        <!-- Current Password -->
                        <div class="mb-6">
                            <label for="current_password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Current Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="current_password" 
                                    name="current_password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all @error('current_password') border-red-500 @enderror">
                                <button type="button" onclick="togglePassword('current_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="current_password_icon"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                New Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password" 
                                    name="password"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all @error('password') border-red-500 @enderror">
                                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password_icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-6">
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    id="password_confirmation" 
                                    name="password_confirmation"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-accent-500 focus:border-accent-500 transition-all">
                                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i class="fas fa-eye" id="password_confirmation_icon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                                Cancel
                            </a>
                            <button 
                                type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-accent-500 to-accent-600 text-white font-semibold rounded-lg hover:from-accent-600 hover:to-accent-700 transition-all duration-200 shadow-lg shadow-accent-500/30 hover:shadow-xl hover:shadow-accent-500/40">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection

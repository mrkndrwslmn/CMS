<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'CMS') }} - @yield('title', 'Client Dashboard')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-50 antialiase min-h-screen flex flex-col"></body>
    <!-- Navigation -->
    <nav class="nav-blur fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('client.dashboard') }}" class="text-xl font-branding gradient-text">
                        {{ config('app.name', 'CMS') }}
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('client.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('client.dashboard') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('client.tasks') }}" 
                       class="nav-link {{ request()->routeIs('client.tasks') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        My Projects
                    </a>
                    <a href="{{ route('client.requests') }}" 
                       class="nav-link {{ request()->routeIs('client.requests*') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Service Requests
                    </a>
                    <a href="{{ route('client.feedback') }}" 
                       class="nav-link {{ request()->routeIs('client.feedback') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Feedback
                    </a>
                    
                    <!-- User Menu -->
                    <div class="relative ml-6">
                        <div class="flex items-center space-x-3">
                            <!-- New Request Button -->
                            <a href="{{ route('client.requests.create') }}" 
                               class="btn-primary text-sm px-4 py-2">
                                New Request
                            </a>
                            
                            <!-- Notifications -->
                            <button class="relative text-neutral-600 hover:text-primary-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5V3h5v14z"/>
                                </svg>
                                @if(auth()->user()->unreadNotificationsCount() > 0)
                                    <span class="absolute -top-1 -right-1 bg-accent-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                        {{ auth()->user()->unreadNotificationsCount() }}
                                    </span>
                                @endif
                            </button>
                            
                            <!-- Profile -->
                            <div class="flex items-center space-x-2">
                                <img src="{{ auth()->user()->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->fullName) }}" 
                                     alt="{{ auth()->user()->fullName }}" 
                                     class="w-8 h-8 rounded-full">
                                <span class="text-sm font-medium text-neutral-700">{{ auth()->user()->fullName }}</span>
                            </div>
                            
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" 
                                        class="text-neutral-600 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-neutral-600" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden bg-white border-t border-neutral-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('client.dashboard') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Dashboard</a>
                <a href="{{ route('client.tasks') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">My Projects</a>
                <a href="{{ route('client.requests') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Service Requests</a>
                <a href="{{ route('client.feedback') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Feedback</a>
                <a href="{{ route('client.requests.create') }}" class="block px-3 py-2 text-primary-600 font-medium">New Request</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 min-h-screen">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="bg-success-50 border border-success-200 text-success-800 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="bg-error-50 border border-error-200 text-error-800 px-4 py-3 rounded-lg">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('active');
        }
    </script>
</body>
</html>
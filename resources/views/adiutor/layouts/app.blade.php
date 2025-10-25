<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Adiutor Dashboard') - {{ config('app.name', 'CMS') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="@yield('favicon', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor//favico.ico')" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS (Local Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>
<body class="bg-neutral-50 antialiased">
    <!-- Navigation -->
    <nav class="nav-blur fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('adiutor.dashboard') }}" class="text-xl font-branding gradient-text">
                        {{ config('app.name', 'CMS') }}
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('adiutor.dashboard') }}" 
                       class="nav-link {{ request()->routeIs('adiutor.dashboard') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Dashboard
                    </a>
                    <a href="{{ route('adiutor.projects.index') }}" 
                       class="nav-link {{ request()->routeIs('adiutor.projects.*') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Projects
                    </a>
                    <a href="{{ route('adiutor.tasks.index') }}" 
                       class="nav-link {{ request()->routeIs('adiutor.tasks.*') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Tasks
                    </a>
                    <a href="{{ route('adiutor.clients') }}" 
                       class="nav-link {{ request()->routeIs('adiutor.clients') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Clients
                    </a>
                    <a href="{{ route('adiutor.profile.show') }}" 
                       class="nav-link {{ request()->routeIs('adiutor.profile.*') ? 'text-primary-600' : 'text-neutral-600 hover:text-primary-600' }} transition-colors">
                        Profile
                    </a>
                    
                    <!-- User Menu -->
                    <div class="relative ml-6">
                        <div class="flex items-center space-x-3">
                            <!-- Notifications -->
                            @include('components.notification-bell')
                            
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
                <a href="{{ route('adiutor.dashboard') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Dashboard</a>
                <a href="{{ route('adiutor.projects.index') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Projects</a>
                <a href="{{ route('adiutor.tasks.index') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Tasks</a>
                <a href="{{ route('adiutor.clients') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Clients</a>
                <a href="{{ route('adiutor.profile.show') }}" class="block px-3 py-2 text-neutral-600 hover:text-primary-600">Profile</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="block w-full text-left px-3 py-2 text-red-600">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="p-24 min-h-screen">
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
    
    <!-- Page-specific scripts -->
    @yield('scripts')
    @stack('scripts')
</body>
</html>
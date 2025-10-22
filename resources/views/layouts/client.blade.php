<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - TREIS ADIUTOR</title>
    
    <!-- Firebase Configuration -->
    <script>
        window.firebaseConfig = {
            apiKey: "{{ config('firebase.api_key') }}",
            authDomain: "{{ config('firebase.auth_domain') }}",
            projectId: "{{ config('firebase.project_id') }}",
            storageBucket: "{{ config('firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('firebase.messaging_sender_id') }}",
            appId: "{{ config('firebase.app_id') }}",
            vapidKey: "{{ config('firebase.vapidKey') }}"
        };
    </script>
    
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3a5a78',
                        secondary: '#6c9ab5',
                        accent: '#f9a826',
                        dark: '#1a2a3a',
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-lg fixed w-full z-50 border-b border-gray-100">
        <div class="container mx-auto px-6 py-3">
            <div class="flex justify-between items-center">
                <a href="index.php" class="text-2xl font-bold text-primary flex items-center">
                    <img src="../assets/images/logo.png" alt="Treis Adiutor Logo" class="h-12 mr-3 inline-block rounded-md shadow-sm">
                    <span>Treis <span class="text-accent font-extrabold">Adiutor</span></span>
                </a>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-primary focus:outline-none p-2 rounded-md transition-colors duration-300">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:block">
                    <ul class="flex space-x-8 items-center">
                        @auth
                            <!-- Logged in navigation -->
                            <li><a href="{{ route('client.dashboard') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('client.dashboard') ? 'border-primary text-primary' : 'border-transparent' }}">Home</a></li>
                            <li><a href="{{ route('client.tasks') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('client.tasks') ? 'border-primary text-primary' : 'border-transparent' }}">Tasks</a></li>
                            <li><a href="{{ route('client.requests') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('client.requests') ? 'border-primary text-primary' : 'border-transparent' }}">My Request</a></li>
                            <li><a href="{{ route('client.messages.index') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('client.messages.*') ? 'border-primary text-primary' : 'border-transparent' }}">
                                Messages
                                <span id="unread-badge" class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full" style="display:none;">0</span>
                            </a></li>
                            
                            <!-- Notification Bell -->
                            <li>
                                @include('components.notification-bell')
                            </li>
                            
                            <!-- User profile dropdown -->
                            <li class="relative group">
                                <button class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 border-transparent flex items-center">
                                    <i class="fas fa-user-circle mr-1"></i> Account <i class="fas fa-chevron-down ml-1 text-xs transition-transform duration-300 group-hover:rotate-180"></i>
                                </button>
                                <div class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                                    <a href="{{ route('client.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('client.profile') ? 'bg-gray-100' : '' }}">
                                        <i class="fas fa-user mr-2"></i> Profile
                                    </a>
                                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                </div>
                            </li>
                        @else
                            <!-- Guest navigation -->
                            <li><a href="{{ route('home') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('home') ? 'border-primary text-primary' : 'border-transparent' }}">Home</a></li>
                            <li><a href="{{ route('home') }}#services" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 border-transparent">Services</a></li>
                            <li><a href="{{ route('about') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('about') ? 'border-primary text-primary' : 'border-transparent' }}">About</a></li>
                            <li><a href="{{ route('home') }}#testimonials" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 border-transparent">Testimonials</a></li>
                            <li><a href="{{ route('login') }}" class="ml-2 py-2 px-6 bg-primary text-white hover:bg-primary-dark rounded-md transition-colors duration-300 font-medium shadow-sm">Login</a></li>
                        @endauth
                    </ul>
                </nav>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 bg-gray-50 rounded-lg p-4 shadow-inner">
                <ul class="flex flex-col space-y-3">
                    @auth
                        <!-- Logged in mobile navigation -->
                        <li><a href="{{ route('client.dashboard') }}" class="block py-2 px-3 rounded {{ request()->routeIs('client.dashboard') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <i class="fas fa-home mr-2"></i> Home
                        </a></li>
                        <li><a href="{{ route('client.tasks') }}" class="block py-2 px-3 rounded {{ request()->routeIs('client.tasks') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <i class="fas fa-tasks mr-2"></i> Tasks
                        </a></li>
                        <li><a href="{{ route('client.requests') }}" class="block py-2 px-3 rounded {{ request()->routeIs('client.requests') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <i class="fas fa-tasks mr-2"></i> My Requests
                        </a></li>
                        <li><a href="{{ route('client.messages.index') }}" class="block py-2 px-3 rounded {{ request()->routeIs('client.messages.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <i class="fas fa-comments mr-2"></i> Messages
                        </a></li>
                        <li><a href="{{ route('client.profile') }}" class="block py-2 px-3 rounded {{ request()->routeIs('client.profile') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a></li>
                        <li><a href="{{ route('logout') }}" class="block py-2 px-3 rounded text-gray-700 hover:bg-gray-200 transition-colors duration-300 font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </a></li>
                    @else
                        <!-- Guest mobile navigation -->
                        <li><a href="{{ route('home') }}" class="block py-2 px-3 rounded {{ request()->routeIs('home') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <i class="fas fa-home mr-2"></i> Home
                        </a></li>
                        <li><a href="{{ route('home') }}#services" class="block py-2 px-3 rounded text-gray-700 hover:bg-gray-200 transition-colors duration-300 font-medium">
                            <i class="fas fa-cogs mr-2"></i> Services
                        </a></li>
                        <li><a href="{{ route('about') }}" class="block py-2 px-3 rounded {{ request()->routeIs('about') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <i class="fas fa-info-circle mr-2"></i> About
                        </a></li>
                        <li><a href="{{ route('home') }}#testimonials" class="block py-2 px-3 rounded text-gray-700 hover:bg-gray-200 transition-colors duration-300 font-medium">
                            <i class="fas fa-comment mr-2"></i> Testimonials
                        </a></li>
                        <li><a href="{{ route('login') }}" class="block py-2 px-3 mt-2 bg-primary text-white hover:bg-primary-dark rounded transition-colors duration-300 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login
                        </a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Add JavaScript for mobile menu toggle -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', function() {
                // Toggle the mobile menu
                if (mobileMenu.classList.contains('hidden')) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenuButton.innerHTML = '<i class="fas fa-times text-xl"></i>';
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.innerHTML = '<i class="fas fa-bars text-xl"></i>';
                }
            });
        }
    });
</script>

    @vite(['resources/js/app.js'])

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="index.php" class="text-2xl font-bold mb-4 block">
                        Treis <span class="text-accent">Adiutor</span>
                    </a>
                    <p class="text-gray-400 mb-4">Professional support services tailored to your needs.</p>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/treisadiutor" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.x.com/treisadiutor" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://github.com/treisadiutor" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="https://www.instagram.com/treisadiutor" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="../get-started.php" class="text-gray-400 hover:text-white transition-colors duration-300">Consultation</a></li>
                        <li><a href="../get-started.php" class="text-gray-400 hover:text-white transition-colors duration-300">Project Development</a></li>
                        <li><a href="../get-started.php" class="text-gray-400 hover:text-white transition-colors duration-300">Creative Design</a></li>
                        <li><a href="../get-started.php" class="text-gray-400 hover:text-white transition-colors duration-300">Programming Help</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="../about.php" class="text-gray-400 hover:text-white transition-colors duration-300">About Us</a></li>                        
                        <li><a href="../privacy-policy.php" class="text-gray-400 hover:text-white transition-colors duration-300">Privacy Policy</a></li>
                        <li><a href="../tnc.php" class="text-gray-400 hover:text-white transition-colors duration-300">Terms and Conditions</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400 flex items-start">
                            <i class="fas fa-phone-alt mr-3 mt-1"></i>
                            <span>+63 (976) 102-2819</span>
                        </li>
                        <li class="text-gray-400 flex items-start">
                            <i class="fas fa-envelope mr-3 mt-1"></i>
                            <span>info@treisadiutor.com</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-12 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
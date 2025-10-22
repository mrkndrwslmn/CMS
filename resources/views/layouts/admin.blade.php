<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - CMS</title>
    
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
    <header class="bg-white shadow-md fixed w-full z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold text-primary">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Treis Adiutor Logo" class="h-10 inline-block rounded-md shadow-md">
                    Treis <span class="text-accent">Adiutor</span>
                </a>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-primary text-primary' : '' }}">
                               Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.users.index') }}"
                               class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.users.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                               Users
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.requests.index') }}"
                               class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.requests.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                               Requests
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.budget-requests.index') }}"
                               class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.budget-requests.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                               Budget Requests
                               @php
                                   $pendingCount = \App\Models\BudgetChangeRequest::where('status', 'pending')->count();
                               @endphp
                               @if($pendingCount > 0)
                                   <span class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                               @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.payments.index') }}"
                               class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.payments.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                               Payments
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.messages.index') }}"
                               class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.messages.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                               Messages
                               <span id="unread-badge" class="ml-1 bg-red-500 text-white text-xs px-2 py-0.5 rounded-full" style="display:none;">0</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.index') }}"
                               class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.reports.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                               Reports
                            </a>
                        </li>
                        <li>
                            @include('components.notification-bell')
                        </li>
                        <li class="relative group">
                            <a href="#" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium flex items-center">
                                Profile <i class="fas fa-chevron-down ml-1 text-xs"></i>
                            </a>
                            <ul class="absolute right-0 bg-white shadow-md rounded-md py-2 hidden group-hover:block w-48 z-50">
                                <li><a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-gray-700 hover:text-primary transition-colors duration-300">Manage Account</a></li>
                                <li><a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-700 hover:text-primary transition-colors duration-300">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            
            <!-- Mobile Navigation -->
            <div id="mobile-menu" class="md:hidden hidden mt-4">
                <ul class="flex flex-col space-y-4 pb-4">
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                           class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.dashboard') ? 'border-b-2 border-primary text-primary' : '' }}">
                           Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                           class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.users.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                           Users
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.requests.index') }}"
                           class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.requests.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                           Requests
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.reports.index') }}"
                           class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium {{ request()->routeIs('admin.reports.*') ? 'border-b-2 border-primary text-primary' : '' }}">
                           Reports
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.profile') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Profile</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <a href="{{ route('home') }}" class="text-2xl font-bold mb-4 block">
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
                        <li><a href="/get-started" class="text-gray-400 hover:text-white transition-colors duration-300">Consultation</a></li>
                        <li><a href="/get-started" class="text-gray-400 hover:text-white transition-colors duration-300">Project Development</a></li>
                        <li><a href="/get-started" class="text-gray-400 hover:text-white transition-colors duration-300">Creative Design</a></li>
                        <li><a href="/get-started" class="text-gray-400 hover:text-white transition-colors duration-300">Programming Help</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Company</h3>
                    <ul class="space-y-2">
                        <li><a href="/about" class="text-gray-400 hover:text-white transition-colors duration-300">About Us</a></li>                        
                        <li><a href="/privacy-policy" class="text-gray-400 hover:text-white transition-colors duration-300">Privacy Policy</a></li>
                        <li><a href="/terms" class="text-gray-400 hover:text-white transition-colors duration-300">Terms and Conditions</a></li>
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
    
    @vite(['resources/js/app.js', 'resources/js/messaging.js'])
    
    <script>
        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
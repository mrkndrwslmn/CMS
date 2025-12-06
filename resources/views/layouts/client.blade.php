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
                        <x-lucide-menu class="w-6 h-6" />
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
                            <li><a href="{{ route('client.referrals.dashboard') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('client.referrals.*') ? 'border-primary text-primary' : 'border-transparent' }}">
                                Referrals
                                @php
                                    $pendingReferralsCount = Auth::user()->referralsMade()->where('status', 'pending')->count();
                                @endphp
                                @if($pendingReferralsCount > 0)
                                    <span class="ml-1 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingReferralsCount }}</span>
                                @endif
                            </a></li>
                            <li><a href="{{ route('client.coupons.index') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('client.coupons.*') ? 'border-primary text-primary' : 'border-transparent' }}">
                                Coupons
                                @php
                                    $activeCouponsCount = Auth::user()->coupons()->where('status', 'active')->where('valid_until', '>', now())->count();
                                @endphp
                                @if($activeCouponsCount > 0)
                                    <span class="ml-1 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $activeCouponsCount }}</span>
                                @endif
                            </a></li>
                            <li><a href="{{ route('client.loyalty.dashboard') }}" class="py-2 px-1 text-gray-700 hover:text-primary transition-colors duration-300 font-medium border-b-2 {{ request()->routeIs('client.loyalty.*') ? 'border-primary text-primary' : 'border-transparent' }}">
                                Loyalty
                                @php
                                    $userPoints = Auth::user()->loyalty_points ?? 0;
                                @endphp
                                @if($userPoints > 0)
                                    <span class="ml-1 bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ number_format($userPoints) }}</span>
                                @endif
                            </a></li>
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
                                    <x-lucide-user-circle class="w-4 h-4 mr-1" /> Account <x-lucide-chevron-down class="w-3 h-3 ml-1 transition-transform duration-300 group-hover:rotate-180" />
                                </button>
                                <div class="absolute right-0 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                                    <a href="{{ route('client.profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('client.profile') ? 'bg-gray-100' : '' }}">
                                        <x-lucide-user class="w-4 h-4 mr-2" /> Profile
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <x-lucide-log-out class="w-4 h-4 mr-2" /> Logout
                                        </button>
                                    </form>
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
                        <li><a href="{{ route('client.dashboard') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.dashboard') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-home class="w-4 h-4 mr-2" /> Home
                        </a></li>
                        <li><a href="{{ route('client.tasks') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.tasks') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-list-checks class="w-4 h-4 mr-2" /> Tasks
                        </a></li>
                        <li><a href="{{ route('client.requests') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.requests') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-file-text class="w-4 h-4 mr-2" /> My Requests
                        </a></li>
                        <li><a href="{{ route('client.referrals.dashboard') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.referrals.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-share-2 class="w-4 h-4 mr-2" /> Referrals
                            @php
                                $pendingReferralsCount = Auth::user()->referralsMade()->where('status', 'pending')->count();
                            @endphp
                            @if($pendingReferralsCount > 0)
                                <span class="ml-1 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingReferralsCount }}</span>
                            @endif
                        </a></li>
                        <li><a href="{{ route('client.coupons.index') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.coupons.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-ticket class="w-4 h-4 mr-2" /> Coupons
                            @php
                                $activeCouponsCount = Auth::user()->coupons()->where('status', 'active')->where('valid_until', '>', now())->count();
                            @endphp
                            @if($activeCouponsCount > 0)
                                <span class="ml-1 bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $activeCouponsCount }}</span>
                            @endif
                        </a></li>
                        <li><a href="{{ route('client.loyalty.dashboard') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.loyalty.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-star class="w-4 h-4 mr-2" /> Loyalty
                            @php
                                $userPoints = Auth::user()->loyalty_points ?? 0;
                            @endphp
                            @if($userPoints > 0)
                                <span class="ml-1 bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ number_format($userPoints) }}</span>
                            @endif
                        </a></li>
                        <li><a href="{{ route('client.messages.index') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.messages.*') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-message-circle class="w-4 h-4 mr-2" /> Messages
                        </a></li>
                        <li><a href="{{ route('client.profile') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('client.profile') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-user class="w-4 h-4 mr-2" /> Profile
                        </a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left py-2 px-3 rounded text-gray-700 hover:bg-gray-200 transition-colors duration-300 font-medium">
                                    <x-lucide-log-out class="w-4 h-4 mr-2" /> Logout
                                </button>
                            </form>
                        </li>
                    @else
                        <!-- Guest mobile navigation -->
                        <li><a href="{{ route('home') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('home') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-home class="w-4 h-4 mr-2" /> Home
                        </a></li>
                        <li><a href="{{ route('home') }}#services" class="flex items-center py-2 px-3 rounded text-gray-700 hover:bg-gray-200 transition-colors duration-300 font-medium">
                            <x-lucide-settings class="w-4 h-4 mr-2" /> Services
                        </a></li>
                        <li><a href="{{ route('about') }}" class="flex items-center py-2 px-3 rounded {{ request()->routeIs('about') ? 'bg-primary text-white' : 'text-gray-700 hover:bg-gray-200' }} transition-colors duration-300 font-medium">
                            <x-lucide-info class="w-4 h-4 mr-2" /> About
                        </a></li>
                        <li><a href="{{ route('home') }}#testimonials" class="flex items-center py-2 px-3 rounded text-gray-700 hover:bg-gray-200 transition-colors duration-300 font-medium">
                            <x-lucide-message-square class="w-4 h-4 mr-2" /> Testimonials
                        </a></li>
                        <li><a href="{{ route('login') }}" class="flex items-center py-2 px-3 mt-2 bg-primary text-white hover:bg-primary-dark rounded transition-colors duration-300 font-medium">
                            <x-lucide-log-in class="w-4 h-4 mr-2" /> Login
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
                    mobileMenuButton.innerHTML = '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>';
                } else {
                    mobileMenu.classList.add('hidden');
                    mobileMenuButton.innerHTML = '<svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>';
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
                        <a href="https://www.facebook.com/treisadiutorofficial" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://www.x.com/treisadiutor" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z"/></svg>
                        </a>
                        <a href="https://github.com/treisadiutor" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/treisadiutor" class="text-gray-400 hover:text-white transition-colors duration-300">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
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
                        <li><a href="{{ route('referral-program') }}" class="text-gray-400 hover:text-white transition-colors duration-300 flex items-center">
                            Referral Program
                            <span class="ml-2 px-2 py-0.5 bg-accent text-white text-xs rounded-full">New</span>
                        </a></li>
                        <li><a href="../privacy-policy.php" class="text-gray-400 hover:text-white transition-colors duration-300">Privacy Policy</a></li>
                        <li><a href="../tnc.php" class="text-gray-400 hover:text-white transition-colors duration-300">Terms and Conditions</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400 flex items-start">
                            <x-lucide-phone class="w-4 h-4 mr-3 mt-1" />
                            <span>+63 (976) 102-2819</span>
                        </li>
                        <li class="text-gray-400 flex items-start">
                            <x-lucide-mail class="w-4 h-4 mr-3 mt-1" />
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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Home') - @yield('site_name', 'Treis Adiutor')</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('description', 'We are TREIS ADIUTOR, your trusted programming buddy who can give you premium, professional, and on-time services for both rush and non-rush academic and programming projects.')">
    <meta name="keywords" content="@yield('keywords', 'Treis Adiutor, virtual assistant, written works, programming service, web development, treisadiutor, Philippines')">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    
    <!-- Favicon -->
    <link rel="icon" href="@yield('favicon', '/favicon.svg')" type="image/x-icon">
    
    <!-- Preload Critical Fonts (Branding) -->
    <link rel="preload" href="{{ Vite::asset('resources/fonts/Stereofunk.ttf') }}" as="font" type="font/ttf" crossorigin="anonymous" fetchpriority="high">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS (Local Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/messaging.js'])
    
    <!-- External Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://unpkg.com/gsap@3.12.0/dist/gsap.min.js"></script>
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Analytics -->
    @stack('analytics')
    
    <!-- Firebase Configuration -->
    <script>
        window.firebaseConfig = {
            apiKey: "{{ config('firebase.web_api_key') }}",
            authDomain: "{{ config('firebase.project_id') }}.firebaseapp.com",
            projectId: "{{ config('firebase.project_id') }}",
            storageBucket: "{{ config('firebase.project_id') }}.appspot.com",
            messagingSenderId: "{{ config('firebase.messaging_sender_id') }}",
            appId: "{{ config('firebase.app_id') }}",
            measurementId: "{{ config('firebase.measurement_id') }}"
        };
    </script>
    
    @stack('styles')

    <style>
        @keyframes text-pulse {
            0%, 100% {
                color: rgba(255, 255, 255, 1);
            }
            50% {
                color: rgba(255, 255, 255, 0.7);
            }
        }
        
        .announcement-text-pulse {
            animation: text-pulse 1.5s ease-in-out infinite;
        }

        /* Prevent AOS from hiding content during page load */
        body.aos-preload [data-aos] {
            opacity: 1 !important;
            transform: none !important;
            pointer-events: auto !important;
        }
        
        /* Only apply AOS animations after page is ready */
        body:not(.aos-preload) [data-aos] {
            opacity: 0;
        }
        
        body:not(.aos-preload) [data-aos].aos-animate {
            opacity: 1;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col relative overflow-x-hidden bg-white aos-preload">
  <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
    <!-- Top gradient wash -->
    <div class="absolute inset-x-0 top-0 h-[600px] bg-gradient-to-b from-primary-50/80 via-primary-100/40 to-transparent"></div>
    
    <!-- Animated gradient orbs -->
    <div class="absolute -top-32 -left-24 w-[520px] h-[520px] bg-primary-300/40 blur-[160px] rounded-full animate-float"></div>
    <div class="absolute top-1/4 right-[-120px] w-[420px] h-[420px] bg-accent-400/35 blur-[150px] rounded-full animate-float-delay"></div>
    <div class="absolute bottom-[-160px] left-1/3 w-[560px] h-[560px] bg-secondary-300/30 blur-[180px] rounded-full animate-float-slow"></div>
    
    <!-- Additional accent orbs for vibrancy -->
    <div class="absolute top-1/2 left-[-100px] w-[380px] h-[380px] bg-primary-400/25 blur-[140px] rounded-full animate-float"></div>
    <div class="absolute bottom-32 right-[-80px] w-[440px] h-[440px] bg-accent-300/30 blur-[160px] rounded-full animate-float-delay"></div>
  </div>
    <!-- Header -->
    <header class="bg-white shadow-md fixed w-full z-50">
    <div class="container mx-auto px-4 py-4 max-w-7xl">
        <div class="flex justify-between items-center">
            <a href="/" class="text-2xl font-branding gradient-text tracking-tight smooth-transition group-hover:opacity-80" style="letter-spacing: 0.05em;">
                TREIS <span class="text-accent">ADIUTOR</span>
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
                    @auth
                        <!-- Navigation -->
                        <nav class="bg-white border-b border-neutral-100 fixed left-0 right-0 z-40" style="top: 0;" id="main-nav">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                <div class="flex justify-between items-center h-16">
                                    <!-- Logo -->
                                    <div class="flex items-center">
                                        <a href="{{ route('client.dashboard') }}" class="flex items-center gap-2">
                                            <span class="text-xl font-branding text-primary-600">
                                                {{ config('app.name', 'CMS') }}
                                            </span>
                                        </a>
                                    </div>

                                    <!-- Right Side - Desktop: Only New Request Button + Notifications + Profile Menu -->
                                    <div class="hidden md:flex items-center gap-3">
                                        <!-- New Request Button -->
                                        <a href="{{ route('client.requests.create') }}" 
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors text-sm font-medium">
                                            <x-lucide-plus class="w-4 h-4" />
                                            New Request
                                        </a>
                                        
                                        <!-- Notifications Bell Component -->
                                        @include('components.notification-bell')
                                        
                                        <!-- Profile Menu Dropdown -->
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" 
                                                    class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-neutral-50 transition-colors">
                                                <img src="{{ auth()->user()->getProfilePictureUrl() }}" 
                                                    alt="{{ auth()->user()->fullName }}" 
                                                    class="w-8 h-8 rounded-full ring-2 ring-neutral-100 object-cover">
                                                <x-lucide-chevron-down class="w-4 h-4 text-neutral-400" />
                                            </button>
                                            
                                            <!-- Dropdown Menu -->
                                            <div x-show="open" 
                                                @click.away="open = false"
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="opacity-100 scale-100"
                                                x-transition:leave-end="opacity-0 scale-95"
                                                class="absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-lg border border-neutral-100 py-2 z-50"
                                                style="display: none;">
                                                
                                                <!-- User Info -->
                                                <div class="px-4 py-3 border-b border-neutral-100">
                                                    <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->fullName }}</p>
                                                    <p class="text-xs text-neutral-500 mt-0.5">{{ auth()->user()->email }}</p>
                                                </div>
                                                
                                                <!-- Navigation Links -->
                                                <div class="py-2">
                                                    <a href="{{ route('client.dashboard') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-layout-dashboard class="w-5 h-5" />
                                                        Dashboard
                                                    </a>
                                                                                    
                                                    <a href="{{ url('/services') }}" 
                                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.requests.create') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-search class="w-5 h-5" />
                                                        Browse Services
                                                    </a>

                                                    <a href="{{ route('client.tasks') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.tasks') || request()->routeIs('client.projects.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-folder class="w-5 h-5" />
                                                        My Projects
                                                    </a>
                                                    
                                                    <a href="{{ route('client.documents') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.documents') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-files class="w-5 h-5" />
                                                        Documents
                                                    </a>
                                                    
                                                    <a href="{{ route('client.requests') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.requests*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-clipboard-list class="w-5 h-5" />
                                                        Service Requests
                                                    </a>
                                                    
                                                    <a href="{{ route('client.messages.index') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.messages.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-message-circle class="w-5 h-5" />
                                                        Messages
                                                    </a>
                                                    
                                                    <a href="{{ route('client.payments.history') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.payments.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-credit-card class="w-5 h-5" />
                                                        Payments
                                                    </a>
                                                    
                                                    <a href="{{ route('client.feedback') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.feedback') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-star class="w-5 h-5" />
                                                        Feedback
                                                    </a>
                                                    
                                                    <a href="{{ route('client.revisions.index') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.revisions.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-rotate-ccw class="w-5 h-5" />
                                                        Revisions
                                                        @if(($sidebarStats['pendingRevisionsCount'] ?? 0) > 0)
                                                            <span class="ml-auto bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['pendingRevisionsCount'] }}</span>
                                                        @endif
                                                    </a>
                                                    
                                                    <a href="{{ route('client.notifications.index') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.notifications.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-bell class="w-5 h-5" />
                                                        All Notifications
                                                        @if(($sidebarStats['unreadNotificationsCount'] ?? 0) > 0)
                                                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['unreadNotificationsCount'] }}</span>
                                                        @endif
                                                    </a>
                                                </div>
                                                
                                                <!-- Rewards & Benefits Section -->
                                                <div class="border-t border-neutral-100 py-2">
                                                    <div class="px-4 py-2">
                                                        <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Rewards & Benefits</p>
                                                    </div>
                                                    
                                                    <a href="{{ route('client.referrals.dashboard') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.referrals.dashboard') || request()->routeIs('client.referrals.share') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-users class="w-5 h-5" />
                                                        Referrals
                                                        @if(($sidebarStats['pendingReferralsCount'] ?? 0) > 0)
                                                            <span class="ml-auto bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['pendingReferralsCount'] }}</span>
                                                        @endif
                                                    </a>
                                                    
                                                    <a href="{{ route('client.referrals.history') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 pl-10 text-sm transition-colors {{ request()->routeIs('client.referrals.history') ? 'bg-primary-50 text-primary-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
                                                        <x-lucide-clock class="w-4 h-4" />
                                                        Referral History
                                                    </a>
                                                    
                                                    <a href="{{ route('client.referrals.credits') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 pl-10 text-sm transition-colors {{ request()->routeIs('client.referrals.credits') ? 'bg-primary-50 text-primary-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
                                                        <x-lucide-wallet class="w-4 h-4" />
                                                        Credits & Withdrawals
                                                    </a>
                                                    
                                                    <a href="{{ route('client.coupons.index') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.coupons.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-ticket class="w-5 h-5" />
                                                        Coupons
                                                        @if(($sidebarStats['activeCouponsCount'] ?? 0) > 0)
                                                            <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['activeCouponsCount'] }}</span>
                                                        @endif
                                                    </a>
                                                    
                                                    <a href="{{ route('client.loyalty.dashboard') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('client.loyalty.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                                        <x-lucide-award class="w-5 h-5" />
                                                        Loyalty Program
                                                        @if(($sidebarStats['userPoints'] ?? 0) > 0)
                                                            <span class="ml-auto bg-primary-500 text-white text-xs px-2 py-0.5 rounded-full">{{ number_format($sidebarStats['userPoints']) }}</span>
                                                        @endif
                                                    </a>
                                                    
                                                    <a href="{{ route('client.loyalty.transactions') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 pl-10 text-sm transition-colors {{ request()->routeIs('client.loyalty.transactions') ? 'bg-primary-50 text-primary-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
                                                        <x-lucide-history class="w-4 h-4" />
                                                        Points History
                                                    </a>
                                                </div>
                                                
                                                <!-- Account Section -->
                                                <div class="border-t border-neutral-100 py-2">
                                                    <a href="{{ route('client.profile') }}" 
                                                    class="flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                                        <x-lucide-user class="w-5 h-5" />
                                                        My Profile
                                                    </a>
                                                    
                                                    <form method="POST" action="{{ route('logout') }}">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                            <x-lucide-log-out class="w-5 h-5" />
                                                            Logout
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mobile menu button -->
                                    <div class="md:hidden flex items-center gap-2">
                                        <!-- Notifications Bell Component (Mobile) -->
                                        @include('components.notification-bell')
                                        
                                        <button type="button" onclick="toggleMobileMenu()" class="p-2 text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors">
                                            <x-lucide-menu id="menu-icon" class="w-6 h-6" />
                                            <x-lucide-x id="close-icon" class="w-6 h-6 hidden" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile Navigation -->
                            <div id="mobile-menu" class="md:hidden bg-white border-t border-neutral-100" style="max-height: 0; overflow: hidden; transition: max-height 0.3s ease-in-out;">
                                <div class="px-4 py-3 space-y-1">
                                    <!-- User Info -->
                                    <div class="flex items-center gap-3 px-3 py-3 bg-neutral-50 rounded-xl mb-3">
                                        <img src="{{ auth()->user()->getProfilePictureUrl() }}" 
                                            alt="{{ auth()->user()->fullName }}" 
                                            class="w-10 h-10 rounded-full ring-2 ring-neutral-200 object-cover">
                                        <div>
                                            <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->fullName }}</p>
                                            <p class="text-xs text-neutral-500">{{ auth()->user()->email }}</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Navigation Links -->
                                    <a href="{{ route('client.dashboard') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-layout-dashboard class="w-5 h-5" />
                                        Dashboard
                                    </a>
                                    
                                    <a href="{{ url('/services') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.requests.create') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-search class="w-5 h-5" />
                                        Browse Services
                                    </a>

                                    <a href="{{ route('client.tasks') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.tasks') || request()->routeIs('client.projects.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-folder class="w-5 h-5" />
                                        My Projects
                                    </a>
                                    
                                    <a href="{{ route('client.documents') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.documents') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-files class="w-5 h-5" />
                                        Documents
                                    </a>
                                    
                                    <a href="{{ route('client.requests') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.requests*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-clipboard-list class="w-5 h-5" />
                                        Service Requests
                                    </a>
                                    
                                    <a href="{{ route('client.messages.index') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.messages.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-message-circle class="w-5 h-5" />
                                        Messages
                                    </a>
                                    
                                    <a href="{{ route('client.payments.history') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.payments.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-credit-card class="w-5 h-5" />
                                        Payments
                                    </a>
                                    
                                    <a href="{{ route('client.feedback') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.feedback') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-star class="w-5 h-5" />
                                        Feedback
                                    </a>
                                    
                                    <a href="{{ route('client.revisions.index') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.revisions.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-rotate-ccw class="w-5 h-5" />
                                        Revisions
                                        @if(($sidebarStats['pendingRevisionsCount'] ?? 0) > 0)
                                            <span class="ml-auto bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['pendingRevisionsCount'] }}</span>
                                        @endif
                                    </a>
                                    
                                    <a href="{{ route('client.notifications.index') }}" 
                                    class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.notifications.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                        <x-lucide-bell class="w-5 h-5" />
                                        All Notifications
                                        @if(($sidebarStats['unreadNotificationsCount'] ?? 0) > 0)
                                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['unreadNotificationsCount'] }}</span>
                                        @endif
                                    </a>
                                    
                                    <!-- Rewards & Benefits Section -->
                                    <div class="border-t border-neutral-100 my-2 pt-2">
                                        <div class="px-3 py-2">
                                            <p class="text-xs font-semibold text-neutral-500 uppercase tracking-wider">Rewards & Benefits</p>
                                        </div>
                                        
                                        <a href="{{ route('client.referrals.dashboard') }}" 
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.referrals.dashboard') || request()->routeIs('client.referrals.share') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                            <x-lucide-users class="w-5 h-5" />
                                            Referrals
                                            @if(($sidebarStats['pendingReferralsCount'] ?? 0) > 0)
                                                <span class="ml-auto bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['pendingReferralsCount'] }}</span>
                                            @endif
                                        </a>
                                        
                                        <a href="{{ route('client.referrals.history') }}" 
                                        class="flex items-center gap-3 px-3 py-2 pl-8 rounded-lg transition-colors {{ request()->routeIs('client.referrals.history') ? 'bg-primary-50 text-primary-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
                                            <x-lucide-clock class="w-4 h-4" />
                                            Referral History
                                        </a>
                                        
                                        <a href="{{ route('client.referrals.credits') }}" 
                                        class="flex items-center gap-3 px-3 py-2 pl-8 rounded-lg transition-colors {{ request()->routeIs('client.referrals.credits') ? 'bg-primary-50 text-primary-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
                                            <x-lucide-wallet class="w-4 h-4" />
                                            Credits & Withdrawals
                                        </a>
                                        
                                        <a href="{{ route('client.coupons.index') }}" 
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.coupons.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                            <x-lucide-ticket class="w-5 h-5" />
                                            Coupons
                                            @if(($sidebarStats['activeCouponsCount'] ?? 0) > 0)
                                                <span class="ml-auto bg-green-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $sidebarStats['activeCouponsCount'] }}</span>
                                            @endif
                                        </a>
                                        
                                        <a href="{{ route('client.loyalty.dashboard') }}" 
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('client.loyalty.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                            <x-lucide-star class="w-5 h-5" />
                                            Loyalty Program
                                            @if(($sidebarStats['userPoints'] ?? 0) > 0)
                                                <span class="ml-auto bg-primary-500 text-white text-xs px-2 py-0.5 rounded-full">{{ number_format($sidebarStats['userPoints']) }}</span>
                                            @endif
                                        </a>
                                        
                                        <a href="{{ route('client.loyalty.transactions') }}" 
                                        class="flex items-center gap-3 px-3 py-2 pl-8 rounded-lg transition-colors {{ request()->routeIs('client.loyalty.transactions') ? 'bg-primary-50 text-primary-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
                                            <x-lucide-history class="w-4 h-4" />
                                            Points History
                                        </a>
                                    </div>
                                    
                                    <div class="border-t border-neutral-200 my-2 pt-2">
                                        <a href="{{ route('client.requests.create') }}" 
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary-600 text-white font-medium hover:bg-primary-700 transition-colors">
                                            <x-lucide-plus class="w-5 h-5" />
                                            New Request
                                        </a>
                                    </div>
                                    
                                    <div class="border-t border-neutral-200 my-2 pt-2">
                                        <a href="{{ route('client.profile') }}" 
                                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-neutral-700 hover:bg-neutral-50 transition-colors">
                                            <x-lucide-user class="w-5 h-5" />
                                            My Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}" class="mt-1">
                                            @csrf
                                            <button type="submit" 
                                                    class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-red-600 hover:bg-red-50 transition-colors">
                                                <x-lucide-log-out class="w-5 h-5" />
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </nav>
                    @else
                        <li><a href="{{ route('services') }}" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Services</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium">About</a></li>
                        <li><a href="{{ route('client-testimonials') }}" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Testimonials</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Login</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden">
            <ul class="flex flex-col space-y-4 pb-4">
                @auth
                    <li><a href="{{ route('client.dashboard') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Dashboard</a></li>
                    <li><a href="{{ route('client.profile') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Profile</a></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block text-left w-full text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Logout</button>
                        </form>
                    </li>
                @else
                    <li><a href="{{ route('home') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Home</a></li>
                    <li><a href="{{ route('home') }}#services" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Services</a></li>
                    <li><a href="{{ route('about') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">About</a></li>
                    <li><a href="{{ route('home') }}#testimonials" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Testimonials</a></li>
                    <li><a href="{{ route('login') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</header>

    <!-- Announcements Banner -->
    @if(isset($announcements) && $announcements->count() > 0)
    <div id="announcements-banner" class="bg-primary-600 border-b border-primary-700 fixed top-0 left-0 right-0 z-50">
        @php $announcement = $announcements->first(); @endphp
        <div class="px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-center gap-3">
            <svg class="w-4 h-4 text-primary-200 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
            </svg>
            <div class="text-center announcement-text-pulse">
                <span class="font-semibold text-sm">{{ $announcement->title }}:</span>
                <span class="text-sm ml-2">{{ $announcement->content }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
  <footer class="py-16 relative overflow-hidden bg-neutral-50">
    <div class="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-neutral-200 to-transparent"></div>
    <div class="max-w-7xl mx-auto px-6 relative">
      <div class="flex flex-wrap -mx-4 mb-16">
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-3/12 mb-10 lg:mb-0">
          <a href="/" class="inline-block mb-6">
            <span class="text-2xl font-bold font-branding gradient-text tracking-tight" style="letter-spacing: 0.08em;">TREIS ADIUTOR</span>
          </a>
          <p class="text-neutral-600 mb-6">Your trusted technology partner for innovative startups and forward-thinking businesses.</p>
          <div class="flex space-x-5 items-center">
            <a href="https://www.facebook.com/treisadiutorofficial" class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-neutral-200 hover:border-primary-300 hover:bg-primary-50 transition-all duration-300 text-neutral-600 hover:text-primary-600 shadow-sm"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://www.twitter.com/treisadiutor" class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-neutral-200 hover:border-primary-300 hover:bg-primary-50 transition-all duration-300 text-neutral-600 hover:text-primary-600 shadow-sm"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="https://www.instagram.com/treisadiutor" class="w-10 h-10 rounded-full bg-white flex items-center justify-center border border-neutral-200 hover:border-primary-300 hover:bg-primary-50 transition-all duration-300 text-neutral-600 hover:text-primary-600 shadow-sm"><i class="fa-brands fa-instagram"></i></a>
          </div>
        </div>
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-2/12 mb-10 lg:mb-0">
          <h4 class="text-neutral-800 font-semibold heading-serif mb-6 text-lg">Our Firm</h4>
          <ul class="space-y-3 text-sm">
            <li><a href="{{ url('/services') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">Services</a></li>
            <li><a href="{{ url('/about-us') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">About Us</a></li>
            <li><a href="{{ url('/contact') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">Contact Us</a></li>
          </ul>
        </div>
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-2/12 mb-10 lg:mb-0">
          <h4 class="text-neutral-800 font-semibold heading-serif mb-6 text-lg">Resources</h4>
          <ul class="space-y-3 text-sm">
            <li><a href="{{ url('/featured-projects') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">Projects</a></li>
            <li><a href="{{ url('/client-testimonials') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">Testimonials</a></li>
            <li><a href="{{ url('/referral-program') }}" class="text-neutral-600 hover:text-primary-600 transition-colors flex items-center">
              Referral Program
              <span class="ml-2 px-2 py-0.5 bg-accent text-white text-xs rounded-full font-medium">Earn Rewards</span>
            </a></li>
            <li><a href="{{ url('/faq') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">FAQs</a></li>
          </ul>
        </div>
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-2/12 mb-10 lg:mb-0">
          <h4 class="text-neutral-800 font-semibold heading-serif mb-6 text-lg">Legal</h4>
          <ul class="space-y-3 text-sm">
            <li><a href="{{ url('/privacy-policy') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">Privacy Policy</a></li>
            <li><a href="{{ url('/terms-and-conditions') }}" class="text-neutral-600 hover:text-primary-600 transition-colors">Terms & Conditions</a></li>
          </ul>
        </div>
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-3/12">
          <h4 class="text-neutral-800 font-semibold heading-serif mb-6 text-lg">Get in touch</h4>
          <p class="text-neutral-600 mb-6">Need help with your project? Just drop us a message!</p>
          <a href="{{ url('/contact') }}" class="px-5 py-2.5 rounded-full glass-button border border-primary-200 hover:border-primary-300 text-primary-600 hover:bg-primary-50 transition-all duration-300 inline-flex items-center text-sm shadow-sm">
            Contact Us
            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </a>
        </div>
      </div>
      <div class="border-t border-neutral-200 pt-8">
        <p class="text-center text-sm text-neutral-500">&copy; {{ date('Y') }} Treis Adiutor. All rights reserved.</p>
      </div>
    </div>
  </footer>
  
    <!-- External Scripts -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    
    <!-- Mobile Menu Toggle -->
    <script>
        document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Adjust header position based on announcements banner
        function adjustHeaderPosition() {
            const announcementsBanner = document.getElementById('announcements-banner');
            const header = document.querySelector('header');
            const mainContent = document.querySelector('main');
            
            if (announcementsBanner && header) {
                const bannerHeight = announcementsBanner.offsetHeight;
                header.style.top = bannerHeight + 'px';
                if (mainContent) {
                    mainContent.style.paddingTop = (header.offsetHeight + bannerHeight) + 'px';
                }
            }
        }

        // Run on page load and window resize
        if (document.getElementById('announcements-banner')) {
            adjustHeaderPosition();
            window.addEventListener('resize', adjustHeaderPosition);
        }
    </script>
    
    <!-- Chatbot Widget -->
    @vite(['resources/js/chatbot.js'])
    
    @stack('scripts')
</body>
</html>

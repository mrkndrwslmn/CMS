<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Client Dashboard') - {{ config('app.name', 'CMS') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    
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
    
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/messaging.js'])
    
    <!-- Alpine.js for dropdown functionality -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-neutral-50 antialiased min-h-screen flex flex-col">
    <!-- Announcements Banner - Full Width at Top -->
    @if(isset($announcements) && $announcements->count() > 0)
        <div class="bg-primary-600 border-b border-primary-700 fixed top-0 left-0 right-0 z-50">
            @php $announcement = $announcements->first(); @endphp
            <div class="px-4 sm:px-6 lg:px-8 py-2 flex items-center justify-center gap-3">
                <x-lucide-megaphone class="w-4 h-4 text-primary-200 flex-shrink-0" />
                <div class="text-center text-white">
                    <span class="font-semibold text-sm">{{ $announcement->title }}:</span>
                    <span class="text-sm ml-2 opacity-90">{{ $announcement->content }}</span>
                </div>
            </div>
        </div>
    @endif

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
                            <img src="{{ auth()->user()->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->fullName) }}" 
                                 alt="{{ auth()->user()->fullName }}" 
                                 class="w-8 h-8 rounded-full ring-2 ring-neutral-100">
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
                    <img src="{{ auth()->user()->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->fullName) }}" 
                         alt="{{ auth()->user()->fullName }}" 
                         class="w-10 h-10 rounded-full ring-2 ring-neutral-200">
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
    
    <!-- Main Content -->
    <main class="min-h-screen" id="main-content" style="padding-top: 64px;">
        <script>
            // Adjust main content padding based on announcements banner height
            document.addEventListener('DOMContentLoaded', function() {
                const announcementsBanner = document.querySelector('.bg-primary-600.fixed');
                const mainNav = document.getElementById('main-nav');
                const mainContent = document.getElementById('main-content');
                
                if (announcementsBanner) {
                    const bannerHeight = announcementsBanner.offsetHeight;
                    mainNav.style.top = bannerHeight + 'px';
                    mainContent.style.paddingTop = (bannerHeight + 64) + 'px';
                }
            });
        </script>
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <x-ui.alert type="error">{{ session('error') }}</x-ui.alert>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        function toggleNotifications() {
            const panel = document.getElementById('notification-panel');
            const backdrop = document.getElementById('notification-backdrop');
            
            panel.classList.toggle('show');
            backdrop.classList.toggle('hidden');
            
            // Load notifications when panel opens
            if (panel.classList.contains('show')) {
                loadNotifications();
            }
        }
        
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            const isOpen = menu.style.maxHeight && menu.style.maxHeight !== '0px';
            
            if (isOpen) {
                menu.style.maxHeight = '0';
                menuIcon.classList.remove('hidden');
                closeIcon.classList.add('hidden');
            } else {
                menu.style.maxHeight = menu.scrollHeight + 'px';
                menuIcon.classList.add('hidden');
                closeIcon.classList.remove('hidden');
            }
        }
        
        async function loadNotifications() {
            const container = document.getElementById('notifications-container');
            
            try {
                const response = await fetch('/notifications/fetch', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });
                
                if (!response.ok) throw new Error('Failed to fetch');
                
                const data = await response.json();
                
                if (data.notifications && data.notifications.length > 0) {
                    renderNotifications(data.notifications);
                } else {
                    container.innerHTML = `
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-neutral-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-neutral-500">No notifications yet</p>
                            <p class="text-neutral-400 text-sm mt-1">We'll notify you when something important happens</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
            container.innerHTML = `
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-neutral-500">Failed to load notifications</p>
                        <button onclick="loadNotifications()" class="mt-3 text-primary-600 hover:text-primary-700 text-sm font-medium">Try Again</button>
                    </div>
                `;
            }
        }
        
        function renderNotifications(notifications) {
            const container = document.getElementById('notifications-container');
            
            container.innerHTML = notifications.map(notification => {
                const isUnread = !notification.read_at;
                const icon = getNotificationIcon(notification.type);
                const timeAgo = formatTimeAgo(notification.created_at);
                
                return `
                    <div class="mb-3 p-4 rounded-lg border ${isUnread ? 'bg-primary-50 border-primary-200' : 'bg-white border-neutral-200'} hover:shadow-sm transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full ${isUnread ? 'bg-primary-100' : 'bg-neutral-100'} flex items-center justify-center">
                                ${icon}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-neutral-900">${notification.data.title || 'Notification'}</p>
                                <p class="text-sm text-neutral-600 mt-1">${notification.data.message || ''}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-neutral-500">${timeAgo}</span>
                                    ${isUnread ? '<span class="text-xs font-medium text-primary-600">New</span>' : ''}
                                </div>
                                ${notification.data.action_url ? `
                                    <a href="${notification.data.action_url}" class="inline-block mt-2 text-xs text-primary-600 hover:text-primary-700 font-medium">
                                        View Details →
                                    </a>
                                ` : ''}
                            </div>
                            ${isUnread ? `
                                <button onclick="markAsRead('${notification.id}')" class="flex-shrink-0 text-neutral-400 hover:text-neutral-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        function getNotificationIcon(type) {
            const icons = {
                'payment': '<svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>',
                'project': '<svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"></path><path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"></path></svg>',
                'request': '<svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>',
                'message': '<svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path></svg>',
                'meeting': '<svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>',
                'default': '<svg class="w-5 h-5 text-neutral-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>'
            };
            
            return icons[type] || icons.default;
        }
        
        function formatTimeAgo(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const seconds = Math.floor((now - date) / 1000);
            
            if (seconds < 60) return 'Just now';
            if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
            if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
            if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;
            
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }
        
        async function markAsRead(notificationId) {
            try {
                const response = await fetch(`/notifications/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    }
                });
                
                if (response.ok) {
                    loadNotifications(); // Reload notifications
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }
    </script>

    @stack('scripts')
    
    <!-- Global Alert System -->
    <x-ui.alert-manager />
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Adiutor Dashboard') - {{ config('app.name', 'CMS') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="@yield('favicon', '/favicon.svg')" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Local Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Firebase Configuration -->
    <script>
        window.firebaseConfig = {
            apiKey: "{{ config('firebase.api_key') }}",
            authDomain: "{{ config('firebase.auth_domain') }}",
            projectId: "{{ config('firebase.project_id') }}",
            storageBucket: "{{ config('firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('firebase.messaging_sender_id') }}",
            appId: "{{ config('firebase.app_id') }}",
            measurementId: "{{ config('firebase.measurement_id') }}",
            vapidKey: "{{ config('firebase.vapidKey') }}"
        };
    </script>
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
                    <a href="{{ route('adiutor.dashboard') }}" class="flex items-center gap-2">
                        <span class="text-xl font-branding text-primary-600">
                            {{ config('app.name', 'CMS') }}
                        </span>
                    </a>
                </div>

                <!-- Right Side - Desktop: Quick Actions + Notifications + Profile Menu -->
                <div class="hidden md:flex items-center gap-3">
                    <!-- Quick Actions -->
                    <div class="flex items-center gap-2">
                        <a href="{{ route('adiutor.projects.index') }}?action=new" 
                           class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors text-sm font-medium">
                            <x-lucide-folder-plus class="w-4 h-4" />
                            New Project
                        </a>
                        
                        <a href="{{ route('adiutor.tasks.index') }}?action=create" 
                           class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50 rounded-lg transition-colors text-sm font-medium">
                            <x-lucide-plus class="w-4 h-4" />
                            Add Task
                        </a>
                    </div>
                    
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
                                <p class="text-xs text-primary-600 mt-1 font-medium">Adiutor Account</p>
                            </div>
                            
                            <!-- Navigation Links -->
                            <div class="py-2">
                                <a href="{{ route('adiutor.dashboard') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                    <x-lucide-layout-dashboard class="w-5 h-5" />
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('adiutor.projects.index') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.projects.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                    <x-lucide-folder class="w-5 h-5" />
                                    My Projects
                                </a>
                                
                                <a href="{{ route('adiutor.tasks.index') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.tasks.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                    <x-lucide-check-square class="w-5 h-5" />
                                    My Tasks
                                </a>
                                
                <a href="{{ route('adiutor.time-tracking.index') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.time-tracking.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-clock class="w-5 h-5" />
                    Time Tracking
                </a>
                
                <a href="{{ route('adiutor.earnings.index') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.earnings.index') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-wallet class="w-5 h-5" />
                    My Earnings
                </a>
                
                <a href="{{ route('adiutor.earnings.wallet') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.earnings.wallet') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-credit-card class="w-5 h-5" />
                    Wallet
                </a>
                
                <a href="{{ route('adiutor.earnings.payouts') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.earnings.payouts', 'adiutor.earnings.payout.show') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-banknote class="w-5 h-5" />
                    Payout History
                </a>
                
                <a href="{{ route('adiutor.hour-requests.index') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.hour-requests.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-clock-plus class="w-5 h-5" />
                    Hour Requests
                </a>
                
                <a href="{{ route('adiutor.budget-requests.index') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.budget-requests.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-receipt class="w-5 h-5" />
                    Budget Requests
                </a>
                
                <a href="{{ route('adiutor.clients') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.clients') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-users class="w-5 h-5" />
                    Clients
                </a>
                
                <a href="{{ route('adiutor.group-chats.index') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.group-chats.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-messages-square class="w-5 h-5" />
                    Group Chats
                </a>
                
                <a href="{{ url('/calendar') }}" 
                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->is('calendar*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-calendar class="w-5 h-5" />
                    Calendar
                </a>
                                <a href="{{ route('adiutor.revisions.index') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.revisions.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                    <x-lucide-rotate-ccw class="w-5 h-5" />
                                    Revisions
                                </a>
                                
                                <a href="{{ route('adiutor.documents') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.documents') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                    <x-lucide-file-text class="w-5 h-5" />
                                    Documents
                                </a>
                                
                                <a href="{{ route('adiutor.feedback') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm transition-colors {{ request()->routeIs('adiutor.feedback') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                                    <x-lucide-star class="w-5 h-5" />
                                    Feedback
                                </a>
                            </div>
                            
                            <!-- Account Section -->
                            <div class="border-t border-neutral-100 py-2">
                                <a href="{{ route('adiutor.notifications.index') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors {{ request()->routeIs('adiutor.notifications.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                                    <x-lucide-bell class="w-5 h-5" />
                                    All Notifications
                                </a>
                                
                                <a href="{{ route('adiutor.profile.show') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                    <x-lucide-user class="w-5 h-5" />
                                    My Profile
                                </a>
                                
                                <a href="{{ route('adiutor.profile.earnings') }}" 
                                   class="flex items-center gap-3 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                    <x-lucide-settings class="w-5 h-5" />
                                    Earnings Settings
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
                        <p class="text-xs text-primary-600 font-medium">Adiutor Account</p>
                    </div>
                </div>
                
                <!-- Navigation Links -->
                <a href="{{ route('adiutor.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.dashboard') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-layout-dashboard class="w-5 h-5" />
                    Dashboard
                </a>
                
                <a href="{{ route('adiutor.projects.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.projects.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-folder class="w-5 h-5" />
                    My Projects
                </a>
                
                <a href="{{ route('adiutor.tasks.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.tasks.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-check-square class="w-5 h-5" />
                    My Tasks
                </a>
                
                <a href="{{ route('adiutor.time-tracking.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.time-tracking.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-clock class="w-5 h-5" />
                    Time Tracking
                </a>
                
                <a href="{{ route('adiutor.earnings.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.earnings.index') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-wallet class="w-5 h-5" />
                    My Earnings
                </a>
                
                <a href="{{ route('adiutor.earnings.wallet') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.earnings.wallet') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-credit-card class="w-5 h-5" />
                    Wallet
                </a>
                
                <a href="{{ route('adiutor.earnings.payouts') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.earnings.payouts', 'adiutor.earnings.payout.show') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-banknote class="w-5 h-5" />
                    Payout History
                </a>
                
                <a href="{{ route('adiutor.hour-requests.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.hour-requests.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-clock-plus class="w-5 h-5" />
                    Hour Requests
                </a>
                
                <a href="{{ route('adiutor.budget-requests.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.budget-requests.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-receipt class="w-5 h-5" />
                    Budget Requests
                </a>
                
                <a href="{{ route('adiutor.clients') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.clients') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-users class="w-5 h-5" />
                    Clients
                </a>
                
                <a href="{{ route('adiutor.group-chats.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.group-chats.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-messages-square class="w-5 h-5" />
                    Group Chats
                </a>
                
                <a href="{{ url('/calendar') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->is('calendar*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-calendar class="w-5 h-5" />
                    Calendar
                </a>
                
                <a href="{{ route('adiutor.revisions.index') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.revisions.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-rotate-ccw class="w-5 h-5" />
                    Revisions
                </a>
                
                <a href="{{ route('adiutor.documents') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.documents') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-file-text class="w-5 h-5" />
                    Documents
                </a>
                
                <a href="{{ route('adiutor.feedback') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.feedback') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                    <x-lucide-star class="w-5 h-5" />
                    Feedback
                </a>
                
                <div class="border-t border-neutral-100 my-2 pt-2">
                    <a href="{{ route('adiutor.projects.index') }}?action=new" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary-600 text-white font-medium mb-2">
                        <x-lucide-folder-plus class="w-5 h-5" />
                        New Project
                    </a>
                    
                    <a href="{{ route('adiutor.tasks.index') }}?action=create" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg border border-neutral-200 text-neutral-700 font-medium hover:bg-neutral-50 transition-colors">
                        <x-lucide-plus class="w-5 h-5" />
                        Add Task
                    </a>
                </div>
                
                <div class="border-t border-neutral-100 my-2 pt-2">
                    <a href="{{ route('adiutor.notifications.index') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('adiutor.notifications.*') ? 'bg-primary-50 text-primary-700' : 'text-neutral-700 hover:bg-neutral-50' }}">
                        <x-lucide-bell class="w-5 h-5" />
                        All Notifications
                    </a>
                    <a href="{{ route('adiutor.profile.show') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-neutral-700 hover:bg-neutral-50 transition-colors">
                        <x-lucide-user class="w-5 h-5" />
                        My Profile
                    </a>
                    <a href="{{ route('adiutor.profile.earnings') }}" 
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-neutral-700 hover:bg-neutral-50 transition-colors">
                        <x-lucide-settings class="w-5 h-5" />
                        Earnings Settings
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
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500">No notifications yet</p>
                            <p class="text-gray-400 text-sm mt-1">We'll notify you when something important happens</p>
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
                        <p class="text-gray-500">Failed to load notifications</p>
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
                    <div class="mb-3 p-4 rounded-lg border ${isUnread ? 'bg-primary-50 border-primary-200' : 'bg-white border-gray-200'} hover:shadow-sm transition-shadow">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full ${isUnread ? 'bg-primary-100' : 'bg-gray-100'} flex items-center justify-center">
                                ${icon}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">${notification.data.title || 'Notification'}</p>
                                <p class="text-sm text-gray-600 mt-1">${notification.data.message || ''}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-gray-500">${timeAgo}</span>
                                    ${isUnread ? '<span class="text-xs font-medium text-primary-600">New</span>' : ''}
                                </div>
                                ${notification.data.action_url ? `
                                    <a href="${notification.data.action_url}" class="inline-block mt-2 text-xs text-primary-600 hover:text-primary-700 font-medium">
                                        View Details →
                                    </a>
                                ` : ''}
                            </div>
                            ${isUnread ? `
                                <button onclick="markAsRead('${notification.id}')" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
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
                'task': '<svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path></svg>',
                'revision': '<svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path></svg>',
                'message': '<svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path></svg>',
                'default': '<svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>'
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
    
    <!-- Page-specific scripts -->
    @yield('scripts')
    @stack('scripts')
    
    <!-- Global Alert System -->
    <x-ui.alert-manager />
</body>
</html>
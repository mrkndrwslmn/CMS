<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Adiutor Dashboard') - {{ config('app.name', 'CMS') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" href="@yield('favicon', '/favicon.svg')" type="image/x-icon">
    
    <!-- Preload Critical Fonts (Branding) -->
    <link rel="preload" href="{{ Vite::asset('resources/fonts/Stereofunk.ttf') }}" as="font" type="font/ttf" crossorigin="anonymous" fetchpriority="high">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (Local Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js with Collapse plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
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

    <style>
        [x-cloak] { display: none !important; }
        nav.overflow-y-auto { -ms-overflow-style: none; scrollbar-width: none; }
        nav.overflow-y-auto::-webkit-scrollbar { display: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-neutral-50 font-sans antialiased">
    <!-- Announcements Banner - Full Width at Top -->
    @if(isset($announcements) && $announcements->count() > 0)
        <div class="bg-primary-600 border-b border-primary-700 fixed top-0 left-0 right-0 z-50" id="announcement-banner">
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

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar-wrapper" class="w-64 fixed inset-y-0 left-0 bg-white border-r border-neutral-100 shadow-sm transition-transform duration-300 ease-in-out z-30 -translate-x-full md:translate-x-0" style="top: 0;">
            <!-- Brand Header -->
            <div class="h-16 flex items-center justify-between border-b border-neutral-100 px-4">
                <a href="{{ route('adiutor.dashboard') }}" class="flex items-center gap-2 group">
                    <span class="text-xl font-branding text-primary-500 tracking-wide group-hover:text-primary-600 transition-colors">
                        {{ config('app.name', 'CMS') }}
                    </span>
                </a>
                <!-- Mobile close button -->
                <button class="md:hidden p-1.5 text-neutral-400 hover:text-neutral-600 hover:bg-neutral-100 rounded-lg transition-colors" id="sidebar-close">
                    <x-lucide-x class="w-5 h-5" />
                </button>
            </div>
            
            <nav class="mt-2 px-3 overflow-y-auto pb-6" style="max-height: calc(100vh - 140px);" x-data="sidebarNav()">
                <!-- Dashboard -->
                <a href="{{ route('adiutor.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg mb-1 transition-all duration-200 {{ request()->routeIs('adiutor.dashboard') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-layout-dashboard class="w-5 h-5" />
                    <span>Dashboard</span>
                </a>
                
                <a href="{{ url('/calendar') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg mb-1 transition-all duration-200 {{ request()->is('calendar*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-calendar class="w-5 h-5" />
                    <span>Calendar</span>
                </a>
                
                <!-- Section: Work -->
                <div class="mt-5 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Work</span>
                </div>
                
                <!-- Work Items -->
                <div class="mb-0.5">
                    <button @click="toggle('work')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('adiutor.projects.*', 'adiutor.tasks.*', 'adiutor.time-tracking.*', 'adiutor.revisions.*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-briefcase class="w-5 h-5" />
                            <span>My Work</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.work }" />
                    </button>
                    <div x-show="openSections.work" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('adiutor.projects.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.projects.*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-folder class="w-4 h-4" />
                            <span>Projects</span>
                        </a>
                        <a href="{{ route('adiutor.tasks.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.tasks.*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-check-square class="w-4 h-4" />
                            <span>Tasks</span>
                        </a>
                        <a href="{{ route('adiutor.time-tracking.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.time-tracking.*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-clock class="w-4 h-4" />
                            <span>Time Tracking</span>
                        </a>
                        <a href="{{ route('adiutor.revisions.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.revisions.*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-rotate-ccw class="w-4 h-4" />
                            <span>Revisions</span>
                        </a>
                    </div>
                </div>
                
                <!-- Section: Earnings -->
                <div class="mt-5 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Earnings</span>
                </div>
                
                <!-- Earnings Items -->
                <div class="mb-0.5">
                    <button @click="toggle('earnings')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('adiutor.earnings.*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-wallet class="w-5 h-5" />
                            <span>Earnings</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.earnings }" />
                    </button>
                    <div x-show="openSections.earnings" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('adiutor.earnings.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.earnings.index') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-trending-up class="w-4 h-4" />
                            <span>My Earnings</span>
                        </a>
                        <a href="{{ route('adiutor.earnings.wallet') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.earnings.wallet') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-credit-card class="w-4 h-4" />
                            <span>Wallet</span>
                        </a>
                        <a href="{{ route('adiutor.earnings.payouts') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.earnings.payouts', 'adiutor.earnings.payout.show') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-banknote class="w-4 h-4" />
                            <span>Payout History</span>
                        </a>
                    </div>
                </div>
                
                <!-- Section: Requests -->
                <div class="mt-5 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Requests</span>
                </div>
                
                <!-- Requests Items -->
                <a href="{{ route('adiutor.hour-requests.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg mb-0.5 transition-all duration-200 {{ request()->routeIs('adiutor.hour-requests.*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-clock-plus class="w-5 h-5" />
                    <span>Hour Requests</span>
                </a>
                
                <a href="{{ route('adiutor.budget-requests.index') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg mb-0.5 transition-all duration-200 {{ request()->routeIs('adiutor.budget-requests.*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-receipt class="w-5 h-5" />
                    <span>Budget Requests</span>
                </a>
                
                <!-- Section: Communication -->
                <div class="mt-5 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Communication</span>
                </div>
                
                <!-- Communication Items -->
                <div class="mb-0.5">
                    <button @click="toggle('communication')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('adiutor.clients', 'adiutor.group-chats.*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-message-circle class="w-5 h-5" />
                            <span>Messages</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.communication }" />
                    </button>
                    <div x-show="openSections.communication" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('adiutor.clients') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.clients') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-users class="w-4 h-4" />
                            <span>Clients</span>
                        </a>
                        <a href="{{ route('adiutor.group-chats.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.group-chats.*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-messages-square class="w-4 h-4" />
                            <span>Group Chats</span>
                        </a>
                    </div>
                </div>
                
                <!-- Resources -->
                <a href="{{ route('adiutor.documents') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg mb-0.5 transition-all duration-200 {{ request()->routeIs('adiutor.documents') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-file-text class="w-5 h-5" />
                    <span>Documents</span>
                </a>
                
                <a href="{{ route('adiutor.feedback') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg mb-0.5 transition-all duration-200 {{ request()->routeIs('adiutor.feedback') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-star class="w-5 h-5" />
                    <span>Feedback</span>
                </a>
                
                <!-- Referral Program Section -->
                <div class="mt-5 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Referrals</span>
                </div>
                
                <div class="mb-0.5">
                    <button @click="toggle('referrals')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('adiutor.referrals.*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-gift class="w-5 h-5" />
                            <span>Referral Program</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.referrals }" />
                    </button>
                    <div x-show="openSections.referrals" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('adiutor.referrals.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.referrals.dashboard') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-layout-dashboard class="w-4 h-4" />
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('adiutor.referrals.share') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.referrals.share') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-share-2 class="w-4 h-4" />
                            <span>Share & Invite</span>
                        </a>
                        <a href="{{ route('adiutor.referrals.credits') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.referrals.credits') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-circle-dollar-sign class="w-4 h-4" />
                            <span>Credits</span>
                        </a>
                        <a href="{{ route('adiutor.referrals.history') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.referrals.history') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-clock class="w-4 h-4" />
                            <span>History</span>
                        </a>
                    </div>
                </div>
                
                <!-- Divider -->
                <div class="my-4 border-t border-neutral-100"></div>
                
                <!-- Account Section -->
                <div class="mb-0.5">
                    <button @click="toggle('account')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('adiutor.profile.*', 'adiutor.notifications.*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-settings class="w-5 h-5" />
                            <span>Account</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.account }" />
                    </button>
                    <div x-show="openSections.account" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('adiutor.profile.show') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.profile.show') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-user class="w-4 h-4" />
                            <span>My Profile</span>
                        </a>
                        <a href="{{ route('adiutor.profile.earnings') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.profile.earnings') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-sliders class="w-4 h-4" />
                            <span>Earnings Settings</span>
                        </a>
                        <a href="{{ route('adiutor.notifications.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('adiutor.notifications.*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-bell class="w-4 h-4" />
                            <span>All Notifications</span>
                        </a>
                    </div>
                </div>
                
                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50 transition-all duration-200">
                        <x-lucide-log-out class="w-5 h-5" />
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-20 md:hidden hidden"></div>

        <!-- Main Content Area -->
        <main id="page-content-wrapper" class="flex-1 md:ml-64 min-h-screen transition-all duration-300">
            <!-- Top Navigation -->
            <header class="h-16 bg-white border-b border-neutral-100 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button class="md:hidden p-2 -ml-2 text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors" id="menu-toggle">
                        <x-lucide-menu class="w-5 h-5" />
                    </button>
                    <h1 class="text-lg font-semibold text-neutral-900">
                        @yield('page-title', 'Dashboard')
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Notification Bell -->
                    @include('components.notification-bell')
                    
                    <!-- Quick User Menu (Mobile-friendly) -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-neutral-50 transition-colors">
                            <img src="{{ auth()->user()->getProfilePictureUrl() }}" 
                                 alt="{{ auth()->user()->fullName }}" 
                                 class="w-8 h-8 rounded-full ring-2 ring-neutral-100 object-cover">
                            <x-lucide-chevron-down class="w-4 h-4 text-neutral-400 hidden sm:block" />
                        </button>
                        
                        <div x-show="open" 
                             @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-neutral-100 py-1 z-50" 
                             style="display: none;">
                            <div class="px-4 py-2 border-b border-neutral-100">
                                <p class="text-sm font-medium text-neutral-900">{{ auth()->user()->fullName }}</p>
                                <p class="text-xs text-neutral-500">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('adiutor.profile.show') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                <x-lucide-user class="w-4 h-4 text-neutral-400" />
                                Profile
                            </a>
                            <a href="{{ route('adiutor.profile.earnings') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                <x-lucide-settings class="w-4 h-4 text-neutral-400" />
                                Settings
                            </a>
                            <div class="border-t border-neutral-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <x-lucide-log-out class="w-4 h-4" />
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        // Mobile sidebar toggle
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebar = document.getElementById('sidebar-wrapper');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        
        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
            sidebarOverlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden', 'md:overflow-auto');
        }
        
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            sidebarOverlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
        
        menuToggle?.addEventListener('click', openSidebar);
        sidebarClose?.addEventListener('click', closeSidebar);
        sidebarOverlay?.addEventListener('click', closeSidebar);
        
        // Handle announcements banner offset
        document.addEventListener('DOMContentLoaded', function() {
            const announcementBanner = document.getElementById('announcement-banner');
            if (announcementBanner) {
                const bannerHeight = announcementBanner.offsetHeight;
                sidebar.style.top = bannerHeight + 'px';
                sidebar.style.height = `calc(100vh - ${bannerHeight}px)`;
                document.getElementById('page-content-wrapper').querySelector('header').style.top = bannerHeight + 'px';
            }
        });

        // Alpine.js sidebar navigation component
        function sidebarNav() {
            return {
                openSections: {
                    work: {{ request()->routeIs('adiutor.projects.*', 'adiutor.tasks.*', 'adiutor.time-tracking.*', 'adiutor.revisions.*') ? 'true' : 'false' }},
                    earnings: {{ request()->routeIs('adiutor.earnings.*') ? 'true' : 'false' }},
                    communication: {{ request()->routeIs('adiutor.clients', 'adiutor.group-chats.*') ? 'true' : 'false' }},
                    referrals: {{ request()->routeIs('adiutor.referrals.*') ? 'true' : 'false' }},
                    account: {{ request()->routeIs('adiutor.profile.*', 'adiutor.notifications.*') ? 'true' : 'false' }}
                },
                toggle(section) {
                    this.openSections[section] = !this.openSections[section];
                }
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Treis Adiutor CMS</title>
    
    <!-- Favicon -->
    <link rel="icon" href="@yield('favicon', '/favicon.svg')" type="image/x-icon">
    
    <!-- Preload Critical Fonts (Branding) -->
    <link rel="preload" href="{{ Vite::asset('resources/fonts/Stereofunk.ttf') }}" as="font" type="font/ttf" crossorigin="anonymous" fetchpriority="high">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
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
    
    <!-- Tailwind CSS (Local Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/messaging.js'])
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    
    <!-- Alpine.js with Collapse plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        nav.overflow-y-auto { -ms-overflow-style: none; scrollbar-width: none; }
        nav.overflow-y-auto::-webkit-scrollbar { display: none; }
    </style>
    @stack('styles')
</head>
<body class="bg-neutral-50 font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar-wrapper" class="w-64 fixed inset-y-0 left-0 bg-white border-r border-neutral-100 shadow-sm transition-transform duration-300 ease-in-out z-30 -translate-x-full md:translate-x-0">
            <!-- Brand Header -->
            <div class="h-16 flex items-center justify-center border-b border-neutral-100 px-6">
                <a href="/" class="flex items-center gap-2 group">
                    <span class="text-xl font-branding text-primary-500 tracking-wide group-hover:text-primary-600 transition-colors">
                        TREIS ADIUTOR
                    </span>
                </a>
            </div>
            
            <nav class="mt-4 px-3 overflow-y-auto pb-6" style="max-height: calc(100vh - 64px);" x-data="sidebarNav()">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg mb-1 transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-layout-dashboard class="w-5 h-5" />
                    <span>Dashboard</span>
                </a>
                
                <!-- Section: Management -->
                <div class="mt-6 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Management</span>
                </div>
                
                <!-- User Management -->
                <div class="mb-0.5">
                    <button @click="toggle('users')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.users*', 'admin.clients*', 'admin.adiutors*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-users class="w-5 h-5" />
                            <span>Users</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.users }" />
                    </button>
                    <div x-show="openSections.users" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.users*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-user class="w-4 h-4" />
                            <span>All Users</span>
                        </a>
                        <a href="{{ route('admin.adiutors.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.adiutors*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-hard-hat class="w-4 h-4" />
                            <span>Adiutors</span>
                        </a>
                        <a href="{{ route('admin.clients.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.clients*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-briefcase class="w-4 h-4" />
                            <span>Clients</span>
                        </a>
                    </div>
                </div>
                
                <!-- Projects -->
                <div class="mb-0.5">
                    <button @click="toggle('projects')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.requests*', 'admin.projects*', 'admin.tasks*', 'admin.budget-requests*', 'admin.revisions*', 'admin.calendar*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-folder-kanban class="w-5 h-5" />
                            <span>Projects</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.projects }" />
                    </button>
                    <div x-show="openSections.projects" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.requests.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.requests*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-clipboard-list class="w-4 h-4" />
                            <span>Service Requests</span>
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.projects*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-folder class="w-4 h-4" />
                            <span>Projects</span>
                        </a>
                        <a href="{{ route('admin.tasks.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.tasks*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-check-square class="w-4 h-4" />
                            <span>Tasks</span>
                        </a>
                        <a href="{{ route('admin.budget-requests.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.budget-requests*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-banknote class="w-4 h-4" />
                            <span>Budget Requests</span>
                        </a>
                        <a href="{{ route('admin.revisions.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.revisions*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-rotate-ccw class="w-4 h-4" />
                            <span>Revisions</span>
                        </a>
                        <a href="{{ route('admin.calendar.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.calendar*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-calendar class="w-4 h-4" />
                            <span>Calendar</span>
                        </a>
                    </div>
                </div>
                
                <!-- Section: Financial -->
                <div class="mt-6 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Financial</span>
                </div>
                
                <!-- Payments & Payouts -->
                <div class="mb-0.5">
                    <button @click="toggle('finance')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.payments*', 'admin.payouts*', 'admin.earnings-analytics*', 'admin.hour-requests*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-wallet class="w-5 h-5" />
                            <span>Payments</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.finance }" />
                    </button>
                    <div x-show="openSections.finance" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.earnings-analytics.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.earnings-analytics.index') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-pie-chart class="w-4 h-4" />
                            <span>Earnings Overview</span>
                        </a>
                        <a href="{{ route('admin.earnings-analytics.leaderboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.earnings-analytics.leaderboard') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-trophy class="w-4 h-4" />
                            <span>Earnings Leaderboard</span>
                        </a>
                        <a href="{{ route('admin.earnings-analytics.project-costs') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.earnings-analytics.project-costs') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-calculator class="w-4 h-4" />
                            <span>Project Costs</span>
                        </a>
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.payments*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-credit-card class="w-4 h-4" />
                            <span>Payments</span>
                        </a>
                        <a href="{{ route('admin.payouts.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.payouts.index', 'admin.payouts.show') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-landmark class="w-4 h-4" />
                            <span>Payouts</span>
                        </a>
                        <a href="{{ route('admin.payouts.time-entry-approvals') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.payouts.time-entry-approvals', 'admin.payouts.adiutor-earnings') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-clock class="w-4 h-4" />
                            <span>Time Entry Approvals</span>
                            @php
                                $pendingTimeEntries = \App\Models\TimeEntry::whereNotNull('end_time')->where('is_approved', false)->count();
                            @endphp
                            @if($pendingTimeEntries > 0)
                                <span class="ml-auto px-1.5 py-0.5 text-xs font-medium bg-warning-100 text-warning-700 rounded-full">{{ $pendingTimeEntries }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.earnings-analytics.payout-history') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.earnings-analytics.payout-history') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-history class="w-4 h-4" />
                            <span>Payout History</span>
                        </a>
                        <a href="{{ route('admin.hour-requests.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.hour-requests*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-clock class="w-4 h-4" />
                            <span>Hour Requests</span>
                        </a>
                    </div>
                </div>
                
                <!-- Section: Engagement -->
                <div class="mt-6 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Engagement</span>
                </div>
                
                <!-- Communication -->
                <div class="mb-0.5">
                    <button @click="toggle('communication')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.messages*', 'admin.feedback*', 'admin.announcements*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-message-circle class="w-5 h-5" />
                            <span>Communication</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.communication }" />
                    </button>
                    <div x-show="openSections.communication" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.messages*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <div class="flex items-center gap-2">
                                <x-lucide-mail class="w-4 h-4" />
                                <span>Messages</span>
                            </div>
                            @if(auth()->user()->unreadMessagesCount() > 0)
                                <span class="bg-primary-500 text-white text-xs font-medium rounded-full px-2 py-0.5">
                                    {{ auth()->user()->unreadMessagesCount() }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.feedback.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.feedback.index', 'admin.feedback.show') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-star class="w-4 h-4" />
                            <span>Feedback</span>
                        </a>
                        <a href="{{ route('admin.feedback.analytics') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.feedback.analytics') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-bar-chart-2 class="w-4 h-4" />
                            <span>Feedback Analytics</span>
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.announcements*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-megaphone class="w-4 h-4" />
                            <span>Announcements</span>
                        </a>
                    </div>
                </div>
                
                <!-- Rewards -->
                <div class="mb-0.5">
                    <button @click="toggle('rewards')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.coupons*', 'admin.loyalty*', 'admin.referrals*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-gift class="w-5 h-5" />
                            <span>Rewards</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.rewards }" />
                    </button>
                    <div x-show="openSections.rewards" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.coupons.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.coupons*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-ticket class="w-4 h-4" />
                            <span>Coupons</span>
                        </a>
                        <a href="{{ route('admin.loyalty.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.loyalty.index', 'admin.loyalty.show') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-award class="w-4 h-4" />
                            <span>Loyalty</span>
                        </a>
                        <a href="{{ route('admin.loyalty.leaderboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.loyalty.leaderboard') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-trophy class="w-4 h-4" />
                            <span>Loyalty Leaderboard</span>
                        </a>
                        <a href="{{ route('admin.referrals.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.referrals.index', 'admin.referrals.show') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-users class="w-4 h-4" />
                            <span>Referrals</span>
                        </a>
                        <a href="{{ route('admin.referrals.withdrawals.pending') }}" class="flex items-center justify-between gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.referrals.withdrawals*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <div class="flex items-center gap-2">
                                <x-lucide-wallet class="w-4 h-4" />
                                <span>Withdrawals</span>
                            </div>
                            @php
                                $pendingWithdrawals = \App\Models\ReferralCreditWithdrawal::where('status', 'pending')->count();
                            @endphp
                            @if($pendingWithdrawals > 0)
                                <span class="px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">{{ $pendingWithdrawals }}</span>
                            @endif
                        </a>
                    </div>
                </div>
                
                <!-- Section: Content -->
                <div class="mt-6 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">Content</span>
                </div>
                
                <!-- Content -->
                <div class="mb-0.5">
                    <button @click="toggle('content')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.documents*', 'admin.templates*', 'admin.deliverables*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-file-text class="w-5 h-5" />
                            <span>Content</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.content }" />
                    </button>
                    <div x-show="openSections.content" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.documents.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.documents.index', 'admin.documents.show', 'admin.documents.create', 'admin.documents.edit') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-file class="w-4 h-4" />
                            <span>Documents</span>
                        </a>

                        <!-- This will be enabled in future releases 
                        <a href="{{ route('admin.documents.bulk-create') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.documents.bulk-create') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-upload class="w-4 h-4" />
                            <span>Bulk Upload</span>
                        </a> -->

                        <a href="{{ route('admin.deliverables.pending') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.deliverables*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-check-circle class="w-4 h-4" />
                            <span>Pending Approvals</span>
                            @php
                                $pendingCount = \App\Models\Document::where('is_deliverable', true)->where('is_approved', false)->where('is_archived', false)->count();
                            @endphp
                            @if($pendingCount > 0)
                                <span class="ml-auto px-2 py-0.5 text-xs font-medium bg-amber-100 text-amber-700 rounded-full">{{ $pendingCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.documents.trash') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.documents.trash') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-trash-2 class="w-4 h-4" />
                            <span>Trash</span>
                        </a>
                        <a href="{{ route('admin.templates.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.templates*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-layers class="w-4 h-4" />
                            <span>Templates</span>
                        </a>
                        <a href="{{ route('admin.services.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.services*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-briefcase class="w-4 h-4" />
                            <span>Services</span>
                        </a>
                    </div>
                </div>
                
                <!-- Section: System -->
                <div class="mt-6 mb-2 px-3">
                    <span class="text-xs font-medium text-neutral-400 uppercase tracking-wider">System</span>
                </div>
                
                <!-- System -->
                <div class="mb-0.5">
                    <button @click="toggle('system')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.audit*', 'admin.notifications*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-settings class="w-5 h-5" />
                            <span>System</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.system }" />
                    </button>
                    <div x-show="openSections.system" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.audit.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.audit*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-history class="w-4 h-4" />
                            <span>Audit Logs</span>
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.notifications*') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-bell class="w-4 h-4" />
                            <span>Notifications</span>
                        </a>
                    </div>
                </div>
                
                <!-- Analytics -->
                <div class="mb-0.5">
                    <button @click="toggle('analytics')" class="w-full flex items-center justify-between px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reports*') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                        <div class="flex items-center gap-3">
                            <x-lucide-bar-chart-3 class="w-5 h-5" />
                            <span>Analytics</span>
                        </div>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform duration-200" x-bind:class="{ 'rotate-180': openSections.analytics }" />
                    </button>
                    <div x-show="openSections.analytics" x-collapse class="mt-1 ml-8 space-y-0.5">
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.index') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-activity class="w-4 h-4" />
                            <span>Overview</span>
                        </a>
                        <a href="{{ route('admin.reports.dashboard') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.dashboard') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-gauge class="w-4 h-4" />
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('admin.reports.users') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.users') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-users class="w-4 h-4" />
                            <span>Users</span>
                        </a>
                        <a href="{{ route('admin.reports.projects') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.projects') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-folder class="w-4 h-4" />
                            <span>Projects</span>
                        </a>
                        <a href="{{ route('admin.reports.tasks') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.tasks') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-list-todo class="w-4 h-4" />
                            <span>Tasks</span>
                        </a>
                        <a href="{{ route('admin.reports.requests') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.requests') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-clipboard class="w-4 h-4" />
                            <span>Requests</span>
                        </a>
                        <a href="{{ route('admin.reports.documents') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.documents') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-file-text class="w-4 h-4" />
                            <span>Documents</span>
                        </a>
                        <!-- This will be enabled in future releases
                        <a href="{{ route('admin.reports.custom') }}" class="flex items-center gap-2 px-3 py-2 text-sm rounded-lg {{ request()->routeIs('admin.reports.custom') ? 'text-primary-600 bg-primary-50/50' : 'text-neutral-500 hover:text-neutral-700 hover:bg-neutral-50' }}">
                            <x-lucide-sliders class="w-4 h-4" />
                            <span>Custom</span>
                        </a> -->

                    </div>
                </div>
                
                <!-- Divider -->
                <div class="my-4 border-t border-neutral-100"></div>
                
                <!-- Profile -->
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('admin.profile') ? 'bg-primary-50 text-primary-600' : 'text-neutral-600 hover:bg-neutral-50 hover:text-neutral-900' }}">
                    <x-lucide-user-circle class="w-5 h-5" />
                    <span>Profile</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main id="page-content-wrapper" class="flex-1 md:ml-64 min-h-screen transition-all duration-300">
            <!-- Top Navigation -->
            <header class="h-16 bg-white border-b border-neutral-100 px-6 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button class="md:hidden p-2 -ml-2 text-neutral-500 hover:text-neutral-700 hover:bg-neutral-100 rounded-lg transition-colors" id="menu-toggle">
                        <x-lucide-menu class="w-5 h-5" />
                    </button>
                    <h1 class="text-lg font-semibold text-neutral-900">
                        @yield('page-title', 'Admin Panel')
                    </h1>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Notification Bell -->
                    @include('components.notification-bell')
                    
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 p-1.5 rounded-lg hover:bg-neutral-50 transition-colors">
                            @if(Auth::user()->profilePic)
                                <img src="{{ Auth::user()->getProfilePictureUrl() }}" class="w-8 h-8 rounded-full object-cover ring-2 ring-neutral-100">
                            @else
                                <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center ring-2 ring-primary-100">
                                    <span class="text-white text-sm font-medium">{{ substr(Auth::user()->fullName, 0, 1) }}</span>
                                </div>
                            @endif
                            <span class="hidden md:block text-sm font-medium text-neutral-700">{{ Auth::user()->fullName }}</span>
                            <x-lucide-chevron-down class="w-4 h-4 text-neutral-400" />
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
                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                <x-lucide-user class="w-4 h-4 text-neutral-400" />
                                Profile
                            </a>
                            <a href="#" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                <x-lucide-settings class="w-4 h-4 text-neutral-400" />
                                Settings
                            </a>
                            <div class="border-t border-neutral-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-neutral-700 hover:bg-neutral-50 transition-colors">
                                    <x-lucide-log-out class="w-4 h-4 text-neutral-400" />
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        document.getElementById('menu-toggle')?.addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar-wrapper');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
        });

        // Alpine.js sidebar navigation component
        function sidebarNav() {
            return {
                openSections: {
                    users: {{ request()->routeIs('admin.users*', 'admin.clients*', 'admin.adiutors*') ? 'true' : 'false' }},
                    projects: {{ request()->routeIs('admin.requests*', 'admin.projects*', 'admin.tasks*', 'admin.revisions*') ? 'true' : 'false' }},
                    finance: {{ request()->routeIs('admin.payments*', 'admin.payouts*', 'admin.earnings-analytics*', 'admin.hour-requests*') ? 'true' : 'false' }},
                    communication: {{ request()->routeIs('admin.messages*', 'admin.feedback*', 'admin.announcements*') ? 'true' : 'false' }},
                    content: {{ request()->routeIs('admin.documents*', 'admin.templates*') ? 'true' : 'false' }},
                    rewards: {{ request()->routeIs('admin.coupons*', 'admin.loyalty*', 'admin.referrals*') ? 'true' : 'false' }},
                    system: {{ request()->routeIs('admin.audit*', 'admin.notifications*') ? 'true' : 'false' }},
                    analytics: {{ request()->routeIs('admin.reports*') ? 'true' : 'false' }}
                },
                toggle(section) {
                    if (!this.openSections[section]) {
                        Object.keys(this.openSections).forEach(key => {
                            this.openSections[key] = false;
                        });
                        this.openSections[section] = true;
                    } else {
                        this.openSections[section] = false;
                    }
                }
            }
        }
    </script>
    
    @stack('scripts')
    
    <!-- Global Alert System -->
    <x-ui.alert-manager />
</body>
</html>
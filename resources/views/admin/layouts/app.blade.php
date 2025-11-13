<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Treis Adiutor CMS</title>
    
    
    <!-- Favicon -->
    <link rel="icon" href="@yield('favicon', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor//favico.ico')" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    
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
    
    <!-- Alpine.js with Collapse plugin -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Admin Styles -->
    <style>
        /* All styling is now handled by Tailwind CSS */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        
        /* Alpine.js cloak */
        [x-cloak] {
            display: none !important;
        }

        /* Hide scrollbar while keeping scroll functionality */
        nav.overflow-y-auto {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;      /* Firefox */
        }

        nav.overflow-y-auto::-webkit-scrollbar {
            display: none;              /* Chrome, Safari and Opera */
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="flex">
        <!-- Sidebar -->
        <div id="sidebar-wrapper" class="w-64 h-screen fixed left-0 bg-gradient-to-b from-primary-600 to-primary-800 text-white transition-transform duration-300 ease-in-out z-30 -translate-x-full md:translate-x-0">
            <div class="py-4 px-5 border-b border-white/10 text-center">
                <a href="/" class="text-xl font-branding text-white tracking-wider">
                    TREIS ADIUTOR
                </a>
            </div>
            <nav class="mt-6 px-4 overflow-y-auto" style="max-height: calc(100vh - 100px);" x-data="sidebarNav()">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-2 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="border-t border-white/20 my-4"></div>
                
                <!-- User Management Section -->
                <div class="mb-2">
                    <button @click="toggle('users')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-users w-5 mr-3"></i>
                            <span class="text-sm font-medium">User Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.users }"></i>
                    </button>
                    <div x-show="openSections.users" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.users*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-user w-4 mr-2"></i>
                            <span>All Users</span>
                        </a>
                        <a href="{{ route('admin.clients.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.clients*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-user-tie w-4 mr-2"></i>
                            <span>Clients</span>
                        </a>
                    </div>
                </div>
                
                <!-- Project Management Section -->
                <div class="mb-2">
                    <button @click="toggle('projects')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-project-diagram w-5 mr-3"></i>
                            <span class="text-sm font-medium">Project Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.projects }"></i>
                    </button>
                    <div x-show="openSections.projects" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.requests.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.requests*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-clipboard-list w-4 mr-2"></i>
                            <span>Service Requests</span>
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.projects*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-project-diagram w-4 mr-2"></i>
                            <span>Projects</span>
                        </a>
                        <a href="{{ route('admin.tasks.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.tasks*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-tasks w-4 mr-2"></i>
                            <span>Tasks</span>
                        </a>
                        <a href="{{ route('admin.budget-requests.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.budget-requests*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-money-bill-wave w-4 mr-2"></i>
                            <span>Budget Requests</span>
                        </a>
                        <a href="{{ route('admin.revisions.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.revisions*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-redo w-4 mr-2"></i>
                            <span>Revision Requests</span>
                        </a>
                    </div>
                </div>
                
                <!-- Financial Management Section -->
                <div class="mb-2">
                    <button @click="toggle('finance')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-dollar-sign w-5 mr-3"></i>
                            <span class="text-sm font-medium">Financial Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.finance }"></i>
                    </button>
                    <div x-show="openSections.finance" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.payments.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.payments*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-credit-card w-4 mr-2"></i>
                            <span>Payments</span>
                        </a>
                        <a href="{{ route('admin.budget-requests.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.budget-requests*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-chart-line w-4 mr-2"></i>
                            <span>Budget Changes</span>
                        </a>
                    </div>
                </div>
                
                <!-- Communication Section -->
                <div class="mb-2">
                    <button @click="toggle('communication')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-comments w-5 mr-3"></i>
                            <span class="text-sm font-medium">Communication</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.communication }"></i>
                    </button>
                    <div x-show="openSections.communication" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.messages.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.messages*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-envelope w-4 mr-2"></i>
                            <span class="flex-1">Messages</span>
                            @if(auth()->user()->unreadMessagesCount() > 0)
                                <span class="bg-accent-500 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">
                                    {{ auth()->user()->unreadMessagesCount() }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('admin.feedback.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.feedback*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-star w-4 mr-2"></i>
                            <span>Feedback</span>
                        </a>
                        <a href="{{ route('admin.announcements.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.announcements*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-bullhorn w-4 mr-2"></i>
                            <span>Announcements</span>
                        </a>
                    </div>
                </div>
                
                <!-- Content Management Section -->
                <div class="mb-2">
                    <button @click="toggle('content')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-folder-open w-5 mr-3"></i>
                            <span class="text-sm font-medium">Content Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.content }"></i>
                    </button>
                    <div x-show="openSections.content" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.documents.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.documents*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-file-alt w-4 mr-2"></i>
                            <span>Documents</span>
                        </a>
                        <a href="{{ route('admin.templates.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.templates*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-layer-group w-4 mr-2"></i>
                            <span>Project Templates</span>
                        </a>
                    </div>
                </div>
                
                <!-- Payouts Section -->
                <div class="mb-2">
                    <button @click="toggle('payouts')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-money-check-alt w-5 mr-3"></i>
                            <span class="text-sm font-medium">Payouts</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.payouts }"></i>
                    </button>
                    <div x-show="openSections.payouts" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.payouts.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.payouts.index') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-list w-4 mr-2"></i>
                            <span>All Payouts</span>
                        </a>
                        <a href="{{ route('admin.payouts.index', ['status' => 'pending']) }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.payouts.index') && request('status') === 'pending' ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-clock w-4 mr-2"></i>
                            <span>Pending Review</span>
                        </a>
                        <a href="{{ route('admin.payouts.index', ['status' => 'processing']) }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.payouts.index') && request('status') === 'processing' ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-spinner w-4 mr-2"></i>
                            <span>Processing</span>
                        </a>
                        <a href="{{ route('admin.payouts.index', ['status' => 'completed']) }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.payouts.index') && request('status') === 'completed' ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-check-circle w-4 mr-2"></i>
                            <span>Completed</span>
                        </a>
                        <a href="{{ route('admin.payouts.export') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm">
                            <i class="fas fa-file-export w-4 mr-2"></i>
                            <span>Export Report</span>
                        </a>
                    </div>
                </div>
                
                <!-- System Management Section -->
                <div class="mb-2">
                    <button @click="toggle('system')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-cogs w-5 mr-3"></i>
                            <span class="text-sm font-medium">System Management</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.system }"></i>
                    </button>
                    <div x-show="openSections.system" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.audit.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.audit*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-history w-4 mr-2"></i>
                            <span>Audit Logs</span>
                        </a>
                        <a href="{{ route('admin.notifications.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.notifications*') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-bell w-4 mr-2"></i>
                            <span>Notifications</span>
                        </a>
                    </div>
                </div>
                
                <!-- Analytics & Reports Section -->
                <div class="mb-2">
                    <button @click="toggle('analytics')" class="w-full flex items-center justify-between py-2 px-3 text-white/80 hover:text-white hover:bg-white/10 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-chart-bar w-5 mr-3"></i>
                            <span class="text-sm font-medium">Analytics & Reports</span>
                        </div>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': openSections.analytics }"></i>
                    </button>
                    <div x-show="openSections.analytics" x-collapse class="ml-6 mt-1 space-y-1">
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.index') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-chart-line w-4 mr-2"></i>
                            <span>Overview</span>
                        </a>
                        <a href="{{ route('admin.reports.dashboard') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.dashboard') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-tachometer-alt w-4 mr-2"></i>
                            <span>Dashboard Reports</span>
                        </a>
                        <a href="{{ route('admin.reports.users') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.users') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-users w-4 mr-2"></i>
                            <span>User Analytics</span>
                        </a>
                        <a href="{{ route('admin.reports.projects') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.projects') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-project-diagram w-4 mr-2"></i>
                            <span>Project Reports</span>
                        </a>
                        <a href="{{ route('admin.reports.tasks') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.tasks') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-tasks w-4 mr-2"></i>
                            <span>Task Reports</span>
                        </a>
                        <a href="{{ route('admin.reports.requests') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.requests') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-clipboard-list w-4 mr-2"></i>
                            <span>Request Reports</span>
                        </a>
                        <a href="{{ route('admin.reports.documents') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.documents') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-file-alt w-4 mr-2"></i>
                            <span>Document Reports</span>
                        </a>
                        <a href="{{ route('admin.reports.custom') }}" class="flex items-center py-2 px-3 text-white/70 hover:text-white hover:bg-white/10 rounded-lg text-sm {{ request()->routeIs('admin.reports.custom') ? 'bg-white/10 text-white' : '' }}">
                            <i class="fas fa-filter w-4 mr-2"></i>
                            <span>Custom Reports</span>
                        </a>
                    </div>
                </div>
                
                <div class="border-t border-white/20 my-4"></div>
                
                <!-- Profile -->
                <a href="{{ route('admin.profile') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.profile') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-user w-5 mr-3"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </div>

        <!-- Page Content -->
        <div id="page-content-wrapper" class="flex-1 md:ml-64 min-h-screen transition-all duration-300 ease-in-out">
            <!-- Top Navigation -->
            <nav class="bg-white shadow-sm px-6 py-4 flex justify-between items-center">
                <div>
                    <button class="md:hidden text-gray-600 hover:text-gray-900" id="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h5 class="hidden md:block text-lg font-medium text-gray-800">
                        @yield('page-title', 'Admin Panel')
                    </h5>
                </div>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                        @if(Auth::user()->profilePic)
                            <img src="{{ Auth::user()->getProfilePictureUrl() }}" class="rounded-full w-8 h-8 object-cover">
                        @else
                            <div class="bg-primary-500 rounded-full w-8 h-8 flex items-center justify-center">
                                <span class="text-white font-medium">{{ substr(Auth::user()->fullName, 0, 1) }}</span>
                            </div>
                        @endif
                        <span class="hidden md:block text-gray-800">{{ Auth::user()->fullName }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>
                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                        <a href="{{ route('admin.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Settings
                        </a>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('admin.logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="p-6">
                @if(session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 alert" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <p>{{ session('success') }}</p>
                            <button type="button" class="ml-auto" onclick="this.parentElement.parentElement.style.display='none'">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 alert" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <p>{{ session('error') }}</p>
                            <button type="button" class="ml-auto" onclick="this.parentElement.parentElement.style.display='none'">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @if(isset($errors) && is_object($errors) && $errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 alert" role="alert">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                            <div>
                                <ul class="list-disc ml-5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="ml-auto" onclick="this.parentElement.parentElement.style.display='none'">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Toggle sidebar on mobile
        document.getElementById('menu-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar-wrapper');
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
            
            // Toggle content margin to adjust for sidebar
            const content = document.getElementById('page-content-wrapper');
            if (window.innerWidth >= 768) { // md breakpoint
                content.classList.toggle('md:ml-0');
                content.classList.toggle('md:ml-64');
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                alert.style.display = 'none';
            });
        }, 5000);

        // Alpine.js sidebar navigation component
        function sidebarNav() {
            return {
                openSections: {
                    users: {{ request()->routeIs('admin.users*', 'admin.clients*') ? 'true' : 'false' }},
                    projects: {{ request()->routeIs('admin.requests*', 'admin.projects*', 'admin.tasks*', 'admin.revisions*') ? 'true' : 'false' }},
                    finance: {{ request()->routeIs('admin.payments*', 'admin.budget-requests*') ? 'true' : 'false' }},
                    communication: {{ request()->routeIs('admin.messages*', 'admin.feedback*', 'admin.announcements*') ? 'true' : 'false' }},
                    content: {{ request()->routeIs('admin.documents*', 'admin.templates*') ? 'true' : 'false' }},
                    payouts: {{ request()->routeIs('admin.payouts*') ? 'true' : 'false' }},
                    system: {{ request()->routeIs('admin.audit*', 'admin.notifications*') ? 'true' : 'false' }},
                    analytics: {{ request()->routeIs('admin.reports*') ? 'true' : 'false' }}
                },
                toggle(section) {
                    // If the section is currently closed, close all other sections first
                    if (!this.openSections[section]) {
                        // Close all sections
                        Object.keys(this.openSections).forEach(key => {
                            this.openSections[key] = false;
                            localStorage.setItem('admin_sidebar_' + key, false);
                        });
                        // Then open the clicked section
                        this.openSections[section] = true;
                        localStorage.setItem('admin_sidebar_' + section, true);
                    } else {
                        // If clicking on an already open section, just close it
                        this.openSections[section] = false;
                        localStorage.setItem('admin_sidebar_' + section, false);
                    }
                },
                init() {
                    // First check if any section should be open based on current route
                    const routeBasedOpenSections = Object.keys(this.openSections).filter(section => this.openSections[section]);
                    
                    if (routeBasedOpenSections.length > 0) {
                        // If there are route-based open sections, keep only the first one and close others
                        const primarySection = routeBasedOpenSections[0];
                        Object.keys(this.openSections).forEach(section => {
                            this.openSections[section] = section === primarySection;
                        });
                    } else {
                        // Restore state from localStorage, but ensure only one section is open
                        let hasOpenSection = false;
                        Object.keys(this.openSections).forEach(section => {
                            const stored = localStorage.getItem('admin_sidebar_' + section);
                            if (stored === 'true' && !hasOpenSection) {
                                this.openSections[section] = true;
                                hasOpenSection = true;
                            } else {
                                this.openSections[section] = false;
                            }
                        });
                    }
                }
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>
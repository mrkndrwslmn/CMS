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
    
    <!-- Alpine.js -->
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
        <div id="sidebar-wrapper" class="w-64 h-screen fixed left-0 bg-gradient-to-b from-primary-500 to-primary-700 text-white transition-transform duration-300 ease-in-out z-30 -translate-x-full md:translate-x-0">
            <div class="py-4 px-5 border-b border-white/10 text-center">
                <a href="/" class="text-xl font-branding text-white tracking-wider">
                    TREIS ADIUTOR
                </a>
            </div>
            <nav class="mt-6 px-4 overflow-y-auto" style="max-height: calc(100vh - 100px);">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 mr-3"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="border-t border-white/20 my-4"></div>
                
                <!-- User Management Section -->
                <div class="px-3 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">User Management</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.users*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-users w-5 mr-3"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.clients.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.clients*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-user-tie w-5 mr-3"></i>
                    <span>Clients</span>
                </a>
                
                <div class="border-t border-white/20 my-4"></div>
                
                <!-- Project Management Section -->
                <div class="px-3 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Project Management</p>
                </div>
                <a href="{{ route('admin.requests.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.requests*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-clipboard-list w-5 mr-3"></i>
                    <span>Service Requests</span>
                </a>
                <a href="{{ route('admin.projects.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.projects*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-project-diagram w-5 mr-3"></i>
                    <span>Projects</span>
                </a>
                <a href="{{ route('admin.tasks.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.tasks*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-tasks w-5 mr-3"></i>
                    <span>Tasks</span>
                </a>
                <a href="{{ route('admin.budget-requests.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.budget-requests*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-money-bill-wave w-5 mr-3"></i>
                    <span>Budget Requests</span>
                </a>
                <a href="{{ route('admin.revisions.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.revisions*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-redo w-5 mr-3"></i>
                    <span>Revision Requests</span>
                </a>
                
                <div class="border-t border-white/20 my-4"></div>
                
                <!-- Communication Section -->
                <div class="px-3 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Communication</p>
                </div>
                <a href="{{ route('admin.messages.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.messages*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-comments w-5 mr-3"></i>
                    <span class="flex-1">Messages</span>
                    @if(auth()->user()->unreadMessagesCount() > 0)
                        <span class="bg-accent-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                            {{ auth()->user()->unreadMessagesCount() }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.feedback.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.feedback*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-star w-5 mr-3"></i>
                    <span>Feedback</span>
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.announcements*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-bullhorn w-5 mr-3"></i>
                    <span>Announcements</span>
                </a>
                
                <div class="border-t border-white/20 my-4"></div>
                
                <!-- Content Management Section -->
                <div class="px-3 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Content</p>
                </div>
                <a href="{{ route('admin.documents.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.documents*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-folder-open w-5 mr-3"></i>
                    <span>Documents</span>
                </a>
                
                <div class="border-t border-white/20 my-4"></div>
                
                <!-- Analytics Section -->
                <div class="px-3 mb-2">
                    <p class="text-xs font-semibold text-white/50 uppercase tracking-wider">Analytics</p>
                </div>
                <a href="{{ route('admin.reports.index') }}" class="flex items-center py-3 px-4 text-white/80 hover:text-white hover:bg-white/10 rounded-lg mb-1 {{ request()->routeIs('admin.reports*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-chart-bar w-5 mr-3"></i>
                    <span>Reports & Analytics</span>
                </a>
                
                <div class="border-t border-white/20 my-4"></div>
                
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
                            <img src="{{ asset('storage/' . Auth::user()->profilePic) }}" class="rounded-full w-8 h-8 object-cover">
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
    </script>
    
    @stack('scripts')
</body>
</html>
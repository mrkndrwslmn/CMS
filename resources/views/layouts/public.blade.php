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
    <link rel="icon" href="@yield('favicon', 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor//favico.ico')" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS (Local Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- External Libraries -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <script src="https://unpkg.com/gsap@3.12.0/dist/gsap.min.js"></script>
    
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
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
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
                        <li><a href="{{ route('client.dashboard') }}" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Dashboard</a></li>
                        <li><a href="{{ route('client.profile') }}" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Profile</a></li>
                        <li><a href="{{ route('logout') }}" class="text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Logout</a></li>
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
                    <li><a href="{{ route('logout') }}" class="block text-gray-700 hover:text-primary transition-colors duration-300 font-medium">Logout</a></li>
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
    <div id="announcements-banner" class="bg-gradient-to-r from-primary-600 to-accent-600 border-b border-primary-700 fixed top-0 left-0 right-0 z-50">
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
            <li><a href="/services" class="text-neutral-600 hover:text-primary-600 transition-colors">Services</a></li>
            <li><a href="/about-us" class="text-neutral-600 hover:text-primary-600 transition-colors">About Us</a></li>
            <li><a href="/contact" class="text-neutral-600 hover:text-primary-600 transition-colors">Contact Us</a></li>
          </ul>
        </div>
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-2/12 mb-10 lg:mb-0">
          <h4 class="text-neutral-800 font-semibold heading-serif mb-6 text-lg">Resources</h4>
          <ul class="space-y-3 text-sm">
            <li><a href="/featured-projects" class="text-neutral-600 hover:text-primary-600 transition-colors">Projects</a></li>
            <li><a href="/client-testimonials" class="text-neutral-600 hover:text-primary-600 transition-colors">Testimonials</a></li>
            <li><a href="/referral-program" class="text-neutral-600 hover:text-primary-600 transition-colors flex items-center">
              Referral Program
              <span class="ml-2 px-2 py-0.5 bg-accent text-white text-xs rounded-full font-medium">Earn Rewards</span>
            </a></li>
            <li><a href="/faq" class="text-neutral-600 hover:text-primary-600 transition-colors">FAQs</a></li>
          </ul>
        </div>
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-2/12 mb-10 lg:mb-0">
          <h4 class="text-neutral-800 font-semibold heading-serif mb-6 text-lg">Legal</h4>
          <ul class="space-y-3 text-sm">
            <li><a href="/privacy-policy" class="text-neutral-600 hover:text-primary-600 transition-colors">Privacy Policy</a></li>
            <li><a href="/terms-and-conditions" class="text-neutral-600 hover:text-primary-600 transition-colors">Terms & Conditions</a></li>
          </ul>
        </div>
        <div class="w-full px-4 sm:w-1/2 md:w-1/2 lg:w-4/12 xl:w-3/12">
          <h4 class="text-neutral-800 font-semibold heading-serif mb-6 text-lg">Get in touch</h4>
          <p class="text-neutral-600 mb-6">Need help with your project? Just drop us a message!</p>
          <a href="/contact" class="px-5 py-2.5 rounded-full glass-button border border-primary-200 hover:border-primary-300 text-primary-600 hover:bg-primary-50 transition-all duration-300 inline-flex items-center text-sm shadow-sm">
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
    <script src="{{ asset('js/chatbot.js') }}"></script>
    
    @stack('scripts')
</body>
</html>

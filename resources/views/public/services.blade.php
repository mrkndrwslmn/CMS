@extends('layouts.public')

@section('title', 'Our Services - Academic and Programming Solutions')
@section('site_name', 'Treis Adiutor')

@section('content')

  <!-- Hero Section -->
  <section class="min-h-[60vh] relative flex items-center pt-32 pb-16 section-padding overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-grid-pattern opacity-50"></div>
    
    <!-- Background Shapes -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary-500/10 rounded-full blur-3xl animate-float-slow"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-info-500/10 rounded-full blur-3xl animate-float-delay"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-amber-500/5 rounded-full blur-3xl animate-float"></div>
    
    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center" data-aos="fade-up">
      <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-4 inline-block py-1.5 px-4 rounded-full bg-primary-100/80 backdrop-blur-sm">Explore our Services</span>
      <h1 class="text-4xl md:text-5xl lg:text-6xl heading-serif mb-6 leading-tight">
        Our <span class="gradient-text">Services & Pricing</span>
      </h1>
      <p class="text-lg text-neutral-600 mb-10 max-w-3xl mx-auto leading-relaxed">
        Explore our full range of academic and technical services — with clear starting prices so you know exactly what to expect. No hidden fees. No surprises. Just premium work that fits your goals and your budget.
      </p>
      <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-info-500 mx-auto rounded-full"></div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-6">
      
      <!-- Controls: Search Bar & Filters -->
      <div class="mb-12 sticky top-24 z-30" data-aos="fade-up" data-aos-delay="100">
        <div class="bg-white/75 rounded-2xl shadow-lg p-6 border border-neutral-200">
          <div class="flex flex-col gap-6">
            <!-- Search Bar with Button -->
            <div class="relative w-full flex">
              <div class="relative flex-1">
                <input type="text" id="serviceSearch" placeholder="Search for a service... (e.g., 'React App')" 
                  class="w-full pl-12 pr-4 py-3 rounded-l-xl bg-white border border-neutral-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all duration-300 text-neutral-700 placeholder-neutral-400 shadow-sm border-r-0">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-400">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
              <button id="searchButton" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-r-xl transition-all duration-300 border border-primary-600 shadow-sm flex items-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <span class="ml-2 hidden sm:inline">Search</span>
              </button>
            </div>  
            
            <!-- Filters Row -->
            <div class="flex flex-col md:flex-row gap-4 items-center">
              <!-- Category Dropdown -->
              <div class="relative w-full md:w-auto">
                <select id="categoryFilter" class="w-full md:w-48 px-4 py-3 rounded-xl bg-white border border-neutral-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all duration-300 text-neutral-700 appearance-none cursor-pointer">
                  <option value="All Services">All Categories</option>
                  <!-- Categories will be populated dynamically -->
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
              </div>

              <!-- Price Range Filter -->
              <div class="relative w-full md:w-auto">
                <select id="priceFilter" class="w-full md:w-48 px-4 py-3 rounded-xl bg-white border border-neutral-200 focus:ring-2 focus:ring-amber-500 focus:border-transparent outline-none transition-all duration-300 text-neutral-700 appearance-none cursor-pointer">
                  <option value="all">All Prices</option>
                  <option value="0-2000">₱0 - ₱2,000</option>
                  <option value="2000-5000">₱2,000 - ₱5,000</option>
                  <option value="5000-10000">₱5,000 - ₱10,000</option>
                  <option value="10000+">₱10,000+</option>
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
              </div>

              <!-- Sort By -->
              <div class="relative w-full md:w-auto">
                <select id="sortFilter" class="w-full md:w-48 px-4 py-3 rounded-xl bg-white border border-neutral-200 focus:ring-2 focus:ring-info-500 focus:border-transparent outline-none transition-all duration-300 text-neutral-700 appearance-none cursor-pointer">
                  <option value="name">Sort by Name</option>
                  <option value="price-low">Price: Low to High</option>
                  <option value="price-high">Price: High to Low</option>
                  <option value="category">Sort by Category</option>
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-neutral-400 pointer-events-none">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                  </svg>
                </div>
              </div>

              <!-- Clear Filters Button -->
              <button id="clearFilters" class="w-full md:w-auto px-6 py-3 bg-neutral-100 hover:bg-neutral-200 text-neutral-700 font-medium rounded-xl transition-all duration-300 border border-neutral-200">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Clear Filters
              </button>
            </div>

            <!-- Active Filters Display -->
            <div id="activeFilters" class="hidden">
              <div class="flex flex-wrap gap-2">
                <span class="text-sm text-neutral-600 font-medium">Active filters:</span>
                <!-- Active filter tags will appear here -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Loading Indicator -->
      <div id="loading-indicator" class="text-center text-neutral-500 py-16 hidden">
        <div class="inline-block">
          <svg class="animate-spin h-12 w-12 text-primary-500 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="text-lg font-medium text-neutral-700">Searching for services...</p>
          <p class="text-sm text-neutral-500 mt-2">Finding the best match</p>
        </div>
      </div>

      <!-- Services Grid -->
      <div id="services-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Services will be populated here by JavaScript -->
      </div>
    </div>
  </section>

  <!-- Call to Action Section -->
  <section class="py-20 md:py-28 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary-50/30 to-white -z-10"></div>

    <div class="max-w-7xl mx-auto px-6 relative">
      <div class="max-w-5xl mx-auto">
        <div class="text-center mb-12" data-aos="fade-up">
          <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-neutral-200 mb-8">
            <div class="flex -space-x-2">
              <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 border-2 border-white"></div>
              <div class="w-7 h-7 rounded-full bg-gradient-to-br from-info-400 to-info-600 border-2 border-white"></div>
              <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 border-2 border-white"></div>
            </div>
            <span class="text-sm font-medium text-neutral-700">Trusted by 500+ companies</span>
          </div>
          <h2 class="heading-serif text-4xl md:text-5xl lg:text-6xl mb-6 text-neutral-900">
            Ready to <br class="hidden md:block"/>
            <span class="gradient-text">get started?</span>
          </h2>
          <p class="text-lg md:text-xl text-neutral-600 mb-12 max-w-3xl mx-auto leading-relaxed">
            Didn't see exactly what you're looking for? No worries — we handle custom projects all the time. Tell us what you need, and we'll craft a solution (and quote) just for you.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
            <a href="/get-started" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-xl font-semibold shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/30 transition-all duration-300 hover:-translate-y-0.5">
              <span class="relative">Get Started</span>
              <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
              </svg>
            </a>
            <a href="/" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-neutral-700 rounded-xl font-semibold border-2 border-neutral-200 hover:border-neutral-300 shadow-sm hover:shadow-md transition-all duration-300">
              <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
              </svg>
              <span>Back to Home</span>
            </a>
          </div>

          <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-neutral-500">
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">Fast turnaround</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">Quality guaranteed</span>
            </div>
            <div class="flex items-center gap-2">
              <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span class="font-medium">100% secure</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
          <div class="bg-white rounded-2xl p-6 border border-neutral-200 hover:border-neutral-300 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">Quick Response</h3>
            <p class="text-sm text-neutral-600">Get a personalized quote within 2 hours of your inquiry</p>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-neutral-200 hover:border-neutral-300 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-info-100 flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">Expert Team</h3>
            <p class="text-sm text-neutral-600">Dedicated professionals committed to your success</p>
          </div>

          <div class="bg-white rounded-2xl p-6 border border-neutral-200 hover:border-neutral-300 hover:shadow-lg transition-all duration-300">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">
              <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
              </svg>
            </div>
            <h3 class="text-lg font-semibold text-neutral-900 mb-2">Full Confidentiality</h3>
            <p class="text-sm text-neutral-600">Your data and projects are always protected</p>
          </div>
        </div>
      </div>
    </div>
  </section>  
  
  @endsection

  @push('scripts')
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      once: true
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      const mobileMenuButton = document.getElementById('mobile-menu-button');
      const mobileMenu = document.getElementById('mobile-menu');
    
        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                // Toggle Tailwind's 'hidden' class for display:none
                mobileMenu.classList.toggle('hidden');
                // Toggle 'active' class for max-height transition animation
                mobileMenu.classList.toggle('active');
    
                // Optional: Change the SVG icon (hamburger <-> X)
                const iconPath = mobileMenuButton.querySelector('svg path');
                if (mobileMenu.classList.contains('active')) {
                    // Change to 'X' icon
                    iconPath.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                } else {
                    // Change back to hamburger icon
                    iconPath.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                }
            });
        }  
      let allServices = [];
      let aiSearchTimer;

      const loadingIndicator = document.getElementById('loading-indicator');
      const servicesGrid = document.getElementById('services-grid');
      const categoryFilter = document.getElementById('categoryFilter');
      const priceFilter = document.getElementById('priceFilter');
      const sortFilter = document.getElementById('sortFilter');
      const searchInput = document.getElementById('serviceSearch');
      const searchButton = document.getElementById('searchButton');
      const clearFiltersBtn = document.getElementById('clearFilters');
      const activeFiltersDiv = document.getElementById('activeFilters');

      async function fetchServices() {
          try {
              const response = await fetch('/api/services');
              if (!response.ok) {
                  throw new Error(`HTTP error! status: ${response.status}`);
              }
              const data = await response.json();
              allServices = data;
              populateCategories();
              renderServices(allServices);
          } catch (error) {
              console.error('Error fetching services:', error);
              servicesGrid.innerHTML = `<div class="col-span-full text-center text-neutral-500">Failed to load services. Please try again later.</div>`;
          }
      }

      function populateCategories() {
          const categories = [...new Set(allServices.map(service => service.service_type))];
          
          // Clear existing options except the first one
          categoryFilter.innerHTML = '<option value="All Services">All Categories</option>';
          
          // Add category options
          categories.forEach(category => {
              const option = document.createElement('option');
              option.value = category;
              option.textContent = category;
              categoryFilter.appendChild(option);
          });
      }
      
      function renderServices(services) {
        servicesGrid.innerHTML = '';

        if (services.length === 0) {
            const noServicesMessage = document.createElement('div');
            noServicesMessage.className = 'col-span-full text-center py-16';
            noServicesMessage.innerHTML = `
              <div class="glass-dark rounded-3xl p-12 border border-neutral-200 max-w-md mx-auto">
                <div class="mb-6">
                  <svg class="w-20 h-20 mx-auto text-neutral-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 10.5h.01"></path>
                  </svg>
                </div>
                <h3 class="text-2xl font-semibold heading-serif text-neutral-800 mb-3">No Services Found</h3>
                <p class="text-neutral-600 leading-relaxed">We couldn't find any services matching your search. Try a different term or contact us for a custom request.</p>
                <a href="{{ url('/contact') }}" class="inline-flex items-center mt-6 px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors shadow-md">
                  <span>Request Custom Service</span>
                  <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                  </svg>
                </a>
              </div>
            `;
            servicesGrid.appendChild(noServicesMessage);
            return;
        }
      
        services.forEach((service, index) => {
            const serviceCard = createServiceCard(service);
            serviceCard.setAttribute('data-aos', 'fade-up');
            serviceCard.setAttribute('data-aos-delay', (index % 3) * 100);
            servicesGrid.appendChild(serviceCard);
        });
      }
      
      function createServiceCard(service) {
        const card = document.createElement('div');
        card.className = 'glass-dark rounded-3xl p-8 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-2xl hover:-translate-y-3 transition-all duration-500 group';
        
        const colors = getServiceColor(service.service_type);
        
        const cardContent = `
          <div class="flex-grow">
            <div class="flex justify-between items-start mb-6">
              <div class="w-16 h-16 rounded-2xl flex items-center justify-center ${colors.iconBg} shadow-md group-hover:scale-110 transition-transform duration-300">
                ${getServiceIcon(service.service_type)}
              </div>
              <div class="text-right">
                <p class="text-xs uppercase tracking-wider text-neutral-500 font-semibold mb-1">STARTS AT</p>
                <p class="text-3xl font-bold gradient-text">₱${service.price}</p>
              </div>
            </div>
            <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-3 group-hover:text-primary-600 transition-colors">${service.service_name}</h3>
            <p class="text-neutral-600 leading-relaxed mb-6">${service.description}</p>
          </div>
          <div class="mt-auto pt-6 border-t border-neutral-200 flex items-center justify-between">
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold ${colors.badge} shadow-xs">
              <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              ${service.service_type}
            </span>
            <a href="${buildInquireUrl(service)}" class="inline-flex items-center text-xs font-bold px-5 py-2.5 rounded-xl transition-all duration-300 ${colors.buttonBg} ${colors.buttonText} ${colors.buttonHover} shadow-md hover:shadow-lg group/btn">
              <span>Inquire Now</span>
              <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        `;
        
        card.innerHTML = cardContent;
        return card;
      }
      
      function getServiceColor(serviceType) {
        const colorMap = {
            'Programming': {
                badge: 'bg-primary-100 text-primary-700', 
                iconBg: 'bg-primary-100',
                buttonBg: 'bg-primary-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-primary-700'
            },
            'Mobile Development': {
                badge: 'bg-amber-100 text-amber-700', 
                iconBg: 'bg-amber-100',
                buttonBg: 'bg-amber-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-amber-700'
            },
            'Design': {
                badge: 'bg-info-100 text-info-700', 
                iconBg: 'bg-info-100',
                buttonBg: 'bg-info-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-info-700'
            },
            'Support': {
                badge: 'bg-success-100 text-success-700', 
                iconBg: 'bg-success-100',
                buttonBg: 'bg-success-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-success-700'
            },
            'Integration': {
                badge: 'bg-purple-100 text-purple-700', 
                iconBg: 'bg-purple-100',
                buttonBg: 'bg-purple-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-purple-700'
            },
            'Consulting': {
                badge: 'bg-indigo-100 text-indigo-700', 
                iconBg: 'bg-indigo-100',
                buttonBg: 'bg-indigo-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-indigo-700'
            },
            'Maintenance': {
                badge: 'bg-orange-100 text-orange-700', 
                iconBg: 'bg-orange-100',
                buttonBg: 'bg-orange-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-orange-700'
            },
            'Marketing': {
                badge: 'bg-pink-100 text-pink-700', 
                iconBg: 'bg-pink-100',
                buttonBg: 'bg-pink-600',
                buttonText: 'text-white',
                buttonHover: 'hover:bg-pink-700'
            }
        };

        return colorMap[serviceType] || {
            badge: 'bg-neutral-100 text-neutral-700', 
            iconBg: 'bg-neutral-100',
            buttonBg: 'bg-neutral-600',
            buttonText: 'text-white',
            buttonHover: 'hover:bg-neutral-700'
        };
      }

      function getServiceIcon(serviceType) {
          const iconClasses = "w-8 h-8";
          const colorClass = getServiceColor(serviceType);
          const iconColor = colorClass.buttonBg.replace('bg-', '').replace('-600', '-600');
          
          const iconMap = {
              'Web Development': `<svg class="${iconClasses} text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>`,
              'Mobile Development': `<svg class="${iconClasses} text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16a1 1 0 001 1z"></path></svg>`,
              'Design': `<svg class="${iconClasses} text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>`,
              'Backend Development': `<svg class="${iconClasses} text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>`,
              'Integration': `<svg class="${iconClasses} text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>`,
              'Consulting': `<svg class="${iconClasses} text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>`,
              'Maintenance': `<svg class="${iconClasses} text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>`,
              'Marketing': `<svg class="${iconClasses} text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>`
          };

          return iconMap[serviceType] || `<svg class="${iconClasses} text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>`;
      }

      // Build the Inquire Now URL with all service details
      function buildInquireUrl(service) {
        const params = new URLSearchParams({
          id: service.id, // Template service ID
          service: service.service_name,
          type: service.service_type,
          description: service.description,
          price: service.price,
          duration: service.estimated_duration_days || '',
          requirements: service.requirements || '',
          features: JSON.stringify(service.features || []),
          skills: JSON.stringify(service.required_skills || [])
        });
        return `/get-started?${params.toString()}`;
      }

      function setupFilterEventListeners() {
        // Category filter
        categoryFilter.addEventListener('change', applyFilters);
        
        // Price filter
        priceFilter.addEventListener('change', applyFilters);
        
        // Sort filter
        sortFilter.addEventListener('change', applyFilters);
        
        // Search input - debounced search as you type
        searchInput.addEventListener('input', () => {
          clearTimeout(aiSearchTimer);
          aiSearchTimer = setTimeout(applyFilters, 300); // Debounce search
        });
        
        // Search button - immediate search
        searchButton.addEventListener('click', (e) => {
          e.preventDefault();
          
          // Add visual feedback
          searchButton.classList.add('bg-primary-700');
          const originalContent = searchButton.innerHTML;
          searchButton.innerHTML = `
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-2 hidden sm:inline">Searching...</span>
          `;
          
          clearTimeout(aiSearchTimer); // Clear any pending debounced search
          applyFilters(); // Apply filters immediately
          
          // Reset button after a short delay
          setTimeout(() => {
            searchButton.classList.remove('bg-primary-700');
            searchButton.innerHTML = originalContent;
          }, 500);
        });
        
        // Enter key in search input - immediate search
        searchInput.addEventListener('keypress', (e) => {
          if (e.key === 'Enter') {
            e.preventDefault();
            
            // Add visual feedback to search button
            searchButton.classList.add('bg-primary-700');
            const originalContent = searchButton.innerHTML;
            searchButton.innerHTML = `
              <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <span class="ml-2 hidden sm:inline">Searching...</span>
            `;
            
            clearTimeout(aiSearchTimer); // Clear any pending debounced search
            applyFilters(); // Apply filters immediately
            
            // Reset button after a short delay
            setTimeout(() => {
              searchButton.classList.remove('bg-primary-700');
              searchButton.innerHTML = originalContent;
            }, 500);
          }
        });
        
        // Clear filters button
        clearFiltersBtn.addEventListener('click', clearAllFilters);
      }

      function clearAllFilters() {
        categoryFilter.value = 'All Services';
        priceFilter.value = 'all';
        sortFilter.value = 'name';
        searchInput.value = '';
        applyFilters();
      }

      function applyFilters() {
        const categoryValue = categoryFilter.value;
        const priceValue = priceFilter.value;
        const sortValue = sortFilter.value;
        const searchQuery = searchInput.value.toLowerCase();
        
        let filteredServices = [...allServices];
        
        // Apply category filter
        if (categoryValue !== 'All Services') {
          filteredServices = filteredServices.filter(service => service.service_type === categoryValue);
        }
        
        // Apply price filter
        if (priceValue !== 'all') {
          filteredServices = filteredServices.filter(service => {
            const price = parseFloat(service.price);
            switch (priceValue) {
              case '0-2000':
                return price >= 0 && price <= 2000;
              case '2000-5000':
                return price > 2000 && price <= 5000;
              case '5000-10000':
                return price > 5000 && price <= 10000;
              case '10000+':
                return price > 10000;
              default:
                return true;
            }
          });
        }
        
        // Apply search filter
        if (searchQuery) {
          filteredServices = filteredServices.filter(service =>
            service.service_name.toLowerCase().includes(searchQuery) ||
            service.description.toLowerCase().includes(searchQuery) ||
            service.service_type.toLowerCase().includes(searchQuery)
          );
        }
        
        // Apply sorting
        filteredServices.sort((a, b) => {
          switch (sortValue) {
            case 'name':
              return a.service_name.localeCompare(b.service_name);
            case 'price-low':
              return parseFloat(a.price) - parseFloat(b.price);
            case 'price-high':
              return parseFloat(b.price) - parseFloat(a.price);
            case 'category':
              return a.service_type.localeCompare(b.service_type);
            default:
              return 0;
          }
        });
        
        // Show AI search if no results and search query exists
        if (filteredServices.length === 0 && searchQuery) {
          loadingIndicator.classList.remove('hidden');
          clearTimeout(aiSearchTimer);
          aiSearchTimer = setTimeout(() => {
            aiSearch(searchQuery, allServices);
          }, 1000);
        } else {
          loadingIndicator.classList.add('hidden');
          renderServices(filteredServices);
        }
        
        updateActiveFilters(categoryValue, priceValue, searchQuery);
      }

      function updateActiveFilters(category, price, search) {
        const activeFiltersContainer = activeFiltersDiv.querySelector('div');
        let activeFilters = [];
        
        if (category !== 'All Services') {
          activeFilters.push(`Category: ${category}`);
        }
        
        if (price !== 'all') {
          const priceLabels = {
            '0-2000': '₱0 - ₱2,000',
            '2000-5000': '₱2,000 - ₱5,000',
            '5000-10000': '₱5,000 - ₱10,000',
            '10000+': '₱10,000+'
          };
          activeFilters.push(`Price: ${priceLabels[price]}`);
        }
        
        if (search) {
          activeFilters.push(`Search: "${search}"`);
        }
        
        if (activeFilters.length > 0) {
          activeFiltersContainer.innerHTML = `
            <span class="text-sm text-neutral-600 font-medium">Active filters:</span>
            ${activeFilters.map(filter => `
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                ${filter}
              </span>
            `).join('')}
          `;
          activeFiltersDiv.classList.remove('hidden');
        } else {
          activeFiltersDiv.classList.add('hidden');
        }
      }
      


        // AI Search - Semantic matching and dynamic pricing generation
        async function aiSearch(query, allServices) {
            try {
                const response = await fetch('/api/aiSearch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                        body: JSON.stringify({
                            query: query,
                            services: allServices
                        })
                    });
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`HTTP error! status: ${response.status}, message:${errorText}`);
                    }

                const data = await response.json();
                const generatedServices = parseGeneratedServices(data.responseText);
                    
                if (generatedServices.length > 0) {
                    renderServices(generatedServices); 

                    loadingIndicator.classList.add('hidden');

                } else {
                    console.error("No valid services generated. Trying manual fallback.");
                    generateManualServices(query); 
                }

            } catch (error) {
                console.error("Internal error has happened: ", error);
                generateManualServices(query); 
            }
        }
    
        function parseGeneratedServices(responseText) {
            // Split the response by blank lines between services
            const serviceBlocks = responseText.split("\n\n").filter(block => block.trim() !== "");
            
            const services = [];

            serviceBlocks.forEach(block => {
                const lines = block.split("\n").map(line => line.trim());
                let serviceType = "", serviceName = "", serviceDescription = "", price = "";
            
            
            // Extract information from each line
            lines.forEach(line => {
                if (line.startsWith("Service Type:")) {
                    serviceType = line.replace("Service Type:", "").trim();
                } else if (line.startsWith("Service Name:")) {
                    serviceName = line.replace("Service Name:", "").trim();
                } else if (line.startsWith("Service Description:")) {
                    serviceDescription = line.replace("Service Description:", "").trim();
                    } else if (line.startsWith("Price:")) {
                    price = line.replace("Price:", "").trim();
                    price = parsePrice(price); // Parse the price to ensure it's a valid number
                }
            });
        
            
            // If valid service data is found, push the service to the array
            if (serviceType && serviceName && serviceDescription && price !== "") {
            services.push({
                    service_type: serviceType,
                    service_name: serviceName,
                    description: serviceDescription,
                    price: price
                });
            }
        });
        
        return services;

        }

       // Function to parse price (e.g., "600 PHP" to numeric value)
        function parsePrice(priceText) {
                // Clean up and extract the numeric value
                const priceMatch = priceText.match(/(\d+)/);
                return priceMatch ? parseInt(priceMatch[0], 10) : null;
            }
            
            // Function to generate a dynamic price based on service difficulty
            function generatePriceBasedOnDifficulty(description) {
                const difficultyScore = calculateDifficulty(description); // A helper function to calculate difficulty based on description
            // Price based on difficulty (randomized between 100 and 1000 PHP)
                const price = Math.floor(Math.random() * (1000 - 100 + 1)) + 100;
                return price;
        }
    
        // A simple helper to determine the difficulty of the service based on description length or complexity
        function calculateDifficulty(description) {
            const wordCount = description.split(" ").length;
            if (wordCount > 100) {
                return 0.75; // High difficulty
            } else if (wordCount > 50) {
                return 0.5; // Medium difficulty
            } else {
                return 0.25; // Low difficulty
            }
        }

        // Fallback function to manually generate services based on query if AI fails
        function generateManualServices(query) {
            loadingIndicator.classList.add('hidden');

            const matchedServices = allServices.filter(service => {
                return service.service_name.toLowerCase().includes(query.toLowerCase()) ||
                service.description.toLowerCase().includes(query.toLowerCase());
            });
            
            // If no direct matches, generate services with prices
            if (matchedServices.length === 0) {
                    const noServicesMessage = document.createElement('div');
                    noServicesMessage.classList.add(
                        'col-span-full',
                        'text-center',
                        'text-gray-400',
                        'font-semibold',
                        'text-xl'
                    );
                    noServicesMessage.innerHTML = 'Currently, there are no services being offered based on your search.';
                    servicesGrid.appendChild(noServicesMessage);
                    return;
            } else {
                renderServices(matchedServices); 
            }
        }

        // Fetch services and setup filters
        await fetchServices();
        setupFilterEventListeners();
        });
    </script>

    <script>
(function() {
  var path = window.location.pathname;
  if (!path.endsWith('.html') && path !== '/' && !path.match(/\.[a-zA-Z0-9]+$/)) {
    var htmlPath = path.endsWith('/') ? path.slice(0, -1) : path;
    htmlPath += '.html';
    var xhr = new XMLHttpRequest();
    xhr.open('HEAD', htmlPath, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
        window.location.replace(htmlPath + window.location.search + window.location.hash);
      }
    };
    xhr.send();
  }
})();
</script>
@endpush
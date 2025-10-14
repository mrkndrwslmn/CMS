@extends('layouts.public')

@section('title', 'Our Services - Academic and Programming Solutions')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    <script defer src="https://cdn.vercel-insights.com/v1/script.js?projectId=prj_8NsY544ll3Q74OVb6njoN8QFj0kl"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1864950796514595" crossorigin="anonymous"></script>
@endpush

@section('content')

  <!-- Hero Section -->
  <section class="min-h-[60vh] relative flex items-center pt-32 pb-16 section-padding overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-grid-pattern opacity-50"></div>
    
    <!-- Background Shapes -->
    <div class="absolute top-20 right-10 w-72 h-72 bg-primary-500/10 rounded-full blur-3xl animate-float-slow"></div>
    <div class="absolute bottom-20 left-10 w-96 h-96 bg-accent-500/10 rounded-full blur-3xl animate-float-delay"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-secondary-500/5 rounded-full blur-3xl animate-float"></div>
    
    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center" data-aos="fade-up">
      <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-4 inline-block py-1.5 px-4 rounded-full bg-primary-100/80 backdrop-blur-sm">Explore our Services</span>
      <h1 class="text-4xl md:text-5xl lg:text-6xl heading-serif mb-6 leading-tight">
        Our <span class="gradient-text">Services & Pricing</span>
      </h1>
      <p class="text-lg text-neutral-600 mb-10 max-w-3xl mx-auto leading-relaxed">
        Explore our full range of academic and technical services — with clear starting prices so you know exactly what to expect. No hidden fees. No surprises. Just premium work that fits your goals and your budget.
      </p>
      <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto rounded-full"></div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-6">
      
      <!-- Controls: Filter Buttons & Search Bar -->
      <div class="mb-12 sticky top-24 z-30" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-dark rounded-2xl shadow-lg p-6 border border-neutral-200">
          <div class="flex flex-col md:flex-row gap-6 items-center">
            <!-- Search Bar -->
            <div class="relative w-full md:w-auto md:flex-1">
              <input type="text" id="serviceSearch" placeholder="Search for a service... (e.g., 'React App')" 
                class="w-full pl-12 pr-4 py-3 rounded-xl bg-white border border-neutral-200 focus:ring-2 focus:ring-primary-500 focus:border-transparent outline-none transition-all duration-300 text-neutral-700 placeholder-neutral-400 shadow-sm">
              <div class="absolute left-4 top-1/2 -translate-y-1/2 text-neutral-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>  
            
            <!-- Filter Buttons -->
            <div class="flex flex-wrap justify-center gap-2 md:gap-3">
              <button class="filter-btn text-sm px-5 py-2.5 rounded-xl font-medium transition-all duration-300 selected bg-primary-600 text-white shadow-md" data-filter="All Services">
                All Services
              </button>
              <button class="filter-btn text-sm px-5 py-2.5 rounded-xl font-medium transition-all duration-300 bg-white text-neutral-700 hover:bg-primary-50 hover:text-primary-600 border border-neutral-200" data-filter="Writing">
                Writing
              </button>
              <button class="filter-btn text-sm px-5 py-2.5 rounded-xl font-medium transition-all duration-300 bg-white text-neutral-700 hover:bg-secondary-50 hover:text-secondary-600 border border-neutral-200" data-filter="Editing & Arts">
                Editing & Arts
              </button>
              <button class="filter-btn text-sm px-5 py-2.5 rounded-xl font-medium transition-all duration-300 bg-white text-neutral-700 hover:bg-success-50 hover:text-success-600 border border-neutral-200" data-filter="Programming">
                Programming
              </button>
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
          <p class="text-sm text-neutral-500 mt-2">Using AI to find the best match</p>
        </div>
      </div>

      <!-- Services Grid -->
      <div id="services-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Services will be populated here by JavaScript -->
      </div>
    </div>
  </section>

  <!-- Call to Action Section -->
  <section class="py-32 relative">
    <div class="max-w-7xl mx-auto px-6">
      <div class="rounded-3xl overflow-hidden relative">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-accent-500/10 rounded-3xl transform -rotate-1"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-accent-500/10 to-primary-500/10 rounded-3xl transform rotate-1 opacity-70"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-30"></div>
        
        <!-- Floating Shapes -->
        <div class="absolute top-10 left-10 w-32 h-32 bg-primary-300/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-10 right-10 w-40 h-40 bg-accent-300/20 rounded-full blur-3xl"></div>
        
        <div class="relative glass-dark p-12 md:p-20 z-10 border border-white/20">
          <div class="max-w-4xl mx-auto text-center">
            <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-4 inline-block py-1.5 px-4 rounded-full bg-primary-100/80 backdrop-blur-sm">Get Started Today</span>
            <h2 class="heading-serif text-4xl md:text-5xl mb-6">
              Ready to <span class="gradient-text">get started</span>?
            </h2>
            <p class="text-lg text-neutral-600 mb-10 max-w-2xl mx-auto leading-relaxed">
              Didn't see exactly what you're looking for? No worries — we handle custom projects all the time. Tell us what you need, and we'll craft a solution (and quote) just for you.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <a href="/get-started" class="btn-primary inline-flex items-center justify-center group">
                <span>Get Started</span>
              </a>
              <a href="/" class="btn-secondary inline-flex items-center justify-center group">
                <svg class="w-5 h-5 mr-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Back to Home</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>  
  
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

      async function fetchServices() {
          try {
              const response = await fetch('/api/services');
              if (!response.ok) {
                  throw new Error(`HTTP error! status: ${response.status}`);
              }
              const data = await response.json();
              allServices = data;
              renderServices(allServices);
          } catch (error) {
              console.error('Error fetching services:', error);
              servicesGrid.innerHTML = `<div class="col-span-full text-center text-neutral-500">Failed to load services. Please try again later.</div>`;
          }
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
                <a href="/contact" class="inline-flex items-center mt-6 px-6 py-3 bg-primary-600 text-white font-medium rounded-xl hover:bg-primary-700 transition-colors shadow-md">
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
            <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-semibold ${colors.badge} shadow-sm">
              <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              ${service.service_type}
            </span>
            <a href="/get-started?service=${encodeURIComponent(service.service_name)}&type=${encodeURIComponent(service.service_type)}&description=${encodeURIComponent(service.description)}&price=${service.price}" class="inline-flex items-center text-sm font-bold px-5 py-2.5 rounded-xl transition-all duration-300 ${colors.buttonBg} ${colors.buttonText} ${colors.buttonHover} shadow-md hover:shadow-lg group/btn">
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
        switch (serviceType) {
            case 'Writing':
                return { 
                  badge: 'bg-primary-100 text-primary-700', 
                  iconBg: 'bg-primary-100',
                  buttonBg: 'bg-primary-600',
                  buttonText: 'text-white',
                  buttonHover: 'hover:bg-primary-700'
                };
            case 'Editing & Arts':
                return { 
                  badge: 'bg-secondary-100 text-secondary-700', 
                  iconBg: 'bg-secondary-100',
                  buttonBg: 'bg-secondary-600',
                  buttonText: 'text-white',
                  buttonHover: 'hover:bg-secondary-700'
                };
            case 'Programming':
                return { 
                  badge: 'bg-success-100 text-success-700', 
                  iconBg: 'bg-success-100',
                  buttonBg: 'bg-success-600',
                  buttonText: 'text-white',
                  buttonHover: 'hover:bg-success-700'
                };
            default:
                return { 
                  badge: 'bg-neutral-100 text-neutral-700', 
                  iconBg: 'bg-neutral-100',
                  buttonBg: 'bg-neutral-600',
                  buttonText: 'text-white',
                  buttonHover: 'hover:bg-neutral-700'
                };
        }
      }

      function getServiceIcon(serviceType) {
          const iconClasses = "w-8 h-8";
          switch (serviceType) {
              case 'Writing':
                  return `<svg class="${iconClasses} text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>`;
              case 'Editing & Arts':
                  return `<svg class="${iconClasses} text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>`;
              case 'Programming':
                  return `<svg class="${iconClasses} text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>`;
              default:
                  return `<svg class="${iconClasses} text-neutral-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>`;
          }
      }

      function setupFilterButtons() {
        const filterButtons = document.querySelectorAll('button[data-filter]');
        filterButtons.forEach(button => {
          button.addEventListener('click', () => {
            // Remove selected state from all buttons
            filterButtons.forEach(btn => {
              btn.classList.remove('selected', 'bg-primary-600', 'text-white', 'shadow-md');
              btn.classList.add('bg-white', 'text-neutral-700', 'border', 'border-neutral-200');
            });
            
            // Add selected state to clicked button
            button.classList.remove('bg-white', 'text-neutral-700', 'border', 'border-neutral-200');
            button.classList.add('selected', 'bg-primary-600', 'text-white', 'shadow-md');
            
            const filter = button.getAttribute('data-filter');
            const searchQuery = searchInput.value.toLowerCase();
            filterServices(filter, searchQuery);
          });
        });
      }
      
      function filterServices(filter, query) {
        let filteredServices = allServices;
        
        if (filter !== 'All Services') {
            filteredServices = filteredServices.filter(service => service.service_type === filter);
        }
        
        if (query) {
            filteredServices = filteredServices.filter(service =>
                service.service_name.toLowerCase().includes(query) ||
                service.description.toLowerCase().includes(query) ||
                service.service_type.toLowerCase().includes(query)
            );
        }
      
        
            if (filteredServices.length === 0) {
                servicesGrid.innerHTML = '';
                clearTimeout(aiSearchTimer); 

                loadingIndicator.classList.remove('hidden');

                aiSearchTimer = setTimeout(() => {
                    aiSearch(query, allServices); 
                }, 1000); 
            
            } else {
                clearTimeout(aiSearchTimer); 
                renderServices(filteredServices);
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
                    console.log(response);

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

        // Search input event listener
        const searchInput = document.getElementById('serviceSearch');
        searchInput.addEventListener('input', () => {
        const activeFilter = document.querySelector('button.selected').getAttribute('data-filter');
        const searchQuery = searchInput.value.toLowerCase();
        filterServices(activeFilter, searchQuery);
        });
    
        // Fetch services and setup filter buttons
        await fetchServices();
        setupFilterButtons();
        });
    </script>

    <script src="redirector.js"></script>
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
@extends('layouts.public')

@section('title', 'Our Services - Academic and Programming Solutions')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    
@endpush

@section('content')

  <!-- Hero Section -->
    <section class="section-padding pt-32 md:pt-40 text-center">
        <div class="max-w-4xl mx-auto px-6" data-aos="fade-up">
            <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-4 inline-block py-1.5 px-4 rounded-full bg-primary-100/80 backdrop-blur-sm">Our Story</span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl heading-serif mb-6 leading-tight">
                Built on Passion, Driven by <span class="gradient-text">Results</span>
            </h1>
            <p class="text-lg text-neutral-600 max-w-3xl mx-auto leading-relaxed">
                We’re not a big agency and that’s our edge. We’re a tight-knit team of three, united by a love for learning, coding, and helping others succeed. From late-night deadlines to complex systems architecture, we’ve been there — and now, we’re here to make it easier for you. We blend academic brainpower with technical firepower to deliver solutions that are smart, fast, and built around you.
            </p>
        </div>
    </section>

    <!-- Our Journey Section -->
    <section class="section-padding bg-neutral-50/70">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-24 items-center">
                <div data-aos="fade-right">
                    <div class="relative z-10 p-3">
                        <img src="https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/pictures/hero-image.png" alt="Team working on a project" class="rounded-2xl shadow-inner object-cover w-full border border-neutral-100"/>
                    </div>
                </div>
                <div data-aos="fade-left" data-aos-delay="100">
                    <h2 class="text-3xl md:text-4xl heading-serif mb-6">From a Shared Vision to Proven Success</h2>
                    <p class="text-neutral-600 text-lg mb-6 leading-relaxed">
                        Treis Adiutor started in 2019 as a passion project. A few friends helping classmates with mathematics and OED assignments. What we didn’t expect? That spark would grow into a full-service powerhouse trusted by students, professionals, and startups alike. Today, we offer everything from academic research and digital storytelling to complex programming and system builds.
                    </p>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="w-6 h-6 rounded-full bg-primary-100 flex-shrink-0 flex items-center justify-center mr-4 mt-1">
                                <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="text-neutral-600">A deep commitment to quality and client satisfaction.</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-6 h-6 rounded-full bg-primary-100 flex-shrink-0 flex items-center justify-center mr-4 mt-1">
                                <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="text-neutral-600">Efficient, customized solutions made with real people in mind.</span>
                        </li>
                        <li class="flex items-start">
                            <div class="w-6 h-6 rounded-full bg-primary-100 flex-shrink-0 flex items-center justify-center mr-4 mt-1">
                                <svg class="w-4 h-4 text-primary-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                            </div>
                            <span class="text-neutral-600">A growing track record of trust, impact, and on-time results.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Values & Milestones Section -->
    <section class="section-padding">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl md:text-4xl heading-serif mb-4">Our Core Philosophy</h2>
                <p class="text-lg text-neutral-600 max-w-3xl mx-auto">Our success is built on collaboration, continuous learning, and adapting to new challenges. These values drive everything we do.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <!-- Value Card 1 -->
                <div class="glass-dark rounded-3xl p-8 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl heading-serif mb-2">Collaboration</h3>
                    <p class="text-neutral-600">
                        We don’t just work for you — we work <em>with</em> you. Every project is a partnership where your goals, ideas, and feedback shape the outcome.
                    </p>
                </div>
                <!-- Value Card 2 -->
                <div class="glass-dark rounded-3xl p-8 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-info-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-info-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-xl heading-serif mb-2">Innovation</h3>
                    <p class="text-neutral-600">
                        The world moves fast — so do we. We combine creative thinking with the latest tools to build smarter, faster, more impactful solutions.
                    </p>
                </div>
                <!-- Value Card 3 -->
                <div class="glass-dark rounded-3xl p-8 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-success-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="text-xl heading-serif mb-2">Integrity</h3>
                    <p class="text-neutral-600">
                        Trust is everything. From original academic work to secure coding, we uphold the highest standards of honesty, quality, and confidentiality.
                    </p>
                </div>
            </div>

            <!-- Milestones -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center" data-aos="fade-up">
                <div class="border-t-2 border-primary-200 pt-4">
                    <p class="text-4xl heading-serif gradient-text">2019</p>
                    <p class="text-sm text-neutral-500 mt-1">Year Founded</p>
                </div>
                <div class="border-t-2 border-primary-200 pt-4">
                    <p class="text-4xl heading-serif gradient-text">2,000+</p>
                    <p class="text-sm text-neutral-500 mt-1">Projects Completed</p>
                </div>
                <div class="border-t-2 border-primary-200 pt-4">
                    <p class="text-4xl heading-serif gradient-text">500+</p>
                    <p class="text-sm text-neutral-500 mt-1">Satisfied Clients</p>
                </div>
                <div class="border-t-2 border-primary-200 pt-4">
                    <p class="text-4xl heading-serif gradient-text">100%</p>
                    <p class="text-sm text-neutral-500 mt-1">On-time Delivery</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 md:py-28 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-primary-50/30 to-white -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 relative">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-12" data-aos="fade-up">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-full shadow-sm border border-neutral-200 mb-8">
                        <div class="flex -space-x-2">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 border-2 border-white"></div>
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-info-400 to-info-600 border-2 border-white"></div>
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-success-400 to-success-600 border-2 border-white"></div>
                        </div>
                        <span class="text-sm font-medium text-neutral-700">Trusted by 500+ companies</span>
                    </div>

                        <h2 class="heading-serif text-4xl md:text-5xl lg:text-6xl mb-6 text-neutral-900">Ready to partner <br class="hidden md:block"/><span class="gradient-text">with us?</span></h2>
                        <p class="text-lg md:text-xl text-neutral-600 mb-12 max-w-3xl mx-auto leading-relaxed">When you work with Treis Adiutor, you're not just hiring a service—you're partnering with a dedicated team committed to your success. Let's build something great together.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                        <a href="{{ url('/contact') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-xl font-semibold shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/30 transition-all duration-300 hover:-translate-y-0.5">
                            <span class="relative">Start Your Project</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="{{ url('/services') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-neutral-700 rounded-xl font-semibold border-2 border-neutral-200 hover:border-neutral-300 shadow-sm hover:shadow-md transition-all duration-300">
                            <span>View Services</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
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
                        <div class="w-12 h-12 rounded-xl bg-success-100 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    document.addEventListener('DOMContentLoaded', () => {
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
    });
  </script>
@endpush
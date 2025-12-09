@extends('layouts.public')

@section('title', 'Technology & Digital Solutions for Startups')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    
@endpush

@section('content')

    <section class="min-h-screen relative flex items-center pt-24 pb-16 section-padding overflow-hidden">
        <!-- Vibrant gradient base -->
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-white via-primary-100/50 to-info-100/40"></div>
        
        <!-- Animated light orbs for energy -->
        <div class="absolute top-10 -right-16 w-72 h-72 bg-info-400/50 blur-[160px] rounded-full -z-10 animate-float"></div>
        <div class="absolute bottom-[-80px] -left-10 w-80 h-80 bg-primary-400/45 blur-[140px] rounded-full -z-10 animate-float-delay"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 bg-primary-300/35 blur-[130px] rounded-full -z-10 animate-float-slow"></div>
        
        <!-- Soft gradient fade at bottom -->
        <div class="absolute inset-x-0 bottom-0 h-64 bg-gradient-to-t from-white/90 via-white/40 to-transparent -z-10"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="flex items-center mb-4">
                        <span class="px-3 py-1 rounded-full bg-success-100 text-success-700 text-sm font-semibold inline-flex items-center">
                            <x-lucide-check-circle class="w-4 h-4 mr-1" />
                            <span>Highly Satisfied Clients</span>
                        </span>
                    </div>
                    
                    <h1 class="heading-serif text-4xl md:text-5xl lg:text-6xl font-semibold text-neutral-800 mb-6 leading-tight">
                        Get <span class="gradient-text">Reliable, Professional, </span> and <span class="gradient-text">High-quality</span> Services
                    </h1>
                    
                    <p class="text-lg md:text-xl text-neutral-600 mb-6 leading-relaxed">
                        Tech startups and businesses trust us with their digital transformation. Professional, reliable, and scalable solutions that drive growth and innovation.
                    </p>
                    
                    <div class="flex flex-col mb-10 bg-primary-50 px-5 py-4 rounded-xl border border-primary-100">
                        <div class="flex items-center mb-2">
                            <div class="mr-3 text-primary-500">
                                <x-lucide-check-circle class="w-5 h-5" />
                            </div>
                            <p class="text-sm font-semibold text-neutral-800">On-time delivery</p>
                        </div>
                        <div class="flex items-center">
                            <div class="mr-3 text-primary-500">
                                <x-lucide-star class="w-5 h-5" />
                            </div>
                            <p class="text-sm font-medium text-neutral-700">Trusted by 500+ tech startups and businesses worldwide</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-6">
                        <a href="/contact" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-primary-600 text-white text-base font-medium rounded-xl shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                            <span>Get Started</span>
                            <x-lucide-arrow-right class="w-5 h-5" />
                        </a>
                        <a href="{{ url('/services') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white text-neutral-700 text-base font-medium rounded-xl border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                            <span>View Services</span>
                        </a>
                    </div>
                    
                    <div class="mt-12 border-t border-neutral-100 pt-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center">
                                <div class="flex -space-x-3 mr-4">
                                    <div class="w-10 h-10 rounded-full bg-primary-500 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-primary-300 shadow-sm">G</div>
                                    <div class="w-10 h-10 rounded-full bg-info-500 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-info-300 shadow-sm">M</div>
                                    <div class="w-10 h-10 rounded-full bg-primary-600 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-primary-400 shadow-sm">K</div>
                                    <div class="w-10 h-10 rounded-full bg-info-600 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-info-400 shadow-sm">+</div>
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <div class="flex">
                                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        </div>
                                        <span class="text-sm font-medium text-neutral-700 ml-2">500+ satisfied clients</span>
                                    </div>
                                    <span class="text-xs text-neutral-500">Trusted by tech companies & startups</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative hidden md:block">
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 via-info-500/20 to-primary-500/20 rounded-3xl opacity-60 blur-3xl transform -rotate-6"></div>
                    
                    <div class="absolute top-12 -left-10 w-20 h-20 bg-primary-200/30 rounded-full blur-xl"></div>
                    <div class="absolute bottom-12 -right-10 w-24 h-24 bg-info-200/30 rounded-full blur-xl"></div>
                    
                    <div class="relative z-10 p-3 rounded-3xl">
                        <img 
                            src="https://kkb.treisadiutor.com/LOGOS%20(1).gif" 
                            alt="Hero Image" 
                            class="rounded-2xl object-cover w-full"
                            loading="eager"
                            fetchpriority="high"
                        />                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="featured-projects" class="section-padding bg-white relative overflow-hidden">
        <!-- Seamless transition gradients -->
        <div class="absolute inset-0 bg-gradient-to-b from-primary-50/30 via-white to-info-50/30 -z-10"></div>
        <div class="absolute top-0 right-[-100px] w-[400px] h-[400px] bg-primary-400/20 blur-[130px] rounded-full -z-10 animate-float"></div>
        <div class="absolute bottom-0 left-[-100px] w-[380px] h-[380px] bg-info-400/20 blur-[120px] rounded-full -z-10 animate-float-delay"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20 -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Our Work</span>
                <h2 class="text-3xl md:text-4xl font-semibold text-neutral-800 mb-6">From <span class="gradient-text">Concept to Completion</span></h2>
                <p class="text-neutral-600 max-w-3xl mx-auto text-lg leading-relaxed">
                    We don't just talk the talk. Explore a curated selection of our projects to see how we transform complex challenges into elegant, effective solutions that deliver real-world results.
                </p>
            </div>
        </div>

        <div class="marquee-container" data-aos="fade-up" data-aos-delay="100" style="min-height: 96px;">
            <div id="marquee-content" class="marquee-content flex items-center space-x-8 py-4 min-h-[96px]" style="opacity: 1 !important;">
                <div class="flex items-center justify-center w-screen text-neutral-500">
                    <svg class="animate-spin h-6 w-6 text-primary-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Loading Projects...
                </div>
            </div>
        </div>

        <div class="text-center mt-16" data-aos="fade-up">
            <a href="{{ url('/featured-projects') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all group">
                <span>Explore All Projects</span>
                <x-lucide-arrow-right class="w-5 h-5 transition-transform group-hover:translate-x-1" />
            </a>
        </div>
    </section>

    <section class="py-20 relative overflow-hidden -mb-12">
        <!-- Cohesive background that flows from previous section -->
        <div class="absolute inset-0 bg-gradient-to-b from-info-50/40 via-white/60 to-transparent -z-10"></div>
        <div class="absolute top-0 right-[-80px] w-[450px] h-[450px] bg-info-400/25 blur-[140px] rounded-full -z-10 animate-float"></div>
        <div class="absolute bottom-[-100px] left-[-60px] w-[400px] h-[400px] bg-primary-400/25 blur-[130px] rounded-full -z-10 animate-float-delay"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-15 -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Our Services</span>
                <h2 class="text-3xl md:text-4xl font-semibold text-neutral-800 mb-4 mt-4">What We <span class="gradient-text">Excel At</span></h2>
                <p class="text-neutral-600 max-w-2xl mx-auto mt-6">
                    Technology solutions powered by proven strategies, creative thinking, and deep technical expertise for modern businesses.
                </p>
                <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-info-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="{{ url('/services') }}" class="block group relative">
                    
                    <div class="relative rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        
                        <div class="h-2 w-full bg-gradient-to-r from-primary-500 to-primary-700"></div>
                        
                        <div class="p-8">
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <h3 class="text-xl font-semibold text-neutral-800 group-hover:text-primary-600 transition-colors duration-300">Digital Content & Strategy</h3>
                                <div class="ml-auto">
                                    <span class="flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-success-500 text-white shadow-sm">
                                        PRO
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-neutral-600 mb-4">No fluff. No filler. Just smart, high-impact content that drives business growth.</p>
                            
                            <div class="mb-5 rounded-xl bg-gradient-to-r from-primary-50 to-primary-100 border border-primary-100 p-3 shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-primary-700">Brand-Aligned Content</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-2 rounded-lg hover:bg-primary-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Technical & Marketing Content</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-primary-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Market Research & Analysis</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-primary-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Documentation & White Papers</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ url('/services') }}" class="block group relative">
                    
                    <div class="relative rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                        <div class="h-2 w-full bg-gradient-to-r from-info-500 to-info-700"></div>
                        
                        <div class="p-8">
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-info-500 to-info-700 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <h3 class="text-xl font-semibold text-neutral-800 group-hover:text-info-600 transition-colors duration-300">Programming & Tech Services</h3>
                                <div class="ml-auto">
                                    <span class="flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-primary-500 text-white shadow-sm">
                                        PRO
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-neutral-600 mb-4">Clean code. Scalable systems. Built for now and the future.</p>
                            
                            <div class="mb-5 rounded-xl bg-gradient-to-r from-info-50 to-info-100 border border-info-100 p-3 shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-info-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-info-700">Fast Turnaround</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-2 rounded-lg hover:bg-info-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-info-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Full-Stack Development</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-info-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-info-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">System & App Architecture</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-info-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-info-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-info-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Cloud Infrastructure</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ url('/services') }}" class="block group relative">
                    <div class="relative rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                        
                        <div class="h-2 w-full bg-gradient-to-r from-success-500 to-success-700"></div>
                        
                        <div class="p-8">
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-success-500 to-success-700 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <h3 class="text-xl font-semibold text-neutral-800 group-hover:text-success-600 transition-colors duration-300">Consulting Services</h3>
                                <div class="ml-auto">
                                    <span class="flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-success-500 text-white shadow-sm">
                                        EXPERT
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-neutral-600 mb-4">When you're stuck, we help you plan smarter and move faster.</p>
                            
                            <div class="mb-5 rounded-xl bg-gradient-to-r from-success-50 to-success-100 border border-success-100 p-3 shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-success-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-success-700">Expert Help</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-2 rounded-lg hover:bg-success-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-success-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Project Planning</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-success-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-success-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Programming Advisory</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-success-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-success-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Code Reviews & QA</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section class="py-20 relative overflow-hidden -mb-12">
    <!-- Flow from services section -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/60 to-transparent -z-10"></div>
    <div class="absolute top-[-80px] right-[-100px] w-[500px] h-[500px] bg-primary-400/25 blur-[150px] rounded-full -z-10 animate-float"></div>
    <div class="absolute bottom-[-100px] left-[-80px] w-[480px] h-[480px] bg-info-400/20 blur-[140px] rounded-full -z-10 animate-float-delay"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-15 -z-10"></div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="flex items-center justify-center mb-3">
                <div class="flex -space-x-2 mr-3">
                    <x-lucide-star class="w-5 h-5 text-warning-400 fill-warning-400" />
                </div>
                <span class="text-primary-600 font-medium">Consistently praised by hundreds of satisfied clients</span>
            </div>
            <h2 class="text-3xl md:text-4xl font-semibold text-neutral-800 mb-4 mt-4">Client Success Stories</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto mt-4 mb-8">
                See how our services have helped businesses and professionals achieve their goals
            </p>
            <div class="w-20 h-1 bg-primary-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <div class="group relative" data-aos="fade-up" data-aos-delay="100">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-success-100 text-success-700 rounded-full p-1">
                        <x-lucide-badge-check class="w-4 h-4" />
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 rounded-xl bg-primary-100 flex items-center justify-center">
                            <span class="text-lg font-semibold text-primary-600">AC</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <x-lucide-quote class="w-8 h-8 text-primary-200" />
                        </div>
                    </div>

                    <div class="flex items-center mb-3">
                        <h3 class="text-lg font-medium text-primary-600 mr-2">Web Development</h3>
                        <span class="px-2 py-1 bg-success-50 text-success-700 text-xs font-medium rounded-md">Completed</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"Exceptional work! The team delivered our web application ahead of schedule and exceeded all our expectations."</p>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative" data-aos="fade-up" data-aos-delay="200">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-success-100 text-success-700 rounded-full p-1">
                        <x-lucide-badge-check class="w-4 h-4" />
                    </div>
                </div>

                
                <div class="absolute -top-2 left-4 bg-primary-600 text-white text-xs font-bold px-3 py-1 rounded-full z-10 shadow-md">
                    Brand Success
                </div>

                <div class="bg-white rounded-2xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 rounded-xl bg-info-100 flex items-center justify-center">
                            <span class="text-lg font-semibold text-info-600">KP</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <x-lucide-quote class="w-8 h-8 text-info-200" />
                        </div>
                    </div>

                    <div class="flex items-center mb-3">
                        <h3 class="text-lg font-medium text-info-600 mr-2">Brand Research</h3>
                        <span class="px-2 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-md">Award-winning</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"Their brand research helped our company stand out from competitors. Our new brand identity led to a 40% increase in market recognition and won us the industry innovation award."</p>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative" data-aos="fade-up" data-aos-delay="300">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-success-100 text-success-700 rounded-full p-1">
                        <x-lucide-badge-check class="w-4 h-4" />
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 rounded-xl bg-primary-100 flex items-center justify-center">
                            <span class="text-lg font-semibold text-primary-600">JA</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <x-lucide-quote class="w-8 h-8 text-primary-200" />
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <h3 class="text-lg font-medium text-primary-600 mr-2">Web Development</h3>
                        <span class="px-2 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-md">Project Success</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"Their technological approach streamlined our entire project workflow. Their solutions improved our team's productivity by 80% and helped us deliver exceptional results ahead of schedule."</p>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative" data-aos="fade-up" data-aos-delay="400">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-success-100 text-success-700 rounded-full p-1">
                        <x-lucide-badge-check class="w-4 h-4" />
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 rounded-xl bg-info-100 flex items-center justify-center">
                            <span class="text-lg font-semibold text-info-600">VE</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <x-lucide-quote class="w-8 h-8 text-info-200" />
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <h3 class="text-lg font-medium text-info-600 mr-2">UI Design</h3>
                        <span class="px-2 py-1 bg-warning-50 text-warning-700 text-xs font-medium rounded-md">Brand Success</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"The UI brand design absolutely exceeded our expectations! The visual identity perfectly captures our brand values and has significantly improved our customer engagement metrics."</p>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="py-28 relative overflow-hidden -mb-16">
    <!-- Flow from testimonials section -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/50 to-transparent -z-10"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary-400/25 blur-[150px] rounded-full -z-10 animate-float"></div>
    <div class="absolute bottom-0 left-0 w-[450px] h-[450px] bg-info-400/20 blur-[140px] rounded-full -z-10 animate-float-delay"></div>
    <div class="absolute inset-0 bg-grid-pattern opacity-15 -z-10"></div>
    
    <div class="max-w-7xl mx-auto px-6 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100">Why Choose Us</span>
            <h2 class="text-3xl md:text-4xl font-semibold text-neutral-800 mb-4 mt-4">Why Treis Adiutor?</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto">The perfect partner for your digital transformation and technology journey</p>
            <div class="w-20 h-1 bg-primary-500 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <div class="bg-white rounded-2xl p-8 border border-neutral-100 shadow-sm hover:shadow-md transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 rounded-xl bg-primary-100 mb-8 flex items-center justify-center">
                    <x-lucide-users class="w-7 h-7 text-primary-500" />
                </div>
                <h3 class="text-xl font-semibold text-neutral-800 mb-4 group-hover:text-primary-600 transition-colors duration-300">Personalized Partnership</h3>
                <p class="text-neutral-600 mb-6">You're not just a task on a board. We assign a dedicated adiutor to your project, ensuring personalized attention from start to finish.</p>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-primary-100 mr-3 flex items-center justify-center">
                            <x-lucide-check class="w-3 h-3 text-primary-500" />
                        </div>
                        One-on-one guidance
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-primary-100 mr-3 flex items-center justify-center">
                            <x-lucide-check class="w-3 h-3 text-primary-500" />
                        </div>
                        Custom approach for each project
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-neutral-200 shadow-sm hover:border-neutral-300 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 rounded-2xl bg-info-500 mb-8 flex items-center justify-center">
                    <x-lucide-shield-check class="w-7 h-7 text-white" />
                </div>
                <h3 class="text-xl font-semibold text-neutral-800 mb-4 group-hover:text-info-600 transition-colors duration-300">Advanced Security</h3>
                <p class="text-neutral-600 mb-6">Your work, your data. It’s safe with us. Always. We follow enterprise-grade security standards and strict confidentiality practices.</p>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-info-100 mr-3 flex items-center justify-center">
                            <x-lucide-check class="w-3 h-3 text-info-500" />
                        </div>
                        End-to-end encryption
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-info-100 mr-3 flex items-center justify-center">
                            <x-lucide-check class="w-3 h-3 text-info-500" />
                        </div>
                        Strict confidentiality
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl p-8 border border-neutral-200 shadow-sm hover:border-neutral-300 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 rounded-2xl bg-success-500 mb-8 flex items-center justify-center">
                    <x-lucide-zap class="w-7 h-7 text-white" />
                </div>
                <h3 class="text-xl font-semibold text-neutral-800 mb-4 group-hover:text-success-600 transition-colors duration-300">On-Time Delivery</h3>
                <p class="text-neutral-600 mb-6">Late delivery? Not in our vocabulary. Whether it’s due next week or tomorrow, we make it happen without cutting corners.</p>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-success-100 mr-3 flex items-center justify-center">
                            <x-lucide-check class="w-3 h-3 text-success-500" />
                        </div>
                        Rush service available
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-success-100 mr-3 flex items-center justify-center">
                            <x-lucide-check class="w-3 h-3 text-success-500" />
                        </div>
                        High quality, every time
                    </li>
                </ul>
            </div>
        </div>

        <div class="relative mt-16 pt-16" data-aos="fade-up">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-info-500/10 rounded-3xl transform -rotate-1"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-info-500/10 to-primary-500/10 rounded-3xl transform rotate-1 opacity-70"></div>
            <div class="relative rounded-3xl p-8 md:p-12 border border-white/10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-primary-100 flex items-center justify-center mb-4">
                            <x-lucide-lightbulb class="w-8 h-8 text-primary-500" />
                        </div>
                        <h3 class="text-xl font-semibold text-neutral-800 mb-2">Innovative Solutions</h3>
                        <p class="text-neutral-600">Creative minds. Smart strategies. We tackle your business and tech challenges with innovative ideas that drive results.</p>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-info-100 flex items-center justify-center mb-4">
                            <x-lucide-trending-up class="w-8 h-8 text-info-500" />
                        </div>
                        <h3 class="text-xl font-semibold text-neutral-800 mb-2">Proven Results</h3>
                        <p class="text-neutral-600">Hundreds of individuals and startups trust us because we deliver, every single time.</p>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-success-100 flex items-center justify-center mb-4">
                            <x-lucide-clock class="w-8 h-8 text-success-500" />
                        </div>
                        <h3 class="text-xl font-semibold text-neutral-800 mb-2">24/7 Support</h3>
                        <p class="text-neutral-600">Late-night questions? Deadline stress? We’re here—day or night—to help you out.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="py-28 relative overflow-hidden -mb-16">
        <!-- Flow from why choose us section -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/50 to-transparent -z-10"></div>
        <div class="absolute top-20 right-0 w-96 h-96 bg-primary-400/25 blur-[140px] rounded-full -z-10 animate-float"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-info-400/20 blur-[120px] rounded-full -z-10 animate-float-delay"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-15 -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Our Technologies</span>
                <h2 class="text-3xl md:text-4xl font-semibold text-neutral-800 mb-4 mt-4">Built With <span class="gradient-text">Modern Tech</span></h2>
                <p class="text-neutral-600 max-w-2xl mx-auto">We use today’s most powerful tools and frameworks to create solutions that are fast, scalable, and built to last.</p>
                <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-info-500 mx-auto mt-6 rounded-full"></div>
            </div>
            
            <div id="tech-stack-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative" style="min-height: 400px;">                
                <div class="rounded-3xl p-8 border border-white/10 animate-pulse" data-aos="fade-up" data-aos-delay="100" style="opacity: 1 !important; transform: none !important;">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 organic-shape bg-gradient-to-br from-primary-700/20 to-primary-500/20 backdrop-blur-md flex items-center justify-center">
                            <div class="w-6 h-6 bg-primary-400/30 rounded"></div>
                        </div>
                        <div class="ml-4 h-6 bg-white/10 rounded-lg w-32"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-12 mx-auto"></div>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-14 mx-auto"></div>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-10 mx-auto"></div>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-3xl p-8 border border-white/10 animate-pulse" data-aos="fade-up" data-aos-delay="200" style="opacity: 1 !important; transform: none !important;">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 organic-shape bg-gradient-to-br from-info-700/20 to-info-500/20 backdrop-blur-md flex items-center justify-center">
                            <div class="w-6 h-6 bg-info-400/30 rounded"></div>
                        </div>
                        <div class="ml-4 h-6 bg-white/10 rounded-lg w-40"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-12 mx-auto"></div>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-16 mx-auto"></div>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-10 mx-auto"></div>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-3xl p-8 border border-white/10 animate-pulse" data-aos="fade-up" data-aos-delay="300" style="opacity: 1 !important; transform: none !important;">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 organic-shape bg-gradient-to-br from-success-700/20 to-success-500/20 backdrop-blur-md flex items-center justify-center">
                            <div class="w-6 h-6 bg-success-400/30 rounded"></div>
                        </div>
                        <div class="ml-4 h-6 bg-white/10 rounded-lg w-36"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-14 mx-auto"></div>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-12 mx-auto"></div>
                        </div>
                        <div class="p-3 bg-white/5 rounded-xl text-center backdrop-blur-sm border border-white/5">
                            <div class="w-8 h-8 bg-white/10 mx-auto mb-2 rounded-lg"></div>
                            <div class="h-3 bg-white/10 rounded-lg w-16 mx-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 relative overflow-hidden -mb-12">
        <!-- Flow from tech section -->
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white/60 to-transparent -z-10"></div>
        <div class="absolute top-[-100px] left-0 w-[480px] h-[480px] bg-info-400/25 blur-[150px] rounded-full -z-10 animate-float"></div>
        <div class="absolute bottom-[-120px] right-0 w-[520px] h-[520px] bg-primary-400/25 blur-[160px] rounded-full -z-10 animate-float-delay"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-15 -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Frequently Asked Questions</span>
                <h2 class="text-3xl md:text-4xl font-semibold text-neutral-800 mb-4 mt-4">Got <span class="gradient-text">Questions</span>?</h2>
                <p class="text-neutral-600 max-w-2xl mx-auto mt-6">
                    We've answered common questions from tech startups and businesses
                </p>
                <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-info-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-primary-400" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-3">
                        <x-lucide-clock class="w-5 h-5 text-primary-500 mr-2" />
                        <h3 class="text-lg font-semibold text-neutral-800">How quickly can you deliver?</h3>
                    </div>
                    <p class="text-neutral-600">Most projects are done within <span class="font-medium text-primary-700">1 week</span>, sometimes even faster. Got a tight deadline? We offer <span class="font-medium text-primary-700">expedited services</span> to meet tight deadlines without compromising quality. Our team works around the clock to ensure on-time delivery.</p>
                </div>

                <div class="rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-success-400" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-3">
                        <x-lucide-lock class="w-5 h-5 text-success-500 mr-2" />
                        <h3 class="text-lg font-semibold text-neutral-800">Is my information kept confidential?</h3>
                    </div>
                    <p class="text-neutral-600">Absolutely. We maintain <span class="font-medium text-success-700">strict confidentiality</span> for all client projects. Your personal details are protected by enterprise-grade encryption. We don’t share, resell, or reuse anything—ever.</p>
                </div>

                <div class="rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-warning-400" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-3">
                        <x-lucide-shield-check class="w-5 h-5 text-warning-500 mr-2" />
                        <h3 class="text-lg font-semibold text-neutral-800">What if I'm not happy with the work?</h3>
                    </div>
                    <p class="text-neutral-600">No stress. We offer <span class="font-medium text-warning-700">multiple revisions</span> until you're satisfied. Still not working out? We may issue a <span class="font-medium text-warning-700">partial refund</span> depending on the situation. With over 500+ successful projects and glowing reviews, chances are—you’ll love the result.</p>
                </div>

                <div class="rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-info-400" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center mb-3">
                        <x-lucide-credit-card class="w-5 h-5 text-info-500 mr-2" />
                        <h3 class="text-lg font-semibold text-neutral-800">What payment methods do you accept?</h3>
                    </div>
                    <p class="text-neutral-600">We offer <span class="font-medium text-info-700">flexible and secure payment options</span> including all major e-wallets like Gcash, Maya, GoTyme, PayPal, bank transfers, and cryptocurrencies. Clients pay 50% upfront with the remainder due upon completion.</p>
                </div>
                
                <div class="rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-primary-400" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex items-center mb-3">
                        <x-lucide-file-text class="w-5 h-5 text-primary-500 mr-2" />
                        <h3 class="text-lg font-semibold text-neutral-800">Is your work original and unique?</h3>
                    </div>
                    <p class="text-neutral-600"><span class="font-medium text-primary-700">We stand by our work</span>. All deliverables are original, custom-built for your business needs, and free from plagiarism. We take intellectual property rights seriously.</p>
                </div>
                
                <div class="rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-error-400" data-aos="fade-up" data-aos-delay="600">
                    <div class="flex items-center mb-3">
                        <x-lucide-info class="w-5 h-5 text-error-500 mr-2" />
                        <h3 class="text-lg font-semibold text-neutral-800">How do I get started?</h3>
                    </div>
                    <p class="text-neutral-600">Click the "Start Your Project" button, fill out our brief project form, and you'll receive a custom quote within 2 hours. Once approved, we'll begin work immediately. It’s <span class="font-medium text-error-700">fast, simple, and stress-free.</span></p>
                </div>
            </div>

            <div class="text-center mt-12" data-aos="fade-up">
                <a href="/contact" class="inline-flex items-center justify-center text-primary-600 font-medium group">
                    <span>Have more questions? Contact us</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>
    
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
                        <span class="text-sm font-medium text-neutral-700">Trusted by 500+ tech companies</span>
                    </div>

                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-semibold text-neutral-800 mb-6">
                        Ready to start your <br class="hidden md:block"/>
                        <span class="gradient-text">next project?</span>
                    </h2>

                    <p class="text-lg md:text-xl text-neutral-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                        From concept to launch, we deliver professional solutions that help your business grow. Let's build something great together.
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                        <a href="{{ url('/contact') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-xl font-semibold shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/30 transition-all duration-300 hover:-translate-y-0.5">
                            <span class="relative">Get Started</span>
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
                            <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                            <span class="font-medium">Fast turnaround</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500" />
                            <span class="font-medium">Quality guaranteed</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <x-lucide-check-circle class="w-5 h-5 text-success-500" />
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
<script>
    // Initialize AOS but keep content visible during load
    function initializeAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 1000,
                once: true,
                startEvent: 'load', // Wait for page load
                disable: false
            });
        }
    }

    // Force visibility for elements in viewport
    function ensureContentVisible() {
        const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
        document.querySelectorAll('[data-aos]').forEach(el => {
            const rect = el.getBoundingClientRect();
            // If element is in viewport or close to it, show immediately
            if (rect.top <= viewportHeight * 1.2 && rect.bottom >= -100) {
                el.classList.add('aos-animate');
                el.style.opacity = '1';
                el.style.transform = 'none';
            }
        });
    }

    // Run immediately to show content
    ensureContentVisible();
    
    // Wait for page to be fully ready before enabling AOS animations
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            ensureContentVisible();
            setTimeout(() => {
                document.body.classList.remove('aos-preload');
                initializeAOS();
                ensureContentVisible();
            }, 100);
        });
    } else {
        // DOM already loaded
        ensureContentVisible();
        setTimeout(() => {
            document.body.classList.remove('aos-preload');
            initializeAOS();
            ensureContentVisible();
        }, 100);
    }

    // Ensure visibility after full page load
    window.addEventListener('load', () => {
        ensureContentVisible();
        setTimeout(ensureContentVisible, 200);
    });

    // Handle viewport changes
    window.addEventListener('resize', ensureContentVisible);
    window.addEventListener('scroll', ensureContentVisible);

    document.addEventListener('DOMContentLoaded', async () => {            
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('active');

                const iconPath = mobileMenuButton.querySelector('svg path');
                if (mobileMenu.classList.contains('active')) {
                    iconPath.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                } else {
                    iconPath.setAttribute('d', 'M4 6h16M4 12h16M4 18h16');
                }
            });
        }

        async function fetchTechStack() {
            try {
                const response = await fetch('/api/tech_stack'); 
                if(!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                updateTechStackUI(data);
            } catch (error) {
                console.error('Error fetching tech stack:', error);
            }
        }

        function updateTechStackUI(techData) {
            const techStackContainer = document.getElementById('tech-stack-container');
            // Keep any existing skeleton/placeholders until new content is ready
            
            // Map category keys to color schemes (using defined theme colors)
            const categoryColorMap = {
                'Programming Languages': { from: 'info-700/30', to: 'info-500/30', text: 'info-300', hover: 'info-200', bg: 'info-900/60', info: 'info-400' },
                'Frontend': { from: 'primary-700/30', to: 'primary-500/30', text: 'primary-300', hover: 'primary-200', bg: 'primary-900/60', info: 'primary-400' },
                'Backend': { from: 'info-700/30', to: 'info-500/30', text: 'info-300', hover: 'info-200', bg: 'info-900/60', info: 'info-400' },
                'Databases & Database Management': { from: 'warning-700/30', to: 'warning-500/30', text: 'warning-300', hover: 'warning-200', bg: 'warning-900/60', info: 'warning-400' },
                'Version Control & Collaboration': { from: 'success-700/30', to: 'success-500/30', text: 'success-300', hover: 'success-200', bg: 'success-900/60', info: 'success-400' },
                'DevOps & Cloud': { from: 'primary-700/30', to: 'primary-500/30', text: 'primary-300', hover: 'primary-200', bg: 'primary-900/60', info: 'primary-400' },
                'Testing & CI/CD': { from: 'info-700/30', to: 'info-500/30', text: 'info-300', hover: 'info-200', bg: 'info-900/60', info: 'info-400' },
                'Productivity Tools': { from: 'primary-700/30', to: 'primary-500/30', text: 'primary-300', hover: 'primary-200', bg: 'primary-900/60', info: 'primary-400' },
                'Design & Multimedia Tools': { from: 'error-700/30', to: 'error-500/30', text: 'error-300', hover: 'error-200', bg: 'error-900/60', info: 'error-400' }
            };
            
            const defaultColor = { from: 'neutral-700/30', to: 'neutral-500/30', text: 'neutral-300', hover: 'neutral-200', bg: 'neutral-900/60', info: 'neutral-400' };

            let delay = 100;
            const frag = document.createDocumentFragment();
            for (const categoryKey in techData) {
                if (techData.hasOwnProperty(categoryKey)) {
                    const categoryData = techData[categoryKey];
                    const categoryDiv = document.createElement('div');
                    categoryDiv.classList.add('group', 'relative', 'bg-white/80', 'backdrop-blur-sm', 'rounded-2xl', 'p-8', 'border', 'border-neutral-100/50', 'hover:border-primary-300/50', 'shadow-sm', 'hover:shadow-xl', 'transition-all', 'duration-500', 'hover:-translate-y-1');
                    categoryDiv.setAttribute('data-aos', 'fade-up');
                    categoryDiv.setAttribute('data-aos-delay', delay.toString());
                    delay += 100;
                    
                    const categoryName = categoryData.name;
                    const colorSet = categoryColorMap[categoryName] || defaultColor;

                    categoryDiv.innerHTML = `
                        <div class="absolute inset-0 bg-gradient-to-br from-${colorSet.from} to-${colorSet.to} rounded-3xl opacity-50"></div>
                        <div class="relative">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-${colorSet.bg} to-${colorSet.info}/20 flex items-center justify-center shadow-lg">
                                    <svg class="w-7 h-7 text-${colorSet.info}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-neutral-800 group-hover:text-${colorSet.info} transition-colors duration-300">${categoryName}</h3>
                                    <p class="text-xs text-neutral-500 mt-0.5">Modern tools & frameworks</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-3"></div>
                        </div>
                    `;

                    const techItemsContainer = categoryDiv.querySelector('.grid');

                    categoryData.technologies.forEach(tech => {
                        const techItemDiv = document.createElement('div');
                        techItemDiv.classList.add('tech-item', 'group/item', 'p-4', 'bg-primary-50/30', 'rounded-xl', 'text-center', 'backdrop-blur-sm', 'border', 'border-primary-100/40', 'hover:bg-white', 'hover:border-primary-300', 'hover:shadow-md', 'transition-all', 'duration-300', 'hover:-translate-y-1', 'cursor-pointer');
                        
                        // Create image with error handling for Brandfetch API
                        const img = document.createElement('img');
                        img.src = tech.image;
                        img.alt = tech.name;
                        img.className = 'w-10 h-10 mx-auto mb-3 object-contain group-hover/item:scale-110 transition-transform duration-300';
                        img.loading = 'lazy'; // Lazy load for better performance
                        
                        // Add error handling in case Brandfetch image fails to load
                        img.onerror = function() {
                            // If Brandfetch fails, this will already have the fallback lettermark
                            // But we can add additional styling to indicate it's a fallback
                            console.warn(`Failed to load logo for ${tech.name}`);
                        };
                        
                        const span = document.createElement('span');
                        span.className = 'text-xs text-neutral-700 font-semibold group-hover/item:text-primary-600 transition-colors duration-300';
                        span.textContent = tech.name;
                        
                        techItemDiv.appendChild(img);
                        techItemDiv.appendChild(span);
                        techItemsContainer.appendChild(techItemDiv); 
                    });
                    frag.appendChild(categoryDiv);
                }
            }
            // Replace placeholders with the built content in one operation
            // Remove simple skeletons (elements with animate-pulse) if present
            const placeholders = techStackContainer.querySelectorAll('.animate-pulse');
            placeholders.forEach(p => p.remove());
            techStackContainer.appendChild(frag);
        }

        fetchTechStack();

        async function fetchFeaturedProjects() {
            const container = document.getElementById('marquee-content');
            if (!container) return;

                // show a lightweight skeleton while projects load (prevents empty/zero-height area)
                const projectsSkeleton = `
                    <div class="flex items-center gap-4 w-screen py-6 justify-center">
                        <div class="w-48 h-28 bg-neutral-100 rounded-md animate-pulse"></div>
                        <div class="w-48 h-28 bg-neutral-100 rounded-md animate-pulse"></div>
                        <div class="w-48 h-28 bg-neutral-100 rounded-md animate-pulse"></div>
                    </div>
                `;
                // Only set skeleton if container appears empty
                if (container.children.length === 0 || container.textContent.trim().length === 0) {
                    container.innerHTML = projectsSkeleton;
                }

            try {
                const response = await fetch('/api/showcases?limit=8');

                if (!response.ok) throw new Error('Failed to fetch projects from API');

                const projects = await response.json();

                if (projects.length === 0) {
                    container.innerHTML = '<p class="w-screen text-center text-neutral-500 font-medium">We are building something new. Be right back!</p>';
                    return;
                }

                const fallbackImage = 'https://qzdtlrbpjudrvffrnory.supabase.co/storage/v1/object/public/Treis%20Adiutor/pictures/placeholder.png';

                let cardsHtml = '';
                projects.forEach(project => {
                    const imageUrl = project.thumbnail || fallbackImage;
                    cardsHtml += `
                    <a href="/project-details?slug=${project.slug}" class="project-card relative block group mx-4">
                        <img src="${imageUrl}" 
                            alt="Preview of ${project.project_name}" 
                            class="w-full h-full object-cover bg-neutral-100 aspect-[4/3]">
                        <div class="project-card-content">
                            <h4 class="font-semibold text-lg text-white">${project.project_name}</h4>
                            <p class="text-sm text-white/80">${project.category || ''}</p>
                        </div>
                    </a>
                    `;
                });
                
                container.classList.remove('space-x-8');
                container.innerHTML = cardsHtml + cardsHtml;

            } catch (error) {
                console.error('Error fetching featured projects:', error);
                container.innerHTML = '<p class="w-screen text-center text-neutral-500 font-medium">Could not load recent projects at this time.</p>';
            }
        }

        fetchFeaturedProjects();

        gsap.fromTo('.image-to-move', {
            y: -20, 
            rotate: 5, 
        }, {
            y: 20,  
            rotate: -5,
            duration: 5, 
            yoyo: true, 
            repeat: -1,
            ease: "sine.inOut", 
        });
    });
</script>
@endpush

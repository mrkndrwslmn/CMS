@extends('layouts.public')

@section('title', 'Technology & Digital Solutions for Startups')
@section('site_name', 'Treis Adiutor')

@push('analytics')
    <script defer src="https://cdn.vercel-insights.com/v1/script.js?projectId=prj_8NsY544ll3Q74OVb6njoN8QFj0kl"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1864950796514595" crossorigin="anonymous"></script>
@endpush

@section('content')

    <section class="min-h-screen relative flex items-center pt-24 pb-16 section-padding overflow-hidden">
        <div class="relative z-10 max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div>
                    <div class="flex items-center mb-4">
                        <span class="px-3 py-1 rounded-full bg-green-100 text-success-700 text-sm font-semibold inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Highly Satisfied Clients</span>
                        </span>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl heading-serif mb-6 leading-tight">
                        Get <span class="gradient-text">Reliable, Professional, </span> and <span class="gradient-text">High-quality</span> Services
                    </h1>
                    
                    <p class="text-lg md:text-xl text-neutral-600 mb-6 leading-relaxed">
                        Tech startups and businesses trust us with their digital transformation. Professional, reliable, and scalable solutions that drive growth and innovation.
                    </p>
                    
                    <div class="flex flex-col mb-10 bg-primary-50 px-5 py-4 rounded-xl border border-primary-100">
                        <div class="flex items-center mb-2">
                            <div class="mr-3 text-primary-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-neutral-800">On-time delivery</p>
                        </div>
                        <div class="flex items-center">
                            <div class="mr-3 text-primary-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-neutral-700">Trusted by 500+ tech startups and businesses worldwide</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-6">
                        <a href="/contact" class="btn-primary inline-flex items-center justify-center group py-5 px-8 text-base relative shine-effect">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shine"></span>
                            <span>Get Started</span>
                        </a>
                        <a href="/services" class="border-2 border-primary-500 text-primary-500 rounded-xl inline-flex items-center justify-center py-5 px-8 text-base group">
                            <span>View Services</span>
                        </a>
                    </div>
                    
                    <div class="mt-12 border-t border-neutral-100 pt-6">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div class="flex items-center">
                                <div class="flex -space-x-3 mr-4">
                                    <div class="w-10 h-10 rounded-full bg-primary-500 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-primary-300 shadow-sm">G</div>
                                    <div class="w-10 h-10 rounded-full bg-accent-500 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-accent-300 shadow-sm">M</div>
                                    <div class="w-10 h-10 rounded-full bg-primary-600 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-primary-400 shadow-sm">K</div>
                                    <div class="w-10 h-10 rounded-full bg-accent-600 backdrop-blur-md flex items-center justify-center text-xs text-white border-2 border-accent-400 shadow-sm">+</div>
                                </div>
                                <div>
                                    <div class="flex items-center">
                                        <div class="flex">
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
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
                    <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 via-accent-500/20 to-primary-500/20 rounded-3xl opacity-60 blur-3xl transform -rotate-6"></div>
                    
                    <div class="absolute top-12 -left-10 w-20 h-20 bg-primary-200/30 rounded-full blur-xl"></div>
                    <div class="absolute bottom-12 -right-10 w-24 h-24 bg-accent-200/30 rounded-full blur-xl"></div>
                    
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

    <section id="featured-projects" class="section-padding bg-neutral-50/50 relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-grid-pattern opacity-50"></div>
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Our Work</span>
                <h2 class="heading-serif text-3xl md:text-4xl mb-6">From <span class="gradient-text">Concept to Completion</span></h2>
                <p class="text-neutral-600 max-w-3xl mx-auto text-lg leading-relaxed">
                    We don't just talk the talk. Explore a curated selection of our projects to see how we transform complex challenges into elegant, effective solutions that deliver real-world results.
                </p>
            </div>
        </div>

        <div class="marquee-container" data-aos="fade-up" data-aos-delay="100">
            <div id="marquee-content" class="marquee-content flex items-center space-x-8 py-4">
                <div class="flex items-center justify-center w-screen text-neutral-500">
                    <svg class="animate-spin h-6 w-6 text-primary-500 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    Loading Projects...
                </div>
            </div>
        </div>

        <div class="text-center mt-16" data-aos="fade-up">
            <a href="/featured-projects" class="btn-primary inline-flex items-center group">
                <span>Explore All Projects</span>
                <svg class="w-5 h-5 ml-2 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </section>

    <section class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Our Services</span>
                <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">What We <span class="gradient-text">Excel At</span></h2>
                <p class="text-neutral-600 max-w-2xl mx-auto mt-6">
                    Technology solutions powered by proven strategies, creative thinking, and deep technical expertise for modern businesses.
                </p>
                <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <a href="/services" class="block group relative">
                    
                    <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                        
                        <div class="h-2 w-full bg-gradient-to-r from-primary-500 to-violet-500"></div>
                        
                        <div class="p-8">
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-violet-500 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <h3 class="text-xl font-semibold heading-serif text-neutral-800 group-hover:text-primary-600 transition-colors duration-300">Digital Content & Strategy</h3>
                                <div class="ml-auto">
                                    <span class="flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-green-500 to-green-600 text-white shadow-sm">
                                        PRO
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-neutral-600 mb-4">No fluff. No filler. Just smart, high-impact content that drives business growth.</p>
                            
                            <div class="mb-5 rounded-xl bg-gradient-to-r from-primary-50 to-violet-50 border border-primary-100 p-3 shadow-sm">
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

                <a href="/services" class="block group relative">
                    
                    <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                        <div class="h-2 w-full bg-gradient-to-r from-blue-500 to-accent-500"></div>
                        
                        <div class="p-8">
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-accent-500 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <h3 class="text-xl font-semibold heading-serif text-neutral-800 group-hover:text-accent-600 transition-colors duration-300">Programming & Tech Services</h3>
                                <div class="ml-auto">
                                    <span class="flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm">
                                        PRO
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-neutral-600 mb-4">Clean code. Scalable systems. Built for now and the future.</p>
                            
                            <div class="mb-5 rounded-xl bg-gradient-to-r from-blue-50 to-accent-50 border border-blue-100 p-3 shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-blue-700">Fast Turnaround</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Full-Stack Development</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">System & App Architecture</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-blue-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Cloud Infrastructure</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="/services" class="block group relative">
                    <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                        
                        <div class="h-2 w-full bg-gradient-to-r from-emerald-500 to-cyan-500"></div>
                        
                        <div class="p-8">
                            <div class="mb-6">
                                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-cyan-500 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center mb-4">
                                <h3 class="text-xl font-semibold heading-serif text-neutral-800 group-hover:text-emerald-600 transition-colors duration-300">Consulting Services</h3>
                                <div class="ml-auto">
                                    <span class="flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-sm">
                                        EXPERT
                                    </span>
                                </div>
                            </div>
                            
                            <p class="text-neutral-600 mb-4">When you're stuck, we help you plan smarter and move faster.</p>
                            
                            <div class="mb-5 rounded-xl bg-gradient-to-r from-emerald-50 to-cyan-50 border border-emerald-100 p-3 shadow-sm">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-emerald-700">Expert Help</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-center p-2 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Project Planning</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-neutral-700">Programming Advisory</span>
                                </div>
                                
                                <div class="flex items-center p-2 rounded-lg hover:bg-emerald-50 transition-colors duration-200">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center mr-3 shadow-sm">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

    <section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <div class="flex items-center justify-center mb-3">
                <div class="flex -space-x-2 mr-3">
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                </div>
                <span class="text-primary-600 font-medium">Consistently praised by hundreds of satisfied clients</span>
            </div>
            <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">Client <span class="gradient-text">Success Stories</span></h2>
            <p class="text-neutral-600 max-w-2xl mx-auto mt-4 mb-8">
                See how our services have helped businesses and professionals achieve their goals
            </p>
            <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            <div class="group relative" data-aos="fade-up" data-aos-delay="100">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-green-100 text-green-700 rounded-full p-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="glass-dark rounded-3xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-lg transition-all duration-500 hover:-translate-y-2">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 organic-shape bg-gradient-to-br from-primary-600/30 to-primary-500/30 backdrop-blur-md flex items-center justify-center border border-white/10 shadow-glow">
                            <span class="text-lg heading-serif gradient-text">AC</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <svg class="w-8 h-8 text-primary-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center mb-3">
                        <h3 class="text-lg heading-serif text-primary-600 mr-2">Web Development</h3>
                        <span class="px-2 py-1 bg-green-50 text-green-700 text-xs rounded-md">Completed</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"Exceptional work! The team delivered our web application ahead of schedule and exceeded all our expectations."</p>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative" data-aos="fade-up" data-aos-delay="200">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-green-100 text-green-700 rounded-full p-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                
                <div class="absolute -top-2 left-4 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full z-10 shadow-md">
                    Brand Success
                </div>

                <div class="glass-dark rounded-3xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-lg transition-all duration-500 hover:-translate-y-2">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 organic-shape bg-gradient-to-br from-accent-600/30 to-accent-500/30 backdrop-blur-md flex items-center justify-center border border-white/10 shadow-glow">
                            <span class="text-lg heading-serif gradient-text">KP</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <svg class="w-8 h-8 text-accent-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                        </div>
                    </div>

                    <div class="flex items-center mb-3">
                        <h3 class="text-lg heading-serif text-accent-600 mr-2">Brand Research</h3>
                        <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded-md">Award-winning</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"Their brand research helped our company stand out from competitors. Our new brand identity led to a 40% increase in market recognition and won us the industry innovation award."</p>

                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative" data-aos="fade-up" data-aos-delay="300">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-green-100 text-green-700 rounded-full p-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="glass-dark rounded-3xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-lg transition-all duration-500 hover:-translate-y-2">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 organic-shape bg-gradient-to-br from-primary-600/30 to-primary-500/30 backdrop-blur-md flex items-center justify-center border border-white/10 shadow-glow">
                            <span class="text-lg heading-serif gradient-text">JA</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <svg class="w-8 h-8 text-primary-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <h3 class="text-lg heading-serif text-primary-600 mr-2">Web Development</h3>
                        <span class="px-2 py-1 bg-violet-50 text-violet-700 text-xs rounded-md">Project Success</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"Their technological approach streamlined our entire project workflow. Their solutions improved our team's productivity by 80% and helped us deliver exceptional results ahead of schedule."</p>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative" data-aos="fade-up" data-aos-delay="400">
                
                <div class="absolute -top-3 -right-3 bg-white rounded-full shadow-md z-10 p-1.5">
                    <div class="bg-green-100 text-green-700 rounded-full p-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="glass-dark rounded-3xl p-6 h-full flex flex-col border border-neutral-100 shadow-sm hover:shadow-lg transition-all duration-500 hover:-translate-y-2">
                    <div class="mb-5 relative">
                        <div class="w-14 h-14 organic-shape bg-gradient-to-br from-accent-600/30 to-accent-500/30 backdrop-blur-md flex items-center justify-center border border-white/10 shadow-glow">
                            <span class="text-lg heading-serif gradient-text">VE</span>
                        </div>
                        <div class="absolute -top-2 -right-1">
                            <svg class="w-8 h-8 text-accent-300" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-center mb-3">
                        <h3 class="text-lg heading-serif text-accent-600 mr-2">UI Design</h3>
                        <span class="px-2 py-1 bg-yellow-50 text-yellow-700 text-xs rounded-md">Brand Success</span>
                    </div>

                    <p class="text-sm text-neutral-600 italic flex-grow">"The UI brand design absolutely exceeded our expectations! The visual identity perfectly captures our brand values and has significantly improved our customer engagement metrics."</p>
                    <div class="flex items-center justify-between mt-4">
                        <div class="flex space-x-1">
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="py-32 relative">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Why Choose Us</span>
            <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">Why <span class="gradient-text">Treis Adiutor</span>?</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto">The perfect partner for your digital transformation and technology journey</p>
            <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
            <div class="glass-dark rounded-3xl p-8 border border-neutral-200 hover:border-neutral-300 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-16 h-16 organic-shape bg-primary-500 mb-8 flex items-center justify-center shadow-glow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-4 group-hover:text-primary-600 transition-colors duration-300">Personalized Partnership</h3>
                <p class="text-neutral-600 mb-6">You're not just a task on a board. We assign a dedicated adiutor to your project, ensuring personalized attention from start to finish.</p>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-primary-100 mr-3 flex items-center justify-center">
                            <svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        One-on-one guidance
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-primary-100 mr-3 flex items-center justify-center">
                            <svg class="w-3 h-3 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        Custom approach for each project
                    </li>
                </ul>
            </div>

            <div class="glass-dark rounded-3xl p-8 border border-neutral-200 hover:border-neutral-300 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-16 h-16 organic-shape bg-accent-500 mb-8 flex items-center justify-center shadow-glow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-4 group-hover:text-accent-600 transition-colors duration-300">Advanced Security</h3>
                <p class="text-neutral-600 mb-6">Your work, your data. It’s safe with us. Always. We follow enterprise-grade security standards and strict confidentiality practices.</p>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-accent-100 mr-3 flex items-center justify-center">
                            <svg class="w-3 h-3 text-accent-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        End-to-end encryption
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-accent-100 mr-3 flex items-center justify-center">
                            <svg class="w-3 h-3 text-accent-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        Strict confidentiality
                    </li>
                </ul>
            </div>

            <div class="glass-dark rounded-3xl p-8 border border-neutral-200 hover:border-neutral-300 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-16 h-16 organic-shape bg-emerald-500 mb-8 flex items-center justify-center shadow-glow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-4 group-hover:text-emerald-600 transition-colors duration-300">On-Time Delivery</h3>
                <p class="text-neutral-600 mb-6">Late delivery? Not in our vocabulary. Whether it’s due next week or tomorrow, we make it happen without cutting corners.</p>
                <ul class="space-y-3 text-sm text-neutral-600">
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 mr-3 flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        Rush service available
                    </li>
                    <li class="flex items-center">
                        <div class="w-5 h-5 rounded-full bg-emerald-100 mr-3 flex items-center justify-center">
                            <svg class="w-3 h-3 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        High quality, every time
                    </li>
                </ul>
            </div>
        </div>

        <div class="relative mt-16 pt-16" data-aos="fade-up">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-500/10 to-accent-500/10 rounded-3xl transform -rotate-1"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-accent-500/10 to-primary-500/10 rounded-3xl transform rotate-1 opacity-70"></div>
            <div class="relative glass-dark rounded-3xl p-8 md:p-12 border border-white/10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-primary-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">Innovative Solutions</h3>
                        <p class="text-neutral-600">Creative minds. Smart strategies. We tackle your business and tech challenges with innovative ideas that drive results.</p>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-accent-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-accent-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">Proven Results</h3>
                        <p class="text-neutral-600">Hundreds of individuals and startups trust us because we deliver, every single time.</p>
                    </div>

                    <div class="flex flex-col items-center text-center">
                        <div class="w-16 h-16 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold heading-serif text-neutral-800 mb-2">24/7 Support</h3>
                        <p class="text-neutral-600">Late-night questions? Deadline stress? We’re here—day or night—to help you out.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <section class="py-32 relative">
        <div class="absolute inset-0 bg-white/5"></div>
        
        <div class="max-w-7xl mx-auto px-6 relative">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Our Technologies</span>
                <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">Built With <span class="gradient-text">Modern Tech</span></h2>
                <p class="text-neutral-600 max-w-2xl mx-auto">We use today’s most powerful tools and frameworks to create solutions that are fast, scalable, and built to last.</p>
                <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
            </div>
            
            <div id="tech-stack-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 relative">                
                <div class="glass-dark rounded-3xl p-8 border border-white/10 animate-pulse" data-aos="fade-up" data-aos-delay="100">
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
                
                <div class="glass-dark rounded-3xl p-8 border border-white/10 animate-pulse" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 organic-shape bg-gradient-to-br from-accent-700/20 to-accent-500/20 backdrop-blur-md flex items-center justify-center">
                            <div class="w-6 h-6 bg-accent-400/30 rounded"></div>
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
                
                <div class="glass-dark rounded-3xl p-8 border border-white/10 animate-pulse" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 organic-shape bg-gradient-to-br from-emerald-700/20 to-emerald-500/20 backdrop-blur-md flex items-center justify-center">
                            <div class="w-6 h-6 bg-emerald-400/30 rounded"></div>
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

    <section class="py-24 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Frequently Asked Questions</span>
                <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">Got <span class="gradient-text">Questions</span>?</h2>
                <p class="text-neutral-600 max-w-2xl mx-auto mt-6">
                    We've answered common questions from tech startups and businesses
                </p>
                <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="glass-dark rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-primary-400" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold heading-serif text-neutral-800">How quickly can you deliver?</h3>
                    </div>
                    <p class="text-neutral-600">Most projects are done within <span class="font-medium text-primary-700">1 week</span>, sometimes even faster. Got a tight deadline? We offer <span class="font-medium text-primary-700">expedited services</span> to meet tight deadlines without compromising quality. Our team works around the clock to ensure on-time delivery.</p>
                </div>

                <div class="glass-dark rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-green-400" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold heading-serif text-neutral-800">Is my information kept confidential?</h3>
                    </div>
                    <p class="text-neutral-600">Absolutely. We maintain <span class="font-medium text-green-700">strict confidentiality</span> for all client projects. Your personal details are protected by enterprise-grade encryption. We don’t share, resell, or reuse anything—ever.</p>
                </div>

                <div class="glass-dark rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-amber-400" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold heading-serif text-neutral-800">What if I'm not happy with the work?</h3>
                    </div>
                    <p class="text-neutral-600">No stress. We offer <span class="font-medium text-amber-700">multiple revisions</span> until you're satisfied. Still not working out? We may issue a <span class="font-medium text-amber-700">partial refund</span> depending on the situation. With over 500+ successful projects and glowing reviews, chances are—you’ll love the result.</p>
                </div>

                <div class="glass-dark rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-blue-400" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold heading-serif text-neutral-800">What payment methods do you accept?</h3>
                    </div>
                    <p class="text-neutral-600">We offer <span class="font-medium text-blue-700">flexible and secure payment options</span> including all major e-wallets like Gcash, Maya, GoTyme, PayPal, bank transfers, and cryptocurrencies. Clients pay 50% upfront with the remainder due upon completion.</p>
                </div>
                
                <div class="glass-dark rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-indigo-400" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold heading-serif text-neutral-800">Is your work original and unique?</h3>
                    </div>
                    <p class="text-neutral-600"><span class="font-medium text-indigo-700">We stand by our work</span>. All deliverables are original, custom-built for your business needs, and free from plagiarism. We take intellectual property rights seriously.</p>
                </div>
                
                <div class="glass-dark rounded-2xl p-6 transition-all duration-300 hover:shadow-md border-l-4 border-rose-400" data-aos="fade-up" data-aos-delay="600">
                    <div class="flex items-center mb-3">
                        <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold heading-serif text-neutral-800">How do I get started?</h3>
                    </div>
                    <p class="text-neutral-600">Click the "Start Your Project" button, fill out our brief project form, and you'll receive a custom quote within 2 hours. Once approved, we'll begin work immediately. It’s <span class="font-medium text-rose-700">fast, simple, and stress-free.</span></p>
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
    
    <section class="py-32 relative">
        
        <div class="max-w-7xl mx-auto px-6">
            <div class="rounded-3xl overflow-hidden relative">
                <div class="absolute inset-0 bg-gradient-to-r from-primary-600/20 to-accent-600/20 backdrop-blur-xl"></div>
                <div class="absolute inset-0 bg-primary-600/10 
                    bg-[radial-gradient(#3b82f620_1px,transparent_1px)] [background-size:20px_20px]"></div>
                
                <div class="absolute top-0 left-0 w-40 h-40 bg-primary-300/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-40 h-40 bg-accent-300/20 rounded-full blur-3xl"></div>
                
                <div class="relative p-12 md:p-20 z-10">
                    <div class="max-w-4xl mx-auto">
                        <div class="text-center">
                            <span class="inline-block px-5 py-1.5 rounded-full bg-white/90 text-primary-600 text-sm font-bold mb-6 shadow-md border border-primary-100 animate-pulse">
                                <span class="inline-block mr-2 bg-red-100 text-red-600 px-2 py-0.5 rounded-md text-xs font-bold">LIMITED SLOTS</span>
                                Start your project today before this week’s queue fills up!
                            </span>
                            <h2 class="heading-serif text-4xl md:text-5xl mb-6">Ready to <span class="gradient-text">accelerate your growth</span> with technology?</h2>
                            <p class="text-lg text-neutral-700 mb-5 max-w-2xl mx-auto">From MVP launches to enterprise solutions, we've got your back with technology that scales with your business.</p>
                            
                            <div class="flex justify-center items-center mb-6">
                                <span class="text-sm bg-green-50 text-green-700 px-3 py-2 rounded-lg flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span>Join over 500+ tech startups and businesses who trust us</span>
                                </span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-xl text-center">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-primary-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-neutral-800 mb-1">Fast Turnaround</h3>
                                <p class="text-xs text-neutral-600">Most projects delivered within 48-72 hours</p>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-xl text-center">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-primary-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-neutral-800 mb-1">Client Satisfaction Priority</h3>
                                <p class="text-xs text-neutral-600">Money-back guarantee if you're not satisfied</p>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm p-4 rounded-xl text-center">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-primary-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-sm font-semibold text-neutral-800 mb-1">Complete Confidentiality</h3>
                                <p class="text-xs text-neutral-600">Your project details remain private</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col md:flex-row gap-6 justify-center">
                            <a href="/contact" class="inline-flex items-center justify-center px-8 py-5 bg-gradient-to-r from-primary-500 to-accent-500 text-white rounded-full hover:shadow-glow transition-all duration-300 font-bold group relative overflow-hidden">
                                
                                <span class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 group-hover:scale-[2.5] rounded-full transition-all duration-1000 origin-center"></span>
                                
                                <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shine"></span>
                                
                                <svg class="w-5 h-5 mr-2 relative" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                                <span class="relative text">Start Your Project Now</span>
                                <svg class="w-5 h-5 ml-2 relative transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            <a href="/services" class="btn-secondary inline-flex items-center justify-center px-8 py-5 group bg-white border border-primary-200 hover:border-primary-300 rounded-full shadow-sm hover:shadow-md transition-all duration-300">
                                <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span>See Pricing & Services</span>
                                <svg class="w-5 h-5 ml-2 opacity-70 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                        
                        <div class="flex justify-center mt-6">
                            <span class="text-xs text-neutral-500 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Secure payment • No obligation quotes • On-time delivery
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    AOS.init({
        duration: 1000,
        once: true
    });

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
            techStackContainer.innerHTML = ''; 
            
            // Map category keys to color schemes
            const categoryColorMap = {
                'Programming Languages': { from: 'blue-700/30', to: 'blue-500/30', text: 'blue-300', hover: 'blue-200', bg: 'blue-900/60', accent: 'blue-400' },
                'Frontend': { from: 'primary-700/30', to: 'primary-500/30', text: 'primary-300', hover: 'primary-200', bg: 'primary-900/60', accent: 'primary-400' },
                'Backend': { from: 'accent-700/30', to: 'accent-500/30', text: 'accent-300', hover: 'accent-200', bg: 'accent-900/60', accent: 'accent-400' },
                'Databases & Database Management': { from: 'amber-700/30', to: 'amber-500/30', text: 'amber-300', hover: 'amber-200', bg: 'amber-900/60', accent: 'amber-400' },
                'Version Control & Collaboration': { from: 'emerald-700/30', to: 'emerald-500/30', text: 'emerald-300', hover: 'emerald-200', bg: 'emerald-900/60', accent: 'emerald-400' },
                'DevOps & Cloud': { from: 'purple-700/30', to: 'purple-500/30', text: 'purple-300', hover: 'purple-200', bg: 'purple-900/60', accent: 'purple-400' },
                'Testing & CI/CD': { from: 'cyan-700/30', to: 'cyan-500/30', text: 'cyan-300', hover: 'cyan-200', bg: 'cyan-900/60', accent: 'cyan-400' },
                'Productivity Tools': { from: 'indigo-700/30', to: 'indigo-500/30', text: 'indigo-300', hover: 'indigo-200', bg: 'indigo-900/60', accent: 'indigo-400' },
                'Design & Multimedia Tools': { from: 'rose-700/30', to: 'rose-500/30', text: 'rose-300', hover: 'rose-200', bg: 'rose-900/60', accent: 'rose-400' }
            };
            
            const defaultColor = { from: 'gray-700/30', to: 'gray-500/30', text: 'gray-300', hover: 'gray-200', bg: 'gray-900/60', accent: 'gray-400' };

            let delay = 100;
            for (const categoryKey in techData) {
                if (techData.hasOwnProperty(categoryKey)) {
                    const categoryData = techData[categoryKey];
                    const categoryDiv = document.createElement('div');
                    categoryDiv.classList.add('glass-dark', 'rounded-3xl', 'p-8', 'border', 'border-white/10', 'hover:border-white/20', 'transition-all', 'duration-300');
                    categoryDiv.setAttribute('data-aos', 'fade-up');
                    categoryDiv.setAttribute('data-aos-delay', delay.toString());
                    delay += 100;
                    
                    const categoryName = categoryData.name;
                    const colorSet = categoryColorMap[categoryName] || defaultColor;

                    categoryDiv.innerHTML = `
                        <div class="flex items-center mb-8">
                            <h3 class="text-xl font-semibold heading-serif text-neutral-800 ml-4 group-hover:text-${colorSet.text} transition-colors duration-300">${categoryName}</h3>
                        </div>
                        <div class="grid grid-cols-3 gap-4"></div>
                    `;

                    const techItemsContainer = categoryDiv.querySelector('.grid');

                    categoryData.technologies.forEach(tech => {
                        const techItemDiv = document.createElement('div');
                        techItemDiv.classList.add('tech-item', 'p-3', 'bg-primary-50/50', 'rounded-xl', 'text-center', 'backdrop-blur-sm', 'border', 'border-neutral-100', 'hover:bg-primary-100/50', 'hover:border-neutral-200', 'transition-all', 'duration-300');
                        
                        // Create image with error handling for Brandfetch API
                        const img = document.createElement('img');
                        img.src = tech.image;
                        img.alt = tech.name;
                        img.className = 'w-8 h-8 mx-auto mb-2 object-contain';
                        img.loading = 'lazy'; // Lazy load for better performance
                        
                        // Add error handling in case Brandfetch image fails to load
                        img.onerror = function() {
                            // If Brandfetch fails, this will already have the fallback lettermark
                            // But we can add additional styling to indicate it's a fallback
                            console.warn(`Failed to load logo for ${tech.name}`);
                        };
                        
                        const span = document.createElement('span');
                        span.className = 'text-sm text-neutral-600 font-medium';
                        span.textContent = tech.name;
                        
                        techItemDiv.appendChild(img);
                        techItemDiv.appendChild(span);
                        techItemsContainer.appendChild(techItemDiv); 
                    });
                    techStackContainer.appendChild(categoryDiv); 
                }
            }
        }

        fetchTechStack();

        async function fetchFeaturedProjects() {
            const container = document.getElementById('marquee-content');
            if (!container) return;

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

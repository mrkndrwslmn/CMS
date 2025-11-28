@extends('layouts.public')

@section('title', 'Referral Program - Earn Rewards')
@section('description', 'Join our referral program and earn amazing rewards! Refer friends and get exclusive benefits, discounts, and points for every successful referral.')

@section('content')
<!-- Hero Section -->
<section class="min-h-screen relative flex items-center pt-24 pb-16 section-padding overflow-hidden">
    <div class="relative z-10 max-w-7xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
                <div class="flex items-center mb-4">
                    <span class="px-3 py-1 rounded-full bg-accent-100 text-accent-700 text-sm font-semibold inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                        <span>Limited Time Offer</span>
                    </span>
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl heading-serif mb-6 leading-tight">
                    Share the <span class="gradient-text">Love</span>, Earn <span class="gradient-text">Rewards!</span>
                </h1>
                
                <p class="text-lg md:text-xl text-neutral-600 mb-6 leading-relaxed">
                    Refer friends to Treis Adiutor and earn <strong class="text-accent-600">{{ config('referral.rewards.referrer.completion_points', 1000) }} points</strong> 
                    plus exclusive discounts for every successful referral!
                </p>
                
                <div class="flex flex-col mb-10 bg-primary-50 px-5 py-4 rounded-xl border border-primary-100">
                    <div class="flex items-center mb-2">
                        <div class="mr-3 text-primary-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-neutral-800">Unlimited referrals</p>
                    </div>
                    <div class="flex items-center">
                        <div class="mr-3 text-primary-500">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-neutral-700">Both you and your friends get rewarded</p>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-6">
                    @auth
                        <a href="{{ route('client.referrals.dashboard') }}" class="btn-primary inline-flex items-center justify-center group py-5 px-8 text-base relative shine-effect">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shine"></span>
                            <span>Go to My Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-primary inline-flex items-center justify-center group py-5 px-8 text-base relative shine-effect">
                            <span class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shine"></span>
                            <span>Join & Start Earning</span>
                        </a>
                    @endauth
                    <a href="#how-it-works" class="border-2 border-primary-500 text-primary-500 rounded-xl inline-flex items-center justify-center py-5 px-8 text-base group">
                        <span>Learn More</span>
                    </a>
                </div>
            </div>
            
            <div class="relative hidden md:block">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-500/20 via-accent-500/20 to-primary-500/20 rounded-3xl opacity-60 blur-3xl transform -rotate-6"></div>
                
                <div class="absolute top-12 -left-10 w-20 h-20 bg-primary-200/30 rounded-full blur-xl"></div>
                <div class="absolute bottom-12 -right-10 w-24 h-24 bg-accent-200/30 rounded-full blur-xl"></div>
                
                <div class="relative z-10 p-8 rounded-3xl glass-dark border border-neutral-100">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 border border-primary-200">
                            <div class="text-4xl font-bold text-primary-600 mb-2">{{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }}</div>
                            <div class="text-sm text-neutral-600 font-medium">Points per Referral</div>
                        </div>
                        <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-accent-50 to-accent-100 border border-accent-200">
                            <div class="text-4xl font-bold text-accent-600 mb-2">{{ config('referral.rewards.referrer.coupon_discount', 15) }}%</div>
                            <div class="text-sm text-neutral-600 font-medium">Discount Coupon</div>
                        </div>
                        <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-green-50 to-green-100 border border-green-200">
                            <div class="text-4xl font-bold text-green-600 mb-2">{{ config('referral.rewards.referred.signup_points', 500) }}</div>
                            <div class="text-sm text-neutral-600 font-medium">Welcome Bonus</div>
                        </div>
                        <div class="text-center p-6 rounded-2xl bg-gradient-to-br from-purple-100 to-purple-500 border border-purple-200">
                            <div class="text-4xl font-bold text-purple-600 mb-2">∞</div>
                            <div class="text-sm text-neutral-600 font-medium">Unlimited Referrals</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-24 bg-neutral-50/50 relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-grid-pattern opacity-50"></div>
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center" data-aos="fade-up">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4 transform transition-transform duration-500 hover:scale-110 hover:rotate-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-primary-600 mb-2 heading-serif">{{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }}</div>
                <div class="text-neutral-600 font-medium">Points per Referral</div>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-gradient-to-br from-accent-500 to-accent-600 rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4 transform transition-transform duration-500 hover:scale-110 hover:rotate-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-accent-600 mb-2 heading-serif">{{ config('referral.rewards.referrer.coupon_discount', 15) }}%</div>
                <div class="text-neutral-600 font-medium">Discount Coupon</div>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4 transform transition-transform duration-500 hover:scale-110 hover:rotate-3">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-green-600 mb-2 heading-serif">{{ config('referral.rewards.referred.signup_points', 500) }}</div>
                <div class="text-neutral-600 font-medium">Welcome Bonus</div>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg flex items-center justify-center mx-auto mb-4 transform transition-transform duration-500 hover:scale-110 hover:rotate-3">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="text-4xl font-bold text-purple-600 mb-2 heading-serif">∞</div>
                <div class="text-neutral-600 font-medium">Unlimited Referrals</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-24 relative">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">How It Works</span>
            <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">Start Earning in <span class="gradient-text">3 Simple Steps</span></h2>
            <p class="text-neutral-600 max-w-2xl mx-auto mt-6">
                Getting started is easy. Follow these three steps and start earning rewards today.
            </p>
            <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="group relative" data-aos="fade-up" data-aos-delay="100">
                <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden">
                    <div class="h-2 w-full bg-gradient-to-r from-primary-500 to-violet-500"></div>
                    
                    <div class="p-8">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-violet-500 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <h3 class="text-xl font-semibold heading-serif text-neutral-800 group-hover:text-primary-600 transition-colors duration-300">Step 1: Sign Up</h3>
                            <div class="ml-auto">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold bg-gradient-to-r from-primary-500 to-violet-500 text-white shadow-sm">
                                    1
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-neutral-600 mb-4">Create your free account and get your unique referral code instantly. It takes less than a minute!</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center p-2 rounded-lg hover:bg-primary-50 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Quick registration</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg hover:bg-primary-50 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Instant code generation</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="group relative" data-aos="fade-up" data-aos-delay="200">
                <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden">
                    <div class="h-2 w-full bg-gradient-to-r from-accent-500 to-orange-500"></div>
                    
                    <div class="p-8">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-accent-500 to-orange-500 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <h3 class="text-xl font-semibold heading-serif text-neutral-800 group-hover:text-accent-600 transition-colors duration-300">Step 2: Share</h3>
                            <div class="ml-auto">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold bg-gradient-to-r from-accent-500 to-orange-500 text-white shadow-sm">
                                    2
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-neutral-600 mb-4">Share your code with friends via email, social media, or direct link. Make it easy for them!</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center p-2 rounded-lg hover:bg-accent-50 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-accent-100 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Multiple sharing options</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg hover:bg-accent-50 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-accent-100 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Track your invitations</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="group relative" data-aos="fade-up" data-aos-delay="300">
                <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden">
                    <div class="h-2 w-full bg-gradient-to-r from-green-500 to-emerald-500"></div>
                    
                    <div class="p-8">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl shadow-lg flex items-center justify-center transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="flex items-center mb-4">
                            <h3 class="text-xl font-semibold heading-serif text-neutral-800 group-hover:text-green-600 transition-colors duration-300">Step 3: Earn</h3>
                            <div class="ml-auto">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full text-sm font-bold bg-gradient-to-r from-green-500 to-emerald-500 text-white shadow-sm">
                                    3
                                </span>
                            </div>
                        </div>
                        
                        <p class="text-neutral-600 mb-4">Get rewarded when your friend signs up and makes their first purchase. Both of you win!</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center p-2 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Automatic rewards</span>
                            </div>
                            <div class="flex items-center p-2 rounded-lg hover:bg-green-50 transition-colors duration-200">
                                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center mr-3 shadow-sm">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-neutral-700">Both parties benefit</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rewards Details Section -->
<section class="py-24 bg-neutral-50/50 relative overflow-hidden">
    <div class="absolute inset-0 -z-10 bg-grid-pattern opacity-50"></div>
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Rewards</span>
            <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">Generous Rewards for <span class="gradient-text">Everyone</span></h2>
            <p class="text-neutral-600 max-w-2xl mx-auto mt-6">
                Both you and your friends get amazing rewards when they join
            </p>
            <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- For You -->
            <div class="group relative" data-aos="fade-up" data-aos-delay="100">
                <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden">
                    <div class="h-2 w-full bg-gradient-to-r from-primary-500 to-violet-500"></div>
                    
                    <div class="p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-violet-500 rounded-2xl shadow-lg flex items-center justify-center mr-4 transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-semibold heading-serif text-neutral-800 group-hover:text-primary-600 transition-colors duration-300">For You (Referrer)</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-start p-3 rounded-xl bg-primary-50 border border-primary-100 transition-all duration-200 hover:bg-primary-100">
                                <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-800 text-lg">{{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }} Loyalty Points</div>
                                    <div class="text-neutral-600 text-sm">Instantly credited when your friend completes payment</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start p-3 rounded-xl bg-primary-50 border border-primary-100 transition-all duration-200 hover:bg-primary-100">
                                <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-800 text-lg">{{ config('referral.rewards.referrer.coupon_discount', 15) }}% Discount Coupon</div>
                                    <div class="text-neutral-600 text-sm">Valid for {{ config('referral.rewards.referrer.coupon_validity_days', 30) }} days on any service</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start p-3 rounded-xl bg-primary-50 border border-primary-100 transition-all duration-200 hover:bg-primary-100">
                                <div class="w-8 h-8 rounded-lg bg-primary-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-800 text-lg">Unlimited Referrals</div>
                                    <div class="text-neutral-600 text-sm">No cap on how many friends you can refer!</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- For Your Friend -->
            <div class="group relative" data-aos="fade-up" data-aos-delay="200">
                <div class="relative glass-dark rounded-3xl h-full border border-neutral-100 shadow-sm transition-all duration-500 hover:shadow-xl hover:-translate-y-2 overflow-hidden">
                    <div class="h-2 w-full bg-gradient-to-r from-green-500 to-emerald-500"></div>
                    
                    <div class="p-8">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl shadow-lg flex items-center justify-center mr-4 transform transition-transform duration-500 group-hover:scale-110 group-hover:rotate-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-semibold heading-serif text-neutral-800 group-hover:text-green-600 transition-colors duration-300">For Your Friend</h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-start p-3 rounded-xl bg-green-50 border border-green-100 transition-all duration-200 hover:bg-green-100">
                                <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-800 text-lg">{{ number_format(config('referral.rewards.referred.signup_points', 500)) }} Welcome Points</div>
                                    <div class="text-neutral-600 text-sm">Bonus points just for signing up with your code</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start p-3 rounded-xl bg-green-50 border border-green-100 transition-all duration-200 hover:bg-green-100">
                                <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-800 text-lg">{{ config('referral.rewards.referred.coupon_discount', 10) }}% First Purchase Discount</div>
                                    <div class="text-neutral-600 text-sm">Instant discount coupon for their first order</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start p-3 rounded-xl bg-green-50 border border-green-100 transition-all duration-200 hover:bg-green-100">
                                <div class="w-8 h-8 rounded-lg bg-green-500 flex items-center justify-center mr-3 flex-shrink-0 shadow-sm">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="font-semibold text-neutral-800 text-lg">Access to Loyalty Program</div>
                                    <div class="text-neutral-600 text-sm">Start earning points on every purchase</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-24 relative">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-sm uppercase tracking-wider font-medium text-primary-600 mb-3 inline-block py-1 px-3 rounded-full bg-primary-100 backdrop-blur-md">Frequently Asked Questions</span>
            <h2 class="heading-serif text-3xl md:text-4xl mb-4 mt-4">Got <span class="gradient-text">Questions</span>?</h2>
            <p class="text-neutral-600 max-w-2xl mx-auto mt-6">
                Find answers to common questions about our referral program
            </p>
            <div class="w-20 h-1 bg-gradient-to-r from-primary-500 to-accent-500 mx-auto mt-6 rounded-full"></div>
        </div>
        
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="glass-dark rounded-2xl transition-all duration-300 hover:shadow-md border-l-4 border-primary-400 overflow-hidden" data-aos="fade-up">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-neutral-800 text-lg">How do I get my referral code?</span>
                    <svg class="w-5 h-5 text-primary-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-neutral-600">Simply sign up for a free account at Treis Adiutor. Your unique referral code will be automatically generated and available on your dashboard. You can start sharing it immediately!</p>
                </div>
            </div>
            
            <div class="glass-dark rounded-2xl transition-all duration-300 hover:shadow-md border-l-4 border-accent-400 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-neutral-800 text-lg">When do I receive my rewards?</span>
                    <svg class="w-5 h-5 text-accent-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-neutral-600">Your rewards are credited automatically when your referred friend completes their first payment. Loyalty points appear in your account within 24 hours, and discount coupons are sent to your email immediately.</p>
                </div>
            </div>
            
            <div class="glass-dark rounded-2xl transition-all duration-300 hover:shadow-md border-l-4 border-green-400 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-neutral-800 text-lg">Is there a limit to how many people I can refer?</span>
                    <svg class="w-5 h-5 text-green-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-neutral-600">No! You can refer as many friends as you want. There's no cap on referrals or rewards. The more you share, the more you earn!</p>
                </div>
            </div>
            
            <div class="glass-dark rounded-2xl transition-all duration-300 hover:shadow-md border-l-4 border-purple-400 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-neutral-800 text-lg">Can I use my loyalty points for discounts?</span>
                    <svg class="w-5 h-5 text-purple-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-neutral-600">Absolutely! You can redeem your loyalty points for discounts on any of our services. Check your loyalty dashboard to see your current balance and redemption options.</p>
                </div>
            </div>
            
            <div class="glass-dark rounded-2xl transition-all duration-300 hover:shadow-md border-l-4 border-blue-400 overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-neutral-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-neutral-800 text-lg">Do coupons expire?</span>
                    <svg class="w-5 h-5 text-blue-600 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-neutral-600">Yes, discount coupons are valid for {{ config('referral.rewards.referrer.coupon_validity_days', 30) }} days from the date they're issued. Make sure to use them before they expire! You can check expiry dates in your coupons section.</p>
                </div>
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
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-accent-400 to-accent-600 border-2 border-white"></div>
                        <div class="w-7 h-7 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 border-2 border-white"></div>
                    </div>
                    <span class="text-sm font-medium text-neutral-700">Join our growing community</span>
                </div>

                <h2 class="heading-serif text-4xl md:text-5xl lg:text-6xl mb-6 text-neutral-900">
                    Ready to start <br class="hidden md:block"/>
                    <span class="gradient-text">earning rewards?</span>
                </h2>

                <p class="text-lg md:text-xl text-neutral-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                    Join thousands of users who are already earning rewards by sharing Treis Adiutor with their friends!
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center mb-10">
                    @auth
                        <a href="{{ route('client.referrals.dashboard') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-xl font-semibold shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/30 transition-all duration-300 hover:-translate-y-0.5">
                            <span class="relative">View My Dashboard</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="group relative inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-primary-600 to-primary-500 text-white rounded-xl font-semibold shadow-lg shadow-primary-500/25 hover:shadow-xl hover:shadow-primary-500/30 transition-all duration-300 hover:-translate-y-0.5">
                            <span class="relative">Get Started Now</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="group inline-flex items-center justify-center px-8 py-4 bg-white text-neutral-700 rounded-xl font-semibold border-2 border-neutral-200 hover:border-neutral-300 shadow-sm hover:shadow-md transition-all duration-300">
                            <span>Already Have an Account?</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endauth
                </div>

                <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-neutral-500">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Unlimited referrals</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Instant rewards</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-medium">Easy to share</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
                <div class="bg-white rounded-2xl p-6 border border-neutral-200 hover:border-neutral-300 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-2">Earn Points</h3>
                    <p class="text-sm text-neutral-600">Get {{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }} loyalty points for each successful referral</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-neutral-200 hover:border-neutral-300 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-accent-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-2">Get Discounts</h3>
                    <p class="text-sm text-neutral-600">Receive {{ config('referral.rewards.referrer.coupon_discount', 15) }}% discount coupons for your next purchase</p>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-neutral-200 hover:border-neutral-300 hover:shadow-lg transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-neutral-900 mb-2">Help Friends</h3>
                    <p class="text-sm text-neutral-600">Your friends get {{ config('referral.rewards.referred.signup_points', 500) }} welcome bonus points</p>
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
    
    function toggleFAQ(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('svg');
        
        // Close all other FAQs
        document.querySelectorAll('.px-6.pb-4').forEach(el => {
            if (el !== content && !el.classList.contains('hidden')) {
                el.classList.add('hidden');
                const otherIcon = el.previousElementSibling.querySelector('svg');
                if (otherIcon) {
                    otherIcon.style.transform = 'rotate(0deg)';
                }
            }
        });
        
        // Toggle current FAQ
        content.classList.toggle('hidden');
        if (icon) {
            icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        }
    }
</script>
@endpush

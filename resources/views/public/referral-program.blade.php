@extends('layouts.public')

@section('title', 'Referral Program - Earn Rewards')
@section('description', 'Join our referral program and earn amazing rewards! Refer friends and get exclusive benefits, discounts, and points for every successful referral.')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 py-20 overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center text-white">
            <div class="inline-flex items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full mb-6 border border-white/20">
                <i class="fas fa-gift mr-2 text-accent"></i>
                <span class="text-sm font-medium">Limited Time Offer</span>
            </div>
            
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight" data-aos="fade-up">
                Share the Love,<br>
                <span class="text-accent">Earn Rewards!</span>
            </h1>
            
            <p class="text-xl md:text-2xl mb-8 text-white/90" data-aos="fade-up" data-aos-delay="100">
                Refer friends to Treis Adiutor and earn <strong class="text-accent">{{ config('referral.rewards.referrer.completion_points', 1000) }} points</strong> 
                plus exclusive discounts for every successful referral!
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
                @auth
                    <a href="{{ route('client.referrals.dashboard') }}" class="px-8 py-4 bg-accent text-white rounded-full hover:bg-accent-dark transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl hover:scale-105 inline-flex items-center justify-center">
                        <i class="fas fa-rocket mr-2"></i>
                        Go to My Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-accent text-white rounded-full hover:bg-accent-dark transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl hover:scale-105 inline-flex items-center justify-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Join & Start Earning
                    </a>
                @endauth
                <a href="#how-it-works" class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-full hover:bg-white/20 transition-all duration-300 font-semibold text-lg border-2 border-white/30 hover:border-white/50 inline-flex items-center justify-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Learn More
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-16 bg-white border-b">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
            <div class="text-center" data-aos="fade-up">
                <div class="w-20 h-20 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-3xl text-white"></i>
                </div>
                <div class="text-4xl font-bold text-primary-600 mb-2">{{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }}</div>
                <div class="text-gray-600 font-medium">Points per Referral</div>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-ticket text-3xl text-white"></i>
                </div>
                <div class="text-4xl font-bold text-green-600 mb-2">{{ config('referral.rewards.referrer.coupon_discount', 15) }}%</div>
                <div class="text-gray-600 font-medium">Discount Coupon</div>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gift text-3xl text-white"></i>
                </div>
                <div class="text-4xl font-bold text-purple-600 mb-2">{{ config('referral.rewards.referred.signup_points', 500) }}</div>
                <div class="text-gray-600 font-medium">Welcome Bonus</div>
            </div>
            
            <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                <div class="w-20 h-20 bg-gradient-to-br from-accent to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-infinity text-3xl text-white"></i>
                </div>
                <div class="text-4xl font-bold text-accent mb-2">∞</div>
                <div class="text-gray-600 font-medium">Unlimited Referrals</div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="how-it-works" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4" data-aos="fade-up">
                How It <span class="text-accent">Works</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Start earning rewards in just 3 simple steps
            </p>
        </div>
        
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="relative" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                        <div class="absolute -top-6 left-8 w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            1
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-100 to-primary-200 rounded-full flex items-center justify-center mx-auto mb-6 mt-4">
                            <i class="fas fa-user-plus text-3xl text-primary-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 text-center">Sign Up</h3>
                        <p class="text-gray-600 text-center leading-relaxed">
                            Create your free account and get your unique referral code instantly. It takes less than a minute!
                        </p>
                    </div>
                    <!-- Arrow for desktop -->
                    <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                        <i class="fas fa-arrow-right text-3xl text-primary-300"></i>
                    </div>
                </div>
                
                <!-- Step 2 -->
                <div class="relative" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                        <div class="absolute -top-6 left-8 w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            2
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-green-200 rounded-full flex items-center justify-center mx-auto mb-6 mt-4">
                            <i class="fas fa-share-nodes text-3xl text-green-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 text-center">Share</h3>
                        <p class="text-gray-600 text-center leading-relaxed">
                            Share your code with friends via email, social media, or direct link. Make it easy for them!
                        </p>
                    </div>
                    <!-- Arrow for desktop -->
                    <div class="hidden md:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                        <i class="fas fa-arrow-right text-3xl text-green-300"></i>
                    </div>
                </div>
                
                <!-- Step 3 -->
                <div class="relative" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border border-gray-100">
                        <div class="absolute -top-6 left-8 w-12 h-12 bg-gradient-to-br from-accent to-orange-600 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            3
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-100 to-orange-200 rounded-full flex items-center justify-center mx-auto mb-6 mt-4">
                            <i class="fas fa-trophy text-3xl text-accent"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3 text-center">Earn</h3>
                        <p class="text-gray-600 text-center leading-relaxed">
                            Get rewarded when your friend signs up and makes their first purchase. Both of you win!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rewards Details Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4" data-aos="fade-up">
                Your <span class="text-accent">Rewards</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Generous rewards for you and your friends
            </p>
        </div>
        
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- For You -->
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 rounded-2xl p-8 border-2 border-primary-200" data-aos="fade-right">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-primary-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">For You (Referrer)</h3>
                </div>
                
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">{{ number_format(config('referral.rewards.referrer.completion_points', 1000)) }} Loyalty Points</div>
                            <div class="text-gray-600">Instantly credited when your friend completes payment</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">{{ config('referral.rewards.referrer.coupon_discount', 15) }}% Discount Coupon</div>
                            <div class="text-gray-600">Valid for {{ config('referral.rewards.referrer.coupon_validity_days', 30) }} days on any service</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-primary-600 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">Unlimited Referrals</div>
                            <div class="text-gray-600">No cap on how many friends you can refer!</div>
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- For Your Friend -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border-2 border-green-200" data-aos="fade-left">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-friends text-white text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">For Your Friend</h3>
                </div>
                
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">{{ number_format(config('referral.rewards.referred.signup_points', 500)) }} Welcome Points</div>
                            <div class="text-gray-600">Bonus points just for signing up with your code</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">{{ config('referral.rewards.referred.coupon_discount', 10) }}% First Purchase Discount</div>
                            <div class="text-gray-600">Instant discount coupon for their first order</div>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center mr-3 mt-1 flex-shrink-0">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 text-lg">Access to Loyalty Program</div>
                            <div class="text-gray-600">Start earning points on every purchase</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4" data-aos="fade-up">
                Frequently Asked <span class="text-accent">Questions</span>
            </h2>
        </div>
        
        <div class="max-w-3xl mx-auto space-y-4">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-900 text-lg">How do I get my referral code?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Simply sign up for a free account at Treis Adiutor. Your unique referral code will be automatically generated and available on your dashboard. You can start sharing it immediately!</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-900 text-lg">When do I receive my rewards?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Your rewards are credited automatically when your referred friend completes their first payment. Loyalty points appear in your account within 24 hours, and discount coupons are sent to your email immediately.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-900 text-lg">Is there a limit to how many people I can refer?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-gray-600">No! You can refer as many friends as you want. There's no cap on referrals or rewards. The more you share, the more you earn!</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-900 text-lg">Can I use my loyalty points for discounts?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Absolutely! You can redeem your loyalty points for discounts on any of our services. Check your loyalty dashboard to see your current balance and redemption options.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="400">
                <button class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors" onclick="toggleFAQ(this)">
                    <span class="font-semibold text-gray-900 text-lg">Do coupons expire?</span>
                    <i class="fas fa-chevron-down text-primary-600 transition-transform"></i>
                </button>
                <div class="px-6 pb-4 hidden">
                    <p class="text-gray-600">Yes, discount coupons are valid for {{ config('referral.rewards.referrer.coupon_validity_days', 30) }} days from the date they're issued. Make sure to use them before they expire! You can check expiry dates in your coupons section.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-800 relative overflow-hidden">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-accent rounded-full blur-3xl"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h2 class="text-4xl md:text-5xl font-bold mb-6" data-aos="fade-up">
                Ready to Start Earning?
            </h2>
            <p class="text-xl mb-8 text-white/90" data-aos="fade-up" data-aos-delay="100">
                Join thousands of users who are already earning rewards by sharing Treis Adiutor with their friends!
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center" data-aos="fade-up" data-aos-delay="200">
                @auth
                    <a href="{{ route('client.referrals.dashboard') }}" class="px-8 py-4 bg-accent text-white rounded-full hover:bg-accent-dark transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl hover:scale-105 inline-flex items-center justify-center">
                        <i class="fas fa-arrow-right mr-2"></i>
                        View My Referral Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-accent text-white rounded-full hover:bg-accent-dark transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl hover:scale-105 inline-flex items-center justify-center">
                        <i class="fas fa-rocket mr-2"></i>
                        Get Started Now
                    </a>
                    <a href="{{ route('login') }}" class="px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-full hover:bg-white/20 transition-all duration-300 font-semibold text-lg border-2 border-white/30 hover:border-white/50 inline-flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Already Have an Account?
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });
    
    function toggleFAQ(button) {
        const content = button.nextElementSibling;
        const icon = button.querySelector('i');
        
        // Close all other FAQs
        document.querySelectorAll('.px-6.pb-4').forEach(el => {
            if (el !== content) {
                el.classList.add('hidden');
                el.previousElementSibling.querySelector('i').style.transform = 'rotate(0deg)';
            }
        });
        
        // Toggle current FAQ
        content.classList.toggle('hidden');
        icon.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
    }
</script>
@endpush

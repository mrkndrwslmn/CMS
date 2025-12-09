@extends('layouts.public')

@section('title', 'Client Testimonials')
@section('description', 'Read what our clients say about working with Treis Adiutor. Real stories from tech startups and businesses we have helped.')

@section('content')
<section class="min-h-screen pt-32 pb-16 section-padding">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="heading-serif text-4xl md:text-5xl mb-6">Client <span class="gradient-text">Testimonials</span></h1>
            <p class="text-neutral-600 text-lg max-w-2xl mx-auto">
                Don't just take our word for it. See what our clients have to say about their experience working with us.
            </p>
        </div>

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Sample Testimonials -->
            <div class="glass-dark rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary-200 flex items-center justify-center mr-4">
                        <span class="text-lg font-bold text-primary-700">AC</span>
                    </div>
                    <div>
                        <h3 class="font-semibold">Alex Chen</h3>
                        <p class="text-sm text-neutral-600">Tech Startup Founder</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                </div>
                <p class="text-neutral-600 italic">"Exceptional work! The team delivered our web application ahead of schedule and exceeded all our expectations."</p>
            </div>

            <div class="glass-dark rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-info-200 flex items-center justify-center mr-4">
                        <span class="text-lg font-bold text-info-700">SM</span>
                    </div>
                    <div>
                        <h3 class="font-semibold">Sarah Martinez</h3>
                        <p class="text-sm text-neutral-600">Product Manager</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                </div>
                <p class="text-neutral-600 italic">"Professional, responsive, and incredibly skilled. They transformed our vision into a beautiful, functional product."</p>
            </div>

            <div class="glass-dark rounded-2xl p-6">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary-200 flex items-center justify-center mr-4">
                        <span class="text-lg font-bold text-primary-700">MJ</span>
                    </div>
                    <div>
                        <h3 class="font-semibold">Michael Johnson</h3>
                        <p class="text-sm text-neutral-600">Business Owner</p>
                    </div>
                </div>
                <div class="flex mb-4">
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                    <i class="fas fa-star text-warning-400"></i>
                </div>
                <p class="text-neutral-600 italic">"Their technical expertise and attention to detail made all the difference. Highly recommend!"</p>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('contact') }}" class="btn-primary inline-flex items-center px-8 py-4">
                Start Your Project
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
@endsection

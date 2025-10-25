@extends('layouts.public')

@section('title', 'Contact Us')
@section('description', 'Get in touch with Treis Adiutor. We are here to help with your technology and digital transformation needs.')

@section('content')
<section class="min-h-screen pt-32 pb-16 section-padding">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center mb-16">
            <h1 class="heading-serif text-4xl md:text-5xl mb-6">Get in <span class="gradient-text">Touch</span></h1>
            <p class="text-neutral-600 text-lg max-w-2xl mx-auto">
                Have a project in mind? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <!-- Contact Information -->
            <div>
                <h2 class="text-2xl font-semibold mb-6 heading-serif">Contact Information</h2>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center mr-4">
                            <i class="fas fa-envelope text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Email</h3>
                            <a href="mailto:contact@treisadiutor.com" class="text-neutral-600 hover:text-primary-600">contact@treisadiutor.com</a>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center mr-4">
                            <i class="fas fa-phone text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Phone</h3>
                            <a href="tel:+1234567890" class="text-neutral-600 hover:text-primary-600">+1 (234) 567-890</a>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="w-12 h-12 rounded-lg bg-primary-100 flex items-center justify-center mr-4">
                            <i class="fas fa-clock text-primary-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-1">Business Hours</h3>
                            <p class="text-neutral-600">24/7 Support Available</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="font-semibold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/treisadiutor" class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <a href="https://www.twitter.com/treisadiutor" class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://www.instagram.com/treisadiutor" class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center hover:bg-primary-600 hover:text-white transition-all">
                            <i class="fab fa-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="glass-dark rounded-3xl p-8">
                <h2 class="text-2xl font-semibold mb-6 heading-serif">Send us a Message</h2>
                <form action="{{ route('get-started.submit') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">Your Name</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-3 rounded-lg border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-neutral-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-3 rounded-lg border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-neutral-700 mb-2">Subject</label>
                            <input type="text" id="subject" name="subject" required class="w-full px-4 py-3 rounded-lg border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all">
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-neutral-700 mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" required class="w-full px-4 py-3 rounded-lg border border-neutral-300 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none transition-all"></textarea>
                        </div>

                        <button type="submit" class="w-full btn-primary py-4 rounded-lg font-semibold">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

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

        <!-- Stats Section -->
        @if(isset($stats) && $stats['total_reviews'] > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="glass-dark rounded-2xl p-6 text-center">
                <div class="text-4xl font-bold gradient-text mb-2">{{ $stats['total_reviews'] }}</div>
                <p class="text-neutral-600">Total Reviews</p>
            </div>
            <div class="glass-dark rounded-2xl p-6 text-center">
                <div class="text-4xl font-bold gradient-text mb-2">{{ $stats['average_rating'] }}<span class="text-2xl">/5</span></div>
                <p class="text-neutral-600">Average Rating</p>
            </div>
            <div class="glass-dark rounded-2xl p-6 text-center">
                <div class="text-4xl font-bold gradient-text mb-2">{{ $stats['five_star_count'] }}</div>
                <p class="text-neutral-600">5-Star Reviews</p>
            </div>
        </div>
        @endif

        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($testimonials ?? [] as $testimonial)
            <div class="glass-dark rounded-2xl p-6 hover:shadow-lg transition-shadow duration-300">
                <!-- Client Info -->
                <div class="flex items-center mb-4">
                    @php
                        $clientName = $testimonial->client->fullName ?? 'Anonymous Client';
                        $initials = collect(explode(' ', $clientName))->map(fn($word) => strtoupper(substr($word, 0, 1)))->take(2)->implode('');
                        $colors = ['primary', 'info', 'success', 'warning'];
                        $colorIndex = $testimonial->id % count($colors);
                        $color = $colors[$colorIndex];
                    @endphp
                    <div class="w-12 h-12 rounded-full bg-{{ $color }}-200 flex items-center justify-center mr-4">
                        @if($testimonial->client && $testimonial->client->profilePic)
                            <img src="{{ $testimonial->client->profilePic }}" alt="{{ $clientName }}" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <span class="text-lg font-bold text-{{ $color }}-700">{{ $initials }}</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-semibold text-neutral-800">{{ $clientName }}</h3>
                        @if($testimonial->project)
                            <p class="text-sm text-neutral-600">{{ $testimonial->project->name ?? 'Project Client' }}</p>
                        @else
                            <p class="text-sm text-neutral-600">Verified Client</p>
                        @endif
                    </div>
                </div>
                
                <!-- Star Rating -->
                <div class="flex mb-4">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $testimonial->rating)
                            <svg class="w-5 h-5 text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endif
                    @endfor
                </div>
                
                <!-- Feedback Title -->
                @if($testimonial->title)
                    <h4 class="font-semibold text-neutral-800 mb-2">{{ $testimonial->title }}</h4>
                @endif
                
                <!-- Feedback Message -->
                <p class="text-neutral-600 italic leading-relaxed">
                    @php
                        // Clean up the message - extract main feedback if it's structured
                        $message = $testimonial->message;
                        // If message contains structured format, try to extract the detailed feedback
                        if (str_contains($message, 'Detailed Feedback:')) {
                            preg_match('/Detailed Feedback:\s*(.+?)(?:\n\n|Would recommend|$)/s', $message, $matches);
                            $message = trim($matches[1] ?? $message);
                        }
                        // Limit to 200 characters for card display
                        $displayMessage = strlen($message) > 200 ? substr($message, 0, 200) . '...' : $message;
                    @endphp
                    "{{ $displayMessage }}"
                </p>
                
                <!-- Date & Type Badge -->
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-neutral-100">
                    <span class="text-xs text-neutral-500">{{ $testimonial->created_at->diffForHumans() }}</span>
                    @if($testimonial->type === 'service')
                        <span class="text-xs px-2 py-1 bg-primary-100 text-primary-700 rounded-full">Service Review</span>
                    @else
                        <span class="text-xs px-2 py-1 bg-info-100 text-info-700 rounded-full">General Feedback</span>
                    @endif
                </div>
            </div>
            @empty
            <!-- Empty State -->
            <div class="col-span-full text-center py-12">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-neutral-100 flex items-center justify-center">
                    <svg class="w-12 h-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-neutral-700 mb-2">No Testimonials Yet</h3>
                <p class="text-neutral-500 mb-6">Be the first to share your experience with us!</p>
                <a href="{{ route('contact') }}" class="btn-primary inline-flex items-center px-6 py-3">
                    Start Your Project
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
            @endforelse
        </div>

        <!-- CTA Section -->
        @if(isset($testimonials) && $testimonials->count() > 0)
        <div class="text-center mt-16">
            <div class="glass-dark rounded-2xl p-8 max-w-2xl mx-auto">
                <h2 class="heading-serif text-2xl md:text-3xl mb-4">Ready to Join Our Happy Clients?</h2>
                <p class="text-neutral-600 mb-6">Start your project with us today and experience the difference.</p>
                <a href="{{ route('contact') }}" class="btn-primary inline-flex items-center px-8 py-4">
                    Start Your Project
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

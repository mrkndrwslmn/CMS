@extends('client.layouts.app')

@section('title', 'Leave Feedback')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-10">
        <div class="flex items-center mb-4">
            <a href="{{ route('client.feedback') }}" class="text-primary-600 hover:text-primary-700 mr-4 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-primary-700">Leave Feedback</h1>
        </div>
        <p class="text-neutral-600 mt-2">Share your experience and help us improve our services</p>
    </div>

    <!-- Project Information -->
    <div class="bg-white rounded-xl border border-neutral-200 p-6 mb-8">
        <h2 class="text-xl font-bold text-primary-700 mb-6">Project Details</h2>
        <div class="flex items-start space-x-6">
            <div class="flex-1">
                <h3 class="text-lg font-bold text-primary-700 mb-2">{{ $project->title }}</h3>
                <p class="text-neutral-600 text-sm mb-4 leading-relaxed">{{ $project->description }}</p>
                
                <div class="space-y-3 text-sm">
                    @if($selectedAdiutor)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span class="text-neutral-600">Adiutor: <span class="font-semibold text-primary-700">{{ $selectedAdiutor->adiutor->fullName }}</span></span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-neutral-600">Completed: <span class="font-semibold text-primary-700">{{ $project->updated_at->format('M j, Y') }}</span></span>
                    </div>

                    @if($project->budget)
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            <span class="text-neutral-600">Budget: <span class="font-semibold text-primary-700">₱{{ number_format($project->budget, 2) }}</span></span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="px-3 py-1 rounded-lg text-xs font-bold bg-success-50 text-success-700 border border-success-200">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Adiutor Avatar -->
            @if($selectedAdiutor)
                <div class="flex-shrink-0">
                    <img src="{{ $selectedAdiutor->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($selectedAdiutor->adiutor->fullName) . '&background=4F46E5&color=fff' }}" 
                         alt="{{ $selectedAdiutor->adiutor->fullName }}" 
                         class="w-16 h-16 rounded-full border-2 border-primary-200">
                </div>
            @endif
        </div>
    </div>

    <!-- Feedback Form -->
    <form action="{{ route('client.feedback.store', $project->id) }}" method="POST" class="space-y-8">
        @csrf
        <input type="hidden" name="adiutor_id" value="{{ $selectedAdiutor->adiutor_id }}">

        <!-- Overall Rating -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-xl font-bold text-primary-700 mb-6">Overall Rating</h2>
            
            <div class="mb-6">
                <label class="block text-sm font-semibold text-neutral-700 mb-3">
                    How would you rate your overall experience? <span class="text-error-600">*</span>
                </label>
                <div class="flex items-center space-x-2">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" class="sr-only rating-input" {{ old('rating') == $i ? 'checked' : '' }} required>
                            <svg class="w-8 h-8 text-neutral-300 hover:text-warning-400 transition-colors rating-star" 
                                 fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </label>
                    @endfor
                </div>
                @error('rating')
                    <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Detailed Ratings -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-xl font-bold text-primary-700 mb-6">Detailed Ratings</h2>
            
            <div class="space-y-6">
                <!-- Quality Rating -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-3">
                        Quality of Work <span class="text-error-600">*</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="quality_rating" value="{{ $i }}" class="sr-only rating-input" {{ old('quality_rating') == $i ? 'checked' : '' }} required>
                                <svg class="w-6 h-6 text-neutral-300 hover:text-warning-400 transition-colors rating-star" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    @error('quality_rating')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Communication Rating -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-3">
                        Communication <span class="text-error-600">*</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="communication_rating" value="{{ $i }}" class="sr-only rating-input" {{ old('communication_rating') == $i ? 'checked' : '' }} required>
                                <svg class="w-6 h-6 text-neutral-300 hover:text-warning-400 transition-colors rating-star" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    @error('communication_rating')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Timeliness Rating -->
                <div>
                    <label class="block text-sm font-semibold text-neutral-700 mb-3">
                        Timeliness <span class="text-error-600">*</span>
                    </label>
                    <div class="flex items-center space-x-2">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="timeliness_rating" value="{{ $i }}" class="sr-only rating-input" {{ old('timeliness_rating') == $i ? 'checked' : '' }} required>
                                <svg class="w-6 h-6 text-neutral-300 hover:text-warning-400 transition-colors rating-star" 
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </label>
                        @endfor
                    </div>
                    @error('timeliness_rating')
                        <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Written Feedback -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-xl font-bold text-primary-700 mb-6">Written Feedback</h2>
            
            <div>
                <label for="comment" class="block text-sm font-semibold text-neutral-700 mb-2">
                    Please share your detailed feedback <span class="text-error-600">*</span>
                </label>
                <textarea id="comment" 
                          name="comment" 
                          rows="6" 
                          class="w-full px-4 py-3 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('comment') border-error-300 @enderror" 
                          placeholder="Tell us about your experience working with this Adiutor. What went well? What could be improved?"
                          required>{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-error-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Additional Options -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <h2 class="text-xl font-bold text-primary-700 mb-6">Additional Options</h2>
            
            <div class="space-y-4">
                <!-- Would Recommend -->
                <div class="flex items-center">
                    <input id="would_recommend" 
                           name="would_recommend" 
                           type="checkbox" 
                           value="1" 
                           {{ old('would_recommend') ? 'checked' : '' }}
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded">
                    <label for="would_recommend" class="ml-2 block text-sm text-neutral-900">
                        I would recommend this Adiutor to others
                    </label>
                </div>

                <!-- Public Review -->
                <div class="flex items-center">
                    <input id="public" 
                           name="public" 
                           type="checkbox" 
                           value="1" 
                           {{ old('public') ? 'checked' : '' }}
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-neutral-300 rounded">
                    <label for="public" class="ml-2 block text-sm text-neutral-900">
                        Make this review public (it will be visible on the Adiutor's profile)
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white rounded-xl border border-neutral-200 p-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('client.feedback') }}" class="px-6 py-3 border-2 border-neutral-300 text-neutral-700 font-semibold rounded-lg hover:bg-neutral-50 transition-colors">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    Submit Feedback
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle star rating interactions
    const ratingGroups = document.querySelectorAll('input[type="radio"][name*="rating"]');
    
    ratingGroups.forEach(input => {
        input.addEventListener('change', function() {
            const name = this.name;
            const value = parseInt(this.value);
            const stars = document.querySelectorAll(`input[name="${name}"] + .rating-star`);
            
            stars.forEach((star, index) => {
                if (index < value) {
                    star.classList.remove('text-neutral-300');
                    star.classList.add('text-warning-400');
                } else {
                    star.classList.remove('text-warning-400');
                    star.classList.add('text-neutral-300');
                }
            });
        });
    });

    // Initialize existing ratings
    const checkedInputs = document.querySelectorAll('input[type="radio"]:checked');
    checkedInputs.forEach(input => {
        input.dispatchEvent(new Event('change'));
    });

    // Handle star hover effects
    const ratingContainers = document.querySelectorAll('div:has(input[type="radio"][name*="rating"])');
    
    ratingContainers.forEach(container => {
        const stars = container.querySelectorAll('.rating-star');
        const inputs = container.querySelectorAll('input[type="radio"]');
        
        stars.forEach((star, index) => {
            star.addEventListener('mouseenter', function() {
                for (let i = 0; i <= index; i++) {
                    stars[i].classList.remove('text-neutral-300');
                    stars[i].classList.add('text-warning-400');
                }
                for (let i = index + 1; i < stars.length; i++) {
                    stars[i].classList.remove('text-warning-400');
                    stars[i].classList.add('text-neutral-300');
                }
            });
        });
        
        container.addEventListener('mouseleave', function() {
            const checkedInput = container.querySelector('input[type="radio"]:checked');
            if (checkedInput) {
                checkedInput.dispatchEvent(new Event('change'));
            } else {
                stars.forEach(star => {
                    star.classList.remove('text-warning-400');
                    star.classList.add('text-neutral-300');
                });
            }
        });
    });
});
</script>
@endsection
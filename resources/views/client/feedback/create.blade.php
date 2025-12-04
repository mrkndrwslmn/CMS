@extends('client.layouts.app')

@section('title', 'Leave Feedback')

@section('content')
<div class="max-w-4xl mx-auto px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <x-ui.breadcrumb :items="[
        ['label' => 'Dashboard', 'route' => 'client.dashboard', 'icon' => 'home'],
        ['label' => 'Feedback', 'route' => 'client.feedback', 'icon' => 'message-square'],
        ['label' => 'Leave Feedback', 'icon' => 'star'],
    ]" />

    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-semibold text-neutral-800">Rate This Project</h1>
        <p class="text-sm text-neutral-500 mt-1">Share your feedback to help us improve our services and recognize great work</p>
    </div>

    <!-- Project Information -->
    <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6 mb-8">
        <h2 class="text-lg font-medium text-neutral-700 mb-6">Project Details</h2>
        <div class="flex items-start space-x-6">
            <div class="flex-1">
                <h3 class="text-base font-medium text-neutral-700 mb-2">{{ $project->title }}</h3>
                <p class="text-sm text-neutral-500 mb-4 leading-relaxed">{{ $project->description }}</p>
                
                <div class="space-y-3 text-sm">
                    @if($project->assignments->count() > 0)
                        <div class="flex items-center gap-2">
                            <x-lucide-users class="w-4 h-4 text-neutral-400" />
                            <span class="text-neutral-500">Team: 
                                @foreach($project->assignments as $index => $assignment)
                                    <span class="font-medium text-neutral-700">{{ $assignment->adiutor->fullName }}</span>{{ $index < $project->assignments->count() - 1 ? ', ' : '' }}
                                @endforeach
                            </span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <x-lucide-calendar class="w-4 h-4 text-neutral-400" />
                        <span class="text-neutral-500">Completed: <span class="font-medium text-neutral-700">{{ $project->updated_at->format('M j, Y') }}</span></span>
                    </div>

                    @if($project->budget)
                        <div class="flex items-center gap-2">
                            <x-lucide-peso-sign class="w-4 h-4 text-neutral-400" />
                            <span class="text-neutral-500">Budget: <span class="font-medium text-neutral-700">₱{{ number_format($project->budget, 2) }}</span></span>
                        </div>
                    @endif

                    <div class="flex items-center gap-2">
                        <x-lucide-check-circle class="w-4 h-4 text-success-500" />
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-700">Completed</span>
                    </div>
                </div>
            </div>

            <!-- Team Avatars -->
            @if($project->assignments->count() > 0)
                <div class="flex-shrink-0 flex -space-x-2">
                    @foreach($project->assignments->take(3) as $assignment)
                        <img src="{{ $assignment->adiutor->profilePic ?? 'https://ui-avatars.com/api/?name=' . urlencode($assignment->adiutor->fullName) . '&background=2563EB&color=fff' }}" 
                             alt="{{ $assignment->adiutor->fullName }}" 
                             class="w-10 h-10 rounded-full border-2 border-white"
                             title="{{ $assignment->adiutor->fullName }}">
                    @endforeach
                    @if($project->assignments->count() > 3)
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-neutral-100 flex items-center justify-center">
                            <span class="text-xs font-medium text-neutral-600">+{{ $project->assignments->count() - 3 }}</span>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- Feedback Form -->
    <form action="{{ route('client.feedback.store', $project->id) }}" method="POST" class="space-y-8">
        @csrf

        <!-- Overall Rating -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h2 class="text-lg font-medium text-neutral-700 mb-6">Overall Rating</h2>
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-neutral-700 mb-3">
                    How would you rate your overall experience? <span class="text-error-500">*</span>
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
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h2 class="text-lg font-medium text-neutral-700 mb-6">Detailed Ratings</h2>
            
            <div class="space-y-6">
                <!-- Quality Rating -->
                <div>
                    <label class="block text-sm font-medium text-neutral-700 mb-3">
                        Quality of Work <span class="text-error-500">*</span>
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
                    <label class="block text-sm font-medium text-neutral-700 mb-3">
                        Communication <span class="text-error-500">*</span>
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
                    <label class="block text-sm font-medium text-neutral-700 mb-3">
                        Timeliness <span class="text-error-500">*</span>
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
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h2 class="text-lg font-medium text-neutral-700 mb-6">Written Feedback</h2>
            
            <div>
                <label for="comment" class="block text-sm font-medium text-neutral-700 mb-2">
                    Please share your detailed feedback about this project <span class="text-error-500">*</span>
                </label>
                <textarea id="comment" 
                          name="comment" 
                          rows="6" 
                          maxlength="1000"
                          class="w-full px-4 py-2.5 text-sm text-neutral-700 bg-white border border-neutral-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all @error('comment') border-error-300 @enderror" 
                          placeholder="Tell us about your experience with this project. What went well? What could be improved? How was the team's performance?"
                          required>{{ old('comment') }}</textarea>
                <div class="flex justify-between items-center mt-1">
                    @error('comment')
                        <p class="text-sm text-error-600">{{ $message }}</p>
                    @else
                        <p class="text-xs text-neutral-400">Minimum 10 characters</p>
                    @enderror
                    <p class="text-xs text-neutral-400">
                        <span id="commentCharCount">{{ strlen(old('comment', '')) }}</span>/1000
                    </p>
                </div>
            </div>
        </div>

        <!-- Additional Options -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <h2 class="text-lg font-medium text-neutral-700 mb-6">Additional Options</h2>
            
            <div class="space-y-4">
                <!-- Would Recommend -->
                <div class="flex items-center">
                    <input id="would_recommend" 
                           name="would_recommend" 
                           type="checkbox" 
                           value="1" 
                           {{ old('would_recommend') ? 'checked' : '' }}
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500/20 border-neutral-200 rounded">
                    <label for="would_recommend" class="ml-2 block text-sm text-neutral-600">
                        I would work with this team again
                    </label>
                </div>

                <!-- Public Review -->
                <div class="flex items-center">
                    <input id="public" 
                           name="public" 
                           type="checkbox" 
                           value="1" 
                           {{ old('public') ? 'checked' : '' }}
                           class="h-4 w-4 text-primary-600 focus:ring-primary-500/20 border-neutral-200 rounded">
                    <label for="public" class="ml-2 block text-sm text-neutral-600">
                        Make this review public (visible on team member profiles)
                    </label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="bg-white rounded-2xl border border-neutral-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <a href="{{ route('client.feedback') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-neutral-700 text-sm font-medium rounded-lg border border-neutral-200 shadow-sm hover:bg-neutral-50 hover:shadow-md transition-all">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-primary-700 hover:shadow-md transition-all">
                    <x-lucide-star class="w-4 h-4" />
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

    // Character counter for comment textarea
    const commentTextarea = document.getElementById('comment');
    const charCountSpan = document.getElementById('commentCharCount');
    
    if (commentTextarea && charCountSpan) {
        commentTextarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            charCountSpan.textContent = currentLength;
            
            // Visual feedback when approaching limit
            if (currentLength > 900) {
                charCountSpan.classList.add('text-warning-600');
                charCountSpan.classList.remove('text-error-600');
            } else if (currentLength >= 1000) {
                charCountSpan.classList.add('text-error-600');
                charCountSpan.classList.remove('text-warning-600');
            } else {
                charCountSpan.classList.remove('text-warning-600', 'text-error-600');
            }
        });
    }
});
</script>
@endsection
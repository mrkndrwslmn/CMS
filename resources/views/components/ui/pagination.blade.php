@props(['paginator', 'simple' => false])

@if ($paginator->hasPages())
    @php
        $elements = $paginator->getUrlRange(1, $paginator->lastPage());
        
        // Build proper elements array like Laravel pagination does
        $window = 3; // Number of pages to show on each side of current page
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        
        // Calculate the start and end of the window
        $start = max(1, $currentPage - $window);
        $end = min($lastPage, $currentPage + $window);
        
        // Adjust if we're near the beginning or end
        if ($currentPage <= $window) {
            $end = min($lastPage, ($window * 2) + 1);
        }
        if ($currentPage > $lastPage - $window) {
            $start = max(1, $lastPage - ($window * 2));
        }
        
        $paginationElements = [];
        
        // Add first page if not in window
        if ($start > 1) {
            $paginationElements[] = [1 => $paginator->url(1)];
            if ($start > 2) {
                $paginationElements[] = '...';
            }
        }
        
        // Add pages in window
        $windowPages = [];
        for ($i = $start; $i <= $end; $i++) {
            $windowPages[$i] = $paginator->url($i);
        }
        $paginationElements[] = $windowPages;
        
        // Add last page if not in window
        if ($end < $lastPage) {
            if ($end < $lastPage - 1) {
                $paginationElements[] = '...';
            }
            $paginationElements[] = [$lastPage => $paginator->url($lastPage)];
        }
    @endphp

    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" {{ $attributes->merge(['class' => 'flex items-center justify-between']) }}>
        {{-- Mobile View --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 bg-white border border-neutral-200 rounded-lg cursor-not-allowed">
                    <x-lucide-chevron-left class="w-4 h-4 mr-1" />
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-200">
                    <x-lucide-chevron-left class="w-4 h-4 mr-1" />
                    Previous
                </a>
            @endif

            <span class="inline-flex items-center px-3 py-2 text-sm text-neutral-500">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 transition-all duration-200">
                    Next
                    <x-lucide-chevron-right class="w-4 h-4 ml-1" />
                </a>
            @else
                <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-400 bg-white border border-neutral-200 rounded-lg cursor-not-allowed">
                    Next
                    <x-lucide-chevron-right class="w-4 h-4 ml-1" />
                </span>
            @endif
        </div>

        {{-- Desktop View --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            {{-- Results Info --}}
            <div>
                <p class="text-sm text-neutral-500">
                    Showing
                    @if ($paginator->firstItem())
                        <span class="font-medium text-neutral-700">{{ $paginator->firstItem() }}</span>
                        to
                        <span class="font-medium text-neutral-700">{{ $paginator->lastItem() }}</span>
                    @else
                        <span class="font-medium text-neutral-700">0</span>
                    @endif
                    of
                    <span class="font-medium text-neutral-700">{{ $paginator->total() }}</span>
                    results
                </p>
            </div>

            {{-- Pagination Links --}}
            <div class="flex items-center gap-1">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center justify-center w-9 h-9 text-neutral-300 bg-white border border-neutral-200 rounded-lg cursor-not-allowed" aria-disabled="true">
                        <x-lucide-chevron-left class="w-4 h-4" />
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center justify-center w-9 h-9 text-neutral-500 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 hover:text-neutral-700 transition-all duration-200" aria-label="{{ __('pagination.previous') }}">
                        <x-lucide-chevron-left class="w-4 h-4" />
                    </a>
                @endif

                @if (!$simple)
                    {{-- Pagination Elements --}}
                    @foreach ($paginationElements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="inline-flex items-center justify-center w-9 h-9 text-neutral-400 text-sm">
                                {{ $element }}
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-white bg-primary-500 border border-primary-500 rounded-lg shadow-sm">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-neutral-600 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 hover:text-neutral-800 transition-all duration-200" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @else
                    {{-- Simple pagination: just show current/total --}}
                    <span class="inline-flex items-center px-3 py-2 text-sm text-neutral-500">
                        Page <span class="font-medium text-neutral-700 mx-1">{{ $paginator->currentPage() }}</span> of <span class="font-medium text-neutral-700 mx-1">{{ $paginator->lastPage() }}</span>
                    </span>
                @endif

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center justify-center w-9 h-9 text-neutral-500 bg-white border border-neutral-200 rounded-lg hover:bg-neutral-50 hover:border-neutral-300 hover:text-neutral-700 transition-all duration-200" aria-label="{{ __('pagination.next') }}">
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </a>
                @else
                    <span class="inline-flex items-center justify-center w-9 h-9 text-neutral-300 bg-white border border-neutral-200 rounded-lg cursor-not-allowed" aria-disabled="true">
                        <x-lucide-chevron-right class="w-4 h-4" />
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif

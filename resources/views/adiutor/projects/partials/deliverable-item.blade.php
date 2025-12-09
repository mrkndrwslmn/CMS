{{-- Deliverable Item Partial for Adiutor --}}
<div class="flex items-center justify-between p-3 bg-white rounded-lg border border-neutral-100 hover:border-primary-200 transition-colors">
    <div class="flex items-center space-x-3 flex-1 min-w-0">
        <div class="flex-shrink-0 w-10 h-10 bg-neutral-50 rounded-lg flex items-center justify-center border border-neutral-200">
            @if($document->deliverable_type === 'link')
                <x-lucide-link class="w-5 h-5 text-blue-500" />
            @else
                @php
                    $extension = strtolower(pathinfo($document->fileName ?? '', PATHINFO_EXTENSION));
                    $iconConfig = match($extension) {
                        'pdf' => ['icon' => 'file-text', 'color' => 'text-red-500'],
                        'doc', 'docx' => ['icon' => 'file-text', 'color' => 'text-blue-500'],
                        'xls', 'xlsx' => ['icon' => 'file-spreadsheet', 'color' => 'text-green-500'],
                        'jpg', 'jpeg', 'png', 'gif', 'webp' => ['icon' => 'image', 'color' => 'text-purple-500'],
                        'zip', 'rar', '7z' => ['icon' => 'archive', 'color' => 'text-yellow-600'],
                        'mp4', 'mov', 'avi' => ['icon' => 'file-video', 'color' => 'text-indigo-500'],
                        'mp3', 'wav', 'ogg' => ['icon' => 'file-audio', 'color' => 'text-pink-500'],
                        default => ['icon' => 'file', 'color' => 'text-neutral-500']
                    };
                @endphp
                <x-dynamic-component :component="'lucide-' . $iconConfig['icon']" class="w-5 h-5 {{ $iconConfig['color'] }}" />
            @endif
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 flex-wrap">
                <p class="font-medium text-neutral-800 text-sm truncate">
                    {{ $document->fileName ?: ($document->description ?: 'Untitled') }}
                </p>
                @if($document->is_deliverable)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $document->deliverable_type === 'link' ? 'bg-primary-50 text-primary-700' : 'bg-neutral-50 text-neutral-700' }}">
                        {{ ucfirst($document->deliverable_type ?? 'file') }}
                    </span>
                    @if($document->is_approved)
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-50 text-success-700">
                            <x-lucide-check-circle class="w-3 h-3" />
                            Approved
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-50 text-warning-700">
                            <x-lucide-clock class="w-3 h-3" />
                            Pending
                        </span>
                    @endif
                @endif
            </div>
            <p class="text-xs text-neutral-400 flex items-center gap-2 mt-0.5 flex-wrap">
                @if($document->uploader)
                    <span>By {{ $document->uploader->fullName }}</span>
                    <span>·</span>
                @endif
                <span>{{ $document->created_at->diffForHumans() }}</span>
                @if($document->fileSize)
                    <span>·</span>
                    <span>{{ number_format($document->fileSize / 1024, 1) }} KB</span>
                @endif
            </p>
        </div>
    </div>
    <div class="flex items-center gap-2 ml-3">
        @if($document->deliverable_type === 'link' && $document->link_url)
            <a href="{{ $document->link_url }}" target="_blank" 
               class="inline-flex items-center px-2.5 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-colors">
                <x-lucide-external-link class="w-3.5 h-3.5 mr-1" />
                Open
            </a>
        @else
            <a href="{{ route('adiutor.tasks.download-file', $document->documentID) }}" 
               class="inline-flex items-center px-2.5 py-1.5 bg-primary-500 text-white text-xs font-medium rounded-lg hover:bg-primary-600 transition-colors">
                <x-lucide-download class="w-3.5 h-3.5 mr-1" />
                Download
            </a>
        @endif
    </div>
</div>

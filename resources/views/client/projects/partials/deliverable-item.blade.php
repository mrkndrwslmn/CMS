{{-- Deliverable Item Partial for Client --}}
<div class="flex items-center justify-between p-3 bg-white rounded-lg border border-neutral-100 hover:border-primary-200 transition-colors {{ $document->is_locked ?? false ? 'opacity-60' : '' }}">
    <div class="flex items-center space-x-3 flex-1 min-w-0">
        <div class="flex-shrink-0 w-10 h-10 bg-neutral-50 rounded-lg flex items-center justify-center border border-neutral-200">
            @if($document->is_locked ?? false)
                <x-lucide-lock class="w-5 h-5 text-warning-500" />
            @elseif($document->deliverable_type === 'link')
                <x-lucide-link class="w-5 h-5 text-blue-500" />
            @else
                @php
                    $extension = strtolower(pathinfo($document->fileName ?? '', PATHINFO_EXTENSION));
                    $iconConfig = match($extension) {
                        'pdf' => ['icon' => 'file-text', 'color' => 'text-error-500'],
                        'doc', 'docx' => ['icon' => 'file-text', 'color' => 'text-info-500'],
                        'xls', 'xlsx' => ['icon' => 'file-spreadsheet', 'color' => 'text-success-500'],
                        'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg' => ['icon' => 'image', 'color' => 'text-purple-500'],
                        'zip', 'rar', '7z' => ['icon' => 'archive', 'color' => 'text-warning-500'],
                        'mp4', 'mov', 'avi' => ['icon' => 'video', 'color' => 'text-pink-500'],
                        'mp3', 'wav', 'ogg' => ['icon' => 'music', 'color' => 'text-indigo-500'],
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
                @if($document->is_locked ?? false)
                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded text-xs font-medium bg-warning-100 text-warning-700">
                        <x-lucide-lock class="w-3 h-3" />
                        Locked
                    </span>
                @elseif($document->deliverable_type === 'link')
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-700">
                        Link
                    </span>
                @endif
            </div>
            <p class="text-xs text-neutral-400 flex items-center gap-2 mt-0.5 flex-wrap">
                @if($document->uploader)
                    <span>By {{ $document->uploader->fullName }}</span>
                    <span>·</span>
                @endif
                <span>{{ \Carbon\Carbon::parse($document->created_at)->diffForHumans() }}</span>
                @if($document->fileSize)
                    <span>·</span>
                    <span>{{ number_format($document->fileSize / 1024, 1) }} KB</span>
                @endif
            </p>
            @if($document->is_locked ?? false)
                <p class="text-xs text-warning-700 mt-1 font-medium flex items-center">
                    <x-lucide-alert-triangle class="w-3 h-3 mr-1" />
                    Complete milestone payment to unlock
                </p>
            @endif
        </div>
    </div>
    <div class="flex items-center gap-2 ml-3">
        @if($document->is_locked ?? false)
            <button disabled class="inline-flex items-center px-2.5 py-1.5 bg-neutral-100 text-neutral-400 text-xs font-medium rounded-lg cursor-not-allowed">
                <x-lucide-lock class="w-3.5 h-3.5 mr-1" />
                Locked
            </button>
        @elseif($document->deliverable_type === 'link' && $document->link_url)
            <a href="{{ $document->link_url }}" target="_blank" 
               class="inline-flex items-center px-2.5 py-1.5 bg-blue-500 text-white text-xs font-medium rounded-lg hover:bg-blue-600 transition-colors">
                <x-lucide-external-link class="w-3.5 h-3.5 mr-1" />
                Open
            </a>
        @else
            <a href="{{ route('client.projects.documents.download', ['projectId' => $project->id, 'documentId' => $document->documentID]) }}" 
               class="inline-flex items-center px-2.5 py-1.5 bg-primary-600 text-white text-xs font-medium rounded-lg hover:bg-primary-700 transition-colors">
                <x-lucide-download class="w-3.5 h-3.5 mr-1" />
                Download
            </a>
        @endif
    </div>
</div>

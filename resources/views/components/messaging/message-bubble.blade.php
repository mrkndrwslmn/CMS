{{--
    Message Bubble Component
    
    Usage:
    <x-messaging.message-bubble :message="$message" :currentUserId="auth()->id()" />
    
    Props:
    - message: The message object (with sender relationship loaded)
    - currentUserId: The ID of the currently authenticated user
    - showSenderInfo: Whether to show sender name/avatar (default: true)
--}}

@props([
    'message',
    'currentUserId',
    'showSenderInfo' => true,
])

@php
    $isSender = $message->sender_id === $currentUserId;
    $sender = $message->sender;
    $senderName = $sender?->fullName ?? 'Deleted User';
    $senderPic = $sender?->profilePic;
    $hasAttachments = !empty($message->attachments);
@endphp

<div class="flex {{ $isSender ? 'justify-end' : 'justify-start' }} mb-4 animate-fade-in">
    <div class="max-w-[70%]">
        {{-- Sender Info (only for received messages) --}}
        @if(!$isSender && $showSenderInfo)
            <div class="flex items-center gap-2 mb-1.5 px-1">
                @if($senderPic)
                    <img src="{{ $senderPic }}" alt="{{ $senderName }}" 
                         class="w-6 h-6 rounded-full object-cover shrink-0">
                @else
                    <div class="w-6 h-6 rounded-full bg-primary-50 flex items-center justify-center shrink-0">
                        <x-lucide-user class="w-3.5 h-3.5 text-primary-600" />
                    </div>
                @endif
                <span class="text-xs font-medium text-neutral-700">{{ $senderName }}</span>
                @if($sender?->role === 'admin')
                    <span class="px-1.5 py-0.5 text-[10px] font-semibold bg-primary-100 text-primary-700 rounded">Admin</span>
                @endif
            </div>
        @endif

        {{-- Message Bubble --}}
        <div class="rounded-2xl px-4 py-3 {{ $isSender 
            ? 'bg-primary-600 text-white rounded-br-md' 
            : 'bg-white border border-neutral-100 text-neutral-800 rounded-bl-md shadow-sm' }}">
            
            {{-- Message Text --}}
            <p class="text-sm leading-relaxed whitespace-pre-wrap break-words">{!! \App\Services\MessagingService::formatMentions($message->message, $isSender) !!}</p>
            
            {{-- Attachments --}}
            @if($hasAttachments)
                <div class="mt-2 space-y-1">
                    @foreach($message->attachments as $attachment)
                        <a href="{{ $attachment['url'] ?? '/storage/' . $attachment['path'] }}" 
                           download="{{ $attachment['name'] }}"
                           target="_blank"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium transition-colors
                                  {{ $isSender 
                                      ? 'bg-primary-500/30 text-white hover:bg-primary-500/50' 
                                      : 'bg-neutral-100 hover:bg-neutral-200 text-neutral-700' }}">
                            <x-lucide-paperclip class="w-3.5 h-3.5" />
                            <span class="truncate max-w-[150px]">{{ $attachment['name'] }}</span>
                            @if(isset($attachment['size']))
                                <span class="opacity-70">({{ number_format($attachment['size'] / 1024, 1) }}KB)</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Timestamp and Status --}}
        <div class="flex items-center gap-1.5 mt-1.5 px-1 text-xs text-neutral-400 {{ $isSender ? 'justify-end' : 'justify-start' }}">
            <x-lucide-clock class="w-3.5 h-3.5" />
            <span>{{ $message->created_at->diffForHumans() }}</span>
            
            {{-- Read status indicator (for sent messages) --}}
            @if($isSender)
                @if($message->status === 'read')
                    <x-lucide-check-check class="w-3.5 h-3.5 text-primary-400" title="Read" />
                @elseif($message->status === 'delivered')
                    <x-lucide-check-check class="w-3.5 h-3.5 text-neutral-400" title="Delivered" />
                @else
                    <x-lucide-check class="w-3.5 h-3.5 text-neutral-400" title="Sent" />
                @endif
            @endif
        </div>
    </div>
</div>

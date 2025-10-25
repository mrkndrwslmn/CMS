@extends('client.layout')

@section('title', 'My Messages')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments"></i> My Messages
        </h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Project Conversations</h6>
                    <span class="badge bg-primary">{{ $conversations->total() }} Total</span>
                </div>
                <div class="card-body">
                    @if($conversations->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No conversations yet</p>
                            <p class="text-muted">Messages will appear here when you communicate with our team about your projects.</p>
                            <a href="{{ route('client.dashboard') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($conversations as $conversation)
                                <a href="{{ route('client.messages.show', $conversation->project_id) }}" 
                                   class="list-group-item list-group-item-action {{ $conversation->unread_count_client > 0 ? 'border-start border-primary border-3' : '' }}">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <div class="d-flex w-100 justify-content-between align-items-start">
                                                <h6 class="mb-1 fw-bold">
                                                    {{ $conversation->project->title }}
                                                    @if($conversation->unread_count_client > 0)
                                                        <span class="badge bg-danger ms-2">{{ $conversation->unread_count_client }} New</span>
                                                    @endif
                                                </h6>
                                            </div>
                                            <p class="mb-1 text-muted small">
                                                <i class="fas fa-folder"></i> 
                                                <strong>Project Status:</strong> 
                                                <span class="badge bg-{{ $conversation->project->status === 'completed' ? 'success' : 'info' }}">
                                                    {{ ucfirst($conversation->project->status) }}
                                                </span>
                                            </p>
                                            @if($conversation->lastMessage)
                                                <p class="mb-0 text-truncate small" style="max-width: 500px;">
                                                    <i class="fas fa-comment"></i>
                                                    <strong>{{ $conversation->lastMessage->sender->fullName }}:</strong>
                                                    {{ Str::limit($conversation->lastMessage->message, 100) }}
                                                </p>
                                            @endif
                                        </div>
                                        <div class="col-md-4 text-end">
                                            @if($conversation->last_message_at)
                                                <small class="text-muted">
                                                    <i class="fas fa-clock"></i>
                                                    {{ $conversation->last_message_at->diffForHumans() }}
                                                </small>
                                            @endif
                                            <div class="mt-2">
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-hashtag"></i> {{ $conversation->project_id }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            {{ $conversations->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Update unread count badge in navigation
    document.addEventListener('DOMContentLoaded', async () => {
        if (window.messagingService) {
            await window.messagingService.updateUnreadCount();
        }
    });
</script>
@endsection

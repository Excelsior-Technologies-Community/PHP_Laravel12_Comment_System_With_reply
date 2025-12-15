<div class="comment-box mb-3">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h6 class="card-subtitle mb-2 text-muted">
                    {{ $comment->user->name }}
                    <small class="text-muted">• {{ $comment->created_at->diffForHumans() }}</small>
                </h6>
                
                @auth
                    @if(Auth::id() == $comment->user_id)
                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" 
                              onsubmit="return confirm('Are you sure?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @endif
                @endauth
            </div>
            
            <p class="card-text">{{ $comment->body }}</p>
            
            @auth
                <button class="btn btn-sm btn-outline-primary reply-btn" 
                        onclick="showReplyModal({{ $comment->id }})">
                    Reply
                </button>
            @endauth
        </div>
    </div>

    <!-- Replies -->
    @if($comment->replies->count() > 0)
        <div class="mt-3">
            @foreach($comment->replies as $reply)
                <div class="nested-comment mb-2">
                    @include('partials.comment', ['comment' => $reply, 'depth' => $depth + 1])
                </div>
            @endforeach
        </div>
    @endif
</div>
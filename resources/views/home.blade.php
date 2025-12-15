<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Comment System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .comment-box {
            border-left: 3px solid #ddd;
            padding-left: 15px;
            margin-left: 20px;
        }
        .reply-btn {
            font-size: 0.8rem;
            padding: 2px 10px;
        }
        .nested-comment {
            margin-left: 40px;
            border-left: 2px solid #eee;
            padding-left: 15px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3>Laravel Comment System with Replies</h3>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success m-3">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger m-3">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="card-body">
                        @auth
                            <!-- Add Comment Form -->
                            <form action="{{ route('comments.store') }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <label for="body" class="form-label">Add a comment:</label>
                                    <textarea class="form-control" id="body" name="body" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Post Comment</button>
                            </form>
                        @else
                            <div class="alert alert-info">
                                Please <a href="{{ route('login') }}">login</a> to post comments.
                            </div>
                        @endauth

                        <hr>

                        <!-- Comments List -->
                        <h4>Comments ({{ $comments->count() }})</h4>
                        
                        @if($comments->isEmpty())
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        @else
                            @foreach($comments as $comment)
                                @include('partials.comment', ['comment' => $comment, 'depth' => 0])
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reply Modal -->
    <div class="modal fade" id="replyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="replyForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reply to Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <textarea class="form-control" name="body" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Post Reply</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showReplyModal(commentId) {
            const form = document.getElementById('replyForm');
            form.action = `/comments/${commentId}/reply`;
            new bootstrap.Modal(document.getElementById('replyModal')).show();
        }
    </script>
</body>
</html>
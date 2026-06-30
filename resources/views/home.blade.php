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
            <div class="col-md-12">
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

                        <div class="row mb-4">

                            <div class="col-md-3">
                                <div class="card text-white bg-primary shadow">
                                    <div class="card-body text-center">
                                        <h5>{{ $totalComments }}</h5>
                                        <small>Total Comments</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-white bg-success shadow">
                                    <div class="card-body text-center">
                                        <h5>{{ $totalReplies }}</h5>
                                        <small>Total Replies</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-white bg-warning shadow">
                                    <div class="card-body text-center">
                                        <h5>{{ $todayComments }}</h5>
                                        <small>Today's Comments</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="card text-white bg-dark shadow">
                                    <div class="card-body text-center">
                                        <h5>{{ $totalUsers }}</h5>
                                        <small>Registered Users</small>
                                    </div>
                                </div>
                            </div>

                        </div>

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

                        <div class="row mb-4">

                            <div class="col-md-8">

                                <form action="{{ route('home') }}" method="GET">

                                    <div class="row">

                                        <div class="col-md-6">
                                            <input type="text" name="search" class="form-control"
                                                placeholder="Search comments..." value="{{ request('search') }}">
                                        </div>

                                        <div class="col-md-3">
                                            <select name="filter" class="form-select">

                                                <option value="">Newest</option>

                                                <option value="oldest" {{ request('filter') == 'oldest' ? 'selected' : '' }}>
                                                    Oldest
                                                </option>

                                                <option value="today" {{ request('filter') == 'today' ? 'selected' : '' }}>
                                                    Today's Comments
                                                </option>

                                            </select>
                                        </div>

                                        <div class="col-md-3 d-grid">

                                            <button class="btn btn-primary">

                                                Search / Filter

                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                        <!-- Comments List -->
                        <h4>

                            Comments ({{ $comments->total() }})

                        </h4>

                        @if($comments->isEmpty())
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        @else
                            @foreach($comments as $comment)
                                @include('partials.comment', ['comment' => $comment, 'depth' => 0])
                            @endforeach
                        @endif

                        <div class="mt-4 d-flex justify-content-center">

                            {{ $comments->links() }}

                        </div>
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
<div class="comment-wrapper mb-4">

    <div class="card border-0 shadow-sm rounded-4">

        <div class="card-body p-4">


            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-start">


                <div class="d-flex align-items-center">


                    <div class="avatar me-3">

                        {{ strtoupper(substr($comment->user->name,0,1)) }}

                    </div>


                    <div>

                        <h6 class="mb-1 fw-bold">

                            {{ $comment->user->name }}

                        </h6>


                        <small class="text-muted">

                            {{ $comment->created_at->diffForHumans() }}


                            @if($comment->updated_at->ne($comment->created_at))

                            <span class="badge bg-secondary ms-2">
                                Edited
                            </span>

                            @endif


                        </small>


                    </div>


                </div>



                {{-- Owner Actions --}}

                @auth

                @if(Auth::id() == $comment->user_id)


                <div class="dropdown">

                    <button class="btn btn-light btn-sm rounded-circle"
                        data-bs-toggle="dropdown">

                        ⋮

                    </button>


                    <ul class="dropdown-menu">


                        <li>

                            <button
                                class="dropdown-item"
                                onclick="showEditModal(
                                {{ $comment->id }},
                                @js($comment->body)
                                )">

                                ✏ Edit

                            </button>

                        </li>



                        <li>

                            <form
                                action="{{ route('comments.destroy',$comment) }}"
                                method="POST">

                                @csrf
                                @method('DELETE')


                                <button
                                    class="dropdown-item text-danger"
                                    onclick="return confirm('Delete comment?')">

                                    🗑 Delete

                                </button>


                            </form>


                        </li>


                    </ul>


                </div>


                @endif

                @endauth

            </div>


            {{-- Comment Text --}}

            <p class="mt-3 mb-3 text-dark">

                {{ $comment->body }}

            </p>


            {{-- Actions --}}

            <div class="d-flex gap-2 align-items-center">



                {{-- Like --}}

                @auth

                <form action="{{ route('comments.like',$comment) }}"
                    method="POST">

                    @csrf


                    <button
                        class="btn btn-sm rounded-pill btn-outline-danger">

                        ❤️
                        {{ $comment->likes->count() }}

                    </button>


                </form>


                @else


                <button
                    class="btn btn-sm rounded-pill btn-outline-danger">

                    ❤️
                    {{ $comment->likes->count() }}

                </button>


                @endauth


                {{-- Reply --}}

                @auth

                <button
                    class="btn btn-sm rounded-pill btn-outline-primary"
                    onclick="showReplyModal({{ $comment->id }})">

                    💬 Reply

                </button>


                {{-- Report --}}

                <button
                    class="btn btn-sm rounded-pill btn-outline-warning"
                    data-bs-toggle="modal"
                    data-bs-target="#reportModal{{ $comment->id }}">


                    🚩 Report

                </button>

                @endauth

            </div>


        </div>


    </div>


    {{-- Replies --}}

    @if($comment->replies->count() > 0)


    <div class="reply-container mt-3">


        @foreach($comment->replies as $reply)


        @include('partials.comment',
        [
        'comment'=>$reply,
        'depth'=>$depth+1
        ])

        @endforeach

    </div>


    @endif



</div>


{{-- Report Modal --}}

@auth

<div class="modal fade"
    id="reportModal{{ $comment->id }}">


    <div class="modal-dialog modal-dialog-centered">


        <div class="modal-content rounded-4">


            <form method="POST"
                action="{{ route('comments.report',$comment) }}">

                @csrf

                <div class="modal-header">

                    <h5>
                        🚩 Report Comment
                    </h5>

                    <button
                        class="btn-close"
                        data-bs-dismiss="modal">

                    </button>

                </div>


                <div class="modal-body">

                    <select
                        name="reason"
                        class="form-select"
                        required>


                        <option value="">
                            Choose reason
                        </option>


                        <option>
                            Spam
                        </option>


                        <option>
                            Offensive Content
                        </option>


                        <option>
                            Harassment
                        </option>


                        <option>
                            Other
                        </option>


                    </select>


                </div>


                <div class="modal-footer">


                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <button
                        class="btn btn-warning">

                        Report

                    </button>


                </div>


            </form>


        </div>


    </div>


</div>

@endauth
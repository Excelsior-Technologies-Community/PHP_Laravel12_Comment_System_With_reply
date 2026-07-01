<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CommentReport;

class CommentController extends Controller
{
    // Store main comment
    public function store(Request $request)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'body' => $request->body,
            'parent_id' => null,
        ]);

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    // Store reply to a comment
    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'parent_id' => $comment->id,
            'body' => $request->body,
        ]);

        return redirect()->back()->with('success', 'Reply added successfully!');
    }

    public function like(Comment $comment)
    {
        $user = Auth::user();


        $alreadyLiked = $comment->likes()
            ->where('user_id', $user->id)
            ->exists();


        if ($alreadyLiked) {
            $comment->likes()
                ->where('user_id', $user->id)
                ->delete();


            return back()
                ->with('success', 'Like removed');
        }


        $comment->likes()->create([
            'user_id' => $user->id
        ]);


        return back()
            ->with('success', 'Comment liked');
    }

    public function report(Request $request, Comment $comment)
    {

        $request->validate([
            'reason' => 'required|string'
        ]);


        $alreadyReported = CommentReport::where([
            'comment_id' => $comment->id,
            'user_id' => Auth::id()
        ])->exists();


        if ($alreadyReported) {
            return back()
                ->with('error', 'You already reported this comment!');
        }


        CommentReport::create([

            'comment_id' => $comment->id,

            'user_id' => Auth::id(),

            'reason' => $request->reason

        ]);


        return back()
            ->with('success', 'Comment reported successfully!');
    }

    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() != $comment->user_id) {
            return back()->with('error', 'Unauthorized action!');
        }

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return back()->with('success', 'Comment updated successfully!');
    }

    // Delete comment
    public function destroy(Comment $comment)
    {
        // Check if user owns the comment
        if (Auth::id() !== $comment->user_id) {
            return redirect()->back()->with('error', 'Unauthorized action!');
        }

        $comment->delete();
        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }
}

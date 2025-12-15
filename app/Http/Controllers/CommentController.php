<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
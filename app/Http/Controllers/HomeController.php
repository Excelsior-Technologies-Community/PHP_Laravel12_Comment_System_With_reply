<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get top-level comments (not replies) with their replies
        $comments = Comment::whereNull('parent_id')
            ->with(['user', 'replies.user', 'replies.replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('home', compact('comments'));
    }
}
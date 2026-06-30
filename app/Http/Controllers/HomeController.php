<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::whereNull('parent_id')
            ->with([
                'user',
                'replies.user',
                'replies.replies.user'
            ]);

        // Search
        if ($request->search) {
            $query->where('body', 'like', '%' . $request->search . '%');
        }

        // Filter
        if ($request->filter == 'oldest') {

            $query->oldest();

        } elseif ($request->filter == 'today') {

            $query->whereDate('created_at', today())
                ->latest();

        } else {

            $query->oldest();

        }

        // Pagination
        $comments = $query
            ->paginate(3)
            ->withQueryString();

        // Dashboard Statistics
        $totalComments = Comment::whereNull('parent_id')->count();

        $totalReplies = Comment::whereNotNull('parent_id')->count();

        $totalUsers = User::count();

        $todayComments = Comment::whereDate('created_at', today())->count();

        return view('home', compact(
            'comments',
            'totalComments',
            'totalReplies',
            'totalUsers',
            'todayComments'
        ));
    }
}
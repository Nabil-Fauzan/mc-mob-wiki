<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentVoteController extends Controller
{
    public function toggle(Request $request, Comment $comment)
    {
        $vote = $comment->votes()->where('user_id', Auth::id())->first();

        if ($vote) {
            $vote->delete();
            $voted = false;
        } else {
            $comment->votes()->create([
                'user_id' => Auth::id(),
            ]);
            $voted = true;
        }

        $count = $comment->votes()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'voted' => $voted,
                'count' => $count,
            ]);
        }

        return back()->with('success', $voted ? 'Observation upvoted.' : 'Upvote removed.');
    }
}

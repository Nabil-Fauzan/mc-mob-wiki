<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /**
     * Post a new field note/comment.
     */
    public function store(Request $request, Mob $mob)
    {
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $mob->comments()->create([
            'user_id' => Auth::id(),
            'body' => $request->body,
        ]);

        return back()->with('success', 'Field note transmitted successfully.');
    }

    /**
     * Update an existing field note.
     */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $comment->update([
            'body' => $request->body,
        ]);

        return back()->with('success', 'Field note recalibrated.');
    }
    /**
     * Delete a field note (admin only).
     */
    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Field note expunged.');
    }
}

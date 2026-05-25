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

        $body = $request->body;
        
        // Toxicity Shield
        $oracle = app(\App\Http\Controllers\OracleController::class);
        if ($oracle->checkToxicity($body)) {
            $body = '[REDACTED BY AETHER PROTOCOL]';
        }

        $comment = $mob->comments()->create([
            'user_id' => Auth::id(),
            'body' => $body,
        ]);

        // Mention Parsing
        preg_match_all('/@([A-Za-z0-9_-]+)/', $body, $matches);
        if (!empty($matches[1])) {
            $mentionedUsernames = array_unique($matches[1]);
            $usersToNotify = \App\Models\User::whereIn('minecraft_username', $mentionedUsernames)
                                            ->orWhereIn('public_slug', $mentionedUsernames)
                                            ->get()
                                            ->filter(fn($u) => $u->id !== Auth::id());
            
            foreach ($usersToNotify as $uToNotify) {
                $uToNotify->notify(new \App\Notifications\UserMentioned(
                    Auth::user()->name, 
                    $mob->name, 
                    $mob->id, 
                    $comment->id
                ));
            }
        }

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

        $body = $request->body;
        
        // Toxicity Shield
        $oracle = app(\App\Http\Controllers\OracleController::class);
        if ($oracle->checkToxicity($body)) {
            $body = '[REDACTED BY AETHER PROTOCOL]';
        }

        $comment->update([
            'body' => $body,
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

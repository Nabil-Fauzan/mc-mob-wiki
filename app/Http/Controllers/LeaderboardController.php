<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'xp'); // xp or reputation

        if ($type === 'reputation') {
            $users = User::withCount('commentVotes')
                         ->orderByDesc('comment_votes_count')
                         ->orderByDesc('xp') // Tie breaker
                         ->paginate(50);
        } else {
            $users = User::orderByDesc('xp')
                         ->orderByDesc('level')
                         ->paginate(50);
        }

        return view('pages.leaderboard', compact('users', 'type'));
    }
}

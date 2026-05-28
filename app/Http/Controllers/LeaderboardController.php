<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mob;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type', 'xp'); // xp, reputation, mobs_hp, mobs_dmg
        $difficulty = $request->query('difficulty', 'hard'); // easy, normal, hard
        
        $isMob = in_array($type, ['mobs_hp', 'mobs_dmg']);

        if ($type === 'reputation') {
            $records = User::withCount('commentVotes')
                         ->orderByDesc('comment_votes_count')
                         ->orderByDesc('xp')
                         ->paginate(50);
        } elseif ($type === 'mobs_hp') {
            $col = "health_{$difficulty}";
            $records = Mob::with('category')->orderByDesc($col)
                          ->orderBy('name')
                          ->paginate(50);
        } elseif ($type === 'mobs_dmg') {
            $col = "damage_{$difficulty}";
            $records = Mob::with('category')->orderByDesc($col)
                          ->orderBy('name')
                          ->paginate(50);
        } else {
            $records = User::orderByDesc('xp')
                         ->orderByDesc('level')
                         ->paginate(50);
        }

        return view('pages.leaderboard', compact('records', 'type', 'difficulty', 'isMob'));
    }
}

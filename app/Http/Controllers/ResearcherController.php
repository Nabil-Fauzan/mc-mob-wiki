<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ResearcherController extends Controller
{
    public function show(User $user)
    {
        // Fetch the user's bookmarked mobs
        $favorites = $user->favorite_mobs()->with(['category', 'biomes.dimension'])->latest()->get();
        
        // Fetch their recent comments
        $comments = $user->comments()->with('mob')->latest()->get();
        
        // Calculate Researcher Rank
        $totalComments = $comments->count();
        $rank = 'Novice Explorer';
        if ($totalComments >= 50) {
            $rank = 'Grandmaster Researcher';
        } elseif ($totalComments >= 20) {
            $rank = 'Mythic Beast Hunter';
        } elseif ($totalComments >= 10) {
            $rank = 'Seasoned Tracker';
        } elseif ($totalComments >= 5) {
            $rank = 'Field Researcher';
        }

        return view('researchers.show', compact('user', 'favorites', 'comments', 'rank', 'totalComments'));
    }
}

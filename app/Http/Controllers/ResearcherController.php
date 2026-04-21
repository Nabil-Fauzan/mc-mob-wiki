<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ResearcherController extends Controller
{
    public function show(User $user)
    {
        $viewer = Auth::user();
        $canViewPrivateProfile = $viewer && ($viewer->id === $user->id || $viewer->is_admin);

        abort_unless($user->profile_is_public || $canViewPrivateProfile, 404);

        $favorites = $user->favorite_mobs()->with(['category', 'biomes.dimension'])->latest()->get();
        $comments = $user->comments()
            ->with(['mob', 'votes', 'user'])
            ->withCount('votes')
            ->latest()
            ->get();

        $totalComments = $comments->count();
        $totalFavorites = $favorites->count();
        $reputation = (int) $comments->sum('votes_count');
        $averageCommentScore = $totalComments > 0 ? round($reputation / $totalComments, 1) : 0;

        $rank = 'Novice Explorer';
        if ($reputation >= 100 || $totalComments >= 50) {
            $rank = 'Grandmaster Researcher';
        } elseif ($reputation >= 40 || $totalComments >= 20) {
            $rank = 'Mythic Beast Hunter';
        } elseif ($reputation >= 15 || $totalComments >= 10) {
            $rank = 'Seasoned Tracker';
        } elseif ($reputation >= 5 || $totalComments >= 5) {
            $rank = 'Field Researcher';
        }

        $roles = collect([
            $user->is_admin ? ['label' => 'Master Admin', 'tone' => 'red'] : null,
            $reputation >= 25 ? ['label' => 'Trusted Analyst', 'tone' => 'amber'] : null,
            $totalComments >= 10 ? ['label' => 'Field Researcher', 'tone' => 'sky'] : null,
            $totalFavorites >= 10 ? ['label' => 'Collector', 'tone' => 'rose'] : null,
            $user->minecraft_username ? ['label' => 'Minecraft Verified', 'tone' => 'emerald'] : null,
        ])->filter()->values();

        $achievements = collect([
            [
                'title' => 'First Observation',
                'description' => 'Posted the first field note.',
                'unlocked' => $totalComments >= 1,
            ],
            [
                'title' => 'Community Signal',
                'description' => 'Earned 5 reputation from comment upvotes.',
                'unlocked' => $reputation >= 5,
            ],
            [
                'title' => 'Seasoned Tracker',
                'description' => 'Recorded at least 10 field notes.',
                'unlocked' => $totalComments >= 10,
            ],
            [
                'title' => 'Curator',
                'description' => 'Saved 10 favorite entities.',
                'unlocked' => $totalFavorites >= 10,
            ],
            [
                'title' => 'Respected Voice',
                'description' => 'Reached 25 total reputation.',
                'unlocked' => $reputation >= 25,
            ],
        ]);

        $stats = [
            'favorites_count' => $totalFavorites,
            'comments_count' => $totalComments,
            'reputation' => $reputation,
            'avg_comment_score' => $averageCommentScore,
            'joined_at' => $user->created_at,
        ];

        return view('researchers.show', compact(
            'user',
            'favorites',
            'comments',
            'rank',
            'roles',
            'achievements',
            'stats',
            'canViewPrivateProfile'
        ));
    }
}

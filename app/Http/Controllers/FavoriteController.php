<?php

namespace App\Http\Controllers;

use App\Models\Mob;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Toggle the favorite status of a mob for the authenticated user.
     */
    public function toggle(Mob $mob)
    {
        $user = auth()->user();
        
        $result = $user->favorite_mobs()->toggle($mob->id);
        $isFavorited = count($result['attached']) > 0;
        
        return response()->json([
            'favorited' => $isFavorited,
            'count' => $mob->favoritedBy()->count(),
            'message' => $isFavorited ? 'Mob added to favorites.' : 'Mob removed from favorites.'
        ]);
    }
}

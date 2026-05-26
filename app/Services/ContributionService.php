<?php

namespace App\Services;

use App\Models\MobContribution;
use App\Models\MobRevision;
use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;

class ContributionService
{
    /**
     * Approve a contribution, update the mob, track revision, and award user.
     */
    public function approve(MobContribution $contribution, ?Achievement $contributorAchievement = null)
    {
        $mob = $contribution->mob;
        $field = $contribution->field;
        $oldValue = $mob->$field;

        // Track revision
        MobRevision::create([
            'mob_id' => $mob->id,
            'user_id' => $contribution->user_id,
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $contribution->proposed_value,
        ]);

        // Update Mob
        $mob->$field = $contribution->proposed_value;
        $mob->save();

        // Update Contribution
        $contribution->update([
            'status' => 'approved',
            'admin_id' => Auth::id()
        ]);

        // Award XP and Gamification
        $this->awardContributor($contribution->user, $contributorAchievement);
    }

    /**
     * Award XP and unlock achievements for a successful contribution.
     */
    private function awardContributor($user, ?Achievement $contributorAchievement = null)
    {
        $xpToAward = 50; // Default
        if ($user->level <= 15) {
            $xpToAward = 250;
        } elseif ($user->level <= 30) {
            $xpToAward = 100;
        }
        
        $user->addXp($xpToAward);
        
        if (!$contributorAchievement) {
            $contributorAchievement = Achievement::where('name', 'Contributor')->first();
        }

        if ($contributorAchievement && !$user->achievements()->where('achievement_id', $contributorAchievement->id)->exists()) {
            $user->achievements()->attach($contributorAchievement->id, ['unlocked_at' => now()]);
        }
    }
}
